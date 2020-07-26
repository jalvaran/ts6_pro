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
                $Condicion.=" AND ( t1.nombre_maquina like '%$BusquedasGenerales%' or t1.nombre_ubicacion LIKE '%$BusquedasGenerales%' or t1.nombre_componente like '%$BusquedasGenerales%' or t1.serie_componente like '%$BusquedasGenerales%')";
            }
            
            
            if($orden_tipo<>''){
                $Condicion.=" AND t1.tipo_mantenimiento = '$orden_tipo' ";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items 
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
                                        <th>Ver Ordenes</th>
                                        <th>Ver Tareas</th>
                                        <th>Ver Costos</th>
                                        <th>Tipo Mantenimiento</th>
                                        <th>Total de Ordenes</th>
                                        <th>Tiempo de Parada</th>
                                        <th>Máquina</th>
                                        <th>Código Máquina</th>
                                        <th>Tareas Maquina</th>
                                        <th>Ubicación</th>
                                        <th>Componente</th>
                                        <th>Serie Componente</th>                                        
                                        <th>Tareas Componente</th>                                         
                                    </tr>
                                </thead>');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["maquina_id"];
                                
                                print('<tr>');
                                   
                                    print("<td>");
                                        print('<a style="font-size:25px;text-align:center" title="Ver Adjuntos" onclick="listar_adjuntos_ot(`'.$idItem.'`,`div_modal_view`,`modal_view`)" ><i class="fa fa-paperclip text-primary"></i></a>');
                                                                                
                                    print("</td style='text-align:center'>");
                                    print("<td>");
                                        print('<a style="font-size:25px;text-align:center" title="Ver Adjuntos" onclick="listar_adjuntos_ot(`'.$idItem.'`,`div_modal_view`,`modal_view`)" ><i class="fa fa-paperclip text-primary"></i></a>');
                                                                                
                                    print("</td style='text-align:center'>");
                                    print("<td>");
                                        print('<a style="font-size:25px;text-align:center" title="Ver Adjuntos" onclick="listar_adjuntos_ot(`'.$idItem.'`,`div_modal_view`,`modal_view`)" ><i class="fa fa-paperclip text-primary"></i></a>');
                                                                                
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
                                    
                                    print("<td class='mailbox-subject text-primary'>");
                                        print(" <strong>".$RegistrosTabla["nombre_componente"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        print($RegistrosTabla["serie_componente"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".number_format($RegistrosTabla["total_tareas_componentes"])."</strong>");
                                    print("</td>");                                     
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
            
            
            
        break; //Fin caso 1
        
                             
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>