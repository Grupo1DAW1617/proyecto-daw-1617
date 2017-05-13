<?php

require_once __DIR__ . "/BusquedaViews.php";

$trabajador = unserialize($_SESSION['trabajador']);
$perfil = get_class($trabajador);
$perfil = substr($perfil,12);

if($perfil == "Administracion"){
    \Vista\Busqueda\BusquedaViews::incidencias("/Controlador/Administracion/Router.php");
}else if ($perfil == "Gerencia"){
    \Vista\Busqueda\BusquedaViews::incidencias("/Controlador/Gerencia/Router.php");
}
