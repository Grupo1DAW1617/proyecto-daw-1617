<?php

require_once __DIR__.'/../../Modelo/BD/GenericoBD.php';;
require_once __DIR__.'/../Plantilla/Views.php';

use Vista\Plantilla;
abstract class CalendarioGestionarCalendario extends Plantilla\Views
{
    public static function cal($comprobar){
        parent::setOn(true);
        parent::setRoot($comprobar);
        require_once __DIR__."/../Plantilla/cabecera.php";
        ?>


        <link type="text/css" rel="stylesheet" media="all"
              href="<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/CSS/Bootstrap/estilos.css"
              xmlns="http://www.w3.org/1999/html">

        <div class="container">

    <!-- IRUNE -->

        <form class="col-sm-6" name="buscar" action="<?php echo parent::getUrlRaiz()?>/Vista/Administracion/Administracion.php?cod=6" method="post">

            <h2>Crear un calendario</h2>
            <div class="form-group col-sm-12" style="padding: 0; margin-left: -15px">
                <h4 class="col-sm-4">Calendarios: </h4>
                <div class="col-sm-5">
                    <select name="calendario" class="form-control"><?php
                        $anoActual = date(Y);
                        echo "<option value=''> -- Selecciona -- </option>";
                        for($x=0; $x<9; $x++){
                            $ano = $anoActual + $x;
                            echo "<option value='$ano'>$ano</option>";
                        }
                        ?>
                    </select>
                </div>

            </div>
            <div class="form-group col-sm-12" style="padding: 0; margin-left: -15px">
                <h4 class="col-sm-4">Descripci&oacute;n: </h4>
                <textarea class="col-sm-5" name="descripcion" placeholder="Introduce la descripción aquí"></textarea><br>
            </div>
            <input class="btn btn-primary" type="submit" name="crear" value="Crear">

        </form>

        <form class="col-sm-6" name="buscar" action="<?php echo parent::getUrlRaiz()?>/Vista/Administracion/Administracion.php?cod=7" method="post"> <!--Aitor-->

            <h2>Cerrar un calendario</h2>
            <div class="form-group col-sm-12" style="padding: 0; margin-left: -15px">
                <h4 class="col-sm-4">Calendarios: </h4>
                <div class="col-sm-5">
                    <select name="calendario" class="form-control"><?php
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
            <br>
            <input class="btn btn-warning" type="submit" name="cerrar" value="Cerrar">

        </form>

        </div>

        <script src="<?php echo parent::getUrlRaiz();?>/Vista/Plantilla/JS/jquery-2.2.1.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/localization/messages_es.js "></script>


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

