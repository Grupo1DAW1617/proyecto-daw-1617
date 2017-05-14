<?php
/**
 *
 * Anas
 */
namespace Modelo\BD;
use Controlador\Gerencia\Controlador;
use Modelo\Base\Calendario;
use Modelo\Base\Centro;
use Modelo\Base\Empresa;
use Modelo\Base\Estado;
use Modelo\Base\VacacionesTrabajadores;

require_once __DIR__."/GenericoBD.php";

abstract class VacacionesTrabajadoresBD extends GenericoBD{

    private static $tabla = "vacacionestrabajadores";

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

    // Alejandra

    public static function getVacaciones($ano, $fi, $ff, $emp, $cen, $tra, $est, $buscar){
        $con = parent::conectar();
        if($buscar == "vacaciones"){
            if($ano != ""){                                                                                                     //desc es una palabra reservada: se tendria que poner ca.[desc] ????
                $query = "SELECT e.nombre as 'empresa', c.nombre as 'centro', t.nombre, t.apellido1, t.apellido2, ca.desc as 'calendario', v.* FROM " .self::$tabla. " v, trabajadores t, centros c, empresas e, calendario ca WHERE v.dniTrabajador=t.dni AND  t.idCentro=c.id AND c.idEmpresa=e.id AND v.calendario_id=ca.id AND DATE(v.fecha)='$ano'";
            }else{
                $query = "SELECT e.nombre as 'empresa', c.nombre as 'centro', t.nombre, t.apellido1, t.apellido2, ca.desc as 'calendario', v.* FROM " .self::$tabla. " v, trabajadores t, centros c, empresas e, calendario ca WHERE v.dniTrabajador=t.dni AND  t.idCentro=c.id AND c.idEmpresa=e.id AND v.calendario_id=ca.id AND DATE(v.fecha) BETWEEN '$fi' AND '$ff'";
            }
        }else{
            if($ano != ""){
                $query = "SELECT e.nombre as 'empresa', c.nombre as 'centro', t.nombre, t.apellido1, t.apellido2, ca.desc as 'calendario', v.* FROM " .self::$tabla. " v, trabajadores t, centros c, empresas e, calendario ca WHERE v.dniTrabajador=t.dni AND  t.idCentro=c.id AND c.idEmpresa=e.id AND v.calendario_id=ca.id AND YEAR(v.fecha)='$ano'";
            }else{
                $query = "SELECT e.nombre as 'empresa', c.nombre as 'centro', t.nombre, t.apellido1, t.apellido2, ca.desc as 'calendario', v.* FROM " .self::$tabla. " v, trabajadores t, centros c, empresas e, calendario ca WHERE v.dniTrabajador=t.dni AND  t.idCentro=c.id AND c.idEmpresa=e.id AND v.calendario_id=ca.id AND YEAR(v.fecha) BETWEEN '$fi' AND '$ff'";
            }
        }

        if($emp != "" && count($emp) != 0){
            for($i=0; $i<count($emp); $i++){
                if($i == 0){
                    $query .= " AND e.id IN (".$emp[$i]->getId();
                }else{
                    $query .= ", " . $emp[$i]->getId();
                }
            }
            $query .= ")";
            if($cen != "" && count($cen) != 0){
                for($i=0; $i<count($cen); $i++){
                    if($i == 0){
                        $query .= " AND c.id IN (".$cen[$i]->getId();
                    }else{
                        $query .= ", " . $cen[$i]->getId();
                    }
                }
                $query .= ")";
                if($tra != "" && count($tra) != 0){
                    for($i=0; $i<count($tra); $i++){
                        if($i == 0){
                            $query .= " AND t.dni IN ('".$tra[$i]->getDni();
                        }else{
                            $query .= "', '" . $tra[$i]->getDni();
                        }
                    }
                    $query .= "')";
                    if($est != "" && count($est) != 0){
                        for($i=0; $i<count($est); $i++){
                            if($i == 0){
                                $query .= " AND v.estado IN ('".$est[$i]->getTipo();
                            }else{
                                $query .= "', '" . $est[$i]->getTipo();
                            }
                        }
                        $query .= "')";
                    }
                }
            }
        }
        $query .= " ORDER BY e.nombre";
        $rs = mysqli_query($con ,$query) or die(mysqli_error($con) . "Error getVacasTrab");
        $vacaciones = [];
        if (mysqli_num_rows($rs) != 0) {
            while ($fila = mysqli_fetch_assoc($rs)) {
                $trabajador =  Controlador::getTrabajador($fila["dniTrabajador"]);
                //$vacaciones[] = [new VacacionesTrabajadores($fila["id"], $trabajador, $fila["fecha"], $fila["horaInicio"], $fila["horaFin"], new Calendario(null, $fila["calendario"]), new Estado(null, $fila["estado"])), new Empresa(null, $fila["empresa"]), new Centro(null, $fila["centro"])];
                $vacaciones[] = [new VacacionesTrabajadores($fila["id"], null, $fila["fecha"], $fila["horaInicio"], $fila["horaFin"], new Calendario(null, $fila["calendario"]), new Estado(null, $fila["estado"])), new Empresa(null, $fila["empresa"]), new Centro(null, $fila["centro"]), $trabajador];
            }
        }
        parent::desconectar($con);
        return $vacaciones;
    }

}