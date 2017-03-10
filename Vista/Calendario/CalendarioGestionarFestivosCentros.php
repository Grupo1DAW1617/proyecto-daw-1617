<?php

require_once __DIR__.'/../../Modelo/BD/GenericoBD.php';;
require_once __DIR__.'/../Plantilla/Views.php';

use Vista\Plantilla;
abstract class CalendarioGestionarFestivosCentros extends Plantilla\Views
{
    public static function cal($comprobar){
        parent::setOn(true);
        parent::setRoot($comprobar);
        require_once __DIR__."/../Plantilla/cabecera.php";
        ?>


        <link type="text/css" rel="stylesheet" media="all" href="<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/CSS/Bootstrap/estilos.css">

        <!-- IRUNE -->

        <form name="buscar" action="<?php echo parent::getUrlRaiz()?>/Vista/Administracion/Administracion.php?cod=8" method="post">

            <h2>Añadir festivos de centro</h2>

            <div class="form-group row" style="padding: 0; margin-left: -15px">
                <h4 class="col-sm-2">Centro: </h4>
                <div class="col-sm-2">
                    <select name="centro" id="cent" class="form-control">
                        <?php
                        require_once "../../Modelo/BD/CentroBD.php";
                        $centros = Modelo\BD\CentroBD::cargarCentros();
                        echo "<option name='centros'>-- Selecciona --</option>";
                        for($x=0; $x<count($centros); $x++){
                            echo "<option value='".$centros[$x]->getId()."'>".$centros[$x]->getNombre()."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row" style="padding: 0; margin-left: -15px">
                <h4 class="col-sm-2">Calendarios: </h4>
                <div class="col-sm-2">
                    <select name="calendarios" id="calend" class="form-control">
                        <?php
                        require_once "../../Modelo/BD/CalendarioBD.php";     //Aitor
                        echo "<option name='calendarios'>-- Selecciona --</option>";  //Aitor
                        $id = \Modelo\BD\CalendarioBD::getIdCalendario();    //Aitor
                        while ($rows=mysqli_fetch_array($id)){              //Aitor
                            echo "<option value='".$rows["id"]."'>".$rows["id"]."</option>";    //Aitor
                        };

                        ?>
                    </select>
                </div>
            </div>


            <form name="rango" >
                <h4><br>
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
                    <div class="form-group">
                        <label for="fInicial"> Desde: </label>  <input type="date" id="fInicial" min="<?php echo date('Y-m-d') ?>" />
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
                    <input type="date" id="calendarioNacionales" min="<?php// echo date('Y-m-d') ?>" onchange="guardarOpcion()">
                    <input type="button" value="Añadir" id="botonNacionales" onclick="guardarFecha()">
                    <div>
                        <input type="button" value="Guardar" onclick="guardarFechas()">
                    </div>
                </div>
                <div style="visibility: hidden"  id="fecha2">
                    <label for="fInicial"> Desde : </label>  <input type="date" id="fInicial" min="<?php// echo date('Y-m-d') ?>"/>  <label for="fFinal"> Hasta : </label>  <input type="date" id="fFinal" min="<?php// echo date('Y-m-d') ?>"/>
                    <input type="button" value="Seleccionar dias" id="rangoDias" name="rangoDias" onclick="guardarRango()"/>
                </div>

            </form>
-->

        </form>

        <?php if ($comprobar){?>

            <div class="calendario_ajax">

                <form name="rango" >
                </form>

                <div style='visibility: hidden' class="cal"></div><div id="mask"></div>
            </div>
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


        <!-- ESTO NO TE HACE FALTA! -->
        <script type="text/javascript">
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
                if(fechas.length == 0 || $('#calend').val() == "-- Selecciona --" || $('#cent').val() == "-- Selecciona --"){
                    alert("No puedes dejar los campos sin seleccionar");
                }else{
                    var fechasEnvio = [];
                    for(var x = 0; x < fechas.length; x++){
                        var f = "" + fechas[x].getFullYear() + "-" + (fechas[x].getMonth() + 1) + "-" + fechas[x].getDate() + " 00:00:00";
                        fechasEnvio.push(f);
                    }
                    var calendario = $('#calend').val();
                    var centro = $('#cent').val();

                    $.ajax({

                        type: "GET",
                        url: "<?php echo parent::getUrlRaiz()?>/Controlador/Calendario/ControladorCalendario.php",
                        data: {fechasEnvio:fechasEnvio , calendario:calendario, centro:centro, accion:"festivosCentros"}
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

