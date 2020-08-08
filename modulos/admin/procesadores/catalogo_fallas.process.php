<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../../../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../clases/catalogo_fallas.class.php");

if( !empty($_REQUEST["Accion"]) ){
    
    $obCon=new CatalogoFallas($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1: //Crear una falla
            
            
            $DatosFormulario["Falla"]=$obCon->normalizar($_REQUEST["Falla"]);
            
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            
            
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("catalogo_fallas", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("catalogo_fallas", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->Query($sql);
            print("OK;Datos Guardados");
            
        break;//Fin caso 1
        
        case 2: //Crear editar una causa
            
            
            $DatosFormulario["falla_id"]=$obCon->normalizar($_REQUEST["falla_id"]);
            $DatosFormulario["Causa"]=$obCon->normalizar($_REQUEST["Causa"]);
            
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            
            
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("catalogo_causas", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("catalogo_causas", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->Query($sql);
            print("OK;Datos Guardados");
            
        break;//Fin caso 2
        
        
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>