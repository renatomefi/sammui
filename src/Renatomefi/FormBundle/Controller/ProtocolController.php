<?php

namespace Renatomefi\FormBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Renatomefi\FormBundle\Document\Protocol;
use Renatomefi\FormBundle\Document\ProtocolComment;
use Renatomefi\FormBundle\Document\ProtocolUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ProtocolController
 * @package Renatomefi\FormBundle\Controller
 */
class ProtocolController extends FOSRestController
{

    /**
     * @param $id
     * @param bool $notFoundException
     * @throws NotFoundHttpException
     * @return Protocol
     */
    protected function getProtocol($id, $notFoundException = true)
    {
        $formsDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Protocol');

        $result = $formsDM->findOneById($id);

        if (!$result && true === $notFoundException)
            throw $this->createNotFoundException("No Protocol found with id: \"$id\"");

        return $result;
    }

    /**
     * @param null $id
     * @param bool $notFoundException
     * @return mixed
     */
    public function getForm($id = null, $notFoundException = false)
    {
        $formsDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Form');

        if (null == $id) {
            $form = $formsDM->findAll();
            $exception = 'No Forms found';
        } else {
            $form = $formsDM->findOneById($id);
            $exception = 'Form not found: ' . $id;
        }

        if (TRUE === $notFoundException && !$form) {
            throw $this->createNotFoundException($exception);
        }

        return $form;
    }

    /**
     * @param $formId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getFormAction($formId)
    {
        $form = $this->getForm($formId, true);

        $protocolDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Protocol');

        $result = $protocolDM
            ->createQueryBuilder()
            ->hydrate(false)
            ->field('form')->references($form)
            ->field('id')->hydrate(true)
            ->getQuery()
            ->execute()
            ->toArray();

        if (!$result)
            throw $this->createNotFoundException("No protocols found for form: \"$formId\"");

        return $result;
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($id)
    {
        return $this->getProtocol($id);
    }

    /**
     * @param $formId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction($formId)
    {
        $form = $this->getForm($formId, true);

        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = new Protocol();
        $protocol->setForm($form);

        $dm->persist($protocol);
        $dm->flush();

        return $protocol;
    }

    /**
     * @param $protocolId
     * @param $userName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchAddUserAction($protocolId, $userName)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = $this->getProtocol($protocolId);

        $userDm = $dm->getRepository('UserBundle:User');
        $user = $userDm->findOneByUsername($userName);

        if (null === $user) {
            if (!$protocol->getOneNonUser($userName)) {
                $nonUser = new ProtocolUser();
                $nonUser->setUsername($userName);
                $protocol->addNonUser($nonUser);
            }
        } else {
            if (!$protocol->getUser()->contains($user))
                $protocol->addUser($user);
        }

        $dm->persist($protocol);
        $dm->flush();
        $dm->clear();
        unset($protocol);

        $protocol = $this->getProtocol($protocolId);

        return ['user' => $protocol->getUser(), 'nonUser' => $protocol->getNonUser()];
    }

    /**
     * @param $protocolId
     * @param $userName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchRemoveUserAction($protocolId, $userName)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = $this->getProtocol($protocolId);

        if ($nonUser = $protocol->getOneNonUser($userName))
            $protocol->removeNonUser($nonUser);

        if ($user = $protocol->getOneUser($userName))
            $protocol->removeUser($user);

        $dm->persist($protocol);
        $dm->flush();
        $dm->clear();
        unset($protocol);

        $protocol = $this->getProtocol($protocolId);

        return ['user' => $protocol->getUser(), 'nonUser' => $protocol->getNonUser()];
    }

    /**
     * @param Request $request
     * @param $protocolId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchAddCommentAction(Request $request, $protocolId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = $this->getProtocol($protocolId);

        $comment = new ProtocolComment();
        $comment->setBody($request->get('body'));

        $protocol->addComment($comment);

        $dm->persist($protocol);
        $dm->flush();
        $dm->clear();

        return $this->getProtocol($protocolId)->getComment()->toArray();
    }

    /**
     * @param $protocolId
     * @param $commentId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchRemoveCommentAction($protocolId, $commentId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $protocol = $this->getProtocol($protocolId);

        $comment = $protocol->getOneComment($commentId);

        if (!$comment)
            throw $this->createNotFoundException("Comment '$commentId' not found in protocol '$protocolId'");

        $protocol->removeComment($comment);

        $dm->persist($protocol);
        $dm->flush();
        $dm->clear();

        return $this->getProtocol($protocolId)->getComment()->toArray();
    }
}
