<?php
/**
 * Created by PhpStorm.
 * User: 2gdwes10
 * Date: 2/3/16
 * Time: 9:44
 */

namespace Controlador\Administracion;

require_once __DIR__."/../../Vista/Plantilla/Views.php";

require_once __DIR__.'/Controlador.php';

use Vista\Plantilla\Views;
use Modelo\BD;

$gestionListas = "Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=1";

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
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteEmpresa.php");
}
if(isset($_POST['vistaEditarEmpresa'])){
    //Ganeko
    $_SESSION['id'] = $_POST['id'];
    //headerLocation a vista Editar
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/updateEmpresa.php");
}
if(isset($_POST['editarEmpresa'])){
    //Ganeko
    Controlador::updateEmpresa($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteEmpresa.php");
}
if(isset($_POST['vistaEditarCentro'])){
    //Ganeko
    $_SESSION['id'] = $_POST['id'];
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/updateCentro.php");
}
if(isset($_POST['editarCentro'])){
    //Ganeko
    Controlador::updateCentro($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteCentro.php");
}
if(isset($_POST['vistaEditarVehiculo'])){
    //Ganeko
    $_SESSION['id'] = $_POST['id'];
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/updateVehiculo.php");
}
if(isset($_POST['editarVehiculo'])){
    //Ganeko
    Controlador::updateVehiculo($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteVehiculo.php");
}
if(isset($_POST['vistaEditarFoto'])){
    //Ganeko
    $_SESSION['dni'] = $_POST['dni'];
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/updateFoto.php");
}
if(isset($_POST['vistaEditarPass'])){
    //Ganeko
    $_SESSION['dni'] = $_POST['dni'];
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/updatePassword.php");
}
if(isset($_POST['vistaEditarConvenio'])){
    //Ganeko
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/updateConvenio.php");
}
if(isset($_POST['addEstado'])){
    Controlador::AddEstado($_POST);
    header($gestionListas);
}
if(isset($_POST['eliminarEstado'])){
    Controlador::deleteEstado($_POST);
    //headerLocation a vista Eliminar
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteEstado.php");
}
if(isset($_POST['addVehiculo'])){
    Controlador::AddVehiculo($_POST);
    header($gestionListas);
    }
if(isset($_POST['eliminarVehiculo'])){

    Controlador::deleteVehiculo($_POST);
    //headerLocation a vista Eliminar
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteVehiculo.php");
}
if(isset($_POST['addHorasConvenio'])){
    Controlador::AddHorasConvenio($_POST);
    header($gestionListas);
}
if(isset($_POST['eliminarHorasConvenio'])){

    Controlador::deleteHorasConvenio($_POST);
    //headerLocation a vista Eliminar
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteHorasConvenio.php");
}
if(isset($_POST['eliminarTrabajador'])){
    Controlador::deleteTrabajador($_POST);
    //headerLocation a vista Eliminar
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteTrabajador.php");
}
if(isset($_POST['addCentro'])){
    Controlador::AddCentro($_POST);
    header($gestionListas);
}

if(isset($_POST['eliminarCentro'])){
    Controlador::DeleteCentro($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteCentro.php");
}
if(isset($_POST['addHorario'])){
    Controlador::AddHorario($_POST);
    header($gestionListas);
}

if(isset($_POST['eliminarHorario'])){
    Controlador::DeleteHorario($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteHorario.php");

}
if(isset($_POST['vistaUpdateTipoFranja'])){
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/updateTipoFranja.php");
}
if(isset($_POST['updateTipoFranja'])){
    Controlador::UpdateTipoFranja($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteTipoFranja.php");
}
if(isset($_POST['addTipoFranja'])){
    Controlador::addTipoFranja($_POST);
    header($gestionListas);
}
if(isset($_POST['deleteTipoFranja'])){
    Controlador::DeleteTipoFranja($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteTipoFranja.php");
}
if(isset($_POST['updateHorasConvenio'])){
    Controlador::UpdateHorasConvenio($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteHorasConvenio.php");
}
if(isset($_POST['añadirHorarioTrabajador'])){
    Controlador::addHorarioTrabajador($_POST);
    //header($gestionListas);
}
if(isset($_POST['mostrarHorarioTrabajador'])){ //Ibai
    $_SESSION["filtrosHorarios"] = $_POST;
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteHorarioTrabajador.php");
}
if(isset($_POST['borrarHorarioTrabajador'])){
    Controlador::DeleteHorarioTrabajador($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteHorarioTrabajador.php");
}
if(isset($_POST['eliminarParteLogistica'])){
    Controlador::DeleteParteLogistica($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");
}
if(isset($_POST['eliminarParteProduccion'])){
    Controlador::DeleteParteProduccion($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");
}
if(isset($_POST['listarParteLog'])){
    Controlador::viewParteLog($_POST);
    //header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");
}
if(isset($_POST['listarParteProd'])){
    Controlador::viewParteProd($_POST);
    //header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");
}
if(isset($_POST['validarParteLogistica'])){
    Controlador::updateValidarParteLogistica($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");
}
if(isset($_POST['modificarParteLog'])){
    Controlador::updateParteLogistica($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=4");
}
if(isset($_POST['guardarParteLogistica'])){
    Controlador::guardarParteLogistica($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");
}
if(isset($_POST['abrirParteLogistica'])){
    Controlador::updateAbrirParteLogistica($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");
}
if(isset($_POST['validarParteProduccion'])){
    Controlador::updateValidarParteProduccion($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");
}
if(isset($_POST['modificarParteProd'])){
    Controlador::updateParteProduccion($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=5");
}
if(isset($_POST['guardarParteProduccion'])){
    Controlador::guardarParteProduccion($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");
}
if(isset($_POST['updatePassword'])){
    Controlador::updatePassword($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/updatePassword.php");
}
if(isset($_POST['updateFoto'])){
    $file = $_FILES;
    Controlador::updateFoto($_POST,$file);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/updateFoto.php");
}
if(isset($_POST['añadirFestivo'])){
    Controlador::addFestivo($_POST);
    header($gestionListas);
   }
if(isset($_POST['deleteFestivo'])){
    Controlador::deleteFestivo($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/deleteFestivo.php");
}
if(isset($_POST['abrirParteProduccion'])){
    Controlador::updateAbrirParteProduccion($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");
}
if(isset($_POST['abrirParteLogistica'])){
    Controlador::updateAbrirParteLogistica($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=2");

}/* PABLO */
if(isset($_POST['updateHorarioTrabajador'])){
    $_SESSION["dht_semana"]=$_POST["dht_semana"];

	header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/updateHorarioTrabajador.php");
	
}
if(isset($_POST["insertarIncidencia"])){ //David
    Controlador::insertarIncidencia($_POST);

}
if(isset ($_POST['updateT3'])){

    Controlador::updateHorarioTrabajador($_POST);
    header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=1");

}
/*
 *
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
                ?>
                <form method="post" action="<?php echo Views::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                    <tr>
                        <td><?php echo $log->getTrabajador()->getDni(); ?></td>
                        <td><?php echo $log->getTrabajador()->getNombre()." ".$log->getTrabajador()->getApellido1()." ".$log->getTrabajador()->getApellido2(); ?></td>
                        <td><?php echo $log->getFecha(); ?></td>
                        <td><?php echo $log->getNota(); ?></td>
                        <td><?php echo $log->getEstado()->getTipo(); ?></td>
                        <td>
                            <button type="submit" name="listarParteLog"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-list" style="color:blue; font-size: 1.5em">
                            </button>
                            <button type="submit" name="modificarParteLog"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-edit" style="color:blue; font-size: 1.5em">
                            </button>

                            <button type="submit" name="validarParteLogistica"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-ok"
                                    style="color:green; font-size: 1.5em"></span></button>
                            <button type="submit" name="eliminarParteLogistica"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-remove" style="color:red; font-size: 1.5em">
                            </button>
                            <button type="submit" name="abrirParteLogistica"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-open-file" style="color:blue; font-size: 1.5em">
                            </button>


                        </td>
                    </tr>
                    <input type="hidden" name="id" value="<?php echo $log->getId(); ?>">
                </form>
                <?php
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
                ?>
                <form method="post" action="<?php echo Views::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                    <tr>
                        <td><?php echo $prod->getTrabajador()->getDni(); ?></td>
                        <td><?php echo $prod->getTrabajador()->getNombre()." ".$prod->getTrabajador()->getApellido1()." ".$prod->getTrabajador()->getApellido2(); ?></td>
                        <td><?php echo $prod->getFecha(); ?></td>
                        <td><?php echo $prod->getIncidencia(); ?></td>
                        <td><?php echo $prod->getAutopista(); ?></td>
                        <td><?php echo $prod->getDieta(); ?></td>
                        <td><?php echo $prod->getOtroGasto(); ?></td>
                        <td><?php echo $prod->getEstado()->getTipo(); ?></td>
                        <td>
                            <button type="submit" name="listarParteProd"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-list" style="color:blue; font-size: 1.5em">
                            </button>
                            <button type="submit" name="modificarParteProd"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-edit" style="color:blue; font-size: 1.5em">
                            </button>

                            <button type="submit" name="validarParteProduccion"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-ok"
                                    style="color:green; font-size: 1.5em"></span></button>
                            <button type="submit" name="eliminarParteProduccion"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-remove" style="color:red; font-size: 1.5em">
                            </button>
                            <button type="submit" name="abrirParteProduccion"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-open-file" style="color:blue; font-size: 1.5em">
                            </button>


                        </td>
                    </tr>
                    <input type="hidden" name="id" value="<?php echo $prod->getId(); ?>">
                </form>
                <?php
            }
            ?>
        </table>
        </span>
        <?php
    }
}
*/
if(isset($_POST['volver'])){
        header("Location: ".Views::getUrlRaiz()."/Vista/Administracion/Administracion.php?cod=1");
}



/***********/
if (isset($_POST["semanas"]))
{

    $trabajador = BD\TrabajadorBD::getTrabajadorByDni($_POST["dni"]);

    $semanas = Controlador::getHorarioTrabajador($trabajador);

    for ($x = 1; $x <= 52; $x++) {
        if (!isset($semanas[$x]))
        {
            ?>
            <div style="float: left";>
                <label><?php echo $x ?></label><input type="checkbox" name="<?php echo $x;?>" id="<?php echo $x;?>" value="<?php echo $x ?>"/>
            </div>
            <?php
        }

    }
    ?>
<?php
}
if (isset($_POST["empresas"])) { //Ibai
    echo Controlador::rellenarEmpresas();
}
if (isset($_POST["centros"])) { //Ibai
    echo Controlador::rellenarCentros($_POST["centros"]);
}
if (isset($_POST["calendarios"])) { //Ibai
    echo Controlador::rellenarCalendarios();
}
?>