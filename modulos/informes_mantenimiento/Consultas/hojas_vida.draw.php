<?php

@session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
include_once("../../../modelo/php_conexion.php");
include_once("../../../constructores/paginas_constructor.php");
include_once("../clases/informes_mantenimientos.class.php");

if( !empty($_REQUEST["Accion"]) ){
    $css =  new PageConstruct("", "", 1, "", 1, 0);
    $obCon = new InformesMantenimientos($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1:// dibujo el listado de las ordenes de trabajo
            
            $tabla="vista_hojas_vida_maquinas";
            $Limit=20;
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            
            
            $Page=$obCon->normalizar($_REQUEST["Page"]);
            $NumPage=$obCon->normalizar($_REQUEST["Page"]);
            if($Page==''){
                $Page=1;
                $NumPage=1;
            }
            
            $BusquedasGenerales=$obCon->normalizar($_REQUEST["BusquedasGenerales"]);
            $fecha_inicial=$obCon->normalizar($_REQUEST["fecha_inicial"]);
            $fecha_final=$obCon->normalizar($_REQUEST["fecha_final"]);
            $orden_tipo=$obCon->normalizar($_REQUEST["orden_tipo"]);
            
            $obCon->crear_vista_hojas_vida($db,$fecha_inicial,$fecha_final);
                        
            $Condicion=" WHERE tipo_mantenimiento<>'' ";
            
            if($BusquedasGenerales<>''){
                $Condicion.=" AND ( t1.nombre_maquina like '%$BusquedasGenerales%' or t1.nombre_ubicacion LIKE '%$BusquedasGenerales%' )";
            }
            
            
            if($orden_tipo<>''){
                $Condicion.=" AND t1.tipo_mantenimiento = '$orden_tipo' ";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items,SUM(total_costos) as  TotalCostos 
                   FROM $tabla t1 $Condicion;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
            $TotalCostos  = $totales['TotalCostos'];          
            $sql="SELECT t1.*
                  FROM $tabla t1 $Condicion LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $CondicionBase64= base64_encode(urlencode($Condicion));
            $link_exportar_excel="procesadores/informes_mantenimiento.process.php?Accion=1&empresa_id=$empresa_id&c=$CondicionBase64";
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                
                    print('<div class="row widget-separator-1 mb-3">
                                <div class="col-md-3">
                                    <div class="widget-1">
                                        <div class="content">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h5 class="title">Registros</h5>
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
                                    <div class="widget-1">
                                        <div class="content">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h5 class="title">Costos</h5>
                                                    <span class="descr">Totales</span>
                                                </div>
                                                <div class="col text-right">
                                                    <div class="number text-primary">'.number_format($TotalCostos).'</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="widget-1">
                                        <div class="content">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h5 class="title">Exportar a Excel</h5>
                                                    <span class="descr"></span>
                                                </div>
                                                <div class="col text-right">
                                                    <div class="text-success" style="font-size:50px"><a href="'.$link_exportar_excel.'" target="_blank"><li class="fa fa-file-excel"></li></a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                        <th>Ver Ordenes</th>
                                        <th>Ver Tareas</th>
                                        <th>Ver Costos</th>
                                        <th>Tipo Mantenimiento</th>
                                        <th>Total de Ordenes</th>
                                        <th>Tiempo de Parada</th>
                                        <th>Costos Totales</th>
                                        <th>Máquina</th>
                                        <th>Código Máquina</th>
                                        <th>Tareas Maquina</th>
                                        <th>Ubicación</th>
                                                                            
                                                                            
                                    </tr>
                                </thead>');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["maquina_id"];
                                
                                print('<tr>');
                                   
                                    print("<td style='text-align:center'>");
                                        print('<a style="font-size:25px;text-align:center" title="Ver Ordenes de trabajo" onclick="listar_ordenes_maquina(`'.$idItem.'`,`'.$RegistrosTabla["tipo_mantenimiento"].'`)" ><i class="far fa-list-alt text-primary"></i></a>');
                                                                                
                                    print("</td >");
                                    print("<td style='text-align:center'>");
                                        print('<a style="font-size:25px;text-align:center" title="Ver Adjuntos" onclick="listar_tareas_maquina(`'.$idItem.'`,`'.$RegistrosTabla["tipo_mantenimiento"].'`)" ><i class="fa fa-cog text-flickr"></i></a>');
                                                                                
                                    print("</td >");
                                    print("<td style='text-align:center'>");
                                        print('<a style="font-size:25px;text-align:center" title="Ver Adjuntos" onclick="listar_costos_maquina(`'.$idItem.'`,`'.$RegistrosTabla["tipo_mantenimiento"].'`)" ><i class="fa fa-dollar-sign text-success"></i></a>');
                                                                                
                                    print("</td>");
                                    
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["nombre_tipo_mantenimiento"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".number_format($RegistrosTabla["cantidad_ordenes"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".number_format($RegistrosTabla["total_tiempo_parada"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".number_format($RegistrosTabla["total_costos"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["nombre_maquina"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".($RegistrosTabla["codigo_maquina"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".number_format($RegistrosTabla["total_tareas_maquinas"])."</strong>");
                                    print("</td>");                                    
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["nombre_ubicacion"]);
                                    print("</td>");                                    
                                    
                                                                         
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
            
        break; //Fin caso 1
        
        case 2://Listar las ordenes de trabajo
            
            $maquina_id=$obCon->normalizar($_REQUEST["maquina_id"]);
            $tipo_mantenimiento=$obCon->normalizar($_REQUEST["tipo_mantenimiento"]);
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            $DatosMaquina=$obCon->DevuelveValores("$db.equipos_maquinas", "ID", $maquina_id);
            $css->CrearTabla();
            
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Ordenes de trabajo ejecutadas para la maquina: ".$DatosMaquina["Nombre"]."</strong>", 4);
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>PDF</strong>", 1);
                    $css->ColTabla("<strong>ID</strong>", 1);
                    $css->ColTabla("<strong>Fecha de Cierre</strong>", 1);
                    $css->ColTabla("<strong>Observaciones de Cierre</strong>", 1);
                    
                $css->CierraFilaTabla();
                
                $sql="SELECT * FROM $db.ordenes_trabajo WHERE maquina_id='$maquina_id' AND tipo_mantenimiento='$tipo_mantenimiento' AND (Estado=3 or Estado=4)";
                $Consulta=$obCon->Query($sql);
                
                while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                    $idItem=$RegistrosTabla["ID"];
                    $css->FilaTabla(16);
                        print("<td style='text-align:center'>");
                            $link="../../general/Consultas/PDF_Documentos.draw.php?idDocumento=1&empresa_id=1&id=$idItem";
                            print('<a style="font-size:25px;color:green" href="'.$link.'" target="_blank" title="Ver PDF" title="Ver PDF" ><i class="far fa-file-pdf text-error"></i></a>');
                        print("</td>");
                        print("<td class='mailbox-name'>");
                            print($RegistrosTabla["ID"]);
                        print("</td>");
                        print("<td class='mailbox-name'>");
                            print($RegistrosTabla["fecha_cierre"]);
                        print("</td>");
                        print("<td class='mailbox-name'>");
                            print($RegistrosTabla["observaciones_cierre"]);
                        print("</td>");
                    $css->CierraFilaTabla();
                }
                
                
            $css->CerrarTabla();
            
        break;//Fin caso 2    
        
        case 3://Listar las tareas de una maquina
            
            $maquina_id=$obCon->normalizar($_REQUEST["maquina_id"]);
            $tipo_mantenimiento=$obCon->normalizar($_REQUEST["tipo_mantenimiento"]);
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            $DatosMaquina=$obCon->DevuelveValores("$db.equipos_maquinas", "ID", $maquina_id);
            $css->CrearTabla();
            
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Tareas realizadas en la maquina: ".$DatosMaquina["Nombre"]."</strong>", 4);
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    
                    $css->ColTabla("<strong>ID</strong>", 1);
                    $css->ColTabla("<strong>Nombre de la Tarea</strong>", 1);
                    
                    
                $css->CierraFilaTabla();
                
                $sql="SELECT DISTINCT (tarea_id), nombre_tarea,ID FROM $db.vista_ordenes_trabajo_tareas WHERE maquina_id='$maquina_id' AND tipo_mantenimiento='$tipo_mantenimiento' AND (estado_orden_trabajo=3 or estado_orden_trabajo=4)";
                $Consulta=$obCon->Query($sql);
                
                while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                    $idItem=$RegistrosTabla["ID"];
                    $css->FilaTabla(16);
                        
                        print("<td class='mailbox-name'>");
                            print($RegistrosTabla["ID"]);
                        print("</td>");
                        print("<td class='mailbox-name'>");
                            print($RegistrosTabla["nombre_tarea"]);
                        print("</td>");
                        
                    $css->CierraFilaTabla();
                }
                
                
            $css->CerrarTabla();
            
        break;//Fin caso 3
        
        case 4://Listar los costos en mantenimientos de una maquina
            
            $maquina_id=$obCon->normalizar($_REQUEST["maquina_id"]);
            $tipo_mantenimiento=$obCon->normalizar($_REQUEST["tipo_mantenimiento"]);
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            $DatosMaquina=$obCon->DevuelveValores("$db.equipos_maquinas", "ID", $maquina_id);
            $css->CrearTabla();
            
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Costos en mantenimientos de la maquina: ".$DatosMaquina["Nombre"]."</strong>", 4);
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    
                    $css->ColTabla("<strong>Codigo Insumo</strong>", 1);
                    $css->ColTabla("<strong>Nombre</strong>", 1);
                    $css->ColTabla("<strong>Cantidad</strong>", 1);
                    $css->ColTabla("<strong>Total</strong>", 1);
                    
                $css->CierraFilaTabla();
                
                $sql="SELECT nombre_insumo,codigo_insumo,SUM(cantidad) as cantidad, SUM(total) as total FROM $db.vista_ordenes_trabajo_costos WHERE maquina_id='$maquina_id' AND tipo_mantenimiento='$tipo_mantenimiento' AND (estado_orden_trabajo=3 or estado_orden_trabajo=4) GROUP BY codigo_insumo";
                
                $Consulta=$obCon->Query($sql);
                $suma_cantidad=0;
                $suma_total=0;
                while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                    $suma_cantidad=$suma_cantidad+$RegistrosTabla["cantidad"];
                    $suma_total=$suma_total+$RegistrosTabla["total"];
                    $css->FilaTabla(16);
                        
                        print("<td class='mailbox-name'>");
                            print($RegistrosTabla["codigo_insumo"]);
                        print("</td>");
                        print("<td class='mailbox-name'>");
                            print($RegistrosTabla["nombre_insumo"]);
                        print("</td>");
                        print("<td class='mailbox-name'>");
                            print($RegistrosTabla["cantidad"]);
                        print("</td>");
                        print("<td class='mailbox-name'>");
                            print(number_format($RegistrosTabla["total"]));
                        print("</td>");
                        
                    $css->CierraFilaTabla();
                }
                
                $css->FilaTabla(16);
                    
                    
                    $css->ColTabla("<strong>Totales</strong>", 2,"R");
                    $css->ColTabla(number_format($suma_cantidad), 1);
                    $css->ColTabla(number_format($suma_total), 1);
                    
                $css->CierraFilaTabla();
                
            $css->CerrarTabla();
            
        break;//Fin caso 4
        
                             
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>