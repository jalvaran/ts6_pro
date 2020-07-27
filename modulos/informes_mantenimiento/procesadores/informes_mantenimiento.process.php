<?php

@session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../../../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../clases/informes_mantenimientos.class.php");

if( !empty($_REQUEST["Accion"]) ){
    
    $obCon=new InformesMantenimientos($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1://Exportar vista de hojas de vida
            include_once '../clases/excel_informes_mantenimientos.class.php';
            $Condicion=($_REQUEST['c']);            
            $Condicion= urldecode(base64_decode($Condicion));
            $empresa_id=$obCon->normalizar($_REQUEST['empresa_id']);
            $obExcel=new ExcelInformesMantenimiento($idUser);            
            $obExcel->excel_hojas_vida($empresa_id,$Condicion);
            
        break;//Fin caso 1
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>