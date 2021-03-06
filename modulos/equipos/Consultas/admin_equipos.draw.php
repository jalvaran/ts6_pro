<?php

@session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
include_once("../../../modelo/php_conexion.php");
include_once("../../../constructores/paginas_constructor.php");

if( !empty($_REQUEST["Accion"]) ){
    $css =  new PageConstruct("", "", 1, "", 1, 0);
    $obCon = new conexion($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1:// dibujo el listado de las maquinas
            
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
            //$Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
            $BusquedasGenerales=$obCon->normalizar($_REQUEST["BusquedasGenerales"]);
            
            $Condicion=" WHERE ID<>'' ";
            /*
            if($Busquedas<>''){
                $Condicion.=" AND ( t1.Nombre like '%$Busquedas%' or t1.Codigo = '%$Busquedas%' or t1.Marca like '%$Busquedas%')";
            }
            
             * 
             */
            if($BusquedasGenerales<>''){
                $Condicion.=" AND ( t1.Nombre like '%$BusquedasGenerales%' or t1.Codigo = '%$BusquedasGenerales%' or t1.Marca like '%$BusquedasGenerales%')";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM equipos_maquinas t1 $Condicion;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT t1.*,(SELECT Nombre FROM catalogo_procesos t2 WHERE t2.ID=t1.proceso_id LIMIT 1) AS Proceso, 
                    (SELECT NombreSeccion FROM catalogo_secciones t3 WHERE t3.ID=t1.ubicacion_id LIMIT 1) AS Ubicacion,
                    (SELECT NombreRepresentante FROM catalogo_representante t4 WHERE t4.ID=t1.representante_id LIMIT 1) AS Representante 
                  FROM equipos_maquinas t1 $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
                    
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                
                    print('<div class="row widget-separator-1 mb-3">
                                <div class="col-md-3">
                                    <div class="widget-1">
                                        <div class="content">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h5 class="title">Máquinas</h5>
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
                                        <th>Codigo</th>
                                        <th>Nombre</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Serie</th>
                                        <th>Proceso</th>
                                        <th>Ubicacion</th>
                                        <th>Valor de Adquisicion</th>
                                        <th>Adquisicion</th>
                                        <th>Representante</th>
                                        <th>Fabricacion</th>
                                        <th>Instalacion</th>
                                        <th>Especificaciones</th>
                                    </tr>
                                </thead>');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                
                                print('<tr>');
                                    print("<td>");
                                        print('<a onclick="frm_crear_editar_registro(`'.$idItem.'`)" ><i class="icon-pencil text-info"></i></a>');
                                        print(' || <a onclick="ver_componentes_maquina(`'.$idItem.'`)" title="Ver componentes"  ><i class="fa fa-eye text-success"></i></a>');
                                    print("</td>");
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["Codigo"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["Nombre"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Marca"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-success'>");
                                        print($RegistrosTabla["Modelo"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(" <strong>".$RegistrosTabla["NumeroSerie"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print(" <strong>".$RegistrosTabla["Proceso"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        print(" <strong>".$RegistrosTabla["Ubicacion"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-success'>");
                                        print(" <strong>".number_format($RegistrosTabla["ValorAdquisicion"])."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["FechaAdquisicion"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["Representante"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["FechaFabricacion"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        print(($RegistrosTabla["FechaInstalacion"]));
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["Especificaciones"]));
                                    print("</td>");
                                    
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
            
            
            
        break; //Fin caso 1
        
        case 2:// dibujo el listado de los componentes
            
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
            //$Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
            $BusquedasGenerales=$obCon->normalizar($_REQUEST["BusquedasGenerales"]);
            
            $Condicion=" WHERE ID<>'' ";
            /*
            if($Busquedas<>''){
                $Condicion.=" AND ( t1.Nombre like '%$Busquedas%' or t1.NumeroSerie = '%$Busquedas%' or t1.Marca like '%$Busquedas%')";
            }
            
             * 
             */
            if($BusquedasGenerales<>''){
                $Condicion.=" AND ( t1.Nombre like '%$BusquedasGenerales%' or t1.NumeroSerie = '%$BusquedasGenerales%' or t1.Marca like '%$BusquedasGenerales%')";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM equipos_componentes t1 $Condicion;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT t1.*,(SELECT Nombre FROM equipos_maquinas t2 WHERE t2.ID=t1.maquina_id LIMIT 1) As Maquina  
                  FROM equipos_componentes t1 $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
                    
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                
                    print('<div class="row widget-separator-1 mb-3">
                                <div class="col-md-3">
                                    <div class="widget-1">
                                        <div class="content">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h5 class="title">Componentes</h5>
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
                                        <th>Maquina</th>
                                        <th>Nombre</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Serie</th>                                        
                                        <th>Especificaciones</th>
                                    </tr>
                                </thead>');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                
                                print('<tr>');
                                    print("<td>");
                                        print('<a onclick="frm_crear_editar_registro(`'.$idItem.'`)" ><i class="icon-pencil text-info"></i></a>');
                                        
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        print("<strong>".$RegistrosTabla["Maquina"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["Nombre"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Marca"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-success'>");
                                        print($RegistrosTabla["Modelo"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-flickr'>");
                                        print(" <strong>".$RegistrosTabla["NumeroSerie"]."</strong>");
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["Especificaciones"]));
                                    print("</td>");
                                    
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
            
            
            
        break; //Fin caso 2
        
        
        case 3:// dibujo el listado de las partes
            
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
            //$Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
            $BusquedasGenerales=$obCon->normalizar($_REQUEST["BusquedasGenerales"]);
            
            $Condicion=" WHERE ID<>'' ";
            /*
            if($Busquedas<>''){
                $Condicion.=" AND ( t1.DescripcionPrimaria like '%$Busquedas%' or t1.Codigo = '%$Busquedas%' or t1.DescripcionSecundaria like '%$Busquedas%')";
            }
            
             * 
             */
            if($BusquedasGenerales<>''){
                $Condicion.=" AND ( t1.DescripcionPrimaria like '%$BusquedasGenerales%' or t1.Codigo = '%$BusquedasGenerales%' or t1.DescripcionSecundaria like '%$BusquedasGenerales%')";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM equipos_partes t1 $Condicion;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT t1.* 
                  FROM equipos_partes t1 $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
                    
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                
                    print('<div class="row widget-separator-1 mb-3">
                                <div class="col-md-3">
                                    <div class="widget-1">
                                        <div class="content">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h5 class="title">Partes</h5>
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
                                        <th>Codigo</th>
                                        <th>DescripcionPrimaria</th>
                                        <th>DescripcionSecundaria</th>
                                        <th>Cantidad</th>
                                        <th>Costo</th>
                                        <th>Fecha</th>
                                        
                                    </tr>
                                </thead>');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                
                                print('<tr>');
                                    print("<td>");
                                        print('<a onclick="frm_crear_editar_registro(`'.$idItem.'`)" ><i class="icon-pencil text-info"></i></a>');
                                    print("</td>");
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["Codigo"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["DescripcionPrimaria"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["DescripcionSecundaria"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-success'>");
                                        print(number_format($RegistrosTabla["Cantidad"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(" <strong>".number_format($RegistrosTabla["Costo"])."</strong>");
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject text-flickr'>");
                                        print(($RegistrosTabla["Fecha"]));
                                    print("</td>");
                                    
                                                                       
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
            
            
            
        break; //Fin caso 3
        
        case 4: //Dibuja el formulario para crear o editar un registro segun el listado seleccionado y la empresa
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            $edit_id=$obCon->normalizar($_REQUEST["edit_id"]);
            $tipo_equipo=$obCon->normalizar($_REQUEST["tipo_equipo"]);
            
            if($tipo_equipo==1){
                $tab=$db.".equipos_maquinas";
                $title="Maquinas";
            }
            if($tipo_equipo==2){
                $tab=$db.".equipos_componentes";
                $title="Componentes";
            }
            if($tipo_equipo==3){
                $tab=$db.".equipos_partes";
                $title="Partes";
            }
            if($tipo_equipo==4){
                $tab=$db.".catalogo_representante";
                $title="Representantes de los equipos";
            }
            if(isset($_REQUEST["maquina_id"])){
                $data_extra["maquina_id"]["default"]=$obCon->normalizar($_REQUEST["maquina_id"]);
                $css->input("text", "componente_asociado", "", "componente_asociado", "", $data_extra["maquina_id"]["default"], "", "", "", "");
                $css->frm_form("frm_equipos", $title,$tab,$edit_id, $data_extra);
                exit();
            }
            $css->frm_form("frm_equipos", $title,$tab,$edit_id, "");
        break; //Fin caso 4
            
        
        case 5:// dibujo el listado de los representantes
            
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
            //$Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
            $BusquedasGenerales=$obCon->normalizar($_REQUEST["BusquedasGenerales"]);
            
            $Condicion=" WHERE ID<>'' ";
            /*
            if($Busquedas<>''){
                $Condicion.=" AND ( t1.DescripcionPrimaria like '%$Busquedas%' or t1.Codigo = '%$Busquedas%' or t1.DescripcionSecundaria like '%$Busquedas%')";
            }
            
             * 
             */
            if($BusquedasGenerales<>''){
                $Condicion.=" AND ( t1.NombreRepresentante like '%$BusquedasGenerales%' or t1.ID = '%$BusquedasGenerales%' or t1.Contacto like '%$BusquedasGenerales%')";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM catalogo_representante t1 $Condicion;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT t1.* 
                  FROM catalogo_representante t1 $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
                    
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                
                    print('<div class="row widget-separator-1 mb-3">
                                <div class="col-md-3">
                                    <div class="widget-1">
                                        <div class="content">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h5 class="title">Representantes</h5>
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
                                        <th>Nombre</th>
                                        <th>Contacto</th>
                                        <th>Telefono</th>
                                        <th>Fax</th>
                                        <th>Email</th>
                                        <th>Direccion</th>
                                        <th>Ciudad</th>
                                        <th>Celular</th>
                                        
                                    </tr>
                                </thead>');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                
                                print('<tr>');
                                    print("<td>");
                                        print('<a onclick="frm_crear_editar_registro(`'.$idItem.'`)" ><i class="icon-pencil text-info"></i></a>');
                                    print("</td>");
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["ID"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-primary'>");
                                        print("<strong>".$RegistrosTabla["NombreRepresentante"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Contacto"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject text-success'>");
                                        print(($RegistrosTabla["Telefono"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(" <strong>".($RegistrosTabla["Fax"])."</strong>");
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject text-flickr'>");
                                        print(($RegistrosTabla["Email"]));
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Direccion"]);
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Ciudad"]);
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Celular"]);
                                    print("</td>");
                                                                       
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
            
            
            
        break; //Fin caso 5
        
        case 6://Visualiza los componentes de una maquina
            
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $DatosEmpresa=$obCon->ValorActual("empresapro", "db", " ID='$empresa_id'");
            $db=$DatosEmpresa["db"];
            $maquina_id=$obCon->normalizar($_REQUEST["maquina_id"]);
            $DatosMaquina=$obCon->DevuelveValores($db.".equipos_maquinas", "ID", $maquina_id);
            print('<button id="btnFrmComponenteMaquina" class="btn btn-dark btn-gradient btn-pill m-1" onclick=frm_crear_componente_asociado_maquina(`'.$maquina_id.'`) >Crear y Agregar <i class="fa fa-plus-circle"></i></button>');
            print('<div class="panel panel-default">
                                <div class="panel-head"> 
                                    <div class="panel-title">
                                        <i class="icon-grid panel-head-icon font-24"></i>
                                        <span class="panel-title-text">Componentes de la Maquina: '.$DatosMaquina["Nombre"].', modelo: '.$DatosMaquina["Modelo"].'</span>
                                    </div>
                                </div>
                                <div class="panel-wrapper">
                                    <div class="recent-list">
                                        <ul>');
            
                $sql="SELECT * FROM equipos_componentes WHERE maquina_id='$maquina_id'";
                $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
                while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                    $ID=$DatosConsulta["ID"];
                    print(' <li>
                                                <div class="main-icon"><i class="icon-layers"></i></div>
                                                <div class="content">
                                                    <p><strong>Componente:</strong> '.$DatosConsulta["Nombre"].' || <strong>Modelo:</strong> '.$DatosConsulta["Modelo"].' || <strong>Marca:</strong> <span class="badge badge-success badge-pill"> '.$DatosConsulta["Marca"].'</span> || <strong>Serie:</strong> '.$DatosConsulta["NumeroSerie"].'</p>
                                                    <div class="row action align-items-center">
                                                        <div class="col-sm-8">
                                                            
                                                            <a onclick=desligueComponente(`'.$ID.'`,`'.$maquina_id.'`) ><i class="icon-trash" style="color:red"></i></a>
                                                        </div>
                                                        <div class="col-sm-4 text-right">
                                                            <span class="date"> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>');
                
                        }                    
                        print( ' </ul>
                                    </div>
                                </div>
                            </div>');
            
        break;//Fin caso 6    
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>