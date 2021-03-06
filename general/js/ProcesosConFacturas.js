/**
 * Controlador para realizar los procesos de contabilizacion y descarga de inventarios de facturas
 * JULIAN ALVARAN 2019-01-10
 * TECHNO SOLUCIONES SAS 
 * 317 774 0609
 */

/**
 * Se encarga de crear las tablas en el servidor externo
 * @returns {undefined}
 */
function ContabilizarFacturas(){
       
    var form_data = new FormData();
        form_data.append('idAccion', 1);
        
        $.ajax({
        url: '../../general/procesadores/ProcesosConFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           // console.log(data);
          var respuestas = data.split(';'); 
          if (respuestas[0] == "OK") { 
              
                var Mensaje=respuestas[1];
                if(Mensaje!=''){
                    document.getElementById('DivMensajesContabilizacionFacturas').innerHTML=Mensaje;                
                }
                setTimeout('ContabilizarFacturas()',30*1000);
          }else {
                document.getElementById('DivErroresContabilizacionFacturas').innerHTML=data; 
                setTimeout('ContabilizarFacturas()',30*1000);
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  

ContabilizarFacturas();
