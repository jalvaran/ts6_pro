/*
 * javascript para controlar los eventos de las hojas de vida de las maquinas
 */

/*
 * Agregamos eventos a los botones o formularios
 */


$('#btnActualizarListado').on('click',function () {        

    dibujeListadoSegunID(1);

});

$('#empresa_id').on('change',function () {        

    dibujeListadoSegunID(1);

});

$('#orden_tipo').on('change',function () {        

    dibujeListadoSegunID(1);

});

$("#txtBusquedasGenerales").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
            dibujeListadoSegunID(1);   
        }
    });
        

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
        dibuja_hoja_vida_maquinas(Page);
    }
    
    
}

/*
 * Funciones generales para listar tablas
 */

function dibuja_hoja_vida_maquinas(Page=1){
    var idDiv="DivListado";
    urlQuery='Consultas/hojas_vida.draw.php';  
    var empresa_id=document.getElementById('empresa_id').value;    
    var BusquedasGenerales=document.getElementById('txtBusquedasGenerales').value;
    var fecha_inicial=document.getElementById('fecha_inicial').value;    
    var fecha_final=document.getElementById('fecha_final').value;    
    var orden_tipo=document.getElementById('orden_tipo').value;    
    
    var form_data = new FormData();
        form_data.append('Accion', 1);  
        form_data.append('Page', Page);       
        form_data.append('empresa_id', empresa_id);  
        form_data.append('BusquedasGenerales', BusquedasGenerales);
        form_data.append('fecha_inicial', fecha_inicial);  
        form_data.append('fecha_final', fecha_final);  
        form_data.append('orden_tipo', orden_tipo);  
        
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

function listar_ordenes_maquina(maquina_id,tipo_mantenimiento){
      
    var empresa_id=document.getElementById('empresa_id').value;
    var idModal="modal_view";
    var idDiv="div_modal_view";
    
    var idBotonModal="btnModalView";
    openModal(idModal);
    document.getElementById(idBotonModal).style.display="none";
   
    urlQuery='Consultas/hojas_vida.draw.php';    
    var form_data = new FormData();
        form_data.append('Accion', 2);        
        form_data.append('tipo_mantenimiento', tipo_mantenimiento);
        form_data.append('maquina_id', maquina_id);
        form_data.append('empresa_id', empresa_id);
        form_data.append('idModal', idModal);
        $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', 
        beforeSend: function() { //lo que hará la pagina antes de ejecutar el proceso
            
        },
        complete: function(){
           
        },
        success: function(data){    
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}

function listar_tareas_maquina(maquina_id,tipo_mantenimiento){
      
    var empresa_id=document.getElementById('empresa_id').value;
    
    var idModal="modal_view";
    var idDiv="div_modal_view";
    
    var idBotonModal="btnModalView";
    openModal(idModal);
    document.getElementById(idBotonModal).style.display="none";
    urlQuery='Consultas/hojas_vida.draw.php';    
    var form_data = new FormData();
        form_data.append('Accion', 3);        
        form_data.append('maquina_id', maquina_id);
        form_data.append('empresa_id', empresa_id);
        form_data.append('tipo_mantenimiento', tipo_mantenimiento);
        $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', 
        beforeSend: function() { //lo que hará la pagina antes de ejecutar el proceso
            
        },
        complete: function(){
           
        },
        success: function(data){    
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}

function listar_costos_maquina(maquina_id,tipo_mantenimiento){
      
    var empresa_id=document.getElementById('empresa_id').value;
    
    var idModal="modal_view";
    var idDiv="div_modal_view";
    var idBotonModal="btnModalView";
    openModal(idModal);
    document.getElementById(idBotonModal).style.display="none";
    urlQuery='Consultas/hojas_vida.draw.php';    
    var form_data = new FormData();
        form_data.append('Accion', 4);        
        form_data.append('maquina_id', maquina_id);
        form_data.append('empresa_id', empresa_id);
        form_data.append('tipo_mantenimiento', tipo_mantenimiento);
        $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', 
        beforeSend: function() { //lo que hará la pagina antes de ejecutar el proceso
            
        },
        complete: function(){
           
        },
        success: function(data){    
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}

dibujeListadoSegunID();

