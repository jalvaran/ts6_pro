<?php
/**
 * Pagina para verificar las hojas de vida de los equipos
 * 2020-07-09, Julian Alvaran Techno Soluciones SAS
 * 
 * es recomendable No usar los siguientes ID para ningún objeto:
 * FrmModal, ModalAcciones,DivFormularios,BtnModalGuardar,DivOpcionesTablas,
 * DivControlCampos,DivOpciones1,DivOpciones2,DivOpciones3,DivParametrosTablas
 * TxtTabla, TxtCondicion,TxtOrdenNombreColumna,TxtOrdenTabla,TxtLimit,TxtPage,tabla
 * 
 */
$Domain=$_SERVER['HTTP_HOST'];
$urlRequest=($_SERVER['SCRIPT_NAME']);
$urlRequest= explode("/", $urlRequest);
$Pagina=end($urlRequest);

$myPage=$Pagina;
$myTitulo="Hoja de Vida de los Equipos";
include_once("../../sesiones/php_control_usuarios.php");
include_once("../../constructores/paginas_constructor.php");

$css =  new PageConstruct($myTitulo, ""); //objeto con las funciones del html

$obCon = new conexion($idUser); //Conexion a la base de datos
$NombreUser=$_SESSION['nombre'];

$sql="SELECT TipoUser,Role FROM usuarios WHERE idUsuarios='$idUser'";
$DatosUsuario=$obCon->Query($sql);
$DatosUsuario=$obCon->FetchAssoc($DatosUsuario);
$TipoUser=$DatosUsuario["TipoUser"];
$Role=$DatosUsuario["Role"];

$css->PageInit($myTitulo);
    
    $css->Modal("modal_view", "TS6", "", 1, 0, 1);
        $css->div("div_modal_view", "col-md-12", "", "", "", "", "");
        
        $css->Cdiv();
        
    $css->CModal("btnModalView", "", "", "Enviar");
    
    $css->div("", "row", "", "", "", "", "");
        
        print('<div class="col-lg-12">
                <div class="panel panel-dark">
                    <div class="panel-head">
                        <div class="panel-title">
                            
                            <i class="fa fa-warehouse panel-head-icon font-24"></i>
                            <span class="panel-title-text">Ordenes de Trabajo: </span>  
                            <div class="row">
                            <div class="col-md-4">
                                ');
        $css->select("empresa_id", "form-control btn-pill", "empresa_id", "", "", "", "");
            if($TipoUser=="administrador"){
                $sql="SELECT * FROM empresapro WHERE Estado=1";
            }else{
                $sql="SELECT t1.* FROM empresapro t1 WHERE t1.Estado=1 AND EXISTS (SELECT 1 FROM usuarios_rel_empresas t2 WHERE t2.usuario_id='$idUser' AND t2.empresa_id=t1.ID) ";
            }
            
            $Consulta=$obCon->Query($sql);
            while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                $css->option("", "", "", $DatosConsulta["ID"], "", "");
                    print($DatosConsulta["RazonSocial"]);
                $css->Coption();
            }
        $css->Cselect();
        
        
        $css->select("listado_id", "form-control btn-pill", "listado_id", "", "", "", "");
            
                        
            $css->option("", "", "", 1, "", "");
                print("Hoja de Vida");
            $css->Coption();
            
                        
        $css->Cselect();
        
        
        
        print('         </div>
            
                        <div class="col-md-4">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text " style="font-size:14px;">Fecha inicial: </span>
                                </div>    
                                    <input id="fecha_inicial" type="date" class="form-control" value="">
                                    
                                  
                            </div>
                                
                                <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text " style="font-size:14px;">Fecha final: &nbsp;</span>
                                </div>    
                                    <input id="fecha_final" type="date" class="form-control" value="">
                                    
                            </div>
                                
                        </div>
                        
                        
                        
                        ');
        print('<div class="col-md-4">');
            $css->select("orden_tipo", "form-control btn-pill", "orden_tipo", "", "", "", "");
            
                $css->option("", "", "", '', "", "");
                    print("Todos los mantenientos");
                $css->Coption();

                $css->option("", "", "", 1, "", "");
                    print("Correctivos");
                $css->Coption();
                $css->option("", "", "", 2, "", "");
                    print("Preventivos");
                $css->Coption();

            $css->Cselect();
        print('</div>');
        
        print('        
                        </div>
                        </div>
                        <div class="panel-action panel-action-background">
                            
                            
                            <button id="btnActualizarListado" class="btn btn-success btn-gradient btn-pill m-1"><i class="fa fa-sync"></i></button>

                        </div>  
                    </div>
                    <div class="panel-wrapper">
                        <div class="panel-body">
                            <div id="DivListado">

                            </div>
                        </div>
                    </div>
                   </div> 
                </div>');
    
    $css->Cdiv();
       
$css->PageFin();
print('<script src="jsPages/hojas_vida.js"></script>'); 
$css->Cbody();
$css->Chtml();

?>