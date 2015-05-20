<?php

namespace Renatomefi\FormBundle\Controller;

use Renatomefi\FormBundle\Document\Protocol;
use Renatomefi\FormBundle\Document\ProtocolComment;
use Renatomefi\FormBundle\Document\ProtocolFieldValue;
use Renatomefi\FormBundle\Document\ProtocolFile;
use Renatomefi\FormBundle\Document\ProtocolPublish;
use Renatomefi\FormBundle\Document\ProtocolUser;
use Renatomefi\TranslateBundle\Document\Language;
use Renatomefi\UserBundle\Document\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Intl\Data\Generator\LanguageDataGenerator;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProtocolExportController
 * @package Renatomefi\FormBundle\Controller
 */
class ProtocolExportController extends Controller
{

    /**
     * @var Language
     */
    protected $language;

    /**
     * @var
     */
    protected $languageKey;

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
     * @param $key
     * @return string
     */
    protected function translate($key)
    {
        if (!$this->language) {
            $langDM = $this->get('doctrine_mongodb')->getRepository('TranslateBundle:Language');
            $this->language = $langDM->findOneByKey($this->languageKey);
        }

        return ($trans = $this->language->findTranslationByKey($key)) ? $trans->getValue() : $key;
    }

    /**
     * @Route("/pdf/{protocolId}/{lang}")
     * @Method("GET")
     *
     * @param $protocolId
     * @param $lang
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \PHPExcel_Exception
     */
    public function pdfAction($protocolId, $lang)
    {
        $this->languageKey = $lang;

        $protocol = $this->getProtocol($protocolId);

        $pdfFile = tempnam(sys_get_temp_dir(), 'sammui_pdf_');
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'FormBundle:ProtocolExport:pdf.html.twig',
                [
                    'fieldValues' => $protocol->getFieldValues()
                ]
            ),
            $pdfFile,
            [],
            true
        );

        $response = new StreamedResponse(function () use ($pdfFile) {
            echo file_get_contents($pdfFile);
            unlink($pdfFile);
        });

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $protocol->getForm()->getName() . '_' . $protocol->getId() . '.pdf'
        );

        $response->headers->set('Content-Disposition', $dispositionHeader);
        $response->headers->set('Content-Type', 'application/pdf; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }

    /**
     * @Route("/files/{protocolId}/{lang}")
     * @Method("GET")
     *
     * @param $protocolId
     * @param $lang
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \PHPExcel_Exception
     */
    public function filesAction($protocolId, $lang)
    {
        $this->languageKey = $lang;

        $protocol = $this->getProtocol($protocolId);

        $tmpFile = tempnam(sys_get_temp_dir(), 'sammui_files_');

        $zipFile = new \ZipArchive();
        $zipFile->open($tmpFile, \ZipArchive::CREATE);

        /** @var ProtocolFile $protocolFile */
        foreach ($protocol->getFile() as $protocolFile) {
            $zipFile->addFromString($protocolFile->getId() . '_' . $protocolFile->getFilename(), $protocolFile->getFile()->getBytes());
        }

        $zipFileName = $zipFile->filename;
        $zipFile->close();

        $response = new StreamedResponse(function () use ($zipFileName) {
            echo file_get_contents($zipFileName);
            unlink($zipFileName);
        });

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $protocol->getForm()->getName() . '_' . $protocol->getId() . '_files' . '.zip'
        );

        $response->headers->set('Content-Disposition', $dispositionHeader);
        $response->headers->set('Content-Type', 'application/zip; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }

    /**
     * @Route("/excel/{protocolId}/{lang}")
     * @Method("GET")
     *
     * @param $protocolId
     * @param $lang
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \PHPExcel_Exception
     */
    public function excelAction($protocolId, $lang)
    {
        $this->languageKey = $lang;

        $protocol = $this->getProtocol($protocolId);

        // ask the service for a Excel5
        $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject();

        $htmlWizard = $this->get('phpexcel')->createHelperHTML();

        $objPHPExcel->getProperties()->setCreator("sammui")
            ->setLastModifiedBy("sammui")
            ->setTitle($protocol->getForm()->getName() . ': ' . $protocol->getId());

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('info');

        $objPHPExcel->getActiveSheet()->setCellValue('A1', $protocol->getForm()->getName());
        $objPHPExcel->getActiveSheet()->setCellValue('B1', $this->translate($protocol->getForm()->getName()));
        $objPHPExcel->getActiveSheet()->setCellValue('C1', $protocol->getForm()->getId());

        $objPHPExcel->getActiveSheet()->setCellValue('A2', $this->translate('protocol'));
        $objPHPExcel->getActiveSheet()->setCellValue('B2', $protocol->getId());

        $objPHPExcel->getActiveSheet()->setCellValue('A3', $this->translate('form-protocol-group'));
        $objPHPExcel->getActiveSheet()->setCellValue('B3', $protocol->getCurrentGroup());
        $objPHPExcel->getActiveSheet()->setCellValue('C3',
            $this->translate('form-' . $protocol->getForm()->getName() . '-group-' . $protocol->getCurrentGroup()));

        $objPHPExcel->getActiveSheet()->setCellValue('A4', $this->translate($this->translate('form-protocol-first_save_date')));
        $objPHPExcel->getActiveSheet()->setCellValue('B4', $protocol->getFirstSaveDate());

        $objPHPExcel->getActiveSheet()->setCellValue('A5', $this->translate('form-protocol-last_save_date'));
        $objPHPExcel->getActiveSheet()->setCellValue('B5', $protocol->getLastSaveDate());

        $objPHPExcel->getActiveSheet()->setCellValue('A6', 'publish');
        $objPHPExcel->getActiveSheet()->setCellValue('B6',
            $protocol->isLocked() ? $this->translate('published') : $this->translate('not published'));

        $publishes = $protocol->getPublish();
        $currentLine = 7;
        for ($i = 0; $i < count($publishes); $i++) {
            /** @var ProtocolPublish $publish */
            $publish = $publishes[$i];
            $position = $i + $currentLine;

            $objPHPExcel->getActiveSheet()->setCellValue('B' . $position, $publish->getCreatedAt());
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $position, ($publish->getLocked()) ? $this->translate('lock') : $this->translate('unlock'));
            if ($publish->getUser())
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $position, $publish->getUser()->getUsername());
        }
        $currentLine += count($publishes);

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $currentLine, $this->translate('form-filling-page-comments'));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $currentLine, count($protocol->getComment()));
        $currentLine++;

        $comments = $protocol->getComment();
        for ($i = 0; $i < count($comments); $i++) {
            /** @var ProtocolComment $comment */
            $comment = $comments[$i];
            $position = $i + $currentLine;

            $objPHPExcel->getActiveSheet()->setCellValue('B' . $position, $comment->getCreatedAt());
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $position, $htmlWizard->toRichTextObject(utf8_decode($comment->getBody())));
        }

        $currentLine += count($comments);

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $currentLine, $this->translate('form-filling-page-users'));
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

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $currentLine, $this->translate('form-filling-page-non_users'));
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

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $currentLine, $this->translate('form-filling-page-files'));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $currentLine, count($protocol->getFile()));
        $currentLine++;

        $files = $protocol->getFile();
        for ($i = 0; $i < count($files); $i++) {
            /** @var ProtocolFile $file */
            $file = $files[$i];
            $position = $i + $currentLine;

            $objPHPExcel->getActiveSheet()->setCellValue('B' . $position, $file->getUploadDate());
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $position, $file->getFilename()
                . '(' . $file->getId() . '):' . $file->getLength());
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $position, $file->getTitle()
                . ': ' . $file->getDescription());
        }

        $currentLine += count($files);


        $objPHPExcel->getActiveSheet()->setCellValue('A' . $currentLine, $this->translate('form-filling-page-conclusion'));
        $currentLine++;

        if ($protocol->getConclusion()) {
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $currentLine, $htmlWizard->toRichTextObject(utf8_decode($protocol->getConclusion())));
            $objPHPExcel->getActiveSheet()->mergeCells(str_replace('%', $currentLine, 'B%:D%'));
            $objPHPExcel->getActiveSheet()->getRowDimension($currentLine)->setRowHeight(300);
            $currentLine++;
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:A' . $currentLine)->getFont()->setBold(true);


        // Values Sheet
        $objPHPExcel->createSheet(1);
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setTitle('data');

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'field');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'rvalue');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'value');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'date');

        $values = $protocol->getFieldValues();
        $currentLine = 2;
        for ($i = 0; $i < count($values); $i++) {
            /** @var ProtocolFieldValue $value */
            $value = $values[$i];
            $position = $i + $currentLine;

            $objPHPExcel->getActiveSheet()->setCellValue('A' . $position, $value->getField()->getName());
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $position,
                $this->translate('form-' . $protocol->getForm()->getName() . '-field-' . $value->getField()->getName()));
            $curValue = $value->getValue();
            if (is_array($curValue)) {
                $curValue = '';
                foreach ($value->getValue() as $k => $v) {
                    $curValue .= $k . ':' . $v . ',';
                }
                $curValue = rtrim($curValue, ',');
            }
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $position, $curValue);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $position, $value->getValueInHR());
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $position, $value->getCreatedAt());
        }

        $currentLine += count($values);

        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:A' . $currentLine)->getFont()->setBold(true);

        foreach (range('A', 'D') as $columnID) {
            $objPHPExcel->getSheet(0)->getColumnDimension($columnID)
                ->setAutoSize(true);
            $objPHPExcel->getSheet(1)->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

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
