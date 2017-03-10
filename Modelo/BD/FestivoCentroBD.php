<?php
/**
 * Created by PhpStorm.
 * User: virux
 * Date: 07/03/2017
 * Time: 12:13
 */

namespace Modelo\BD;
require_once __DIR__."/GenericoBD.php";

abstract class FestivoCentroBD extends GenericoBD{

    public static function insertarCentro($centro){

        $con = parent::conectar();

        $query = "INSERT INTO festivos(fecha,motivo,centros_id,calendario_id) VALUES ('".$centro->getFecha()."','".$centro->getMotivo()."','".$centro->getCentro()."','".$centro->getCalendario()."')";

        $rs = mysqli_query($con, $query) or die(mysqli_error($con));

        if(mysqli_affected_rows($con) == 1){
            return true;
        }else{
            return false;
        }


    }

}