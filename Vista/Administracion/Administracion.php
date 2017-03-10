<?php
require_once __DIR__.'/AdministracionViews.php';
require_once __DIR__.'/../../Controlador/Administracion/Controlador.php';
require_once __DIR__.'/../../Vista/Calendario/CalendarioGestionarCalendario.php';

use Controlador\Administracion\Controlador;

switch($_GET['cod']) {

    case "1":
        Vista\Administracion\AdministracionViews::elegir();
        break;
    case "2":
        Vista\Administracion\AdministracionViews::allPartesByDni();
        break;
    case "3":
        Vista\Administracion\AdministracionViews::findPartesByDni($_POST);
        break;
    case "4":
        Vista\Administracion\AdministracionViews::editParteLogistica();
        break;
    case "5":
        Vista\Administracion\AdministracionViews::editParteProduccion();
        break;
    case "6": // IRUNE

        $calendario = \Controlador\Administracion\Controlador::crearObjetoCalendario();

        if ($calendario != false) {

            if (Modelo\BD\CalendarioBD::crearCalendario($calendario)) {
                echo "<script>alert('Nuevo calendario creado.');</script>";
                \CalendarioGestionarCalendario::cal(true);
            }
            else {
                echo "<script>alert('El calendario ya existe.');</script>";
                \CalendarioGestionarCalendario::cal(true);
            }

        }
        else {
            echo "<script>alert('No puede haber campos vacios.');</script>";
            \CalendarioGestionarCalendario::cal(true);
        }
        break;
    case "7":   //Aitor

        if (\Controlador\Administracion\Controlador::cerrarCalendario()) {

            if (\Controlador\Administracion\Controlador::comprobarFestivos($_POST["calendario"])) {

                if(Modelo\BD\CalendarioBD::cerrarCalendario($_POST["calendario"])){
                    echo "<script>alert('Calendario cerrado.');</script>";
                    \CalendarioGestionarCalendario::cal(true);
                }
            }
            else {
                echo "<script>alert('El calendario seleccionado no tiene los festivos asignados.');</script>";
                \CalendarioGestionarCalendario::cal(true);
            }
        }
        else {
            echo "<script>alert('Tienes que seleccionar un calendario abierto.');</script>";
            \CalendarioGestionarCalendario::cal(true);
        }
        break;

}
?>

