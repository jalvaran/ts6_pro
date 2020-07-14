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
        
        case 1: //Crear o editar una maquina
            
            $DatosFormulario["ID"]=$obCon->normalizar($_REQUEST["ID"]);
            $DatosFormulario["Codigo"]=$obCon->normalizar($_REQUEST["Codigo"]);
            $DatosFormulario["Nombre"]=$obCon->normalizar($_REQUEST["Nombre"]);
            $DatosFormulario["Marca"]=$obCon->normalizar($_REQUEST["Marca"]);
            $DatosFormulario["Modelo"]=$obCon->normalizar($_REQUEST["Modelo"]);
            $DatosFormulario["NumeroSerie"]=$obCon->normalizar($_REQUEST["NumeroSerie"]);
            $DatosFormulario["FechaFabricacion"]=$obCon->normalizar($_REQUEST["FechaFabricacion"]);
            $DatosFormulario["FechaInstalacion"]=$obCon->normalizar($_REQUEST["FechaInstalacion"]);
            $DatosFormulario["Especificaciones"]=$obCon->normalizar($_REQUEST["Especificaciones"]);
            
            $DatosFormulario["proceso_id"]=$obCon->normalizar($_REQUEST["proceso_id"]);
            $DatosFormulario["ubicacion_id"]=$obCon->normalizar($_REQUEST["ubicacion_id"]);
            $DatosFormulario["representante_id"]=$obCon->normalizar($_REQUEST["representante_id"]);
            $DatosFormulario["ValorAdquisicion"]=$obCon->normalizar($_REQUEST["ValorAdquisicion"]);
            $DatosFormulario["FechaAdquisicion"]=$obCon->normalizar($_REQUEST["FechaAdquisicion"]);
            
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $tipo_equipo=$obCon->normalizar($_REQUEST["tipo_equipo"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            
            if(!is_numeric($DatosFormulario["ValorAdquisicion"]) or $DatosFormulario["ValorAdquisicion"]<0){
                exit("E1;El campo ValorAdquision debe ser un numero mayor o igual a cero;$key");
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
        
        case 2: //Crear o editar un componente
            
            $DatosFormulario["ID"]=$obCon->normalizar($_REQUEST["ID"]);
            
            $DatosFormulario["Nombre"]=$obCon->normalizar($_REQUEST["Nombre"]);
            $DatosFormulario["Marca"]=$obCon->normalizar($_REQUEST["Marca"]);
            $DatosFormulario["Modelo"]=$obCon->normalizar($_REQUEST["Modelo"]);
            $DatosFormulario["NumeroSerie"]=$obCon->normalizar($_REQUEST["NumeroSerie"]);
            $DatosFormulario["maquina_id"]=$obCon->normalizar($_REQUEST["maquina_id"]);
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
                $sql=$obCon->getSQLInsert("equipos_componentes", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("equipos_componentes", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 2
        
        case 3: //Crear o editar una parte
            
            $DatosFormulario["ID"]=$obCon->normalizar($_REQUEST["ID"]);
            $DatosFormulario["Codigo"]=$obCon->normalizar($_REQUEST["Codigo"]);
            $DatosFormulario["DescripcionPrimaria"]=$obCon->normalizar($_REQUEST["DescripcionPrimaria"]);
            $DatosFormulario["DescripcionSecundaria"]=$obCon->normalizar($_REQUEST["DescripcionSecundaria"]);
            $DatosFormulario["Cantidad"]=$obCon->normalizar($_REQUEST["Cantidad"]);
            $DatosFormulario["Costo"]=$obCon->normalizar($_REQUEST["Costo"]);            
            $DatosFormulario["Fecha"]=$obCon->normalizar($_REQUEST["Fecha"]);
            
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $tipo_equipo=$obCon->normalizar($_REQUEST["tipo_equipo"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            
            if(!is_numeric($DatosFormulario["Cantidad"]) and $DatosFormulario["Cantidad"]>0){
                exit("E1;El campo Cantidad debe contener un valor numerico positivo;Cantidad");
            }
            
            if(!is_numeric($DatosFormulario["Costo"]) and $DatosFormulario["Costo"]>0){
                exit("E1;El campo Costo debe contener un valor numerico positivo;Costo");
            }
            
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("equipos_partes", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("equipos_partes", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 3
        
        case 4: //Crear o editar un representante
            
            $DatosFormulario["NombreRepresentante"]=$obCon->normalizar($_REQUEST["NombreRepresentante"]);
            $DatosFormulario["Contacto"]=$obCon->normalizar($_REQUEST["Contacto"]);
            $DatosFormulario["Telefono"]=$obCon->normalizar($_REQUEST["Telefono"]);
            $DatosFormulario["Fax"]=$obCon->normalizar($_REQUEST["Fax"]);
            $DatosFormulario["Email"]=$obCon->normalizar($_REQUEST["Email"]);
            $DatosFormulario["Direccion"]=$obCon->normalizar($_REQUEST["Direccion"]);            
            $DatosFormulario["Ciudad"]=$obCon->normalizar($_REQUEST["Ciudad"]);
            $DatosFormulario["Celular"]=$obCon->normalizar($_REQUEST["Celular"]);
            
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
                $sql=$obCon->getSQLInsert("catalogo_representante", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("catalogo_representante", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 4
        
        case 5://Desligar un componente de una maquina
            $componente_id=$obCon->normalizar($_REQUEST["componente_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            
            $sql="UPDATE equipos_componentes SET maquina_id=0 WHERE ID='$componente_id'";
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Componente Desligado a la maquina");
        break;//Fin caso 5    
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>