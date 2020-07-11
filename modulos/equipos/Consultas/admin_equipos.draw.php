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
            $Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
            $BusquedasGenerales=$obCon->normalizar($_REQUEST["BusquedasGenerales"]);
            
            $Condicion=" WHERE ID<>'' ";
            
            if($Busquedas<>''){
                $Condicion.=" AND ( t1.Nombre like '%$Busquedas%' or t1.Codigo = '%$Busquedas%' or t1.Marca like '%$Busquedas%')";
            }
            
            if($BusquedasGenerales<>''){
                $Condicion.=" AND ( t1.Nombre like '%$BusquedasGenerales%' or t1.Codigo = '%$BusquedasGenerales%' or t1.Marca like '%$BusquedasGenerales%')";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM equipos_maquinas t1 $Condicion;";
            
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
            
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT t1.* 
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
                                        <th>Acciones</th>
                                        <th>Codigo</th>
                                        <th>Nombre</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Serie</th>
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
            $css->frm_form("frm_equipos", $title,$tab,$edit_id, "");
        break; //Fin caso 4
                
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>