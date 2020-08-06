<?php
if(file_exists("../../../modelo/php_conexion.php")){
    include_once("../../../modelo/php_conexion.php");
}
class InformesMantenimientos extends conexion{
    
    public function crear_vista_hojas_vida($db,$fecha_inicial,$fecha_final) {
        $condicion_fecha="";
        if($fecha_inicial<>""){
            $condicion_fecha.=" AND fecha_cierre >= '$fecha_inicial' ";
        }
        if($fecha_final<>""){
            $condicion_fecha.=" AND fecha_cierre <= '$fecha_final' ";
        }
        $principalDb=DB;
        $sql="DROP VIEW IF EXISTS `vista_hojas_vida_maquinas`;";
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
        
        $sql="CREATE VIEW vista_hojas_vida_maquinas AS
                SELECT t1.tipo_mantenimiento,COUNT(t1.ID) as cantidad_ordenes,t1.maquina_id,t1.componente_id,SUM(t1.tiempo_parada) as total_tiempo_parada,
                    (SELECT nombre_estado FROM $principalDb.ordenes_trabajo_estados t2 WHERE t2.ID=t1.estado LIMIT 1) AS nombre_estado,
                    (SELECT tipo_mantenimiento FROM $principalDb.ordenes_trabajo_tipo_mantenimiento t3 WHERE t3.ID=t1.tipo_mantenimiento LIMIT 1) AS nombre_tipo_mantenimiento,
                    
                    (SELECT Nombre FROM equipos_maquinas t5 WHERE t5.ID=t1.maquina_id LIMIT 1) AS nombre_maquina,
                    (SELECT Codigo FROM equipos_maquinas t5 WHERE t5.ID=t1.maquina_id LIMIT 1) AS codigo_maquina,
                    (SELECT ubicacion_id FROM equipos_maquinas t5 WHERE t5.ID=t1.maquina_id LIMIT 1) AS ubicacion_id,
                    (SELECT NombreSeccion FROM catalogo_secciones t6 WHERE t6.ID=(SELECT ubicacion_id) LIMIT 1) AS nombre_ubicacion,
                    (SELECT Nombre FROM equipos_componentes t7 WHERE t7.ID=t1.componente_id LIMIT 1) AS nombre_componente,
                    (SELECT NumeroSerie FROM equipos_componentes t7 WHERE t7.ID=t1.componente_id LIMIT 1) AS serie_componente,
                    (SELECT COUNT(*) FROM vista_ordenes_trabajo_tareas t8 WHERE t8.maquina_id=t1.maquina_id AND t8.tipo_mantenimiento=t1.tipo_mantenimiento LIMIT 1) AS total_tareas_maquinas, 
                    
                    (SELECT SUM(total) FROM vista_ordenes_trabajo_costos t8 WHERE t8.maquina_id=t1.maquina_id AND t8.tipo_mantenimiento=t1.tipo_mantenimiento LIMIT 1) AS total_costos
                    
                FROM `ordenes_trabajo` t1 WHERE t1.tipo_mantenimiento<=2 AND (t1.Estado=3 or t1.Estado=4) $condicion_fecha GROUP BY t1.tipo_mantenimiento,t1.maquina_id";
        
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
    }
    
    public function crear_vistas_indicadores_gestion($db,$fecha_inicial,$fecha_final) {
        $principalDb=DB;
        $sql="DROP VIEW IF EXISTS `vista_indicadores_ordenes_trabajo`;";
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
        $Condicion=" WHERE (t1.Estado=3 or t1.Estado=4) AND (t1.tipo_mantenimiento=1 or t1.tipo_mantenimiento=2) ";
        if($fecha_inicial<>''){
            $Condicion.=" AND (t1.fecha_cierre >= '$fecha_inicial') ";
        }
        if($fecha_final<>''){
            $Condicion.=" AND (t1.fecha_cierre <= '$fecha_final') ";
        }
        
        $sql="CREATE VIEW vista_indicadores_ordenes_trabajo AS
                SELECT t1.tipo_mantenimiento,COUNT(t1.ID) as total_ordenes, SUM(t1.tiempo_parada) as total_tiempo_parada,
                (SELECT proceso_id FROM equipos_maquinas t2 WHERE t1.maquina_id=t2.ID) as proceso_ot_id,
                (SELECT Nombre FROM catalogo_procesos t3 WHERE t3.ID=(SELECT proceso_ot_id)) as nombre_proceso,
                (SELECT unidadNegocio_id FROM catalogo_procesos t3 WHERE t3.ID=(SELECT proceso_ot_id)) as unidad_negocio_id,
                (SELECT UnidadNegocio FROM catalogo_unidades_negocio t4 WHERE t4.ID=(SELECT unidad_negocio_id)) as nombre_unidad_negocio,
                (SELECT SUM(total) FROM vista_ordenes_trabajo_costos t5 WHERE t5.tipo_mantenimiento=t1.tipo_mantenimiento AND (t5.estado_orden_trabajo=3 or t5.estado_orden_trabajo=4 )) as total_costos   
                FROM `ordenes_trabajo` t1 $Condicion   
                GROUP BY t1.tipo_mantenimiento,t1.maquina_id;";
        //print($sql);
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
    }
    
    /**
     * Fin Clase
     */
}
