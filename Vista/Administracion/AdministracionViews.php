<?php
namespace Vista\Administracion;

use \Controlador\Administracion;
use Controlador\Login\Controlador;
use Modelo\BD;


require_once __DIR__ . "/../Plantilla/Views.php";
require_once __DIR__ . "/../../Controlador/Administracion/Controlador.php";
require_once __DIR__.'/../../Modelo/BD/GenericoBD.php';
require_once __DIR__.'/../../Modelo/Base/FranjasClass.php';
require_once __DIR__.'/../../Modelo/BD/CalendarioBD.php';

abstract class AdministracionViews extends \Vista\Plantilla\Views
{
/*****************************************************/
/* MENU */
/*****************************************************/
    public static function elegir(){

        parent::setOn(true);
        parent::setRoot(true);
        /* Ganeko */

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        ?>
            <h3 class="page-header">Trabajador
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/insertTrabajador.php"><span class="glyphicon glyphicon-plus" style="font-size: 24px; color: green;"></span></a>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/deleteTrabajador.php"><span class="glyphicon glyphicon-eye-open" style="font-size: 24px; color: black;"></span></a>
            </h3>
            <h3 class="page-header">Empresa
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/insertEmpresa.php"><span src="" class="glyphicon glyphicon-plus" style="font-size: 24px; color: green;"></span></a>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/deleteEmpresa.php"><span class="glyphicon glyphicon-eye-open" style="font-size: 24px; color: black;"></span></a>
            </h3>
            <h3 class="page-header">Centro
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/insertCentro.php"><span src="" class="glyphicon glyphicon-plus" style="font-size: 24px; color: green;"></span></a>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/deleteCentro.php"><span class="glyphicon glyphicon-eye-open" style="font-size: 24px; color: black;"></span></a>
            </h3>
            <h3 class="page-header">Vehiculo
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/insertVehiculo.php"><span src="" class="glyphicon glyphicon-plus" style="font-size: 24px; color: green;"></span></a>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/deleteVehiculo.php"><span class="glyphicon glyphicon-eye-open" style="font-size: 24px; color: black;"></span></a>
            </h3>
            <h3 class="page-header">Convenio
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/insertHorasConvenio.php"><span src="" class="glyphicon glyphicon-plus" style="font-size: 24px; color: green;"></span></a>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/deleteHorasConvenio.php"><span class="glyphicon glyphicon-eye-open" style="font-size: 24px; color: black;"></span></a>

            </h3>
            <h3 class="page-header">Tipo Franja
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/insertTipoFranja.php"><span src="" class="glyphicon glyphicon-plus" style="font-size: 24px; color: green;"></span></a>
                <a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/deleteTipoFranja.php"><span class="glyphicon glyphicon-eye-open" style="font-size: 24px; color: black;"></span></a>

            </h3>
            <h3 class="page-header">Horario
                <a href="<?php echo self::getUrlRaiz() ?>/Vista/Administracion/insertHorario.php"><span src="" class="glyphicon glyphicon-plus" style="font-size: 24px; color: green;"></span></a>
                <a href="<?php echo self::getUrlRaiz() ?>/Vista/Administracion/deleteHorario.php"><span class="glyphicon glyphicon-eye-open" style="font-size: 24px; color: black;"></span></a>
            </h3>
            <h3 class="page-header">Horario-Trabajador
                <a href="<?php echo self::getUrlRaiz() ?>/Vista/Administracion/insertHorarioTrabajador.php"><span src="" class="glyphicon glyphicon-plus" style="font-size: 24px; color: green;"></span></a>
                <a href="<?php echo self::getUrlRaiz() ?>/Vista/Administracion/filtroHorarioTrabajador.php"><span class="glyphicon glyphicon-eye-open" style="font-size: 24px; color: black;"></span></a>
            </h3>




        <?php
        require_once __DIR__ . "/../Plantilla/pie.php";
    }

/*****************************************************/
/* TRABAJADOR */
/*****************************************************/

        public static function insertTrabajador()
        {

            parent::setOn(true);
            parent::setRoot(true);

            require_once __DIR__ . "/../Plantilla/cabecera.php";
            //<?php echo parent::getUrlRaiz()
            $empresas = \Controlador\Administracion\Controlador::getAllEmpresas();
            $perfiles = \Controlador\Administracion\Controlador::getAllPerfiles();

        ?>
        <div class="container ins">
            <form name="insertTrabajador" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo self::getUrlRaiz()?>/Controlador/Administracion/Router.php">
                <fieldset>
                    <legend>Añadir Trabajador</legend>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">DNI:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="dni" maxlength="9" required>
                        </div>
                    </div><!--CONTRASEÑA EN CREAR TRABAJADOR PABLO-->
					<div class="form-group">
					
						<label class="control-label col-sm-2 col-md-2">Contraseña</label>
						<div class="col-sm-4 col-md-3">
						
							<input class="form-control" type="password" name="pass" maxlength="15" required>
						
						</div>
					
					</div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Nombre:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="nombre" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Apellido 1:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="apellido1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Apellido 2:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="apellido2" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Foto:</label>
                        <div class="col-sm-4 col-md-3">
                            <input name="foto" type="file" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Teléfono:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="telefono" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Empresa:</label>
                        <div class="col-sm-4 col-md-3">
                            <select class="form-control" name="empresa" onchange="SincronizarCentrosConEmpresasALaFormaGuarra()">
                                <?php
                                foreach($empresas as $empresa){

                                    ?>
                                    <option rel="<?php echo $empresa->getId(); ?>" value="<?php echo $empresa->getId(); ?>"><?php echo $empresa->getNombre(); ?></option>
                                    <?php

                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Centro:</label>
                        <div class="col-sm-4 col-md-3">
                            <select class="form-control" name="centro">
                                <?php
                                foreach($empresas as $empresa) {
                                    foreach($empresa->getCentros() as $centro){
                                        ?>
                                        <option rel="<?php echo $empresa->getId(); ?>" value="<?php echo $centro->getId(); ?>"><?php echo $centro->getNombre(); ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                       <script>
                           //Funcion que muestra las option de los centros sincronizadamente a lo guarro Aitor I

                        function SincronizarCentrosConEmpresasALaFormaGuarra() {
                            OcultarYMostrar(document.getElementsByName("empresa")[0].value);
                        }

                        function OcultarYMostrar(id) {
                            nodos = document.getElementsByName("centro")[0].children;
                            for(var x = 0; x < nodos.length; x++){
                             if(nodos[x].getAttribute("rel") == id){
                                 nodos[x].style.display = "block";
                                 nodos[x].setAttribute("selected","selected");
                            }
                            else{
                                 nodos[x].style.display= "none";
                                 nodos[x].removeAttribute("selected");
                                }
                            }
                        }

                        SincronizarCentrosConEmpresasALaFormaGuarra();
                    </script>


                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Perfil:</label>
                        <div class="col-sm-4 col-md-3">
                            <select class="form-control" name="perfil">
                                <?php
                                for($x = 0; $x < sizeof($perfiles); $x++) {
                                    ?>
                                    <option value="<?php echo $perfiles[$x][1] ?>"><?php echo $perfiles[$x][1]  ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </fieldset>
                <div class="form-group"><!--Ganeko & Ibai-->
                    <div class="col-sm-1 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Añadir" name="addTrabajador">
                    </div>
            </form>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-sm-1">
                        <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                    </div>
                </div>
            </form>
        </div>
        <?php

            require_once __DIR__ . "/../Plantilla/pie.php";

        }

    public static function deleteTrabajador(){

        parent::setOn(true);
        parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";

        $trabajadores = Administracion\Controlador::getAllTrabajadores();
        $trabajadorSession = unserialize($_SESSION['trabajador']);

        //problema en funcion getALl Trabajadores
        ?>
        <h2 class="page-header">Trabajadores</h2>
        <div class="table-responsive col-md-offset-1 col-md-10">
            <table class="table table-bordered">
                <tr>
                    <th>DNI</th>
                    <th>NOMBRE</th>
                    <th>APELLIDOS</th>
                    <th>TELEFONO</th>
                    <th>CENTRO</th>
                    <th>PERFIL</th>
                    <th>ACCIÓN</th>
                </tr>
                <?php
                foreach($trabajadores as $trabajador) {
                    if ($trabajador->getDni() != $trabajadorSession->getDni()) {
                        ?>

                        <tr>
                            <td><?php echo $trabajador->getDni(); ?></td>
                            <td><?php echo $trabajador->getNombre(); ?></td>
                            <td><?php echo $trabajador->getApellido1() . " " . $trabajador->getApellido2(); ?></td>
                            <td><?php echo $trabajador->getTelefono(); ?></td>
                            <td><?php echo $trabajador->getCentro()->getNombre(); ?></td>
                            <td><?php echo substr(strrchr(get_class($trabajador), "\\"), 1); ?></td>
                            <td>
                                <form name="deleteTrabajador" method="post"
                                      action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                                    <button type="submit" name="eliminarTrabajador" value="Eliminar"
                                            style="border: none; background: none;"><span
                                            class="glyphicon glyphicon-remove"
                                            style="color:red; font-size: 1.5em"></span></button>
                                    <!-- Ganeko --> <button type="submit" name="vistaEditarFoto" value="Foto"
                                            style="border: none; background: none;"><span
                                            class="glyphicon glyphicon-picture"
                                            style="color:deepskyblue; font-size: 1.5em"></span></button>
                                            <button type="submit" name="vistaEditarPass" value="Pass"
                                            style="border: none; background: none;"><span
                                            class="glyphicon glyphicon-pencil"
                                            style="color:black; font-size: 1.5em"></span></button>
                                    <input type="hidden" name="dni" value="<?php echo $trabajador->getDni(); ?>">
                                </form>
                            </td>
                        </tr>

                        <?php

                    }

                }
                        ?>
                    </table>
                </div>
                <form name="deleteTrabajador" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-md-10 col-md-offset-1"><!-- Ganeko -->
                         <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
                    </div>
                </form>

                <?php

        require_once __DIR__ . "/../Plantilla/pie.php";
    }

/*****************************************************/
/* EMPRESA */
/*****************************************************/

        public static function insertEmpresa()
        {

            parent::setOn(true);
            parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        ?>
        <div class="container ins">
            <form class="form-horizontal" name="insertTrabajador" method="post" action="<?php echo self::getUrlRaiz()?>/Controlador/Administracion/Router.php"><br/>
                <fieldset>
                    <legend>Añadir Empresa</legend>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Nombre:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="nombre" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">NIF:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="nif" maxlength="9" required>
                        </div>
                    </div>
                </fieldset>
                <div class="form-group"><!--Ganeko & Ibai-->
                    <div class="col-sm-1 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Añadir" name="addEmpresa">
                    </div>
            </form>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-sm-1">
                        <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                    </div>
                </div>
            </form>
        </div>
        <?php
        require_once __DIR__ . "/../Plantilla/pie.php";

        }

        public static function deleteEmpresa()
        {

            parent::setOn(true);
            parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";

        $empresas = Administracion\Controlador::getAllEmpresas();
        if(is_null($empresas)){
            echo "no hay empresas";
        }else {
            ?>
            <h2 class="page-header">Empresas</h2>
            <div class="table-responsive col-md-offset-1 col-md-10">
                <table class="table table-bordered">
                    <tr>
                        <th>EMPRESA</th>
                        <th>NIF</th>
                        <th>ACCIÓN</th>
                    </tr>
                    <?php
                    foreach ($empresas as $empresa) {
                        ?>
                        <tr>
                            <td><?php echo $empresa->getNombre(); ?></td>
                            <td><?php echo $empresa->getNif(); ?></td>
                            <td>
                                <form name="deleteTrabajador" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                                    <button type="submit" name="eliminarEmpresa" value="Eliminar"
                                            style="border: none; background: none;"><span
                                            class="glyphicon glyphicon-remove"
                                            style="color:red; font-size: 1.5em"></span></button>
                                    <button type="submit" name="vistaEditarEmpresa" value="Editar"
                                            style="border: none; background: none;"><span
                                            class="glyphicon glyphicon-pencil"
                                            style="color:black; font-size: 1.5em"></span></button>
                                    <input type="hidden" name="id" value="<?php echo $empresa->getId(); ?>">
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            <form name="deleteTrabajador" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                <div class="col-md-10 col-md-offset-1"><!--Ganeko-->
                    <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
                </div>
            </form>
            <?php
        }
        require_once __DIR__ . "/../Plantilla/pie.php";

        }

/*****************************************************/
/* CENTRO */
/*****************************************************/

        public static function insertCentro()
        {

            parent::setOn(true);
            parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        $empresas = \Modelo\BD\EmpresaBD::getAll();
        $centros = \Modelo\BD\CentroBD::getAll();
        ?>
        <div class="container ins">
            <form class="form-horizontal" name="insertCentro" method="post" action="<?php echo self::getUrlRaiz()?>/Controlador/Administracion/Router.php"><br/>
                <fieldset>
                    <legend>Añadir Centro</legend>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Nombre:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="nombre" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Localización:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="localizacion" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Empresa:</label>
                        <div class="col-sm-4 col-md-3">
                            <select class="form-control" name="empresa">
                                <?php
                                foreach($empresas as $empresa){
                                    ?>
                                    <option value="<?php echo $empresa->getId(); ?>"><?php echo $empresa->getNombre(); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </fieldset>
                <div class="form-group"><!--Ganeko & Ibai-->
                    <div class="col-sm-1 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Añadir" name="addCentro">
                    </div>
            </form>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-sm-1">
                        <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                    </div>
                </div>
            </form>

        </div>
        <?php
        require_once __DIR__ . "/../Plantilla/pie.php";
    }

        public static function deleteCentro()
        {

            parent::setOn(true);
            parent::setRoot(true);

            require_once __DIR__ . "/../Plantilla/cabecera.php";
            $centros = \Modelo\BD\CentroBD::getAll();
            if (is_null($centros)) {
                echo "No hay centros";
            } else {
                ?>
                <h2 class="page-header">Centros</h2>
                <div class="table-responsive col-md-offset-1 col-md-10">
                    <table class="table table-bordered">
                        <tr>
                            <th>CENTRO</th>
                            <th>LOCALIZACIÓN</th>
                            <th>EMPRESA</th>
                            <th>ACCIÓN</th>
                        </tr>
                        <?php
                        foreach ($centros as $centro) {
                            ?>
                            <form name="deleteTrabajador" method="post"
                                  action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                                <tr>
                                    <td><?php echo $centro->getNombre(); ?></td>
                                    <td><?php echo $centro->getLocalizacion(); ?></td>
                                    <td><?php echo $centro->getEmpresa()->getNombre(); ?></td>
                                    <td>
                                        <button type="submit" name="eliminarCentro" value="Eliminar"
                                                style="border: none; background: none;"><span
                                                class="glyphicon glyphicon-remove"
                                                style="color:red; font-size: 1.5em"></span></button>
                                        <button type="submit" name="vistaEditarCentro" value="Editar"
                                                style="border: none; background: none;"><span
                                                class="glyphicon glyphicon-pencil"
                                                style="color:black; font-size: 1.5em"></span></button>
                                    </td>
                                </tr>
                                <input type="hidden" name="id" value="<?php echo $centro->getId(); ?>">
                            </form>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <form name="deleteTrabajador" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-md-10 col-md-offset-1"><!--Ganeko-->
                        <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
                    </div>
                </form>
                <?php
            }
            require_once __DIR__ . "/../Plantilla/pie.php";
        }

/*****************************************************/
/* ESTADO */
/*****************************************************/

        public static function insertEstado()
        {

            parent::setOn(true);
            parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        ?>
        <div class="container ins">
            <form class="form-horizontal" name="insertTrabajador" method="post" action="<?php echo self::getUrlRaiz()?>/Controlador/Administracion/Router.php"><br/>
                <fieldset>
                    <legend>Añadir Estado</legend>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Nombre:</label>
                        <div class="col-sm-4 col-md-3">
                           <input class="form-control" type="text" name="tipo">
                        </div>
                    </div>
                </fieldset>
                <div class="form-group"><!--Ganeko & Ibai-->
                    <div class="col-sm-1 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Añadir" name="addEstado">
                    </div>
            </form>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-sm-1">
                        <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                    </div>
                </div>
            </form>
        </div>
        <?php
        require_once __DIR__ . "/../Plantilla/pie.php";

    }

    public static function deleteEstado(){

        parent::setOn(true);
        parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        $estados = Administracion\Controlador::getAllEstados();
        ?>
        <h2 class="page-header">Estados</h2>
        <div class="table-responsive col-md-offset-1 col-md-10">
            <table class="table table-bordered">
                    <tr>
                        <th>ESTADO</th>
                        <th>ACCIÓN</th>
                    </tr>
                    <?php
                    foreach($estados as $estado) {
                        ?>
                            <tr>
                                <td><?php echo $estado->getTipo(); ?></td>
                                <td>
                                    <form name="deleteEstado" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                                        <input type="submit" name="eliminarEstado" value="Eliminar">
                                        <input type="hidden" name="id" value="<?php echo $estado->getId(); ?>">
                                    </form>
                                </td>
                            </tr>
                        <?php
                    }
                    ?>
            </table>
        </div>
        <form name="deleteEstado" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Gerencia/Router.php">
            <div class="col-md-10 col-md-offset-1"><!-- Ganeko -->
                <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
            </div>
        </form>

        <?php
        require_once __DIR__ . "/../Plantilla/pie.php";

    }

/*****************************************************/
/* VEHICULO */
/*****************************************************/

        public static function insertVehiculo()
        {

            parent::setOn(true);
            parent::setRoot(true);

            $centros = Administracion\Controlador::getAllCentros();
            require_once __DIR__ . "/../Plantilla/cabecera.php";
            ?>
            <div class="container ins">
                <form class="form-horizontal" name="insertTrabajador" method="post"
                      action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php"><br/>
                    <fieldset>
                        <legend>Añadir Vehiculo</legend>
                        <div class="form-group">
                            <label class="control-label col-sm-2 col-md-2">Centro:</label>
                            <div class="col-sm-4 col-md-3">
                                <select class="form-control" name="centro">
                                    <?php
                                    foreach ($centros as $indice => $valor) {
                                        ?>
                                        <option
                                            value="<?php echo $valor->getId() ?>"><?php echo $valor->getNombre() ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2 col-md-2">Matrícula:</label>
                            <div class="col-sm-4 col-md-3">
                                <input class="form-control" type="text" name="matricula" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2 col-md-2">Marca:</label>
                            <div class="col-sm-4 col-md-3">
                                <input class="form-control" type="text" name="marca" required>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group"><!--Ganeko & Ibai-->
                        <div class="col-sm-1 col-sm-offset-2">
                            <input class="btn btn-primary" type="submit" value="Añadir" name="addVehiculo">
                        </div>
                </form>
                <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                        <div class="col-sm-1">
                            <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                        </div>
                    </div>
                </form>
            </div>
            <?php

        require_once __DIR__ . "/../Plantilla/pie.php";
        }

        public static function deleteVehiculo()
        {

            parent::setOn(true);
            parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        $vehiculos = Administracion\Controlador::getAllVehiculos();
            if(is_null($vehiculos)){
                echo "No hay vehiculos";
            }else {
                ?>
                <h2 class="page-header">Vehículos</h2>
                <div class="table-responsive col-md-offset-1 col-md-10">
                    <table class="table table-bordered">
                        <tr>
                            <th>MATRICULA</th>
                            <th>MARCA</th>
                            <th>CENTRO</th>
                            <th>ACCIÓN</th>
                        </tr>
                        <?php
                        foreach ($vehiculos as $vehiculo) {
                            ?>
                            <tr>
                                <td><?php echo $vehiculo->getMatricula(); ?></td>
                                <td><?php echo $vehiculo->getMarca(); ?></td>
                                <td><?php echo $vehiculo->getCentro()->getNombre(); ?></td>
                                <td>
                                    <form name="deleteEstado" method="post"
                                          action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                                        <button class="btn btn-primary" type="submit" name="eliminarVehiculo"
                                                value="Eliminar" style="border: none; background: none;"><span
                                                class="glyphicon glyphicon-remove"
                                                style="color:red; font-size: 1.5em"></span></button>
                                        <button class="btn btn-primary" type="submit" name="vistaEditarVehiculo"
                                                value="Editar" style="border: none; background: none;"><span
                                                class="glyphicon glyphicon-pencil"
                                                style="color:black; font-size: 1.5em"></span></button>
                                        <input type="hidden" name="id" value="<?php echo $vehiculo->getId(); ?>">
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <form name="updateFoto" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-md-10 col-md-offset-1"><!--  PABLO  -->
                         <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
                    </div>
                </form>
                <?php
            }
        require_once __DIR__ . "/../Plantilla/pie.php";

    }

/*****************************************************/
/* HORAS CONVENIO */
/*****************************************************/

    public static function insertHorasConvenio(){

            parent::setOn(true);
            parent::setRoot(true);

        $centros=Administracion\Controlador::getAllCentros();

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        ?>
        <div class="ins">
            <form class="form-horizontal" name="insertTrabajador" method="post" action="<?php echo self::getUrlRaiz()?>/Controlador/Administracion/Router.php"><br/>
                <fieldset>
                    <legend>Añadir Convenio</legend>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Centro:</label>
                        <div class="col-sm-4 col-md-3">
                            <select class="form-control" name="centro">
                                <?php
                                foreach($centros as $indice => $valor){
                                    ?>
                                    <option value="<?php echo $valor->getId()?>"><?php echo $valor->getNombre()?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Número de horas al año:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="number" name="horasAnual" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Denominación:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="denominacion" required>
                        </div>
                    </div>
                    </fieldset>
                <div class="form-group"><!--Ganeko & Ibai-->
                    <div class="col-sm-1 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Añadir" name="addHorasConvenio">
                    </div>
            </form>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-sm-1">
                        <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                    </div>
                </div>
            </form>
        </div>
        <?php
        require_once __DIR__ . "/../Plantilla/pie.php";
    }

        public static function deleteHorasConvenio()
        {

            parent::setOn(true);
            parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        $horasconvenio = Administracion\Controlador::getAllHorasConvenio();
            if(is_null($horasconvenio)){
                echo "no hay horas convenio";
            }else {
                ?>
                <h2 class="page-header">Convenios</h2>
                <div class="table-responsive col-md-offset-1 col-md-10">
                    <table class="table table-bordered">
                        <tr>
                            <th>CENTRO</th>
                            <th>DENOMINACION</th>
                            <th>HORAS</th>
                            <th>ACCIÓN</th>
                        </tr>
                        <?php
                        foreach ($horasconvenio as $horaconvenio) {
                            ?>
                            <tr>
                                <td><?php echo $horaconvenio->getCentro()->getNombre(); ?></td>
                                <td><?php echo $horaconvenio->getDenominacion() ?></td>
                                <td><?php echo $horaconvenio->getHorasAnual(); ?></td>
                                <td>
                                    <form name="deleteEstado" method="post"
                                          action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                                        <button type="submit" name="eliminarHorasConvenio" value="Eliminar"
                                                style="border: none; background: none;"><span
                                                class="glyphicon glyphicon-remove"
                                                style="color:red; font-size: 1.5em"></span></button>
					<button type="submit" name="vistaEditarConvenio" value="Editar"
                                                style="border: none; background: none;"><span
                                                class="glyphicon glyphicon-pencil"
                                                style="color:black; font-size: 1.5em"></span></button>
                                        <input type="hidden" name="id" value="<?php echo $horaconvenio->getId(); ?>">
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <form name="deleteEstado" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-md-10 col-md-offset-1"><!--Ganeko-->
                        <div class="pull-right">

                            <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                        </div>
                    </div>
                </form>
                <?php
            }
        require_once __DIR__ . "/../Plantilla/pie.php";

    }

        public static function updateHorasConvenio(){

        parent::setOn(true);
        parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        $hora = Administracion\Controlador::getConvenioById($_SESSION['id']);
            ?>
            <h2 class="page-header">Convenios</h2>
            <div class="table-responsive col-md-offset-1 col-md-10">
                <table class="table table-bordered">
                    <tr>
                        <th>NOMBRE</th>
                        <th>HORAS</th>
                        <th>CENTRO</th>
                        <th>NUEVAS HORAS</th>
                        <th>ACCIÓN</th>
                    </tr>
                        <tr>
                            <td><?php echo $hora->getDenominacion(); ?></td>
                            <td><?php echo $hora->getHorasAnual(); ?></td>
                            <td><?php echo $hora->getCentro()->getNombre(); ?></td>
                            <form name="deleteEstado" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                            <td>

                                    <input type="text" name="nuevo" size="5" placeholder="1200">
                                    <input type="hidden" name="id" value="<?php echo $hora->getId(); ?>">

                            </td>
                            <td>

                                    <button type="submit" name="updateHorasConvenio" value="Editar"
                                            style="border: none; background: none;"><span
                                            class="glyphicon glyphicon-edit"
                                            style="color:blue; font-size: 1.5em"></span></button>
                                    <input type="hidden" name="id" value="<?php echo $hora->getId(); ?>">

                            </td>
                            </form>
                        </tr>
                </table>
            </div>
            <form name="deleteEstado" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                <div class="col-md-10 col-md-offset-1"><!--Ganeko-->
                    <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
                </div>
            </form>
            <?php
            require_once __DIR__ . "/../Plantilla/pie.php";
    }


/*****************************************************/
/* TIPO FRANJA */
/*****************************************************/

    public static function updateTipoFranja(){

        parent::setOn(true);
        parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        $franjas = Administracion\Controlador::getAllTiposFranjas();
        if(is_null($franjas)){
            echo "no hay horas";
        }else {
            ?>
            <h2 class="page-header">Update de Franjas</h2>
            <div class="table-responsive col-md-offset-1 col-md-10">
                <table class="table table-bordered">
                    <tr>
                        <th>TIPO</th>
                        <th>PRECIO</th>
                        <th>NUEVO PRECIO</th>
                        <th>ACCIÓN</th>
                    </tr>
                    <?php
                    foreach ($franjas as $franja) {
                        ?>
                        <tr>
                            <td><?php echo $franja->getTipo(); ?></td>
                            <td><?php echo $franja->getPrecio(); ?></td>
                            <form name="deleteEstado" method="post"
                                  action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                                <td><input type="text" name="nuevo" size="5" placeholder="00.00"></td>
                                <td>
                                    <button type="submit" name="updateTipoFranja" value="Editar"
                                            style="border: none; background: none;"><span
                                            class="glyphicon glyphicon-edit"
                                            style="color:blue; font-size: 1.5em"></span></button>
                                    <input type="hidden" name="id" value="<?php echo $franja->getId(); ?>">
                                </td>
                            </form>
                        </tr>
                        <?php
                        }
                        ?>
                </table>
            </div>
            <form name="deleteEstado" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                <div class="col-md-10 col-md-offset-1"><!--Ganeko-->
                    <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
                </div>
            </form>
            <?php
        }
        require_once __DIR__ . "/../Plantilla/pie.php";

    }

    public static function insertTipoFranja(){

        parent::setOn(true);
        parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        ?>
        <div class="container">
            <form class="form-horizontal" name="insertTipoFranja" method="post" action="<?php echo self::getUrlRaiz()?>/Controlador/Administracion/Router.php"><br/>
                <fieldset>
                    <legend>Añadir Tipo Horario</legend>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Tipo de Horario:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="tipo" placeholder="Mañana, tarde, noche..." required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2">Precio:</label>
                        <div class="col-sm-4 col-md-3">
                            <input class="form-control" type="text" name="precio" required>
                        </div>
                    </div>
                </fieldset>
                <div class="form-group"><!--Ganeko & Ibai-->
                    <div class="col-sm-1 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Añadir" name="addTipoFranja">
                    </div>
            </form>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-sm-1">
                        <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                    </div>
                </div>
            </form>
        </div>
        <?php
        require_once __DIR__ . "/../Plantilla/pie.php";

    }

    public static function deleteTipoFranja(){

        parent::setOn(true);
        parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
        $tipos = Administracion\Controlador::getAllTiposFranjas();
        if(is_null($tipos)){
            echo "no hay tipos";
        }else {
            ?>
            <h2 class="page-header">Tipos de Franjas</h2>
            <div class="table-responsive col-md-offset-1 col-md-10">
                <table class="table table-bordered">
                    <tr>
                        <th>TIPO</th>
                        <th>PRECIO</th>
                        <th>ACCIÓN</th>
                    </tr>
                    <?php
                    foreach ($tipos as $tipo) {
                        ?>
                        <tr>
                            <td><?php echo $tipo->getTipo(); ?></td>
                            <td><?php echo $tipo->getPrecio(); ?></td>
                            <td>
                                <form name="deleteEstado" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                                    <button class="btn btn-primary" type="submit" name="deleteTipoFranja"
                                            value="Eliminar" style="border: none; background: none;"><span
                                            class="glyphicon glyphicon-remove"
                                            style="color:red; font-size: 1.5em"></span></button>
                                    <input type="hidden" name="id" value="<?php echo $tipo->getId(); ?>">
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            <form name="deleteEstado" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                <div class="col-md-10 col-md-offset-1">
                    <div class="pull-right">
                        <input class="btn btn-primary" type="submit" name="vistaUpdateTipoFranja" value="Editar">
                        <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                    </div>
                </div>
            </form>
            <?php
        }
        require_once __DIR__ . "/../Plantilla/pie.php";

    }

/*****************************************************/
/* PASSWORD */
/*****************************************************/

    public static function updatePassword(){

        parent::setOn(true);
        parent::setRoot(true);

        require_once __DIR__ . "/../Plantilla/cabecera.php";
            ?>

            <h2 class="page-header">Trabajadores</h2>
            <div class="table-responsive col-md-offset-1 col-md-10">
                <table class="table table-bordered">
                    <tr>
                        <th>DNI</th>
                        <th>Nueva contraseña</th>
                        <th>Acción</th>
                    </tr>
                    <form name="updatePassword" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="trabajador" value="<?php echo $_SESSION["dni"]; ?>">

                            </td>
                            <td><input class="form-control" type="password" name="password"/></td>
                            <td>
                                <button type="submit" name="updatePassword" value="Cambiar"
                                        style="border: none; background: none"><span class="glyphicon glyphicon-edit"
                                                                                     style="color: blue; font-size: 1.5em"></span>
                                </button>
                            </td>
                        </tr>
                    </form>
                </table>
            </div>
            <form name="updatePassword" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                <div class="col-md-10 col-md-offset-1"><!--Ganeko-->
                    <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
                </div>
            </form>

            <?php

            require_once __DIR__ . "/../Plantilla/pie.php";
            require_once __DIR__ . "/../Plantilla/cabecera.php";
            $horas = Administracion\Controlador::getAllHorasConvenio();
            ?>

            <table>
                <tr>
                    <th>NOMBRE</th>
                    <th>HORAS</th>
                    <th>NUEVO PRECIO</th>
                    <th>CENTRO</th>
                    <th>ACCIÓN</th>
                </tr>
                <?php
                foreach ($horas as $hora) {
                    ?>
                    <form name="deleteEstado" method="post"
                          action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                        <tr>
                            <td><?php echo $hora->getDenominacion(); ?></td>
                            <td><?php echo $hora->getHorasAnual(); ?></td>
                            <td><?php echo $hora->getCentro()->getNombre(); ?></td>
                            <td><input type="text" name="nuevo" size="5" placeholder="1200"></td>
                            <td><input type="submit" name="updateHorasConvenio" value="Editar"></td>
                        </tr>
                        <input type="hidden" name="id" value="<?php echo $hora->getId(); ?>">
                    </form>
            </table>
            <?php
        }
            require_once __DIR__ . "/../Plantilla/pie.php";

        }

/*****************************************************/
/* HORARIO */
/*****************************************************/

        public static function insertarHorario()
        {
            parent::setOn(true);
            parent::setRoot(true);

            require_once __DIR__ . "/../Plantilla/cabecera.php";
            $franjas = Administracion\Controlador::getAllFranjas();

            ?>
            <form class="form-horizontal" name="insertarFranja" method="post"
                  action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2">Nombre horario:</label>
                    <div class="col-sm-4 col-md-3">
                        <input class="form-control" type="text" name="horario" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2">Hora inicio: </label>
                    <div class="col-sm-4 col-md-3">
                        <select class="form-control" name="horaInicio">
                            <?php
                            foreach ($franjas as $valor) {
                                ?>
                                <option value="<?php echo $valor->getId() ?>"><?php echo $valor->getHoraInicio() ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2">Hora fin: </label>
                    <div class="col-sm-4 col-md-3">
                        <select class="form-control" name="horaFin">
                            <?php
                            foreach ($franjas as $valor) {
                                ?>
                                <option value="<?php echo $valor->getId() ?>"><?php echo $valor->getHoraFin() ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group"><!--Ganeko-->
                    <div class="col-sm-1 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Añadir" name="addHorario">
                    </div>
            </form>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-sm-1">
                        <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                    </div>
                </div>
            </form>
            </div>
            <?php

            require_once __DIR__ . "/../Plantilla/pie.php";
        }

        public static function deleteHorario()
        {
            parent::setOn(true);
            parent::setRoot(true);
            require_once __DIR__ . "/../Plantilla/cabecera.php";
            $horarios = Administracion\Controlador::getAllHorarios();
            if(is_null($horarios)){
                echo "No hay horarios";
            }else {
                ?>
                <div class="table-responsive col-md-offset-1 col-md-10">
                    <table class="table table-bordered">
                        <tr>
                            <th>HORARIO</th>
                            <th>HORA INICIO</th>
                            <th>HORA FIN</th>
                            <th>ACCIÓN</th>
                        </tr>
                        <?php
                        foreach ($horarios as $horario) {
                            ?>
                            <form name="deleteHorario" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                                <tr>
                                    <td><?php echo $horario->getTipo() ?></td>
                                    <td><?php echo Administracion\Controlador::getHoraInicioHorario($horario)  ?></td>
                                    <td><?php echo Administracion\Controlador::getHoraFinHorario($horario)  ?></td>
								    <!-- PABLO cambiar botones por glypicon unico boton volver-->
                                    <td><button type="submit" name="eliminarHorario" value="Eliminar" style="border: none; background: none;"><span class="glyphicon glyphicon-remove" style="color:red; font-size: 1.5em"></span></button></td>
                                </tr>
                                <input type="hidden" name="id" value="<?php echo $horario->getId(); ?>">
                            </form>
                            <?php
                        }
                        ?>
                    </table>
                </div>
				    <form method='post' name="atrasHorarios" action='<?php echo self::getUrlRaiz() ?>/Vista/Administracion/Administracion.php?cod=1'>
				    <div class='form-group'>
				        <div class="col-md-10 col-md-offset-1"><!-- Pablo Ganeko -->
					        <input type="submit" value="Volver" class='cerrar btn btn-warning pull-right' name='volver'>
					    </div>
				    </div>
				    </form>
                <?php
            }
            require_once __DIR__ . "/../Plantilla/pie.php";
        }

/*****************************************************/
/* HORARIO TRABAJADOR */
/*****************************************************/

        public static function insertHorarioTrabajador()
        {
            parent::setOn(true);
            parent::setRoot(true);

            require_once __DIR__ . "/../Plantilla/cabecera.php";
            ?>
            <script src="<?php echo parent::getUrlRaiz() ?>/Vista/Administracion/funciones.js"></script>
            <?php
            $trabajadores = Administracion\Controlador::getAllTrabajadores();
            $horarios = Administracion\Controlador::getAllHorarios();
            $calendarios = Administracion\Controlador::getAllCalendarios();
            ?>

            <form class="form-horizontal" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2">Trabajador: </label>
                    <div class="col-sm-4 col-md-3">
                        <select class="form-control" name="trabajador" id="trabajador" required>
                            <option value="" disabled selected="selected">Selecciona...</option>
                            <?php
                            foreach ($trabajadores as $trabajador) {
                                ?>
                                <option
                                    value="<?php echo $trabajador->getDni() ?>"><?php echo $trabajador->getDni() . " -- " . $trabajador->getNombre() ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2">Semana: </label>
                    <div class="col-sm-4 col-md-3" id="semanas"> <!-- Pablo -->
					<select class="form-control" name="semana"><div style="float: left";>
						<?php
							for($x = 1;$x <= 52; $x++){
								?>
								
									
									
										<option value="<?php echo $x ?>"><?php echo $x ?> </option>
									
									
								
								<?php
							}?></div></select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2">Horario: </label>
                    <div class="col-sm-4 col-md-3">
                        <select class="form-control" name="horario">
                            <?php
                            foreach ($horarios as $horario) {
                                ?>
                                <option value="<?php echo $horario->getId() ?>"><?php echo $horario->getTipo() ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2 col-md-2">Calendario: </label>
                  <div class="col-sm-4 col-md-3">
                        <select class="form-control" name="calendario">
                            <?php
                            foreach ($calendarios as $calendario) {
                                ?>
                                <option value="<?php echo $calendario->getId() ?>"><?php echo $calendario->getId() ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                    </fieldset>
                <div class="form-group"><!--Ganeko & Ibai-->
                    <div class="col-sm-1 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Añadir" name="añadirHorarioTrabajador">
                    </div>
            </form>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-sm-1">
                        <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                    </div>
                </div>
            </form>

            <?php
            require_once __DIR__ . "/../Plantilla/pie.php";
        }

        // Función para mostrar filtros de empresa,centro... al mostrar horarios-trabajador a administracion
        // Ibai
        public static function filtroHorarioTrabajador(){
            parent::setOn(true);
            parent::setRoot(true);
            require_once __DIR__ . "/../Plantilla/cabecera.php";
            ?>
            <script src="<?php echo parent::getUrlRaiz() ?>/Vista/Plantilla/JS/jquery-2.2.1.min.js"></script>


                <form id="formulario" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                <div class="ins form-inline">
                    <div class="form-group col-xs-6 col-sm-3">
                        <label class="col-2">Empresa</label>
                        <div class="col-9" id="divEmpresas">
                            <select class="form-control" name="empresa" id="selectEmpresa">

                            </select>
                        </div>
                    </div>
                    <div class="form-group col-xs-6 col-sm-3">
                      <label class="col-2">Centro</label>
                      <div class="col-9">
                            <select class="form-control" name="centro" id="selectCentro">

                            </select>
                        </div>
                    </div>
                    <div class="form-group col-xs-6 col-sm-3">
                      <label class="col-2">Calendario</label>
                      <div class="col-9">
                            <select class="form-control" name="calendario" id="selectCalendario">

                            </select>
                        </div>
                    </div>
                    <div class="form-group col-xs-6 col-sm-3">
                      <label class="col-2">Mes</label>
                      <div class="col-9">
                            <select class="form-control" name="mes" id="selectMes">

                            </select>
                        </div>
                    </div>
                    <div class="form-group container text-center" style="margin-top:20px;">
                        <div class="">
                            <button class="btn btn-primary"  type="submit" name="mostrarHorarioTrabajador" id="aplicarFiltros">Buscar</button>
                            <button class="btn btn-danger"  type="button" id="resetFiltros">Reset</button>
                            <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                        </div>
                    </div>
                </div>
                </form>
            <?php
            require_once __DIR__ . "/../Plantilla/pie.php";
        }

        public static function deleteHorarioTrabajador()
        {
            parent::setOn(true);
            parent::setRoot(true);
            $horarioTrabajador = Administracion\Controlador::getAllHorarioTrabajadorFiltrado($_SESSION["filtrosHorarios"]);

            require_once __DIR__ . "/../Plantilla/cabecera.php";
            ?>
            <div class="table-responsive col-md-offset-1 col-md-10">
                <table class="table table-bordered">
                    <tr>
                        <th>TRABAJADOR</th>
                        <th>SEMANA</th>
                        <th>HORARIO</th>
                        <th>CALENDARIO</th> <!--Ibai-->
                        <th>ACCIÓN</th>
                    </tr>
                    <?php
                    for($x = 0 ; $x<count($horarioTrabajador); $x++) { /* PABLO */
                        ?>
                        <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                            <?php
                                echo Administracion\Controlador::incidenciasHorarioTrabajador($horarioTrabajador[$x]->getTrabajador()->getDni(), $horarioTrabajador[$x]->getNumeroSemana(), $horarioTrabajador[$x]->getCalendario()->getId()) ?
                                    "<tr style='background-color:lightcoral'>":
                                    "<tr>";
                             ?>
                                <td><?php echo $horarioTrabajador[$x]->getTrabajador()->getDni() ?></td>
                                <td><?php echo $horarioTrabajador[$x]->getNumeroSemana() ?></td>
                                <td><?php echo $horarioTrabajador[$x]->getHorario()->getTipo() ?></td><!-- PABLO -->
                                <td><?php echo $horarioTrabajador[$x]->getCalendario()->getId()?></td>
								<input type='hidden' value='<?php echo $x ?>' name='dht_semana'>
                                <td><button type="submit" name="updateHorarioTrabajador" value="Update" style="border: none; background: none"><span class='glyphicon glyphicon-pencil' style='color:blue; font-size:24px;'></span></button>
								<button type="submit" name="borrarHorarioTrabajador" value="Eliminar" style="border: none; background: none"><span class="glyphicon glyphicon-remove" style="color: red; font-size: 1.5em"></span></button></td>

                            </tr>
                            <input type="hidden" value="<?php echo $horarioTrabajador[$x]->getId() ?>" name="id">
                        </form>
                        <?php
                    }
                    ?>
                </table>
            </div>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                <div class="col-md-10 col-md-offset-1"><!-- Ganeko -->
                    <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
                </div>
            </form>
            <?php


            require_once __DIR__ . "/../Plantilla/pie.php";
        }

/*****************************************************/
/* PARTE LOGISTICA */
/*****************************************************/

        public static function viewParteLog($parte,$viajes)
        {
            parent::setOn(true);
            parent::setRoot(true);
            require_once __DIR__ . "/../Plantilla/cabecera.php";

            echo "<h3 class='text-left'><strong>Trabajador: ".$parte->getTrabajador()->getNombre()." ".$parte->getTrabajador()->getApellido1()." ".$parte->getTrabajador()->getApellido2()."</strong></h3><br/>";

            echo "<div class='table-responsive'>";
                        //Calculo de horas extras

            $numhorasrealizadas = 0;
			foreach($viajes as $viaje){
                $horaInicio = $viaje->getHoraInicio();
				$horaFin = $viaje->getHoraFin();

				$numhorasrealizadas = $numhorasrealizadas + (substr($horaFin,0,2)-substr($horaInicio,0,2)) ;
            }

            $fecha = $parte->getFecha();

            $semana = date('W',strtotime($fecha));

            $trabajador = $parte->getTrabajador();


			$horariosTrabajador = $trabajador->getHorariosTrabajadorBySemana($semana);

            if (!is_array($horariosTrabajador))
            {
                 $horariosTrabajador = array($horariosTrabajador);

            }
            foreach($horariosTrabajador as $horarioTrabajador)
			{

				$horariofranjas = $horarioTrabajador->getHorario()->getHorariosFranja();
				echo "<p class='col-xs-12'><strong>Horario asociado: ";
				echo $horariofranjas[0]->getFranja()->getHoraInicio()." - ".$horariofranjas[sizeof($horariofranjas)-1]->getFranja()->getHoraFin();
				echo "</p>";

			}

			$numhoras = 0;
			foreach($horariosTrabajador as $horarioTrabajador)
			{
				$numhoras = $numhoras + sizeof($horarioTrabajador->getHorario()->getHorariosFranja());
			}

            $extras = $numhorasrealizadas - $numhoras;
            if ($extras > 0)
            {
                echo "<p class='col-xs-12' style='color: red;'><strong>Horas extras: ";
			    echo $extras;
			    echo "</p>";
            }


			//Termina calculo de horas extras

                echo "<table class='table table-striped'><tr><th >ID</th><th >HORA INICIO</th><th >HORA FIN</th><th >VEHICULO</th><th >ALBARAN</th></tr>";
                foreach ($viajes as $viaje) {

                    echo "<tr> <td>" . $viaje->getId() . "</td><td>" . $viaje->getHoraInicio() . "</td><td>" . $viaje->getHoraFin() . "</td><td>" . $viaje->getVehiculo()->getMatricula() . "</td><td>" . $viaje->getAlbaran() . "</td></tr>";
                }
                ?>
                <tr><th colspan="5">OBSERVACIONES</th></tr>
                <tr><td colspan="5"><?php echo $parte->getNota();?></td></tr>
                <?php
                echo "</table>";



            ?>
            <a class="btn btn-warning pull-right" href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/Administracion.php?cod=2">Volver</a>
            </div>
            </div>
            <?php
            //echo '</div> </div><div><button id="close" class="btn-danger btn pull-right col-sm-2 cerrar">Volver</button></div>';

            require_once __DIR__ . "/../Plantilla/pie.php";
        }

        public static function editParteLogistica()
        {
            parent::setOn(true);
            parent::setRoot(true);
            require_once __DIR__ . "/../Plantilla/cabecera.php";

            $parte = unserialize($_SESSION['parte']);

            echo "<h3 class='text-left'><strong>Trabajador: ".$parte->getTrabajador()->getNombre()." ".$parte->getTrabajador()->getApellido1()." ".$parte->getTrabajador()->getApellido2()."</strong></h3><br/>";

            echo "<div class='table-responsive'>";
                        //Calculo de horas extras

            $numhorasrealizadas = 0;
            $viajes = array();

            if (!is_array($parte->getViajes()) && !is_null($parte->getViajes()))
            {
                $viajes[] = $parte->getViajes();
            }
            else{
                $viajes = $parte->getViajes();
            }
			foreach($viajes as $viaje){
                $horaInicio = $viaje->getHoraInicio();
				$horaFin = $viaje->getHoraFin();

				$numhorasrealizadas = $numhorasrealizadas + (substr($horaFin,0,2)-substr($horaInicio,0,2)) ;
            }

            $fecha = $parte->getFecha();

            $semana = date('W',strtotime($fecha));

            $trabajador = $parte->getTrabajador();


			$horariosTrabajador = $trabajador->getHorariosTrabajadorBySemana($semana);

            if (!is_array($horariosTrabajador))
            {
                 $horariosTrabajador = array($horariosTrabajador);

            }
            foreach($horariosTrabajador as $horarioTrabajador)
			{

				$horariofranjas = $horarioTrabajador->getHorario()->getHorariosFranja();
				echo "<p class='col-xs-12'><strong>Horario asociado: ";
				echo $horariofranjas[0]->getFranja()->getHoraInicio()." - ".$horariofranjas[sizeof($horariofranjas)-1]->getFranja()->getHoraFin();
				echo "</p>";

			}

			$numhoras = 0;
			foreach($horariosTrabajador as $horarioTrabajador)
			{
				$numhoras = $numhoras + sizeof($horarioTrabajador->getHorario()->getHorariosFranja());
			}

            $extras = $numhorasrealizadas - $numhoras;
            if ($extras > 0)
            {
                echo "<p class='col-xs-12' style='color: red;'><strong>Horas extras: ";
			    echo $extras;
			    echo "</p>";
            }


			//Termina calculo de horas extras
		    ?>
                <form action="<?php echo parent::getUrlRaiz();?>/Controlador/Administracion/Router.php" method="post">
                <table class='table table-striped'><tr><th >ID</th><th >HORA INICIO</th><th >HORA FIN</th><th >VEHICULO</th><th >ALBARAN</th></tr>
                <input type="hidden" name="idParte" id="idParte" value="<?php echo $parte->getId(); ?>"/>
                <?php
                $x = 1;
                foreach ($viajes as $viaje) {?>

                    <tr> <td><input type="hidden" name="id<?php echo $x; ?>" id="id<?php echo $x;?>" value="<?php echo $viaje->getId();?>"/><?php echo $viaje->getId();?></td><td><input type="time" name="HoraInicio<?php echo $x;?>" id="HoraInicio<?php echo $x;?>" value="<?php echo $viaje->getHoraInicio(); ?>"/></td><td><input type="time" name="HoraFin<?php echo $x;?>" id="HoraFin<?php echo $x;?>" value="<?php echo $viaje->getHoraFin(); ?>"/> </td>
                    <td><select name="Matricula<?php echo $x;?>" id="Matricula<?php echo $x;?>">
                     <?php
                     $vehiculos = BD\VehiculoBD::getAll();
                     foreach ($vehiculos as $vehiculo)
                     {
                        echo "<option value='".$vehiculo->getMatricula()."'";
                        if (strcmp($viaje->getVehiculo()->getMatricula(),$vehiculo->getMatricula()) == 0)
                        {
                            echo "selected='selected'";
                        }
                        echo ">".$vehiculo->getMatricula()."</option>";
                     }
                     ?>

                     </select></td>
                     <td><input type="text" name="Albaran<?php echo $x;?>" id="Albaran<?php echo $x;?>" value="<?php echo $viaje->getAlbaran();?>"/></td></tr>
                <?php
                $x++;
                }
                ?>
                <tr><th colspan="5">OBSERVACIONES</th></tr>
                <tr><td colspan="5"><textarea name="Nota" id="Nota"><?php echo $parte->getNota();?></textarea></td></tr>
                </table>
                    <!-- Ibai -->
                    <div class="form-group text-center" style="margin-top:20px;">
                        <button class="btn btn-primary" type="submit" name="guardarParteLogistica">Guardar</button>
                        <a class="btn btn-warning" href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/Administracion.php?cod=2">Volver</a>
                    </div>
                </form>
            </div>
            </div>
            <?php
            //echo '</div> </div><div><button id="close" class="btn-danger btn pull-right col-sm-2 cerrar">Volver</button></div>';

            require_once __DIR__ . "/../Plantilla/pie.php";
        }

/*****************************************************/
/* PARTE PRODUCCION */
/*****************************************************/

        public static function viewParteProd($parte,$estado)
        {
            parent::setOn(true);
            parent::setRoot(true);
            require_once __DIR__ . "/../Plantilla/cabecera.php";

		echo "<h3 class='text-left'><strong>Trabajador: ".$parte->getTrabajador()->getNombre()." ".$parte->getTrabajador()->getApellido1()." ".$parte->getTrabajador()->getApellido2()."</strong></h3><br/>";

		$parteProduccionTareas = $parte->getParteProduccionTareas();


		if(!is_null($parteProduccionTareas)){
			foreach ($parteProduccionTareas as $parteProduccionTarea)
			{
				echo "<link rel='stylesheet' type='text/css' href='".self::getUrlRaiz()."/Vista/Plantilla/CSS/ProduccionStyle.css'>";
				echo "<input type='hidden' id='contTareas' value='".sizeof($parteProduccionTareas)."'>";

					$tipo = $parteProduccionTarea->getTarea()->getTipo();

					echo "<div class='panel panel-default' rel='".$parteProduccionTarea->getId()."'>";

					echo "<div class='panel-heading container-fluid'><article class='col-xs-6 text-left'><h4 class='panel-title'><strong>".$parteProduccionTarea->getTarea()->getTipo()->getDescripcion().":</strong> <span class='lead small'>".$parteProduccionTarea->getTarea()->getDescripcion()."</span></h4></article>";

					if(strnatcasecmp($estado->getTipo(),"abierto")==0){ echo "<article class='col-xs-6'><a class='tOp eliminar_tarea' rel='".$parteProduccionTarea->getId()."'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a><!--<a class='tOp editar_tarea' rel='".$parteProduccionTarea->getId()."'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>--></article>";}

					echo '</div><div class="panel-body">';

					if(!empty($parteProduccionTarea->getNumeroHoras())){
						echo "<span class='col-sm-4 col-xs-12'>Numero Horas: ".$parteProduccionTarea->getNumeroHoras()."</span>";
					}

					if(!empty($parteProduccionTarea->getPaqueteEntrada())&&!empty($parteProduccionTarea->getPaqueteSalida())){
						echo "<span class='col-sm-4 col-xs-12'>Nº Entrada: ".$parteProduccionTarea->getPaqueteEntrada()."</span><span class='col-sm-4 col-xs-12'>Nº Salida: ".$parteProduccionTarea->getPaqueteSalida()."</span><span class='col-sm-4 col-xs-12'>Total: ".($parteProduccionTarea->getPaqueteSalida()-$parteProduccionTarea->getPaqueteEntrada())."</span>";
					}

					echo "</div></div>";

                }
				if(strnatcasecmp($estado->getTipo(),"abierto")==0){
					echo "<button type='button' class='btn btn-primary pCerrar' rel='".$parte->getId()."'>Cerrar Parte</button> ";
					echo "<button type='button' class='btn btn-danger pBorrar' rel='".$parte->getId()."'>Eliminar Parte</button>";
				}elseif(strnatcasecmp($estado->getTipo(),"cerrado")==0){
					echo "<div class='panel panel-default'><div class='panel-body' >";


					if(count($parte->getHorariosParte())==1){
						echo "<p class='col-xs-12'><strong>Horario realizado: Continua de ";
					}else{
						echo "<p class='col-xs-12'><strong>Horario realizado: Partida de ";
					}

					$x = 1;

					foreach($parte->getHorariosParte() as $horarioParte){
						if($x>1){echo " y ";}
						$x++;

						echo $horarioParte->getHoraEntrada()." - ".$horarioParte->getHoraSalida();



					}

					$fecha = $parte->getFecha();

                    $semana = date('W',strtotime($fecha));

					$trabajador = $parte->getTrabajador();

					$horariosTrabajador = $trabajador->getHorariosTrabajadorBySemana($semana);

                    if (!is_array($horariosTrabajador))
                    {
                        $horariosTrabajador = array($horariosTrabajador);

                    }

						foreach($horariosTrabajador as $horarioTrabajador)
						{

						    $horariofranjas = $horarioTrabajador->getHorario()->getHorariosFranja();
						    echo "<p class='col-xs-12'><strong>Horario asociado: ";
						    echo $horariofranjas[0]->getFranja()->getHoraInicio()." - ".$horariofranjas[sizeof($horariofranjas)-1]->getFranja()->getHoraFin();
						    echo "</p>";

						}
						//Calculo de horas extras

                            $numhorasrealizadas = 0;
					        foreach($parte->getHorariosParte() as $horarioParte){

						        $x++;
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
                            if ($extras > 0)
                            {
                                echo "<p class='col-xs-12' style='color: red;'><strong>Horas extras: ";
			                    echo $extras;
			                    echo "</p>";
                            }

                    //Termina calculo de horas extra

					echo "</strong></p><article>";

					if(!empty($parte->getAutopista())){
						echo "<span class='col-sm-4 col-xs-12'>Autopistas/Peajes: ".$parte->getAutopista()."€</span>";
					}else{
						echo "<span class='col-sm-4 col-xs-12'>Autopista/Peajes: 0€</span>";
					}

					if(!empty($parte->getDieta())){
						echo "<span class='col-sm-4 col-xs-12'>Dietas: ".$parte->getDieta()."€</span>";
					}else{
						echo "<span class='col-sm-4 col-xs-12'>Dietas: 0€</span>";
					}

					if(!empty($parte->getOtroGasto())){
						echo "<span class='col-sm-4 col-xs-12'>Otros Gastos: ".$parte->getOtroGasto()."€</span>";
					}else{
						echo "<span class='col-sm-4 col-xs-12'>Otros Gastos: 0€</span>";
					}

					echo "</article><article class='col-xs-12'>";

					if(!empty($parte->getIncidencia())){
						echo "<p><strong>Incidencia: </strong><br/>".$parte->getIncidencia()."</p>";
					}else{
						echo "<p><strong>Incidencia: </strong><br/>No hay ninguna incidencia.</p>";
					}

					echo "</div></div>";


			}
		}else{
			echo "<div class='panel panel-default'><div class='panel-body'>El Parte no tiene ninguna Tarea.</div></div>";
			if(strnatcasecmp($estado->getTipo(),"abierto")==0){
				echo "<button type='button' class='btn btn-danger pBorrar' rel='".$parte->getId()."'>Eliminar Parte</button>";
			}/*CAMBIAR BOTON PABLO*/
		}?>

            <a class="btn btn-warning pull-right" href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/Administracion.php?cod=2">Volver</a>

            <?php
            //echo '</div> </div><div><button id="close" class="btn-danger btn pull-right col-sm-2 cerrar">Volver</button></div>';

            require_once __DIR__ . "/../Plantilla/pie.php";
        }

        public static function editParteProduccion()
        {
            parent::setOn(true);
            parent::setRoot(true);
            require_once __DIR__ . "/../Plantilla/cabecera.php";

            $parte = unserialize($_SESSION['parte']);

		echo "<h3 class='text-left'><strong>Trabajador: ".$parte->getTrabajador()->getNombre()." ".$parte->getTrabajador()->getApellido1()." ".$parte->getTrabajador()->getApellido2()."</strong></h3><br/>";

		$parteProduccionTareas = $parte->getParteProduccionTareas();


		if(!is_null($parteProduccionTareas)){
		?>
		<form action="<?php echo parent::getUrlRaiz();?>/Controlador/Administracion/Router.php" method="post">
		<input type="hidden" name="idParte" id="idParte" value="<?php echo $parte->getId(); ?>"/>

		<?php
		    $x = 1;

			foreach ($parteProduccionTareas as $parteProduccionTarea)
			{
				echo "<link rel='stylesheet' type='text/css' href='".self::getUrlRaiz()."/Vista/Plantilla/CSS/ProduccionStyle.css'>";
				echo "<input type='hidden' id='contTareas' value='".sizeof($parteProduccionTareas)."'>";

					$tipo = $parteProduccionTarea->getTarea()->getTipo();
					$tareas = BD\TareaBD::getTareaByTipo($tipo);

					echo "<div class='panel panel-default' rel='".$parteProduccionTarea->getId()."'>";
                    ?>
                    <input type="hidden" name="id<?php echo $x; ?>" id="id<?php echo $x;?>" value="<?php echo $parteProduccionTarea->getId();?>"/>
					<div class='panel-heading container-fluid'><article class='col-xs-6 text-left'><h4 class='panel-title'><strong>
					<?php echo $parteProduccionTarea->getTarea()->getTipo()->getDescripcion();?>:</strong> <span class='lead small'>
					<select name="ParteProduccionTarea<?php echo $x;?>" id="ParteProduccionTarea<?php echo $x;?>">
					    <?php
                        foreach ($tareas as $tarea)
                        {?>
                            <option value="<?php echo $tarea->getId();?>"
                            <?php
                            if ($tarea->getId() == $parteProduccionTarea->getTarea()->getId())
                            {
                                echo "selected='selected'";
                            }
                             ?>
                            ><?php echo $tarea->getDescripcion();?></option>
                         <?php
                        }
                         ?>

                    </select>
					</span></h4></article>

                    <?php
					if(strnatcasecmp($parte->getEstado()->getTipo(),"abierto")==0){ echo "<article class='col-xs-6'><a class='tOp eliminar_tarea' rel='".$parteProduccionTarea->getId()."'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a><!--<a class='tOp editar_tarea' rel='".$parteProduccionTarea->getId()."'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>--></article>";}

					echo '</div><div class="panel-body">';

					if(!empty($parteProduccionTarea->getNumeroHoras())){?>

						<span class='col-sm-4 col-xs-12'>Numero Horas: <input type="number" name="NumeroHoras<?php echo $x;?>" id="NumeroHoras<?php echo $x;?>" min="1" value="<?php echo $parteProduccionTarea->getNumeroHoras();?>"/></span>
					<?php
					}

					if(!empty($parteProduccionTarea->getPaqueteEntrada())&&!empty($parteProduccionTarea->getPaqueteSalida())){
					?>
						<span class='col-sm-4 col-xs-12'>Nº Entrada: <input type="number" name="PaqueteEntrada<?php echo $x;?>" id="PaqueteEntrada<?php echo $x;?>" min="1" value="<?php echo $parteProduccionTarea->getPaqueteEntrada();?>"/></span><span class='col-sm-4 col-xs-12'>Nº Salida: <input type="number" name="PaqueteSalida<?php echo $x;?>" id="PaqueteSalida<?php echo $x;?>" min="1" value="<?php echo $parteProduccionTarea->getPaqueteSalida();?>"/></span><span class='col-sm-4 col-xs-12'>Total: <?php echo $parteProduccionTarea->getPaqueteSalida()-$parteProduccionTarea->getPaqueteEntrada();?></span>
					<?php
					}
                    ?>
					</div></div>
					<?php
					$x++;

                }
				if(strnatcasecmp($parte->getEstado()->getTipo(),"abierto")==0){
					echo "<button type='button' class='btn btn-primary pCerrar' rel='".$parte->getId()."'>Cerrar Parte</button> ";
					echo "<button type='button' class='btn btn-danger pBorrar' rel='".$parte->getId()."'>Eliminar Parte</button>";
				}elseif(strnatcasecmp($parte->getEstado()->getTipo(),"cerrado")==0){
					echo "<div class='panel panel-default'><div class='panel-body' >";


					if(count($parte->getHorariosParte())==1){
						echo "<p class='col-xs-12'><strong>Horario realizado: Continua de ";
					}else{
						echo "<p class='col-xs-12'><strong>Horario realizado: Partida de ";
					}

					$x = 0;

					foreach($parte->getHorariosParte() as $horarioParte){
						if($x>0){echo " y ";}
						$x++;
                        ?>
						<input type="time" name="HoraEntrada<?php echo $x;?>" id="HoraEntrada<?php echo $x;?>" value="<?php echo $horarioParte->getHoraEntrada();?>"/> - <input type="time" name="HoraSalida<?php echo $x;?>" id="HoraSalida<?php echo $x;?>" value="<?php echo $horarioParte->getHoraSalida();?>"/>
                        <?php


					}

					$fecha = $parte->getFecha();

                    $semana = date('W',strtotime($fecha));

					$trabajador = $parte->getTrabajador();

					$horariosTrabajador = $trabajador->getHorariosTrabajadorBySemana($semana);

                    if (!is_array($horariosTrabajador))
                    {
                        $horariosTrabajador = array($horariosTrabajador);

                    }

						foreach($horariosTrabajador as $horarioTrabajador)
						{

						    $horariofranjas = $horarioTrabajador->getHorario()->getHorariosFranja();
						    echo "<p class='col-xs-12'><strong>Horario asociado: ";
						    echo $horariofranjas[0]->getFranja()->getHoraInicio()." - ".$horariofranjas[sizeof($horariofranjas)-1]->getFranja()->getHoraFin();
						    echo "</p>";

						}
						//Calculo de horas extras

                            $numhorasrealizadas = 0;
					        foreach($parte->getHorariosParte() as $horarioParte){

						        $x++;
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
                            if ($extras > 0)
                            {
                                echo "<p class='col-xs-12' style='color: red;'><strong>Horas extras: ";
			                    echo $extras;
			                    echo "</p>";
                            }

                    //Termina calculo de horas extra

					echo "</strong></p><article>";
					?>
                    <span class='col-sm-4 col-xs-12'>Autopistas/Peajes: <input type="number" name="Autopista" id="Autopista"
                    <?php
					if(!empty($parte->getAutopista())){?>
						value="<?php echo $parte->getAutopista();?>"/>€</span>
				    <?php
					}else{?>
						value="0"/>€</span>
				    <?php
				    }
				    ?>
                    <span class='col-sm-4 col-xs-12'>Dietas: <input type="number" name="Dieta" id="Dieta"
                    <?php
					if(!empty($parte->getDieta())){?>
						value="<?php echo $parte->getDieta();?>"/>€</span>
				    <?php
					}else{?>
						value="0"/>€</span>
				    <?php
				    }
					?>
                    <span class='col-sm-4 col-xs-12'>Otros Gastos: <input type="number" name="Otros" id="Otros"
                    <?php
					if(!empty($parte->getOtroGasto())){?>
						value="<?php echo $parte->getOtroGasto();?>"/>€</span>
				    <?php
					}else{?>
						value="0"/>€</span>
				    <?php
				    }

					echo "</article><article class='col-xs-12'>";

                    ?>
                    <span class='col-sm-4 col-xs-12'>Incidencia: <br/><textarea name="Incidencia" id="Incidencia">
                    <?php
					if(!empty($parte->getIncidencia())){?>
						<?php echo $parte->getIncidencia();?></textarea></span>
				    <?php
					}else{?>
						No hay ninguna incidencia.</span></textarea>
				    <?php
				    }


					echo "</div></div>";


			}
		}else{
			echo "<div class='panel panel-default'><div class='panel-body'>El Parte no tiene ninguna Tarea.</div></div>";
			if(strnatcasecmp($parte->getEstado()->getTipo(),"abierto")==0){
				echo "<button type='button' class='btn btn-danger pBorrar' rel='".$parte->getId()."'>Eliminar Parte</button>";
			}/*CAMBIAR BOTONES PABLO*/
		}?>
            <div class="text-center">
                <button type="submit" name="guardarParteProduccion"  class="btn btn-primary">Guardar</button>
				<a href="<?php echo self::getUrlRaiz()?>/Vista/Administracion/Administracion.php?cod=2" style='text-decoration:none;'><button type="button" class="btn btn-warning cerrar">Volver</button></a>&nbsp;&nbsp;

                </form>
            </div>
            

            <?php
            //echo '</div> </div><div><button id="close" class="btn-danger btn pull-right col-sm-2 cerrar">Volver</button></div>';

            require_once __DIR__ . "/../Plantilla/pie.php";
        }

/*****************************************************/
/* PARTES */
/*****************************************************/

        public static function allPartesByDni()
        {

            parent::setOn(true);
            parent::setRoot(true);
            require_once __DIR__ . "/../Plantilla/cabecera.php";
            $partesProd = Administracion\Controlador::getAllPartesProduccion();
            $partesLog = Administracion\Controlador::getAllPartesLogistica();
            ?>
            Filtros:
            <form name="buscar" action="<?php echo parent::getUrlRaiz()?>/Vista/Administracion/Administracion.php?cod=3" method="post">
            <label>DNI: </label><input type="text" name="dni" size="10">
            <!--<button type="button" id="buscar" style="border: none; background: none"><span
                    class="glyphicon glyphicon-search" style="color:black; font-size: 1.5em"></span></button>-->
                    <input type="submit" name="Buscar" value="Buscar">
            </form>
            <span id="respuesta">
            <table class="table table-bordered text-center">

                <h2>PARTES LOGÍSTICA</h2>
                <tr>
                    <th>DNI</th>
                    <th>NOMBRE</th>
                    <th>FECHA</th>
                    <th>NOTA</th>
                    <th>AUTOPISTAS</th>
                    <th>DIETAS</th>
                    <th>OTROS GASTOS</th>
                    <th>ESTADO</th>
                    <th>ACCIÓN</th>
                </tr>
                <?php
                //Correcion/Revision Aitor I Ganeko
                foreach ($partesLog as $log) {

                        ?>
                        <form method="post"
                              action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                            <tr>
                                <td><?php  echo $log->getTrabajador()->getDni(); ?></td>
                                <td><?php echo $log->getTrabajador()->getNombre()." ".$log->getTrabajador()->getApellido1()." ".$log->getTrabajador()->getApellido2(); ?></td>
                                <td><?php echo $log->getFecha(); ?></td>
                                <td><?php echo $log->getNota(); ?></td>
                                <td><?php echo $log->getAutopista(); ?></td>
                                <td><?php echo $log->getDieta(); ?></td>
                                <td><?php echo $log->getOtroGasto(); ?></td>
                                <td><?php echo $log->getEstado()->getTipo(); ?></td>
                                <td>
                                    <button type="submit" name="listarParteLog"
                                            style="border: none; background: none"><span
                                            class="glyphicon glyphicon-list" style="color:blue; font-size: 1.5em">
                                    </button>
                                    <?php if($log->getEstado()->getTipo() != "Finalizado"){?>
                                    <button type="submit" name="modificarParteLog"
                                            style="border: none; background: none"><span
                                            class="glyphicon glyphicon-edit" style="color:blue; font-size: 1.5em">
                                    </button>
                                    <button type="submit" name="eliminarParteLogistica"
                                            style="border: none; background: none"><span
                                            class="glyphicon glyphicon-remove" style="color:red; font-size: 1.5em">
                                    </button>
                                    <?php }?>
                            <?php
                            if ($log->getEstado()->getTipo() == "Cerrado") {
                                ?>
                                <button type="submit" name="abrirParteLogistica"
                                                style="border: none; background: none"><span
                                                class="glyphicon glyphicon-open-file" style="color:blue; font-size: 1.5em">
                                        </button>
                                        <button type="submit" name="validarParteLogistica"
                                                style="border: none; background: none"><span
                                                class="glyphicon glyphicon-ok"
                                                style="color:green; font-size: 1.5em"></span></button>
                                <?php
                            }
                            ?>


                                </td>
                            </tr>
                            <input type="hidden" name="id" value="<?php echo $log->getId(); ?>">
                        </form>
                        <?php

                }
                ?>
            </table>
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
                <?php //ganeko
                foreach ($partesProd as $prod) {

                        ?>
                        <form method="post"
                              action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
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
                            <?php if($prod->getEstado()->getTipo() != "Finalizado"){?>
                            <button type="submit" name="modificarParteProd"
                                            style="border: none; background: none"><span
                                            class="glyphicon glyphicon-edit" style="color:blue; font-size: 1.5em">
                                    </button>
                                    <button type="submit" name="eliminarParteProduccion"
                                        style="border: none; background: none"><span
                                        class="glyphicon glyphicon-remove" style="color:red; font-size: 1.5em">
                            </button>
                            <?php }?>
                            <?php
                            if ($prod->getEstado()->getTipo() == "Cerrado") {
                                ?>
                                <button type="submit" name="abrirParteProduccion"
                                                style="border: none; background: none"><span
                                                class="glyphicon glyphicon-open-file" style="color:blue; font-size: 1.5em">
                                        </button>
                                        <button type="submit" name="validarParteProduccion"
                                                style="border: none; background: none"><span
                                                class="glyphicon glyphicon-ok"
                                                style="color:green; font-size: 1.5em"></span></button>
                                <?php
                            }
                            ?>

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

            require_once __DIR__ . "/../Plantilla/pie.php";

                }

/*****************************************************/
/* FESTIVOS */
/*****************************************************/

        public static function insertarFestivo(){

            parent::setOn(true);
            parent::setRoot(true);

            require_once __DIR__ . "/../Plantilla/cabecera.php";
            $festivos = Administracion\Controlador::getAllFestivos();
            ?>
            <form class="form-horizontal" name="insertarFestivo" method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2">Motivo: </label>
                    <div class="col-sm-4 col-md-3">
                        <input class="form-control" type="text" name="motivo" placeholder="Navidad, Semana Santa...">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2">Fecha: </label>
                    <div class="col-sm-4 col-md-3">
                        <input class="form-control" type="text" name="fecha" placeholder="aaaa-mm-dd" size="5">
                    </div>
                </div>
                    </fieldset>
                <div class="form-group"><!--Ganeko & Ibai-->
                    <div class="col-sm-1 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Añadir" name="añadirFestivo">
                    </div>
            </form>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">

                    <div class="col-sm-1">
                        <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                    </div>
                </div>
            </form>

            <?php

            require_once __DIR__ . "/../Plantilla/pie.php";

        }

        public static function deleteFestivo(){
            parent::setOn(true);
            parent::setRoot(true);

            require_once __DIR__ . "/../Plantilla/cabecera.php";
            $festivos = Administracion\Controlador::getAllFestivos();
            if(is_null($festivos)){
                echo "No hay festivos";
            }else {
                ?>

                <table class="table table-bordered text-center">
                    <tr>
                        <th>MOTIVO</th>
                        <th>FECHA</th>
                        <th>ACCIÓN</th>
                    </tr>
                    <?php
                    foreach ($festivos as $festivo) {
                        ?>
                        <form name="delFestivo" method="post"
                              action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                            <tr>
                                <td><?php echo $festivo->getMotivo(); ?></td>
                                <td><?php echo $festivo->getFecha(); ?></td>
                                <td>
                                    <button type="submit" name="deleteFestivo" style="border: none; background: none">
                                        <span class="glyphicon glyphicon-remove"
                                              style="color:red; font-size: 1.5em"></span></button
                                </td>
                            </tr>
                            <input type="hidden" name="id" value="<?php echo $festivo->getId(); ?>">
                        </form>
                        <?php
                    }
                    ?>
                </table>
                <?php
            }
            require_once __DIR__ . "/../Plantilla/pie.php";
        }


        public static function visualizarVacacionesOpen()           //Aitor
        {
            parent::setOn(true);
            parent::setRoot(true);

            require_once __DIR__ . "/../Plantilla/cabecera.php";

            echo "<form action='../../Controlador/Administracion/ControladorCalendario.php' method='post'>";
            echo "<table class='table table-striped'><tr><td>Calendario</td><td>Trabajador</td><td>Fecha</td></tr>";
            $festivos=BD\FestivoBD::getFestivoByEstado();
            while($rows=mysqli_fetch_array($festivos)){
                echo "<tr><td>$rows[calendario_id]</td><td>$rows[dniTrabajador]</td><td>$rows[fecha]</td></tr>";
            }
            echo "</table></form>";
            require_once __DIR__ . "/../Plantilla/pie.php";
        }

    /*****************************************************/
/* FOTO */
/*****************************************************/

    public static function updateFoto(){
        //Ganeko
        parent::setOn(true);
        parent::setRoot(true);


        require_once __DIR__ . "/../Plantilla/cabecera.php";

        ?>

        <h2 class="page-header">Trabajadores</h2>
        <div class="table-responsive col-md-offset-1 col-md-10">
            <table class="table table-bordered">
                <tr>
                    <th>DNI</th>
                    <th>Nueva foto</th>
                    <th>Acción</th>
                </tr>
                <form name="updatePassword" method="post" enctype="multipart/form-data" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="trabajador" value="<?php echo $_SESSION["dni"]; ?>">

                        </td>
                        <td><input class="form-control" type="file" name="foto"/></td>
                        <td><button type="submit" name="updateFoto" value="Cambiar" style="border: none; background: none"><span class="glyphicon glyphicon-edit" style="color: blue; font-size: 1.5em"></span></button></td>
                    </tr>
                </form>
            </table>
        </div>

        <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
            <div class="col-md-10 col-md-offset-1 pull-rigth">
                <!--Ibai-->
                <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
            </div>
        </form>

        <?php

        require_once __DIR__ . "/../Plantilla/pie.php";

    }


//Arreglo de filtros ventana admin Aitor I(copy paste de gerencia....)




    public static function findPartesByDni($datos)
    {

        parent::setOn(true);
        parent::setRoot(true);
        require_once __DIR__ . "/../Plantilla/cabecera.php";
        ?>
        Filtros:
        <form name="buscar" action="<?php echo parent::getUrlRaiz()?>/Vista/Administracion/Administracion.php?cod=3" method="post">
            <label>DNI: </label><input type="text" name="dni" size="10">
            <!--<button type="button" id="buscar" style="border: none; background: none"><span
                    class="glyphicon glyphicon-search" style="color:black; font-size: 1.5em"></span></button>-->
            <input type="submit" name="Buscar" value="Buscar">
        </form>
        <?php
        $perfil = Administracion\Controlador::getPerfilbyDni($datos['dni']);
        $partes = Administracion\Controlador::getParte($datos['dni'], $perfil);

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
                <?php //ganeko
                foreach ($partes as $log) {



                        ?>
                        <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">                   <tr>
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
                            <?php if($log->getEstado()->getTipo() != "Finalizado"){?>
                        <button type="submit" name="modificarParteLog"
                                style="border: none; background: none"><span
                                class="glyphicon glyphicon-edit" style="color:blue; font-size: 1.5em">
                                    </button>
                        <button type="submit" name="eliminarParteLogistica"
                                style="border: none; background: none"><span
                                class="glyphicon glyphicon-remove" style="color:red; font-size: 1.5em">
                            </button>
                            <?php } /*Aitor I (Todo los if de este tipo)*/?>
                            <?php
                        if ($log->getEstado()->getTipo() == "Cerrado") {
                            ?>
                            <button type="submit" name="abrirParteLogistica"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-open-file" style="color:blue; font-size: 1.5em">
                                        </button>
                            <button type="submit" name="validarParteLogistica"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-ok"
                                    style="color:green; font-size: 1.5em"></span></button>
                            <?php
                        }
                        ?>

                            <?php
                        if ($log->getEstado()->getTipo() == "Validado") {
                            ?>
                            <button type="submit" name="finalizarParteLogistica"
                                    style="border: none; background: none"><span
                                    class="glyphicon glyphicon-ok"
                                    style="color:green; font-size: 1.5em"></span></button>

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
                            foreach($horariosTrabajador as $horarioTrabajador)
                            {
                                if (!is_null($horarioTrabajador))
                                {
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
                <?php //ganeko
                foreach ($partes as $prod) {



                        ?>
                        <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
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
                            <?php if($prod->getEstado()->getTipo()!= "Finalizado"){ /*Aitor I (Todo los if de este tipo)*/?>
                            <button type="submit" name="modificarParteProd"
                                            style="border: none; background: none"><span
                                            class="glyphicon glyphicon-edit" style="color:blue; font-size: 1.5em">
                                    </button>
                                    <button type="submit" name="eliminarParteProduccion"
                                        style="border: none; background: none"><span
                                        class="glyphicon glyphicon-remove" style="color:red; font-size: 1.5em">
                            </button>
                            <?php }?>
                            <?php
                            if ($prod->getEstado()->getTipo() == "Cerrado") {
                                ?>
                                <button type="submit" name="abrirParteProduccion"
                                                style="border: none; background: none"><span
                                                class="glyphicon glyphicon-open-file" style="color:blue; font-size: 1.5em">
                                        </button>
                                        <button type="submit" name="validarParteProduccion"
                                                style="border: none; background: none"><span
                                                class="glyphicon glyphicon-ok"
                                                style="color:green; font-size: 1.5em"></span></button>
                                <?php
                            }
                            ?>

                            <?php
                            if ($prod->getEstado()->getTipo() == "Validado") {
                                ?>
                                <button type="submit" name="finalizarParteProduccion"
                                        style="border: none; background: none"><span
                                        class="glyphicon glyphicon-ok"
                                        style="color:green; font-size: 1.5em"></span></button>

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
                ?>
        </table>
            </span>
            <?php
        }
        require_once __DIR__ . "/../Plantilla/pie.php";
    }
	public static function updateHorarioTrabajador(){ /*PABLO*/
		parent::setOn(true);
        	parent::setRoot(true);
			$horarioTrabajador = Administracion\Controlador::getAllHorarioTrabajador();
			$horarios = Administracion\Controlador::getAllHorarios();
			
            require_once __DIR__ . "/../Plantilla/cabecera.php";
            ?>
            <div class="table-responsive col-md-offset-1 col-md-10">
                <table class="table table-bordered">
                    <tr>
                        <th>TRABAJADOR</th>
                        <th>SEMANA</th>
                        <th>HORARIO</th>
						<th>CALENDARIO</th> <!--Ibai-->
						<th>ACCIÓN</th>
                    </tr>

                        <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                            <tr>
							<?php $_SESSION["dniht"]=$horarioTrabajador[$_SESSION["dht_semana"]]->getTrabajador()->getDni(); $_SESSION["semht"]=$horarioTrabajador[$_SESSION["dht_semana"]]->getNumeroSemana();?>
                                <td><?php echo $horarioTrabajador[$_SESSION["dht_semana"]]->getTrabajador()->getDni(); ?></td>
                                <td><?php echo $horarioTrabajador[$_SESSION["dht_semana"]]->getNumeroSemana(); ?></td>
                               <!-- PABLO --> <td><select class='form-control' name="horario"><?php $x=1; foreach ($horarios as $hor){?><option value="<?php echo $x; ?>"><?php echo $hor->getTipo();?></option><?php $x++; } ?></select>
                                <td><?php echo $horarioTrabajador[$_SESSION["dht_semana"]]->getCalendario()->getId()?></td>
							    <td><button type="submit" name="updateT3" value="Editar"
                                            style="border: none; background: none;"><span
                                            class="glyphicon glyphicon-edit"
                                            style="color:blue; font-size: 1.5em"></span></td>
                            </tr>
                        </form>

                </table>
            </div>
            <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                <div class="col-md-10 col-md-offset-1"><!-- Ganeko -->
                    <input class="btn btn-warning pull-right" type="submit" name="volver" value="Volver">
                </div>
            </form>
			<?php

		require_once __DIR__ ."/../Plantilla/pie.php";

	}
    public static function updateEmpresa(){ /*Ganeko*/

                require_once __DIR__ . "/../Plantilla/cabecera.php";
                ?>
                <div class="table-responsive col-md-offset-1 col-md-10">
                    <table class="table table-bordered">
                        <tr>
                            <th>EMPRESA</th>
                            <th>NIF</th>
                        </tr>
                        <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                            <tr>
                                <?php
                                $empresa = Administracion\Controlador::buscarEmpresaId($_SESSION['id']);
                                ?>
                                <td><input type="text" name="nombre" value="<?php echo $empresa->getNombre(); ?>"></td>
                                <td><input type="text" name="nif" value="<?php echo $empresa->getNif(); ?>"></td>
                                <input type="hidden" name="id" value="<?php echo $_SESSION['id'];?>">
                            </tr>
                    </table>
                </div>
                    <div class="col-md-10 col-md-offset-1"><!-- Ganeko -->
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit" name="editarEmpresa" value="Guardar">
                            <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                        </div>
                    </div>
                </form>
                <?php

            require_once __DIR__ ."/../Plantilla/pie.php";

        }

    public static function updateCentro(){ /*Ganeko*/


                require_once __DIR__ . "/../Plantilla/cabecera.php";
                ?>
                <div class="table-responsive col-md-offset-1 col-md-10">
                    <table class="table table-bordered">
                        <tr>
                            <th>CENTRO</th>
                            <th>LOCALIZACIÓN</th>
                            <th>EMPRESA</th>
                        </tr>
                        <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                            <tr>
                                <?php
                                $centro = Administracion\Controlador::getCentroId($_SESSION['id']);
                                ?>
                                <td><input type="text" name="nombre" value="<?php echo $centro->getNombre(); ?>"></td>
                                <td><input type="text" name="localizacion" value="<?php echo $centro->getLocalizacion(); ?>"></td>
                                <td><?php echo $centro->getEmpresa()->getNombre(); ?></td>
                                <input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>">
                            </tr>
                    </table>
                </div>
                    <div class="col-md-10 col-md-offset-1"><!-- Ganeko -->
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit" name="editarCentro" value="Guardar">
                            <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                        </div>
                    </div>
                </form>
                <?php

            require_once __DIR__ ."/../Plantilla/pie.php";

        }

        public static function updateVehiculo(){ /*Ganeko*/


                require_once __DIR__ . "/../Plantilla/cabecera.php";
                ?>
                <div class="table-responsive col-md-offset-1 col-md-10">
                    <table class="table table-bordered">
                        <tr>
                            <th>MATRICULA</th>
                            <th>MARCA</th>
                            <th>CENTRO</th>
                        </tr>
                        <form method="post" action="<?php echo self::getUrlRaiz() ?>/Controlador/Administracion/Router.php">
                            <tr>
                                <?php
                                $vehiculo = Administracion\Controlador::getVehiculoId($_SESSION['id']);
                                ?>
                                <td><input type="text" name="nombre" value="<?php echo $vehiculo->getMatricula(); ?>"></td>
                                <td><input type="text" name="localizacion" value="<?php echo $vehiculo->getMarca(); ?>"></td>
                                <td><select name="centro">
                                    <?php
                                        $centros = Administracion\Controlador::getAllCentros();
                                        for($x = 0; $x < count($centros); $x++){
                                            echo "<option value='".$centros[$x]->getId()."'>".$centros[$x]->getNombre()."</option>";
                                        }
                                    ?>

                                </select>
                                </td>
                                <input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>">
                            </tr>
                    </table>
                </div>
                    <div class="col-md-10 col-md-offset-1"><!-- Ganeko -->
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit" name="editarVehiculo" value="Guardar">
                            <input class="btn btn-warning" type="submit" name="volver" value="Volver">
                        </div>
                    </div>
                </form>
                <?php

            require_once __DIR__ ."/../Plantilla/pie.php";

        }










}


