<?php
namespace Controlador\Administracion;
if(session_status()!=2){
    session_start();
}


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
use Modelo\Base\ParteProducionTarea;
use Modelo\Base\Produccion;
use Modelo\Base\TiposFranjas;
use Modelo\Base\Trabajador;
use Modelo\Base\AusenciaTrabajador;
use Modelo\Base\Vehiculo;
use Modelo\Base\Viaje;
use Modelo\Base\Login;
use Modelo\BD;
use Vista\Plantilla\Views;
use Vista\Administracion\AdministracionViews;

require_once __DIR__."/../../Modelo/BD/RequiresBD.php";
require_once __DIR__ ."/../../Modelo/Base/LogisticaClass.php";
require_once __DIR__ ."/../../Modelo/Base/AdministracionClass.php";
require_once __DIR__ ."/../../Modelo/Base/CalendarioClass.php";
require_once __DIR__ ."/../../Modelo/Base/ProduccionClass.php";
require_once __DIR__ ."/../../Modelo/Base/GerenciaClass.php";
require_once __DIR__ .'/../../Modelo/Base/EstadoClass.php';
require_once __DIR__ .'/../../Modelo/Base/LoginClass.php';
require_once __DIR__ .'/../../Modelo/Base/HoraConvenioClass.php';
require_once __DIR__ .'/../../Modelo/Base/HorariosClass.php';
require_once __DIR__."/../../Modelo/BD/LoginBD.php";
require_once __DIR__ .'/../../Modelo/Base/HorariosTrabajadoresClass.php';
require_once __DIR__."/../../Modelo/Base/FestivoClass.php";
require_once __DIR__."/../../Modelo/Base/ViajeClass.php";
require_once __DIR__."/../../Vista/Administracion/AdministracionViews.php";
require_once __DIR__."/../../Vista/Administracion/Incidencias.php";



abstract class Controlador{
    //Ruta para modificar el directorio donde se suben la imagenes al servidor
    private static $urlFoto = "/var/www/public/proyecto2GDAW/ProyectoFinal15-16/Vista/Fotos/";

    public static function insertarTrabajador($datos, $file){

        $trabajador="";

        $centro = BD\CentroBD::getCentrosById($datos['centro']);

        $perfil = $datos['perfil'];

        $datos['dni'] = strtoupper($datos['dni']);
        $datos['nombre'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['nombre'])))));
        $datos['apellido1'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['apellido1'])))));
        $datos['apellido2'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['apellido2'])))));
		$login="";
        switch($perfil){
            case "Logistica":
                $trabajador= new Logistica($datos["dni"],$datos['nombre'],$datos['apellido1'],$datos['apellido2'],$datos['telefono'],null/*foto*/,$centro,null,null,null,null);
				$login=new Login(null, md5($datos["pass"]), $trabajador->getDni());
                break;
            case "Administracion":
                $trabajador= new Administracion($datos["dni"],$datos['nombre'],$datos['apellido1'],$datos['apellido2'],$datos['telefono'],null/*foto*/,$centro,null,null,null);
				$login=new Login(null, md5($datos["pass"]), $trabajador->getDni());
                break;
            case "Gerencia":
                $trabajador= new Gerencia($datos["dni"],$datos['nombre'],$datos['apellido1'],$datos['apellido2'],$datos['telefono'],null/*foto*/,$centro,null,null,null);
				$login=new Login(null, md5($datos["pass"]), $trabajador->getDni());
                break;
            case "Produccion":
                $trabajador= new Produccion($datos["dni"],$datos['nombre'],$datos['apellido1'],$datos['apellido2'],$datos['telefono'],null/*foto*/,$centro,null,null,null,null);
				$login=new Login(null, md5($datos["pass"]), $trabajador->getDni());
                break;
        }

        if (strlen($file['foto']['name']) != 0){
            self::imagenTrabajador($trabajador, $file);
        }else{
            $trabajador->setFoto("Vista/Fotos/Default/foto.jpg");
        }

        $trabajador->add();

        

        BD\LoginBD::add($login);

    }

    public static function imagenTrabajador($trabajador, $file){

        $x = $trabajador->getDni();

        $url = "Vista/Fotos/".$x."/".$file['foto']['name'];

        self::subirImagen($file, $x);

        $trabajador->setFoto($url);

    }

    public static function subirImagen($file, $x)
    {

        $dir = opendir(__DIR__."/../../Vista/Fotos/");

        if (is_uploaded_file($file['foto']['tmp_name'])) {
            if (!file_exists(__DIR__."/../../Vista/Fotos/".$x)){
                mkdir(__DIR__."/../../Vista/Fotos/".$x);
                chmod(__DIR__."/../../Vista/Fotos/".$x,0755);

                move_uploaded_file($file['foto']['tmp_name'], __DIR__."/../../Vista/Fotos/".$x."/".basename($file['foto']['name']));
            }

            echo "<br>Fichero subido: " . $file['foto']['name'];

        } else {

            return "Error al subir el fichero: " . $file['foto']['name'];

        }
        closedir($dir);
    }

    public static function updateFoto($datos,$file){

        self::eliminarDir(__DIR__."/../../Vista/Fotos/".$datos["trabajador"]);

        $trabajador = BD\TrabajadorBD::getTrabajadorByDni($datos["trabajador"]);

        self::imagenTrabajador($trabajador, $file);

        $trabajadorSession = unserialize($_SESSION["trabajador"]);

        if($trabajador->getDni()==$trabajadorSession->getDni()){
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
        foreach(glob($carpeta . "/*") as $archivos_carpeta)
        {
            echo $archivos_carpeta;

            if (is_dir($archivos_carpeta))
            {
                self::eliminarDir($archivos_carpeta);
            }
            else
            {
                unlink($archivos_carpeta);
            }
        }
        rmdir($carpeta);
    }

    public static function insertarEmpresa($datos){
        //no hay centros en la nueva empresa
        $datos['nombre'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['nombre'])))));
        $datos['nif'] = strtoupper($datos['nif']);
        $empresa = new Empresa(null, $datos['nombre'], $datos['nif'], null );

        $empresa->add();
    }

    public static function deleteEmpresa($datos){
        $empresa = BD\EmpresaBD::getEmpresaByID($datos['id']);
        $empresa->delete();
    }

    public static function getAllEmpresas(){
        return BD\EmpresaBD::getAll();
    }

    public static function getAllPerfiles(){
        return BD\TrabajadorBD::getAllPerfiles();
    }

    public static function AddVehiculo($datos){
        $centro= BD\CentroBD::getCentrosById($datos["centro"]);
        $datos['matricula'] = strtoupper($datos['matricula']);
        $datos['marca'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['marca'])))));
        $vehiculo= new Vehiculo(null,$datos["matricula"],$datos["marca"],$centro);
        BD\VehiculoBD::add($vehiculo);
    }

    public static function deleteVehiculo($datos){
        BD\VehiculoBD::deletteVehiculo($datos["id"]);
    }
    public static function AddEstado($datos){
        $estado= new Estado(null,$datos["tipo"]);
        BD\EstadoBD::add($estado);
    }
    public static function DeleteEstado($datos){
        BD\EstadoBD::delete($datos["id"]);
    }
    public static function getAllTrabajadores(){
        return BD\TrabajadorBD::getAllTrabajadores();
    }
    public static function getAllEstados(){
        return BD\EstadoBD::getAll();
    }
    public static function getAllCentros(){
        return BD\CentroBD::getAll();
    }
    public static function getAllVehiculos(){
        return BD\VehiculoBD::getAll();
    }
    public static function AddHorasConvenio($datos){
        $centro= BD\CentroBD::getCentrosById($datos["centro"]);
        $datos['denominacion'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['denominacion'])))));
        $horaconvenio= new HoraConvenio(null,$datos["horasAnual"],$datos["denominacion"],$centro);
        BD\HorasConvenioBD::add($horaconvenio);
    }
    public static function getAllHorasConvenio(){
        return BD\HorasConvenioBD::getAll();
    }
    public static function deleteHorasConvenio($datos){
        BD\HorasConvenioBD::delete($datos["id"]);
    }
    public static function deleteTrabajador($datos){
        $datos['dni'] = strtoupper($datos['dni']);
        BD\LoginBD::deleteLoginByDni($datos["dni"]);
        BD\TrabajadorBD::deleteTrabajador($datos["dni"]);
        self::eliminarDir(__DIR__."/../../Vista/Fotos/".$datos['dni']);
    }


    public static function AddCentro($datos){
        $empresa = BD\EmpresaBD::getEmpresaByID($datos['empresa']);
        $datos['nombre'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['nombre'])))));
        $datos['localizacion'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['localizacion'])))));
        $centro = new Centro(null, $datos['nombre'], $datos['localizacion'], $empresa);
        $centro->add();
    }
    public static function DeleteCentro($datos){
        $centro = BD\CentroBD::getCentrosById($datos['id']);
        $centro->delete();
    }
    public static function getAllTiposFranjas(){
        return BD\TipoFranjaBD::getAll();
    }
    public static function updateTipoFranja($datos){

        $tipo = new TiposFranjas($datos['id'],null,$datos['nuevo']);

        BD\TipoFranjaBD::update($tipo);
    }
    public static function AddTipoFranja($datos){
        $datos['tipo'] = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower(($datos['tipo'])))));
        $tipo = new TiposFranjas(null, $datos['tipo'], $datos['precio']);
        $tipo->save();
    }
    public static function DeleteTipoFranja($datos){
        \Modelo\BD\TipoFranjaBD::delete($datos['id']);
    }
    public static function UpdateHorasConvenio($datos){
        $horas = new HoraConvenio($datos['id'],$datos['nuevo']); /*error*/

        BD\HorasConvenioBD::UpdateHorasConvenio($horas);
    }

    public static function getAllLogins(){
        return BD\LoginBD::getAll();
    }

    public static function updatePassword($datos){
        $datos['password'] = md5($datos['password']);
        BD\LoginBD::changePasswordByDni($datos);
    }

    public static function getAllFranjas(){
        return BD\FranjaBD::getAll();
    }

    public static function AddHorario($datos)
    {
        $horario= new Horarios(null,$datos["horario"]);
        $idHorario=BD\HorarioBD::add($horario);
        while($datos["horaInicio"]!=$datos["horaFin"]){

            $horaioFranja= new HorariosFranja(null,BD\HorarioBD::getHorarioById($idHorario),BD\FranjaBD::getFranjaById($datos["horaInicio"])        );
            BD\HorarioFranjaBD::add($horaioFranja);

            if($datos["horaInicio"]==24){
                $datos["horaInicio"]=1;
            }else {
                $datos["horaInicio"] = $datos["horaInicio"] + 1;
            }
        }
        $horaioFranja= new HorariosFranja(null,BD\HorarioBD::getHorarioById($idHorario),BD\FranjaBD::getFranjaById($datos["horaInicio"])        );
        BD\HorarioFranjaBD::add($horaioFranja);

    }

    public static function getAllHorarios(){
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

    public static function deleteHorario($datos){
        BD\HorarioBD::delete($datos["id"]);
    }
    public static function addHorarioTrabajador($datos){ //Ibai
        $calendario = new Calendario($datos["calendario"]);
        $horarioTrabajador= new HorariosTrabajadores(null,$datos["semana"], BD\TrabajadorBD::getTrabajadorByDni($datos["trabajador"]),BD\HorarioBD::getHorarioById($datos["horario"]), $calendario);
        BD\HorarioTrabajadorBD::add($horarioTrabajador);
    }

    public static function getAllHorarioTrabajador(){
        return BD\HorarioTrabajadorBD::getAll();
    }
    public static function getAllHorarioTrabajadorFiltrado($filtros){
        return BD\HorarioTrabajadorBD::getAllFiltrado($filtros);
    }
    public static function getHorarioTrabajador($trabajador){
        return BD\HorarioTrabajadorBD::getAll($trabajador);
    }
    public static function DeleteHorarioTrabajador($datos){
        BD\HorarioTrabajadorBD::delete($datos["id"]);
    }
    public static function getAllPartesProduccion(){
        return BD\ParteProduccionBD::getAll();
    }
    public static function getAllPartesLogistica(){
        return BD\PartelogisticaBD::getAll();
    }
    public static function DeleteParteProduccion($datos){

        BD\ParteProduccionBD::Delete($datos['id']);
    }
    public static function DeleteParteLogistica($datos){
        BD\PartelogisticaBD::Delete($datos['id']);
    }

    public static function viewParteLog($datos)
    {
        //$trabajador=unserialize($_SESSION['trabajador']);
        $parte=BD\PartelogisticaBD::selectParteLogisticaById($datos['id']);
        $viajes=BD\ViajeBD::getViajeByParte($parte);

        AdministracionViews::viewParteLog($parte,$viajes);

    }

    public static function viewParteProd($datos)
    {
        //$trabajador=unserialize($_SESSION['trabajador']);
        $parte=BD\ParteProduccionBD::getParteById($datos['id']);
        $estado = BD\EstadoBD::selectEstadoByParteProduccion($parte);
        //$viajes=BD\ViajeBD::getViajeByParte($parte);

        AdministracionViews::viewParteProd($parte,$estado);

    }
    public static function updateValidarParteLogistica($datos){
        BD\PartelogisticaBD::updateValidar($datos['id']);
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

        $parteProduccionTareas = $parte->getParteProduccionTareas();

        $x = 1;
        foreach ($parteProduccionTareas as $parteProduccionTarea)
        {
            $update = false;
            $parteProdTarea = $parteProduccionTarea;
            $parteProdTarea->setParte($parte);

            if (($parteProduccionTarea->getTarea()->getId() != $datos['ParteProduccionTarea'.$x]) || ($parteProduccionTarea->getNumeroHoras() != $datos['NumeroHoras'.$x]))
            {
                $parteProdTarea->setNumeroHoras($datos['NumeroHoras'.$x]);
                $parteProdTarea->setTarea(BD\TareaBD::getTareaById($datos['ParteProduccionTarea'.$x]));
                $update = true;
            }
            if (isset($datos['PaqueteEntrada'.$x]))
            {
                if ($datos['PaqueteEntrada'.$x] != $parteProduccionTarea->getPaqueteEntrada())
                {
                    $parteProdTarea->setPaqueteEntrada($datos['PaqueteEntrada'.$x]);
                    $update = true;
                }
            }
            if (isset($datos['PaqueteSalida'.$x]))
            {
                if ($datos['PaqueteSalida'.$x] != $parteProduccionTarea->getPaqueteSalida())
                {
                    $parteProdTarea->setPaqueteSalida($datos['PaqueteSalida'.$x]);
                    $update = true;
                }
            }

            if ($update)
            {
                BD\ParteProduccionTareaBD::update($parteProduccionTarea);
            }
            $x++;
        }

        $update = false;
        $x = 1;
        $horariosParte = $parte->getHorariosParte();
        foreach($horariosParte as $horarioParte){
            if (strcmp($horarioParte->getHoraEntrada(),$datos['HoraEntrada'.$x] != 0))
            {
                $horariosParte[$x-1]->setHoraEntrada($datos['HoraEntrada'.$x]);
                $update = true;
            }
            if (strcmp($horarioParte->getHoraSalida(),$datos['HoraSalida'.$x] != 0))
            {
                $horariosParte[$x-1]->setHoraSalida($datos['HoraSalida'.$x]);
                $update = true;
            }
        }

        if ($update)
        {
            $parte->setHorariosParte($horariosParte);
        }

        if ($parte->getAutopista() != $datos['Autopista'])
        {
            $parte->setAutopista($datos['Autopista']);
            $update = true;
        }

        if ($parte->getDieta() != $datos['Dieta'])
        {
            $parte->setDieta($datos['Dieta']);
            $update = true;
        }

        if ($parte->getOtroGasto() != $datos['Otros'])
        {
            $parte->setOtroGasto($datos['Otros']);
            $update = true;
        }

        if ($parte->getIncidencia() != $datos['Incidencia'])
        {
            $parte->setIncidencia($datos['Incidencia']);
            $update = true;
        }

        BD\ParteProduccionBD::updateDatosParte($parte);

        unset($_SESSION['parte']);


    }
    public static function guardarParteLogistica($datos)
    {
        $parte = unserialize($_SESSION['parte']);

        $viajes = array();

        if (!is_array($parte->getViajes()))
        {
            $viajes[] = $parte->getViajes();
        }
        else
        {
            $viajes = $parte->getViajes();
        }

        $x = 1;
        foreach ($viajes as $viaje)
        {

            if ((strcmp($viaje->getHoraInicio(),$datos['HoraInicio'.$x]) != 0) || (strcmp($viaje->getHoraFin(),$datos['HoraFin'.$x]) != 0) || (strcmp($viaje->getVehiculo()->getMatricula(),$datos['Matricula'.$x]) != 0) || (strcmp($viaje->getAlbaran(),$datos['Albaran'.$x]) != 0))
            {

                $viaje = new Viaje($datos['id'.$x],$datos['HoraInicio'.$x],$datos['HoraFin'.$x],$datos['Albaran'.$x],BD\VehiculoBD::getVehiculoByMatricula($datos['Matricula'.$x]),null);

                BD\ViajeBD::update($viaje);
            }
            $x++;
        }

        if (strcmp($parte->getNota(),$datos['Nota'] != 0))
        {
            $parte->setNota($datos['Nota']);
            BD\PartelogisticaBD::updateNotaParte($parte);
        }

        unset($_SESSION['parte']);

    }
    public static function updateAbrirParteLogistica($datos){
        BD\PartelogisticaBD::updateAbrir($datos['id']);
    }
    public static function updateValidarParteProduccion($datos){
        BD\ParteProduccionBD::updateValidar($datos['id']);
    }
    public static function updateAbrirParteProduccion($datos){
        BD\ParteProduccionBD::updateAbrir($datos['id']);
        $parte = BD\ParteProduccionBD::getParteById($datos['id']);
        $horariosparte = $parte->getHorariosParte();
        foreach ($horariosparte as $horarioparte)
        {
            BD\HorarioParteBD::delete($horarioparte);
        }


    }
    public static function getAllFestivos(){
        return BD\FestivoBD::getAll();
    }
    public static function addFestivo($datos){
        $festivo = new Festivo(null, $datos['fecha'], $datos['motivo']);

        BD\FestivoBD::add($festivo);
    }
    public static function deleteFestivo($datos){
        BD\FestivoBD::delete($datos['id']);
    }
    public static function buscarParteLog($datos){
        return BD\PartelogisticaBD::getAllByTrabajador($datos['dni']);;
    }
    public static function buscarParteProd($datos){
        return BD\ParteProduccionBD::getAllByTrabajador($datos['dni']);
    }
    public static function getPerfilByDni($dni){
        $trabajador = new Logistica($dni);
        $perfil = BD\TrabajadorBD::getPerfilByDni($trabajador);


        return $perfil;

    }

    public static function getParte($dni, $perfil){
        $trabajador = new Logistica($dni);

        if($perfil == "Produccion"){
            return $partes = BD\ParteProduccionBD::getAllByTrabajador($trabajador);
        }
        elseif($perfil == "Logistica"){
            return $partes = BD\PartelogisticaBD::getAllByTrabajador($trabajador);
        }

    }

    public static function updateParteLogistica($datos)
    {
        $parte = BD\PartelogisticaBD::selectParteLogisticaById($datos['id']);

        $_SESSION['parte'] = serialize($parte);
    }
    public static function updateParteProduccion($datos)
    {
        $parte = BD\ParteProduccionBD::getParteById($datos['id']);

        $_SESSION['parte'] = serialize($parte);

    }/* PABLO */
	public static function updateHorarioTrabajador($datos){
		BD\HorarioTrabajadorBD::updateHorarioTrabajador($datos["horario"], $_SESSION["dniht"] , $_SESSION["semht"]);
    }
    public static function getCentros($e = null){
        return BD\CentroBD::getCentrosByEmpresas($e);
    }

    public static function getAllCalendarios(){
        return BD\CalendarioBD::getAll();
    }

    public static function rellenarEmpresas(){ //Ibai
        $empresas = Controlador::getAllEmpresas();
        $mensaje = '<option value="" disabled  selected="selected">Elige</option>';

        for ($x = 0; $x < count($empresas); $x++) {
            $mensaje.='<option value="'. $empresas[$x]->getId().'">'.$empresas[$x]->getNombre().'</option>';
        }

        return $mensaje."</select>";
    }

    public static function rellenarCentros($id = null){ //Ibai
        $centros = Controlador::getCentros([$id]);
        $mensaje = '<option value="" disabled  selected="selected">Elige</option>';

        for($x=0;$x<count($centros);$x++){
            $mensaje.='<option value="'. $centros[$x]->getId().'">'.$centros[$x]->getNombre().'</option>';
        }
        return $mensaje;
    }

    public static function rellenarCalendarios()
    { //Ibai
        $calendarios = Controlador::getAllCalendarios();
        $mensaje = '<option value="" disabled  selected="selected">Elige</option>';

        for ($x = 0; $x < count($calendarios); $x++) {
            $mensaje .= '<option value="' . $calendarios[$x]->getId() . '">' . $calendarios[$x]->getId() . '</option>';
        }
        return $mensaje;
    }

    public static function incidenciasHorarioTrabajador($dni,$semana,$calendario){
        return BD\HorarioTrabajadorBD::checkIncidencias($dni,$semana,$calendario);
    }

    //David
    public static function insertarIncidencia($datos){

        $fecha = getdate();
        $fecha2 = date("Y-m-d");

        $incidencia = new AusenciaTrabajador(null, $fecha2, "00:00", "00:00", $datos["dni"], $datos["motivo"], $fecha["year"]);

        $res = \Modelo\BD\AusenciaTrabajadorBD::setAusencias($incidencia);

        if($res != 1){
            echo "<script>alert('Error al introducir la incidencia. Es posible que el DNI no sea correcto.')</script>";
            \Vista\Administracion\Incidencias::mostrar();
        }else{
            echo "<script>alert('Incidencia introducida correctamente')</script>";
            \Vista\Administracion\Incidencias::mostrar();
        }
    }

    // IRUNE

    public static function crearObjetoCalendario() {

        if ($_POST['calendario'] != "" && $_POST['descripcion'] != "") {

            $calendario = new \Modelo\Base\Calendario($_POST['calendario'], $_POST['descripcion'], 1);
            return $calendario;

        }
        else {
            return false;
        }

    }

    // IRUNE

    public static function cerrarCalendario() {

        if ($_POST['calendario'] != "") {
            return true;
        }
        else {
            return false;
        }

    }

    public static function comprobarFestivos($calendario) {

        if(BD\CalendarioBD::comprobarFestivos($calendario)){
            return true;
        }
        else {
            return false;
        }

    }

    // IRUNE

    public static function crearObjetoCentro() {

        $centro = new Centro($_POST['centro']);
        return $centro;

    }

}