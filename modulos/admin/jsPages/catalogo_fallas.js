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
    $('#btn_frm_catalogo_fallas').on('click',function () { 
        
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
        dibuja_listado_fallas(Page);
    }
    if(listado_id==2){
        dibuja_listado_causas(Page);
    }
    
    
}

/*
 * Funciones generales para crear formularios
 */

function frm_crear_editar_registro(edit_id=''){
    var idDiv="DivListado";
   
    var catalogo_id=document.getElementById('catalogo_id').value;
    urlQuery='Consultas/catalogo_fallas.draw.php';    
    var form_data = new FormData();
        form_data.append('Accion', 3);  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
        
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
                    GuardarFalla();
                }
                if(lista==2){
                    GuardarCausa();
                }
                                
                              
            } else {     
                swal("Cancelado", "Se ha cancelado el proceso :)", "error");   
            } 
        });
}

function GuardarFalla(){
    
    urlQuery='procesadores/catalogo_fallas.process.php';    
    
    var btnEnviar = "btn_frm_catalogo_fallas";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_catalogo_fallas").data("edit_id");
    
    var catalogo_id=document.getElementById('catalogo_id').value;
    
    var Falla=document.getElementById('Falla').value;
             
    var form_data = new FormData();
        form_data.append('Accion', '1');  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
        
        
        form_data.append('Falla', Falla);
                                       
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

function GuardarCausa(){
    
    urlQuery='procesadores/catalogo_fallas.process.php';    
    
    var btnEnviar = "btn_frm_catalogo_fallas";
    document.getElementById(btnEnviar).disabled=true;
    document.getElementById(btnEnviar).value="Enviando...";
    var edit_id=$("#btn_frm_catalogo_fallas").data("edit_id");
    
    var catalogo_id=document.getElementById('catalogo_id').value;
    
    var falla_id=document.getElementById('falla_id').value;
    var Causa=document.getElementById('Causa').value;
         
    var form_data = new FormData();
        form_data.append('Accion', '2');  
        form_data.append('edit_id', edit_id);
        form_data.append('catalogo_id', catalogo_id);
       
        
        form_data.append('falla_id', falla_id);
        form_data.append('Causa', Causa);
                               
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

function dibuja_listado_fallas(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/catalogo_fallas.draw.php';  
    
    
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 1);  
        form_data.append('Page', Page);
                
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


function dibuja_listado_causas(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/catalogo_fallas.draw.php';  
    
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var form_data = new FormData();
        form_data.append('Accion', 2);  
        form_data.append('Page', Page);
        
        
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

