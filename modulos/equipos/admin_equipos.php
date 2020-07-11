<?php
/**
 * Pagina para administrar los equipos
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
$myTitulo="Administrar Equipos";
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
                            <span class="panel-title-text">Selecciona un Empresa y Listado de equipos: </span>');
        $css->select("empresa_id", "form-control btn-pill", "empresa_id", "", "", "", "");
            $sql="SELECT * FROM empresapro WHERE Estado=1";
            $Consulta=$obCon->Query($sql);
            while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                $css->option("", "", "", $DatosConsulta["ID"], "", "");
                    print($DatosConsulta["RazonSocial"]);
                $css->Coption();
            }
        $css->Cselect();
        
        $css->select("tipo_equipo", "form-control btn-pill", "tipo_equipo", "", "", "", "");
            
            $css->option("", "", "", 1, "", "");
                print("Máquinas");
            $css->Coption();
            
            $css->option("", "", "", 2, "", "");
                print("Componentes");
            $css->Coption();
            
            $css->option("", "", "", 3, "", "");
                print("Partes");
            $css->Coption();
            
        $css->Cselect();
        
        print('         </div>
                        <div class="panel-action panel-action-background">
                            <div class="page-search md" >
                                <input id="TxtBusquedas" type="text" placeholder="Buscar...." style="background-color:white">
                            </div>
                            <button id="btnFrmNuevaEmpresa" class="btn btn-primary btn-gradient btn-pill m-1">Crear <i class="fa fa-plus-circle"></i></button>
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
print('<script src="jsPages/admin_empresas.js"></script>');
$css->Cbody();
$css->Chtml();

?>