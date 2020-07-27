<?php
/* 
 * Clase donde se realizaran la generacion de archivos en excel para el modulo de inteligencia de negocios
 * Julian Alvaran 
 * Techno Soluciones SAS
 */
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

include_once'../../../general/class/excel.class.php';

class ExcelInformesMantenimiento extends Excel{
    
        
    public function excel_hojas_vida($empresa_id,$Condicion) {
        require_once('../../../librerias/Excel/PHPExcel2.php');
        $DatosEmpresa=$this->DevuelveValores("empresapro", "ID", $empresa_id);
        $db=$DatosEmpresa["db"];
    
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getActiveSheet()->getStyle('E:H')->getNumberFormat()->setFormatCode('#,##0');
        $styleTitle = [
            'font' => [
                'bold' => true,
                'size' => 12
            ]
            
        ];
                
        $Campos=["A","B","C","D","E","F","G","H","I","J","K","L","M",
                 "N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB"];
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1","RESUMEN HOJA DE VIDA DE LAS MAQUINAS")
             
                ;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
        
        $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($styleTitle);
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleTitle);
        $z=0;
        $i=3;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($Campos[$z++].$i,"Tipo de Mantenimiento")
            ->setCellValue($Campos[$z++].$i,"Maquina")
            ->setCellValue($Campos[$z++].$i,"Codigo de la Maquina")    
            ->setCellValue($Campos[$z++].$i,"Ubicación")         
            ->setCellValue($Campos[$z++].$i,"Total Ordenes")
            ->setCellValue($Campos[$z++].$i,"Total Tareas")
            ->setCellValue($Campos[$z++].$i,"Tiempo de parada")
            ->setCellValue($Campos[$z++].$i,"Costos Totales")
                                
            ;
            
        $sql="SELECT * FROM $db.vista_hojas_vida_maquinas $Condicion";
        $Consulta=$this->Query($sql);
        $i=3;
        while($DatosVista= $this->FetchAssoc($Consulta)){
            
            $i++;
            $z=0;
            $objPHPExcel->setActiveSheetIndex(0)

                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_tipo_mantenimiento"])
                ->setCellValue($Campos[$z++].$i, utf8_encode($DatosVista["nombre_maquina"]))
                ->setCellValue($Campos[$z++].$i,$DatosVista["codigo_maquina"])
                ->setCellValue($Campos[$z++].$i, utf8_encode($DatosVista["nombre_ubicacion"]))
                ->setCellValue($Campos[$z++].$i,$DatosVista["cantidad_ordenes"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["total_tareas_maquinas"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["total_tiempo_parada"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["total_costos"])
                
                ;
            
        }
        
        
        $objPHPExcel->getActiveSheet()->getStyle("A3:H3")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth('16');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth('45');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth('22');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth('48');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth('10');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth('10');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth('10');
        
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("Resumen Hoja de vida de las maquinas")
        ->setSubject("Hoja de vida")
        ->setDescription("Documento generado por Techno Soluciones SAS")
        ->setKeywords("techno soluciones sas")
        ->setCategory("Resumen Hoja de vida de las maquinas");    
 
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'."hoja_vida".'.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter=IOFactory::createWriter($objPHPExcel,'Xlsx');
    $objWriter->save('php://output');
    exit; 
   
    }
    
        
   //Fin Clases
}
    