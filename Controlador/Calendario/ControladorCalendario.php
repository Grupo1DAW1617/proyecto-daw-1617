<?php
error_reporting(0);
require_once __DIR__."/../../Modelo/Base/TrabajadorClass.php";
require_once __DIR__.'/../../Modelo/BD/GenericoBD.php';
require_once __DIR__.'/../../Modelo/BD/CalendarioBD.php';
require_once __DIR__.'/../../Modelo/Base/VacacionesTrabajadoresClass.php';
require_once __DIR__.'/../../Modelo/BD/VacacionesTrabajadoresBD.php';
require_once __DIR__."/../../Vista/Calendario/CalendarioVacaciones.php";
require_once __DIR__."/../../Vista/Calendario/CalendarioGestionarCalendariosIndividuales.php";
require_once __DIR__."/../../Vista/Calendario/AsignarCalendarios.php";
require_once __DIR__."/../../Vista/Administracion/AdministracionViews.php";
require_once __DIR__."/../../Modelo/BD/FestivoBD.php";
require_once __DIR__."/../../Modelo/BD/FestivoNacionalBD.php";
require_once __DIR__."/../../Modelo/Base/FestivosNacionalClass.php";
require_once __DIR__."/../../Modelo/BD/FestivoNacionalBD.php";
require_once __DIR__."/../../Modelo/Base/FestivoClass.php";
require_once __DIR__."/../../Modelo/BD/FestivoCentroBD.php";


function fecha ($valor)
{
	$timer = explode(" ",$valor);
	$fecha = explode("-",$timer[0]);
	$fechex = $fecha[2]."/".$fecha[1]."/".$fecha[0];
	return $fechex;
}


function buscar_en_array($fecha,$array)
{
	$total_eventos=count($array);
	for($e=0;$e<$total_eventos;$e++)
	{
		if ($array[$e]["fecha"]==$fecha) return true;
	}
}

switch ($_GET["accion"])
{
	case "listar_evento":
	{
		$query=$db->query("select * from ".$tabla." where fecha='".$_GET["fecha"]."' order by id asc");
		if ($fila=$query->fetch_array())
		{
			do
			{
				echo "<p>".$fila["evento"]."<a href='#' class='eliminar_evento' rel='".$fila["id"]."' title='Eliminar este Evento del ".fecha($_GET["fecha"])."'><img src='<?php echo parent::getUrlRaiz()?>/Vista/Plantilla/IMG/delete.gif''></a></p>";
			}
			while($fila=$query->fetch_array());
		}
		break;
	}
	case "guardar_evento":
	{
		$query=$db->query("insert into ".$tabla." (fecha,evento) values ('".$_GET["fecha"]."','".strip_tags($_GET["evento"])."')");
		if ($query) echo "<p class='ok'>Evento guardado correctamente.</p>";
		else echo "<p class='error'>Se ha producido un error guardando el evento.</p>";
		break;
	}
	case "borrar_evento":
	{
		$query=$db->query("delete from ".$tabla." where id='".$_GET["id"]."' limit 1");
		if ($query) echo "<p class='ok'>Evento eliminado correctamente.</p>";
		else echo "<p class='error'>Se ha producido un error eliminando el evento.</p>";
		break;
	}
	case "generar_calendario":
	{
		$fecha_calendario=array();
		if ($_GET["mes"]=="" || $_GET["anio"]=="")
		{
			$fecha_calendario[1]=intval(date("m"));
			if ($fecha_calendario[1]<10) $fecha_calendario[1]="0".$fecha_calendario[1];
			$fecha_calendario[0]=date("Y");
		}
		else
		{
			$fecha_calendario[1]=intval($_GET["mes"]);
			if ($fecha_calendario[1]<10) $fecha_calendario[1]="0".$fecha_calendario[1];
			else $fecha_calendario[1]=$fecha_calendario[1];
			$fecha_calendario[0]=$_GET["anio"];
		}
		$fecha_calendario[2]="01";

		/* obtenemos el dia de la semana del 1 del mes actual */
		$primeromes=date("N",mktime(0,0,0,$fecha_calendario[1],1,$fecha_calendario[0]));

		/* comprobamos si el a�o es bisiesto y creamos array de d�as */
		if (($fecha_calendario[0] % 4 == 0) && (($fecha_calendario[0] % 100 != 0) || ($fecha_calendario[0] % 400 == 0))) $dias=array("","31","29","31","30","31","30","31","31","30","31","30","31");
		else $dias=array("","31","28","31","30","31","30","31","31","30","31","30","31");

		$eventos=array();

		$query=$db->query("select fecha,count(id) as total from ".$tabla." where month(fecha)='".$fecha_calendario[1]."' and year(fecha)='".$fecha_calendario[0]."' group by fecha");
		if ($fila=$query->fetch_array())
		{
			do
			{
				$eventos[$fila["fecha"]]=$fila["total"];
			}
			while($fila=$query->fetch_array());
		}

		$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

		/* calculamos los d�as de la semana anterior al d�a 1 del mes en curso */
		$diasantes=$primeromes-1;

		/* los d�as totales de la tabla siempre ser�n m�ximo 42 (7 d�as x 6 filas m�ximo) */
		$diasdespues=42;

		/* calculamos las filas de la tabla */
		$tope=$dias[intval($fecha_calendario[1])]+$diasantes;
		if ($tope%7!=0) $totalfilas=intval(($tope/7)+1);
		else $totalfilas=intval(($tope/7));

		/* empezamos a pintar la tabla */
		echo "<h2>Calendario de Eventos para: ".$meses[intval($fecha_calendario[1])]." de ".$fecha_calendario[0]." <abbr title='S&oacute;lo se pueden agregar eventos en d&iacute;as h&aacute;biles y en fechas futuras (o la fecha actual).'>(?)</abbr></h2>";
		if (isset($mostrar)) echo $mostrar;

		echo "<table class='calendario' cellspacing='0' cellpadding='0'>";
			echo "<tr><th>Lunes</th><th>Martes</th><th>Mi&eacute;rcoles</th><th>Jueves</th><th>Viernes</th><th>S&aacute;bado</th><th>Domingo</th></tr><tr>";

			/* inicializamos filas de la tabla */
			$tr=0;
			$dia=1;

			function es_finde($fecha)
			{
				$cortamos=explode("-",$fecha);
				$dia=$cortamos[2];
				$mes=$cortamos[1];
				$ano=$cortamos[0];
				$fue=date("w",mktime(0,0,0,$mes,$dia,$ano));
				if (intval($fue)==0 || intval($fue)==6) return true;
				else return false;
			}

			for ($i=1;$i<=$diasdespues;$i++)
			{
				if ($tr<$totalfilas)
				{
					if ($i>=$primeromes && $i<=$tope)
					{
						echo "<td class='";
						/* creamos fecha completa */
						if ($dia<10) $dia_actual="0".$dia; else $dia_actual=$dia;
						$fecha_completa=$fecha_calendario[0]."-".$fecha_calendario[1]."-".$dia_actual;

						if (intval($eventos[$fecha_completa])>0)
						{
							echo "evento";
							$hayevento=$eventos[$fecha_completa];
						}
						else $hayevento=0;

						/* si es hoy coloreamos la celda */
						if (date("Y-m-d")==$fecha_completa) echo " hoy";

						echo "'>";

						/* recorremos el array de eventos para mostrar los eventos del d�a de hoy */
						/*if ($hayevento>0) echo "<a href='#' data-evento='#evento".$dia_actual."' class='modal' rel='".$fecha_completa."' title='Hay ".$hayevento." eventos'>".$dia."</a>";
						else echo "$dia";*/

						/* agregamos enlace a nuevo evento si la fecha no ha pasado */
						//if (date("Y-m-d")<=$fecha_completa && es_finde($fecha_completa)==false) echo "<a href='#' data-evento='#nuevo_evento' title='Agregar un Evento el ".fecha($fecha_completa)."' class='add agregar_evento' rel='".$fecha_completa."'>&nbsp;</a>";

						echo "</td>";
						$dia+=1;
					}
					else echo "<td class='desactivada'>&nbsp;</td>";
					if ($i==7 || $i==14 || $i==21 || $i==28 || $i==35 || $i==42) {echo "<tr>";$tr+=1;}
				}
			}
			echo "</table>";

			$mesanterior=date("Y-m-d",mktime(0,0,0,$fecha_calendario[1]-1,01,$fecha_calendario[0]));
			$messiguiente=date("Y-m-d",mktime(0,0,0,$fecha_calendario[1]+1,01,$fecha_calendario[0]));
			echo "<p class='toggle'>&laquo; <a href='#' rel='$mesanterior' class='anterior'>Mes Anterior</a> - <a href='#' class='siguiente' rel='$messiguiente'>Mes Siguiente</a> &raquo;</p>";



		break;
	}

    /**
     * Buscar los trabajadores por id del Centro
     *
     * Anas
     */

    case "buscarTrab":
    {
        $idEmpresa = $_GET["idEmpresa"];

        $empresa = new \Modelo\Base\Centro($idEmpresa);

        $query = \Modelo\BD\TrabajadorBD::getTodosTrabajadoresByCentro($empresa);
        if($query==null){
            echo null ;
        }else{
            for($x=0;$x<count($query);$x++){
               echo "<option value='".$query[$x]->getDni()."'>".$query[$x]->getNombre()."</option>";
            }
        }
        break;

    }
    /**
     * Las fechas tienen que estar en DateTime segun la Base de datos  así que las convierto
     *
     *
     * Inserto en la Base de Datos las vacaciones
     *
     * Anas
     */
    case "insertarCal":{

        for($x=0;$x<count($_GET["horaInicio"]);$x++){
            $vacacionesTrab = new \Modelo\Base\VacacionesTrabajadores(null,$_GET["dniTrabajador"],$_GET["horaInicio"][$x],$_GET["horaInicio"][$x],$_GET["horaFin"][$x],$_GET["calendario_id"],$_GET["estado"]);

            $estado = \Modelo\BD\VacacionesTrabajadoresBD::seleccionarVacaciones($vacacionesTrab);

            if($estado == false){
                $query = \Modelo\BD\VacacionesTrabajadoresBD::insertarVacacionesTrabajadores($vacacionesTrab);
            }elseif ($estado != "A"){
                $query = \Modelo\BD\VacacionesTrabajadoresBD::updateVacaciones($vacacionesTrab->getDniTrabajador(),$vacacionesTrab->getFecha(),$vacacionesTrab->getEstado());
            }

        }

        if($query){
            echo true;

        }else{
            echo false;
        }

        break;
    }

    case "insertarCalIndiv":{

        $fechas =  $_GET["fechas"];
        for($x=0;$x<count($fechas);$x++){
            $vacacionesTrab = new \Modelo\Base\VacacionesTrabajadores(null,$_GET["dniTrabajador"],$fechas[$x]." 00:00:00",$fechas[$x]." 00:00:00",$fechas[$x]." 23:59:59",$_GET["calendario_id"],$_GET["estado"]);
            $query = \Modelo\BD\VacacionesTrabajadoresBD::insertarVacacionesTrabajadores($vacacionesTrab);
        }
        if($query){
            echo "Vacaciones solicitadas";

        }else{
            echo "Error al solicitar las vacaciones";
        }

        break;
    }

    case "festivosNacionales":{
        $fechas = $_GET["fechasEnvio"];
        $calendario = $_GET["calendario"];
        $contador = 0;

        for($x = 0; $x < count($fechas); $x++){
            $festivoNacional = new \Modelo\Base\FestivosNacional(null,$fechas[$x],"festivo nacional",$calendario);

            $query = \Modelo\BD\FestivoNacionalBD::insertarFestivos($festivoNacional);

            if($query){
                $contador++;
            }
        }

        if($contador == count($fechas)){
            echo "Fechas insertadas";
        }else{
            echo "Fechas no insertadas";
        }
        break;
    }
    case "festivosCentros":{
        $fechas = $_GET["fechasEnvio"];
        $calendario = $_GET["calendario"];
        $centro = $_GET["centro"];
        $contador = 0;

        for($x = 0; $x < count($fechas); $x++){
            $festivoCentro = new \Modelo\Base\Festivo(null,$fechas[$x],"festivo centro", $centro, $calendario);

            $query = \Modelo\BD\FestivoCentroBD::insertarCentro($festivoCentro);

            if($query){
                $contador++;
            }
        }

        if($contador == count($fechas)){
            echo "Centros insertados";
        }else{
            echo "Centros no insertados";
        }
        break;
    }

    case "buscarCalend":{   //Aitor
        $trabajador = $_GET["trabajador"];

        $oTrabajador = new \Modelo\Base\Trabajador($trabajador);
        $query = \Modelo\BD\FestivoBD::getFestivoByEstado($oTrabajador);
        $resultado="";
        if($query==null){
            echo null ;
        }else{
            for($x=0;$x<count($query);$x++){
                $fecha = substr($query[$x]->getFecha(), -19, 10);
                $resultado=$resultado. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .$fecha;
                if($x%2 != 0){
                    $resultado = $resultado."<br>";
                }
            }
        }
        echo $resultado;
        break;
    }
    case "editarCalendario":{
        $valor = $_GET["valor"];
        $trabajador = $_GET["trabajador"];

        $empresa = new \Modelo\Base\Centro($idEmpresa);

        $query = \Modelo\BD\TrabajadorBD::editarCalendario($trabajador,$valor);

        if ($valor == "A") {
            echo "Vacaciones aceptadas";

        }
        else {
            echo "Vacaciones rechazadas";
        }
        break;
    }

    case "buscarDisfrutadas":{   // IRUNE
        $trabajador = $_GET["trabajador"];

        $oTrabajador = new \Modelo\Base\Trabajador($trabajador);
        $query = \Modelo\BD\FestivoBD::getVacacionesDisfrutadas($oTrabajador);
        $resultado="";
        if($query==null){
            echo null ;
        }else{
            for($x=0;$x<count($query);$x++){
                $fecha = substr($query[$x]->getFecha(), -19, 10);
                $resultado=$resultado. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .$fecha;
                if($x%2 != 0){
                    $resultado = $resultado."<br>";
                }
            }
        }
        echo $resultado;
        break;
    }

    case "actualizarEstado": {

        $trabajador = $_GET['trabajador'];

        $query = \Modelo\BD\FestivoBD::asignarDisfrutados($trabajador);
        break;

    }

}


if(isset($_POST["aceptar"])){   //Aitor
    error_reporting(0);
    if($_POST["trabajador"]==""){
        echo "<script>alert('Tienes que elegir a un trabajador.');</script>";
        CalendarioGestionarCalendariosIndividuales::cal(true);
    }
    else
    {
        $_SESSION["trabj"]=$_POST["trabajador"];
        AsignarCalendarios::generar();
    }
}

if(isset($_POST["volver"])){    //Aitor
    CalendarioGestionarCalendariosIndividuales::cal(true);
}


if(isset($_POST["asignarCalend"])){ //Aitor
    if($_POST["calendario"]=="")
    {
        echo "<script>alert('Tienes que elegir un calendario.');</script>";
        AsignarCalendarios::generar();
    }
    else
    {
        guardarFestivoNacional($_POST["calendario"]);
    }
}

function guardarFestivoNacional($calendario){   //Aitor
    $fechasnacionales=\Modelo\BD\FestivoNacionalBD::getFestivoNacional($calendario);
    $fechasCentro=\Modelo\BD\FestivoBD::getFestivoCentro($calendario);

    insertFestivosTrabajador($fechasnacionales, $fechasCentro, $calendario);
}


function insertFestivosTrabajador($fechasnacionales, $fechasCentro, $calendario){    //Aitor
    \Modelo\BD\FestivoBD::insertFestivosTrabajador($fechasnacionales, $fechasCentro, $calendario);
    echo "<script>alert('Calendario asignado correctamente.');</script>";
    AsignarCalendarios::generar();
}

?>