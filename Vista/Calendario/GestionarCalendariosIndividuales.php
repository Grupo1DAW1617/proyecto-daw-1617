<?php

require_once __DIR__.'/CalendarioGestionarCalendariosIndividuales.php';

$login = unserialize($_SESSION['login']);

$trabajador = unserialize($_SESSION['trabajador']);

$trabajorPasswordm5 = md5($trabajador->getDni());

$perfil = get_class($trabajador);

$perfil = substr($perfil,12);

if ($login->getPassword() == $trabajorPasswordm5){
    \Vista\Login\LoginViews::changePassword();
}
else{
    switch($perfil){
        case "Administracion":
            CalendarioGestionarCalendariosIndividuales::cal(true);
            break;
        case "Gerencia":
            CalendarioGestionarCalendariosIndividuales::cal(true);
            break;
        default:
            header("Location: ".\Vista\Plantilla\Views::getUrlRaiz()."/Vista/Login/Login.php");
            break;
    }
}
