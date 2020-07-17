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

include_once("../clases/mantenimiento.class.php");

if( !empty($_REQUEST["Accion"]) ){
    
    $obCon=new Mantenimiento($idUser);
    
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
    
        case 6://Obtener los componentes de una maquina
            
            $maquina_id=$obCon->normalizar($_REQUEST["maquina_id"]);
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            $sql="SELECT * FROM $db.equipos_componentes WHERE maquina_id='$maquina_id'";
            $Consulta=$obCon->Query($sql);
            $i=0;
            $Existe=0;
            while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                $Existe=1;
                $i++;
                $Componentes[$i]["ID"]=$DatosConsulta["ID"];
                $Componentes[$i]["Nombre"]=$DatosConsulta["Nombre"];
                $Componentes[$i]["Marca"]=$DatosConsulta["Marca"];
                $Componentes[$i]["Modelo"]=$DatosConsulta["Modelo"];
                $Componentes[$i]["NumeroSerie"]=$DatosConsulta["NumeroSerie"];
            }
            if($Existe==0){
                exit("E1;No hay componentes para esta maquina");
            }
            
            $jsonComponentes= json_encode($Componentes);
            print("OK;".$jsonComponentes);
            
        break;//Fin caso 6
            
        case 7://Obtener los componentes de una maquina
            
            $componente_id=$obCon->normalizar($_REQUEST["componente_id"]);
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            $sql="SELECT * FROM $db.equipos_componentes WHERE ID='$componente_id'";
            $DatosConsulta=$obCon->FetchAssoc($obCon->Query($sql));
                  
            $jsonComponentes= json_encode($DatosConsulta);
            print("OK;".$jsonComponentes);
            
        break;//Fin caso 7
    
        case 8://agregar una tarea a una orden de trabajo
            
            $orden_trabajo_id=$obCon->normalizar($_REQUEST["orden_trabajo_id"]);
            $cmb_tarea_mantenimiento=$obCon->normalizar($_REQUEST["cmb_tarea_mantenimiento"]);
            if($cmb_tarea_mantenimiento==''){
                exit("E1;Debes Seleccionar una tarea");
            }
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            
            $Validacion=$obCon->ValorActual("$db.ordenes_trabajo_tareas", "ID", " orden_trabajo_id='$orden_trabajo_id' AND tarea_id='$cmb_tarea_mantenimiento'");
            if($Validacion["ID"]>0){
                exit("E1;La tarea ya fué agregada a esta OT");
            }
            $obCon->agregarTareaOT($db, $orden_trabajo_id, $cmb_tarea_mantenimiento);            
            print("OK;Tarea Agregada");
            
        break;//Fin caso 8
        
        case 9://eliminar una tarea de una orden de trabajo
            
            $tabla_id=$obCon->normalizar($_REQUEST["tabla_id"]);
            $item_id=$obCon->normalizar($_REQUEST["item_id"]);
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            $Tabla="";
            if($tabla_id==1){
                $Tabla=$db.".ordenes_trabajo_tareas";
            }
            if($tabla_id==2){
                $Tabla=$db.".ordenes_trabajo_insumos";
            }
            $obCon->BorraReg($Tabla, "ID", $item_id);
            print("OK;Registro Eliminado");
            
        break;//Fin caso 9
        
        case 10://calcular fecha programada segun frecuencian en dias
            
            $ultimoMantenimiento=$obCon->normalizar($_REQUEST["ultimoMantenimiento"]);
            $frecuencia_dias=$obCon->normalizar($_REQUEST["frecuencia_dias"]);
            if($ultimoMantenimiento==''){
                exit("E1;La fecha del ultimo mantenimiento no puede estar vacía");
            }
            if(!is_numeric($frecuencia_dias) or  $frecuencia_dias <= '0' ){
                exit("E1;la frecuencia en dias debe ser un numero mayor a cero");
            }
            $fecha_programada=$obCon->SumeDiasFecha($ultimoMantenimiento, $frecuencia_dias);
            print("OK;Fecha programada calculada para el $fecha_programada;$fecha_programada");
            
        break;//Fin caso 10
        
        case 11://crear una orden de trabajo
            
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]);
            $orden_trabajo_id=$obCon->normalizar($_REQUEST["orden_trabajo_id"]);
            $orden_tabajo_tipo_id=$obCon->normalizar($_REQUEST["orden_tabajo_tipo_id"]);
            $fecha_programada=$obCon->normalizar($_REQUEST["fecha_programada"]);
            $maquina_id=$obCon->normalizar($_REQUEST["maquina_id"]);
            $componente_id=$obCon->normalizar($_REQUEST["componente_id"]);
            $fecha_ultimo_mantenimiento=$obCon->normalizar($_REQUEST["fecha_ultimo_mantenimiento"]);
            $frecuencia_dias=$obCon->normalizar($_REQUEST["frecuencia_dias"]);
            $frecuencia_verificacion_dias=$obCon->normalizar($_REQUEST["frecuencia_verificacion_dias"]);
            $frecuencia_horas=$obCon->normalizar($_REQUEST["frecuencia_horas"]);
            $frecuencia_kilometros=$obCon->normalizar($_REQUEST["frecuencia_kilometros"]);
            $observaciones_orden=$obCon->normalizar($_REQUEST["observaciones_orden"]);
            
            if($orden_trabajo_id==""){
                exit("E1;no se recibió el id de la orden de trabajo");
            }
            if($orden_tabajo_tipo_id==""){
                exit("E1;Debe seleccionar el tipo de  orden de trabajo;orden_tabajo_tipo_id");
            }
            if($fecha_programada==""){
                exit("E1;El campo fecha programada no puede estar vacío;fecha_programada");
            }
            if($maquina_id==""){
                exit("E1;Debe Seleccionar una maquina;maquina_id");
            }
            if($componente_id==""){
                exit("E1;Debe Seleccionar un componente;componente_id");
            }
            if($fecha_ultimo_mantenimiento==""){
                exit("E1;El campo fecha de último mantenimiento no puede estar vacío;fecha_ultimo_mantenimiento");
            }
            if(!is_numeric($frecuencia_verificacion_dias) or $frecuencia_verificacion_dias<0){
                exit("E1;El campo Frecuencia de verificacion en Días debe ser un numero mayor o igual a cero;frecuencia_verificacion_dias");
            }
            if(!is_numeric($frecuencia_dias) or $frecuencia_dias<0){
                exit("E1;El campo Frecuencia en Días debe ser un numero mayor o igual a cero;frecuencia_dias");
            }
            if(!is_numeric($frecuencia_horas) or $frecuencia_horas<0){
                exit("E1;El campo Frecuencia en Horas debe ser un numero mayor o igual a cero;frecuencia_horas");
            }
            if(!is_numeric($frecuencia_kilometros) or $frecuencia_kilometros<0){
                exit("E1;El campo Frecuencia en Kilometros debe ser un numero mayor o igual a cero;frecuencia_kilometros");
            }
            if($observaciones_orden==""){
                exit("E1;El campo Observaciones no puede estar vacío;observaciones_orden");
            }
            if($orden_tabajo_tipo_id==2){//Si es una orden de mantenimiento preventivo debe agregar al menos una tarea
                $sql="SELECT COUNT(ID) as TotalTareas from $db.ordenes_trabajo_tareas where orden_trabajo_id='$orden_trabajo_id'";
                $validacion=$obCon->FetchAssoc($obCon->Query($sql));
                if($validacion["TotalTareas"]<=0 ){
                    exit("E1;Debe Agregar al menos una tarea de mantenimiento;cmb_tarea_mantenimiento");
                }
            }
            $obCon->crearEditarOrdenTrabajo($db, $edit_id, $orden_trabajo_id, $orden_tabajo_tipo_id, $fecha_programada, $maquina_id, $componente_id, $fecha_ultimo_mantenimiento, $frecuencia_dias,$frecuencia_verificacion_dias, $frecuencia_horas, $frecuencia_kilometros, $observaciones_orden, $idUser);
            
            print("OK;Orden de trabajo creada correctamente");
            
        break;//Fin caso 11
        
        case 12://Agregar un insumo a una orden de trabajo
            
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            
            $insumo_id=$obCon->normalizar($_REQUEST["insumo_id"]); 
            $orden_trabajo_id=$obCon->normalizar($_REQUEST["orden_trabajo_id"]); 
            $valor_unitario=$obCon->normalizar($_REQUEST["valor_unitario"]); 
            $cantidad=$obCon->normalizar($_REQUEST["cantidad"]); 
            
            if($empresa_id==''){
                exit("E1;No se recibió el id de la empresa");
            }
            if($insumo_id==''){
                exit("E1;No se recibió el id del insumo");
            }
            if($orden_trabajo_id==''){
                exit("E1;No se recibió el id de la orden de trabajo");
            }
            if(!is_numeric($valor_unitario) or $valor_unitario <= 0){
                exit("E1;El Valor unitario debe ser un número mayor a cero;TxtValorUnitario_".$insumo_id);
            }
            if(!is_numeric($cantidad) or $cantidad <= 0){
                exit("E1;La cantidad debe ser un número mayor a cero;TxtCantidad_".$insumo_id);
            }
                
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            
            $obCon->agregarInsumoOT($db, $orden_trabajo_id, $insumo_id, $cantidad, $valor_unitario, $idUser);
            
            exit("OK;Insumo agregado");
            
        break;//fin caso 12    
        
        
        case 13://cerrar orden de trabajo
            
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]); 
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            $orden_trabajo_id=$obCon->normalizar($_REQUEST["orden_trabajo_id"]);
            $fecha_cierre=$obCon->normalizar($_REQUEST["fecha_cierre"]);
            $verificacion_orden=$obCon->normalizar($_REQUEST["verificacion_orden"]);
            $horas_ultimo_mantenimiento=$obCon->normalizar($_REQUEST["horas_ultimo_mantenimiento"]);
            $kilometros_ultimo_mantenimiento=$obCon->normalizar($_REQUEST["kilometros_ultimo_mantenimiento"]);
            $tecnico_id=$obCon->normalizar($_REQUEST["tecnico_id"]);
            $observaciones_cierre=$obCon->normalizar($_REQUEST["observaciones_cierre"]);
            
            if($orden_trabajo_id==""){
                exit("E1;no se recibió el id de la orden de trabajo");
            }
            
            if($fecha_cierre==""){
                exit("E1;El campo fecha de cierre no puede estar vacío;fecha_cierre");
            }
            if($verificacion_orden==""){
                exit("E1;Debe informar si es una orden de verificacion;verificacion_orden");
            }
            
            if($tecnico_id==""){
                exit("E1;Debe seleccionar un tecnico;tecnico_id");
            }
            
            if($observaciones_cierre==""){
                exit("E1;Debe escribir las observaciones del cierre;observaciones_cierre");
            }
            
            $DatosOrden=$obCon->DevuelveValores("$db.ordenes_trabajo", "ID", $orden_trabajo_id); 
            $DatosComponente=$obCon->DevuelveValores("$db.equipos_componentes", "ID", $DatosOrden["componente_id"]); 
            
            if($DatosComponente["frecuencia_mtto_horas"]>0){
                if(!is_numeric($horas_ultimo_mantenimiento) or $horas_ultimo_mantenimiento<=0){
                    exit("E1;El campo Horas Registradas debe ser un numero mayor a cero;horas_ultimo_mantenimiento");
                }
                
            }
            
            if($DatosComponente["frecuencia_mtto_kilometros"]>0){
                if(!is_numeric($kilometros_ultimo_mantenimiento) or $kilometros_ultimo_mantenimiento<=0){
                    exit("E1;El campo Kilometros Registrados debe ser un numero mayor a cero;kilometros_ultimo_mantenimiento");
                }
                
            }
            
            $obCon->cerrar_orden_trabajo_preventivo($db, $DatosOrden, $DatosComponente, $orden_trabajo_id, $fecha_cierre, $verificacion_orden, $horas_ultimo_mantenimiento, $kilometros_ultimo_mantenimiento, $tecnico_id, $observaciones_cierre, $idUser);
            $nuevo_id=$obCon->getUniqId("ot_");
            if($verificacion_orden=="NO"){
                $Dias=$DatosComponente["frecuencia_mtto_dias"];
            }else{
                $Dias=$DatosComponente["frecuencia_verificacion_dias"];
            }
            $fecha_programada=$obCon->SumeDiasFecha($fecha_cierre, $Dias);
            $obCon->crearEditarOrdenTrabajo($db, "", $nuevo_id, $DatosOrden["tipo_mantenimiento"], $fecha_programada, $DatosOrden["maquina_id"], $DatosOrden["componente_id"], "", "","", "", "", $DatosOrden["observaciones_orden"], $idUser,0);
            
            $sql="SELECT * FROM $db.ordenes_trabajo_tareas WHERE orden_trabajo_id='".$DatosOrden["orden_trabajo_id"]."'";
            $Consulta=$obCon->Query($sql);
            while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                $obCon->agregarTareaOT($db, $nuevo_id, $DatosConsulta["tarea_id"]);
            }
            print("OK;Orden de trabajo creada correctamente");
            
        break;//Fin caso 13
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>