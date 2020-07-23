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

include_once("../clases/admin_catalogos.class.php");

if( !empty($_REQUEST["Accion"]) ){
    
    $obCon=new adminCatalogos($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1: //Crear o editar un proceso
            
            $DatosFormulario["CodigoProceso"]=$obCon->normalizar($_REQUEST["CodigoProceso"]);
            $DatosFormulario["Nombre"]=$obCon->normalizar($_REQUEST["Nombre"]);
            $DatosFormulario["unidadNegocio_id"]=$obCon->normalizar($_REQUEST["unidadNegocio_id"]);
                        
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $catalogo_id=$obCon->normalizar($_REQUEST["catalogo_id"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("catalogo_procesos", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("catalogo_procesos", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 1
        
        case 2: //Crear o editar una seccion
            
            $DatosFormulario["Codigo"]=$obCon->normalizar($_REQUEST["Codigo"]);
            $DatosFormulario["NombreSeccion"]=$obCon->normalizar($_REQUEST["NombreSeccion"]);
                        
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $catalogo_id=$obCon->normalizar($_REQUEST["catalogo_id"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("catalogo_secciones", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("catalogo_secciones", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 2
        
        case 3: //Crear o editar una tarea
            
            $DatosFormulario["CodigoTarea"]=$obCon->normalizar($_REQUEST["CodigoTarea"]);
            $DatosFormulario["NombreTarea"]=$obCon->normalizar($_REQUEST["NombreTarea"]);
            $DatosFormulario["TipoTarea"]=$obCon->normalizar($_REQUEST["TipoTarea"]);
            //$DatosFormulario["Contador"]=$obCon->normalizar($_REQUEST["Contador"]);
            
                        
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $catalogo_id=$obCon->normalizar($_REQUEST["catalogo_id"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("catalogo_tareas", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("catalogo_tareas", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 3
        
        case 4: //Crear o editar un tecnico
            
            $DatosFormulario["Identificacion"]=$obCon->normalizar($_REQUEST["Identificacion"]);
            $DatosFormulario["NombreTecnico"]=$obCon->normalizar($_REQUEST["NombreTecnico"]);
            $DatosFormulario["TipoTecnico"]=$obCon->normalizar($_REQUEST["TipoTecnico"]);
            $DatosFormulario["Telefono"]=$obCon->normalizar($_REQUEST["Telefono"]);
            $DatosFormulario["Email"]=$obCon->normalizar($_REQUEST["Email"]);
            $DatosFormulario["Direccion"]=$obCon->normalizar($_REQUEST["Direccion"]);
            $DatosFormulario["Ciudad"]=$obCon->normalizar($_REQUEST["Ciudad"]);
            $DatosFormulario["Celular"]=$obCon->normalizar($_REQUEST["Celular"]);
            
                        
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $catalogo_id=$obCon->normalizar($_REQUEST["catalogo_id"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("catalogo_tecnicos", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("catalogo_tecnicos", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 4
        
        case 5: //Crear o editar una unidad de negocio
            
            $DatosFormulario["UnidadNegocio"]=$obCon->normalizar($_REQUEST["UnidadNegocio"]);
                                    
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $catalogo_id=$obCon->normalizar($_REQUEST["catalogo_id"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("catalogo_unidades_negocio", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("catalogo_unidades_negocio", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 5
        
        case 6: //Crear o editar un tipo de tarea
            
            $DatosFormulario["tipo_tarea"]=$obCon->normalizar($_REQUEST["tipo_tarea"]);
                                    
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $catalogo_id=$obCon->normalizar($_REQUEST["catalogo_id"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("catalogo_tareas_tipos", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("catalogo_tareas_tipos", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 6
        
        case 7: //Crear o editar una ruta de verificacion
            
            $DatosFormulario["NombreRuta"]=$obCon->normalizar($_REQUEST["NombreRuta"]);
            $DatosFormulario["Descripcion"]=$obCon->normalizar($_REQUEST["Descripcion"]);
            
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]); 
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $catalogo_id=$obCon->normalizar($_REQUEST["catalogo_id"]); 
            
            foreach ($DatosFormulario as $key => $value) {
                
                if($value==''){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            if($edit_id==""){
                $sql=$obCon->getSQLInsert("catalogo_rutas_verificacion", $DatosFormulario); 
            }else{
                $sql=$obCon->getSQLUpdate("catalogo_rutas_verificacion", $DatosFormulario);
                $sql.=" WHERE ID='$edit_id'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            print("OK;Datos Guardados");
            
        break;//Fin caso 7
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>