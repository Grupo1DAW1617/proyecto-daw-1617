<?php
namespace Modelo\BD;
/**
 * Created by PhpStorm.
 * User: Jon
 * Date: 28/02/2016
 * Time: 20:00
 */
use Modelo\Base\Produccion;

require_once __DIR__."/GenericoBD.php";
abstract class ParteProduccionBD extends GenericoBD
{
    private static $tabla = "partesproduccion";

    public static function getParteById($id){
        $conexion = parent::conectar();

        $select = "SELECT * FROM ".self::$tabla." WHERE id = ".$id.";";

        $resultado = mysqli_query($conexion,$select) or die("Error getParteById - ".mysqli_error($conexion));

        $partes = parent::mapear($resultado,"ParteProduccion");

        parent::desconectar($conexion);

        return $partes;
    }

    public static function getAllByTrabajador($trabajador){

        $conexion = GenericoBD::conectar();

        $select = "SELECT * FROM ".self::$tabla." WHERE dniTrabajador = '".$trabajador->getDni()."' order by idEstado DESC, dniTrabajador";

        $resultado = mysqli_query($conexion,$select) or die(mysqli_error($conexion));

        $partes = GenericoBD::mapearArray($resultado,"ParteProduccion");

        GenericoBD::desconectar($conexion);

        return $partes;
    }

    public static function getParteByFecha($trabajador,$fechaSemana){
        $conexion = GenericoBD::conectar();

        $select = "SELECT * FROM '".self::$tabla."' WHERE dniTrabajador = '".$trabajador->getDni()."' AND fecha = '".$fechaSemana."';";

        $resultado = mysqli_query($conexion,$select);

        $partes = GenericoBD::mapearArray($resultado,"PaarteProduccion");


        GenericoBD::desconectar($conexion);

        return $partes;
    }
    public static function getBooleanByParteFecha($trabajador,$fechadia){
        $conexion = GenericoBD::conectar();

        $select = "SELECT * FROM '".self::$tabla."' WHERE dniTrabajador = '".$trabajador->getDni()."' AND fecha = '".$fechadia."';";

        $resultado = mysqli_query($conexion,$select);

        $partes = GenericoBD::mapearArray($resultado,"ParteProduccion");

        if(is_null($partes)){
            GenericoBD::desconectar($conexion);
            return false;
        }else{
            GenericoBD::desconectar($conexion);
            return true;
        }

    }
    public static function getPartebyTrabajadorAndFecha($trabajador,$fecha){
        $conexion = parent::conectar();

        $select = "SELECT * FROM ".self::$tabla." WHERE dniTrabajador = '".$trabajador->getDni()."' AND fecha = '".$fecha."';";

        $resultado = mysqli_query($conexion,$select)or die("Error getParteByTrabajadorAndFecha - ".mysqli_error($conexion));

        $parte = parent::mapear($resultado,"ParteProduccion");

        parent::desconectar($conexion);

        return $parte;

    }

    public static function getParteByHorarioParte($horarioparte){
        $con = parent::conectar();

        $query = "SELECT * FROM ".self::$tabla." WHERE id= ".$horarioparte->getParteProduccion()->getId();

        $rs = mysqli_query($con, $query) or die("Error getParteByHorarioParte");

        $horariosParte = parent::mapear($rs, "ParteProduccion");

        parent::desconectar($con);

        return $horariosParte;

    }

    public static function save($parteProduccion){

        $conexion = GenericoBD::conectar();

        $insert = "INSERT INTO ".self::$tabla." VALUES (null,'".$parteProduccion->getFecha()."','".$parteProduccion->getIncidencia()."','".$parteProduccion->getAutopista()."','".$parteProduccion->getDieta()."','".$parteProduccion->getOtroGasto()."',".$parteProduccion->getEstado()->getId().",'".$parteProduccion->getTrabajador()->getDni()."',null);";

        $res = mysqli_query($conexion,$insert) or die("Error InsertParteProduccion - ".mysqli_error($conexion));

        if($res){
            parent::desconectar($conexion);
            return "Tarea insertada correctamente";

        }

            parent::desconectar($conexion);
    }

    public static function updateDatosParte($parte)
    {
        $conexion = GenericoBD::conectar();

        $update = "UPDATE ".self::$tabla." SET incidencia='".$parte->getIncidencia()."',autopista='".$parte->getAutopista()."',dieta='".$parte->getDieta()."',otroGasto='".$parte->getOtroGasto()."' WHERE id=".$parte->getId();

        mysqli_query($conexion,$update) or die("Error UpdateDatosParte");

        GenericoBD::desconectar($conexion);

        $horariosParte = $parte->getHorariosParte();

        foreach ($horariosParte as $horarioParte)
        {
            HorarioParteBD::update($horarioParte);
        }
    }

    public static function updateValidar($parteId){
        $conexion = GenericoBD::conectar();

        $update = "UPDATE ".self::$tabla." SET idEstado = '3' WHERE id = '".$parteId."';";

        mysqli_query($conexion,$update) or die("Error UpdateParteProduccion");

        GenericoBD::desconectar($conexion);
    }
    public static function updateCerrar($parteId){
        $conexion = GenericoBD::conectar();

        $update = "UPDATE ".self::$tabla." SET idEstado = '2' WHERE id = '".$parteId."';";

        mysqli_query($conexion,$update) or die("Error UpdateParteProduccion");

        GenericoBD::desconectar($conexion);
    }
    public static function updateFinalizar($parteId){
        $conexion = GenericoBD::conectar();

        $update = "UPDATE ".self::$tabla." SET idEstado = '4' WHERE id = '".$parteId."';";

        mysqli_query($conexion,$update) or die("Error UpdateParteProduccion");

        GenericoBD::desconectar($conexion);
    }

    public static function saveHorasExtra($parteId,$horas){
        $con = parent::conectar();

        $query = "UPDATE ".self::$tabla." SET horasExtra = ".$horas." WHERE id = '".$parteId."';";

        mysqli_query($con, $query) or die("Error validar");

        parent::desconectar($con);

    }
    public static function updateAbrir($parteId){
        $conexion = GenericoBD::conectar();

        $update = "UPDATE ".self::$tabla." SET idEstado = '1' WHERE id = '".$parteId."';";

        mysqli_query($conexion,$update) or die("Error UpdateParteProduccion");

        GenericoBD::desconectar($conexion);
    }

    public static function cerrarParte($parteProduccion){
        $conexion = GenericoBD::conectar();
        $update = "UPDATE ".self::$tabla." SET incidencia='".$parteProduccion->getIncidencia()."', autopista='".$parteProduccion->getAutopista()."', dieta='".$parteProduccion->getDieta()."', otroGasto='".$parteProduccion->getOtroGasto()."', idEstado='".$parteProduccion->getEstado()->getId()."' WHERE id = '".$parteProduccion->getId()."';";
        $res = mysqli_query($conexion,$update) or die("Error UpdateParteProduccion");
        if($res){
            parent::desconectar($conexion);
            return "Parte modificado correctamente";
        }
        GenericoBD::desconectar($conexion);
    }

    public static function delete($idParte){
        $conexion = GenericoBD::conectar();
        //Correccion Aitor I(Se pasaba solo el id del parte pero quien hizo esto penso que se pasaba el parte entero)
        $query = "DELETE FROM " .self::$tabla. " WHERE id = " .$idParte;

        $res = mysqli_query($conexion,$query) or die("Error DeleteParteProduccion - ".mysqli_error($conexion));

        if($res){
            parent::desconectar($conexion);
            return "Parte eliminado correctamente";
        }

        GenericoBD::desconectar($conexion);
    }
    public static function getAll(){

        $con = parent::conectar();

        $query = "SELECT * FROM ".self::$tabla." order by idEstado DESC, fecha,dniTrabajador";

        $rs = mysqli_query($con, $query) or die("Error getAllPartes");

        $partes = parent::mapearArray($rs, "ParteProduccion");

        parent::desconectar($con);

        return $partes;

    }

    // Alejandra

    public static function getPartes($ano, $fi, $ff, $emp, $cen, $tra, $est, $buscar)
    {
        $con = parent::conectar();
        if($buscar == "partesAnu"){
            if($ano != ""){
                $query = "SELECT e.nombre as 'empresa', c.nombre as 'centro', t.nombre, t.apellido1, t.apellido2, es.tipo as 'estado', p.* FROM " . self::$tabla . " p, trabajadores t, centros c, empresas e, estados es WHERE p.dniTrabajador=t.dni AND  t.idCentro=c.id AND c.idEmpresa=e.id AND p.idEstado=es.id AND YEAR(p.fecha)='$ano'";
            }else{
                $query = "SELECT e.nombre as 'empresa', c.nombre as 'centro', t.nombre, t.apellido1, t.apellido2, es.tipo as 'estado', p.* FROM " . self::$tabla . " p, trabajadores t, centros c, empresas e, estados es WHERE p.dniTrabajador=t.dni AND  t.idCentro=c.id AND c.idEmpresa=e.id AND p.idEstado=es.id AND YEAR(p.fecha) BETWEEN '$fi' AND '$ff'";
            }
        }else{
            if($ano != ""){
                $query = "SELECT e.nombre as 'empresa', c.nombre as 'centro', t.nombre, t.apellido1, t.apellido2, es.tipo as 'estado', p.* FROM " .self::$tabla. " p, trabajadores t, centros c, empresas e, estados es WHERE p.dniTrabajador=t.dni AND  t.idCentro=c.id AND c.idEmpresa=e.id AND p.idEstado=es.id AND MONTH(p.fecha)='$ano'";
            }else{
                $query = "SELECT e.nombre as 'empresa', c.nombre as 'centro', t.nombre, t.apellido1, t.apellido2, es.tipo as 'estado', p.* FROM " .self::$tabla. " p, trabajadores t, centros c, empresas e, estados es WHERE p.dniTrabajador=t.dni AND  t.idCentro=c.id AND c.idEmpresa=e.id AND p.idEstado=es.id AND MONTH(p.fecha) BETWEEN '$fi' AND '$ff'";
            }
        }
        if ($emp != "" && count($emp) != 0) {
            for ($i = 0; $i < count($emp); $i++) {
                if ($i == 0) {
                    $query .= " AND e.id IN (" . $emp[$i]->getId();
                } else {
                    $query .= ", " . $emp[$i]->getId();
                }
            }
            $query .= ")";
            if ($cen != "" && count($cen) != 0) {
                for ($i = 0; $i < count($cen); $i++) {
                    if ($i == 0) {
                        $query .= " AND c.id IN (" . $cen[$i]->getId();
                    } else {
                        $query .= ", " . $cen[$i]->getId();
                    }
                }
                $query .= ")";
                if ($tra != "" && count($tra) != 0) {
                    for ($i = 0; $i < count($tra); $i++) {
                        if ($i == 0) {
                            $query .= " AND t.dni IN ('" . $tra[$i]->getDni();
                        } else {
                            $query .= "', '" . $tra[$i]->getDni();
                        }
                    }
                    $query .= "')";
                    if ($est != "" && count($est) != 0) {
                        for ($i = 0; $i < count($est); $i++) {
                            if ($i == 0) {
                                $query .= " AND p.idEstado IN (" . $est[$i]->getId();
                            } else {
                                $query .= ", " . $est[$i]->getId();
                            }
                        }
                        $query .= ")";
                    }
                }
            }
        }
        $query .= " ORDER BY e.nombre";
        $rs = mysqli_query($con, $query) or die(mysqli_error($con) . "Error getPartesPro");

        $partes = array();
        if (mysqli_num_rows($rs) != 0) {
            while ($fila = mysqli_fetch_assoc($rs)) {
                $trabajador = Controlador::getTrabajador($fila["dniTrabajador"]);
                $partes[] = [new ParteProduccion($fila["id"], new Estado($fila["idEstado"], $fila["estado"]), $fila["fecha"], $fila["incidencia"], $fila["autopista"], $fila["dieta"], $fila["otroGasto"], $trabajador, null, null, $fila["horasExtra"]), new Empresa(null, $fila["empresa"]), new Centro(null, $fila["centro"])];
            }
        }
        parent::desconectar($con);
        return $partes;
    }
}