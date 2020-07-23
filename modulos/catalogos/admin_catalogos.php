<?php
/**
 * Pagina para administrar los catalogos
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
$myTitulo="Catalogos Generales";
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
    $css->div("", "row", "", "", "", "", "");
        
        print('<div class="col-lg-12">
                <div class="panel panel-dark">
                    <div class="panel-head">
                        <div class="panel-title">
                            
                            <i class="fab fa-whmcs panel-head-icon font-24"></i>
                            <span class="panel-title-text">Selecciona un Empresa y catalogo: </span>');
        $css->select("empresa_id", "form-control btn-pill", "empresa_id", "", "", "", "");
            $sql="SELECT * FROM empresapro WHERE Estado=1";
            $Consulta=$obCon->Query($sql);
            while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                $css->option("", "", "", $DatosConsulta["ID"], "", "");
                    print($DatosConsulta["RazonSocial"]);
                $css->Coption();
            }
        $css->Cselect();
        
        $css->select("catalogo_id", "form-control btn-pill", "catalogo_id", "", "", "", "");
            
            $css->option("", "", "", 5, "", "");
                print("Unidades de negocio");
            $css->Coption();
            
            $css->option("", "", "", 1, "", "");
                print("Procesos");
            $css->Coption();
            
            $css->option("", "", "", 2, "", "");
                print("Ubicaciones o Secciones");
            $css->Coption();
            
            $css->option("", "", "", 6, "", "");
                print("Tipo de Tareas");
            $css->Coption();
            
            $css->option("", "", "", 3, "", "");
                print("Tareas");
            $css->Coption();
            
            $css->option("", "", "", 4, "", "");
                print("Técnicos");
            $css->Coption();
            
            $css->option("", "", "", 7, "", "");
                print("Rutinas");
            $css->Coption();
            
        $css->Cselect();
        
        print('         </div>
                        <div class="panel-action panel-action-background">
                            
                            <button id="btnFrmNuevoRegistro" class="btn btn-primary btn-gradient btn-pill m-1">Crear <i class="fa fa-plus-circle"></i></button>
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
print('<script src="jsPages/admin_catalogos.js"></script>');
$css->Cbody();
$css->Chtml();

?>