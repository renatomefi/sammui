<?php

namespace Renatomefi\FormBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProtocolExportController
 * @package Renatomefi\FormBundle\Controller
 */
class ProtocolExportController extends Controller
{

    /**
     * @Route("/excel/{protocol}")
     * @Method("GET")
     *
     * @param $protocol
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \PHPExcel_Exception
     */
    public function excelAction($protocol)
    {
        // ask the service for a Excel5
        $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject();

        $objPHPExcel->getProperties()->setCreator("sammui")
            ->setLastModifiedBy("sammui")
            ->setTitle("Office 2005 XLSX Test Document")
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "Firstname");
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "Lastname");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "Phone");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "Fax");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "Is Client ?");

        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setVisible(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setVisible(false);

        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setOutlineLevel(1)
            ->setVisible(false)
            ->setCollapsed(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setOutlineLevel(1)
            ->setVisible(false)
            ->setCollapsed(true);

        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

        for ($i = 2; $i <= 5000; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "FName $i")
                ->setCellValue('B' . $i, "LName $i")
                ->setCellValue('C' . $i, "PhoneNo $i")
                ->setCellValue('D' . $i, "FaxNo $i")
                ->setCellValue('E' . $i, true);
        }

        $objPHPExcel->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($objPHPExcel, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=stream-file.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }

    /**
     * Large table
     *
     * @Route("/excel/test1/{protocol}")
     * @Method("GET")
     *
     * @param $protocol
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \PHPExcel_Exception
     */
    public function excelTest1Action($protocol)
    {
        // ask the service for a Excel5
        $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject();

        $objPHPExcel->getProperties()->setCreator("sammui")
            ->setLastModifiedBy("sammui")
            ->setTitle("Office 2005 XLSX Test Document")
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "Firstname");
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "Lastname");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "Phone");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "Fax");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "Is Client ?");

        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setVisible(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setVisible(false);

        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setOutlineLevel(1)
            ->setVisible(false)
            ->setCollapsed(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setOutlineLevel(1)
            ->setVisible(false)
            ->setCollapsed(true);

        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

        for ($i = 2; $i <= 5000; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "FName $i")
                ->setCellValue('B' . $i, "LName $i")
                ->setCellValue('C' . $i, "PhoneNo $i")
                ->setCellValue('D' . $i, "FaxNo $i")
                ->setCellValue('E' . $i, true);
        }

        $objPHPExcel->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($objPHPExcel, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=stream-file.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }

}