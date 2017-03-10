<?php
/**
 * Created by PhpStorm.
 * User: 2gdwes10
 * Date: 29/2/16
 * Time: 12:42
 */

namespace Modelo\BD;

use Modelo\Base;
require_once __DIR__."/GenericoBD.php";

abstract class HorarioTrabajadorBD extends GenericoBD{

    private static $tabla="horariotrabajadores";

    public static function getHorarioTrabajadorByTrabajador($trabajador){

        $conexion=parent::conectar();
        $query="SELECT * FROM ".self::$tabla." WHERE dniTrabajador='".$trabajador->getDni()."'";
        $rs=mysqli_query($conexion,$query) or die(mysqli_error($conexion));
        $respuesta=parent::mapear($rs,"HorarioTrabajador");
        parent::desconectar($conexion);
        return $respuesta;

    }

    public static function getHorarioTrabajadorByTrabajadorBySemana($trabajador,$semana){

        $conexion=parent::conectar();
        $query="SELECT * FROM ".self::$tabla." WHERE dniTrabajador='".$trabajador->getDni()."' and numeroSemana=".$semana;
        $rs=mysqli_query($conexion,$query) or die(mysqli_error($conexion));
        $respuesta=parent::mapear($rs,"HorarioTrabajador");
        parent::desconectar($conexion);
        return $respuesta;

    }


    public static function getHorarioTrabajadorById($trabajadorId){

        $con = parent::conectar();

        $query = "SELECT * FROM ".self::$tabla." WHERE id = ".$trabajadorId;

        $rs = mysqli_query($con, $query) or die("Error getCentrosByEmpresa");

        $horarioTrabajador = parent::mapear($rs, "HorarioTrabajador");

        parent::desconectar($con);

        return $horarioTrabajador;

    }

    public static function add($horarioTrabajador){ //Ibai
        $con = parent::conectar();

        $query = "INSERT INTO ".self::$tabla." VALUES(null,'".$horarioTrabajador->getTrabajador()->getDni()."','".$horarioTrabajador->getHorario()->getId()."','".$horarioTrabajador->getNumeroSemana()."','".$horarioTrabajador->getCalendario()->getId()."')";

        mysqli_query($con, $query) or die($query);

        parent::desconectar($con);
    }

    public static function getAll($trabajador = null){

        $con = parent::conectar();

        if (is_null($trabajador))
        {
            $query = "SELECT * FROM ".self::$tabla." ORDER BY dniTrabajador,numeroSemana";
        }
        else
        {
            $query = "SELECT * FROM ".self::$tabla." where dniTrabajador='".$trabajador->getDni()."' ORDER BY numeroSemana";
        }

        $rs = mysqli_query($con, $query) or die("Error getAll horariosTrabajador");

        echo $query;

        $horarioTrabajador = parent::mapearArray($rs, "HorarioTrabajador");

        parent::desconectar($con);

        return $horarioTrabajador;
    }

    public static function getAllFiltrado($filtros){ //Ibai
        $con = parent::conectar();
        $query = "SELECT * FROM ".self::$tabla;
        $dnis = [];
        $filtrando = false; //Variable para comprobar si ya se está aplicando un filtro en la query
        if(isset($filtros["empresa"])||isset($filtros["centro"])||isset($filtros["calendario"])||isset($filtros["mes"])){
            $query .= " WHERE";
            $querysecundaria = "";
            if(isset($filtros["centro"]) || isset($filtros["empresa"])) {
                if (isset($filtros["centro"]))
                    $querySecundaria = "SELECT dni FROM trabajadores WHERE idCentro = " . $filtros["centro"];
                else if (!isset($filtros["centro"]) && isset($filtros["empresa"]))
                    $querySecundaria = "SELECT dni FROM trabajadores WHERE idCentro IN(SELECT id FROM centros WHERE idEmpresa = " . $filtros["empresa"] . ")";
                $rs = mysqli_query($con, $querySecundaria) or die("Error en getAllFiltrados horario trabajadores");

                while ($fila = mysqli_fetch_array($rs)) {
                    array_push($dnis, $fila["dni"]);
                }

                if (count($dnis) > 0) {
                    $query .= " dniTrabajador IN (";
                    for ($i = 0; $i < count($dnis); $i++) {
                        if ($i == 0) {
                            $query .= "'".$dnis[$i]."'";
                        } else {
                            $query .= ", " . "'".$dnis[$i]."'";
                        }
                    }
                    $query .= ")";
                }
                $filtrando = true;
            }
            if(isset($filtros["calendario"])) {
                $query.=$filtrando?" AND":"";
                $query .= " calendario_id = ".$filtros["calendario"];
                $filtrando = true;
            }
            if(isset($filtros["mes"])) {
                $query.=$filtrando?" AND":"";
                $primerdia = date("Y-m-d",strtotime((isset($filtros["calendario"])?$filtros["calendario"]:date("Y"))."-".$filtros["mes"]."-01"));
                $ultimodia = date("Y-m-t",strtotime((isset($filtros["calendario"])?$filtros["calendario"]:date("Y"))."-".$filtros["mes"]."-01"));
                $semanas = [date("W",strtotime($primerdia)),date("W",strtotime($ultimodia))];
                if($semanas[0]>50)
                    $semanas[0] = 1;
                if($semanas[1] == 1)
                    $semanas[1] = 53;
                if($semanas[0][0] == "0")
                    $semanas[0] = $semanas[0][1];
                if($semanas[1][0] == "0")
                    $semanas[1] = $semanas[1][1];
                $query .= " numeroSemana  between ".$semanas[0]." and ".$semanas[1];
            }
        }

        $query .= " ORDER BY dniTrabajador,numeroSemana";
        $rs = mysqli_query($con, $query) or die($query);

        $horarioTrabajador = parent::mapearArray($rs, "HorarioTrabajador");

        parent::desconectar($con);
        return $horarioTrabajador;
    }


    public static function delete($id){
        $con = parent::conectar();

        $query = "DELETE FROM ".self::$tabla." WHERE id= ".$id.";";

        mysqli_query($con, $query) or die("Error delete horarioTrabajador");

        parent::desconectar($con);

    }
	public static function updateHorarioTrabajador($horario, $dni, $semana){
		
		$con= parent::conectar();
		
		$update= "UPDATE horariotrabajadores SET idHorario = '".$horario."' WHERE dniTrabajador='".$dni."' AND numeroSemana='".$semana."'";
		
		mysqli_query($con, $update) or die ("Error updateHT");
		
		parent::desconectar($con);
		
	}
    public static function checkIncidencias($dni,$semana,$calendario){ //Ibai
        $con= parent::conectar();
        $incidencias = false;
        if(strlen($semana)<2)
            $semana = "0".$semana;
        //Primer dia de la semana
        $date1 = date( 'Y-m-d', strtotime($calendario."W".$semana."1") );
        //Ultimo dia de la semana
        $date2 = date( 'Y-m-d', strtotime($calendario."W".$semana."7") );
        $query = "SELECT id FROM vacacionestrabajadores WHERE dniTrabajador = '".$dni."' and fecha between '".$date1."' AND '".$date2."'";
        $rs = mysqli_query($con, $query) or die(mysqli_error($con));
        if(mysqli_affected_rows($con) >0)
            $incidencias = true;
        $query = "SELECT id FROM ausenciastrabajadores WHERE upper(dniTrabajador) = upper('".$dni."') and fecha between '".$date1."' AND '".$date2."'";
        $rs = mysqli_query($con, $query) or die(mysqli_error($con));
        if(mysqli_affected_rows($con) >0)
            $incidencias = true;

        parent::desconectar($con);
        return $incidencias;
    }
}
