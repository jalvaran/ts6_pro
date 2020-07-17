<?php
if(file_exists("../../../modelo/php_conexion.php")){
    include_once("../../../modelo/php_conexion.php");
}
class Mantenimiento extends conexion{
    
    public function CrearVistaOrdenTrabajo($db) {
        $principalDb=DB;
        $sql="DROP VIEW IF EXISTS `vista_ordenes_trabajo`;";
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
        
        $sql="CREATE VIEW vista_ordenes_trabajo AS
                SELECT t1.*,
                    (SELECT nombre_estado FROM $principalDb.ordenes_trabajo_estados t2 WHERE t2.ID=t1.estado LIMIT 1) AS nombre_estado,
                    (SELECT tipo_mantenimiento FROM $principalDb.ordenes_trabajo_tipo_mantenimiento t3 WHERE t3.ID=t1.tipo_mantenimiento LIMIT 1) AS nombre_tipo_mantenimiento,
                    (SELECT NombreTecnico FROM catalogo_tecnicos t4 WHERE t4.ID=t1.tecnico_id LIMIT 1) AS nombre_tecnico, 
                    (SELECT Nombre FROM equipos_maquinas t5 WHERE t5.ID=t1.maquina_id LIMIT 1) AS nombre_maquina,
                    (SELECT Codigo FROM equipos_maquinas t5 WHERE t5.ID=t1.maquina_id LIMIT 1) AS codigo_maquina,
                    (SELECT ubicacion_id FROM equipos_maquinas t5 WHERE t5.ID=t1.maquina_id LIMIT 1) AS ubicacion_id,
                    (SELECT NombreSeccion FROM catalogo_secciones t6 WHERE t6.ID=(SELECT ubicacion_id) LIMIT 1) AS nombre_ubicacion,
                    (SELECT Nombre FROM equipos_componentes t7 WHERE t7.ID=t1.componente_id LIMIT 1) AS nombre_componente,
                    (SELECT NumeroSerie FROM equipos_componentes t7 WHERE t7.ID=t1.componente_id LIMIT 1) AS serie_componente
                    
                FROM `ordenes_trabajo` t1 ORDER BY `estado`,`tipo_mantenimiento`;";
        
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
    }
    
    public function agregarTareaOT($db,$orden_trabajo_id,$tarea_id) {
        $Tabla="ordenes_trabajo_tareas";
        $created=date("Y-m-d H:i:s");        
        $Datos["orden_trabajo_id"]=$orden_trabajo_id;
        $Datos["tarea_id"]=$tarea_id;
        $Datos["estado"]=1;
        $Datos["created"]=$created;
        $sql=$this->getSQLInsert($Tabla, $Datos);
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
    }
    
    public function crearEditarOrdenTrabajo($db,$edit_id,$orden_trabajo_id,$orden_tabajo_tipo_id,$fecha_programada,$maquina_id,$componente_id,$fecha_ultimo_mantenimiento,$frecuencia_dias,$frecuencia_horas,$frecuencia_kilometros,$observaciones_orden,$idUser,$ActualizaComponente=1) {
        $Tabla="ordenes_trabajo";
        $created=date("Y-m-d H:i:s");   
        
        $Datos["fecha_programada"]=$fecha_programada;
        $Datos["fecha_cierre"]="";
        $Datos["tipo_mantenimiento"]=$orden_tabajo_tipo_id;
        $Datos["orden_trabajo_id"]=$orden_trabajo_id;
        $Datos["maquina_id"]=$maquina_id;
        $Datos["componente_id"]=$componente_id;
        $Datos["observaciones_orden"]=$observaciones_orden;
        $Datos["observaciones_cierre"]="";
        $Datos["observaciones_anulacion"]="";
        $Datos["tecnico_id"]=0;
        $Datos["estado"]=2;
        $Datos["usuario_creador_id"]=$idUser;
        $Datos["usuario_anulacion_id"]=0;
        
        $Datos["created"]=$created;
        if($edit_id==''){
            $sql=$this->getSQLInsert($Tabla, $Datos);
        }else{
            $sql=$this->getSQLUpdate($Tabla, $Datos);
            $sql.=" WHERE ID='$edit_id'";
        }
        
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
        if($orden_tabajo_tipo_id==2 and $ActualizaComponente==1){  //si es una orden de mantenimiento preventivo, actualizo los valores del componente
            $sql="UPDATE equipos_componentes 
                    SET fecha_ultimo_mantenimiento='$fecha_ultimo_mantenimiento', frecuencia_mtto_dias='$frecuencia_dias', frecuencia_mtto_horas='$frecuencia_horas',frecuencia_mtto_kilometros='$frecuencia_kilometros'
                    WHERE ID='$componente_id'

                         ";
            $this->QueryExterno($sql, HOST, USER, PW, $db, "");
        }
        
        if($orden_tabajo_tipo_id==1){//Si es una orden de trabajo de mantenimiento correctivo borro las tareas que se hubiesen podido agregar
            $this->BorraReg($db.".ordenes_trabajo_tareas", "orden_trabajo_id", $orden_trabajo_id);
        }
    }
    
    public function agregarInsumoOT($db,$orden_trabajo_id,$insumo_id,$cantidad,$valor_unitario,$idUser) {
        $Tabla="ordenes_trabajo_insumos";
        $created=date("Y-m-d H:i:s");        
        $Datos["orden_trabajo_id"]=$orden_trabajo_id;
        $Datos["insumo_id"]=$insumo_id;
        $Datos["valor_unitario"]=$valor_unitario;        
        $Datos["cantidad"]=$cantidad;
        $Datos["total"]=round($valor_unitario*$cantidad,2);
        $Datos["idUser"]=$idUser;
        $Datos["created"]=$created;
        $sql=$this->getSQLInsert($Tabla, $Datos);
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
    }
    
    public function cerrar_orden_trabajo_preventivo($db,$DatosOrden,$DatosComponente,$orden_trabajo_id,$fecha_cierre,$verificacion_orden,$horas_ultimo_mantenimiento,$kilometros_ultimo_mantenimiento,$tecnico_id,$observaciones_cierre,$idUser) {
        
        $sql="UPDATE ordenes_trabajo SET fecha_cierre='$fecha_cierre', 
                observaciones_cierre='$observaciones_cierre',tecnico_id='$tecnico_id', usuario_cierre_id='$idUser',estado=3 
               WHERE ID='$orden_trabajo_id';
                ";
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
        
        if($verificacion_orden=='NO'){
            $setFrecuenciaHoras="";
            if($DatosComponente["frecuencia_mtto_horas"]>0){
                $setFrecuenciaHoras="horas_ultimo_mantenimiento='$horas_ultimo_mantenimiento',";
            }
            $setFrecuenciaKilometros="";
            if($DatosComponente["frecuencia_mtto_kilometros"]>0){
                $setFrecuenciaKilometros="kilometros_ultimo_mantenimiento='$kilometros_ultimo_mantenimiento',";
            }
            $diasDiferencia=$this->obtenerDiferenciaFechasDias($fecha_cierre , date("Y-m-d"));
            
            $sql="UPDATE equipos_componentes SET fecha_ultimo_mantenimiento='$fecha_cierre',
                    dias_ultimo_mantenimiento='$diasDiferencia',
                    $setFrecuenciaHoras
                    $setFrecuenciaKilometros
                    usuario_id_update='$idUser'    
                    WHERE ID='".$DatosComponente["ID"]."'
                       ";
            $this->QueryExterno($sql, HOST, USER, PW, $db, "");
        }
        
        if($verificacion_orden=='SI'){
            $setFrecuenciaHoras="";
            if($DatosComponente["frecuencia_mtto_horas"]>0){
                $setFrecuenciaHoras="horas_trabajo='$horas_ultimo_mantenimiento',";
            }
            $setFrecuenciaKilometros="";
            if($DatosComponente["frecuencia_mtto_kilometros"]>0){
                $setFrecuenciaKilometros="kilometros_trabajo='$kilometros_ultimo_mantenimiento',";
            }
            if($DatosComponente["frecuencia_mtto_kilometros"]>0 or $DatosComponente["frecuencia_mtto_horas"]>0){
                $sql="UPDATE equipos_componentes SET  
                    $setFrecuenciaHoras
                    $setFrecuenciaKilometros
                    usuario_id_update='$idUser'     
                    WHERE ID='".$DatosComponente["ID"]."'
                       ";
                $this->QueryExterno($sql, HOST, USER, PW, $db, "");
            }
            
        }
        
    }
    
    /**
     * Fin Clase
     */
}
