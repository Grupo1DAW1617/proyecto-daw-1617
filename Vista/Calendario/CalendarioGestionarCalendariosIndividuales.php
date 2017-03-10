<?php

require_once __DIR__.'/../../Modelo/BD/GenericoBD.php';;
require_once __DIR__.'/../Plantilla/Views.php';

use Vista\Plantilla;
abstract class CalendarioGestionarCalendariosIndividuales extends Plantilla\Views
{
    public static function cal($comprobar){
        parent::setOn(true);
        parent::setRoot($comprobar);
        require_once __DIR__."/../Plantilla/cabecera.php";
        ?>

        <link type="text/css" rel="stylesheet" media="all" href="<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/CSS/Bootstrap/estilos.css">

        <form name="trabajador" method="post" action="../../Controlador/Calendario/ControladorCalendario.php">

            <h2>Asignar calendarios individuales</h2>

            <div class="form-group row" id="empresa" style="padding: 0; margin-left: -15px">
                <h4 class="col-sm-2">Empresa: </h4>
                <div class="col-sm-2">
                    <select id="nomEmpresa" class="form-control">
                        <option value="-1">-- Seleccionar --</option>
                        <?php
                        $empresas = \Modelo\BD\EmpresaBD::getAll();
                        foreach($empresas as $empresa){
                            ?>
                            <option value="<?php echo $empresa->getId(); ?>"><?php echo $empresa->getNombre(); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row" style="padding: 0; margin-left: -15px">
                <h4 class="col-sm-2">Trabajador: </h4>
                <div class="col-sm-2">
                    <select class="form-control" name="trabajador" id="trabajador">
                    </select>
                </div>
            </div>

            <input type="submit" class="btn btn-primary" name="aceptar" value="Aceptar">

        </form>

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
                            "<a class='cerrar close'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>" +
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
                    $('#mask').html("<div id='nueva_nota' class='window' rel='"+fecha+"'><h2>Parte del "+formatDate(fecha)+"</h2><a href='#' class='cerrar' rel='"+fecha+"'>&nbsp;</a><div id='respuesta'></div><div id='respuesta_form'><form><div class='form-group'><label for='Nota' class='col-sm-3 control-label'>Nota: </label><div class='col-sm-9'><textarea rows='15' id='Nota' class='form-control'></textarea><div class='form-group'><button id='aceptar' class='btn-primary btn pull-left col-sm-3 aceptar'>Añadir</button></div></div></div></form></div></div>");


                    $(document).on("click",'.aceptar',function(f)
                    {
                        f.preventDefault();
                        var fecha = $("#nueva_nota").attr('rel');
                        var nota = $("#Nota").val();
                        $.ajax({
                            type: "POST",
                            url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                            cache: false,
                            data: { fecha:fecha,nota:nota,accion:"cerrarParte" }
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

                $(document).on("click",".anterior,.siguiente,.hoyEnlace",function(e)
                {
                    e.preventDefault();
                    var datos=$(this).attr("rel");
                    var nueva_fecha=datos.split("-");
                    generar_calendario(nueva_fecha[1],nueva_fecha[0]);
                });

                /**
                 * A la hora de cambiar el nombre de la empresa , que te busque los trabajadores
                 *
                 * Anas
                 **/

                $( "#nomEmpresa").on("change",function () {
                    try{

                        var idEmpresa = $(this).val();

                        $.ajax({

                            type: "GET",
                            url: "<?php echo parent::getUrlRaiz()?>/Controlador/Calendario/ControladorCalendario.php",
                            data: { idEmpresa : idEmpresa , accion:"buscarTrab"}

                        })
                            .done(function(respuesta) {

                                $("#trabajador").html(respuesta);

                            })
                            .fail(function() {
                                alert( "error" );
                            });

                    }catch (err){
                        alert(err)
                    }
                });

            });
        </script>

        <!-- ESTO NO TE HACE FALTA! -->
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

