<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="es"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="es"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="es"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="es"> <!--<![endif]-->
<?php require_once __DIR__.'/../Produccion/CalendarioViews.php'?>

<head>

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <title>Himevico</title>

    <meta charset="UTF-8"/>
    <meta name="keywords" content="tus palabras clave, aqui"/>
    <meta name="description" content="breve descripcion del sitio"/>
    <meta name="author" content="JonLG"/>

    <meta http-equiv="PRAGMA" content="NO-CACHE">
    <meta http-equiv="EXPIRES" content="-1">

    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" type="text/css" href="<?php echo \Vista\Plantilla\Views::getUrlRaiz() ?>/Vista/Plantilla/CSS/Bootstrap/bootstrap.min.css"/>

    <link rel="stylesheet" type="text/css" href="<?php echo \Vista\Plantilla\Views::getUrlRaiz() ?>/Vista/Plantilla/CSS/Bootstrap/customize.css">
    
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo \Vista\Plantilla\Views::getUrlRaiz();?>/Vista/Plantilla/JS/validetta-v1.0.1-dist/validetta.min.css">

    <link rel="stylesheet" type="text/css" href="<?php echo \Vista\Plantilla\Views::getUrlRaiz(); ?>/Vista/Plantilla/CSS/style.css" media="screen" />

    <link rel="icon" type="image/png" href="<?php echo \Vista\Plantilla\Views::getUrlRaiz(); ?>/Vista/Plantilla/IMG/himevico.png"/>
</head>
<body>



<nav class="navbar navbar-default navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header"><!--Para añadir el icono de menú-->
            <?php
            if(\Vista\Plantilla\Views::isOn()){?>
            <button type="button" class="navbar-toggle collapsed visible-sm visible-xs" data-toggle="collapse" data-target="#navbar-1">
                <span class="sr-only">Desplegar / Ocultar Menú</span>
                <span class="icon-bar"></span><!--Cada span es una rayita en el icon de menú-->
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

                <?php
            }
            ?>
            <a href="<?php  if(\Vista\Plantilla\Views::isOn()){ echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Calendario/Calendario.php<?php } ?>" class="navbar-brand"><img id="logo" src="<?php echo \Vista\Plantilla\Views::getUrlRaiz();?>/Vista/Plantilla/IMG/himevico.png" alt="Himevico logo" class="img-responsive"></a>
            <a style="padding: 0; margin-top: -7px" href="<?php  if(\Vista\Plantilla\Views::isOn()){ echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Calendario/Calendario.php<?php } ?>" class="navbar-brand hidden-xs"><h3 style="color: #adadad">Himevico S.L.</h3></a>
        </div>
        <?php
       /* if(!parent::isOn()){?>
        <div class="collapse navbar-collapse visible-md">
            <h3 style="color: #adadad">Himevico S.L.</h3>
        </div><?php
        }*/

        if(\Vista\Plantilla\Views::isOn()){?>
        <div class="collapse navbar-collapse navbar-right" id="navbar-1"><!--Añadimos el menú-->
            <ul class="nav navbar-nav">
                <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Calendario/Calendario.php">Inicio</a></li>

                <?php if(!\Vista\Plantilla\Views::isRoot()){
                    ?>
                    <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Horario/Horario.php">Horario Semanal</a></li>
                    <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Calendario/Vacaciones.php">Vacaciones/Incidencias</a></li> <!--Cambio David-->
                    <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Calendario/Calendario.php">Partes</a></li>
                <?php
                }
                ?>
                <?php
                if(\Vista\Plantilla\Views::isRoot()){
                    $trabajador = unserialize($_SESSION['trabajador']);
                    $perfil = get_class($trabajador);
                    $perfil = substr($perfil, 12);
                    switch ($perfil) {
                        case "Administracion":
                            $urlListas = "/Vista/Administracion/Administracion.php?cod=1"; //Para gestionar las tablas
                            $urlPartes = "/Vista/Administracion/Administracion.php?cod=2"; //Para gestionar los partes
                            // Alejandra
                            $urlListado = "/Vista/Busqueda/busqueda.php";
                            ?>
                            <?php
                            break;
                        case "Gerencia":
                            $urlListas = "/Vista/Gerencia/Gerencia.php?cod=1";
                            $urlPartes = "/Vista/Gerencia/Gerencia.php?cod=2";
                            // Alejandra
                            $urlListado = "/Vista/Busqueda/busqueda.php";
                            break;
                    }
                    ?>
                    <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz().$urlListas?>">Gestionar tablas</a></li>
                    <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz().$urlPartes?>">Gestionar partes</a></li>
                    <!-- Alejandra -->
                    <li><a href="<?php echo parent::getUrlRaiz().$urlListado?>">Listados</a></li>
                    <!--
                        Generar el desplegable para Vacaciones
                        Anas
                    -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            Vacaciones/Incidencias
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Calendario/Vacaciones.php"> Vacaciones </a> </li>
                            <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Administracion/insertIncidencia.php">Incidencias</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"> <!--Cambio Aitor-->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <span>Gestionar Calendarios</span> <!--Cambio Aitor-->
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Calendario/GestionarCalendario.php">Gestionar Calendario</a></li>   <!--Cambio Aitor-->
                            <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Calendario/GestionarFestivosNacionales.php">Gestionar Festivos Nacionales</a></li>  <!--Cambio Aitor-->
                            <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Calendario/GestionarFestivosCentros.php">Gestionar Festivos Centros</a></li>  <!--Cambio Aitor-->
                            <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Calendario/GestionarCalendariosIndividuales.php">Gestionar Calendarios Individuales</a></li>  <!--Cambio Aitor-->
                        </ul>
                    </li>
                    <?php
                }
                ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <span class="glyphicon  glyphicon-cog " style="font-size: 1.5em"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Login/CambiarPassword.php">Cambiar contraseña</a></li>
                        <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Login/Perfil.php">Perfil</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo \Vista\Plantilla\Views::getUrlRaiz()?>/Vista/Login/Login.php">Desconectar</a></li>
                    </ul>
                </li>
            </ul>
            <?php
            }
            ?>
        </div>
    </div>
</nav>

<!-- Include all compiled plugins (belor include individual files as needed -->
<script src="<?php echo \Vista\Plantilla\Views::getUrlRaiz() ?>/Vista/Plantilla/JS/bootstrap.min.js"></script>

<script src="JS/jquery-2.2.1.min.js"></script>
</body>

<?php
if(\Vista\Plantilla\Views::isOn()){
    $trabajador = unserialize($_SESSION['trabajador']);
    ?>
    <div class="jumbotron jumbotron-fluid hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-md-2  col-sm-3">
                    <img id="foto" src="<?php $trabajador = unserialize($_SESSION['trabajador']); echo \Vista\Plantilla\Views::getUrlRaiz()."/".$trabajador->getFoto()?>" alt="Foto Trabajador" class="img-responsive img-thumbnail">
                </div>
                <div class="col-md-10 col-sm-9">
                    <h4 class="display-3"><strong>Perfil de <?php echo substr(get_class($trabajador), 12)?></strong></h4>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><strong>Nombre: </strong><?php echo $trabajador->getNombre()?></h5>
                                <h5><strong>Apellidos: </strong><?php echo $trabajador->getApellido1().' '.$trabajador->getApellido2()?></h5>
                                <h5><strong>Telefono: </strong><?php echo $trabajador->getTelefono()?></h5>
                            </div>
                            <div class="col-md-6">
                                <h5><strong>Centro: </strong><?php echo $trabajador->getCentro()->getNombre()?></h5>
                                <h5><strong>Empresa: </strong><?php echo $trabajador->getCentro()->getEmpresa()->getNombre()?></h5>
                                <h5><strong>NIF empresa: </strong><?php echo $trabajador->getCentro()->getEmpresa()->getNif()?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<div class="cuerpo container">
