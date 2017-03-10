<?php

namespace Modelo\BD;

require_once __DIR__."/GenericoBD.php";

abstract class AusenciaTrabajadorBD extends GenericoBD
{

    public static function getAusenciasByTrabajador($trabajador){

        $conexion = parent::conectar();

        $query = "SELECT * FROM trabajadoresausencias WHERE dniTrabajador = '".$trabajador->getDni()."'";

        $rs = mysqli_query($conexion, $query) or die(mysqli_error($conexion));

        $ausencias = GenericoBD::mapearArray($rs, "Ausencias");

        parent::desconectar($conexion);

        return $ausencias;

    }

    //David
    public static function setAusencias($ausencia){

        $conexion = parent::conectar();

        $query = "INSERT INTO ausenciastrabajadores (dniTrabajador, idAusencia, fecha, horaInicio, horaFin, calendario_id) VALUES 
        ('".$ausencia->getTrabajador()."', '".$ausencia->getAusencia()."','".$ausencia->getFecha()."','".$ausencia->getHoraInicio()."','".$ausencia->getHoraFin()."', ".$ausencia->getCalendario().")";

        $rs = mysqli_query($conexion, $query);

        parent::desconectar($conexion);

        return $rs;

    }

}