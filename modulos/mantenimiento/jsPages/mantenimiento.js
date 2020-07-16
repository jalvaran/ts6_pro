/*
 * javascript para controlar los eventos de la administracion de los equipos
 */

/*
 * Agregamos eventos a los botones o formularios
 */
/**
 * asignamos el evento click al boton para crear una nueva empresa
 * @type type
 */

$('#btnFrmNuevoRegistro').on('click',function () {        
    
    frm_crear_editar_registro();

});

$('#btnActualizarListado').on('click',function () {        

    dibujeListadoSegunID(1);

});

$('#empresa_id').on('change',function () {        

    dibujeListadoSegunID(1);

});

$('#tipo_equipo').on('change',function () {        

    dibujeListadoSegunID(1);

});

$("#txtBusquedasGenerales").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
            dibujeListadoSegunID(1);   
        }
    });
        
function add_events_frms(){
    
    $('#btn_form_orden_trabajo').on('click',function () {
        ConfirmarCrearEditar();
    });
    
    $('#orden_tabajo_tipo_id').on('change',function () {
        OcultarMostrarOpcionesPreventivo();        
    });
    
    $('#btn_agregar_tarea').on('click',function () {
        AgregarTareaOT();
    });
    
    $('#maquina_id').on('change',function () {
        obtenerComponentesMaquinas($(this).val());
    });
    $('#componente_id').on('change',function () {
        obtenerFrecuenciasComponente($(this).val());
    });
    $('#fecha_ultimo_mantenimiento').on('change',function () {
        CalculeFechaProgramada();
    });
    $('#frecuencia_dias').on('change',function () {
        CalculeFechaProgramada();
    });
}


/**
 * Funciones de proposito general
 * @param {type} idElemento
 * @returns {undefined}
 */

function CalculeFechaProgramada(){
    var ultimoMantenimiento=document.getElementById('fecha_ultimo_mantenimiento').value;    
    var frecuencia_dias=document.getElementById('frecuencia_dias').value;
    
    urlQuery='procesadores/mantenimiento.process.php';  
    
    var form_data = new FormData();
        form_data.append('Accion', '10');        
        form_data.append('ultimoMantenimiento', ultimoMantenimiento);
        form_data.append('frecuencia_dias', frecuencia_dias);
                     
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                toastr.success(respuestas[1]);
                document.getElementById('fecha_programada').value=respuestas[2];
                
            }else if(respuestas[0]=="E1"){  
                //toastr.error(respuestas[1],'',2000);
               // MarqueErrorElemento(respuestas[2]);
            }else{
                toastr.error(data,2000);          
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function OcultarMostrarOpcionesPreventivo(){
    
    var tipo_mantenimiento=document.getElementById('orden_tabajo_tipo_id').value;  
        
    if(tipo_mantenimiento=='1' || tipo_mantenimiento==''){
        
        document.getElementById('divOpcionesPreventivo').style.display="none";
    }

    if(tipo_mantenimiento == '2'){
        
        document.getElementById('divOpcionesPreventivo').style.display="block";
    }
}
        
function AgregarTareaOT(){
    urlQuery='procesadores/mantenimiento.process.php';    
    
    var orden_trabajo_id=document.getElementById('orden_trabajo_id').value;  
    var cmb_tarea_mantenimiento=document.getElementById('cmb_tarea_mantenimiento').value;  
    var empresa_id=document.getElementById('empresa_id').value;
        
    var form_data = new FormData();
        form_data.append('Accion', '8'); 
        
        form_data.append('empresa_id', empresa_id);
        form_data.append('orden_trabajo_id', orden_trabajo_id);
        form_data.append('cmb_tarea_mantenimiento', cmb_tarea_mantenimiento);
             
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                toastr.success(respuestas[1]);
                listarTareasOT();
                
            }else if(respuestas[0]=="E1"){  
                toastr.error(respuestas[1],'',2000);
                MarqueErrorElemento(respuestas[2]);
            }else{
                toastr.error(data,2000);          
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function eliminarItem(tabla_id,item_id){
    urlQuery='procesadores/mantenimiento.process.php';    
    
    
    var empresa_id=document.getElementById('empresa_id').value;
        
    var form_data = new FormData();
        form_data.append('Accion', '9'); 
        
        form_data.append('empresa_id', empresa_id);
        form_data.append('tabla_id', tabla_id);
        form_data.append('item_id', item_id);
             
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                toastr.error(respuestas[1]);
                listarTareasOT();
                
            }else if(respuestas[0]=="E1"){  
                toastr.error(respuestas[1],'',2000);
                MarqueErrorElemento(respuestas[2]);
            }else{
                toastr.error(data,2000);          
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function obtenerFrecuenciasComponente(componente_id=''){
    urlQuery='procesadores/mantenimiento.process.php';    
    if(componente_id==''){
        var componente_id=document.getElementById('componente_id').value;
    }
    
    var empresa_id=document.getElementById('empresa_id').value;
        
    var form_data = new FormData();
        form_data.append('Accion', '7'); 
        
        form_data.append('empresa_id', empresa_id);
        form_data.append('componente_id', componente_id);
                               
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                var jsonComponente=JSON.parse(respuestas[1]);
                console.log(jsonComponente)
                if(jsonComponente.fecha_ultimo_mantenimiento == '' || jsonComponente.fecha_ultimo_mantenimiento == null ){
                    $("#fecha_ultimo_mantenimiento").prop('disabled', false);
                    document.getElementById('fecha_ultimo_mantenimiento').value='';                    
                }else{
                    $("#fecha_ultimo_mantenimiento").prop('disabled', true);
                    
                }
                document.getElementById('fecha_ultimo_mantenimiento').value=jsonComponente.fecha_ultimo_mantenimiento;
                document.getElementById('frecuencia_dias').value=jsonComponente.frecuencia_mtto_dias;
                document.getElementById('frecuencia_horas').value=jsonComponente.frecuencia_mtto_horas;
                document.getElementById('frecuencia_kilometros').value=jsonComponente.frecuencia_mtto_kilometros;
                CalculeFechaProgramada();
            }else if(respuestas[0]=="E1"){  
                toastr.error(respuestas[1],'',2000);
                MarqueErrorElemento(respuestas[2]);
            }else{
                toastr.error(data,2000);          
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(btnEnviar).disabled=false;
            document.getElementById(btnEnviar).value="Enviar";
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function obtenerComponentesMaquinas(maquina_id=''){
    urlQuery='procesadores/mantenimiento.process.php';    
    if(maquina_id==''){
        var maquina_id=document.getElementById('maquina_id').value;
    }
    
    var empresa_id=document.getElementById('empresa_id').value;
        
    var form_data = new FormData();
        form_data.append('Accion', '6'); 
        
        form_data.append('empresa_id', empresa_id);
        form_data.append('maquina_id', maquina_id);
                               
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                var jsonComponentes=JSON.parse(respuestas[1]);
                
                var $el = $("#componente_id");
                    $el.empty(); // remove old options
                    $.each(jsonComponentes, function(key,value) {
                         $el.append($("<option></option>")
                            .attr("value", value.ID).text(value.Nombre+" || Marca: "+value.Marca+" || Modelo: "+value.Modelo+" || Serie: "+value.NumeroSerie));
                    });
                obtenerFrecuenciasComponente();
                
            }else if(respuestas[0]=="E1"){  
                toastr.error(respuestas[1],'',2000);
                MarqueErrorElemento(respuestas[2]);
            }else{
                toastr.error(data,2000);          
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(btnEnviar).disabled=false;
            document.getElementById(btnEnviar).value="Enviar";
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function MarqueErrorElemento(idElemento){
    
    if(idElemento==undefined){
       return; 
    }
    document.getElementById(idElemento).style.backgroundColor="pink";
    document.getElementById(idElemento).focus();
}


function CambiePagina(Funcion,Page=""){
    
    if(Page==""){
        if(document.getElementById('CmbPage')){
            Page = document.getElementById('CmbPage').value;
        }else{
            Page=1;
        }
    }
    if(Funcion==1){
        dibujeListadoSegunID(Page);
    }
    
    
}


function dibujeListadoSegunID(Page=1){
    var listado_id = document.getElementById('listado_id').value;
    if(listado_id==1){
        dibuja_ordenes_trabajo(Page);
    }
    
    
}

/*
 * Funciones generales para crear formularios
 */

function frm_crear_editar_registro(edit_id=''){
    var idDiv="DivListado";
    var empresa_id=document.getElementById('empresa_id').value;
    var listado_id=document.getElementById('listado_id').value;
    urlQuery='Consultas/mantenimiento.draw.php';    
    var form_data = new FormData();
        form_data.append('Accion', 2);  
        form_data.append('edit_id', edit_id);
        form_data.append('listado_id', listado_id);
        form_data.append('empresa_id', empresa_id);
       $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        beforeSend: function() { //lo que hará la pagina antes de ejecutar el proceso
            document.getElementById(idDiv).innerHTML='<div id="GifProcess">Procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
            add_events_frms();
        },
        complete: function(){
           
        },
        success: function(data){    
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            add_events_frms();
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}

/*
 * Funciones generales para guargar o editar registros
 */

function ConfirmarCrearEditar(){
    swal({   
            title: "Seguro que desea guardar?",   
            //text: "You will not be able to recover this imaginary file!",   
            type: "warning",   
            showCancelButton: true,  
            
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Claro que Siii!",   
            cancelButtonText: "Espera voy a revisar algo!",   
            closeOnConfirm: true,   
            closeOnCancel: true 
        }, function(isConfirm){   
            if (isConfirm) {   
                
                var lista = document.getElementById("listado_id").value;
                
                if(lista==1){
                    GuardarEditarOrdenTrabajo();
                }
                               
                              
            } else {     
                swal("Cancelado", "Se ha cancelado el proceso :)", "error");   
            } 
        });
}

function GuardarEditarOrdenTrabajo(){
    
    urlQuery='procesadores/mantenimiento.process.php';    
    
    var btnEnviar = "btn_frm_equipos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_equipos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var tipo_equipo=document.getElementById('tipo_equipo').value;
    
    var ID=document.getElementById('ID').value;
    var Codigo=document.getElementById('Codigo').value;
    var Nombre=document.getElementById('Nombre').value;
    var Marca=document.getElementById('Marca').value;
    var Modelo=document.getElementById('Modelo').value;
    var NumeroSerie=document.getElementById('NumeroSerie').value;
    var FechaFabricacion=document.getElementById('FechaFabricacion').value;
    var FechaInstalacion=document.getElementById('FechaInstalacion').value;
    var Especificaciones=document.getElementById('Especificaciones').value;
    var proceso_id=document.getElementById('proceso_id').value;
    var ubicacion_id=document.getElementById('ubicacion_id').value;
    var representante_id=document.getElementById('representante_id').value;
    var ValorAdquisicion=document.getElementById('ValorAdquisicion').value;
    var FechaAdquisicion=document.getElementById('FechaAdquisicion').value;
    
    var form_data = new FormData();
        form_data.append('Accion', '1');  
        form_data.append('edit_id', edit_id);
        form_data.append('empresa_id', empresa_id);
        form_data.append('tipo_equipo', tipo_equipo);
        form_data.append('ID', ID);
        form_data.append('Codigo', Codigo);
        form_data.append('Nombre', Nombre);
        form_data.append('Marca', Marca);
        form_data.append('Modelo', Modelo);
        form_data.append('NumeroSerie', NumeroSerie);
        form_data.append('FechaFabricacion', FechaFabricacion);
        form_data.append('FechaInstalacion', FechaInstalacion);
        form_data.append('Especificaciones', Especificaciones);
        form_data.append('proceso_id', proceso_id);
        form_data.append('ubicacion_id', ubicacion_id);
        form_data.append('representante_id', representante_id);
        form_data.append('ValorAdquisicion', ValorAdquisicion);
        form_data.append('FechaAdquisicion', FechaAdquisicion);
                       
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            document.getElementById(btnEnviar).disabled=false;
            document.getElementById(btnEnviar).value="Enviar";
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                toastr.success(respuestas[1]);
                
                dibujeListadoSegunID();
                
            }else if(respuestas[0]=="E1"){  
                toastr.error(respuestas[1],'',2000);
                MarqueErrorElemento(respuestas[2]);
            }else{
                toastr.error(data,2000);          
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(btnEnviar).disabled=false;
            document.getElementById(btnEnviar).value="Enviar";
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

/*
 * Funciones generales para listar tablas
 */

function dibuja_ordenes_trabajo(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/mantenimiento.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;    
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var fecha_inicial=document.getElementById('fecha_inicial').value;    
    var fecha_final=document.getElementById('fecha_final').value;    
    var cmb_tipo_mantenimiento=document.getElementById('cmb_tipo_mantenimiento').value;    
    var cmb_estado=document.getElementById('cmb_estado').value;    
    var form_data = new FormData();
        form_data.append('Accion', 1);  
        form_data.append('Page', Page);       
        form_data.append('empresa_id', empresa_id);  
        form_data.append('BusquedasGenerales', BusquedasGenerales);
        form_data.append('fecha_inicial', fecha_inicial);  
        form_data.append('fecha_final', fecha_final);  
        form_data.append('cmb_tipo_mantenimiento', cmb_tipo_mantenimiento);  
        form_data.append('cmb_estado', cmb_estado);  
       $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        beforeSend: function() { //lo que hará la pagina antes de ejecutar el proceso
            document.getElementById(idDiv).innerHTML='<div id="GifProcess">Procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        },
        complete: function(){
           
        },
        success: function(data){    
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function listarTareasOT(orden_trabajo_id=''){
    var idDiv="divTareasOrdenTrabajo";
    $("#btn_agregar_tarea").prop('disabled', true);
    if(orden_trabajo_id===''){
        var orden_trabajo_id=document.getElementById('orden_trabajo_id').value;
    }
    var empresa_id=document.getElementById('empresa_id').value;
    
    urlQuery='Consultas/mantenimiento.draw.php';    
    var form_data = new FormData();
        form_data.append('Accion', 3);        
        form_data.append('orden_trabajo_id', orden_trabajo_id);
        form_data.append('empresa_id', empresa_id);
       $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        beforeSend: function() { //lo que hará la pagina antes de ejecutar el proceso
            //document.getElementById(idDiv).innerHTML='<div id="GifProcess">Procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
            
        },
        complete: function(){
           
        },
        success: function(data){    
            $("#btn_agregar_tarea").prop('disabled', false);
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            $("#btn_agregar_tarea").prop('disabled', false);
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


dibujeListadoSegunID();

