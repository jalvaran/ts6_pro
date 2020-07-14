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
    /*
    $("#TxtBusquedas").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
            dibujeListadoSegunID(1);   
        }
    });
    */
    
function add_events_frms(){
    $('#btn_frm_equipos').on('click',function () { 
        
        ConfirmarCrearEditar();
        
        
    });
}


/**
 * Funciones de proposito general
 * @param {type} idElemento
 * @returns {undefined}
 */

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
    var listado_id = document.getElementById('tipo_equipo').value;
    if(listado_id==1){
        dibuja_listado_maquinas(Page);
    }
    if(listado_id==2){
        dibuja_listado_componentes(Page);
    }
    if(listado_id==3){
        dibuja_listado_partes(Page);
    }
    if(listado_id==4){
        dibuja_listado_representantes(Page);
    }
    
}

/*
 * Funciones generales para crear formularios
 */

function frm_crear_editar_registro(edit_id=''){
    var idDiv="DivListado";
    var empresa_id=document.getElementById('empresa_id').value;
    var tipo_equipo=document.getElementById('tipo_equipo').value;
    urlQuery='Consultas/admin_equipos.draw.php';    
    var form_data = new FormData();
        form_data.append('Accion', 4);  
        form_data.append('edit_id', edit_id);
        form_data.append('tipo_equipo', tipo_equipo);
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


function frm_crear_componente_asociado_maquina(maquina_id){
    var idDiv="div_modal_view";
    $('#btnModalView').hide();
    var empresa_id=document.getElementById('empresa_id').value;
    var tipo_equipo=2;
    urlQuery='Consultas/admin_equipos.draw.php';    
    var form_data = new FormData();
        form_data.append('Accion', 4);  
        form_data.append('edit_id', '');
        form_data.append('tipo_equipo', tipo_equipo);
        form_data.append('empresa_id', empresa_id);
        form_data.append('maquina_id', maquina_id);
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
                
                var lista = document.getElementById("tipo_equipo").value;
                if($("#componente_asociado").length > 0){
                    GuardarEditarRegistroComponente();
                    return;
                }
                if(lista==1){
                    GuardarEditarRegistroMaquina();
                }
                if(lista==2){
                    GuardarEditarRegistroComponente();
                }
                if(lista==3){
                    GuardarEditarRegistroParte();
                }
                if(lista==4){
                    GuardarEditarRegistroRepresentantes();
                }
                
                              
            } else {     
                swal("Cancelado", "Se ha cancelado el proceso :)", "error");   
            } 
        });
}

function GuardarEditarRegistroMaquina(){
    
    urlQuery='procesadores/admin_equipos.process.php';    
    
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

function GuardarEditarRegistroComponente(){
    
    urlQuery='procesadores/admin_equipos.process.php';    
    
    var btnEnviar = "btn_frm_equipos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_equipos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var tipo_equipo=document.getElementById('tipo_equipo').value;
    
    var ID=document.getElementById('ID').value;
    var maquina_id=document.getElementById('maquina_id').value;
    var Nombre=document.getElementById('Nombre').value;
    var Marca=document.getElementById('Marca').value;
    var Modelo=document.getElementById('Modelo').value;
    var NumeroSerie=document.getElementById('NumeroSerie').value;
    
    var Especificaciones=document.getElementById('Especificaciones').value;
     
    var form_data = new FormData();
        form_data.append('Accion', '2');  
        form_data.append('edit_id', edit_id);
        form_data.append('ID', ID);
        form_data.append('maquina_id', maquina_id);
        form_data.append('Nombre', Nombre);
        form_data.append('Marca', Marca);
        form_data.append('Modelo', Modelo);
        form_data.append('NumeroSerie', NumeroSerie);
        
        form_data.append('Especificaciones', Especificaciones);
        form_data.append('empresa_id', empresa_id);
        form_data.append('tipo_equipo', tipo_equipo);
                       
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
                if($("#componente_asociado").length > 0){                    
                    ver_componentes_maquina(maquina_id);
                }else{
                    dibujeListadoSegunID();
                }
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


function GuardarEditarRegistroParte(){
    
    urlQuery='procesadores/admin_equipos.process.php';    
    
    var btnEnviar = "btn_frm_equipos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_equipos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var tipo_equipo=document.getElementById('tipo_equipo').value;
    
    var ID=document.getElementById('ID').value;
    var Codigo=document.getElementById('Codigo').value;
    var DescripcionPrimaria=document.getElementById('DescripcionPrimaria').value;
    var DescripcionSecundaria=document.getElementById('DescripcionSecundaria').value;
    var Cantidad=document.getElementById('Cantidad').value;
    var Costo=document.getElementById('Costo').value;
    var Fecha=document.getElementById('Fecha').value;
         
    var form_data = new FormData();
        form_data.append('Accion', '3');  
        form_data.append('edit_id', edit_id);
        form_data.append('ID', ID);
        form_data.append('Codigo', Codigo);
        form_data.append('DescripcionPrimaria', DescripcionPrimaria);
        form_data.append('DescripcionSecundaria', DescripcionSecundaria);
        form_data.append('Cantidad', Cantidad);
        form_data.append('Costo', Costo);
        form_data.append('Fecha', Fecha);
        
        form_data.append('empresa_id', empresa_id);
        form_data.append('tipo_equipo', tipo_equipo);
                       
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


function desligueComponente(componente_id,maquina_id){
    
    urlQuery='procesadores/admin_equipos.process.php';    
    
    var empresa_id=document.getElementById('empresa_id').value;
    
    var form_data = new FormData();
        form_data.append('Accion', '5');  
        form_data.append('componente_id', componente_id);
        form_data.append('maquina_id', maquina_id);
        form_data.append('empresa_id', empresa_id);
                               
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
                
                ver_componentes_maquina(maquina_id);
                
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

//Dibujantes

function GuardarEditarRegistroRepresentantes(){
    
    urlQuery='procesadores/admin_equipos.process.php';    
    
    var btnEnviar = "btn_frm_equipos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_equipos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var tipo_equipo=document.getElementById('tipo_equipo').value;
    
    var NombreRepresentante=document.getElementById('NombreRepresentante').value;
    var Contacto=document.getElementById('Contacto').value;
    var Telefono=document.getElementById('Telefono').value;
    var Fax=document.getElementById('Fax').value;
    var Email=document.getElementById('Email').value;
    var Direccion=document.getElementById('Direccion').value;
    var Ciudad=document.getElementById('Ciudad').value;
    var Celular=document.getElementById('Celular').value;
    
    
    var form_data = new FormData();
        form_data.append('Accion', '4');  
        form_data.append('edit_id', edit_id);
        form_data.append('empresa_id', empresa_id);
        form_data.append('tipo_equipo', tipo_equipo);
        form_data.append('NombreRepresentante', NombreRepresentante);
        form_data.append('Contacto', Contacto);
        form_data.append('Telefono', Telefono);
        form_data.append('Fax', Fax);
        form_data.append('Email', Email);
        form_data.append('Direccion', Direccion);
        form_data.append('Ciudad', Ciudad);
        form_data.append('Celular', Celular);
                              
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

function dibuja_listado_maquinas(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_equipos.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;
    //var Busquedas=document.getElementById('TxtBusquedas').value;
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 1);  
        form_data.append('Page', Page);
       //form_data.append('Busquedas', Busquedas); 
        form_data.append('empresa_id', empresa_id);  
        form_data.append('BusquedasGenerales', BusquedasGenerales);   
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
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            //add_events_frms();
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function dibuja_listado_componentes(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_equipos.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;
    //var Busquedas=document.getElementById('TxtBusquedas').value;
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 2);  
        form_data.append('Page', Page);
        //form_data.append('Busquedas', Busquedas); 
        form_data.append('empresa_id', empresa_id);  
        form_data.append('BusquedasGenerales', BusquedasGenerales);   
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
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            //add_events_frms();
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function dibuja_listado_partes(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_equipos.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;
    //var Busquedas=document.getElementById('TxtBusquedas').value;
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 3);  
        form_data.append('Page', Page);
        //form_data.append('Busquedas', Busquedas); 
        form_data.append('empresa_id', empresa_id);  
        form_data.append('BusquedasGenerales', BusquedasGenerales);   
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
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            //add_events_frms();
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function dibuja_listado_representantes(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_equipos.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;
    //var Busquedas=document.getElementById('TxtBusquedas').value;
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 5);  
        form_data.append('Page', Page);
        //form_data.append('Busquedas', Busquedas); 
        form_data.append('empresa_id', empresa_id);  
        form_data.append('BusquedasGenerales', BusquedasGenerales);   
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
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            //add_events_frms();
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function ver_componentes_maquina(maquina_id){
    var idDiv="div_modal_view";
    openModal('modal_view');
    urlQuery='Consultas/admin_equipos.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;
    //var Busquedas=document.getElementById('TxtBusquedas').value;
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 6);  
        form_data.append('maquina_id', maquina_id);
        //form_data.append('Busquedas', Busquedas); 
        form_data.append('empresa_id', empresa_id);  
        form_data.append('BusquedasGenerales', BusquedasGenerales);   
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
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            //add_events_frms();
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


dibujeListadoSegunID();

