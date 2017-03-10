<?php
/**
 * Created by PhpStorm.
 * User: 2gdaw12
 * Date: 2/3/17
 * Time: 9:02
 */

//David

namespace Vista\Administracion;

use \Controlador\Administracion;
use Controlador\Login\Controlador;
use Modelo\BD;

require_once __DIR__ . "/../Plantilla/Views.php";
require_once __DIR__ . "/../../Controlador/Administracion/Controlador.php";
require_once __DIR__.'/../../Modelo/BD/GenericoBD.php';
require_once __DIR__.'/../../Modelo/BD/CalendarioBD.php';

abstract class Incidencias extends \Vista\Plantilla\Views
{

    public static function mostrar(){
        parent::setOn(true);
        parent::setRoot(true);
        require_once __DIR__ . "/../Plantilla/cabecera.php";

        ?>
        <form class="form-horizontal" name="insertarIncidencia" method="post"
              action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
            <div class="form-group text-center">
                <label class="control-label col-sm-5 col-md-5">DNI trabajador: </label>
                <div class="col-sm-4 col-md-3">
                    <input class="form-control" type="text" name="dni" maxlength="9" data-validetta="required,regExp[validarDni]">
                </div>
            </div>
            <div class="form-group text-center">
                <label class="control-label col-sm-5 col-md-5">Motivo: </label>
                <div class="col-sm-4 col-md-3">
                    <select class="form-control" name="motivo">
                        <option value="1">Permiso</option>
                        <option value="2">Enfermedad profesional</option>
                        <option value="3">Enfermedad comun</option>
                        <option value="4">Otro</option>
                    </select>
                </div>
            </div>
            <div class="form-group text-center">
                <div class="col-sm-12">
                    <input type="submit" name="insertarIncidencia" class="btn btn-primary enviar" value="Guardar">
                </div>
            </div>
        </form>

        <?php

        require_once __DIR__ . "/../Plantilla/pie.php";
    }

}


