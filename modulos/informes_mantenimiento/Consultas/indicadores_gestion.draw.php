<?php

@session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
include_once("../../../modelo/php_conexion.php");
include_once("../../../constructores/paginas_constructor.php");
include_once("../../../general/class/ClasesPDFDocumentos.class.php");
include_once("../clases/informes_mantenimientos.class.php");

if( !empty($_REQUEST["Accion"]) ){
    $css =  new PageConstruct("", "", 1, "", 1, 0);
    $obCon = new InformesMantenimientos($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1:// dibujo los indicadores de gestion
            $obPDF=new Documento(DB);
            $tabla="vista_indicadores_ordenes_trabajo";
            
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            
            $BusquedasGenerales=$obCon->normalizar($_REQUEST["BusquedasGenerales"]);
            $fecha_inicial=$obCon->normalizar($_REQUEST["fecha_inicial"]);
            $fecha_final=$obCon->normalizar($_REQUEST["fecha_final"]);
            $unidad_negocio_id=$obCon->normalizar($_REQUEST["unidad_negocio_id"]);
            $proceso_id=$obCon->normalizar($_REQUEST["proceso_id"]);
            $Condicion=" WHERE t1.tipo_mantenimiento<>'' ";
            if($proceso_id <>''){
                $Condicion.=" AND t1.proceso_ot_id='$proceso_id' ";
            }
            if($unidad_negocio_id<>''){
                $Condicion.=" AND t1.unidad_negocio_id='$unidad_negocio_id' ";
            }
            
            $rango_informe="Todas las fechas";
            if($fecha_inicial<>''){
                $rango_informe="Mayor o igual al $fecha_inicial";
            }
            if($fecha_final<>''){
                $rango_informe="Menor o igual al $fecha_final";
            }
            if($fecha_inicial<>'' and $fecha_final<>''){
                $rango_informe="del $fecha_inicial al $fecha_final";
            }
            $obCon->crear_vistas_indicadores_gestion($db,$fecha_inicial,$fecha_final);
                        
            
            $CondicionBase64= base64_encode(urlencode($Condicion));
            $rango64= base64_encode(urlencode($rango_informe));
            $link_exportar_excel="../../general/Consultas/PDF_Documentos.draw.php?idDocumento=2&empresa_id=$empresa_id&c=$CondicionBase64&rango=$rango64";
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                
                    print('<div class="row widget-separator-1 mb-3">
                                
                                
                                <div class="col-md-3">
                                    <div class="widget-1">
                                        <div class="content">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h5 class="title">Exportar a PDF</h5>
                                                    <span class="descr"></span>
                                                </div>
                                                <div class="col text-right">
                                                    <div class="text-pinterest" style="font-size:50px"><a href="'.$link_exportar_excel.'" target="_blank"><li class="fa fa-file-pdf"></li></a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                
                                
                            ');
                   
                    $css->div("", "pull-right", "", "", "", "", "");
                        
                        $css->Cdiv();
                        $css->Cdiv();
                    $css->Cdiv();
                    $html=$obPDF->html_indicadores_gestion($db,$Condicion);
                print($html);
                $css->Cdiv();
                
                
                
            $css->Cdiv();
            
            
        break; //Fin caso 1
        
                             
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>