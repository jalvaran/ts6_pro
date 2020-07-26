DROP VIEW IF EXISTS `vista_ordenes_trabajo_tareas`;
CREATE VIEW vista_ordenes_trabajo_tareas AS
SELECT t1.*,
 (SELECT maquina_id FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.orden_trabajo_id) as maquina_id,
 (SELECT componente_id FROM ordenes_trabajo t2 WHERE t1.orden_trabajo_id=t2.orden_trabajo_id) as componente_id 
FROM ordenes_trabajo_tareas  t1;