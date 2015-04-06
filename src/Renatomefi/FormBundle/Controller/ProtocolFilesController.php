<?php

namespace Renatomefi\FormBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Renatomefi\FormBundle\Document\ProtocolFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class ProtocolFilesController
 * @package Renatomefi\FormBundle\Controller
 */
class ProtocolFilesController extends FOSRestController
{
    /**
     * @param $protocolId
     * @param $fileId
     * @return ProtocolFile
     */
    protected function getFile($protocolId, $fileId)
    {
        $documentManager = $this->get('doctrine_mongodb')->getManager();
        $upload = $documentManager->getRepository('FormBundle:ProtocolFile')->find($fileId);

        if ($upload && $upload->getProtocol()->getId() === $protocolId) {
            return $upload;
        }

        throw $this->createNotFoundException("No file with id '$fileId' found for protocol '$protocolId'");
    }

    /**
     * @Route("/delete/protocol/{protocolId}/file/{fileId}")
     * @Method({"DELETE"})
     * @param $protocolId
     * @param $fileId
     * @return \Doctrine\Common\Collections\Collection
     */
    public function deleteUploadAction($protocolId, $fileId)
    {
        $documentManager = $this->get('doctrine_mongodb')->getManager();

        $upload = $this->getFile($protocolId, $fileId);

        $documentManager->remove($upload);
        $documentManager->flush();
        $documentManager->clear();

        return $documentManager->getRepository('FormBundle:Protocol')->find($protocolId)->getFile();
    }

    /**
     * @Route("/protocol/{protocolId}/file/{fileId}")
     * @Method({"PATCH"})
     * @param Request $request
     * @param $protocolId
     * @param $fileId
     * @return \Doctrine\Common\Collections\Collection
     */
    public function patchUploadAction(Request $request, $protocolId, $fileId)
    {
        $documentManager = $this->get('doctrine_mongodb')->getManager();

        $upload = $this->getFile($protocolId, $fileId);

        if ($request->get('title')) {
            $upload->setTitle($request->get('title'));
        }
        if ($request->get('description')) {
            $upload->setDescription($request->get('description'));
        }

        $documentManager->persist($upload);
        $documentManager->flush();
        $documentManager->clear();

        return $upload;
    }

    /**
     * @Route("/upload/{protocolId}")
     * @Method({"POST"})
     * @param Request $request
     * @param $protocolId
     * @return null|ProtocolFile
     */
    public function postUploadAction(Request $request, $protocolId)
    {
        $protocolDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Protocol');
        $protocol = $protocolDM->find($protocolId);

        if (!$protocol) {
            throw $this->createNotFoundException("No Protocol found with id: \"$protocolId\"");
        }

        if ($request->files->get('file')) {
            /** @var $upload \Symfony\Component\HttpFoundation\File\UploadedFile */
            $upload = $request->files->get('file');

            $document = new ProtocolFile();
            $document->setFile($upload->getPathname());
            $document->setFilename($upload->getClientOriginalName());
            $document->setMimeType($upload->getClientMimeType());
            $document->setProtocol($protocol);

            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            $dm->persist($document);
            $dm->flush();
            $dm->clear();

            return $protocolDM->find($protocol->getId())->getFile();
        } else {
            $view = $this->view(
                ['code' => Response::HTTP_BAD_REQUEST, 'message' => 'You must provide a "file".'],
                Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }

    }

    /**
     * @Route("/get/{id}")
     * @Method({"GET"})
     * @param $id
     * @return Response
     */
    public function getAction($id)
    {
        $upload = $this->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('FormBundle:ProtocolFile')
            ->find($id);

        if (null === $upload) {
            throw $this->createNotFoundException(sprintf('Upload with id "%s" could not be found', $id));
        }

        $response = new Response(
            $upload->getFile()->getBytes(), Response::HTTP_OK,
            ['Content-Type' => $upload->getMimeType()]
        );

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $upload->getFilename(),
            $upload->getId()
        );

        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

}