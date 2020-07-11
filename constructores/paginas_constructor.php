<?php
include_once 'html_estruct_class.php';
if(file_exists('../../modelo/php_conexion.php')){
    include_once '../../modelo/php_conexion.php';
}
/**
 * Description of pages_construct Clase para generar paginas
 *
 * @author Julian Andres Alvaran
 */	

class PageConstruct extends html_estruct_class{
    
    public $DatosUsuario;
    public $NombreUsuario;
    public $obCon;
    public $Titulo;
    /**
     * Constructor
     * @param type $Titulo ->Titulo de la pagina
     * @param type $ng_app ->Se define si se desea ingresar un modulo del framework angular
     * @param type $Vector -> uso futuro
     */
    function __construct($Titulo,$Vector,$Angular='',$ng_app='',$CssFramework=1,$Inicializar=1){
        $idUser=$_SESSION["idUser"];
        $this->obCon=new conexion($idUser);
        $this->Titulo=$Titulo;
        $sql="SELECT Nombre,Apellido,Role,TipoUser FROM usuarios WHERE idUsuarios='$idUser'";
        $Consulta=$this->obCon->Query($sql);
        $this->DatosUsuario=$this->obCon->FetchAssoc($Consulta);
        $this->NombreUsuario=$this->DatosUsuario["Nombre"]." ".$this->DatosUsuario["Apellido"];
        if($Inicializar==1){
            print('<!DOCTYPE html>
                    <html lang="en">

                    <head>
                        <meta charset="UTF-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <title>'.$Titulo.'</title>
                        <link rel="icon" type="image/x-icon" href="../../images/favicontechno.png">
                        <!-- Switcher CSS -->
                        <link rel="stylesheet" href="../../assets/plugin/switchery/switchery.min.css" />
                        <!-- Morris CSS -->
                        <link rel="stylesheet" href="../../assets/plugin/morris/morris.css" />
                        <!-- Sweetalert CSS -->
                        <link rel="stylesheet" href="../../assets/plugin/sweetalert/sweetalert.css" />
                        <!-- alertify CSS 
                        <link rel="stylesheet" href="../../assets/plugin/alertify/themes/alertify.core.css" />
                        <link rel="stylesheet" href="../../assets/plugin/alertify/themes/alertify.default.css" id="toggleCSS" />  -->
                        <!-- Custom Stylesheet -->
                        
                        <link rel="stylesheet" href="../../dist/css/style.css" />
                    </head>
                    <body>

                        <div class="loader-wrapper">
                            <div class="loader spinner-3">
                                <div class="bg-primary"></div>
                                <div class="bg-primary"></div>
                                <div class="bg-primary"></div>
                                <div class="bg-primary"></div>
                                <div class="bg-primary"></div>
                                <div class="bg-primary"></div>
                                <div class="bg-primary"></div>
                                <div class="bg-primary"></div>
                                <div class="bg-primary"></div>
                            </div>
                        </div>

                        <div class="wrapper">
                            <!-- Main Container -->
                            <div id="main-wrapper" class="menu-fixed page-hdr-fixed page-menu-small">');
            
    $this->menu_wrapper();      
    $this->page_header();
    
        print('             
            <!-- Main Page Wrapper -->
            <div class="page-wrapper">
                <!-- Page Title -->
                <div class="page-title">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h2 class="page-title-text">'.$this->Titulo.'</h2>
                        </div>
                        <div class="col-sm-6 text-right">
                            <div class="breadcrumbs">
                                <ul>
                                   <a href="../../modulos/menu/Menu.php" target="_self" > Menú <button class="fa fa-home" style="font-size:30px;cursor:pointer"> </button></a>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page Body -->
                <div class="page-body">');
        }
        
    }   
    
    public function page_header() {
        print('<!-- Page header -->
            <div class="page-hdr">
                <div class="row align-items-center">
                    <div class="col-4 col-md-7 page-hdr-left">
                        <!-- Logo Container -->
                        <div id="logo">
                            <div class="tbl-cell logo-icon">
                                <a href="#"><img src="../../images/favicon.png" alt=""></a>
                            </div>
                            <div class="tbl-cell logo">
                                <a href="#"><img src="../../images/logo-header.png"></a>
                            </div>
                        </div>
                        <div class="page-menu menu-icon">
                            <a class="animated menu-close"><i class="far fa-hand-point-left"></i></a>
                        </div>
                        <div class="page-menu page-fullscreen">
                            <a><i class="fas fa-expand"></i></a>
                        </div>
                        <div class="page-search">
                            <input id="txtBusquedasGenerales" type="text" placeholder="Busqueda....">
                        </div>
                    </div>
                    <div class="col-8 col-md-5 page-hdr-right">
                        <div class="page-hdr-desktop">
                            <div class="page-menu menu-dropdown-wrapper menu-user">
                                <a class="user-link">
                                    <span class="tbl-cell user-name pr-3">Hola <span class="pl-2">'.$this->NombreUsuario.'</span></span>
                                    <span class="tbl-cell avatar"><img src="uploads/author-4.jpg" alt=""></span>
                                </a>
                                <div class="menu-dropdown menu-dropdown-right menu-dropdown-push-right">
                                    <div class="arrow arrow-right"></div> 
                                    <div class="menu-dropdown-inner">
                                        <div class="menu-dropdown-head pb-3">
                                            <div class="tbl-cell">
                                                <img src="uploads/author-1.jpg" alt="">
                                                <!-- <i class="fa fa-user-circle"></i> -->
                                            </div>
                                            <div class="tbl-cell pl-2 text-left">
                                                <p class="m-0 font-18">'.$this->NombreUsuario.'</p>
                                                <p class="m-0 font-14">TS6</p>
                                            </div>
                                        </div>
                                        <div class="menu-dropdown-body">
                                            <ul class="menu-nav">
                                                <li><a href="#"><i class="icon-event"></i><span>My Events</span></a></li>
                                                <li><a href="#"><i class="icon-notebook"></i><span>My Notes</span></a></li>
                                                <li><a href="#"><i class="icon-user"></i><span>My Profile</span></a></li>
                                                <li><a href="#"><i class="icon-globe-alt"></i><span>Client Portal</span></a></li>
                                            </ul>
                                        </div>
                                        <div class="menu-dropdown-footer text-right">
                                            <a href="#" class="btn btn-outline btn-primary btn-pill btn-outline-2x font-12 btn-sm">Logout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="page-menu menu-dropdown-wrapper menu-notification">
                                <a><i class="icon-bell"></i><span class="notification">20</span></a>
                                <div class="menu-dropdown menu-dropdown-right menu-dropdown-push-right">
                                    <div class="arrow arrow-right"></div> 
                                    <div class="menu-dropdown-inner">
                                        <div class="menu-dropdown-head">Notification</div>
                                        <div class="menu-dropdown-body">
                                            <ul class="timeline m-0">
                                                <li>
                                                    <a href="" target="_blank" class="timeline-container">
                                                        <div class="arrow"></div>
                                                        <div class="description">Wallet Adddes </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="" target="_blank" class="timeline-container">
                                                        <div class="arrow"></div>
                                                        <div class="description">Coin Transferred from BTC<span class="badge badge-danger badge-pill badge-sm">Unpaid</span></div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="" target="_blank" class="timeline-container">
                                                        <div class="arrow"></div>
                                                        <div class="description">BTC bought</div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="" target="_blank" class="timeline-container">
                                                        <div class="arrow"></div>
                                                        <div class="description">Server Restarted <span class="badge badge-success badge-pill badge-sm">Resolved</span></div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="" target="_blank" class="timeline-container">
                                                        <div class="arrow"></div>
                                                        <div class="description">New order received</div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="page-menu menu-dropdown-wrapper menu-quick-links">
                                <a><i class="icon-grid"></i></a>
                                <div class="menu-dropdown menu-dropdown-right menu-dropdown-push-right">
                                    <div class="arrow arrow-right"></div> 
                                    <div class="menu-dropdown-inner">
                                        <div class="menu-dropdown-head">Quick Links</div>
                                        <div class="menu-dropdown-body p-0">
                                            <div class="row m-0 box">
                                                <div class="col-6 p-0 box">
                                                    <a href="">
                                                        <i class="icon-emotsmile"></i>
                                                        <span>New Contact</span>
                                                    </a>
                                                </div>
                                                <div class="col-6 p-0 box">
                                                    <a href="">
                                                        <i class="icon-docs"></i>
                                                        <span>New Invoice</span>
                                                    </a>
                                                </div>
                                                <div class="col-6 p-0 box">
                                                    <a href="">
                                                        <i class="icon-calculator"></i>
                                                        <span>New Quote</span>
                                                    </a>
                                                </div>
                                                <div class="col-6 p-0 box">
                                                    <a href="">
                                                        <i class="icon-rocket"></i>
                                                        <span>New Expense</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="page-menu">
                                <!-- <a class="open-sidebar-right"><i class="icon-settings"></i><span></span></a> -->
                            </div>
                        </div>
                        <div class="page-hdr-mobile">
                            <div class="page-menu open-mobile-search">
                                <a href="#"><i class="icon-magnifier"></i></a>
                            </div>
                            <div class="page-menu open-left-menu">
                                <a href="#"><i class="icon-menu"></i></a>
                            </div>
                            <div class="page-menu oepn-page-menu-desktop">
                                <a href="#"><i class="icon-options-vertical"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>');
    }
    
    public function menu_wrapper() {
        
        $sql="SELECT t1.*,t3.ID as menu_id,t3.Nombre as menu_name,t3.CSS_Clase,t5.Ruta   
                    FROM menu_submenus t1 
                    INNER JOIN menu_pestanas t2 ON t1.idPestana=t2.ID 
                    INNER JOIN menu t3 ON t3.ID=t2.idMenu 
                    INNER JOIN menu_carpetas t5 ON t5.ID=t1.idCarpeta 
                  WHERE t1.Estado=1  ";
        
        if($this->DatosUsuario["TipoUser"]<>"administrador"){
            $sql.=" AND EXISTS (SELECT 1 FROM paginas_bloques t4 WHERE t4.TipoUsuario='".$this->DatosUsuario["TipoUser"]."' AND t4.Pagina=t1.Pagina)";
        }
        $sql.= "ORDER BY t3.Orden ASC";
        $Consulta=$this->obCon->Query($sql);
        
        while($DatosConsulta=$this->obCon->FetchAssoc($Consulta)){
            $menu_id=$DatosConsulta["menu_id"];
            $submenu_id=$DatosConsulta["ID"];
            $DatosMenu[$menu_id]["name"]=$DatosConsulta["menu_name"];
            $DatosMenu[$menu_id]["icon"]=$DatosConsulta["CSS_Clase"];
            $DatosSubMenu[$menu_id][$submenu_id]=$DatosConsulta;
        }
        
        print('<div class="menu-wrapper">');
        
            print('<div class="menu">');
                print('<ul>');
                    print('<li class="menu-title">Menú</li>');
                        print('
                            
                                ');
                        if(isset($DatosMenu)){
                            foreach ($DatosMenu as $keyMenu => $Menu) {
                                print('<li class="has-sub"><a><i class="'.$Menu["icon"].'"></i><span>'.$Menu["name"].'</span><i class="arrow"></i></a>

                                       ');
                                print('<ul class="sub-menu">');
                                    foreach ($DatosSubMenu[$keyMenu] as $keySubmenu => $SubMenu) {
                                        print('<li><a href="'.$SubMenu["Ruta"].$SubMenu["Pagina"].'" target="'.$SubMenu["Target"].'"><span>'.$SubMenu["Nombre"].'</span></a></li>

                                            ');
                                    }
                                print('</li></ul>');
                            }
                        }
                        print('</li>');                    
                print('</ul>');
            print('</div>');
        print('</div>');
        
    }
    
    /**
     * Inicio de la cabecera
     * @param type $Title
     */
    function CabeceraIni($Title,$Link="#",$js=""){
        
        
        
        
    }
	
	
    /**
     * Inicia todos los elementos de la pagina en general
     * @param type $myTitulo
     */
    public function PageInit($myTitulo) {
        $NombreUsuario= utf8_encode($_SESSION["nombre"]);
        $idUser=$_SESSION["idUser"];
        $this->CabeceraIni($myTitulo,"",""); //Inicia la cabecera de la pagina
        
        
        /*
        
        $this->ModalUsoGeneral("ModalNotificaciones", "DivNotificacionesGenerales", "Notificaciones", "Leer Todo", "LeerTodo()", "");
        $this->DivColapsable("DivColapsableTablas", "","style=display:none;overflow-x: scroll;");
            $this->CrearDiv("DivOpcionesTablasDB", "", "", 1, 1);
            $this->CerrarDiv();
             $this->div("DivTablaDB", "", "", "", "", "", "");
            
            $this->CerrarDiv();
        $this->CDivColapsable();
            $this->div("DivCentralMensajes", "", "", "", "", "", "style=position: absolute;top:50%;left:50%;padding:5px;");
            
            $this->Cdiv();
         
        
         * 
         */
    }
   
    
    /**
     * Se dibuja el modal para las opciones de las tablas
     * @param type $Tabla
     * @param type $DivTabla
     */
    public function ModalFormulariosTablasDB($Tabla,$DivTabla) {
        //$this->BotonAbreModal("Abrir", "ModalAcciones", "", "azul");
        
        $JSBoton="onsubmit=GuardarRegistro(event);";
        $this->form("FrmModal", "", "", "", "", "", "", $JSBoton);
        
            $this->Modal("ModalAccionesConstructor", "TSS", "", 0, 0, 1);

                    $this->CrearDiv("DivFormularios", "", "", 1, 1);
                    $this->CerrarDiv();

            $JSBoton="";
            
            $this->CModal("BtnModalGuardar", $JSBoton, "submit", "Guardar");
        $this->Cform();           
        
        
    }
    /**
     * Crear un modal para usos generales
     * @param type $NombreModal
     * @param type $idDivBodyModal
     * @param type $TituloModal
     * @param type $ValorBoton
     * @param type $JSBoton
     * @param type $Extras
     */
    public function ModalUsoGeneral($NombreModal,$idDivBodyModal,$TituloModal,$ValorBoton,$JSBoton,$Extras) {        
           
            $this->Modal($NombreModal, $TituloModal, "", 0, 0, 1);
                    $this->CrearDiv($idDivBodyModal, "", "", 1, 1);
                    $this->CerrarDiv();
            $this->CModal("BtnModalGuardar", $JSBoton, "submit", $ValorBoton);
        
    }
                
    /**
     * Fin de la pagina
     */
    public function PageFin() {
        print('</div></div>');
        
        $this->FooterPage();
        
        print(' </div>');
        $this->side_bar_section();
        print('  </div>'); 
        
        $this->AgregaJS();
    }
    
     
    public function side_bar_section() {
        print('<!-- Sidebar Section -->
        <div class="sidebar sidebar-right">
            <div class="sidebar-close"><i class="icon-close"></i></div>
            <div class="content">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-primary">
                    <li class="nav-item">
                        <a class="nav-link active" href="#sidebar-member" data-toggle="tab">Member</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebar-setting" data-toggle="tab">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebar-log" data-toggle="tab">Logs</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="sidebar-member">
                        <div class="pl-2 pr-2">
                            <div class="box-title pb-3">Premium Member</div>
                            <div class="user-list br-bottom-1x pb-4">
                                <ul>
                                    <li>
                                        <div class="tbl-cell image">
                                            <img src="uploads/team-1.jpg" alt="">
                                        </div>
                                        <div class="tbl-cell content">
                                            <a>Steve Soren</a>
                                            <p>Lead Developer at Ipos.</p>
                                        </div>
                                        <div class="tbl-cell follow">
                                            <a href="#" class="btn btn-outline btn-info btn-pill btn-outline-1x btn-sm">Follow</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="tbl-cell image">
                                            <img src="uploads/team-2.jpg" alt="">
                                        </div>
                                        <div class="tbl-cell content">
                                            <a>Cheri Aria</a>
                                            <p>Photographer and Lead Designer.</p>
                                        </div>
                                        <div class="tbl-cell follow">
                                            <a href="#" class="btn btn-info btn-pill btn-sm">Following</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="tbl-cell image">
                                            <img src="uploads/team-3.jpg" alt="">
                                        </div>
                                        <div class="tbl-cell content">
                                            <a>Daniel Barnes</a>
                                            <p>Manager at IT park.</p>
                                        </div>
                                        <div class="tbl-cell follow">
                                            <a href="#" class="btn btn-outline btn-info btn-pill btn-outline-1x btn-sm">Follow</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="tbl-cell image">
                                            <img src="uploads/team-4.jpg" alt="">
                                        </div>
                                        <div class="tbl-cell content">
                                            <a>Janet Collins</a>
                                            <p>Developer at atios.</p>
                                        </div>
                                        <div class="tbl-cell follow">
                                            <a href="#" class="btn btn-outline btn-info btn-pill btn-outline-1x btn-sm">Follow</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="box-title pt-3 pb-3">Users</div>
                            <div class="user-list">
                                <ul>
                                    <li>
                                        <div class="tbl-cell image">
                                            <img src="uploads/team-1.jpg" alt="">
                                        </div>
                                        <div class="tbl-cell content">
                                            <a>Steve Soren</a>
                                            <p>Lead Developer at Ipos.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="tbl-cell image">
                                            <img src="uploads/team-2.jpg" alt="">
                                        </div>
                                        <div class="tbl-cell content">
                                            <a>Cheri Aria</a>
                                            <p>Photographer and Lead Designer.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="tbl-cell image">
                                            <img src="uploads/team-3.jpg" alt="">
                                        </div>
                                        <div class="tbl-cell content">
                                            <a>Daniel Barnes</a>
                                            <p>Manager at IT park.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="tbl-cell image">
                                            <img src="uploads/team-4.jpg" alt="">
                                        </div>
                                        <div class="tbl-cell content">
                                            <a>Janet Collins</a>
                                            <p>Developer at atios.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="sidebar-setting">
                        <div class="pl-2 pr-2">
                            <div class="box-title pb-3">General Setting</div>
                            <div class="setting-list">
                                <ul>
                                    <li class="item">
                                        <div class="label">Email Notifications</div>
                                        <div class="control"><input type="checkbox" checked class="js-switch" data-color="#13dafe" /></div>
                                    </li>
                                    <li class="item">
                                        <div class="label">Comment auto Publish</div>
                                        <div class="control"><input type="checkbox" class="js-switch" data-color="#13dafe" /></div>
                                    </li>
                                    <li class="item">
                                        <div class="label">Review Auto Publish</div>
                                        <div class="control"><input type="checkbox" checked class="js-switch" data-color="#13dafe" /></div>
                                    </li>
                                    <li class="item">
                                        <div class="label">Post Setting</div>
                                        <div class="control"><input type="checkbox" class="js-switch" data-color="#13dafe" /></div>
                                    </li>
                                    <li class="item">
                                        <div class="label">Cron Log</div>
                                        <div class="control"><input type="checkbox" class="js-switch" data-color="#13dafe" /></div>
                                    </li>
                                    <li class="item">
                                        <div class="label">Email Log</div>
                                        <div class="control"><input type="checkbox" checked class="js-switch" data-color="#13dafe" /></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="sidebar-log">
                        <div class="pl-2 pr-2">
                            <div class="box-title pb-3">Server and Application Logs</div>
                            <ul class="timeline">
                                <li>
                                    <div class="time"><small>Just Now</small></div>
                                    <a href="" target="_blank" class="timeline-container">
                                        <div class="arrow"></div>
                                        <div class="description">21 new users registered </div>
                                    </a>
                                </li>
                                <li>
                                    <div class="time"><small>11 mins</small></div>
                                    <a href="" target="_blank" class="timeline-container">
                                        <div class="arrow"></div>
                                        <div class="description">New invoice generated <span class="badge badge-danger badge-pill badge-sm">Unpaid</span></div>
                                    </a>
                                </li>
                                <li>
                                    <div class="time"><small>15 mins</small></div>
                                    <a href="" target="_blank" class="timeline-container">
                                        <div class="arrow"></div>
                                        <div class="description">Cron Job Completed</div>
                                    </a>
                                </li>
                                <li>
                                    <div class="time"><small>20 mins</small></div>
                                    <a href="" target="_blank" class="timeline-container">
                                        <div class="arrow"></div>
                                        <div class="description">Server Restarted <span class="badge badge-success badge-pill badge-sm">Resolved</span></div>
                                    </a>
                                </li>
                                <li>
                                    <div class="time"><small>25 mins</small></div>
                                    <a href="" target="_blank" class="timeline-container">
                                        <div class="arrow"></div>
                                        <div class="description">New order received</div>
                                    </a>
                                </li>
                                <li>
                                    <div class="time"><small>30 mins</small></div>
                                    <a href="" target="_blank" class="timeline-container">
                                        <div class="arrow"></div>
                                        <div class="description">New ticket created <span class="badge badge-warning badge-pill badge-sm">High</span></div>
                                    </a>
                                </li>
                                <li>
                                    <div class="time"><small>35 mins</small></div>
                                    <a href="" target="_blank" class="timeline-container">
                                        <div class="arrow"></div>
                                        <div class="description">Payment Made by client $350.</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- End Sidebar Section -->
    </div>
');
    }
    /**
     * Controles generales del AdminLTE
     */
    public function ControlesGenerales() {
        print('<!-- Control Sidebar Toggle Button -->
                <li>
                  <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>');
    }
    /**
     * Inicia el menu lateral
     */
    public function MenuLateralInit() {
        print('<aside class="main-sidebar"><section class="sidebar">');
    }
    /**
     * Finaliza el menu lateral
     */
    public function MenuLateralFin() {
        print('</section></aside>');
    }
    /**
     * Panel para visualizar el nombre del usuario
     * @param type $NombreUsuario
     */
    public function PanelInfoUser($NombreUsuario) {
        print('<!-- Sidebar user panel -->
            <div class="user-panel">
              <div class="pull-left image">
                <img src="../../dist/img/user.png" class="img-circle" alt="User Image">
              </div>
              <div class="pull-left info">
                <p>'.$NombreUsuario.'</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
              </div>
            </div>');
    }
    /**
     * Panel de busqueda para uso futuro
     */
    public function PanelBusqueda() {
        print('<!-- search form -->
            <form action="#" method="get" class="sidebar-form">
              <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                      <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                      </button>
                    </span>
              </div>
            </form>');
    }
    /**
     * Inicia el panel lateral
     * @param type $Titulo
     */
    public function PanelLateralInit($Titulo) {
        print('<!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
              <li class="header">'.$Titulo.'</li>');
    }
    /**
     * Cierra el panel lateral
     */
    public function CPanelLateral() {
        print('</ul>');
    }
    
    /**
     *Opciones del layout 
     */
    public function PanelLayoutOptions() {
        print('<li class="treeview">
                <a href="#">
                  <i class="fa fa-files-o"></i>
                  <span>Layout Options</span>
                  <span class="pull-right-container">
                    <span class="label label-primary pull-right">4</span>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="../layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
                  <li><a href="../layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
                  <li><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
                  <li><a href="../layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
                </ul>
              </li>');
    }
    /**
     * Informacion del usuario que inicia sesion
     * @param type $TituloGrande
     * @param type $TituloPequeno
     */
    public function SesionInfoPage($TituloGrande,$TituloPequeno) {
        print('<section class="content-header">
                <h1>
                  '.$TituloGrande.'
                  <small>'.$TituloPequeno.'</small>
                </h1>
                <ol class="breadcrumb">
                  <li><a href="../index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>

                </ol>
              </section>');
    }
    /**
     * Pie de pagina
     */
    public function FooterPage() {
        $anio=date("Y");
        print('<!-- Page Footer -->
            
            <div class="page-ftr">
                <div>© '.$anio.'. Techno Soluciones SAS</div>
            </div>
        ');
    }
    /**
     * Barra de controles
     */
    public function BarraControles() {
        print('<!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
              <!-- Create the tabs -->
              <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">
                  <h3 class="control-sidebar-heading">Recent Activity</h3>
                  <ul class="control-sidebar-menu">
                    <li>
                      <a href="javascript:void(0)">
                        <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                        <div class="menu-info">
                          <h4 class="control-sidebar-subheading">Langdons Birthday</h4>

                          <p>Will be 23 on April 24th</p>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:void(0)">
                        <i class="menu-icon fa fa-user bg-yellow"></i>

                        <div class="menu-info">
                          <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                          <p>New phone +1(800)555-1234</p>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:void(0)">
                        <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                        <div class="menu-info">
                          <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                          <p>nora@example.com</p>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:void(0)">
                        <i class="menu-icon fa fa-file-code-o bg-green"></i>

                        <div class="menu-info">
                          <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                          <p>Execution time 5 seconds</p>
                        </div>
                      </a>
                    </li>
                  </ul>
                  <!-- /.control-sidebar-menu -->

                  <h3 class="control-sidebar-heading">Tasks Progress</h3>
                  <ul class="control-sidebar-menu">
                    <li>
                      <a href="javascript:void(0)">
                        <h4 class="control-sidebar-subheading">
                          Custom Template Design
                          <span class="label label-danger pull-right">70%</span>
                        </h4>

                        <div class="progress progress-xxs">
                          <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:void(0)">
                        <h4 class="control-sidebar-subheading">
                          Update Resume
                          <span class="label label-success pull-right">95%</span>
                        </h4>

                        <div class="progress progress-xxs">
                          <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:void(0)">
                        <h4 class="control-sidebar-subheading">
                          Laravel Integration
                          <span class="label label-warning pull-right">50%</span>
                        </h4>

                        <div class="progress progress-xxs">
                          <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:void(0)">
                        <h4 class="control-sidebar-subheading">
                          Back End Framework
                          <span class="label label-primary pull-right">68%</span>
                        </h4>

                        <div class="progress progress-xxs">
                          <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                        </div>
                      </a>
                    </li>
                  </ul>
                  <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                  <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                      <label class="control-sidebar-subheading">
                        Report panel usage
                        <input type="checkbox" class="pull-right" checked>
                      </label>

                      <p>
                        Some information about this general settings option
                      </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                      <label class="control-sidebar-subheading">
                        Allow mail redirect
                        <input type="checkbox" class="pull-right" checked>
                      </label>

                      <p>
                        Other sets of options are available
                      </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                      <label class="control-sidebar-subheading">
                        Expose author name in posts
                        <input type="checkbox" class="pull-right" checked>
                      </label>

                      <p>
                        Allow the user to show his name in blog posts
                      </p>
                    </div>
                    <!-- /.form-group -->

                    <h3 class="control-sidebar-heading">Chat Settings</h3>

                    <div class="form-group">
                      <label class="control-sidebar-subheading">
                        Show me as online
                        <input type="checkbox" class="pull-right" checked>
                      </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                      <label class="control-sidebar-subheading">
                        Turn off notifications
                        <input type="checkbox" class="pull-right">
                      </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                      <label class="control-sidebar-subheading">
                        Delete chat history
                        <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                      </label>
                    </div>
                    <!-- /.form-group -->
                  </form>
                </div>
                <!-- /.tab-pane -->
              </div>
            </aside>
            <!-- /.control-sidebar -->
            <!-- Add the sidebars background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>');
    }
    
    /**
     * Agrega los JavaScripts Necesarios
     */
    public function AgregaJS(){
        print('<!-- Include js files -->
                <!-- Vendor Plugin -->
                <script type="text/javascript" src="../../assets/plugin/vendor.min.js"></script>
                <!-- Raphael Plugin -->
                <script type="text/javascript" src="../../assets/plugin/raphael/raphael-min.js"></script>
                <!-- Morris Plugin -->
                <script type="text/javascript" src="../../assets/plugin/morris/morris.min.js"></script>
                <!-- Sparkline Plugin -->
                <script type="text/javascript" src="../../assets/plugin/sparkline/jquery.sparkline.min.js"></script>
                <!-- Ticker Plugin -->
                <script type="text/javascript" src="../../assets/plugin/web-ticker/jquery.webticker.min.js"></script>
                <!-- Sparkline Plugin -->
                <script type="text/javascript" src="../../assets/plugin/echarts/echarts.js"></script>
                <!-- Sweetalert Plugin -->
                <script type="text/javascript" src="../../assets/plugin/sweetalert/sweetalert.js"></script>                
                <!-- Custom demo Script for Dashbaord -->
                <script type="text/javascript" src="../../dist/js/demo/dashboard.js"></script>
                <!-- Custom Script Plugin -->
                <script type="text/javascript" src="../../dist/js/custom.js"></script>
                <!-- Alertify Plugin 
                <script type="text/javascript" src="../../assets/plugin/alertify/lib/alertify.min.js"></script> -->
                ');
        
        
    }
    public function AgregaAngular(){
        print('<script src="../../componentes/angularjs/angular.min.js"></script>');
    }
    /**
     * Crea una barra de progreso
     * @param type $NombreBarra -> Nombre
     * @param type $NombreLeyenda -> Nombre de la leyenda
     * @param type $Tipo
     * @param type $Valor
     * @param type $Min
     * @param type $Max
     * @param type $Ancho
     * @param type $Leyenda
     * @param type $Color
     * @param type $Vector
     */
    public function ProgressBar($NombreBarra,$NombreLeyenda,$Tipo,$Valor,$Min,$Max,$Ancho,$Leyenda,$Color,$Vector) {
        print('<div class="progress">
                <div id="'.$NombreBarra.'" name="'.$NombreBarra.'" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'.$Valor.'" aria-valuemin="'.$Min.'" aria-valuemax="'.$Max.'" style="width:'.$Ancho.'%">
                  <div id="'.$NombreLeyenda.'" name="'.$NombreLeyenda.'"">'.$Leyenda.'</div>
                </div>
              </div>');
    }
    
    /**
     * Crear un Div 
     * @param type $ID->ID del DIV
     * @param type $Class-> Clase del div
     * @param type $Alineacion-> Alineacion
     * @param type $Visible-> 1 para hacer visible 0 para no hacerlo visible
     * @param type $Habilitado-> Habilitar
     * @param type $ng_angular-> Controladores de angular
     * @param type $Styles-> Mas estilos
     */
    function CrearDiv($ID, $Class, $Alineacion,$Visible,$Habilitado,$ng_angular='',$Styles=""){
        if($Visible==1)
            $V="block";
        else
            $V="none";

        if($Habilitado==1) ///pensado a futuro, aun no esta en uso
            $H="true";
        else
            $H="false";
        print("<div id='$ID' class='$Class' align='$Alineacion' style='display:$V;$Styles' $ng_angular>");

    }
    /**
     * Cierra un div
     */
    function CerrarDiv(){
        print("</div>");
		
    }
    /**
     * Dibuja una tabla desde la base de datos
     * @param type $Titulo
     * @param type $id
     * @param type $Ancho
     * @param type $js
     * @param type $Vector
     */
    function CrearTablaDB($Titulo,$id,$Ancho,$js,$Vector) {
        print('<div class="row" >');
        print('<div class="col-lg-11" style="overflow: auto; width:97% ;">');
        //print('<div class="panel panel-default">');
        //print('<div class="panel-heading">'.$Titulo.'</div>');
        print('<div class="panel-heading">');
        print('<table width="'.$Ancho.'" class="table table-striped table-bordered table-hover" id="'.$id.'" '.$js.'>');
    }
    /**
     * Cabecera para las tablas
     * @param type $Tabla
     * @param type $Limite
     * @param type $Titulo
     * @param type $Columnas
     * @param type $js
     * @param type $TotalRegistros
     * @param type $NumPage
     * @param type $Vector
     */
    function CabeceraTabla($Tabla,$Limite,$Titulo,$Columnas,$js,$TotalRegistros,$NumPage,$Vector,$DivTablas=''){
        
        $obCon=new conexion(1);
        print("<thead><tr>");
        $ColSpanTitulo=count($Columnas["Field"]);
        $this->th("", "", "1", "1", "", "");
            print("<strong></strong>");
        $this->Cth();
        $this->th("", "", 2, 1, "", "");    
            $this->select($Tabla."_CmbLimit", "form-control", $Tabla."_CmbLimit", "", "", "onchange=CambiarLimiteTablas('$Tabla','$DivTablas')", "style=width:200px");
                $Sel=0;
                if($Limite==10){
                    $Sel=1;
                }
                $this->option("", "", "", 10, "", "",$Sel);
                     print("Mostrar 10 Registros");
                $this->Coption();
                $Sel=0;
                if($Limite==25){
                    $Sel=1;
                }
                $this->option("", "", "", 25, "", "",$Sel);
                     print("Mostrar 25 Registros");
                $this->Coption();
                $Sel=0;
                if($Limite==50){
                    $Sel=1;
                }
                $this->option("", "", "", 50, "", "",$Sel);
                     print("Mostrar 50 Registros");
                $this->Coption();
                $Sel=0;
                if($Limite==100){
                    $Sel=1;
                }
                $this->option("", "", "", 100, "", "",$Sel);
                     print("Mostrar 100 Registros");
                $this->Coption();
            $this->Cselect();
        
        $this->Cth();
        $this->th("", "", $ColSpanTitulo-2, 1, "", "");
            print("<strong>$Titulo </strong>");
        $this->Cth();
        $this->tr("", "", 1, 1, "", "");
        $c=0;
        foreach ($Columnas["Field"] as $key => $value) {
            if($c==0){
                $c=1;
                $this->th("", "", 1, 1, "", "");
                    print("<<_Opciones_>>");
                $this->Cth();
            }
            
            $NombreColumna=utf8_encode($Columnas["Visualiza"][$key]);
            $this->th("", "", 1, 1, "", "");
                $js="onclick=EscribirEnCaja('".$Tabla."_ordenColumna','$value');CambiarOrden('$Tabla','$DivTablas');DibujeTablaDB('$Tabla','$DivTablas');";
                $this->a("", "", "#".$Tabla."_aControlCampos", "", "", "", $js);                    
                    print('<strong>'.$NombreColumna.'</strong>');
                $this->Ca();
                
            $this->Cth();
                
            
        }
        print("</tr></thead>");
    }
    /**
     * Crea una Fila de la tabla
     * @param type $tabla
     * @param type $Datos
     * @param type $js
     * @param type $Vector
     */
    function FilaTablaDB($tabla,$DivTablas,$Datos,$js,$Vector){
        $obCon=new conexion(1);
        $DatosControlTablas=$obCon->DevuelveValores("configuracion_control_tablas", "TablaDB", $tabla);
        if($DatosControlTablas["Editar"]<>0){
            $OpcionEditar=1;
        }else{
            $OpcionEditar=0;
        }
        if($DatosControlTablas["Ver"]==1){
            $DatosLink=$obCon->DevuelveValores("configuracion_control_tablas", "TablaDB", $tabla);
            $LinkVer=$DatosLink["LinkVer"];
            $OpcionVer=1;
        }else{
            $OpcionVer=0;
        }
        if($DatosControlTablas["AccionesAdicionales"]==1){
            $sql="SELECT * FROM configuracion_tablas_acciones_adicionales WHERE TablaDB='$tabla'";
            $consultaAccionesAdicionales=$obCon->Query($sql);
            $AccionesAdicionales=[];
            $i=0;
            while ($DatosAccionesAdicionales = $obCon->FetchAssoc($consultaAccionesAdicionales)) {
                $AccionesAdicionales[$i]["Titulo"]=$DatosAccionesAdicionales["Titulo"];
                $AccionesAdicionales[$i]["JavaScript"]=$DatosAccionesAdicionales["JavaScript"];
                $AccionesAdicionales[$i]["ClaseIcono"]=$DatosAccionesAdicionales["ClaseIcono"];
                $AccionesAdicionales[$i]["Ruta"]=$DatosAccionesAdicionales["Ruta"];
                $AccionesAdicionales[$i]["Target"]=$DatosAccionesAdicionales["Target"];
                $i++;
            }
            $DatosOtrasAcciones=$obCon->DevuelveValores("configuracion_tablas_acciones_adicionales", "TablaDB", $tabla);
            $OpcionOtrasAcciones=1;
        }else{
            $OpcionOtrasAcciones=0;
        }
        print('<tr class="odd gradeX">');
        $c=0;
        foreach ($Datos as $key => $value) {
            if($c==0){
                $c=1;                
                print("<td>");
                if($OpcionVer==1){
                    
                    $Link="../../general/Consultas/".$LinkVer.$value;
                    print('<i class="fa fa-fw fa-eye"></i><a href="'.$Link.'" target="_blank"> Ver </a><br>');
                } 
                if($OpcionEditar==1){
                    print('<i class="fa fa-fw fa-edit" onclick=DibujaFormularioEditarRegistro(`'.$tabla.'`,`'.$DivTablas.'`,`'.$value.'`)></i><a href=# onclick=DibujaFormularioEditarRegistro(`'.$tabla.'`,`'.$DivTablas.'`,`'.$value.'`)> Editar </a><br>');
                } 
                if($OpcionOtrasAcciones==1){
                    foreach ($AccionesAdicionales as $key => $Accion) {
                       //print_r($Accion);
                       $TituloAccion=$Accion["Titulo"];
                       $js=$Accion["JavaScript"]."(".$value.")";
                       $ClaseIcono=$Accion["ClaseIcono"];
                       $Ruta=$Accion["Ruta"].$value;
                       $Target=$Accion["Target"];
                       print('<i class="'.$ClaseIcono.'" '.$js.'></i><a href="'.$Ruta.'" target="'.$Target.'" '.$js.'> '.$TituloAccion.' </a><br>');
              
                    }
                    //$Link="../../general/Consultas/".$LinkVer.$value;
                    //print('<i class="fa fa-fw fa-eye"></i><a href="'.$Link.'" target="_blank")> Ver </a><br>');
                } 
                print("</td>");
            }            
            $value= utf8_encode($value);                        
            print("<td>$value</td>");
            
        }
        print("</tr>");
    }
    /**
     * Cierra la tabla
     */
    function CerrarTablaDB() {
        print('</table></div></div></div>');
    }
    /**
     * Crear un input text
     * @param type $nombre
     * @param type $type
     * @param type $label
     * @param type $value
     * @param type $placeh
     * @param type $color
     * @param type $TxtEvento
     * @param type $TxtFuncion
     * @param type $Ancho
     * @param type $Alto
     * @param type $ReadOnly
     * @param type $Required
     * @param type $ToolTip
     * @param type $Max
     * @param type $Min
     * @param type $TFont
     */
    function CrearInputText($nombre,$type,$label,$value,$placeh,$color,$TxtEvento,$TxtFuncion,$Ancho,$Alto,$ReadOnly,$Required,$ToolTip='Rellena este Campo',$Max="",$Min="",$TFont='1em'){
		   
        if($ReadOnly==1)
                $ReadOnly="readonly";
        else
                $ReadOnly="";

        if($Required==1)
                $Required="required";
        else
                $Required="";
        
        $JavaScript=$TxtEvento.' = '.$TxtFuncion;
        $OtrasOpciones="";
        if($Max<>''){
            $OtrasOpciones="max=$Max min=$Min";
        }

        print('<strong style="color:'.$color.'">'.$label.'<input name="'.$nombre.'" class="form-control" value="'.$value.'" type="'.$type.'" id="'.$nombre.'" '.$OtrasOpciones.' placeholder="'.$placeh.'" '.$JavaScript.' 
        '.$ReadOnly.' '.$Required.' autocomplete="off" style="width: '.$Ancho.'px;height: '.$Alto.'px; font-size: '.$TFont.' ;data-toggle="tooltip" title="'.$ToolTip.'" "></strong>');

    }
    /**
     * Crea un input text con un boton al lado
     * @param type $type
     * @param type $idText
     * @param type $idButton
     * @param type $class
     * @param type $name
     * @param type $nameButton
     * @param type $title
     * @param type $titleButton
     * @param type $value
     * @param type $valueButton
     * @param type $placeholder
     * @param type $autocomplete
     * @param type $vectorhtml
     * @param type $Script
     * @param type $ScriptButton
     * @param type $styles
     * @param type $np_app
     */
    public function CrearInputTextButton($type, $idText, $idButton, $class, $name,$nameButton, $title,$titleButton, $value,$valueButton, $placeholder, $autocomplete, $vectorhtml, $Script,$ScriptButton, $styles, $ng_app_text,$ng_app_button='') {
        $this->div("", "input-group", "", "", "", "", "");
            $this->input($type, $idText, $class, $name, $title, $value, $placeholder, $autocomplete, $vectorhtml, $Script, $styles, $ng_app_text);
            $this->span("", "input-group-btn", "", "");
                print('<button id='.$idButton.' type="button" class="btn btn-success btn-flat" '.$ScriptButton.' '.$ng_app_button.'>'.$valueButton.'</button>');
                //$this->boton($idButton, "btn btn-info btn-flat", "button", $nameButton, $titleButton, $valueButton, "", $ScriptButton);
            $this->Cspan();
        $this->Cdiv();
    }
    
    /**
     * crea un boton con eventos javascript
     * @param type $nombre
     * @param type $value
     * @param type $enabled
     * @param type $evento
     * @param type $funcion
     * @param type $Color
     * @param type $VectorBoton
     */
    function CrearBotonEvento($nombre,$value,$enabled,$evento,$funcion,$Color,$VectorBoton=''){
            
            switch ($Color){
                case "verde":
                    $Clase="btn btn-success";
                    break;
                case "naranja":
                    $Clase="btn btn-warning";
                    break;
                case "rojo":
                    $Clase="btn btn-danger";
                    break;
                case "blanco":
                    $Clase="btn";
                    break;
                case "azulclaro":
                    $Clase="btn btn-info";
                    break;
                case "azul":
                    $Clase="btn btn-block btn-primary";
                    break;
            }
            if($enabled==1){
                print('<input type="submit" id="'.$nombre.'"  name="'.$nombre.'" value="'.$value.'" '.$evento.'="'.$funcion.' ; return false" class="form-control '.$Clase.'">');
            }else{
                print('<input type="submit" id="'.$nombre.'" disabled="true" name="'.$nombre.'" value="'.$value.'" '.$evento.'="'.$funcion.' ; return false" class="form-control '.$Clase.'">');  
            }
		
		
	}
        /**
         * inicia el Paginador de las tablas
         */
        public function PaginadorTablasInit() {
            print('<div class="col-sm-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                        <ul class="pagination">
                            ');
        }
        /**
         * Pagina anterior
         * @param type $Estado
         * @param type $js
         */
        public function PaginadorAnterior($Estado,$js) {
            
            if($Estado==0){
                $Estado="disabled";
            }else{
                $Estado="";
            }
            print('<li class="paginate_button previous '.$Estado.'" id="example1_previous">
                    <a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0" '.$js.'>Anterior</a>
                   </li>');
        }
        /**
         * Boton del paginador
         * @param type $Estado
         * @param type $Numero
         */
        public function PaginadorBoton($Estado,$Numero) {
            
            if($Estado==1){
                $Estado="active";
            }else{
                $Estado="";
            }
            print('<li class="paginate_button '.$Estado.'">
                    <a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0" onclick=CambiaPagina('.$Numero.')>'.$Numero.'</a>
                </li>');
        }
        
        /**
         * Pagina siguiente
         * @param type $Estado
         * @param type $js
         */
        public function PaginadorSiguiente($Estado,$js) {
            if($Estado==0){
                $Estado="disabled";
            }else{
                $Estado="";
            }
            print('<li class="paginate_button next '.$Estado.'" id="example1_next">
                        <a href="#" aria-controls="example1" data-dt-idx="7" tabindex="0" '.$js.'>Siguiente</a>
                    </li>');
        }
        /**
         * Fin del paginador
         */
        public function PaginadorFin() {
            print('</ul></div></div>');
        }
        /**
         * Paginador para las tablas
         * @param type $Tabla
         * @param type $Limit
         * @param type $PaginaActual
         * @param type $TotalRegistros
         * @param type $Color
         * @param type $js
         * @param type $vector
         */
        public function PaginadorTablas($Tabla,$Limit,$PaginaActual,$TotalRegistros,$vector,$DivTablas='') {
            $this->div("", "col-lg-12", "", "", "", "", "");
            $this->div("", "btn-group-vertical", "", "", "", "", "");
            
            $Estado="disabled";
            $js="";
            if($PaginaActual<>1){
                $Estado="";
                $js="onclick=RetrocederPagina('$Tabla','$DivTablas');";
            }
            $this->input("submit", $Tabla."_BtnRetroceder", "btn btn-block btn-warning btn-xs $Estado", $Tabla."_BtnRetroceder", "Atrás", "Atrás", "", "", "", $js);
            
            $TotalPaginas=ceil($TotalRegistros/$Limit);
            
            $this->select($Tabla."_CmbPage", "btn btn-default btn-xs", $Tabla."_CmbPage", "", "", "onchange=SeleccionaPagina('$Tabla','$DivTablas');", "");
            for($i=1;$i<=$TotalPaginas;$i++){
                $Estado=0;
                if($PaginaActual==$i){
                    $Estado=1;
                }
                
                $this->option("", "", $i, $i, "", "",$Estado);
                    print("$i");
                $this->Coption();
            }
            $this->Cselect();
            $Estado="";
            $js="onclick=AvanzarPagina('$Tabla','$DivTablas');";
            if($TotalPaginas==$PaginaActual){
                $Estado="disabled";
                $js="";
            }
            $this->input("submit", $Tabla."_BtnAvanzar", "btn btn-block btn-warning btn-xs $Estado", $Tabla."_BtnAvanzar", "Avanzar", "Avanzar", "", "", "", $js);
            $this->Cdiv();
            $this->Cdiv();
        }
        
        /**
         * Construye un Menu general en el panel lateral
         * @param type $Nombre
         * @param type $Clase
         * @param type $Activo
         * @param type $vector
         */
        public function PanelMenuGeneralInit($Nombre,$Clase,$Activo,$vector) {
            if($Activo==1){
                $Activo="active";
            }else{
                $Activo="";
            }
            print('<li class="treeview '.$Activo.'">
                <a href="#">
                  <i class="'.$Clase.'"></i> <span>'.$Nombre.'</span>
                  <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                </a>
                
                ');
        }
        /**
         * Cierra el menu general
         */
        public function PanelMenuGeneralFin() {
            $this->Cli();
        }
        /**
         * Inicia el panel de las pestañas en el menu
         */
        public function PanelPestanaInit() {
            print('<ul class="treeview-menu">');
        }
        /**
         * Pestaña para el menu
         * @param type $Nombre
         * @param type $Clase
         * @param type $vector
         */
        public function PanelPestana($Nombre,$Clase,$vector) {
            print('<li class="treeview">
                    <a href="#"><i class="'.$Clase.'"></i> '.$Nombre.'
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    
                  ');
        }
        /**
         * Fin de la pestaña
         */
        public function PanelPestanaFin() {
            print('</li></ul>');
                  
        }
        /**
         * inicia el Submenu en el panel  
         */
        public function PanelSubMenuInit() {
            print('<ul class="treeview-menu">');
        }
        /**
         * Finaliza el submenu
         */
        public function PanelSubMenuFin() {
            print('</ul>');
        }
        /**
         * Submenu
         * @param type $Nombre
         * @param type $Link
         * @param type $js
         * @param type $Clase
         */
        public function PanelSubMenu($Nombre,$Link,$js,$Clase,$Target) {
            print('<li><a href="'.$Link.'" target='.$Target.' '.$js.'><i class="'.$Clase.'"></i> '.$Nombre.'</a></li>');
        }
        /**
         * Construye el menu lateral dibujando solo lo que el usuario por su tipo tiene permitido
         * @param type $idUsuario
         * @param type $vector
         */
        public function ConstruirMenuLateral($idUsuario,$vector) {
            $obCon=new conexion($idUsuario);
            $sql="SELECT Nombre,Apellido,Identificacion,TipoUser FROM usuarios WHERE idUsuarios='$idUsuario'";
            $Consulta=$obCon->Query($sql);
            $DatosUsuario=$obCon->FetchAssoc($Consulta);
            $TipoUser=$DatosUsuario["TipoUser"];
            $NombreUsuario= utf8_encode($DatosUsuario["Nombre"]." ".$DatosUsuario["Apellido"]);
            
            $this->MenuLateralInit();    
                $this->PanelInfoUser($NombreUsuario);
                //$css->PanelBusqueda(); //uso futuro
                $this->PanelLateralInit("<a href='../menu/Menu.php'>MENU GENERAL</a>");
                    $sql="SELECT m.ID,m.CSS_Clase,m.Nombre, m.Pagina,m.Target,m.Image,m.Orden, c.Ruta FROM menu m "
                    . "INNER JOIN menu_carpetas c ON c.ID=m.idCarpeta WHERE m.Estado=1 ORDER BY m.Orden ASC";
                    $Consulta=$obCon->Query($sql);
                    //$Consulta=$obCon->ConsultarTabla("menu"," WHERE Estado=1 ORDER BY Orden ASC");
                    while($DatosMenu=$obCon->FetchArray($Consulta)){
                        $idMenu=$DatosMenu["ID"];
                        if($DatosUsuario["TipoUser"]=="administrador"){
                            $Visible=1;
                        }else{
                            $Visible=0;
                            $sql="SELECT ID FROM paginas_bloques WHERE TipoUsuario='$TipoUser' AND Pagina='$DatosMenu[Pagina]' AND Habilitado='SI'";
                            $DatosUser=$obCon->Query($sql);
                            $DatosUser=$obCon->FetchArray($DatosUser);
                            if($DatosUser["ID"]>0){
                                $Visible=1;
                            }
                        }
                        if($Visible==1){
                            $this->PanelMenuGeneralInit(utf8_encode($DatosMenu["Nombre"]),$DatosMenu["CSS_Clase"],0,"");
                                $ConsultaPestanas=$obCon->ConsultarTabla("menu_pestanas"," WHERE idMenu='$idMenu' AND Estado=1 ORDER BY Orden ASC");
                                $this->PanelPestanaInit();
                                while($DatosPestanas=$obCon->FetchAssoc($ConsultaPestanas)){
                                    $idPestana=$DatosPestanas["ID"];
                                    
                                    $ConsultaSubmenus=$obCon->ConsultarTabla("menu_submenus"," WHERE idPestana='$idPestana' AND Estado=1 ORDER BY Orden ASC");
                                    $IniciaPestana=1;
                                    while($DatosSubMenu=$obCon->FetchAssoc($ConsultaSubmenus)){
                                        
                                        if($DatosUsuario["TipoUser"]=="administrador"){
                                        $Visible=1;
                                        }else{
                                            $Visible=0;
                                            $sql="SELECT ID FROM paginas_bloques WHERE TipoUsuario='$TipoUser' AND Pagina='$DatosSubMenu[Pagina]' AND Habilitado='SI'";
                                            $DatosUser=$obCon->Query($sql);
                                            $DatosUser=$obCon->FetchArray($DatosUser);
                                            if($DatosUser["ID"]>0){
                                                $Visible=1;
                                            }
                                        }
                                        
                                       
                                        
                                        if($Visible==1){
                                            if($IniciaPestana==1){
                                                $IniciaPestana=0;
                                                $this->PanelPestana(utf8_encode($DatosPestanas["Nombre"]), "fa fa-circle-o text-red", "");
                                                $this->PanelSubMenuInit();
                                            }
                                            $DatosCarpeta=$obCon->DevuelveValores("menu_carpetas", "ID", $DatosSubMenu["idCarpeta"]);
                                            $Ruta="#";
                                            if($DatosSubMenu["JavaScript"]==''){
                                                $Ruta='../'.$DatosCarpeta["Ruta"].$DatosSubMenu["Pagina"];
                                            }
                                            
                                            $this->PanelSubMenu(utf8_encode($DatosSubMenu["Nombre"]), $Ruta, $DatosSubMenu["JavaScript"], "fa fa-circle-o text-aqua",$DatosSubMenu["Target"]);
                                        }
                                        
                                    }
                                    $this->PanelSubMenuFin();
                                }
                                $this->PanelPestanaFin();

                            $this->PanelMenuGeneralFin();
                        }
                    }
                    
                $this->CPanelLateral();
            $this->MenuLateralFin();
            
        }
        /**
         * Crea una tabla
         */
        public function CrearTabla($id=""){
            if($id<>''){
                $id="id=".$id;
            }
            print('<div  class="table-responsive"><table '.$id.'  class="table table-bordered table table-hover" >');		
	}
        
        /**
         * Fila tabla
         * @param type $FontSize
         */
	function FilaTabla($FontSize,$styles=''){
            
            
            print('<tr class="odd gradeX" style="font-size:'.$FontSize.'px;'.$styles.'">');
		
	}
	/**
         * Cierra una Fila de una tabla
         */
	function CierraFilaTabla(){
            print('</tr>');
		
	}
	
	/**
         * Columna de una tabla
         * @param type $Contenido
         * @param type $ColSpan
         * @param type $align-> alineacion: L izquierda, R Derecha, C centro
         */
	function ColTabla($Contenido,$ColSpan,$align="L",$AdicionalStyles=""){
            if($align=="L"){
              $align="left";  
            }
            if($align=="R"){
              $align="right";  
            }
            if($align=="C"){
              $align="center";  
            }
            print('<td colspan="'.$ColSpan.' " style="text-align:'.$align.';'.$AdicionalStyles.'"   >'.$Contenido.'</td>');
		
	}
	/**
         * Cierre columna de tabla
         */
	function CierraColTabla(){
            print('</td>');		
	}
	/**
         * Cierra la tabla
         */
	function CerrarTabla(){
            print('</table></div>');		
	}
        /**
         * Check box con css
         * @param type $Nombre
         * @param type $id
         * @param type $Leyenda
         * @param type $Estado-> 1 para chequeado 0 para no
         * @param type $Habilitado-> 0 para deshabilitar
         * @param type $js->JavaScript
         * @param type $Style
         * @param type $ng_app
         * @param type $vector
         */
        function CheckBoxTS($Nombre,$id,$Leyenda,$Estado,$Habilitado,$js,$Style,$ng_app,$vector) {
            
            if($Estado==1){
                $Estado="checked";
            }else{
                $Estado="";
            }
            
            if($Habilitado==0){
                $Habilitado="disabled";
            }else{
                $Habilitado="";
            }
                        
            print('<label class="checkts">'.$Leyenda.'<input name='.$Nombre.' id='.$id.' type="checkbox" '.$Estado.' '.$Habilitado.' '.$Style.' '.$js.'  '.$ng_app.'><span class="checkmark"></span></label>');        
        }
        
        /**
         * Crea una ventana modal
         * @param type $id
         * @param type $title
         * @param type $Vector
         * @param type $Amplio
         * @param type $Oculto
         * @param type $Tipo
         */
        function Modal($id,$title,$Vector,$Amplio=0,$Oculto=1,$Tipo=1){
            $OcultarModal="";
            if($Oculto==1){
                $OcultarModal="fade";
            }
            $class="modal";
            if($Tipo==2){
                $class="modal modal-primary";
            }
            if($Tipo==3){
                $class="modal modal-info";
            }
            if($Tipo==4){
                $class="modal modal-warning";
            }
            
            $style="";
            if($Amplio==1){
                $style='style="width: 80%;"';
            }
            print('<div class="'.$class.' '.$OcultarModal.'" id="'.$id.'" >
                    <div class="modal-dialog" '.$style.'>
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">'.$title.'</h4>
                        </div>
                        <div class="modal-body">');
        }
        
        /**
         * Cierra la ventana modal
         * @param type $idBoton->id para el boton de confirmacion
         * @param type $JSBoton->JavaScript para el boton de confirmacion
         * @param type $TituloBoton->Titulo del boton de confirmacion
         * @param type $ClassBoton->Clase del boton de confirmacion
         */
        function CModal($idBoton,$JSBoton,$TipoBoton,$TituloBoton,$ClassBoton='btn btn-primary') {
            print('</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                      <button id='.$idBoton.' type="'.$TipoBoton.'" class="'.$ClassBoton.'" '.$JSBoton.'>'.$TituloBoton.'</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>');
        }
        /**
         * Boton para abrir una ventana modal
         * @param type $Leyenda
         * @param type $Modal
         * @param type $js
         * @param type $Color
         */
        public function BotonAbreModal($Leyenda,$Modal,$js,$Color='azuloscuro'){
            
            switch ($Color){
                case "verde":
                    $Clase="btn btn-success";
                    break;
                case "naranja":
                    $Clase="btn btn-warning";
                    break;
                case "rojo":
                    $Clase="btn btn-danger";
                    break;
                case "blanco":
                    $Clase="btn";
                    break;
                case "azulclaro":
                    $Clase="btn btn-info";
                    break;
                case "azul":
                    $Clase="btn btn-block btn-primary";
                break;
                case "azuloscuro":
                    $Clase="btn btn-default";
                break;
            }            
            print('<button type="button" class="'.$Clase.'" data-toggle="modal" data-target="#'.$Modal.'">
                '.$Leyenda.'</button>');
        }
        /**
         * Dibuja el formulario para insertar un registro nuevo
         * @param type $Tabla
         * @param type $Columnas
         * @param type $NumeroGrids
         * @param type $vector
         */        
        public function DibujaCamposFormularioInsert($Tabla,$idDivTabla,$Columnas,$NumeroGrids,$vector) {
            $obCon=new conexion($_SESSION["idUser"]);  
            $this->input("hidden", "TxtTipoFormulario", "", "TxtTipoFormulario", "", "Insertar", "", "", "", "");
            $this->input("hidden", "TxtTablaDB", "", "TxtTablaDB", "", $Tabla, "", "", "", "");
            $this->input("hidden", "TxtIdDivTablaDB", "", "TxtIdDivTablaDB", "", $idDivTabla, "", "", "", "");
            $this->input("hidden", "TxtTipoFormulario", "", "TxtTipoFormulario", "", "Insertar", "", "", "", "");
            $this->div("", "row", "", "", "", "", "");
                $this->div("content", "col-lg-12", "", "", "", "", "");
                    
                    foreach ($Columnas["Field"] as $key => $value) {
                        
                        if($key>0){
                            $sql="SELECT * FROM configuracion_campos_asociados WHERE TablaOrigen='$Tabla' AND CampoTablaOrigen='$value'";
                            $Consulta=$obCon->Query($sql);
                            $DatosCamposAsociados=$obCon->FetchAssoc($Consulta);
                            $DatosTipoColumna = explode('(', $Columnas["Type"][$key]);
                            $Tipo=$DatosTipoColumna[0];
                            $TipoCaja="";
                            $Titulo= utf8_encode($Columnas["Visualiza"][$key]);
                            $this->label("", "", "name", "", "");
                                print($Titulo);
                            $this->Clabel();
                            if($DatosCamposAsociados["ID"]>0){
                                $TablaAsociada=$DatosCamposAsociados["TablaAsociada"];
                                $CampoAsociado=$DatosCamposAsociados["CampoAsociado"];
                                $this->select($value, "form-control", "CmbInserts", $Titulo, "", "", "");
                                    $this->option("", "", "Seleccione una opción", "", "", "");
                                            print("Seleccione una opción");
                                        $this->Coption();
                                    $sql="SELECT $CampoAsociado as CampoAsociado FROM $TablaAsociada ORDER BY $CampoAsociado";
                                    $Consulta=$obCon->Query($sql);
                                    while($DatosAsociacion=$obCon->FetchAssoc($Consulta)){
                                        $this->option("", "", utf8_encode($DatosAsociacion["CampoAsociado"]), $DatosAsociacion["CampoAsociado"], "", "");
                                            print(utf8_encode($DatosAsociacion["CampoAsociado"]));
                                        $this->Coption();
                                        
                                    }
                                $this->Cselect();
                            }else{
                                                                
                                if($Tipo=="tinyint" or $Tipo=="smallint" or $Tipo=="mediumint" or $Tipo=="int" or $Tipo=="bigint" or $Tipo=="decimal" or $Tipo=="float" or $Tipo=="double" or $Tipo=="year"){

                                    $TipoCaja="number";
                                    $Script="";
                                    $this->input($TipoCaja, $value, "form-control", "TxtNuevoRegistro", $Titulo, "", $Titulo, "", "", $Script);


                                }elseif($Tipo=="date" or $Tipo=="datetime" or $Tipo=="timestamp") {
                                    
                                    $TipoCaja="date";
                                    $Script="";
                                    print('<div class="input-group date">
                                    <div class="input-group-addon">
                                      <i class="fa fa-calendar"></i>
                                    </div>');

                                    $this->input($TipoCaja, $value, "form-control", "TxtNuevoRegistro", $Titulo, date("Y-m-d"), $Titulo, "", "", $Script,"style='line-height: 15px;'");
                                    print('</div>');

                                }elseif($Tipo=="time"){

                                    $TipoCaja="time";
                                    $Script="";
                                    $this->input($TipoCaja, $value, "form-control", "TxtNuevoRegistro", $Titulo, "", $Titulo, "", "", $Script);


                                }elseif($Tipo=="text" or $Tipo=="mediumtext" or $Tipo=="longtext"){

                                    $TipoCaja="textarea";
                                    $Script="";                        
                                    $this->textarea($value, "form-control", "TxtNuevoRegistro", $Titulo, $Titulo, "", $Script);

                                    $this->Ctextarea();                    


                                }else{
                                    $TipoCaja="text";
                                    $Pattern="";
                                    if($value=="Email"){
                                        $TipoCaja="email";
                                        $Pattern=""; //Solo Letras y numeros
                                    }
                                    
                                    if($value=="Password"){
                                        $Pattern="pattern=[A-Za-z0-9]+"; //Solo Letras y numeros
                                        $TipoCaja="password";
                                        $Titulo="Solo Letras y Números";
                                        print('<div id="pswd_info">
                                            <h4>El Password debe cumplir con los siguientes requerimientos:</h4>
                                            <ul>
                                              <li id="letter" class="invalid">Que contenga al menos <strong>una Letra</strong>
                                              </li>
                                              <li id="capital" class="invalid">Que contenga al menos <strong>una Mayúscula</strong>
                                              </li>
                                              <li id="number" class="invalid">Que contenga al menos <strong>un número</strong>
                                              </li>
                                              <li id="length" class="invalid">Que contenga al menos <strong>8 caracteres</strong>
                                              </li>
                                            </ul>
                                          </div>');
                                    }

                                    $Script="";
                                    
                                    $this->input($TipoCaja, $value, "form-control", "TxtNuevoRegistro", $Titulo, "", $Titulo, "off", "", $Script,$Pattern);

                                }  

                            }
                        }   

                    }
                   
                $this->Cdiv();
            $this->Cdiv();
        }
        
        
        public function DibujaCamposFormularioEdit($Tabla,$idDivTabla,$idEdit,$Columnas,$NumeroGrids,$vector) {
            $obCon=new conexion($_SESSION["idUser"]);  
            $this->input("hidden", "TxtTipoFormulario", "", "TxtTipoFormulario", "", "Editar", "", "", "", "");
            $this->input("hidden", "TxtIdEdit", "", "TxtIdEdit", "", "$idEdit", "", "", "", "");
            $this->input("hidden", "TxtTablaDB", "", "TxtTablaDB", "", $Tabla, "", "", "", "");
            $this->input("hidden", "TxtIdDivTablaDB", "", "TxtIdDivTablaDB", "", $idDivTabla, "", "", "", "");
            $this->div("", "row", "", "", "", "", "");
                $this->div("content", "col-lg-12", "", "", "", "", "");
                    $idTabla=$Columnas["Field"][0];
                    $DatosRegistro=$obCon->DevuelveValores($Tabla, $idTabla, $idEdit);
                    
                    foreach ($Columnas["Field"] as $key => $value) {
                        
                        if($key>0){
                            $sql="SELECT * FROM configuracion_campos_asociados WHERE TablaOrigen='$Tabla' AND CampoTablaOrigen='$value'";
                            $Consulta=$obCon->Query($sql);
                            $DatosCamposAsociados=$obCon->FetchAssoc($Consulta);
                            $DatosTipoColumna = explode('(', $Columnas["Type"][$key]);
                            $Tipo=$DatosTipoColumna[0];
                            $TipoCaja="";
                            $Titulo= utf8_encode($Columnas["Visualiza"][$key]);
                            $this->label("", "", "name", "", "");
                                print($Titulo);
                            $this->Clabel();
                            if($DatosCamposAsociados["ID"]>0){
                                $TablaAsociada=$DatosCamposAsociados["TablaAsociada"];
                                $CampoAsociado=$DatosCamposAsociados["CampoAsociado"];
                                $this->select($value, "form-control", "CmbInserts", $Titulo, "", "", "");
                                    $this->option("", "", "Seleccione una opción", "", "", "");
                                            print("Seleccione una opción");
                                        $this->Coption();
                                    $sql="SELECT $CampoAsociado as CampoAsociado FROM $TablaAsociada ORDER BY $CampoAsociado";
                                    $Consulta=$obCon->Query($sql);
                                    while($DatosAsociacion=$obCon->FetchAssoc($Consulta)){
                                        $Sel=0;
                                        if($DatosRegistro[$value]==$DatosAsociacion["CampoAsociado"]){
                                            $Sel=1;
                                        }
                                        $this->option("", "", utf8_encode($DatosAsociacion["CampoAsociado"]), $DatosAsociacion["CampoAsociado"], "", "",$Sel);
                                            print(utf8_encode($DatosAsociacion["CampoAsociado"]));
                                        $this->Coption();
                                        
                                    }
                                $this->Cselect();
                            }else{
                                                                
                                if($Tipo=="tinyint" or $Tipo=="smallint" or $Tipo=="mediumint" or $Tipo=="int" or $Tipo=="bigint" or $Tipo=="decimal" or $Tipo=="float" or $Tipo=="double" or $Tipo=="year"){

                                    $TipoCaja="number";
                                    $Script="";
                                    $this->input($TipoCaja, $value, "form-control", "TxtNuevoRegistro", $Titulo, $DatosRegistro[$value], $Titulo, "", "", $Script);


                                }elseif($Tipo=="date" or $Tipo=="datetime" or $Tipo=="timestamp") {
                                    
                                    $TipoCaja="date";
                                    $Script="";
                                    print('<div class="input-group date">
                                    <div class="input-group-addon">
                                      <i class="fa fa-calendar"></i>
                                    </div>');

                                    $this->input($TipoCaja, $value, "form-control", "TxtNuevoRegistro", $Titulo, $DatosRegistro[$value], $Titulo, "", "", $Script,"style='line-height: 15px;'");
                                    print('</div>');

                                }elseif($Tipo=="time"){

                                    $TipoCaja="time";
                                    $Script="";
                                    $this->input($TipoCaja, $value, "form-control", "TxtNuevoRegistro", $Titulo, $DatosRegistro[$value], $Titulo, "", "", $Script);


                                }elseif($Tipo=="text" or $Tipo=="mediumtext" or $Tipo=="longtext"){

                                    $TipoCaja="textarea";
                                    $Script="";                        
                                    $this->textarea($value, "form-control", "TxtNuevoRegistro", $Titulo, $Titulo, "", $Script);
                                        print($DatosRegistro[$value]);
                                    $this->Ctextarea();                    


                                }else{
                                    $TipoCaja="text";
                                    $Pattern="";
                                    if($value=="Email"){
                                        $TipoCaja="email";
                                        //$Pattern="pattern=[A-Za-z0-9]+"; //Solo Letras y numeros
                                    }
                                    
                                    if($value=="Password"){
                                        $Pattern="pattern=[A-Za-z0-9]+"; //Solo Letras y numeros
                                        $TipoCaja="password";
                                        $Titulo="Solo Letras y Números";
                                        print('<div id="pswd_info">
                                            <h4>El Password debe cumplir con los siguientes requerimientos:</h4>
                                            <ul>
                                              <li id="letter" class="invalid">Que contenga al menos <strong>una Letra</strong>
                                              </li>
                                              <li id="capital" class="invalid">Que contenga al menos <strong>una Mayúscula</strong>
                                              </li>
                                              <li id="number" class="invalid">Que contenga al menos <strong>un número</strong>
                                              </li>
                                              <li id="length" class="invalid">Que contenga al menos <strong>8 caracteres</strong>
                                              </li>
                                            </ul>
                                          </div>');
                                    }

                                    $Script="";
                                    
                                    $this->input($TipoCaja, $value, "form-control", "TxtNuevoRegistro", $Titulo, $DatosRegistro[$value], $Titulo, "off", "", $Script,$Pattern);

                                }  

                            }
                        }   

                    }
                   
                $this->Cdiv();
            $this->Cdiv();
        }
        
        
        public function Notificacion($Titulo,$Leyenda,$Color,$js,$vector) {
            if($Color=="azul"){
                $clase="callout callout-info";
            }
            if($Color=="rojo"){
                $clase="callout callout-danger";
            }
            if($Color=="naranja"){
                $clase="callout callout-warning";
            }
            if($Color=="verde"){
                $clase="callout callout-success";
            }
            print('<div class="'.$clase.'" '.$js.'>
                      <h4>'.$Titulo.'</h4>
                      <p>'.$Leyenda.'</p>
                    </div>');
        }
        
        public function TituloMenu($Titulo) {
            print('<h3 style= color:#d4b038>'.$Titulo.'</h3><br>');
        }
        
        public function IniciaTabs() {
            print('<div class="div-tabs">
            <ul class="nav nav-tabs">');
        }
        
        public function NuevaTab($idTab,$Titulo,$vector,$Activa='') {
            if($Activa==1){
                $Activa='class="active"';
            }
            print('<li '.$Activa.'><a href="#'.$idTab.'" data-toggle="tab" aria-expanded="true">'.$Titulo.'</a></li>');
        }
        
        public function CierraTabs() {
            print('</ul>');
        }
        
        public function FinTabs() {
            print('</div>');
        }
        
        
        public function IniciaContenidoTabs() {
            print('<div class="tab-content">');
        }
        
        public function FinContenidoTabs() {
            print('</div>');
        }
        
        public function ContenidoTabs($idTab,$vector,$Activo='class="tab-pane"') {
            if($Activo==1){
                $Activo='class="tab-pane active"';
            }
            print('<div '.$Activo.' id="'.$idTab.'">');
        }
        
        public function CierreContenidoTabs() {
            print('</div>');
        }
        
        public function ImageLink($Link,$Target,$Imagen,$vector,$js,$style='') {
            print('<a href="'.$Link.'" target="'.$Target.'" ><img src="'.$Imagen.'" '.$js.' alt="" '.$style.'></a>');
        }
        
        
        function SubTabs($link,$target,$image,$SubTitle,$js=""){
		
		print('	
              <div class="col-md-3">
                    <a href="'.$link.'" target="'.$target.'" class="gal" '.$js.'><img src="'.$image.'" alt="" style="width: 120px;height: 120px;"><span></span></a>
                    <div class="col2"><span class="col3"><a href="'.$link.'" target="'.$target.'">'.utf8_encode($SubTitle).'</a></span></div>
                  </div>
		');
	}
        
        function IniciaMenu($Title){
		
		print('
		
			<div class="">
			  <h3 class="">'.$Title.'</h3>
			</div>  
			 <div class="tabs tb gallery">
             
            
					
		');
	}
        
        function FinMenu(){
            print('</div></div>');
	}
        
        /**
         * Crear un Div Colapsable
         * @param type $id
         * @param type $Titulo
         */
        public function DivColapsable($id,$Titulo,$style) {
            print('<div id='.$id.' class="box box-info box-solid" '.$style.'>
                <div class="box-header with-border">
                  <h3 id="TituloDivColapsableTabla" class="box-title">'.$Titulo.'</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    
                  </div>
                  <!-- /.box-tools -->
                </div>
                <div class="box-body" >');
        }
        /**
         * Cerrar un div colapsable
         */
        public function CDivColapsable() {
            print('</div>
                <!-- /.box-body -->
              </div>');
        }
        
        /**
         * Agrega los JS para exportar a excel desde javascript
         */
        public function AddJSExcel(){
            print('<script src="../../componentes/jsexcel/external/jszip.js"></script>');
            print('<script src="../../componentes/jsexcel/external/FileSaver.js"></script>');
            print('<script src="../../componentes/jsexcel/external/jszip.js"></script>');
            print('<script src="../../componentes/jsexcel/scripts/excel-gen.js"></script>');
        }
        
        
        public function TabInit() {
            print('<div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">');
        }
        
        public function TabLabel($id,$Title,$Ref,$Active=0,$js='') {
            $Class="";
            if($Active==1){
                $Class='class="active"';
            }
            print('<li '.$Class.'><a id='.$id.' href="#'.$Ref.'" data-toggle="tab" '.$js.'>'.$Title.'</a></li>');
        }
        
        
        public function TabInitEnd() {
            print('</ul>');
        }
        
        
        public function TabContentInit() {
            print('<div class="tab-content">');
        }
        
        public function TabContentEnd() {
            print('</div>');
        }
        
        public function TabPaneInit($Ref,$Active='') {
            if($Active==1){
                $Active='active';
            }
            print('<div class="tab-pane '.$Active.'" id="'.$Ref.'">');
        }
        
        public function TabPaneEnd() {
            print('</div>');
        }
        
        public function IconButton($Name,$id,$iconClass,$Titulo,$js,$spanActivo=0,$spanColor="red",$style='') {
            print('<a name='.$Name.' id="'.$id.'" '.$js.' class="btn btn-app" '.$style.' style="background-color:#d9e8ff;color:red;width:300px;heigth:300px">');
            
            
            if($spanActivo==1){
                $idSpan="sp_$id";
                print('<span id="'.$idSpan.'" class="badge bg-'.$spanColor.'">0</span>');
            }
            
            print('<i class="'.$iconClass.' "></i> '.$Titulo.' </a>');
        }
        
        public function CrearTitulo($Mensaje,$color='azul') {
            
            if($color=="azul"){
                $tipo="info";
            }
            if($color=="rojo"){
                $tipo="danger";
            }
            if($color=="naranja"){
                $tipo="warning";
            }
            if($color=="verde"){
                $tipo="success";
            }
            print('<div class="box box-'.$tipo.'">
            <div class="box-header with-border">
              <h3 class="box-title">'.$Mensaje.'</h3>             
            </div>
            
          </div>');
            //print('<div class="callout callout-info">'.$Mensaje.'</div>');
        }
        
        public function ImageOcultarMostrar($Nombre,$Leyenda,$idObjeto,$Ancho,$Alto,$Vector,$RutaImage='../../images/circle.png') {
            print("<strong>$Leyenda</strong><image name='$Nombre' id='$Nombre' src='$RutaImage' style='cursor: pointer;height:$Ancho"."px".";width:$Alto"."px"."' onclick=MuestraOcultaXID('$idObjeto');>");
        }

        function CrearSelect($nombre,$evento,$ancho=200){
		print('<select id="'.$nombre.'" class="form-control" required name="'.$nombre.'" style="width:'.$ancho.'px" onchange="'.$evento.'" >');
		
	}
        
        function CrearOptionSelect($value,$label,$selected){
		
		if($selected==1)
			print('<option value='.$value.' selected>'.$label.'</option>');
		else
			print('<option value='.$value.'>'.$label.'</option>');
		
	}
        
        function CerrarSelect(){
		print('</select>');
		
	}
	
        
	function CrearInputNumber($nombre,$type,$label,$value,$placeh,$color,$TxtEvento,$TxtFuncion,$Ancho,$Alto,$ReadOnly,$Required,$Min,$Max,$Step,$css=""){
		
		if($ReadOnly==1)
			$ReadOnly="readonly";
		else
			$ReadOnly="";
		
		if($Required==1)
			$Required="required";
		else
			$Required="";
		
			print('<strong style="color:'.$color.'">'.$label.'<input name="'.$nombre.'" class="form-control" value="'.$value.'" type="'.$type.'" id="'.$nombre.'" placeholder="'.$placeh.'" '.$TxtEvento.' = "'.$TxtFuncion.'" 
			'.$ReadOnly.' '.$Required.' min="'.$Min.'"   max="'.$Max.'" step="'.$Step.'" autocomplete="off" style="width: '.$Ancho.'px;height: '.$Alto.'px;'.$css.'"></strong>');
		
	}
	
        
	function CrearTextArea($nombre,$label,$value,$placeh,$color,$TxtEvento,$TxtFuncion,$Ancho,$Alto,$ReadOnly,$Required,$BorderWidth=1){
		
		if($ReadOnly==1)
			$ReadOnly="readonly";
		else
			$ReadOnly="";
		$Required="";
		if($Required==1)
			$Required="required";
                
                print("<strong style= 'color:$color'>$label<textarea name='$nombre' class='form-control' id='$nombre' placeholder='$placeh' $TxtEvento = '$TxtFuncion'" 
                ." $ReadOnly  autocomplete='off' style='width: ".$Ancho."px; height: ".$Alto."px;border-top-width:".$BorderWidth."px;border-left-width:".$BorderWidth."px;border-right-width:".$BorderWidth."px;border-bottom-width:".$BorderWidth."px;' $Required>".$value."</textarea></strong>");

			
		
	}
	
        function CrearBotonImagen($Titulo,$Nombre,$target,$RutaImage,$javascript,$Alto,$Ancho,$posicion,$margenes,$VectorBim){
             
          //print("<a href='$target' title='$Titulo'><image name='$Nombre' id='$Nombre' src='$RutaImage' $javascript style='display:scroll; position:".$posicion."; right:10px; height:".$Alto."px; width: ".$Ancho."px;'></a>");
          
          print('<a href="'.$target.'" role="button"  data-toggle="modal" title="'.$Titulo.'" >
			<image src='.$RutaImage.' name='.$Nombre.' id='.$Nombre.' src='.$RutaImage.' '.$javascript.' style="display:scroll; position:'.$posicion.'; '.$margenes.'; height:'.$Alto.'px; width: '.$Ancho.'px;"></a>');
	} 
        
        public function AddJSTextAreaEnriquecida(){
            print(' <link rel="stylesheet" href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
                    <script src="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>');
        }
        
        public function input_number_format($type,$id,$class,$name,$title,$value,$placeholder,$autocomplete,$vectorhtml,$Script,$styles='') {
            if($value==''){
                $value=0;
            }
            $this->input("hidden", $id, $class." input-number", $name, $title, $value, $placeholder, $autocomplete, $vectorhtml, "", $styles, "");
            $this->input("text", $id."_Format_Number", $class." input-number", $name, $title, number_format($value,2), $placeholder, $autocomplete, $vectorhtml, $Script, $styles, 'pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"');
        }
        
        function dialInput($id,$class,$dataMax,$value,$readOnly="",$Color="#00c0ef"){
            
            if($readOnly==1){
                $readOnly="data-readOnly=true";
            }
            print('<input type="text" id="'.$id.'" class="'.$class.'" data-max="'.$dataMax.'" value="'.$value.'" '.$readOnly.' data-thickness="0.2" data-anglearc="250" data-angleoffset="-125" data-width="150" data-height="150" data-fgcolor="'.$Color.'" style="width: 64px; height: 40px; border: 0px none; background: rgba(0, 0, 0, 0) none repeat scroll 0% 0%; font: bold 24px Arial; text-align: center; color: rgb(0, 192, 239); padding: 0px; -moz-appearance: none;">');
                
        }
        
        function frm_form($form_id,$form_title,$tab,$idEdit,$data_extra){
            
            $Columnas=$this->obCon->ShowColums($tab);
            $data_reg=$this->obCon->DevuelveValores($tab, "ID", $idEdit);
            //print_r($Columnas);
            //print('<form id="'.$form_id.'" method="post">');

            print('<div class="form-body">');
            print('<div class="form-heading">'.$form_title.'</div>');
            print('<div class="row">');
            foreach ($Columnas["Field"] as $key => $NombreCol) {

                if(isset($Columnas["Field"][$key])){

                    $Nombre=$Columnas["Field"][$key];
                    $Type=$Columnas["Type"][$key];
                    $Comment=$Columnas["Comment"][$key];
                    $Index=$Columnas["Key"][$key];
                    $Extra=$Columnas["Extra"][$key];
                    $TypeField=$Columnas["TypeField"][$key];
                    $visible=1;
                    $sql="SELECT ID FROM tablas_campos_control WHERE NombreTabla='$tab' AND Campo='$Nombre' AND Habilitado=0";
                    $DatosValidacion=$this->obCon->FetchAssoc($this->obCon->Query($sql));
                    if($DatosValidacion["ID"]>0){
                        continue;
                    }
                    if($Nombre=='Created' or $Nombre=='Updated' or $Nombre=='Sync'){
                        continue;
                    }
                    if($Index=='PRI' and $Extra=='auto_increment'){
                        continue;
                    }
                    

                    if($visible==1){
                        $valueField=$data_reg[$Nombre];
                        $disabled="";
                        if($Index=='PRI' and $valueField==''){
                            $valueField=$this->obCon->getUniqId();
                            $disabled="disabled";
                        }
                        print('<div class="col-md-4">');
                            print('<div class="form-group">
                                    <label class="col-form-label">'.$Nombre.'</label>
                                    <input '.$disabled.' id="'.$NombreCol.'" value="'.$valueField.'" type="'.$TypeField.'" class="form-control ts_form" placeholder="'.$Nombre.'">
                                    <span class="form-text">'.$Comment.'</span> 
                                </div>');
                        print('</div>');
                    }
                }

            }
            print('</div>');
            print('<div class="form-seperator-dashed"></div>');
            print('<div class="form-footer text-right">');
                //print('<button type="reset" class="btn btn-default btn-pill mr-2">Cancelar</button>');
                print('<button id="btn_'.$form_id.'" data-edit_id="'.$idEdit.'" class="btn btn-success btn-pill mr-2">Enviar</button>');
            print('</div>');
            print('</div>');
            //print('</form>');
            
        }
        
        //////////////////////////////////FIN
}
	
	

?>