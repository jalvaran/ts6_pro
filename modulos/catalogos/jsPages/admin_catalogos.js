/*
 * javascript para controlar los eventos de la administracion de los catalogos
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

$('#catalogo_id').on('change',function () {        

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
    $('#btn_frm_catalogos').on('click',function () { 
        
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
    var listado_id = document.getElementById('catalogo_id').value;
    if(listado_id==1){
        dibuja_listado_procesos(Page);
    }
    if(listado_id==2){
        dibuja_listado_secciones(Page);
    }
    if(listado_id==3){
        dibuja_listado_tareas(Page);
    }
    if(listado_id==4){
        dibuja_listado_tecnicos(Page);
    }
    if(listado_id==5){
        dibuja_listado_unidades_negocio(Page);
    }
    if(listado_id==6){
        dibuja_listado_tareas_tipo(Page);
    }
    if(listado_id==7){
        dibuja_listado_rutinas(Page);
    }
    
}

/*
 * Funciones generales para crear formularios
 */

function frm_crear_editar_registro(edit_id=''){
    var idDiv="DivListado";
    var empresa_id=document.getElementById('empresa_id').value;
    var catalogo_id=document.getElementById('catalogo_id').value;
    urlQuery='Consultas/admin_catalogos.draw.php';    
    var form_data = new FormData();
        form_data.append('Accion', 5);  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
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
                
                var lista = document.getElementById("catalogo_id").value;
                if(lista==1){
                    GuardarEditarRegistroProcesos();
                }
                if(lista==2){
                    GuardarEditarRegistroSecciones();
                }
                if(lista==3){
                    GuardarEditarRegistroTareas();
                }
                if(lista==4){
                    GuardarEditarRegistroTecnicos();
                }
                if(lista==5){
                    GuardarEditarRegistroUnidadNegocio();
                }
                if(lista==6){
                    GuardarEditarRegistroTareasTipo();
                }
                if(lista==7){
                    GuardarEditarRegistroRutinas();
                }
                
                              
            } else {     
                swal("Cancelado", "Se ha cancelado el proceso :)", "error");   
            } 
        });
}

function GuardarEditarRegistroProcesos(){
    
    urlQuery='procesadores/admin_catalogos.process.php';    
    
    var btnEnviar = "btn_frm_catalogos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_catalogos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var catalogo_id=document.getElementById('catalogo_id').value;
    
    var CodigoProceso=document.getElementById('CodigoProceso').value;
    var Nombre=document.getElementById('Nombre').value;
    var unidadNegocio_id=document.getElementById('unidadNegocio_id').value;
         
    var form_data = new FormData();
        form_data.append('Accion', '1');  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
        form_data.append('empresa_id', empresa_id);
        
        form_data.append('CodigoProceso', CodigoProceso);
        form_data.append('Nombre', Nombre);
        form_data.append('unidadNegocio_id', unidadNegocio_id);
                               
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

function GuardarEditarRegistroSecciones(){
    
    urlQuery='procesadores/admin_catalogos.process.php';    
    
    var btnEnviar = "btn_frm_catalogos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_catalogos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var catalogo_id=document.getElementById('catalogo_id').value;
    
    var Codigo=document.getElementById('Codigo').value;
    var NombreSeccion=document.getElementById('NombreSeccion').value;
         
    var form_data = new FormData();
        form_data.append('Accion', '2');  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
        form_data.append('empresa_id', empresa_id);
        
        form_data.append('Codigo', Codigo);
        form_data.append('NombreSeccion', NombreSeccion);
                               
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


function GuardarEditarRegistroTareas(){
    
    urlQuery='procesadores/admin_catalogos.process.php';    
    
    var btnEnviar = "btn_frm_catalogos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_catalogos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var catalogo_id=document.getElementById('catalogo_id').value;
    
    var CodigoTarea=document.getElementById('CodigoTarea').value;
    var NombreTarea=document.getElementById('NombreTarea').value;
    var TipoTarea=document.getElementById('TipoTarea').value;
    //var Contador=document.getElementById('Contador').value;   
    
    var form_data = new FormData();
        form_data.append('Accion', '3');  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
        form_data.append('empresa_id', empresa_id);
        
        form_data.append('CodigoTarea', CodigoTarea);
        form_data.append('NombreTarea', NombreTarea);
        form_data.append('TipoTarea', TipoTarea);
        //form_data.append('Contador', Contador);
                               
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


function GuardarEditarRegistroTecnicos(){
    
    urlQuery='procesadores/admin_catalogos.process.php';    
    
    var btnEnviar = "btn_frm_catalogos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_catalogos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var catalogo_id=document.getElementById('catalogo_id').value;
    
    var Identificacion=document.getElementById('Identificacion').value;
    var NombreTecnico=document.getElementById('NombreTecnico').value;
    var TipoTecnico=document.getElementById('TipoTecnico').value;
    var Telefono=document.getElementById('Telefono').value;
    var Email=document.getElementById('Email').value;
    var Direccion=document.getElementById('Direccion').value;
    var Ciudad=document.getElementById('Ciudad').value;
    var Celular=document.getElementById('Celular').value;
        
    var form_data = new FormData();
        form_data.append('Accion', '4');  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
        form_data.append('empresa_id', empresa_id);
        
        form_data.append('Identificacion', Identificacion);
        form_data.append('NombreTecnico', NombreTecnico);
        form_data.append('TipoTecnico', TipoTecnico);
        form_data.append('Telefono', Telefono);
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

function GuardarEditarRegistroUnidadNegocio(){
    
    urlQuery='procesadores/admin_catalogos.process.php';    
    
    var btnEnviar = "btn_frm_catalogos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_catalogos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var catalogo_id=document.getElementById('catalogo_id').value;
    
    var UnidadNegocio=document.getElementById('UnidadNegocio').value;
    
         
    var form_data = new FormData();
        form_data.append('Accion', '5');  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
        form_data.append('empresa_id', empresa_id);
        
        form_data.append('UnidadNegocio', UnidadNegocio);
        
                               
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

function GuardarEditarRegistroTareasTipo(){
    
    urlQuery='procesadores/admin_catalogos.process.php';    
    
    var btnEnviar = "btn_frm_catalogos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_catalogos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var catalogo_id=document.getElementById('catalogo_id').value;
    
    var tipo_tarea=document.getElementById('tipo_tarea').value;
    
         
    var form_data = new FormData();
        form_data.append('Accion', '6');  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
        form_data.append('empresa_id', empresa_id);
        
        form_data.append('tipo_tarea', tipo_tarea);
        
                               
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


function GuardarEditarRegistroRutinas(){
    
    urlQuery='procesadores/admin_catalogos.process.php';    
    
    var btnEnviar = "btn_frm_catalogos";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_catalogos").data("edit_id");
    var empresa_id=document.getElementById('empresa_id').value;
    var catalogo_id=document.getElementById('catalogo_id').value;
    
    var NombreRuta=document.getElementById('NombreRuta').value;
    var Descripcion=document.getElementById('Descripcion').value;
         
    var form_data = new FormData();
        form_data.append('Accion', '7');  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
        form_data.append('empresa_id', empresa_id);
        
        form_data.append('NombreRuta', NombreRuta);
        form_data.append('Descripcion', Descripcion);
                               
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

function dibuja_listado_procesos(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_catalogos.draw.php';  
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


function dibuja_listado_secciones(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_catalogos.draw.php';  
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


function dibuja_listado_tareas(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_catalogos.draw.php';  
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

function dibuja_listado_tecnicos(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_catalogos.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;
    //var Busquedas=document.getElementById('TxtBusquedas').value;
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 4);  
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

function dibuja_listado_unidades_negocio(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_catalogos.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;
    //var Busquedas=document.getElementById('TxtBusquedas').value;
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 6);  
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

function dibuja_listado_tareas_tipo(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_catalogos.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;
    //var Busquedas=document.getElementById('TxtBusquedas').value;
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 7);  
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


function dibuja_listado_rutinas(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/admin_catalogos.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;
    //var Busquedas=document.getElementById('TxtBusquedas').value;
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 8);  
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


dibujeListadoSegunID();

