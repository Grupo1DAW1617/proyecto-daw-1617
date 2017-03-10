<?php
namespace Controlador\Gerencia;
use Modelo\Base\Administracion;
use Modelo\Base\Calendario;
use Modelo\Base\Centro;
use Modelo\Base\Empresa;
use Modelo\Base\Estado;
use Modelo\Base\Festivo;
use Modelo\Base\Gerencia;
use Modelo\Base\HoraConvenio;
use Modelo\Base\Horarios;
use Modelo\Base\HorariosFranja;
use Modelo\Base\HorariosTrabajadores;
use Modelo\Base\Logistica;
use Modelo\Base\Produccion;
use Modelo\Base\TiposFranjas;
use Modelo\Base\Trabajador;
use Modelo\Base\AusenciaTrabajador;
use Modelo\Base\Vehiculo;
use Modelo\BD;
use Vista\Gerencia\GerenciaViews;

require_once __DIR__."/../../Modelo/BD/RequiresBD.php";
require_once __DIR__ ."/../../Modelo/Base/LogisticaClass.php";
require_once __DIR__ ."/../../Modelo/Base/AdministracionClass.php";
require_once __DIR__ ."/../../Modelo/Base/CalendarioClass.php";
require_once __DIR__ ."/../../Modelo/Base/ProduccionClass.php";
require_once __DIR__ ."/../../Modelo/Base/GerenciaClass.php";
require_once __DIR__ .'/../../Modelo/Base/EstadoClass.php';
require_once __DIR__ .'/../../Modelo/Base/HoraConvenioClass.php';
require_once __DIR__ .'/../../Modelo/Base/HorariosClass.php';
require_once __DIR__."/../../Modelo/BD/LoginBD.php";
require_once __DIR__ .'/../../Modelo/Base/HorariosTrabajadoresClass.php';
require_once __DIR__."/../../Modelo/Base/FestivoClass.php";
require_once __DIR__ ."/../../Vista/Gerencia/GerenciaViews.php";


abstract class Controlador
{

    public static function insertarTrabajador($datos, $file)
    {
        $trabajador = "";

        $centro = BD\CentroBD::getCentrosById($datos['centro']);

        $perfil = $datos['perfil'];

        $datos['dni'] = strtoupper($datos['dni']);
        $datos['nombre'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['nombre'])))));
        $datos['apellido1'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['apellido1'])))));
        $datos['apellido2'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['apellido2'])))));

        switch ($perfil) {
            case "Logistica":
                $trabajador = new Logistica($datos["dni"], $datos['nombre'], $datos['apellido1'], $datos['apellido2'], $datos['telefono'], null/*foto*/, $centro, null, null, null, null);

                break;
            case "Administracion":
                $trabajador = new Administracion($datos["dni"], $datos['nombre'], $datos['apellido1'], $datos['apellido2'], $datos['telefono'], null/*foto*/, $centro, null, null, null);
                break;
            case "Gerencia":
                $trabajador = new Gerencia($datos["dni"], $datos['nombre'], $datos['apellido1'], $datos['apellido2'], $datos['telefono'], null/*foto*/, $centro, null, null, null);
                break;
            case "Produccion":
                $trabajador = new Produccion($datos["dni"], $datos['nombre'], $datos['apellido1'], $datos['apellido2'], $datos['telefono'], null/*foto*/, $centro, null, null, null, null);
                break;
        }


        if (strlen($file['foto']['name']) != 0) {
            self::imagenTrabajador($trabajador, $file);
        } else {
            $trabajador->setFoto("Vista/Fotos/Default/foto.jpg");
        }

        $trabajador->add();

        $md5 = md5($trabajador->getDni());

        BD\LoginBD::add($trabajador, $md5);

    }

    public static function imagenTrabajador($trabajador, $file)
    {

        $x = $trabajador->getDni();

        $url = "Vista/Fotos/" . $x . "/" . $file['foto']['name'];

        self::subirImagen($file, $x);

        $trabajador->setFoto($url);

    }

    public static function subirImagen($file, $x)
    {

        $dir = opendir(__DIR__ . "/../../Vista/Fotos/");

        if (is_uploaded_file($file['foto']['tmp_name'])) {
            if (!file_exists(__DIR__ . "/../../Vista/Fotos/" . $x)) {
                mkdir(__DIR__ . "/../../Vista/Fotos/" . $x);
                chmod(__DIR__ . "/../../Vista/Fotos/" . $x, 0755);

                move_uploaded_file($file['foto']['tmp_name'], __DIR__ . "/../../Vista/Fotos/" . $x . "/" . basename($file['foto']['name']));
            }


            echo "<br>Fichero subido: " . $file['foto']['name'];

        } else {

            return "Error al subir el fichero: " . $file['foto']['name'];

        }
        closedir($dir);

    }

    public static function updateFoto($datos, $file)
    {

        self::eliminarDir(__DIR__ . "/../../Vista/Fotos/" . $datos["trabajador"]);

        $trabajador = BD\TrabajadorBD::getTrabajadorByDni($datos["trabajador"]);

        self::imagenTrabajador($trabajador, $file);

        $trabajadorSession = unserialize($_SESSION["trabajador"]);

        if ($trabajador->getDni() == $trabajadorSession->getDni()) {
            $_SESSION["trabajador"] = serialize($trabajador);
        }

        BD\TrabajadorBD::updateFotoByTrabajador($trabajador);
    }

    public static function getTrabajadorByDni($dni){
        $trabajador = BD\TrabajadorBD::getTrabajadorByDni($dni);
        return $trabajador;
    }

    public static function eliminarDir($carpeta)
    {
        foreach (glob($carpeta . "/*") as $archivos_carpeta) {
            echo $archivos_carpeta;

            if (is_dir($archivos_carpeta)) {
                self::eliminarDir($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        rmdir($carpeta);
    }

    public static function insertarEmpresa($datos)
    {
        //no hay centros en la nueva empresa
        $datos['nombre'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['nombre'])))));
        $datos['nif'] = strtoupper($datos['nif']);
        $empresa = new Empresa(null, $datos['nombre'], $datos['nif'], null);

        $empresa->add();
    }

    public static function deleteEmpresa($datos)
    {
        $empresa = BD\EmpresaBD::getEmpresaByID($datos['id']);
        $empresa->delete();
    }

    public static function getAllEmpresas()
    {
        return BD\EmpresaBD::getAll();
    }

    public static function getAllPerfiles()
    {
        return BD\TrabajadorBD::getAllPerfiles();
    }

    public static function AddVehiculo($datos)
    {
        $centro = BD\CentroBD::getCentrosById($datos["centro"]);
        $datos['matricula'] = strtoupper($datos['matricula']);
        $datos['marca'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['marca'])))));
        $vehiculo = new Vehiculo(null, $datos["matricula"], $datos["marca"], $centro);
        BD\VehiculoBD::add($vehiculo);
    }

    public static function deleteVehiculo($datos)
    {
        BD\VehiculoBD::deletteVehiculo($datos["id"]);
    }

    public static function AddEstado($datos)
    {
        $estado = new Estado(null, $datos["tipo"]);
        BD\EstadoBD::add($estado);
    }

    public static function DeleteEstado($datos)
    {
        BD\EstadoBD::delete($datos["id"]);
    }

    public static function getAllTrabajadores()
    {
        return BD\TrabajadorBD::getAllTrabajadores();
    }

    public static function getAllEstados()
    {
        return BD\EstadoBD::getAll();
    }

    public static function getAllCentros()
    {
        return BD\CentroBD::getAll();
    }

    public static function getAllVehiculos()
    {
        return BD\VehiculoBD::getAll();
    }

    public static function AddHorasConvenio($datos)
    {
        $centro = BD\CentroBD::getCentrosById($datos["centro"]);
        $datos['denominacion'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['denominacion'])))));
        $horaconvenio = new HoraConvenio(null, $datos["horasAnual"], $datos["denominacion"], $centro);
        BD\HorasConvenioBD::add($horaconvenio);
    }

    public static function getAllHorasConvenio()
    {
        return BD\HorasConvenioBD::getAll();
    }

    public static function deleteHorasConvenio($datos)
    {
        BD\HorasConvenioBD::delete($datos["id"]);
    }

    public static function deleteTrabajador($datos)
    {
        $datos['dni'] = strtoupper($datos['dni']);
        BD\LoginBD::deleteLoginByDni($datos["dni"]);
        BD\TrabajadorBD::deleteTrabajador($datos["dni"]);
        self::eliminarDir(__DIR__ . "/../../Vista/Fotos/" . $datos['dni']);
    }

    public static function AddCentro($datos)
    {
        $empresa = BD\EmpresaBD::getEmpresaByID($datos['empresa']);
        $datos['nombre'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['nombre'])))));
        $datos['localizacion'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['localizacion'])))));
        $centro = new Centro(null, $datos['nombre'], $datos['localizacion'], $empresa);
        $centro->add();
    }

    public static function DeleteCentro($datos)
    {
        $centro = BD\CentroBD::getCentrosById($datos['id']);
        $centro->delete();
    }

    public static function getAllTiposFranjas()
    {
        return BD\TipoFranjaBD::getAll();
    }

    public static function updateTipoFranja($datos)
    {

        $tipo = new TiposFranjas($datos['id'], null, $datos['nuevo']);

        BD\TipoFranjaBD::update($tipo);
    }

    public static function AddTipoFranja($datos)
    {
        $datos['tipo'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['tipo'])))));
        $tipo = new TiposFranjas(null, $datos['tipo'], $datos['precio']);
        $tipo->save();
    }

    public static function DeleteTipoFranja($datos)
    {
        \Modelo\BD\TipoFranjaBD::delete($datos['id']);
    }

    public static function UpdateHorasConvenio($datos)
    {
        $horas = new HoraConvenio($datos['id'], $datos['nuevo']); /*error*/

        BD\HorasConvenioBD::UpdateHorasConvenio($horas);
    }

    public static function getAllLogins()
    {
        return BD\LoginBD::getAll();
    }

    public static function updatePassword($datos)
    {
        $datos['password'] = md5($datos['password']);
        BD\LoginBD::changePasswordByDni($datos);
    }

    public static function getAllFranjas()
    {
        return BD\FranjaBD::getAll();
    }

    public static function AddHorario($datos)
    {
        $horario = new Horarios(null, $datos["horario"]);
        $idHorario = BD\HorarioBD::add($horario);
        while ($datos["horaInicio"] != $datos["horaFin"]) {

            $horaioFranja = new HorariosFranja(null, BD\HorarioBD::getHorarioById($idHorario), BD\FranjaBD::getFranjaById($datos["horaInicio"]));
            BD\HorarioFranjaBD::add($horaioFranja);

            if ($datos["horaInicio"] == 24) {
                $datos["horaInicio"] = 1;
            } else {
                $datos["horaInicio"] = $datos["horaInicio"] + 1;
            }
        }
        $horaioFranja = new HorariosFranja(null, BD\HorarioBD::getHorarioById($idHorario), BD\FranjaBD::getFranjaById($datos["horaInicio"]));
        BD\HorarioFranjaBD::add($horaioFranja);

    }

    public static function getAllHorarios()
    {
        return BD\HorarioBD::getAll();
    }

    public static function getHoraInicioHorario($horario){
        $franja = BD\FranjaBD::getHoraInicio($horario->getId());
        return $franja->getHoraInicio();
    }

    public static function getHoraFinHorario($horario){
        $franja = BD\FranjaBD::getHoraFinal($horario->getId());
        return $franja->getHoraFin();
    }

    public static function deleteHorario($datos)
    {
        BD\HorarioBD::delete($datos["id"]);
    }

    public static function addHorarioTrabajador($datos){ //Ibai
        $calendario = new Calendario($datos["calendario"]);
        $horarioTrabajador= new HorariosTrabajadores(null,$datos["semana"], BD\TrabajadorBD::getTrabajadorByDni($datos["trabajador"]),BD\HorarioBD::getHorarioById($datos["horario"]), $calendario);
        BD\HorarioTrabajadorBD::add($horarioTrabajador);
    }

    public static function getAllHoraioTrabajador()
    {
        return BD\HorarioTrabajadorBD::getAll();
    }

    public static function DeleteHorarioTrabajador($datos)
    {
        BD\HorarioTrabajadorBD::delete($datos["id"]);
    }

    public static function getAllPartesProduccion()
    {
        return BD\ParteProduccionBD::getAll();
    }

    public static function getAllPartesLogistica()
    {
        return BD\PartelogisticaBD::getAll();
    }

    public static function DeleteParteProduccion($datos)
    {

        BD\ParteProduccionBD::Delete($datos['id']);
    }

    public static function DeleteParteLogistica($datos)
    {
        BD\PartelogisticaBD::Delete($datos['id']);
    }

    public static function viewParteLog($datos)
    {
        //$trabajador=unserialize($_SESSION['trabajador']);
        $parte = BD\PartelogisticaBD::selectParteLogisticaById($datos['id']);
        $viajes = BD\ViajeBD::getViajeByParte($parte);

        GerenciaViews::viewParteLog($parte, $viajes);

    }

    public static function viewParteProd($datos)
    {
        //$trabajador=unserialize($_SESSION['trabajador']);
        $parte = BD\ParteProduccionBD::getParteById($datos['id']);
        $estado = BD\EstadoBD::selectEstadoByParteProduccion($parte);
        //$viajes=BD\ViajeBD::getViajeByParte($parte);

        GerenciaViews::viewParteProd($parte, $estado);

    }
    public static function updateValidarParteLogistica($datos){
        BD\PartelogisticaBD::updateValidar($datos['id']);
    }
	public static function updateHorarioTrabajador($datos){
		BD\HorarioTrabajadorBD::updateHorarioTrabajador($datos["horario"], $_SESSION["dniht"] , $_SESSION["semht"]);
    }
	public static function getAllHorarioTrabajador(){
        return BD\HorarioTrabajadorBD::getAll();
    }
	public static function getAllHorarioTrabajadorFiltrado($filtros){
        return BD\HorarioTrabajadorBD::getAllFiltrado($filtros);
    }
	public static function incidenciasHorarioTrabajador($dni,$semana,$calendario){
        return BD\HorarioTrabajadorBD::checkIncidencias($dni,$semana,$calendario);
    }
    /* Ganeko */
    public static function buscarEmpresaId($id){
        $empresa =  BD\EmpresaBD::getEmpresaByID($id);
        return $empresa;
    }/* Ganeko */
    public static function getCentroId($id){
        $centro = BD\CentroBD::getCentrosById($id);
        return $centro;
    }/* Ganeko */
    public static function getVehiculoId($id){
        $vehiculo = BD\VehiculoBD::getVehiculosById($id);
        return $vehiculo;
    }/* Ganeko */
    public static function getFranjaById($id){
        $franja = BD\TipoFranjaBD::getTipoFranjaById($id);
        return $franja;
    }/* Ganeko */
    public static function getConvenioById($id){
        $convenio = BD\HorasConvenioBD::selectConvenioById($id);
        return $convenio;
    }/* Ganeko */
    public static function updateEmpresa($datos){
        BD\EmpresaBD::updateEmpresa($datos);
    }
    public static function updateCentro($datos){
        BD\CentroBD::updateCentro($datos);
    }
    public static function updateVehiculo($datos){
        BD\VehiculoBD::updateVehiculo($datos);
    }
    public static function guardarParteProduccion($datos)
    {
        $parte = unserialize($_SESSION['parte']);
    }

    public static function updateFinalizarParteLogistica($datos)
    {
        BD\PartelogisticaBD::saveHorasExtra($datos['id'], $datos['horas']);

        BD\PartelogisticaBD::updateFinalizar($datos['id']);
    }

    public static function updateCerrarParteLogistica($datos)
    {
        BD\PartelogisticaBD::updateCerrar($datos['id']);
    }

    public static function updateFinalizarParteProduccion($datos)
    {
        BD\ParteProduccionBD::saveHorasExtra($datos['id'], $datos['horas']);
        BD\ParteProduccionBD::updateFinalizar($datos['id']);
    }

    public static function updateCerrarParteProduccion($datos)
    {
        BD\ParteProduccionBD::updateCerrar($datos['id']);
    }

    public static function getAllFestivos()
    {
        return BD\FestivoBD::getAll();
    }

    public static function addFestivo($datos)
    {
        $festivo = new Festivo(null, $datos['fecha'], $datos['motivo']);

        BD\FestivoBD::add($festivo);
    }

    public static function deleteFestivo($datos)
    {
        BD\FestivoBD::delete($datos['id']);
    }

    public static function getPerfilByDni($dni)
    {
        $trabajador = new Logistica($dni);
        $perfil = BD\TrabajadorBD::getPerfilByDni($trabajador);


        return $perfil;

    }

    public static function getParte($dni, $perfil)
    {
        $trabajador = new Logistica($dni);

        if ($perfil == "Produccion") {
            return $partes = BD\ParteProduccionBD::getAllByTrabajador($trabajador);
        } elseif ($perfil == "Logistica") {
            return $partes = BD\PartelogisticaBD::getAllByTrabajador($trabajador);
        }

    }

    public static function getAllCalendarios(){
        return BD\CalendarioBD::getAll();
    }


    // Alejandra

    public static function getCentros($e)
    {
        return BD\CentroBD::getCentrsByEmpresas($e);
    }

    /*public static function getTrabajadores($c){
        return BD\TrabajadorBD::getTrabajadorsByCentros($c);
    }*/

    public static function getTrabajadores($arrbi)
    {
        return BD\TrabajadorBD::getTrabajadoresByCenPer($arrbi);
    }

    public static function incidencias($v)
    {
    }

    public static function partesAnuales($v)
    {
        try {
            if ($v["opFecha"] == "fecha") {
                $fecha = $v["fecha"];
                $fechaArr = explode("-", $fecha);
                if ($v['opEmpresa'] == "si") {
                    $emp = [];
                    foreach ($v["empresa"] as $empresa) {
                        $emp[] = new Empresa($empresa);
                    }
                    if (count($emp) != 0) {
                        if ($v["opCentro"] == "si") {
                            $cen = [];
                            foreach ($v["centro"] as $centro) {
                                $cen[] = new Centro($centro);
                            }
                            if (count($cen) != 0) {
                                if ($v["opTrabajador"] == "si") {
                                    $per = [];
                                    foreach ($v["perfil"] as $perfil) {
                                        switch ($perfil) {
                                            case "3":
                                                $per[] = new Produccion();
                                                break;
                                            case "4":
                                                $per[] = new Logistica();
                                                break;
                                            default:
                                                throw new \Exception("<script>smoke.signal('Solo realizar partes los de Logistica y Produccion', function (e) {null;}, { duration: 2000 } );</script>");

                                        }
                                    }
                                    if (count($per) != 0) {
                                        $tra = [];
                                        $perDni = self::perfilesLogisPro();
                                        foreach ($v["trabajador"] as $trabajador) {
                                            $i = 0;
                                            for ($i; $i < count($perDni) && $perDni[$i][0] == $trabajador; $i++) ;
                                            if ($i < count($perDni)) {
                                                $encontrado = false;
                                                for ($x = 0; $x < count($per) && $encontrado == false; $x++) {
                                                    $perfil = get_class($per[$x]);
                                                    $perfil = substr($perfil, 12);
                                                    if ($perfil == $perDni[$i][1]) {
                                                        switch ($perfil) {
                                                            case "Logistica":
                                                                $tra[] = new Logistica($trabajador);
                                                                break;
                                                            case "Produccion":
                                                                $tra[] = new Produccion($trabajador);
                                                                break;
                                                        }
                                                        $encontrado = true;
                                                    }
                                                }
                                            }
                                        }
                                        if (count($tra) != 0) {
                                            if ($v['opEstado'] == "si") {
                                                $est = [];
                                                foreach ($v["estado"] as $estado) {
                                                    $est[] = new Estado($estado);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                self::bucarPartesAnuales($fechaArr[0], $emp, $cen, $tra, $est);
            } else {
                $fechaIni = $v["fechaIni"];
                $desdeAn = explode("-", $fechaIni);
                $fechaFin = $v["fechaFin"];
                $hastaAn = explode("-", $fechaFin);
                if ($v['opEmpresa'] == "si") {
                    $emp = [];
                    foreach ($v["empresa"] as $empresa) {
                        $emp[] = new Empresa($empresa);
                    }
                    if (count($emp) != 0) {
                        if ($v["opCentro"] == "si") {
                            $cen = [];
                            foreach ($v["centro"] as $centro) {
                                $cen[] = new Centro($centro);
                            }
                            if (count($cen) != 0) {
                                if ($v["opTrabajador"] == "si") {
                                    $per = [];
                                    foreach ($v["perfil"] as $perfil) {
                                        switch ($perfil) {
                                            case "3":
                                                $per[] = new Produccion();
                                                break;
                                            case "4":
                                                $per[] = new Logistica();
                                                break;
                                            default:
                                                throw new \Exception("<script>smoke.signal('Solo realizar partes los de Logistica y Produccion', function (e) {null;}, { duration: 2000 } );</script>");

                                        }
                                    }
                                    if (count($per) != 0) {
                                        $tra = [];
                                        $perDni = self::perfilesLogisPro();
                                        foreach ($v["trabajador"] as $trabajador) {
                                            $i = 0;
                                            for ($i; $i < count($perDni) && $perDni[$i][0] == $trabajador; $i++) ;
                                            if ($i < count($perDni)) {
                                                $encontrado = false;
                                                for ($x = 0; $x < count($per) && $encontrado == false; $x++) {
                                                    $perfil = get_class($per[$x]);
                                                    $perfil = substr($perfil, 12);
                                                    if ($perfil == $perDni[$i][1]) {
                                                        switch ($perfil) {
                                                            case "Logistica":
                                                                $tra[] = new Logistica($trabajador);
                                                                break;
                                                            case "Produccion":
                                                                $tra[] = new Produccion($trabajador);
                                                                break;
                                                        }
                                                        $encontrado = true;
                                                    }
                                                }
                                            }
                                        }
                                        if (count($tra) != 0) {
                                            if ($v['opEstado'] == "si") {
                                                $est = [];
                                                foreach ($v["estado"] as $estado) {
                                                    $est[] = new Estado($estado);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                self::bucarPartesAnuales($desdeAn[0], $hastaAn[0], $emp, $cen, $tra, $est);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function perfilesLogisPro()
    {
        return BD\TrabajadorBD::getPerfLoPro();
    }

    public static function buscarPartesAnuales($ano = "", $fi = "", $ff = "", $emp = "", $cen = "", $tra = "", $est = "")
    {
        $partes = [];
        if ($tra != "") {

        } else {
            $partes[0] = self::partesLogistica($ano, $fi, $ff, $emp, $cen, $tra, $est);
            $partes[1] = self::partesProduccion($ano, $fi, $ff, $emp, $cen, $tra, $est);
        }

        return $partes;
    }

    public static function partesLogistica($ano, $fi, $ff, $emp, $cen, $tra, $est)
    {
        return BD\PartelogisticaBD::getPartes($ano, $fi, $ff, $emp, $cen, $tra, $est);
    }

    public static function partesProduccion($ano, $fi, $ff, $emp, $cen, $tra, $est)
    {
        return BD\ParteProduccionBD::getPartes($ano, $fi, $ff, $emp, $cen, $tra, $est);
    }

    public static function partesMensuales($v)
    {
    }

    public static function vacacionesAprobadas($v)
    {
    }

    public static function vacacionesDisfrutadas($v)
    {
    }

    public static function vacacionesSolicitadas($v)
    {
    }


}