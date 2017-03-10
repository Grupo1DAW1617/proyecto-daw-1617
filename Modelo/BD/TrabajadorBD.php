<?php
namespace Modelo\BD;



use Modelo\Base\Administracion;
use Modelo\Base\Centro;
use Modelo\Base\Gerencia;
use Modelo\Base\Logistica;
use Modelo\Base\Produccion;
use Modelo\BD;
require_once __DIR__."/GenericoBD.php";
require_once __DIR__."/../Base/TrabajadorClass.php";

abstract class TrabajadorBD extends GenericoBD{

    private static $tabla = "trabajadores";

    public static function editarCalendario($trabajador,$valor){
        $con = parent::conectar();

        $query = "UPDATE vacacionestrabajadores SET estado = '".$valor."' WHERE dniTrabajador = '".$trabajador."' AND estado = 'S'";

        $rs = mysqli_query($con, $query) or die("Error getTrabajadoresByCentro");

        $trabajadores = parent::mapear($rs, "Trabajador");

        parent::desconectar($con);
    }

    public static function getTrabajadoresByCentro($centro){

        $con = parent::conectar();

        $query = "SELECT * FROM ".self::$tabla." WHERE idCentro = ".$centro->getId();

        $rs = mysqli_query($con, $query) or die("Error getTrabajadoresByCentro");

        $trabajadores = parent::mapear($rs, "Trabajador");

        parent::desconectar($con);

        return $trabajadores;

    }

    public static function getTodosTrabajadoresByCentro($centro){
        $con = parent::conectar();

        $query = "SELECT * FROM trabajadores WHERE idCentro = ".$centro->getId();

        $rs = mysqli_query($con, $query) or die("Error getTrabajadoresByCentro");

        $trabajadores = [];
        while ($fila = mysqli_fetch_array($rs)){
            /*
             * Segun el id del tipo de perfil que devuelva , genera un tipo de trabajador u otro
             */
            if($fila["idPerfil"] == 1){
                $gerencia = new Gerencia($fila["dni"],$fila["nombre"],$fila["apellido1"],$fila["apellido2"],$fila["telefono"],$fila["foto"],$fila["idCentro"],null,null,null);
                array_push($trabajadores,$gerencia);
            }else if($fila["idPerfil"] == 2){

                $administracion = new Administracion($fila["dni"],$fila["nombre"],$fila["apellido1"],$fila["apellido2"],$fila["telefono"],$fila["foto"],$fila["idCentro"],null,null,null);
                array_push($trabajadores,$administracion);

            }else if($fila["idPerfil"] == 3){

                $produccion = new Produccion($fila["dni"],$fila["nombre"],$fila["apellido1"],$fila["apellido2"],$fila["telefono"],$fila["foto"],$fila["idCentro"],null,null,null);
                array_push($trabajadores,$produccion);

            }else if($fila["idPerfil"] == 4){

                $logistica = new Logistica($fila["dni"],$fila["nombre"],$fila["apellido1"],$fila["apellido2"],$fila["telefono"],$fila["foto"],$fila["idCentro"],null,null,null);
                array_push($trabajadores,$logistica);

            }

        }
        parent::desconectar($con);

        return $trabajadores;

    }

    public static function getTrabajadorByLogin($login){
        $conexion = parent::conectar();
        $query = "SELECT * FROM trabajadores WHERE dni = ".$login->getUsuario() ." ";
        $rs = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
        $trabajador = parent::mapear($rs, "Trabajador");
        parent::desconectar($conexion);
        return $trabajador;
    }

    public static function getTrabajadorByHorariosTrabajadores($horarioTrabajador){

        $conexion = parent::conectar();


        $query="SELECT t.dni,t.nombre,t.apellido1,t.apellido2,t.telefono,t.foto,t.idCentro,p.tipo FROM ".self::$tabla." t,perfiles p where t.idPerfil=p.id and dni= (select dniTrabajador from horariotrabajadores where id=".$horarioTrabajador->getId().") ORDER BY apellido1, apellido2";
        $rs = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
        $trabajador = parent::mapear($rs, null);
        parent::desconectar($conexion);
        return $trabajador;

    }
    public static function getTrabajadorByParte($parte){
        //return $parte;
    }

    public static function getTrabajadorByDni($dni){
        $conexion = parent::conectar();

        $queryPerfil = "SELECT tipo FROM perfiles WHERE id = (SELECT idPerfil FROM trabajadores WHERE dni = '".$dni."')";
        $rsPerfil = mysqli_query($conexion, $queryPerfil) or die(mysqli_error($conexion));
        $fila = mysqli_fetch_array($rsPerfil);
        $perfil = $fila['tipo'];

        $query = "SELECT * FROM trabajadores WHERE dni = '".$dni."'";
        $rs = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
        $trabajador = parent::mapear($rs, $perfil);

        parent::desconectar($conexion);

        return $trabajador;

    }

    public static function add($trabajador){

        $con = parent::conectar();

        //SACAR PERFIL ID///
        $perfil = get_class($trabajador);
        $perfil = substr(strrchr($perfil, "\\"), 1);
        $queryPerfil = "SELECT id FROM perfiles WHERE tipo = '" . $perfil."'";
        $rs = mysqli_query($con, $queryPerfil) or die("ErrorqueryPerfil");
        $fila = mysqli_fetch_array($rs);
        $idPerfil = $fila['id'];
        //////////

        $query = "INSERT INTO ".self::$tabla." VALUES('".$trabajador->getDni()."','".$trabajador->getNombre()."','".$trabajador->getApellido1()."','".$trabajador->getApellido2()."','".$trabajador->getTelefono()."',".$trabajador->getCentro()->getId().",".$idPerfil.",'".$trabajador->getFoto()."')"; //NOTA no hay objeto Perfil usamos getClass?? ----> esto no se puede: $trabajador->getPerfil()->getId()
        mysqli_query($con, $query) or die("Error addTrabajador");

        parent::desconectar($con);

    }

    public function getTareasParteByFecha(){

        $diaSemana = date("N");
        $fechaSemana = date("d/m/Y",strtotime("-$diaSemana day"));

        if(is_null($this->tareasParte)){
            $this->tareasParte = ParteProduccionTareaBD::getTareasByParteAndFecha($this,$fechaSemana);
        }

        return $this->tareasParte;
    }

    public static function deleteTrabajador($dni)
    {

        $con = parent::conectar();

        $query = "DELETE FROM " . self::$tabla . " WHERE dni='".$dni."'";

        mysqli_query($con, $query) or die(mysqli_error($con));

        parent::desconectar($con);
    }

    public static function getAllPerfiles(){

        $con = parent::conectar();

        $query = "SELECT id,tipo FROM perfiles ORDER BY tipo";

        $rs = mysqli_query($con, $query) or die("Error getAllPerfiles");

        $perfil = array();
        while($fila = mysqli_fetch_assoc($rs)){
            $perfil[] = array($fila['id'],$fila['tipo']);
        }

        parent::desconectar($con);

        return $perfil;

    }

    public static function getAllTrabajadores(){

        $con = parent::conectar();

        $query = "SELECT t.dni,t.nombre,t.apellido1,t.apellido2,t.telefono,t.foto,t.idCentro,p.tipo FROM ".self::$tabla." t,perfiles p where t.idPerfil=p.id ORDER BY apellido1, apellido2";

        $rs = mysqli_query($con, $query) or die("Error getAllTipoTrabajadores");

        $trabajadores = parent::mapearArray($rs,null);

        parent::desconectar($con);

        return $trabajadores;

    }

    public static function obtenerPerfil($id){
        $con = parent::conectar();
        $queryPerfil = "SELECT tipo FROM perfiles WHERE id = ".$id;
        $rsPerfil = mysqli_query($con, $queryPerfil) or die("error queryPerfilAllTrabajadores");
        $filaPerfil = mysqli_fetch_array($rsPerfil);
        parent::desconectar($con);
        return $filaPerfil["tipo"];
    }

    public static function updateFotoByTrabajador($trabajador){

        $con = parent::conectar();

        $query = "UPDATE ".self::$tabla." SET foto = '".$trabajador->getFoto()."' WHERE DNI = '".$trabajador->getDni()."'";

        mysqli_query($con, $query) or die("error updateFoto");

        parent::desconectar($con);

    }

    public static function getPerfilByDni($trabajador){
        $conexion = parent::conectar();

        $query = "SELECT tipo FROM perfiles WHERE id = (SELECT idPerfil FROM trabajadores WHERE dni = '".$trabajador->getDni()."')";

        $rs = mysqli_query($conexion, $query) or die("error getPerfilDni");

        $perfil = mysqli_fetch_array($rs);

        parent::desconectar($conexion);

        return $perfil['tipo'];

    }


    /* Alejandra */

    public static function getTrabajadoresByCentros($centros){
        $con = parent::conectar();
        $query = "SELECT t.dni,t.nombre,t.apellido1,t.apellido2,t.telefono,t.foto,t.idCentro,p.tipo FROM himevico.trabajadores t,himevico.perfiles p WHERE t.idPerfil=p.id AND idCentro IN (";
        for($i=0; $i<count($centros); $i++){
            if($i == 0){
                $query .= $centros[$i];
            }else{
                $query .= ", " . $centros[$i];
            }
        }
        $query .= ")";
        $rs = mysqli_query($con, $query) or die("Error getTrabajadoresByCentros");
        $trabajadores = parent::mapearArray($rs, null);
        parent::desconectar($con);
        return $trabajadores;
    }

    public static function getTrabajadoresByCenPer($bi){
        $con = parent::conectar();
        $query = "SELECT t.dni,t.nombre,t.apellido1,t.apellido2,t.telefono,t.foto,t.idCentro,t.idPerfil,p.tipo FROM himevico.trabajadores t,himevico.perfiles p WHERE t.idPerfil=p.id AND idCentro IN (";
        for($i=0; $i<count($bi[0]); $i++){
            if($i == 0){
                $query .= $bi[0][$i];
            }else{
                $query .= ", " . $bi[0][$i];
            }
        }

        $query .= ") AND t.idPerfil IN (";
        for($i=0; $i<count($bi[1]); $i++){
            if($i == 0){
                $query .= $bi[1][$i];
            }else{
                $query .= ", " . $bi[1][$i];
            }
        }
        $query .= ")";
        $rs = mysqli_query($con, $query) or die("Error getTrabajadoresByCenPer");
        $trabajadores = parent::mapearArray($rs, null);
        parent::desconectar($con);
        return $trabajadores;
    }


    public static function getCentroById(){ //Aitor
        $con = parent::conectar();
        $query="SELECT idCentro FROM trabajadores WHERE dni='".$_SESSION["trabj"]."'";
        $rs = mysqli_query($con, $query) or die("Error getCentroById");
        while($rows=mysqli_fetch_array($rs)){
            return $rows[0];
        }
    }

    public static function getPerfLoPro(){
        $con = parent::conectar();
        $query = "SELECT t.dni,p.tipo FROM himevico.trabajadores t,himevico.perfiles p WHERE t.idPerfil=p.id AND idPerfil in (3, 4)";
        $rs = mysqli_query($con, $query) or die("Error getAllPerfiles");
        $perfil = array();
        while($fila = mysqli_fetch_assoc($rs)){
            $perfil[] = array($fila['dni'], $fila['tipo']);
        }
        parent::desconectar($con);
        return $perfil;
    }


    public  static function getDniFromNombre($nombre){ //Aitor
        $con = parent::conectar();
        $query="SELECT dni FROM trabajadores WHERE nombre='".$nombre."'";
        $rs = mysqli_query($con, $query) or die("Error getDni");
        while($rows=mysqli_fetch_array($rs)){
            return $rows[0];
        }
    }
}