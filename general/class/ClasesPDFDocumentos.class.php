<?php
/* 
 * Clase donde se realizaran la generacion de informes.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../modelo/php_tablas.php';
class Documento{
    /**
     * Constructor 
     * @param type $db
     */
    public $DataBase;
    public $obCon;
    
    function __construct($db){
        $this->DataBase=$db;
        $this->obCon=new conexion(1);
        
    }
    
    /**
     * Inicia la creacion de un pdf
     * @param type $TituloFormato
     * @param type $FontSize
     * @param type $VectorPDF
     * @param type $Margenes
     */
    public function PDF_Ini($TituloFormato,$FontSize,$VectorPDF,$Margenes=1,$Patch="../../") {
        
        //require_once('../../librerias/tcpdf/examples/config/tcpdf_config_alt.php');
        $tcpdf_include_dirs = array(realpath($Patch.'librerias/tcpdf/tcpdf.php'), '/usr/share/php/tcpdf/tcpdf.php', '/usr/share/tcpdf/tcpdf.php', '/usr/share/php-tcpdf/tcpdf.php', '/var/www/tcpdf/tcpdf.php', '/var/www/html/tcpdf/tcpdf.php', '/usr/local/apache2/htdocs/tcpdf/tcpdf.php');
        foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
                if (@file_exists($tcpdf_include_path)) {
                        require_once($tcpdf_include_path);
                        break;
                }
        }
        // create new PDF document
        $this->PDF = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'ISO 8859-1', false);
        // set document information
        $this->PDF->SetCreator(PDF_CREATOR);
        $this->PDF->SetAuthor('Techno Soluciones');
        $this->PDF->SetTitle($TituloFormato);
        $this->PDF->SetSubject($TituloFormato);
        $this->PDF->SetKeywords('Techno Soluciones, PDF, '.$TituloFormato.' , Software');
        // set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, 60, PDF_HEADER_TITLE.'', "");
        // set header and footer fonts
        $this->PDF->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->PDF->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $this->PDF->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        if($Margenes==1){
            $this->PDF->SetMargins(10, 10, PDF_MARGIN_RIGHT);
            $this->PDF->SetHeaderMargin(PDF_MARGIN_HEADER);
            $this->PDF->SetFooterMargin(10);
        }
        
        // set auto page breaks
        $this->PDF->SetAutoPageBreak(TRUE, 10);
        // set image scale factor
        $this->PDF->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
                require_once(dirname(__FILE__).'/lang/spa.php');
                $this->PDF->setLanguageArray($l);
        }
        
        // ---------------------------------------------------------
        // set font
        //$pdf->SetFont('helvetica', 'B', 6);
        // add a page
        $this->PDF->AddPage();
        $this->PDF->SetFont('helvetica', '', $FontSize);
        
    }
    /**
     * Encabezado del PDF
     * @param type $Fecha
     * @param type $idEmpresa
     * @param type $idFormatoCalidad
     * @param type $VectorEncabezado
     * @param type $NumeracionDocumento
     */
    public function PDF_Encabezado($Fecha,$idEmpresa,$idFormatoCalidad,$VectorEncabezado,$NumeracionDocumento="",$DatosEmpresa) {
        
        $DatosFormatoCalidad=$this->obCon->DevuelveValores("formatos_calidad", "ID", $idFormatoCalidad);
        
        $RutaLogo="../../images/header-logo.png";
///////////////////////////////////////////////////////
//////////////encabezado//////////////////
////////////////////////////////////////////////////////
//////
//////
$tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
    <tr border="1">
        <td rowspan="3" border="1" style="text-align: center;"><img src="$RutaLogo" style="width:110px;height:60px;"></td>
        
        <td rowspan="3" width="290px" style="text-align: center; vertical-align: center;"><h2><br>$DatosFormatoCalidad[Nombre]</h2></td>
        <td width="70px" style="text-align: center;">Versión<br></td>
        <td width="130px"> $DatosFormatoCalidad[Version]</td>
    </tr>
    <tr>
    	
    	<td style="text-align: center;" >Código<br></td>
        <td> $DatosFormatoCalidad[Codigo]</td>
        
    </tr>
    <tr>
       <td style="text-align: center;" >Fecha<br></td>
       <td style="font-size:6px;"> $DatosFormatoCalidad[Fecha]</td> 
    </tr>
</table>
EOD;
$this->PDF->writeHTML($tbl, true, false, false, false, '');
$this->PDF->SetFillColor(255, 255, 255);
$txt="<strong>".$DatosEmpresa["RazonSocial"]."<br>".$DatosEmpresa["NIT"]."</strong>";
$this->PDF->MultiCell(62, 5, $txt, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
$txt=$DatosEmpresa["Direccion"]."<br>".$DatosEmpresa["Telefono"]."<br>".$DatosEmpresa["Ciudad"];
$this->PDF->MultiCell(62, 5, $txt, 0, 'C', 1, 0, '', '', true,0, true, true, 10, 'M');
$Documento="<strong>$NumeracionDocumento</strong><br><h5>Impreso por TS6 Pro </h5><br>";
$this->PDF->MultiCell(62, 5, $Documento, 0, 'R', 1, 0, '', '', true,0, true ,true, 10, 'M');
$this->PDF->writeHTML("<br>", true, false, false, false, '');
//Close and output PDF document
    }
//Crear el documento PDF
    public function PDF_Write($html) {
        $this->PDF->writeHTML($html, true, false, false, false, '');
    } 
//Agregar pagina en PDF
    public function PDF_Add() {
        $this->PDF->AddPage();
    }     
//Crear el documento PDF
    public function PDF_Output($NombreArchivo) {
        $this->PDF->Output("$NombreArchivo".".pdf", 'I');
    } 
    
    public function orden_trabajo_pdf($empresa_id,$orden_trabajo_id) {
        
        $DatosEmpresa=$this->obCon->DevuelveValores("empresapro", "ID", $empresa_id);
        $db=$DatosEmpresa["db"];
        $DatosOrden=$this->obCon->DevuelveValores("$db.ordenes_trabajo", "ID", $orden_trabajo_id);
        $idFormato=3000;
        $DatosFormato=$this->obCon->DevuelveValores("formatos_calidad", "ID", $idFormato);
        $Documento=$DatosFormato["Nombre"]." $orden_trabajo_id";
        $this->PDF_Ini($Documento, 8, "");
        
        $this->PDF_Encabezado($DatosOrden["created"],1, $idFormato, "",$Documento,$DatosEmpresa);
        
        $html=$this->EncabezadoOrden($db,$DatosOrden);
        $this->PDF_Write("<br>".$html);
        
        $html=$this->CuerpoOrden($db,$DatosOrden);
        $this->PDF_Write("<br>".$html);
        
        $html=$this->FirmasOrdenTrabajo($db,$DatosOrden);
        $this->PDF_Write("<br>".$html);
        
        $this->PDF_Output("OT_$orden_trabajo_id");
         
    }
    
    public function FirmasOrdenTrabajo($db,$DatosOrden) {
        
        $sql="SELECT idUsuarios as ID, CONCAT(Nombre,' ',Apellido) as NombreUsuario,Identificacion FROM usuarios WHERE idUsuarios='".$DatosOrden["usuario_cierre_id"]."'";
        $DatosUsuario=$this->obCon->FetchAssoc($this->obCon->Query($sql));
        $sql="SELECT * FROM $db.catalogo_tecnicos WHERE ID='".$DatosOrden["tecnico_id"]."'";
        $DatosTecnico=$this->obCon->FetchAssoc($this->obCon->Query($sql));
        $tbl='<table cellspacing="0" cellpadding="2" border="1">';
            $tbl.='<tr>';
                $tbl.='<th>';
                    $tbl.='<strong>Técnico:</strong><br>';
                    if($DatosTecnico["ID"]>0){
                        $tbl.= utf8_encode($DatosTecnico["NombreTecnico"])."<br>";
                        $tbl.= ($DatosTecnico["Identificacion"]);
                    }else{
                        $tbl.='<br><br><br>';
                    }
                    
                $tbl.='</th>';
                $tbl.='<th>';
                    $tbl.='<strong>Usuario que cierra:</strong><br>';
                    if($DatosUsuario["ID"]>0){
                        $tbl.= utf8_encode($DatosUsuario["NombreUsuario"])."<br>";
                        $tbl.= ($DatosUsuario["Identificacion"]);
                    }else{
                        $tbl.='<br><br><br>';
                    }
                    
                $tbl.='</th>';
            $tbl.='</tr>';  
        $tbl.='</table>'; 
        return($tbl);
    }
    
    public function CuerpoOrden($db,$DatosOrden) {
        $tbl='<table cellspacing="0" cellpadding="2" border="1">';
            $tbl.='<tr>';
                $tbl.='<th>';
                    $tbl.='<strong>Tareas:</strong>';
                    
                    $sql="SELECT t2.NombreTarea FROM $db.ordenes_trabajo_tareas t1 
                            INNER JOIN $db.catalogo_tareas t2 ON t2.ID=t1.tarea_id 
                            WHERE orden_trabajo_id='".$DatosOrden["orden_trabajo_id"]."'
                             ";
                    $Consulta=$this->obCon->Query($sql);
                    $lista="";
                    while($DatosConsulta=$this->obCon->FetchAssoc($Consulta)){
                        $lista.='<br>';
                        $lista.='|___| ';
                        $lista.= utf8_encode($DatosConsulta["NombreTarea"]);
                        
                    }       
                    if($lista==''){
                        $lista.='<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                    }
                    $tbl.=$lista;
                $tbl.='</th>';
                $tbl.='<th>';
                    $tbl.='<strong>Verificaciones y/o Mediciones:</strong>';
                    $sql="SELECT t1.*,t2.Nombre,
                            (SELECT NombreSeccion FROM $db.catalogo_secciones t3 WHERE t3.ID=t2.ubicacion_id LIMIT 1) as Ubicacion 
                            FROM $db.ordenes_trabajo_maquinas_verificadas t1 
                            INNER JOIN $db.equipos_maquinas t2 ON t2.ID=t1.maquina_id 
                            WHERE orden_trabajo_id='".$DatosOrden["ID"]."'
                             ";
                    $Consulta=$this->obCon->Query($sql);
                    $lista="";
                    while($DatosConsulta=$this->obCon->FetchAssoc($Consulta)){
                        $lista.='<br>';
                        $lista.='- Horas: '.$DatosConsulta["horas_trabajo"].' - ';
                        $lista.='Kilometros: - '.$DatosConsulta["kilometros_trabajo"].' - ';
                        $lista.= utf8_encode($DatosConsulta["Nombre"]." ".$DatosConsulta["Ubicacion"]);
                        $lista.='<br>';
                    }       
                    if($lista==''){
                        $lista.='<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                    }
                    $tbl.=$lista;
                $tbl.='</th>';
            $tbl.='</tr>';  
            
            $tbl.='<tr>';
                $tbl.='<th>';
                    $dbPrincipal=DB;
                    $tbl.='<strong>Fallas Encontradas:</strong>';
                    
                    $sql="SELECT t1.*,
                    (SELECT Falla FROM $dbPrincipal.catalogo_fallas t2 WHERE t2.ID=t1.falla_id LIMIT 1) AS Falla,
                    (SELECT Causa FROM $dbPrincipal.catalogo_causas t2 WHERE t2.ID=t1.causa_falla_id LIMIT 1) AS Causa,
                    (SELECT Nombre FROM $db.equipos_componentes t2 WHERE t2.ID=t1.componente_id LIMIT 1) AS Nombre,
                    (SELECT Marca FROM $db.equipos_componentes t2 WHERE t2.ID=t1.componente_id LIMIT 1) AS Marca,
                    (SELECT NumeroSerie FROM $db.equipos_componentes t2 WHERE t2.ID=t1.componente_id LIMIT 1) AS NumeroSerie 
                    
                     FROM $db.ordenes_trabajo_fallas t1 
                    
                    WHERE t1.orden_trabajo_id='".$DatosOrden["ID"]."'  
                    
                    ";
                    $Consulta=$this->obCon->Query($sql);
                    $lista="";
                    while($DatosConsulta=$this->obCon->FetchAssoc($Consulta)){
                        $lista.='<br>';
                        $lista.='<strong>Componente: </strong>';
                        $lista.= utf8_encode($DatosConsulta["Nombre"]);
                        $lista.='<br>';
                        $lista.='<strong>Serie: </strong>';
                        $lista.= utf8_encode($DatosConsulta["NumeroSerie"]);
                        $lista.='<br>';
                        $lista.='<strong>Falla: </strong>';
                        $lista.= utf8_encode($DatosConsulta["Falla"]);
                        $lista.='<br>';
                        $lista.='<strong>Causa: </strong>';
                        $lista.= utf8_encode($DatosConsulta["Causa"]);
                        $lista.='<br>';
                    }       
                    if($lista==''){
                        $lista.='<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                    }
                    $tbl.=$lista;
                $tbl.='</th>';
                $tbl.='<th>';
                    $tbl.='<strong>Insumos :</strong>';
                    $sql="SELECT t1.*,t2.DescripcionPrimaria as Nombre 
                            FROM $db.ordenes_trabajo_insumos t1 
                            INNER JOIN $db.equipos_partes t2 ON t2.ID=t1.insumo_id 
                            WHERE t1.orden_trabajo_id='".$DatosOrden["ID"]."'
                             ";
                    $Consulta=$this->obCon->Query($sql);
                    $lista="";
                    while($DatosConsulta=$this->obCon->FetchAssoc($Consulta)){
                        $lista.='<br>';
                        $lista.= utf8_encode($DatosConsulta["Nombre"]);
                        $lista.='<br>';
                        $lista.='- Valor Unitario: '.number_format($DatosConsulta["valor_unitario"]).'<br>';
                        $lista.='- Cantidad: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$DatosConsulta["cantidad"].'<br>';
                        $lista.='- Total: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($DatosConsulta["total"]);
                        
                        $lista.='<br>';
                    }       
                    if($lista==''){
                        $lista.='<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                    }
                    $tbl.=$lista;
                $tbl.='</th>';
            $tbl.='</tr>'; 
            
            $tbl.='<tr>';
                $tbl.='<th colspan="2">';
                    $tbl.='<strong>Observaciones de ejecución:</strong>';
                    if($DatosOrden["observaciones_cierre"]<>''){
                        $tbl.="<br>";
                        $tbl.= utf8_encode($DatosOrden["observaciones_cierre"]);
                    }else{
                        $tbl.='<br><br><br><br><br><br>';
                    }
                $tbl.='</th>';
            $tbl.='</tr>';     
        $tbl.='</table>';
        return($tbl);
        
        
    }
    
    public function EncabezadoOrden($db,$DatosOrden) {
        $datosTipoOrden=$this->obCon->DevuelveValores("ordenes_trabajo_tipo_mantenimiento", "ID", $DatosOrden["tipo_mantenimiento"]);   
        
        $sql="SELECT CONCAT(Nombre,' ',Apellido) as NombreUsuario FROM usuarios WHERE idUsuarios='".$DatosOrden["usuario_creador_id"]."'";
        $DatosUsuario=$this->obCon->FetchAssoc($this->obCon->Query($sql));
        
        $sql="SELECT t1.*,(SELECT NombreSeccion FROM $db.catalogo_secciones t2 WHERE t2.ID=t1.ubicacion_id LIMIT 1) as Ubicacion FROM $db.equipos_maquinas t1 WHERE t1.ID='".$DatosOrden["maquina_id"]."'";
        $datosMaquina=$this->obCon->FetchAssoc($this->obCon->Query($sql));
        
        $sql="SELECT t1.* FROM $db.equipos_componentes t1 WHERE t1.ID='".$DatosOrden["componente_id"]."'";
        $datosComponente=$this->obCon->FetchAssoc($this->obCon->Query($sql));
        
        $tbl = '
        <table cellspacing="0" cellpadding="2" border="1">
            <tr>
                <td><strong>Fecha Programada:</strong></td>
                <td>'.utf8_encode($DatosOrden["fecha_programada"]).'</td>
                <td><strong>Maquina:</strong></td>
                <td>'.utf8_encode($datosMaquina["Nombre"]." ".$datosMaquina["Marca"]).'</td>
            </tr>
            <tr>
                <td><strong>Tipo de Mantenimiento:</strong></td>
                <td>'.utf8_encode($datosTipoOrden["tipo_mantenimiento"]).'</td>
                <td><strong>Ubicación:</strong></td>
                <td>'.utf8_encode($datosMaquina["Ubicacion"]).'</td>    

            </tr>
            
            <tr>
                <td ><strong>Creada por:</strong></td>                
                <td>'.utf8_encode($DatosUsuario["NombreUsuario"]).'</td>
                <td><strong>Componente:</strong></td>
                <td>'.utf8_encode($datosComponente["Nombre"]." ".$datosComponente["NumeroSerie"]).'</td>       
            </tr>
            
            <tr>
                <td><strong>Observaciones Iniciales:</strong></td>                
                <td >'.utf8_encode($DatosOrden["observaciones_orden"]).'</td>
                <td><strong>Horas de Duración o Parada:</strong></td>
                <td> </td> 
            </tr>
            
        </table>

        ';

        return($tbl);
    
    }
    
   
   //Fin Clases
}
    