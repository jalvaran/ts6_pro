<?php

@session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
include_once("../../../modelo/php_conexion.php");
include_once("../../../constructores/paginas_constructor.php");
include_once("../clases/mantenimiento.class.php");

if( !empty($_REQUEST["Accion"]) ){
    $css =  new PageConstruct("", "", 1, "", 1, 0);
    $obCon = new Mantenimiento($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1:// dibujo el listado de las ordenes de trabajo
            
            $tabla="vista_ordenes_trabajo";
            $Limit=20;
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            
            $obCon->CrearVistaOrdenTrabajo($db);
            
            $Page=$obCon->normalizar($_REQUEST["Page"]);
            $NumPage=$obCon->normalizar($_REQUEST["Page"]);
            if($Page==''){
                $Page=1;
                $NumPage=1;
            }
            
            $BusquedasGenerales=$obCon->normalizar($_REQUEST["BusquedasGenerales"]);
            $fecha_inicial=$obCon->normalizar($_REQUEST["fecha_inicial"]);
            $fecha_final=$obCon->normalizar($_REQUEST["fecha_final"]);
            $cmb_tipo_mantenimiento=$obCon->normalizar($_REQUEST["cmb_tipo_mantenimiento"]);
            $cmb_estado=$obCon->normalizar($_REQUEST["cmb_estado"]);
            
            
            $Condicion=" WHERE ID>'0' ";
            
            if($BusquedasGenerales<>''){
                $Condicion.=" AND ( t1.Nombre like '%$BusquedasGenerales%' or t1.Codigo = '%$BusquedasGenerales%' or t1.Marca like '%$BusquedasGenerales%')";
            }
            
            if($fecha_inicial<>''){
                $Condicion.=" AND t1.fecha_programada >= '$fecha_inicial' ";
            }
            
            if($fecha_final<>''){
                $Condicion.=" AND t1.fecha_programada <= '$fecha_final' ";
            }
            
            if($cmb_tipo_mantenimiento<>''){
                $Condicion.=" AND t1.tipo_mantenimiento = '$cmb_tipo_mantenimiento' ";
            }
            
            if($cmb_estado<>''){
                $Condicion.=" AND t1.estado = '$cmb_estado' ";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM $tabla t1 $Condicion;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT t1.*
                  FROM $tabla t1 $Condicion LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
                    
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                
                    print('<div class="row widget-separator-1 mb-3">
                                <div class="col-md-3">
                                    <div class="widget-1">
                                        <div class="content">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h5 class="title">Ordenes de Trabajo</h5>
                                                    <span class="descr">Totales</span>
                                                </div>
                                                <div class="col text-right">
                                                    <div class="number text-primary">'.number_format($ResultadosTotales).'</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                
                                </div>
                                <div class="col-md-3">
                                
                                </div>
                                <div class="col-md-3">
                                
                                
                            ');
                   
                    $css->div("", "pull-right", "", "", "", "", "");
                        if($ResultadosTotales>$Limit){
                            $TotalPaginas= ceil($ResultadosTotales/$Limit);                               
                            print('<div class="btn-group">');
                            $disable='disabled="true"';
                            $Color="dark";
                            $NumPage1=$NumPage;
                            if($NumPage>1){
                                $disable="";
                                $Color="info";
                                $NumPage1=$NumPage-1;
                                print('<button class="btn btn-'.$Color.' btn-pill" onclick=CambiePagina(`1`,`'.$NumPage1.'`) style="cursor:pointer" '.$disable.'><i class="fa fa-chevron-left" '.$disable.'></i></button>');
                            }
                            
                            
                            $FuncionJS="onchange=CambiePagina(`1`);";
                            $css->select("CmbPage", "btn btn-light text-dark btn-pill", "CmbPage", "", "", $FuncionJS, "");

                                for($p=1;$p<=$TotalPaginas;$p++){
                                    if($p==$NumPage){
                                        $sel=1;
                                    }else{
                                        $sel=0;
                                    }

                                    $css->option("", "", "", $p, "", "",$sel);
                                        print($p);
                                    $css->Coption();

                                }

                            $css->Cselect();
                            $disable='disabled="true"';
                            $Color="dark";
                            if($ResultadosTotales>($PuntoInicio+$Limit)){
                                $disable="";
                                $Color="info";
                                $NumPage1=$NumPage+1;
                                print('<span class="btn btn-info btn-pill" onclick=CambiePagina(`1`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
                            }
                             
                            
                            print("</div>");
                        }    
                        $css->Cdiv();
                        $css->Cdiv();
                    $css->Cdiv();
                $css->Cdiv();
                   
                $css->CrearDiv("", "table-responsive mailbox-messages", "", 1, 1);
                    print('<table class="table table-hover table-striped">');
                        print('<thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>ID</th>
                                        <th>Fecha Programada</th>
                                        <th>Mantenimiento</th>
                                        <th>Máquina</th>                                        
                                        <th>Componente</th>
                                        <th>Ubicación</th>
                                        <th>Observaciones</th>
                                        <th>Estado</th>
                                        <th>Fecha de Cierre</th>
                                        <th>Técnico</th>                                        
                                        <th>Observaciones de Cierre</th>
                                        
                                    </tr>
                                </thead>');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                
                                print('<tr>');
                                    print("<td>");
                                        print('<a onclick="frm_crear_editar_registro(`'.$idItem.'`)" ><i class="icon-pencil text-info"></i></a>');
                                        print(' || <a onclick="ver_tareas_orden(`'.$idItem.'`)" title="Ver Tareas"  ><i class="fa fa-eye text-success"></i></a>');
                                        print(' || <a href="" target="_blank" title="Ver PDF"  ><i class="far fa-file-pdf text-error"></i></a>');
                                        print(' || <a onclick="form_cerrar_orden(`'.$idItem.'`)" title="Cerrar Orden"  ><i class="far fa-edit text-warning"></i></a>');
                                    print("</td>");
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["ID"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["fecha_programada"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["nombre_tipo_mantenimiento"]);
                                    print("</td>");                                    
                                    print("<td class='mailbox-subject text-success'>");
                                        print(" <strong>".$RegistrosTabla["nombre_maquina"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print(" <strong>".$RegistrosTabla["nombre_componente"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        print($RegistrosTabla["nombre_ubicacion"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print(" <strong>".$RegistrosTabla["Proceso"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        print(" <strong>".$RegistrosTabla["Ubicacion"]."</strong>");
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["observaciones_orden"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["nombre_estado"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        print(($RegistrosTabla["fecha_cierre"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject '>");
                                        print(($RegistrosTabla["nombre_tecnico"]));
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["observaciones_cierre"]));
                                    print("</td>");
                                    
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
            
            
            
        break; //Fin caso 1
        
        
        case 2: //Dibuja el formulario para crear o editar una orden de trabajo
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]);
                
            $css->frm_form_orden_mantenimiento($db,$edit_id);
        break; //Fin caso 2
    
        case 3: //Dibuja las tareas en una orden de trabajo
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $orden_trabajo_id=$obCon->normalizar($_REQUEST["orden_trabajo_id"]);
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            
            $css->CrearTabla();
                $css->FilaTabla(16);
                
                    $css->ColTabla("ID", 1);
                    $css->ColTabla("Código", 1);
                    $css->ColTabla("Tarea", 1);
                    $css->ColTabla("Tipo Tarea", 1);
                    $css->ColTabla("Eliminar", 1);
                $css->CierraFilaTabla();
                
                $sql="SELECT t2.*,t1.ID as ordenes_trabajo_tareas_id, t1.estado as ordenes_trabajo_tareas_estado  
                        FROM ordenes_trabajo_tareas t1 INNER JOIN catalogo_tareas t2 ON t1.tarea_id=t2.ID 
                        WHERE t1.orden_trabajo_id='$orden_trabajo_id' 
                            ";
                $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
                while($DatosTareas=$obCon->FetchAssoc($Consulta)){
                    $idItem=$DatosTareas["ordenes_trabajo_tareas_id"];
                    $css->FilaTabla(14);
                
                        $css->ColTabla($DatosTareas["ordenes_trabajo_tareas_id"], 1);
                        $css->ColTabla($DatosTareas["CodigoTarea"], 1);
                        $css->ColTabla($DatosTareas["NombreTarea"], 1);
                        $css->ColTabla($DatosTareas["TipoTarea"], 1);
                        print('<td style="text-align:center;color:red;font-size:18px;">');
                            print('<li class="far fa-times-circle" style="cursor:pointer;" onclick="eliminarItem(`1`,`'.$idItem.'`)"></li>');
                        print('</td>');
                    $css->CierraFilaTabla();
                }
            $css->CerrarTabla();
            
            
        break; //Fin caso 2
                     
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>