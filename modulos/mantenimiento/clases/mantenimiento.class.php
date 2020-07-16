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
    
    /**
     * Fin Clase
     */
}
