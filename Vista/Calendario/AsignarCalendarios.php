<?php
require_once __DIR__.'/../Plantilla/Views.php';

use Vista\Plantilla;
abstract class AsignarCalendarios extends Plantilla\Views{      //Aitor
    public static function generar(){

        $texto = $_SESSION["trabj"];
        parent::setOn(true);
        parent::setRoot(true);

        require_once __DIR__."/../Plantilla/cabecera.php";


        ?>
        <!--Aitor-->
        <link type="text/css" rel="stylesheet" media="all" href="http://192.168.33.10/Vista/Plantilla/CSS/Bootstrap/estilos.css">


        <form action="../../Controlador/Calendario/ControladorCalendario.php" method="post">

            <h3>Asignar calendario al trabajador con DNI: <strong><?php echo $texto; ?></strong></h3>

            <div class="form-group row" style="padding: 0; margin-left: -15px">
                <h4 class="col-sm-2">Calendario: </h4>
                <div class="col-sm-2">
                    <select name="calendario" class="form-control">
                        <?php
                        $calendarios=\Modelo\BD\CalendarioBD::getCalendClose();
                        while ($rows=mysqli_fetch_array($calendarios)){
                            echo "<option value='".$rows["id"]."'>".$rows["id"]."</option>";
                        };
                        ?>
                    </select>
                </div>
            </div>
            <input class="btn btn-warning" type='submit' name='volver' value='Volver'>
            <input class="btn btn-success" type='submit' name='asignarCalend' value='Asignar'/>

        </form>

        <?php
        require_once __DIR__."/../Plantilla/pie.php";
    }

}


