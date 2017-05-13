<?php
/**
 * Created by PhpStorm.
 * User: 2gdwes10
 * Date: 2/3/16
 * Time: 9:44
 */

namespace Controlador\Gerencia;

use Vista\Plantilla\Views;

require_once __DIR__."/../../Vista/Plantilla/Views.php";

require_once __DIR__.'/Controlador.php';

$gestionListas = "Location: ".Views::getUrlRaiz()."/Vista/Gerencia/Gerencia.php?cod=1";

if(isset($_POST['addTrabajador'])){
    $file = $_FILES;
    Controlador::insertarTrabajador($_POST, $file);
    header($gestionListas);
}

if(isset($_POST['addEmpresa'])){
    Controlador::insertarEmpresa($_POST);
    header($gestionListas);
}

if(isset($_POST['eliminarEmpresa'])){
    Controlador::deleteEmpresa($_POST);
    //headerLocation a vista Eliminar
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteEmpresa.php");
}
if(isset($_POST['vistaEditarEmpresa'])){
    //Ganeko
    $_SESSION['id'] = $_POST['id'];
    //headerLocation a vista Editar
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/updateEmpresa.php");
}
if(isset($_POST['editarEmpresa'])){
    //Ganeko
    Controlador::updateEmpresa($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteEmpresa.php");
}
if(isset($_POST['vistaEditarCentro'])){
    //Ganeko
    $_SESSION['id'] = $_POST['id'];
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/updateCentro.php");
}
if(isset($_POST['editarCentro'])){
    //Ganeko
    Controlador::updateCentro($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteCentro.php");
}
if(isset($_POST['vistaEditarVehiculo'])){
    //Ganeko
    $_SESSION['id'] = $_POST['id'];
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/updateVehiculo.php");
}
if(isset($_POST['editarVehiculo'])){
    //Ganeko
    Controlador::updateVehiculo($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteVehiculo.php");
}
if(isset($_POST['vistaEditarFoto'])){
    //Ganeko
    $_SESSION['dni'] = $_POST['dni'];
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/updateFoto.php");
}
if(isset($_POST['vistaEditarPass'])){
    //Ganeko
    $_SESSION['dni'] = $_POST['dni'];
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/updatePassword.php");
}
if(isset($_POST['vistaEditarConvenio'])){
    //Ganeko
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/updateConvenio.php");
}
if(isset($_POST['addEstado'])){
    Controlador::AddEstado($_POST);
    //headerLocation a vista Eliminar
    header($gestionListas);
}
if(isset($_POST['eliminarEstado'])){
    Controlador::deleteEstado($_POST);
    //headerLocation a vista Eliminar
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteEstado.php");
}
if(isset($_POST['addVehiculo'])){
    Controlador::AddVehiculo($_POST);
    //headerLocation a vista Eliminar
    header($gestionListas);
}
if(isset($_POST['mostrarHorarioTrabajador'])){ //Ibai
    $_SESSION["filtrosHorarios"] = $_POST;
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteHorarioTrabajador.php");
}
if(isset($_POST['updateHorarioTrabajador'])){
    $_SESSION["dht_semana"]=$_POST["dht_semana"];
	
	header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/updateHorarioTrabajador.php");
	
}
if(isset ($_POST['updateT3'])){

    Controlador::updateHorarioTrabajador($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=1");

}
if(isset($_POST['eliminarVehiculo'])){

    Controlador::deleteVehiculo($_POST);
    //headerLocation a vista Eliminar
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteVehiculo.php");
}
if(isset($_POST['addHorasConvenio'])){
    Controlador::AddHorasConvenio($_POST);
    //headerLocation a vista Eliminar
    header($gestionListas);
}
if(isset($_POST['eliminarHorasConvenio'])){

    Controlador::deleteHorasConvenio($_POST);
    //headerLocation a vista Eliminar
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteHorasConvenio.php");
}
if(isset($_POST['eliminarTrabajador'])){
    Controlador::deleteTrabajador($_POST);
    //headerLocation a vista Eliminar
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteTrabajador.php");
}
if(isset($_POST['addCentro'])){
    Controlador::AddCentro($_POST);
    header($gestionListas);
}

if(isset($_POST['eliminarCentro'])){
    Controlador::DeleteCentro($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteCentro.php");
}

if(isset($_POST['addTipoFranja'])){
    Controlador::addTipoFranja($_POST);
    header($gestionListas);
}
if(isset($_POST['vistaUpdateTipoFranja'])){
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/updateTipoFranja.php");
}
if(isset($_POST['updateTipoFranja'])){
    Controlador::UpdateTipoFranja($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteTipoFranja.php");
}

if(isset($_POST['deleteTipoFranja'])){
    Controlador::DeleteTipoFranja($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteTipoFranja.php");
}
if(isset($_POST['updateHorasConvenio'])){
    Controlador::UpdateHorasConvenio($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteHorasConvenio.php");
}

if(isset($_POST['añadirHorarioTrabajador'])){
    Controlador::addHorarioTrabajador($_POST);
    header($gestionListas);
}
if(isset($_POST['borrarHorarioTrabajador'])){
    Controlador::DeleteHorarioTrabajador($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteHorarioTrabajador.php");
}
if(isset($_POST['eliminarParteLogistica'])){
    Controlador::DeleteParteLogistica($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/Gerencia.php?cod=2");
}
if(isset($_POST['eliminarParteProduccion'])){
    Controlador::DeleteParteProduccion($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/Gerencia.php?cod=2");
}
if(isset($_POST['listarParteLog'])){
    Controlador::viewParteLog($_POST);
  }
if(isset($_POST['listarParteProd'])){
    Controlador::viewParteProd($_POST);
}
if(isset($_POST['finalizarParteLogistica'])){
    Controlador::updateFinalizarParteLogistica($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/Gerencia.php?cod=2");
}
if(isset($_POST['cerrarParteLogistica'])){
    Controlador::updateCerrarParteLogistica($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/Gerencia.php?cod=2");
}
if(isset($_POST['cerrarParteProduccion'])){
    Controlador::updateCerrarParteProduccion($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/Gerencia.php?cod=2");
}
if(isset($_POST['finalizarParteProduccion'])){
    Controlador::updateFinalizarParteProduccion($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/Gerencia.php?cod=2");
}

if(isset($_POST['updatePassword'])){
    Controlador::updatePassword($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/updatePassword.php");
}
if(isset($_POST['updateFoto'])){
    $file = $_FILES;
    Controlador::updateFoto($_POST,$file);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/updateFoto.php");
}
if(isset($_POST['añadirFestivo'])){
    Controlador::addFestivo($_POST);
    header($gestionListas);
}
if(isset($_POST['deleteFestivo'])){
    Controlador::deleteFestivo($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/deleteFestivo.php");
}
if(isset($_POST['volver'])){
    header("Location: ".Views::getUrlRaiz()."/Vista/Gerencia/Gerencia.php?cod=1");
}
if(isset($_POST['dni']) and !isset($_POST['semanas'])) {
    $perfil = Controlador::getPerfilbyDni($_POST['dni']);
    $partes = Controlador::getParte($_POST['dni'], $perfil);

    if ($perfil == "Logistica") {
        ?>
        <span id="respuesta">
        <table class="table table-bordered text-center">

            <h2>PARTES LOGÍSTICA</h2>
            <tr>
                <th>DNI</th>
                <th>NOMBRE</th>
                <th>FECHA</th>
                <th>NOTA</th>
                <th>ESTADO</th>
                <th>ACCIÓN</th>
            </tr>
            <?php
            foreach ($partes as $log) {
                if ($log->getEstado()->getTipo() == "Validado" || $log->getEstado()->getTipo() == "Finalizado") {

                    ?>
                    <form method="post" action="<?php echo Views::getUrlRaiz() ?>/Controlador/Gerencia/Router.php">
                        <tr>
                            <td><?php echo $log->getTrabajador()->getDni(); ?></td>
                            <td><?php echo $log->getTrabajador()->getNombre() . " " . $log->getTrabajador()->getApellido1() . " " . $log->getTrabajador()->getApellido2(); ?></td>
                            <td><?php echo $log->getFecha(); ?></td>
                            <td><?php echo $log->getNota(); ?></td>
                            <td><?php echo $log->getEstado()->getTipo(); ?></td>
                            <td>
                                <button type="submit" name="listarParteLog"
                                        style="border: none; background: none"><span
                                        class="glyphicon glyphicon-list" style="color:blue; font-size: 1.5em">
                                </button>
                    <?php
                    if ($log->getEstado()->getTipo() == "Validado") {
                        ?>

                        <button type="submit" name="finalizarParteLogistica"
                                style="border: none; background: none"><span
                                class="glyphicon glyphicon-ok"
                                style="color:green; font-size: 1.5em"></span></button>
                        <button type="submit" name="eliminarParteLogistica"
                                style="border: none; background: none"><span
                                class="glyphicon glyphicon-remove" style="color:red; font-size: 1.5em">
                        </button>
                        <button type="submit" name="cerrarParteLogistica"
                                style="border: none; background: none"><span
                                class="glyphicon glyphicon-open-file" style="color:blue; font-size: 1.5em">
                        </button>
                        <?php
                    }
                        ?>

                            </td>
                        </tr>
                        <?php
                        //Calculo de horas extras

                        $numhorasrealizadas = 0;
                        $viajes = array();
                        if (!is_null($log->getViajes())) {
                            if (!is_array($log->getViajes())) {

                                $viajes[] = $log->getViajes();
                            } else {
                                $viajes = $log->getViajes();
                            }
                        }
                        foreach($viajes as $viaje){
                            $horaInicio = $viaje->getHoraInicio();
                            $horaFin = $viaje->getHoraFin();

                            $numhorasrealizadas = $numhorasrealizadas + (substr($horaFin,0,2)-substr($horaInicio,0,2)) ;
                        }

                        $fecha = $log->getFecha();

                        $semana = date('W',strtotime($fecha));

                        $trabajador = $log->getTrabajador();


                        $horariosTrabajador = $trabajador->getHorariosTrabajadorBySemana($semana);

                        if (!is_array($horariosTrabajador))
                        {
                            $horariosTrabajador = array($horariosTrabajador);

                        }


                        $numhoras = 0;
                        foreach($horariosTrabajador as $horarioTrabajador) {
                            if (!is_null($horarioTrabajador)) {
                                $numhoras = $numhoras + sizeof($horarioTrabajador->getHorario()->getHorariosFranja());

                            }
                        }

                        $extras = $numhorasrealizadas - $numhoras;

                        if ($extras < 0)
                        {
                            $extras=0;

                        }

                        //Termina calculo de horas extra
                        ?>
                        <input type="hidden" name="horas" value="<?php echo $extras; ?>">

                        <input type="hidden" name="id" value="<?php echo $log->getId(); ?>">
                    </form>
                    <?php
                }
            }
            ?>
        </table>
        <?php
    } elseif ($perfil == "Produccion") {
        ?>
        <table class="table table-bordered text-center">
            <h2>PARTES PRODUCCIÓN</h2>
            <tr>
                <th>DNI</th>
                <th>NOMBRE</th>
                <th>FECHA</th>
                <th>INCIDENCIAS</th>
                <th>AUTOPISTAS</th>
                <th>DIETAS</th>
                <th>OTROS GASTOS</th>
                <th>ESTADO</th>
                <th>ACCIÓN</th>
            </tr>
            <?php
            foreach ($partes as $prod) {
                if ($prod->getEstado()->getTipo() == "Validado" || $prod->getEstado()->getTipo() == "Finalizado") {
                    ?>
                    <form method="post" action="<?php echo Views::getUrlRaiz() ?>/Controlador/Gerencia/Router.php">
                        <tr>
                            <td><?php echo $prod->getTrabajador()->getDni(); ?></td>
                            <td><?php echo $prod->getTrabajador()->getNombre() . " " . $prod->getTrabajador()->getApellido1() . " " . $prod->getTrabajador()->getApellido2(); ?></td>
                            <td><?php echo $prod->getFecha(); ?></td>
                            <td><?php echo $prod->getIncidencia(); ?></td>
                            <td><?php echo $prod->getAutopista(); ?></td>
                            <td><?php echo $prod->getDieta(); ?></td>
                            <td><?php echo $prod->getOtroGasto(); ?></td>
                            <td><?php echo $prod->getEstado()->getTipo(); ?></td>
                            <td>
                                <button type="submit" name="listarParteLog"
                                        style="border: none; background: none"><span
                                        class="glyphicon glyphicon-list" style="color:blue; font-size: 1.5em">
                                </button>
                    <?php
                    if ($prod->getEstado()->getTipo() == "Validado") {
                        ?>

                        <button type="submit" name="finalizarParteLogistica"
                                style="border: none; background: none"><span
                                class="glyphicon glyphicon-ok"
                                style="color:green; font-size: 1.5em"></span></button>
                        <button type="submit" name="eliminarParteLogistica"
                                style="border: none; background: none"><span
                                class="glyphicon glyphicon-remove" style="color:red; font-size: 1.5em">
                        </button>
                        <button type="submit" name="cerrarParteLogistica"
                                style="border: none; background: none"><span
                                class="glyphicon glyphicon-open-file" style="color:blue; font-size: 1.5em">
                        </button>
                        <?php
                    }
                        ?>

                            </td>
                        </tr>
                        <?php
                        //Calculo de horas extras
                        $fecha = $prod->getFecha();

                        $semana = date('W',strtotime($fecha));

                        $trabajador = $prod->getTrabajador();

                        $horariosTrabajador = $trabajador->getHorariosTrabajadorBySemana($semana);

                        if (!is_array($horariosTrabajador))
                        {
                            $horariosTrabajador = array($horariosTrabajador);

                        }

                        $numhorasrealizadas = 0;
                        foreach($prod->getHorariosParte() as $horarioParte){


                            $horaEntrada = $horarioParte->getHoraEntrada();
                            $horaSalida = $horarioParte->getHoraSalida();

                            $numhorasrealizadas = $numhorasrealizadas + (substr($horaSalida,0,2)-substr($horaEntrada,0,2)) ;

                        }

                        $numhoras = 0;
                        foreach($horariosTrabajador as $horarioTrabajador)
                        {
                            $numhoras = $numhoras + sizeof($horarioTrabajador->getHorario()->getHorariosFranja());
                        }

                        $extras = $numhorasrealizadas - $numhoras;
                        if ($extras < 0)
                        {
                            $extras=0;

                        }

                        //Termina calculo de horas extra
                        ?>
                        <input type="hidden" name="horas" value="<?php echo $extras; ?>">

                        <input type="hidden" name="id" value="<?php echo $prod->getId(); ?>">
                    </form>
                    <?php
                }
            }
            ?>
        </table>
        </span>
        <?php
    }
}

/* Alejandra */

if(isset($_POST['buscar'])){
    switch($_POST["buscar"]){
        case "incidencias":
            Controlador::incidencias($_POST);
            break;
        case "partesAnu":
            Controlador::partes($_POST);
            break;
        case "partesMen":
            Controlador::partes($_POST);
            break;
        case "vacaciones":
            Controlador::vacaciones($_POST);
            break;
    }
}