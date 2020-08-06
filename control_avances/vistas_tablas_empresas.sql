DROP VIEW IF EXISTS `vista_ordenes_trabajo_tareas`;
CREATE VIEW vista_ordenes_trabajo_tareas AS
SELECT t1.*,
    (SELECT NombreTarea FROM catalogo_tareas t3 WHERE t3.ID=t1.tarea_id) as nombre_tarea,
    (SELECT maquina_id FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.orden_trabajo_id) as maquina_id,
    (SELECT componente_id FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.orden_trabajo_id) as componente_id,
    (SELECT tipo_mantenimiento FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.orden_trabajo_id) as tipo_mantenimiento,
    (SELECT Estado FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.orden_trabajo_id) as estado_orden_trabajo
    
FROM ordenes_trabajo_tareas  t1;

DROP VIEW IF EXISTS `vista_ordenes_trabajo_costos`;
CREATE VIEW vista_ordenes_trabajo_costos AS
SELECT t1.*,
    (SELECT DescripcionPrimaria FROM equipos_partes t3 WHERE t3.ID=t1.insumo_id) as nombre_insumo,
    (SELECT Codigo FROM equipos_partes t3 WHERE t3.ID=t1.insumo_id) as codigo_insumo,
    (SELECT maquina_id FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.ID) as maquina_id,
    (SELECT componente_id FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.ID) as componente_id,
    (SELECT tipo_mantenimiento FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.ID) as tipo_mantenimiento,
    (SELECT Estado FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.ID) as estado_orden_trabajo
    
FROM ordenes_trabajo_insumos  t1;

DROP VIEW IF EXISTS `vista_ordenes_trabajo_fallas`;
CREATE VIEW vista_ordenes_trabajo_fallas AS
SELECT t1.*,  
    (SELECT tiempo_parada FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.ID) as tiempo_parada,
    (SELECT fecha_cierre FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.ID) as fecha_cierre,
    (SELECT maquina_id FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.ID) as maquina_id,   
    (SELECT Nombre FROM equipos_maquinas t3 WHERE t3.ID=(SELECT maquina_id)) as nombre_maquina, 
    (SELECT ubicacion_id FROM equipos_maquinas t3 WHERE t3.ID=(SELECT maquina_id)) as ubicacion_id, 
    (SELECT NombreSeccion FROM catalogo_secciones t4 WHERE t4.ID=(SELECT ubicacion_id)) as nombre_ubicacion,
    (SELECT proceso_id FROM equipos_maquinas t3 WHERE t3.ID=(SELECT maquina_id)) as proceso_id, 
    (SELECT Nombre FROM catalogo_procesos t4 WHERE t4.ID=(SELECT proceso_id)) as nombre_proceso,
    (SELECT unidadNegocio_id FROM catalogo_procesos t4 WHERE t4.ID=(SELECT proceso_id)) as unidad_negocio_id,
    (SELECT UnidadNegocio FROM catalogo_unidades_negocio t5 WHERE t5.ID=(SELECT unidad_negocio_id)) as nombre_unidad_negocio,
    (SELECT Estado FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.ID) as estado_orden_trabajo
    
FROM ordenes_trabajo_fallas  t1;

