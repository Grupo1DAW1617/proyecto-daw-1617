<?php


namespace Vista\Logistica;

/**
 * Created by PhpStorm.
 * User: Nestor
 * Date: 02/03/2016
 * Time: 8:53
 */

require_once __DIR__.'/../../Modelo/BD/GenericoBD.php';;
require_once __DIR__.'/../Plantilla/Views.php';


use Vista\Plantilla;
abstract class CalendarioViews extends Plantilla\Views
{

public static function generarcalendario(){


    parent::setOn(true);
    require_once __DIR__."/../Plantilla/cabecera.php";
    ?>


    <link type="text/css" rel="stylesheet" media="all" href="<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/CSS/Bootstrap/estilos.css">


    <div class="calendario_ajax">
        <div class="cal"></div><div id="mask"></div>
    </div>

    <script src="<?php echo parent::getUrlRaiz();?>/Vista/Plantilla/JS/jquery-2.2.1.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/localization/messages_es.js "></script>


    <script>
        function generar_calendario(mes,anio)
        {
            var agenda=$(".cal");
            agenda.html("<img src='<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/IMG/loading.gif' alt='Loading'");
            $.ajax({
                type: "POST",
                url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                cache: false,
                data: { mes:mes,anio:anio,accion:"generar_calendario" }
            }).done(function( respuesta )
            {
                agenda.html(respuesta);
            });
        }

        function formatDate (input) {
            var datePart = input.match(/\d+/g),
                year = datePart[0].substring(0),
                month = datePart[1], day = datePart[2];
            return day+'-'+month+'-'+year;
        }

        $(document).ready(function()
        { //AÑADIR EL VIAJE RECOGIENDO FORMULARIO

            /* GENERAMOS CALENDARIO CON FECHA DE HOY */
            generar_calendario("<?php if (isset($_GET["mes"])) echo $_GET["mes"]; ?>","<?php if (isset($_GET["anio"])) echo $_GET["anio"]; ?>");


            /* AGREGAR UN VIAJE */
            $(document).on("click",'a.add',function(e)
            {
                e.preventDefault();
               // var id = $(this).data('evento');
                var fecha = $(this).attr('rel');

                $(".cal").fadeOut(500);

                $.ajax({
                    type: "POST",
                    url: "<?php echo parent::getUrlRaiz()?>/Vista/Logistica/GeneradorFormsViews.php",
                    cache: false,
                    data: { fecha:formatDate(fecha),cod:1 }
                }).done(function( respuesta ){
                    if(respuesta==false){
                        $("#respuesta_form").html("<div class='alert alert-danger' role='alert'><strong>Error:</strong> La fecha del Parte es Incorrecta.</div>");
                        $(".formeventos").css("display","none")
                    }else{
                        $(".formeventos").html(respuesta);
                    }
                });

                $('#mask').fadeIn(1600)
                .html(
                    "<div id='nuevo_evento' class='row' rel='"+fecha+"'>" +
                        "<h2 class='col-xs-12 text-center'>Parte de "+formatDate(fecha)+"</h2>" +
                    "</div>" +
                    "<div class='row window' rel='"+fecha+"'>"+
                        "<div id='respuesta_form' class='col-xs-12 col-md-8 col-md-offset-2'></div>" +
                        "<div class='col-xs-12 col-md-8 col-md-offset-1'>"+
                            "<form class='formeventos form-horizontal'>" +
                                //"<input type='text' name='evento_titulo' id='evento_titulo' class='required'>" +
                                //"<input type='button' name='Enviar' value='Guardar' class='enviar'>" +
                                "<input type='hidden' name='evento_fecha' id='evento_fecha' value='"+fecha+"'>" +
                            "</form>"+
                        "</div>"+
                    "</div>");
                });

            /* LISTAR EVENTOS DEL DIA */
            $(document).on("click",'a.mod',function(e)
            {
                e.preventDefault();
                var fecha = $(this).attr('rel');
                $(".cal").fadeOut(500);

                $('#mask').fadeIn(1500).html("<div id='nuevo_evento' class='window' rel='"+fecha+"'><h2>Viajes del "+formatDate(fecha)+"</h2><a href='#' class='cerrar' rel='"+fecha+"'>&nbsp;</a><div id='respuesta'></div><div id='respuesta_form'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                    cache: false,
                    data: { fecha:fecha,accion:"listar_evento" }
                }).done(function( respuesta )
                {
                    $("#respuesta_form").html(respuesta);
                });

            });

            /*Cerrar Parte*/

            $(document).on("click",'.cerrarParte',function(e)
            {
                var fecha = $("#nuevo_evento").attr('rel');
                e.preventDefault();
                //Aitor I
                $('#mask').html("<div id='nueva_nota' class='window' rel='"+fecha+"'><h2>Parte del "+formatDate(fecha)+"</h2><a href='#' class='cerrar' rel='"+fecha+"'>&nbsp;</a><div id='respuesta'></div><div id='respuesta_form'><form><div class='form-group'><div class='form-group col-sm-4'><label class='col-sm-6 control-label'>Autopista</label>                             <div class='input-group col-sm-3'>                                 <input type='text' class='form-control' name='autopista' id='autopistas' aria-describedby='basic-addon2'>                                 <span class='input-group-addon' id='basic-addon2'>€</span>                             </div>                         </div>                         <div class='form-group col-sm-4'>                             <label class='col-sm-6 control-label'>Dietas:</label>                             <div class='input-group col-sm-3'>                                 <input type='text' class='form-control' name='dieta' id='dietas' aria-describedby='basic-addon2'>                                 <span class='input-group-addon' id='basic-addon2'>€</span></div></div><div class='form-group col-sm-4'><label class='col-sm-6 control-label'>Otros Gastos:</label><div class='input-group col-sm-3'><input type='text' class='form-control' name='otroGastos' id='otrosGastos' aria-describedby='basic-addon2'><span class='input-group-addon' id='basic-addon2'>€</span></div></div>   <label for='Nota' class='col-sm-3 control-label'>Nota: </label><div class='col-sm-6'><textarea rows='5' id='Nota' class='form-control'></textarea><br><div class='form-group'><button id='aceptar' class='btn-primary btn pull-left col-sm-3 aceptar'>Añadir</button></div></div></div></form></div></div>");


                $(document).on("click",'.aceptar',function(f)
                {
                    f.preventDefault();
                    var fecha = $("#nueva_nota").attr('rel');
                    var nota = $("#Nota").val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                        cache: false,
                        data: { fecha:fecha,nota:nota,autopistas:$("#autopistas").val(), otroGastos:$("#otrosGastos").val(), dieta:$("#dietas").val(),accion:"cerrarParte" }//Aitor I
                    }).done(function( respuesta )
                    {
                        $("#respuesta").html(respuesta);

                        setTimeout(function(){

                            $("#mask").fadeOut(500);
                            $('.cal').fadeIn();
                            location.reload();

                        },3000);

                    });
                });

            });


            $(document).on("click",'.cerrar',function (e)
            {
                e.preventDefault();
                $('#mask').fadeOut(500);
                $(".cal").fadeIn(1600);

                setTimeout(function()
                {
                    var fecha=$(".window").attr("rel");
                    var fechacal=fecha.split("-");
                    generar_calendario(fechacal[1],fechacal[0]);
                }, 500);
            });



            //guardar evento
             

    $(document).on("click",'.enviar',function (e)
            {
                e.preventDefault();
                var current_p=$(this);
                var vehiculo=$('#Vehiculo').val();
                var horaInicio=$('#HorasInicio').val()+":"+$('#MinutosInicio').val()+":00";
                var horaFin=$('#HorasFin').val()+":"+$('#MinutosFin').val()+":00";
                var albaran=$('#Albaran').val();
                var fecha=$('#FechaHoy').val();
                var matriz_booleana_o_algo_asi = [1,1];
                //VALIDACIONES AITOR I, .... SE PODRIAN HACER EN UNA FUNCION Y REUTILIZARLA(PORQUE ESTOY SEGURO QUE ESTO LO VOY A TENER QUE REUTILIZAR)
                //PERO EN ESTE PUNTO DEL PROYECTO, VIENDO LO QUE HICIERON LOS DEL AÑO PASADO CREO YO QUE YA NO TENDRIA DEMASIADO SENTIDO. TOTAL SIEMPRE
                //SERA MEJOR COPY PASTE DE ESTO QUE DE UNA TABLA DE 3000 LINEAS HECHA CON COPYPASTE EN CADA CELDA Y MULTIPLICADA POR 4
                if(horaFin <= horaInicio){matriz_booleana_o_algo_asi[0] = 0;}
                
                if(albaran === ""){matriz_booleana_o_algo_asi[1] = 0;}
                else{                                                       
                    if(isNaN(albaran)){matriz_booleana_o_algo_asi[1] = 0;}
                    else{
                        if(parseInt(albaran) !== parseFloat(albaran)){matriz_booleana_o_algo_asi[1] = 0;}
                    }
                }

                if(matriz_booleana_o_algo_asi[0] * matriz_booleana_o_algo_asi[1]){    
                    $.ajax({
                        type: "POST",
                        url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                        cache: false,
                        data: { vehiculo:vehiculo,horaInicio:horaInicio,horaFin:horaFin,albaran:albaran,fecha:fecha,accion:'addViaje' }
                    }).done(function( respuesta )
                        {
                            $("#mask").html(respuesta);
                            setTimeout(function(){

                                $("#mask").fadeOut(500);
                                $('.cal').fadeIn();
                                location.reload();

                            },3000);
                        })
                        .error(function(xhr){alert(xhr.status)}); 
                }
                else{
                    var form_groups =document.getElementsByClassName("form-group");
                    if(!matriz_booleana_o_algo_asi[0]){
                       form_groups[1].style.background = "#f00000";
                       form_groups[2].style.background = "#f00000"; 
                    }
                    if(!matriz_booleana_o_algo_asi[1]){form_groups[3].style.background = "#f00000";}

                }
		});


            //eliminar evento
            $(document).on("click",'.eliminar_evento',function (e)
            {
                e.preventDefault();
                var current_p=$(this);
                $("#respuesta").html("<img src='<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/IMG/loading.gif''>");
                var id=$(this).attr("rel");
                $.ajax({
                    type: "POST",
                    url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                    cache: false,
                    data: { id:id,accion:"borrar_evento" }
                }).done(function( respuesta2 )
                {
                    $("#respuesta").html(respuesta2);
                    setTimeout(function(){

                        $("#mask").fadeOut(500);
                        $('.cal').fadeIn();
                        location.reload();

                    },2000);
                });
            });

            //Aitor I (hecho a la manera de los creadores del proyecto)

            $(document).on("click",'.botonModif',function (e) {
                e.preventDefault();
                var current_p=$(this);
                var id=$(this).attr("rel");

                $(".cal").fadeOut(500);

                $.ajax({
                    type: "POST",
                    url: "<?php echo parent::getUrlRaiz()?>/Vista/Logistica/GeneradorFormsViews.php",
                    cache: false,
                    data: {cod:1, id:id} //Aitor
                }).done(function( respuesta ){
                    if(respuesta==false){
                        $("#respuesta_form").html("<div class='alert alert-danger' role='alert'><strong>Error:</strong> La fecha del Parte es Incorrecta.</div>");
                        $(".formeventos").css("display","none")
                    }else{
                        $(".formeventos").html(respuesta);
                    }
                });

                $('#mask').fadeIn(1600)
                    .html(
                        "<div id='nuevo_evento' class='row' rel='"+id+"'>" +
                        "<h2 class='col-xs-12 text-center'>Viaje con id "+id+"</h2>" +
                        "</div>" +
                        "<div class='row window' rel='"+id+"'>"+
                        "<div id='respuesta_form' class='col-xs-12 col-md-8 col-md-offset-2'></div>" +
                        "<div class='col-xs-12 col-md-8 col-md-offset-1'>"+
                        "<form class='formeventos form-horizontal'>" +
                        //"<input type='text' name='evento_titulo' id='evento_titulo' class='required'>" +
                        //"<input type='button' name='Enviar' value='Guardar' class='enviar'>" +
                        "<input type='hidden' name='evento_fecha' id='evento_fecha' value='"+id+"'>" +
                        "</form>"+
                        "</div>"+
                        "</div>");

            });


            $(document).on("click",".modificarLinea", function (e) {
                e.preventDefault();
                var current_p=$(this);
                var id=$(this).attr("rel");
                var vehiculo=$('#Vehiculo').val();
                var horaInicio=$('#HorasInicio').val()+":"+$('#MinutosInicio').val()+":00";
                var horaFin=$('#HorasFin').val()+":"+$('#MinutosFin').val()+":00";
                var albaran=$('#Albaran').val();
                var fecha=$('#FechaHoy').val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                    cache: false,
                    data: { id:id, vehiculo:vehiculo,horaInicio:horaInicio,horaFin:horaFin,albaran:albaran,fecha:fecha,accion:'modificar_evento' }
                }).done(function( respuesta )
                {
                    $("#mask").html(respuesta);
                    setTimeout(function(){

                        $("#mask").fadeOut(500);
                        $('.cal').fadeIn();
                        location.reload();

                    },3000);



                })
                    .error(function(xhr){alert(xhr.status)});


            });




            //































            $(document).on("click",".anterior,.siguiente,.hoyEnlace",function(e)
            {
                e.preventDefault();
                var datos=$(this).attr("rel");
                var nueva_fecha=datos.split("-");
                generar_calendario(nueva_fecha[1],nueva_fecha[0]);
            });

        });
    </script>

    <!-- ESTO NO TE HACE FALTA! ¿EN SERIO NO ME DIGAS? Aitor I-->
    <script type="text/javascript">
        var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
        try {
            var pageTracker = _gat._getTracker("UA-266167-20");
            pageTracker._setDomainName(".martiniglesias.eu");
            pageTracker._trackPageview();
        } catch(err) {}</script>

<?php
require_once __DIR__."/../Plantilla/pie.php";
    }
}

