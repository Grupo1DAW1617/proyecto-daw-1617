<?php

namespace Vista\Busqueda;
use Controlador\Administracion\Controlador;
use Modelo\Base\Empresa;
use Vista\Plantilla\Views;

require_once __DIR__ . '/../Plantilla/Views.php';
require_once __DIR__ . '/../../Modelo/Base/AdministracionClass.php';
require_once __DIR__ . '/../../Controlador/Administracion/Controlador.php';
require_once __DIR__ . '/../../Modelo/Base/EmpresaClass.php';

abstract class BusquedaViews extends Views{
    public static function principal(){
        parent::setOn(true);
        $trabajador = unserialize($_SESSION['trabajador']);
        $perfil = get_class($trabajador);
        $perfil = substr($perfil,12);

        if($perfil == "Administracion"){
            parent::setRoot(true);
        }else if ($perfil == "Gerencia"){
            parent::setRoot(true);
        }
        require_once __DIR__ . "/../Plantilla/cabecera.php";
        if(parent::isOn()){
            $trabajador = unserialize($_SESSION['trabajador']);

            if(substr(get_class($trabajador), 12) == "Administracion"){?>
                <h3 class="page-header">Vacaciones</h3>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Busqueda/vacasprecon.php">Vacaciones Previstas</a><br/>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Busqueda/vacasprecon.php">Vacaciones Consumidas</a><br/>
                <h3 class="page-header">Incidencias</h3>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Busqueda/incidencias.php">Incidencias</a><br/>
                <!--<div>
                    <?php /*if(isset($_SESSION['buscar'])){
                        if($_SESSION['buscar'] == "vacas"){
                            self::busquedaBoton("Vacaciones");
                        }elseif($_SESSION['buscar'] == "inci"){
                            self::busquedaBoton("Incidencias");
                        }
                    }*/?>
                </div>-->
                <?php
            }else{?>
                <h3 class="page-header">Partes</h3>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Busqueda/partes.php">Partes Anuales</a><br/>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Busqueda/partes.php">Partes Mensuales</a><br/>
                <h3 class="page-header">Vacaciones</h3>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Busqueda/vacaciones.php">Vacaciones Aprobadas</a><br/>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Busqueda/vacaciones.php">Vacaciones Disfrutadas</a><br/>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Busqueda/vacaciones.php">Vacaciones Solicitadas</a><br/>
                <h3 class="page-header">Incidencias</h3>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Busqueda/incidencias.php">Incidencias</a><br/><?php
            }
        }

        require_once __DIR__ . "/../Plantilla/pie.php";
    }

    public static function partes(){
        parent::setOn(true);
        parent::setRoot(true);
        require_once __DIR__ . '/../Plantilla/Cabecera.php';?>
        <form action="../../Controlador/Gerencia/Router.php" method="post" class="container">
            <fieldset>
                <legend>B&uacute;squeda por:</legend>
                <div class="row">
                    <div class="col-sm-2">
                        <label>Fecha</label>
                        <input type="radio" name="opFecha" value="fecha" onclick="addFecha(this.value)" required/>
                    </div>
                    <div class="col-sm-4 col-sm-offset-1">
                        <label>Rango de fechas</label>
                        <input type="radio" name="opFecha" value="rango" onclick="addFecha(this.value)" required/>
                    </div>
                </div>
                <div class="row" id="fecha"></div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Empresa:</label>
                        <input type="radio" name="opEmp" value="si""/>
                    </div>
                </div>
                <div class="row" id="empresas"></div>
                <div class="row">
                    <div class="col-sm-4 cen" style="display: none">
                        <label>Centro:</label>
                        <input type="radio" name="opCentro" value="si" onclick="addCentro()"/>
                    </div>
                </div>
                <div class="row" id="centros"></div>
                <div class="row">
                    <div class="col-sm-4 tra" style="display: none">
                        <label>Trabajador:</label>
                        <input type="radio" name="opTrabajador" value="si" onclick="addTipoTrabajador()"/>
                    </div>
                </div>
                <div class="row" id="tiposTrabajadores"></div>
                <div class="row" id="trabajadores"></div>
                <div class="row">
                    <div class="col-sm-4 est" style="display: none">
                        <label>Estado:</label>
                        <input type="radio" name="opEstado" value="si" onclick="addEstado()"/>
                    </div>
                </div>
                <div class="row" id="estados"></div>
                <p>
                    <div class="row">
                        <div class="col-sm-2 col-md-2">
                            <button class="btn btn-primary" type="submit" name="buscar" value="partesAnu">Partes Anuales</button>
                        </div>
                        <div class="col-sm-2 col-sm-offset-2 col-md-2 col-md-offset-2">
                            <button class="btn btn-primary" type="submit" name="buscar" value="partesMen">Partes Mensuales</button>
                        </div>
                    </div>
                </p>
            </fieldset>
        </form><?php
        echo $_SESSION["alert"];?>
        <div><?php
            if(isset($_SESSION["partes"])){
                if(count($_SESSION["partes"][0]) != 0 || count($_SESSION["partes"][1]) != 0){
                    try { ?>
                        <form action="<?php echo parent::getUrlRaiz(); ?>/Controlador/Gerencia/Router.php"  method="post"><?php
                        for ($h = 0; $h < count($_SESSION["partes"]); $h++) {
                            $perfil = get_class($_SESSION["partes"][$h][0][0]);
                            if($h == 0){
                                $nombre = "ParteLogistica";
                            }else{
                                $nombre = "ParteProduccion";
                            }
                            $perfil = substr($perfil, 12);

                            if (count($_SESSION["partes"][$h]) != 0) {
                                if($perfil == $nombre){?>
                                    <table class="table table-bordered text-center">
                                    <?php echo ($h == 0) ? "<caption>PARTES LOG&Iacute;STICA</caption>" : "<caption>PARTES PRODUCCI&Oacute;N</caption>"; ?>
                                    <tr>
                                        <th>COVE</th>
                                    </tr><?php self::cabeceraTabla($h); ?>
                                    <tr>
                                        <th scope="colgroup" colspan="10">Vitoria</th>
                                    </tr><?php
                                    for ($x = 0; $x < count($_SESSION["partes"][$h]); $x++) {
                                        if ($_SESSION["partes"][$h][$x][1]->getNombre() == "Cove" && $_SESSION["partes"][$h][$x][2]->getNombre() == "Vitoria") {
                                            self::addFila($h, $x);
                                        }
                                    } ?>
                                    <tr>
                                        <th scope="colgroup" colspan="10">Donostia</th>
                                    </tr><?php
                                    for ($x = 0; $x < count($_SESSION["partes"][$h]); $x++) {
                                        if ($_SESSION["partes"][$h][$x][1]->getNombre() == "Cove" && $_SESSION["partes"][$h][$x][2]->getNombre() == "Donostia") {
                                            self::addFila($h, $x);
                                        }
                                    }?>
                                    </table><?php
                                }else{
                                    throw new \Exception("error");
                                }
                            }
                        } ?>
                        <button type="submit" name="imprimir">PDF</button>
                        </form><?php
                    }catch (\Exception $e){
                        $_SESSION["partes"][0] = [];
                        $_SESSION["partes"][1] = [];
                    }
                }else{
                    echo "<script>smoke.signal('No se ha encontrado ningun parte.', function (e) {null;}, { duration: 2000 } );</script>";
                }
            }?>
        </div><?php
        require_once __DIR__ . '/../Plantilla/Pie.php';
    }

    public static function addFila($h, $x){?>
        <tr>
            <td><?php echo $_SESSION["partes"][$h][$x][0]->getTrabajador()->getDni(); ?></td>
            <td><?php echo $_SESSION["partes"][$h][$x][0]->getTrabajador()->getNombre();?></td>
            <td><?php echo $_SESSION["partes"][$h][$x][0]->getFecha();?></td>
            <td><?php echo ($h==0)?$_SESSION["partes"][$h][$x][0]->getNota():$_SESSION["partes"][$h][$x][0]->getIncidencia();?></td>
            <td><?php echo $_SESSION["partes"][$h][$x][0]->getAutopista();?></td>
            <td><?php echo $_SESSION["partes"][$h][$x][0]->getDieta();?></td>
            <td><?php echo $_SESSION["partes"][$h][$x][0]->getOtroGasto();?></td>
            <td><?php echo $_SESSION["partes"][$h][$x][0]->getEstado()->getTipo();?></td>
            <td><?php echo $_SESSION["partes"][$h][$x][0]->getHorasExtra();?></td>
            <td><input type="checkbox" name="imprimir<?php echo $h;?>[]" value="<?php echo $x;?>"/></td>
        </tr><?php
    }

    public static function cabeceraTabla($n){?>
        <thead>
            <tr>
                <th>DNI</th>
                <th>NOMBRE</th>
                <th>FECHA</th>
                <?php echo ($n==0)?"<th>NOTA</th>":"<th>INCIDENCIA</th>";?>
                <th>AUTOPISTA</th>
                <th>DIETA</th>
                <th>OTRO GASTO</th>
                <th>ESTADO</th>
                <th>HORAS EXTRA</th>
                <th>IMPRIMIR</th>
            </tr>
        </thead><?php
    }

    public static function vacaciones(){
        parent::setOn(true);
        parent::setRoot(true);
        require_once __DIR__ . '/../Plantilla/Cabecera.php';?>
        <form action="../../Controlador/Gerencia/Router.php" method="post" class="container">
            <fieldset>
                <legend>B&uacute;squeda por:</legend>
                <div class="row">
                    <div class="col-sm-2">
                        <label>Fecha</label>
                        <input type="radio" name="opFecha" value="fecha" onclick="addFecha(this.value)" required/>
                    </div>
                    <div class="col-sm-4 col-sm-offset-1">
                        <label>Rango de fechas</label>
                        <input type="radio" name="opFecha" value="rango" onclick="addFecha(this.value)" required/>
                    </div>
                </div>
                <div class="row" id="fecha"></div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Empresa:</label>
                        <input type="radio" name="opEmp" value="si""/>
                    </div>
                </div>
                <div class="row" id="empresas"></div>
                <div class="row">
                    <div class="col-sm-4 cen" style="display: none">
                        <label>Centro:</label>
                        <input type="radio" name="opCentro" value="si" onclick="addCentro()"/>
                    </div>
                </div>
                <div class="row" id="centros"></div>
                <div class="row">
                    <div class="col-sm-4 tra" style="display: none">
                        <label>Trabajador:</label>
                        <input type="radio" name="opTrabajador" value="si" onclick="addTipoTrabajador()"/>
                    </div>
                </div>
                <div class="row" id="tiposTrabajadores"></div>
                <div class="row" id="trabajadores"></div>
                <div class="row">
                    <div class="col-sm-4 est" style="display: none">
                        <label>Estado:</label>
                        <input type="radio" name="opEstado" value="si" onclick="addEstadoVacas()"/>
                    </div>
                </div>
                <div class="row" id="estados"></div>
                <p>
                <div class="row">
                    <div class="col-sm-2 col-md-2">
                        <button class="btn btn-primary" type="submit" name="buscar" value="vacaciones">Listar Vacaciones</button>
                    </div>
                </div>
                </p>
            </fieldset>
        </form>
        <div><?php
        if(isset($_SESSION["vacaciones"])){
            if(count($_SESSION["vacaciones"]) != 0){
                try { ?>
                    <form action="<?php echo parent::getUrlRaiz(); ?>/Controlador/Gerencia/Router.php"  method="post">
                        <table class="table table-bordered text-center">
                            <caption>VACACIONES DE LOS TRABAJADORES</caption>
                            <tr>
                                <th>COVE</th>
                            </tr><?php self::cabeceraTablaVacas(); ?>
                            <tr>
                                <th scope="colgroup" colspan="10">Vitoria</th>
                            </tr><?php
                            for ($h = 0; $h < count($_SESSION["vacaciones"]); $h++) {
                                $clase = get_class($_SESSION["vacaciones"][$h][0]);
                                $nombre = "VacacionesTrabajadores";
                                $clase = substr($clase, 12);
                                if($clase == $nombre){
                                    if ($_SESSION["vacaciones"][$h][1]->getNombre() == "Cove" && $_SESSION["vacaciones"][$h][2]->getNombre() == "Vitoria") {
                                        self::addFilaVacas($h);
                                    }
                                }else{
                                    throw new \Exception("error");
                                }
                            }
                             ?>
                            <tr>
                                <th scope="colgroup" colspan="10">Donostia</th>
                            </tr><?php
                            for ($h = 0; $h < count($_SESSION["vacaciones"]); $h++) {
                                $clase = get_class($_SESSION["vacaciones"][$h][0]);
                                $nombre = "VacacionesTrabajadores";
                                $clase = substr($clase, 12);
                                if($clase == $nombre){
                                    if ($_SESSION["vacaciones"][$h][1]->getNombre() == "Cove" && $_SESSION["vacaciones"][$h][2]->getNombre() == "Donostia") {
                                        self::addFilaVacas($h);
                                    }
                                }else{
                                    throw new \Exception("error");
                                }
                            }?>
                        </table>
                        <button type="submit" name="imprimir">PDF</button>
                    </form><?php
                }catch (\Exception $e){
                    $_SESSION["vacaciones"] = [];
                }
            }else{
                echo "<script>smoke.signal('No se ha encontrado ninguna vacacion.', function (e) {null;}, { duration: 2000 } );</script>";
            }
        }?>
        </div><?php
        require_once __DIR__ . '/../Plantilla/Pie.php';
    }

    public static function cabeceraTablaVacas(){?>
        <thead>
            <tr>
                <th>DNI</th>
                <th>NOMBRE</th>
                <th>FECHA</th>
                <th>HORA INICIO</th>
                <th>HORA FIN</th>
                <th>CALENDARIO</th>
                <th>ESTADO</th>
                <th>IMPRIMIR</th>
             </tr>
        </thead><?php
    }

    public static function addFilaVacas($h){?>
        <tr>
            <td><?php echo $_SESSION["vacaciones"][$h][0]->getTrabajador()->getDni(); ?></td>
            <td><?php echo $_SESSION["vacaciones"][$h][0]->getTrabajador()->getNombre();?></td>
            <td><?php echo $_SESSION["vacaciones"][$h][0]->getFecha();?></td>
            <td><?php echo $_SESSION["vacaciones"][$h][0]->getHoraInicio();?></td>
            <td><?php echo $_SESSION["vacaciones"][$h][0]->gethoraFin();?></td>
            <td><?php echo $_SESSION["vacaciones"][$h][0]->getCalendario()->getDesc();?></td>
            <td><?php echo $_SESSION["vacaciones"][$h][0]->getEstado()->getTipo();?></td>
            <td><input type="checkbox" name="imprimir[]" value="<?php echo $h;?>"/></td>
        </tr><?php
    }

    public static function incidencias($url){
        parent::setOn(true);
        parent::setRoot(true);
        require_once __DIR__ . '/../Plantilla/Cabecera.php';?>
        <form action="<?php echo parent::getUrlRaiz() . $url;?>" method="post">

        </form><?php
        require_once __DIR__ . '/../Plantilla/Pie.php';
    }

    public static function busquedaBoton($v){?>
        <br/>
        <form action="<?php echo parent::getUrlRaiz();?>/Controlador/Administracion/Router.php" method="post" class="form-horizontal ins">
            <fieldset>
                <legend><?php echo $v;?></legend>
                <input type="hidden" name="tipo" value="<?php echo $v;?>"/>
                <div class="form-group">
                    <label class="control-label col-sm-2 col-sm-offset-2 col-md-2 col-md-offset-2">A&ntilde;o:</label>
                    <div class="col-sm-4 col-md-4">
                        <?php $fecha = getdate();?>
                        <input type="number" name="ano" required min="1900" max="<?php echo $fecha["year"];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 col-sm-offset-2 col-md-2 col-md-offset-2">Empresa:</label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="empresa"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 col-sm-offset-2 col-md-2 col-md-offset-2">Centro:</label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="centro"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 col-sm-offset-2 col-md-2 col-md-offset-2">Dni Trabajador:</label>
                    <div class="col-sm-4 col-md-4">
                        <input type="text" name="trabajador"/>
                    </div>
                </div>
                <?php if($v == "Vacaciones"){?>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-sm-offset-2 col-md-2 col-md-offset-2">Estado de la vacaci&oacute;n:</label>
                        <div class="col-sm-4 col-md-4">
                            <input type="text" name="estado"/>
                        </div>
                    </div><?php
                }else{

                }?>

                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-4 col-md-2 col-md-offset-4">
                        <input class="btn btn-primary" type="submit" name="buscar" value="Buscar"/>
                    </div>
                </div>
            </fieldset>
        </form>
        <div><?php if(isset($_SESSION['error'])){echo $_SESSION['error'];}?></div><?php
    }

    public static function vacasPreCon(){
        parent::setOn(true);
        parent::setRoot(true);
        require_once __DIR__ . '/../Plantilla/Cabecera.php';?>
        <form action="../../Controlador/Administracion/Router.php" method="post" class="container">
            <fieldset>
                <legend>B&uacute;squeda por:</legend>
                <div class="row">
                    <div class="col-sm-2">
                        <label>Fecha</label>
                        <input type="radio" name="opFecha" value="fecha" onclick="addFecha(this.value)" required/>
                    </div>
                    <div class="col-sm-4 col-sm-offset-1">
                        <label>Rango de fechas</label>
                        <input type="radio" name="opFecha" value="rango" onclick="addFecha(this.value)" required/>
                    </div>
                </div>
                <div class="row" id="fecha"></div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Empresa:</label>
                        <input type="radio" name="opEmp" value="si""/>
                    </div>
                </div>
                <div class="row" id="empresas"></div>
                <div class="row">
                    <div class="col-sm-4 cen" style="display: none">
                        <label>Centro:</label>
                        <input type="radio" name="opCentro" value="si" onclick="addCentro()"/>
                    </div>
                </div>
                <div class="row" id="centros"></div>
                <div class="row">
                    <div class="col-sm-4 tra" style="display: none">
                        <label>Trabajador:</label>
                        <input type="radio" name="opTrabajador" value="si" onclick="addTipoTrabajador()"/>
                    </div>
                </div>
                <div class="row" id="tiposTrabajadores"></div>
                <div class="row" id="trabajadores"></div>
                <div class="row">
                    <div class="col-sm-4 est" style="display: none">
                        <label>Estado:</label>
                        <input type="radio" name="opEstado" value="si" onclick="addEstadoVacasPrecon()"/>
                    </div>
                </div>
                <div class="row" id="estados"></div>
                <p>
                <div class="row">
                    <div class="col-sm-2 col-md-2">
                        <button class="btn btn-primary" type="submit" name="buscar" value="vacasprecon">Listar Vacaciones</button>
                    </div>
                </div>
                </p>
            </fieldset>
        </form>
        <div><?php
        if(isset($_SESSION["vacasprecon"])){
            if(count($_SESSION["vacasprecon"]) != 0){
                try { ?>
                <form action="<?php echo parent::getUrlRaiz(); ?>/Controlador/Administracion/Router.php"  method="post">
                    <table class="table table-bordered text-center">
                        <caption>VACACIONES DE LOS TRABAJADORES</caption>
                        <tr>
                            <th>COVE</th>
                        </tr><?php self::cabeceraTablaVacas(); ?>
                        <tr>
                            <th scope="colgroup" colspan="10">Vitoria</th>
                        </tr><?php
                        for ($h = 0; $h < count($_SESSION["vacasprecon"]); $h++) {
                            $clase = get_class($_SESSION["vacasprecon"][$h][0]);
                            $nombre = "VacacionesTrabajadores";
                            $clase = substr($clase, 12);
                            if($clase == $nombre){
                                if ($_SESSION["vacasprecon"][$h][1]->getNombre() == "Cove" && $_SESSION["vacasprecon"][$h][2]->getNombre() == "Vitoria") {
                                    self::addFilaVacas($h);
                                }
                            }else{
                                throw new \Exception("error");
                            }
                        }
                        ?>
                        <tr>
                            <th scope="colgroup" colspan="10">Donostia</th>
                        </tr><?php
                        for ($h = 0; $h < count($_SESSION["vacasprecon"]); $h++) {
                            $clase = get_class($_SESSION["vacasprecon"][$h][0]);
                            $nombre = "VacacionesTrabajadores";
                            $clase = substr($clase, 12);
                            if($clase == $nombre){
                                if ($_SESSION["vacasprecon"][$h][1]->getNombre() == "Cove" && $_SESSION["vacasprecon"][$h][2]->getNombre() == "Donostia") {
                                    self::addFilaVacas($h);
                                }
                            }else{
                                throw new \Exception("error");
                            }
                        }?>
                    </table>
                    <button type="submit" name="imprimir">PDF</button>
                    </form><?php
                }catch (\Exception $e){
                    $_SESSION["vacasprecon"] = [];
                }
            }else{
                echo "<script>smoke.signal('No se ha encontrado ninguna vacacion.', function (e) {null;}, { duration: 2000 } );</script>";
            }
        }?>
        </div><?php
        require_once __DIR__ . '/../Plantilla/Pie.php';
    }
}