<?php 
session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../../../images/401.png'>Iniciar Sesion </a>");
  
}
if(isset($_REQUEST["idDocumento"])){
    
    include_once("../../modelo/php_conexion.php");    
    include_once("../class/ClasesPDFDocumentos.class.php");
    
    $idUser=1;
    $obCon = new conexion($idUser);
    
    $obDoc = new Documento(DB);
    $idDocumento=$obCon->normalizar($_REQUEST["idDocumento"]);
    
    
    switch ($idDocumento){
        case 1: //PDF para un pedido
            
            $orden_trabajo_id=$obCon->normalizar($_REQUEST["id"]);
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);                  
            $obDoc->orden_trabajo_pdf($empresa_id,$orden_trabajo_id);            
        break;//Fin caso 1
       
    }
}else{
    print("No se recibió parametro de documento");
}

?>