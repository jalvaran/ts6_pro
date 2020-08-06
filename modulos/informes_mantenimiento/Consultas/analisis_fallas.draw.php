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
        
        case 1:// dibujo el listado general de las fallas
            
            $tabla="vista_ordenes_trabajo_fallas";
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
            $unidad_negocio_id=$obCon->normalizar($_REQUEST["unidad_negocio_id"]);
            $proceso_id=$obCon->normalizar($_REQUEST["proceso_id"]);
            
            $obCon->crear_vista_hojas_vida($db,$fecha_inicial,$fecha_final);
                        
            $Condicion=" WHERE (estado_orden_trabajo='3' or estado_orden_trabajo='4') ";
            
            if($BusquedasGenerales<>''){
                $Condicion.=" AND ( t1.nombre_maquina like '%$BusquedasGenerales%' or t1.nombre_ubicacion LIKE '%$BusquedasGenerales%' )";
            }
            
            
            if($unidad_negocio_id<>''){
                $Condicion.=" AND t1.unidad_negocio_id = '$unidad_negocio_id' ";
            }
            
            if($proceso_id<>''){
                $Condicion.=" AND t1.unidad_negocio_id = '$proceso_id' ";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items  
                   FROM $tabla t1 $Condicion;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
            $DataBaseGeneral=DB;  
            $sql="SELECT t1.*,
                    (SELECT Falla FROM $DataBaseGeneral.catalogo_fallas t2 WHERE t2.ID=t1.falla_id LIMIT 1) as nombre_falla,
                    (SELECT Causa FROM $DataBaseGeneral.catalogo_causas t3 WHERE t3.ID=t1.causa_falla_id LIMIT 1) as nombre_causa    
                  FROM $tabla t1 $Condicion ORDER BY tiempo_parada DESC LIMIT $PuntoInicio,$Limit ";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $CondicionBase64= base64_encode(urlencode($Condicion));
            $link_exportar_excel="procesadores/informes_mantenimiento.process.php?Accion=2&empresa_id=$empresa_id&c=$CondicionBase64";
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
                                        <th>ID</th>
                                        <th>Maquina</th>
                                        <th>Ubicacion</th>
                                        <th>Tiempo de parada</th>
                                        <th>Falla</th>
                                        <th>Causa</th>
                                        <th>Proceso</th>
                                        <th>Unidad de Negocio</th>
                                                                            
                                    </tr>
                                </thead>');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                
                                print('<tr>');
                                   
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["ID"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        
                                        print("<strong>".($RegistrosTabla["nombre_maquina"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-success'>");
                                        
                                        print("<strong>".($RegistrosTabla["nombre_ubicacion"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".number_format($RegistrosTabla["tiempo_parada"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".($RegistrosTabla["nombre_falla"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["nombre_causa"]."</strong>");
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject text-success'>");
                                        print("<strong>".$RegistrosTabla["nombre_unidad_negocio"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["nombre_proceso"]."</strong>");
                                    print("</td>");
                                                                         
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
            
        break; //Fin caso 1
        
        case 2:// dibujo listado de fallas mas frecuentes
            
            $tabla="vista_ordenes_trabajo_fallas";
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
            $unidad_negocio_id=$obCon->normalizar($_REQUEST["unidad_negocio_id"]);
            $proceso_id=$obCon->normalizar($_REQUEST["proceso_id"]);
            
            $obCon->crear_vista_hojas_vida($db,$fecha_inicial,$fecha_final);
                        
            $Condicion=" WHERE (estado_orden_trabajo='3' or estado_orden_trabajo='4') ";
            
            if($BusquedasGenerales<>''){
                //$Condicion.=" AND ( t1.nombre_maquina like '%$BusquedasGenerales%' or t1.nombre_ubicacion LIKE '%$BusquedasGenerales%' )";
            }
            
            
            if($unidad_negocio_id<>''){
                $Condicion.=" AND t1.unidad_negocio_id = '$unidad_negocio_id' ";
            }
            
            if($proceso_id<>''){
                $Condicion.=" AND t1.unidad_negocio_id = '$proceso_id' ";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items  
                   FROM $tabla t1 $Condicion;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
            $DataBaseGeneral=DB;  
            $sql="SELECT count(*) as total_fallas, falla_id, causa_falla_id, 
                    (SELECT Falla FROM $DataBaseGeneral.catalogo_fallas t2 WHERE t2.ID=t1.falla_id LIMIT 1) as nombre_falla,
                    (SELECT Causa FROM $DataBaseGeneral.catalogo_causas t3 WHERE t3.ID=t1.causa_falla_id LIMIT 1) as nombre_causa    
                  FROM $tabla t1 $Condicion GROUP BY falla_id, causa_falla_id ORDER BY total_fallas DESC LIMIT $PuntoInicio,$Limit;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $CondicionBase64= base64_encode(urlencode($Condicion));
            $link_exportar_excel="procesadores/informes_mantenimiento.process.php?Accion=3&empresa_id=$empresa_id&c=$CondicionBase64";
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
                                        
                                        <th>No. de Fallas</th>
                                        <th>Falla</th>
                                        <th>Causa</th>
                                                                                                                    
                                    </tr>
                                </thead>');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                
                                print('<tr>');
                                   
                                    
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".number_format($RegistrosTabla["total_fallas"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".($RegistrosTabla["nombre_falla"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["nombre_causa"]."</strong>");
                                    print("</td>");
                                    
                                                                                                             
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
            
        break; //Fin caso 2
        
        case 3:// dibujo listado de maquinas que mas fallan
            
            $tabla="vista_ordenes_trabajo_fallas";
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
            $unidad_negocio_id=$obCon->normalizar($_REQUEST["unidad_negocio_id"]);
            $proceso_id=$obCon->normalizar($_REQUEST["proceso_id"]);
            
            $obCon->crear_vista_hojas_vida($db,$fecha_inicial,$fecha_final);
                        
            $Condicion=" WHERE (estado_orden_trabajo='3' or estado_orden_trabajo='4') ";
            
            if($BusquedasGenerales<>''){
                $Condicion.=" AND ( t1.nombre_maquina like '%$BusquedasGenerales%' or t1.nombre_ubicacion LIKE '%$BusquedasGenerales%' )";
            }
            
            
            if($unidad_negocio_id<>''){
                $Condicion.=" AND t1.unidad_negocio_id = '$unidad_negocio_id' ";
            }
            
            if($proceso_id<>''){
                $Condicion.=" AND t1.unidad_negocio_id = '$proceso_id' ";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items  
                   FROM $tabla t1 $Condicion;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
            $DataBaseGeneral=DB;  
            $sql="SELECT count(*) as total_fallas, nombre_maquina,nombre_ubicacion,nombre_unidad_negocio,nombre_proceso  
                    
                  FROM $tabla t1 $Condicion GROUP BY maquina_id  ORDER BY total_fallas DESC LIMIT $PuntoInicio,$Limit;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $CondicionBase64= base64_encode(urlencode($Condicion));
            $link_exportar_excel="procesadores/informes_mantenimiento.process.php?Accion=4&empresa_id=$empresa_id&c=$CondicionBase64";
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
                                        
                                        <th>No. de Fallas</th>
                                        <th>Maquina</th>
                                        <th>Ubicacion</th>
                                        <th>Unidad de Negocio</th>
                                        <th>Proceso</th>                                                                            
                                    </tr>
                                </thead>');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                
                                print('<tr>');
                                   
                                    
                                    print("<td class='mailbox-subject text-flickr'>");
                                        
                                        print("<strong>".number_format($RegistrosTabla["total_fallas"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        
                                        print("<strong>".($RegistrosTabla["nombre_maquina"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["nombre_ubicacion"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-success'>");
                                        
                                        print("<strong>".($RegistrosTabla["nombre_unidad_negocio"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["nombre_proceso"]."</strong>");
                                    print("</td>");
                                    
                                                                                                             
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
            
        break; //Fin caso 3
        
                             
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>