<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../../../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
function validateDate($date, $format = 'Y-m-d H:i:s'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

include_once("../clases/admin_equipos.class.php");

if( !empty($_REQUEST["Accion"]) ){
    
    $obCon=new adminEquipos($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1: //Crear una maquina
            
            $DatosFormulario["ID"]=$obCon->normalizar($_REQUEST["ID"]);
            $DatosFormulario["Codigo"]=$obCon->normalizar($_REQUEST["Codigo"]);
            $DatosFormulario["Nombre"]=$obCon->normalizar($_REQUEST["Nombre"]);
            $DatosFormulario["Marca"]=$obCon->normalizar($_REQUEST["Marca"]);
            $DatosFormulario["Modelo"]=$obCon->normalizar($_REQUEST["Modelo"]);
            $DatosFormulario["NumeroSerie"]=$obCon->normalizar($_REQUEST["NumeroSerie"]);
            $DatosFormulario["FechaFabricacion"]=$obCon->normalizar($_REQUEST["FechaFabricacion"]);
            $DatosFormulario["FechaInstalacion"]=$obCon->normalizar($_REQUEST["FechaInstalacion"]);
            $DatosFormulario["Especificaciones"]=$obCon->normalizar($_REQUEST["Especificaciones"]);
            
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $tipo_equipo=$obCon->normalizar($_REQUEST["tipo_equipo"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("equipos_maquinas", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("equipos_maquinas", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 1
        
        
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>