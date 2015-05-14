<?php

namespace Renatomefi\FormBundle\Controller;

use Renatomefi\FormBundle\Document\Protocol;
use Renatomefi\FormBundle\Document\ProtocolComment;
use Renatomefi\FormBundle\Document\ProtocolFieldValue;
use Renatomefi\FormBundle\Document\ProtocolPublish;
use Renatomefi\FormBundle\Document\ProtocolUser;
use Renatomefi\UserBundle\Document\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProtocolExportController
 * @package Renatomefi\FormBundle\Controller
 */
class ProtocolExportController extends Controller
{

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @return Protocol
     */
    protected function getProtocol($id)
    {
        $formsDM = $this->get('doctrine_mongodb')->getRepository('FormBundle:Protocol');

        $result = $formsDM->findOneById($id);

        if (!$result)
            throw $this->createNotFoundException("No Protocol found with id: \"$id\"");

        return $result;
    }

    /**
     * @Route("/excel/{protocolId}")
     * @Method("GET")
     *
     * @param $protocolId
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \PHPExcel_Exception
     */
    public function excelAction($protocolId)
    {
        $protocol = $this->getProtocol($protocolId);

        // ask the service for a Excel5
        $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject();

        $objPHPExcel->getProperties()->setCreator("sammui")
            ->setLastModifiedBy("sammui")
            ->setTitle($protocol->getForm()->getName() . ': ' . $protocol->getId());

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('info');

        $objPHPExcel->getActiveSheet()->setCellValue('A1', $protocol->getForm()->getName());
        $objPHPExcel->getActiveSheet()->setCellValue('B1', $protocol->getForm()->getId());

        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'protocol');
        $objPHPExcel->getActiveSheet()->setCellValue('B2', $protocol->getId());

        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'form-protocol-first_save_date');
        $objPHPExcel->getActiveSheet()->setCellValue('B3', $protocol->getFirstSaveDate());

        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'form-protocol-last_save_date');
        $objPHPExcel->getActiveSheet()->setCellValue('B4', $protocol->getLastSaveDate());

        $objPHPExcel->getActiveSheet()->setCellValue('A5', 'publish');
        $objPHPExcel->getActiveSheet()->setCellValue('B5', $protocol->isLocked() ? 'published' : 'not published');

        $publishes = $protocol->getPublish();
        $currentLine = 6;
        for ($i = 0; $i < count($publishes); $i++) {
            /** @var ProtocolPublish $publish */
            $publish = $publishes[$i];
            $position = $i + $currentLine;

            $objPHPExcel->getActiveSheet()->setCellValue('B' . $position, $publish->getLocked());
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $position, $publish->getCreatedAt());
            if ($publish->getUser())
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $position, $publish->getUser()->getUsername());
        }
        $currentLine += count($publishes);

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $currentLine, 'form-filling-page-comments');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $currentLine, count($protocol->getComment()));
        $currentLine++;

        $comments = $protocol->getComment();
        for ($i = 0; $i < count($comments); $i++) {
            /** @var ProtocolComment $comment */
            $comment = $comments[$i];
            $position = $i + $currentLine;

            $objPHPExcel->getActiveSheet()->setCellValue('B' . $position, $comment->getCreatedAt());
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $position, $comment->getBody());
        }

        $currentLine += count($comments);

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $currentLine, 'form-filling-page-users');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $currentLine, count($protocol->getUser()));
        $currentLine++;

        $users = $protocol->getUser();
        for ($i = 0; $i < count($users); $i++) {
            /** @var User $user */
            $user = $users[$i];
            $position = $i + $currentLine;

            $objPHPExcel->getActiveSheet()->setCellValue('B' . $position, $user->getCreatedAt());
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $position, $user->getUsername());
        }

        $currentLine += count($users);

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $currentLine, 'form-filling-page-non_users');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $currentLine, count($protocol->getUser()));
        $currentLine++;

        $nonUsers = $protocol->getNonUser();
        for ($i = 0; $i < count($nonUsers); $i++) {
            /** @var ProtocolUser $user */
            $user = $nonUsers[$i];
            $position = $i + $currentLine;

            $objPHPExcel->getActiveSheet()->setCellValue('B' . $position, $user->getCreatedAt());
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $position, $user->getUsername());
        }

        $currentLine += count($nonUsers);

        $objPHPExcel->getActiveSheet()->getStyle('A1:A' . $currentLine)->getFont()->setBold(true);

        $objPHPExcel->createSheet(1);
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setTitle('data');

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'field');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'value');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'date');

        $values = $protocol->getFieldValues();
        $currentLine = 2;
        for ($i = 0; $i < count($values); $i++) {
            /** @var ProtocolFieldValue $value */
            $value = $values[$i];
            $position = $i + $currentLine;

            $objPHPExcel->getActiveSheet()->setCellValue('A' . $position, $value->getField()->getName());
            $curValue = $value->getValue();
            if (is_array($curValue)) {
                $curValue = implode(', ', $curValue);
            }
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $position, $curValue);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $position, $value->getCreatedAt());
        }

        $currentLine += count($values);

        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:A' . $currentLine)->getFont()->setBold(true);

        $objPHPExcel->setActiveSheetIndex(0);
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($objPHPExcel, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);


        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $protocol->getForm()->getName() . '_' . $protocol->getId() . '.xls'
        );

        $response->headers->set('Content-Disposition', $dispositionHeader);

        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }

}
