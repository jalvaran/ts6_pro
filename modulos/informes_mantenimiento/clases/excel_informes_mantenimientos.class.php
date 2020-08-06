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
    
    public function excel_listado_general_fallas($empresa_id,$Condicion) {
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
            ->setCellValue("A1","LISTADO GENERAL DE FALLAS")
             
                ;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
        
        $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($styleTitle);
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleTitle);
        $z=0;
        $i=3;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($Campos[$z++].$i,"ID")
            ->setCellValue($Campos[$z++].$i,"Maquina")            
            ->setCellValue($Campos[$z++].$i,"Ubicación") 
            ->setCellValue($Campos[$z++].$i,"Tiempo de Parada")       
            ->setCellValue($Campos[$z++].$i,"Falla")
            ->setCellValue($Campos[$z++].$i,"Causa")
            ->setCellValue($Campos[$z++].$i,"Unidad de Negocio")
            ->setCellValue($Campos[$z++].$i,"Proceso")
                                
            ;
        $tabla="vista_ordenes_trabajo_fallas";
        $DataBaseGeneral=DB;
        $sql="SELECT  t1.*,
                    (SELECT Falla FROM $DataBaseGeneral.catalogo_fallas t2 WHERE t2.ID=t1.falla_id LIMIT 1) as nombre_falla,
                    (SELECT Causa FROM $DataBaseGeneral.catalogo_causas t3 WHERE t3.ID=t1.causa_falla_id LIMIT 1) as nombre_causa 
                 FROM $db.$tabla t1 $Condicion";
        $Consulta=$this->Query($sql);
        $i=3;
        while($DatosVista= $this->FetchAssoc($Consulta)){
            
            $i++;
            $z=0;
            $objPHPExcel->setActiveSheetIndex(0)

                ->setCellValue($Campos[$z++].$i,$DatosVista["ID"])
                ->setCellValue($Campos[$z++].$i, utf8_encode($DatosVista["nombre_maquina"]))
                ->setCellValue($Campos[$z++].$i, utf8_encode($DatosVista["nombre_ubicacion"]))
                ->setCellValue($Campos[$z++].$i,$DatosVista["tiempo_parada"])    
                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_falla"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_causa"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_unidad_negocio"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_proceso"])
                
                ;
            
        }
        
        
        $objPHPExcel->getActiveSheet()->getStyle("A3:H3")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth('3');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth('45');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth('40');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth('11');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth('14');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth('35');
        
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("Listado General de fallas")
        ->setSubject("Informes")
        ->setDescription("Documento generado por Techno Soluciones SAS")
        ->setKeywords("techno soluciones sas")
        ->setCategory("Informes");    
 
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'."listado_general_fallas".'.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter=IOFactory::createWriter($objPHPExcel,'Xlsx');
    $objWriter->save('php://output');
    exit; 
   
    }
    
    public function excel_fallas_frecuentes($empresa_id,$Condicion) {
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
            ->setCellValue("A1","LISTADO DE FALLAS FRECUENTES")
             
                ;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
        
        $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($styleTitle);
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleTitle);
        $z=0;
        $i=3;
        $objPHPExcel->setActiveSheetIndex(0)
               
            ->setCellValue($Campos[$z++].$i,"Falla")
            ->setCellValue($Campos[$z++].$i,"Causa")
            ->setCellValue($Campos[$z++].$i,"Total")
                                            
            ;
        $tabla="vista_ordenes_trabajo_fallas";
        $DataBaseGeneral=DB;
        $sql="SELECT  count(*) as total_fallas, falla_id, causa_falla_id,
                    (SELECT Falla FROM $DataBaseGeneral.catalogo_fallas t2 WHERE t2.ID=t1.falla_id LIMIT 1) as nombre_falla,
                    (SELECT Causa FROM $DataBaseGeneral.catalogo_causas t3 WHERE t3.ID=t1.causa_falla_id LIMIT 1) as nombre_causa 
                 FROM $db.$tabla t1 $Condicion GROUP BY falla_id, causa_falla_id ORDER BY total_fallas DESC";
        $Consulta=$this->Query($sql);
        $i=3;
        while($DatosVista= $this->FetchAssoc($Consulta)){
            
            $i++;
            $z=0;
            $objPHPExcel->setActiveSheetIndex(0)

                  
                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_falla"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_causa"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["total_fallas"])
                
                
                ;
            
        }
        
        
        $objPHPExcel->getActiveSheet()->getStyle("A3:H3")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth('30');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth('10');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth('11');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth('14');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth('35');
        
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("Listado General de fallas")
        ->setSubject("Informes")
        ->setDescription("Documento generado por Techno Soluciones SAS")
        ->setKeywords("techno soluciones sas")
        ->setCategory("Informes");    
 
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'."listado_general_fallas".'.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter=IOFactory::createWriter($objPHPExcel,'Xlsx');
    $objWriter->save('php://output');
    exit; 
   
    }
    
    
    public function excel_maquinas_fallas($empresa_id,$Condicion) {
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
            ->setCellValue("A1","LISTADO DE MAQUINAS QUE MAS FALLAN")
             
                ;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
        
        $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($styleTitle);
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleTitle);
        $z=0;
        $i=3;
        $objPHPExcel->setActiveSheetIndex(0)
               
            ->setCellValue($Campos[$z++].$i,"Fallas")
            ->setCellValue($Campos[$z++].$i,"Maquina")
            ->setCellValue($Campos[$z++].$i,"Ubicacion")
            ->setCellValue($Campos[$z++].$i,"Unidad de Negocio")
            ->setCellValue($Campos[$z++].$i,"Proceso")
            
            ;
        $tabla="vista_ordenes_trabajo_fallas";
        
        $sql="SELECT count(*) as total_fallas, nombre_maquina,nombre_ubicacion,nombre_unidad_negocio,nombre_proceso  
                    
                  FROM $db.$tabla t1 $Condicion GROUP BY maquina_id  ORDER BY total_fallas DESC";
        $Consulta=$this->Query($sql);
        $i=3;
        while($DatosVista= $this->FetchAssoc($Consulta)){
            
            $i++;
            $z=0;
            $objPHPExcel->setActiveSheetIndex(0)

                  
                ->setCellValue($Campos[$z++].$i,$DatosVista["total_fallas"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_maquina"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_ubicacion"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_unidad_negocio"])
                ->setCellValue($Campos[$z++].$i,$DatosVista["nombre_proceso"])
                
                ;
            
        }
        
        
        $objPHPExcel->getActiveSheet()->getStyle("A3:H3")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth('6');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth('40');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth('40');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth('15');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth('40');
        
        
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("Maquinas que mas fallan")
        ->setSubject("Informes")
        ->setDescription("Documento generado por Techno Soluciones SAS")
        ->setKeywords("techno soluciones sas")
        ->setCategory("Informes");    
 
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'."listado_general_fallas".'.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter=IOFactory::createWriter($objPHPExcel,'Xlsx');
    $objWriter->save('php://output');
    exit; 
   
    }
    
        
   //Fin Clases
}
    