<?php
namespace Modelo\BD;
/**
 * Created by PhpStorm.
 * User: Jon
 * Date: 28/02/2016
 * Time: 20:03
 */
require_once __DIR__."/GenericoBD.php";
abstract class ParteProduccionTareaBD extends GenericoBD
{

    private static $tabla = "partesproducciontareas";

    public static function getAllByParte($parte){

        $conexion = GenericoBD::conectar();

        $select = "SELECT * FROM ".self::$tabla." WHERE idParteProduccion = ".$parte->getId().";";

        $resultado = mysqli_query($conexion,$select);

        $partes = GenericoBD::mapearArray($resultado,"ParteProduccionTarea");


        GenericoBD::desconectar($conexion);

        return $partes;

    }
    public static function save($ParteProduccionTarea){

        $conexion = parent::conectar();

        $insert = "INSERT INTO ".self::$tabla." (idTareas,idParteProduccion,numeroHoras,paqueteEntrada,paqueteSalida) VALUES (".$ParteProduccionTarea->getTarea()->getId().",".$ParteProduccionTarea->getParte()->getId().",'".$ParteProduccionTarea->getNumeroHoras()."','".$ParteProduccionTarea->getPaqueteEntrada()."'".",'".$ParteProduccionTarea->getPaqueteSalida()."');";

        $res = mysqli_query($conexion,$insert) or die("Error InsertParteProduccionTarea -".mysqli_error($conexion));

        if($res){
            parent::desconectar($conexion);
            return "Tarea insertada correctamente.";

        }

        parent::desconectar($conexion);
    }

    public static function update($ParteProduccionTarea){

        $conexion = GenericoBD::conectar();

        $update = "UPDATE ".self::$tabla." SET numeroHoras='".$ParteProduccionTarea->getNumeroHoras()."', paqueteEntrada='".$ParteProduccionTarea->getPaqueteEntrada()."', paqueteSalida='".$ParteProduccionTarea->getPaqueteSalida()."', idParteProduccion='".$ParteProduccionTarea->getParte()->getId()."', idTareas='".$ParteProduccionTarea->getTarea()->getId()."' WHERE id = '".$ParteProduccionTarea->getId()."';";
        mysqli_query($conexion,$update) or die("Error UpdateParteProduccionTarea");

        GenericoBD::desconectar($conexion);
    }

    public static function delete($ParteProduccionTarea){
        $conexion = GenericoBD::conectar();

        $delete = "DELETE FROM ".self::$tabla." WHERE id = '".$ParteProduccionTarea->getId()."';";

        mysqli_query($conexion,$delete) or die("Error DeleteParteProduccionTarea");

        GenericoBD::desconectar($conexion);
    }


    //Aitor I
    public static function ModificarParteTarea($id,$idtarea,$numeroHoras, $paqueteEntrada, $paqueteSalida){
        $conn = parent::conectar();
        $query = "update " .self::$tabla . " set idTareas = '$idtarea', numeroHoras = '$numeroHoras', paqueteEntrada = $paqueteEntrada, paqueteSalida = $paqueteSalida where id = $id";
        mysqli_query($conn, $query) or die(mysqli_error($conn));
        parent:: desconectar($conn);
    }
}