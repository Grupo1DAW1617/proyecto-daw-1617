<?php
/**
 * Created by PhpStorm.
 * User: virux
 * Date: 07/03/2017
 * Time: 10:41
 */

namespace Modelo\BD;
require_once __DIR__."/GenericoBD.php";

abstract class FestivoNacionalBD extends GenericoBD{

    public static function insertarFestivos($festivos){

        $con = parent::conectar();

        $query = "INSERT INTO festivosnacional(fecha,motivo,calendario_id) VALUES ('".$festivos->getFecha()."','".$festivos->getMotivo()."','".$festivos->getCalendario()."')";


        $rs = mysqli_query($con, $query) or die(mysqli_error($con));

        if(mysqli_affected_rows($con) == 1){
            return true;
        }else{
            return false;
        }


    }

    public static function getFestivoNacional($calendario){ //Aitor
        $conexion = parent::conectar();

        $query="SELECT fecha FROM festivosnacional WHERE calendario_id=".$calendario;
        $rs=mysqli_query($conexion, $query) or die("error add festivonacional");
        $fila=mysqli_fetch_array($rs);
        $fechasnacionales=array();
        while($fila!=null){
            array_push($fechasnacionales, $fila['fecha']);
            $fila=mysqli_fetch_array($rs);
        }
        parent::desconectar($conexion);
        return $fechasnacionales;
    }

}