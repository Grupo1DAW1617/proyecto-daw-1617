<?php

require_once __DIR__.'/../../Modelo/BD/GenericoBD.php';;
require_once __DIR__.'/../Plantilla/Views.php';

use Vista\Plantilla;
abstract class CalendarioGestionarFestivosNacionales extends Plantilla\Views
{
    public static function cal($comprobar){
        parent::setOn(true);
        parent::setRoot($comprobar);
        require_once __DIR__."/../Plantilla/cabecera.php";
        ?>
        <!--Iker seleccion fecha induvidual-->
        <link type="text/css" rel="stylesheet" media="all" href="<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/CSS/Bootstrap/estilos.css">


        <div class="calendario_ajax">

            <h2>Añadir festivos nacionales</h2>

            <div class="form-group row" style="padding: 0; margin-left: -15px">
                <h4 class="col-sm-2">Calendarios: </h4>
                <div class="col-sm-2">
                    <select name="calendarios" class="form-control" id="calend">
                        <?php
                        require_once "../../Modelo/BD/CalendarioBD.php";     //Aitor
                        echo "<option value=''>-- Selecciona --</option>";  //Aitor
                        $id = \Modelo\BD\CalendarioBD::getIdCalendario();    //Aitor
                        while ($rows=mysqli_fetch_array($id)){              //Aitor
                            echo "<option value='".$rows["id"]."'>".$rows["id"]."</option>";    //Aitor
                        };

                        ?>
                    </select>
                </div>
            </div>

            <form name="rango row" >
                <h4><br>
                    <div class="form-group">
                        <label style="font-weight: normal" for="rango"> Rango </label> <input type="radio" name="rangoVacaciones" value="rango"/> &nbsp; &nbsp;
                        <label style="font-weight: normal" for="dSueltos"> D&iacute;as Sueltos </label> <input type="radio" name="rangoVacaciones" value="sueltos"/>
                    </div>
                </h4>

                <div style="visibility: hidden"  id="fecha1">
                    <label id="diasNacionales"></label><br>
                    <div class="form-group" style="margin-left: 0px">
                        <label for="fInicial"> Día: </label> <input type="date" id="calendarioNacionales" onchange="guardarOpcion()" min="<?php echo date('Y-m-d') ?>">
                        <input class="btn btn-default btn-sm" type="button" value="Añadir" id="botonNacionales" onclick="guardarFecha()">
                    </div>
                    <input class="btn btn-primary" type="button" value="Guardar" onclick="guardarFechas()">
                </div>
                <div style="visibility: hidden"  id="fecha2">
                    <div class="form-group">
                        <label for="fInicial"> Desde: </label>  <input type="date" id="fInicial" min="<?php echo date('Y-m-d') ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="fFinal"> Hasta: </label>  <input type="date" id="fFinal" min="<?php echo date('Y-m-d') ?>"/>
                    </div>
                    <input class="btn btn-primary" type="button" value="Seleccionar dias" id="rangoDias" name="rangoDias" onclick="guardarRango()"/>
                </div>
            </form>




<!--
            <form name="rango" >
                <h4><p>Vacaciones por Rango o dias Sueltos:</p>
                    <label for="rango"> Rango </label> <input type="radio" name="rangoVacaciones" value="rango"/>
                    <label for="dSueltos"> D&iacute;as Sueltos </label> <input type="radio" name="rangoVacaciones" value="sueltos"/>
                </h4><br/>
                <div style="visibility: hidden"  id="fecha1">
                    <label id="diasNacionales"></label><br>
                    <input type="date" id="calendarioNacionales" min="<?php //echo date('Y-m-d') ?>" onchange="guardarOpcion()">
                    <input type="button" value="Añadir" id="botonNacionales" onclick="guardarFecha()">
                    <div>
                        <input type="button" value="Guardar" onclick="guardarFechas()">
                    </div>
                </div>
                <div style="visibility: hidden"  id="fecha2">
                    <label for="fInicial"> Desde : </label>  <input type="date" id="fInicial" min="<?php //echo date('Y-m-d') ?>"/>  <label for="fFinal"> Hasta : </label>  <input type="date" id="fFinal" min="<?php echo date('Y-m-d') ?>"/>
                    <input type="button" value="Seleccionar dias" id="rangoDias" name="rangoDias" onclick="guardarRango()"/>
                </div>
            </form>
            -->

        </div>

        <script src="<?php echo parent::getUrlRaiz();?>/Vista/Plantilla/JS/jquery-2.2.1.min.js"><br></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/localization/messages_es.js "></script>


        <script>
            var opc = false;
            var fechas = [];

            $("input[name='rangoVacaciones']").change(function () {
                if($(this).val()=="rango"){
                    $("#fecha1").css("display","none");
                    $("#fecha2").css("display","inline");
                    $("#fecha2").css("visibility","visible");
                    fechas = [];
                    $('#diasNacionales').html("");
                }else{
                    $("#fecha1").css("display","inline");
                    $("#fecha1").css("visibility","visible");
                    $("#fecha2").css("display","none");
                    fechas = [];
                }

            });

            $("#fInicial").change(function () {

                $("#fFinal").attr("min", $("#fInicial").val());
                $("#fFinal").val($("#fInicial").val());

            });

            function guardarRango() {

                var fInicio = new Date($("#fInicial").val());
                var fFinal =  new Date($("#fFinal").val());

                fechas.push(fInicio);
                var aux = new Date(fInicio);

                while (aux<fFinal){

                    var date = new Date(fInicio);
                    date.setDate(aux.getDate()+1);
                    aux.setDate(aux.getDate()+1);
                    fechas.push(date);
                }

                guardarFechas();
            }

            function guardarFecha() {
                if(opc == true){
                    $('#diasNacionales').empty();
                    var y;
                    var d = new Date($("#calendarioNacionales").val());
                    for(y = 0; y < fechas.length && (fechas[y].getDate() != d.getDate() || fechas[y].getMonth() != d.getMonth() || fechas[y].getFullYear() != d.getFullYear()); y++){                    }

                    if(y == fechas.length){
                        var date = new Date($("#calendarioNacionales").val());
                        fechas.push(date);
                    }

                    for(var x = 0; x < fechas.length; x++){
                        var dia;
                        var mes;
                        var ano;
                        if(fechas[x].getDate() < 10){
                            dia = "0" + fechas[x].getDate();
                        }else{
                            dia = "" + fechas[x].getDate();
                        }
                        if(fechas[x].getMonth() < 10){
                            mes = "0" + (fechas[x].getMonth() + 1);
                        }else{
                            dia = "" + (fechas[x].getMonth() + 1);
                        }
                        ano = "" + fechas[x].getFullYear();

                        var f = dia + "-" + mes + "-" + ano;
                        $("#diasNacionales").append($('<label id="' + f +'">' + f + '</label>'));
                        $("#diasNacionales").append("&nbsp;");
                        $('#diasNacionales').append($('<input class="btn alert-danger btn-xs" type="button" onclick="borrarFecha('+ x +')" value="X" name="' + f + '">'));
                        $("#diasNacionales").append("&nbsp;&nbsp;");
                    }
                }
            }

            function borrarFecha(fecha) {
                for(var x = 0; x < fechas.length; x++){
                    var dia;
                    var mes;
                    var ano;
                    if(fechas[x].getDate() < 10){
                        dia = "0" + fechas[x].getDate();
                    }else{
                        dia = "" + fechas[x].getDate();
                    }
                    if(fechas[x].getMonth() < 10){
                        mes = "0" + (fechas[x].getMonth() + 1);
                    }else{
                        dia = "" + (fechas[x].getMonth() + 1);
                    }
                    ano = "" + fechas[x].getFullYear();

                    var f = dia + "-" + mes + "-" + ano;
                    $("#" + f + "").remove();
                    $("[name='" + f + "']").css("display", "none");
                }

                fechas.splice(fecha,1);

                for(var x = 0; x < fechas.length; x++){
                    var dia;
                    var mes;
                    var ano;
                    if(fechas[x].getDate() < 10){
                        dia = "0" + fechas[x].getDate();
                    }else{
                        dia = "" + fechas[x].getDate();
                    }
                    if(fechas[x].getMonth() < 10){
                        mes = "0" + (fechas[x].getMonth() + 1);
                    }else{
                        dia = "" + (fechas[x].getMonth() + 1);
                    }
                    ano = "" + fechas[x].getFullYear();

                    var f = dia + "-" + mes + "-" + ano;
                    $("#diasNacionales").append($('<label id="' + f +'">' + f + '</label>'));
                    $("#diasNacionales").append("&nbsp;");
                    $('#diasNacionales').append($('<input class="btn alert-danger btn-xs" type="button" onclick="borrarFecha('+ x +')" value="X" name="' + f + '">'));
                    $("#diasNacionales").append("&nbsp;&nbsp;");
                }
            }

            function guardarOpcion() {
                opc = true;
            }

            function guardarFechas() {
                if(fechas.length == 0 || $('#calend').val() == ""){
                    alert("No puedes dejar los campos sin seleccionar");
                }else{
                    var fechasEnvio = [];
                    for(var x = 0; x < fechas.length; x++){
                        var f = "" + fechas[x].getFullYear() + "-" + (fechas[x].getMonth() + 1) + "-" + fechas[x].getDate() + " 00:00:00";
                        fechasEnvio.push(f);
                    }
                    var calendario = $('#calend').val();

                    $.ajax({

                        type: "GET",
                        url: "<?php echo parent::getUrlRaiz()?>/Controlador/Calendario/ControladorCalendario.php",
                        data: {fechasEnvio:fechasEnvio , calendario:calendario, accion:"festivosNacionales"}
                    })
                        .done(function(respuesta) {
                            alert(respuesta);

                        })
                        .fail(function() {
                            alert( "error" );
                        });
                }
                $("#diasNacionales").html("");
                fechas = [];
            }
            <!--Iker-->
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

