<?php

require_once __DIR__.'/../../Modelo/BD/GenericoBD.php';;
require_once __DIR__.'/../Plantilla/Views.php';
require_once __DIR__.'/../../Modelo/BD/EmpresaBD.php';
use Vista\Plantilla;
abstract class CalendarioVac extends Plantilla\Views
{
    public static function cal($comprobar){
        parent::setOn(true);
        parent::setRoot($comprobar);
        require_once __DIR__."/../Plantilla/cabecera.php";
        ?>


        <link type="text/css" rel="stylesheet" media="all" href="<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/CSS/Bootstrap/ProduccionStyle.css">

        <?php if ($comprobar){?>

        <div class="calendario_ajax">
            <!--
                Rango entre fechas
                Anas
            -->
            <form name="trabajador" method="post" action="../../Controlador/Calendario/ControladorCalendario.php">

                <div class="col-md-3">
                    <h4 for="fechaDisfrutadas">Vacaciones disfrutadas</h4><br>
                    <label style="margin-left: -25px" name="vacacionesDisfrutadas" id="vacacionesDisfrutadas"></label>
                </div>

                <div class="col-md-4 col-md-offset-1">

                    <div class="form-group row" id="empresa" style="padding: 0; margin-left: -15px">

                        <h2>Asignación de vacaciones:</h2><br>
                        <h4 class="col-sm-5">Empresa: </h4>
                        <div class="col-sm-6">
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
                        <h4 class="col-sm-5">Trabajador: </h4>
                        <div class="col-sm-6">
                            <select class="form-control" name="trabajador" id="trabajador">
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">

                        <h4>
                            <div class="form-group">
                                <label style="font-weight: normal" for="rango"> Rango </label> <input type="radio" id="rango" name="rangoVacaciones" value="rango"/> &nbsp; &nbsp;
                                <label style="font-weight: normal" for="dSueltos"> D&iacute;as Sueltos </label> <input type="radio" id="dSueltos" name="rangoVacaciones" value="sueltos"/>
                            </div>
                        </h4>
                        <div style="visibility: hidden"  id="fecha1">
                            <label id="diasNacionales"></label><br>
                            <div class="form-group" style="margin-left: 0px">
                                <label for="fInicial"> Día: </label> <input type="date" id="calendarioNacionales" onchange="guardarOpcion()" min="<?php echo date('Y-m-d') ?>" >
                                <input class="btn btn-default btn-sm" type="button" value="Añadir" id="botonNacionales" onclick="guardarFecha()">
                            </div>
                            <input class="btn btn-primary" type="button" value="Guardar" onclick="guardarFechas()">
                        </div>
                        <div style="visibility: hidden"  id="fecha2">
                            <div class="form-group" style="margin-left: 60px">
                                <label for="fInicial"> Desde: </label>  <input type="date" id="fInicial" min="<?php echo date('Y-m-d') ?>" />
                            </div>
                            <div class="form-group" style="margin-left: 60px">
                                <label for="fFinal"> Hasta: </label>  <input type="date" id="fFinal"  />
                            </div>
                            <input class="btn btn-primary" type="button" value="Seleccionar dias" id="rangoDias2" name="rangoDias"/>
                        </div>

                    </div>

                </div>

                <div class="col-md-3 col-md-offset-1">
                    <div id="eNomTrab"></div>
                    <h4>Vacaciones solicitadas</h4><br>
                    <label style="margin-left: -25px" name="vacOpen" id="vacOpen"></label><br><br>

                    <input type="button" class="btn btn-success btn-sm" value="Aceptar" onclick="fEditarVacaciones('A')">
                    <input type="button" class="btn btn-danger btn-sm" value="Rechazar" onclick="fEditarVacaciones('R')"><br>
                </div>

            </form>

        </div>


<!--
                <div id="empresa">
                    <label for="nomEmpresa">Nombre de la empresa :</label>
                    <select id="nomEmpresa">
                        <option value="-1" > Seleccionar </option>
                        <?php/*
                        $empresas = \Modelo\BD\EmpresaBD::getAll();
                        foreach($empresas as $empresa){
                            ?>
                            <option value="<?php echo $empresa->getId(); ?>"><?php echo $empresa->getNombre(); ?></option>
                            <?php
                        }*/
                        ?>
                    </select>

                    <label for="nomTrabajador">Trabajador :</label>
                    <select name="trabajador" id="trabajador">
                    </select>

                    <div id="eNomTrab"></div><br>
                    <label for="FechaSolic">Vacaciones solicitadas : &nbsp;</label><br>
                    <label name="vacOpen" id="vacOpen"></label>
                </div>

            <form name="rango" >

                <h4><p>Vacaciones por Rango o dias Sueltos:</p>
                    <label for="rango"> Rango </label> <input type="radio" name="rangoVacaciones" value="rango"/>
                    <label for="dSueltos"> D&iacute;as Sueltos </label> <input type="radio" name="rangoVacaciones" value="sueltos"/>
                </h4><br/>
                <div style="visibility: hidden"  id="fecha1">
                    <label id="diasNacionales"></label><br>
                    <input type="date" id="calendarioNacionales" onchange="guardarOpcion()" min="<?php //echo date('Y-m-d') ?>" >
                    <input type="button" value="Añadir" id="botonNacionales" onclick="guardarFecha()"><br/>
                    <input type="button" value="Guardar" onclick="guardarFechas()">
                </div>
                <div style="visibility: hidden"  id="fecha2">
                    <label for="fInicial"> Desde : </label>  <input type="date" id="fInicial" min="<?php //echo date('Y-m-d') ?>" />

                    <label for="fFinal"> Hasta : </label>  <input type="date" id="fFinal"  /><br/>
                    <div id="errorFechas"> </div>

                    <input type="button" value="Seleccionar dias" id="rangoDias2" name="rangoDias"/>
                </div>

                <label for="fechaDisfrutadas">Vacaciones disfrutadas:</label><br><label name="vacacionesDisfrutadas" id="vacacionesDisfrutadas"></label>
            </form>

            <div style='visibility: hidden' class="cal"></div><div id="mask"></div>
        </form>
        -->


            <?php
        }else{
            ?>
            <div class="calendario_ajax">
                <div class="cal"></div><div id="mask"></div>
            </div>
        <?php
            }
        ?>
        <script src="<?php echo parent::getUrlRaiz();?>/Vista/Plantilla/JS/jquery-2.2.1.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/localization/messages_es.js "></script>


        <script>
                var fechas = [];

                /**
                 * Para que el minimo del segundo campo de fechas sea igual que el primer campo
                 *
                 * Anas
                 **/
                $("#fInicial").change(function () {

                    $("#fFinal").attr("min", $("#fInicial").val());

                });
                /**
                 * Insertar en la base de datos el rango de fechas
                 *
                 * Anas
                 * **/

                $("#rangoDias2").click(function () {
                    var fIni = [];
                    var fFin = [];
                    try {
                        if ($("#nomEmpresa").val() != "-1") {

                            $("#eNomTrab").css("display","none");

                            if($("#fInicial").val()!="" && $("#fFinal").val()!=""){

                                $("#errorFechas").css("display","none");

                                var fInicial = $("#fInicial").val();
                                var fFinal = $("#fFinal").val();

                                var dniTrabajador = $("#trabajador option:selected").val();

                                var d = new Date();
                                var ano = d.getFullYear();
                                var fecha = generarFecha();

                                fIni = generarRango($("#fInicial").val(), $("#fFinal").val(), "inicio");

                                fFin = generarRango($("#fInicial").val(), $("#fFinal").val(), "fin");

                                var estado = "A";

                                $.ajax({

                                    type: "GET",
                                    url: "<?php echo parent::getUrlRaiz()?>/Controlador/Calendario/ControladorCalendario.php",
                                    data: {
                                        dniTrabajador: dniTrabajador,
                                        fecha: fecha,
                                        horaInicio: fIni,
                                        horaFin: fFin,
                                        calendario_id: ano,
                                        estado: estado,
                                        accion: "insertarCal"
                                    }

                                })
                                    .done(function (respuesta) {
                                        if(respuesta){
                                            alert("Insertadas");
                                            location.reload();
                                        }else{
                                            alert("NO Insertadas")
                                        }
                                    })
                                    .fail(function () {
                                        alert("error");
                                    });
                            }else{
                                $("#errorFechas").html("Fecha inicial o Fecha final no seleccionada").css({'color':'red','font-weight':'bold'});
                            }
                        } else {
                            $("#eNomTrab").html("Selecciona el nombre de la empresa").css({'color':'red','font-weight':'bold'});
                        }
                        fIni = [];
                        fFin = [];
                    } catch (err) {
                        alert(err);
                    }

                });

                /**
                 * Dias sueltos
                 *
                 * Anas
                 * */
                function guardarFechas() {
                    if (fechas.length == 0) {
                        alert("No puedes dejar los campos sin seleccionar");
                    } else {

                        var dniTrabajador = $("#trabajador option:selected").val();

                        var d = new Date();
                        var ano = d.getFullYear();

                        var fechasEnvio = [];
                        for(var x = 0; x < fechas.length; x++){
                            var f = "" + fechas[x].getFullYear() + "-" + (fechas[x].getMonth() + 1) + "-" + fechas[x].getDate();
                            fechasEnvio.push(f);
                        }

                        var estado = "A";

                        try {
                            $.ajax({
                                type: "GET",
                                url: "<?php echo parent::getUrlRaiz()?>/Controlador/Calendario/ControladorCalendario.php",
                                data: {
                                    dniTrabajador: dniTrabajador,
                                    fechas: fechasEnvio,
                                    calendario_id: ano,
                                    estado: estado,
                                    accion: "insertarCalIndiv"
                                }
                            })
                                .done(function (respuesta) {
                                    alert(respuesta);

                                })
                                .fail(function () {
                                    alert("error");
                                });
                        } catch (err) {
                            alert(err);
                        }

                    }
                    $("#diasNacionales").html("");
                    fechas = [];
                }


                /**
                 * Calcular en rango de fechas y guardarlos en un array , para despues transformarlo en el formato YYYY-MM-DD HH:MM:SS
                 *
                 *  Anas e Iker
                 **/
                function generarRango(fInicial, fFinal, tiempo) {
                    var fIni = [];
                    var fFin = [];
                    var dI = new Date(fInicial);
                    var dF = new Date(fFinal);

                    fechas.push(dI);
                    var aux = new Date(dI);

                    while (aux < dF) {

                        var date = new Date(dI);
                        date.setDate(aux.getDate() + 1);
                        aux.setDate(aux.getDate() + 1);
                        fechas.push(date);

                    }

                    for (var x = 0; x < fechas.length; x++) {

                        var dia = fechas[x].getDate();
                        var mes = fechas[x].getMonth() + 1;
                        var ano = fechas[x].getFullYear();

                        if (tiempo == "inicio") {
                            fIni.push(ano + "-" + mes + "-" + dia + " 00:00:00");
                        } else {
                            fFin.push(ano + "-" + mes + "-" + dia + " 23:59:59");
                        }
                    }

                    if (tiempo == "inicio") {
                        return fIni;
                    } else {
                        return fFin;
                    }

                }

                function fEditarVacaciones(valor) {
                    if($("#vacOpen").html() != ""){
                        if ($("#nomEmpresa").val() != "-1") {
                            var trabajador = $("#trabajador option:selected").val();
                            try {
                                $.ajax({
                                    type: "GET",
                                    url: "<?php echo parent::getUrlRaiz()?>/Controlador/Calendario/ControladorCalendario.php",
                                    data: {trabajador: trabajador, valor: valor, accion: "editarCalendario"}
                                })
                                    .done(function (respuesta) {
                                        alert(respuesta);
                                        $("#vacOpen").html("");
                                    })
                                    .fail(function () {
                                        alert("error");
                                    });
                            } catch (err) {
                                alert(err);
                            }
                        } else {
                            alert("No puedes dejar el trabajor sin seleccionar");
                        }
                    }else{
                        alert("No hay vacaciones solicitadas");
                    }

                }

                function generarFecha() {

                    var date = new Date();

                    var m = date.getUTCMonth() + 1;
                    var d = date.getUTCDate();
                    var y = date.getUTCFullYear();

                    return d + "-" + m + "-" + y;

                }

                function guardarOpcion() {
                    opc = true;
                }


                $("input[name='rangoVacaciones']").change(function () {
                    if ($(this).val() == "rango") {
                        $("#fecha1").css("display", "none");
                        $("#fecha2").css("display", "inline");
                        $("#fecha2").css("visibility", "visible");
                        fechas = [];
                        $('#diasNacionales').html("");
                    } else {
                        $("#fecha1").css("display", "inline");
                        $("#fecha1").css("visibility", "visible");
                        $("#fecha2").css("display", "none");
                        fechas = [];
                    }

                });

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


                    /*
                    if (opc == true) {
                        if ($("#diasNacionales").is(':empty')) {
                            $("#diasNacionales").append($('<label id="' + $("#calendarioNacionales").val() + '">' + $("#calendarioNacionales").val() + '</label>'));
                            fechas.push($("#calendarioNacionales").val());

                            var v = $("#calendarioNacionales").val().toString();
                            $('#diasNacionales').append($('<input type="button" onclick="borrarFecha(' + 0 + ')" value="X" name="' + v + '">'));

                        } else {
                            var y;
                            for (y = 0; y < fechas.length && fechas[y] != $("#calendarioNacionales").val(); y++) {
                            }

                            if (y == fechas.length) {
                                fechas.push($("#calendarioNacionales").val());
                                $('#diasNacionales').empty();

                                for (var x = 0; x < fechas.length; x++) {
                                    $("#diasNacionales").append($('<label id="' + fechas[x] + '">' + fechas[x] + '</label>'));
                                    $('#diasNacionales').append($('<input type="button" onclick="borrarFecha(' + x + ')" value="X" name="' + fechas[x].toString() + '">'));
                                }
                            }
                        }

                    }
*/


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
                    /*
                    fecha = fechas[fecha];
                    var y;
                    for (y = 0; y < fechas.length && fechas[y] != fecha; y++) {
                    }

                    fechas.splice(y, 1);
                    $('#diasNacionales').empty();
                    for (var x = 0; x < fechas.length; x++) {
                        $("#diasNacionales").append($('<label id="' + fechas[x] + '">' + fechas[x] + '</label>'));
                        $('#diasNacionales').append($('<input type="button" onclick="borrarFecha(' + x + ')" value="X" name="' + fechas[x].toString() + '">'));
                    }*/
                }

                function generar_calendario(mes, anio) {
                    var agenda = $(".cal");
                    agenda.html("<img src='<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/IMG/loading.gif' alt='Loading'");
                    $.ajax({
                        type: "POST",
                        url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendarioVacaciones.php",
                        cache: false,
                        data: {mes: mes, anio: anio, accion: "generar_calendario"}
                    }).done(function (respuesta) {
                        agenda.html(respuesta);
                    });
                }

                function formatDate(input) {
                    var datePart = input.match(/\d+/g),
                        year = datePart[0].substring(0),
                        month = datePart[1], day = datePart[2];
                    return day + '-' + month + '-' + year;
                }

                $(document).ready(function () { //AÑADIR EL VIAJE RECOGIENDO FORMULARIO

                    var f = new Date();
                    actualizarEstado(f);
                    /* GENERAMOS CALENDARIO CON FECHA DE HOY */
                    generar_calendario("<?php if (isset($_GET["mes"])) echo $_GET["mes"]; ?>", "<?php if (isset($_GET["anio"])) echo $_GET["anio"]; ?>");


                    /* AGREGAR UN VIAJE */
                    $(document).on("click", 'a.add', function (e) {
                        e.preventDefault();
                        // var id = $(this).data('evento');
                        var fecha = $(this).attr('rel');

                        $(".cal").fadeOut(500);

                        $.ajax({
                            type: "POST",
                            url: "<?php echo parent::getUrlRaiz()?>/Vista/Logistica/GeneradorFormsViews.php",
                            cache: false,
                            data: {fecha: formatDate(fecha), cod: 1}
                        }).done(function (respuesta) {
                            if (respuesta == false) {
                                $("#respuesta_form").html("<div class='alert alert-danger' role='alert'><strong>Error:</strong> La fecha del Parte es Incorrecta.</div>");
                                $(".formeventos").css("display", "none")
                            } else {
                                $(".formeventos").html(respuesta);
                            }
                        });

                        $('#mask').fadeIn(1600)
                            .html(
                                "<a class='cerrar close'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>" +
                                "<div id='nuevo_evento' class='row' rel='" + fecha + "'>" +
                                "<h2 class='col-xs-12 text-center'>Parte de " + formatDate(fecha) + "</h2>" +
                                "</div>" +
                                "<div class='row window' rel='" + fecha + "'>" +
                                "<div id='respuesta_form' class='col-xs-12 col-md-8 col-md-offset-2'></div>" +
                                "<div class='col-xs-12 col-md-8 col-md-offset-1'>" +
                                "<form class='formeventos form-horizontal'>" +
                                //"<input type='text' name='evento_titulo' id='evento_titulo' class='required'>" +
                                //"<input type='button' name='Enviar' value='Guardar' class='enviar'>" +
                                "<input type='hidden' name='evento_fecha' id='evento_fecha' value='" + fecha + "'>" +
                                "</form>" +
                                "</div>" +
                                "</div>");
                    });

                    /* LISTAR EVENTOS DEL DIA */
                    $(document).on("click", 'a.mod', function (e) {
                        e.preventDefault();
                        var fecha = $(this).attr('rel');
                        $(".cal").fadeOut(500);

                        $('#mask').fadeIn(1500).html("<div id='nuevo_evento' class='window' rel='" + fecha + "'><h2>Viajes del " + formatDate(fecha) + "</h2><a href='#' class='cerrar' rel='" + fecha + "'>&nbsp;</a><div id='respuesta'></div><div id='respuesta_form'></div></div>");
                        $.ajax({
                            type: "POST",
                            url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                            cache: false,
                            data: {fecha: fecha, accion: "listar_evento"}
                        }).done(function (respuesta) {
                            $("#respuesta_form").html(respuesta);
                        });

                    });

                    /*Cerrar Parte*/

                    $(document).on("click", '.cerrarParte', function (e) {
                        var fecha = $("#nuevo_evento").attr('rel');
                        e.preventDefault();
                        $('#mask').html("<div id='nueva_nota' class='window' rel='" + fecha + "'><h2>Parte del " + formatDate(fecha) + "</h2><a href='#' class='cerrar' rel='" + fecha + "'>&nbsp;</a><div id='respuesta'></div><div id='respuesta_form'><form><div class='form-group'><label for='Nota' class='col-sm-3 control-label'>Nota: </label><div class='col-sm-9'><textarea rows='15' id='Nota' class='form-control'></textarea><div class='form-group'><button id='aceptar' class='btn-primary btn pull-left col-sm-3 aceptar'>Añadir</button></div></div></div></form></div></div>");


                        $(document).on("click", '.aceptar', function (f) {
                            f.preventDefault();
                            var fecha = $("#nueva_nota").attr('rel');
                            var nota = $("#Nota").val();
                            $.ajax({
                                type: "POST",
                                url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                                cache: false,
                                data: {fecha: fecha, nota: nota, accion: "cerrarParte"}
                            }).done(function (respuesta) {
                                $("#respuesta").html(respuesta);

                                setTimeout(function () {

                                    $("#mask").fadeOut(500);
                                    $('.cal').fadeIn();
                                    location.reload();

                                }, 3000);

                            });
                        });

                    });


                    $(document).on("click", '.cerrar', function (e) {
                        e.preventDefault();
                        $('#mask').fadeOut(500);
                        $(".cal").fadeIn(1600);

                        setTimeout(function () {
                            var fecha = $(".window").attr("rel");
                            var fechacal = fecha.split("-");
                            generar_calendario(fechacal[1], fechacal[0]);
                        }, 500);
                    });


                    //guardar evento
                    $(document).on("click", '.enviar', function (e) {
                        e.preventDefault();
                        var current_p = $(this);
                        var vehiculo = $('#Vehiculo').val();
                        var horaInicio = $('#HorasInicio').val() + ":" + $('#MinutosInicio').val() + ":00";
                        var horaFin = $('#HorasFin').val() + ":" + $('#MinutosFin').val() + ":00";
                        var albaran = $('#Albaran').val();
                        var fecha = $('#FechaHoy').val();


                        $.ajax({
                            type: "POST",
                            url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                            cache: false,
                            data: {
                                vehiculo: vehiculo,
                                horaInicio: horaInicio,
                                horaFin: horaFin,
                                albaran: albaran,
                                fecha: fecha,
                                accion: 'addViaje'
                            }
                        }).done(function (respuesta) {
                            $("#mask").html(respuesta);
                            setTimeout(function () {

                                $("#mask").fadeOut(500);
                                $('.cal').fadeIn();
                                location.reload();

                            }, 3000);

                        })
                            .error(function (xhr) {
                                alert(xhr.status)
                            });

                    });

                    //eliminar evento
                    $(document).on("click", '.eliminar_evento', function (e) {
                        e.preventDefault();
                        var current_p = $(this);
                        $("#respuesta").html("<img src='<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/IMG/loading.gif''>");
                        var id = $(this).attr("rel");
                        $.ajax({
                            type: "POST",
                            url: "<?php echo parent::getUrlRaiz()?>/Controlador/Logistica/ControladorCalendario.php",
                            cache: false,
                            data: {id: id, accion: "borrar_evento"}
                        }).done(function (respuesta2) {
                            $("#respuesta").html(respuesta2);
                            setTimeout(function () {

                                $("#mask").fadeOut(500);
                                $('.cal').fadeIn();
                                location.reload();

                            }, 2000);
                        });
                    });

                    $(document).on("click", ".anterior,.siguiente,.hoyEnlace", function (e) {
                        e.preventDefault();
                        var datos = $(this).attr("rel");
                        var nueva_fecha = datos.split("-");
                        generar_calendario(nueva_fecha[1], nueva_fecha[0]);
                    });

                    /**
                     * A la hora de cambiar el nombre de la empresa , que te busque los trabajadores
                     *
                     * Anas
                     **/
                    $("#nomEmpresa").on("change", function () {
                        try {
                            $("#vacacionesDisfrutadas").html("");

                            var idEmpresa = $(this).val();

                            $.ajax({

                                type: "GET",
                                url: "<?php echo parent::getUrlRaiz()?>/Controlador/Calendario/ControladorCalendario.php",
                                data: {idEmpresa: idEmpresa, accion: "buscarTrab"}

                            })
                                .done(function (respuesta) {

                                    $("#trabajador").html(respuesta);
                                    cargarFechas($("#trabajador option:selected").val());
                                    cargarDisfrutadas($("#trabajador option:selected").val());

                                })
                                .fail(function () {
                                    alert("error");
                                });

                        } catch (err) {
                            alert(err)
                        }

                    });


                    $("#trabajador").on("change", function () { //Aitor IRUNE
                        cargarFechas($("#trabajador option:selected").val());
                        cargarDisfrutadas($("#trabajador option:selected").val());
                    });

                    function cargarFechas(nombre) {
                        try {
                            var trabajador = nombre;

                            $.ajax({
                                type: "GET",
                                url: "<?php echo parent::getUrlRaiz()?>/Controlador/Calendario/ControladorCalendario.php",
                                data: {trabajador: trabajador, accion: "buscarCalend"}
                            })
                                .done(function (respuesta) {

                                    $("#vacOpen").html(respuesta);

                                })
                                .fail(function () {
                                    alert("error");
                                });
                        } catch (err) {
                            alert(err)
                        }
                    }

                    function cargarDisfrutadas(nombre) {

                        try {

                            var trabajador = nombre;

                            $.ajax({
                                type: "GET",
                                url: "<?php echo parent::getUrlRaiz()?>/Controlador/Calendario/ControladorCalendario.php",
                                data: {trabajador: trabajador, accion: "buscarDisfrutadas"}
                            })
                                .done(function (respuesta) {

                                    $("#vacacionesDisfrutadas").html(respuesta);

                                })
                                .fail(function () {
                                    alert("error");
                                });
                        } catch (err) {
                            alert(err)
                        }
                    }


                    /**
                     * Comprobar lo que ha escogido en el rango
                     *
                     * Anas
                     */
                    $("input[name='rangoVacaciones']").change(function () {

                        if ($(this).val() == "rango") {

                            $("#fechas").css("visibility", "visible ");

                        } else {

                            $("#fechas").css("visibility", "hidden");

                        }
                    });

                    function actualizarEstado() {

                        $.ajax({
                            type: "GET",
                            url: "<?php echo parent::getUrlRaiz()?>/Controlador/Calendario/ControladorCalendario.php",
                            data: {trabajador: $("#trabajador option:selected").val() , accion: "actualizarEstado"}
                        })
                            .fail(function () {
                                alert("error");
                            });
                    }

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
        require_once __DIR__."/../Plantilla/pie.php";?>
        <style>
            footer{
                margin-top: 100px;
            }
        </style>
<?php
    }
}

