<?php
/**
 *
 * Anas
 */
namespace Modelo\BD;
require_once __DIR__."/GenericoBD.php";

abstract class VacacionesTrabajadoresBD extends GenericoBD{

    public static function insertarVacacionesTrabajadores($vacacionesTrab){

        $con = parent::conectar();

        $query = "INSERT INTO vacacionestrabajadores(dniTrabajador,fecha,horaInicio,horaFin,calendario_id,estado) VALUES ('".$vacacionesTrab->getDniTrabajador()."','".$vacacionesTrab->getFecha()."','".$vacacionesTrab->getHoraInicio()."','".$vacacionesTrab->getHoraFin()."','".intval($vacacionesTrab->getCalendario())."','".$vacacionesTrab->getEstado()."')";

        $rs = mysqli_query($con, $query) or die(mysqli_error($con));

        if(mysqli_affected_rows($con) == 1){
            parent::desconectar($con);
            return true;
        }else{
            parent::desconectar($con);
            return false;
        }
    }

    /**
     * @param $dni
     * @param $ano
     * @return bool
     * Comprobar si el dia es festivo o no , para pintarlo con rojo
     *
     * Anas
     */
    public static function buscarFestivosDia($dni,$ano){
        $con = parent::conectar();

        $query = "SELECT * FROM vacacionestrabajadores WHERE dniTrabajador = '".$dni."' and fecha = '".$ano."' ";
        $rs = mysqli_query($con, $query) or die(mysqli_error($con));

        if(mysqli_affected_rows($con) >0){
            parent::desconectar($con);
            $fila = mysqli_fetch_array($rs);
            return $fila["estado"];
        }else{
            parent::desconectar($con);
            return false;
        }

    }

    public static function seleccionarVacaciones($vacaciones){
        $con = parent::conectar();

        $query = "SELECT * FROM vacacionestrabajadores WHERE dniTrabajador = '".$vacaciones->getDniTrabajador()."' and fecha = '".$vacaciones->getFecha()."' ";
        $rs = mysqli_query($con, $query) or die(mysqli_error($con));

        if(mysqli_affected_rows($con) >0){
            parent::desconectar($con);
            $fila = mysqli_fetch_array($rs);
            return $fila["estado"];
        }else{
            parent::desconectar($con);
            return false;
        }
    }

    public static function updateVacaciones($dni,$fecha,$estado){
        $con = parent::conectar();

        $query = "UPDATE vacacionestrabajadores SET estado = '".$estado."' WHERE dniTrabajador = '".$dni."' AND fecha = '".$fecha."'";
        $rs = mysqli_query($con, $query) or die(mysqli_error($con));

        if($query){
            parent::desconectar($con);
            return true;
        }else{
            parent::desconectar($con);
            return false;
        }
    }

}