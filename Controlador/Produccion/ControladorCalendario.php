<?php
session_start();
use Vista\Plantilla;
use Modelo\BD;


error_reporting(-1);
require_once __DIR__.'/../../Modelo/BD/GenericoBD.php';
require_once __DIR__.'/../../Modelo/BD/CalendarioBD.php';
require_once __DIR__."/../../Vista/Plantilla/Views.php";

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

switch ($_POST["accion"])
{
	case "listar_evento":
	{
		$worker = unserialize($_SESSION["trabajador"]);

		$parte = BD\ParteProduccionBD::getPartebyTrabajadorAndFecha($worker,$_POST["fecha"]);

		$estado = BD\EstadoBD::selectEstadoByParteProduccion($parte);

		echo "<h3 class='text-left'><strong>El estado del Parte: ".$estado->getTipo()."</strong></h3><br/>";

		$query=$db->query("select * from himevico.partesproducciontareas where idParteProduccion = ".$parte->getId().";");
		if(mysqli_num_rows($query)!=0){
			if ($fila=$query->fetch_array())
			{
				echo "<link rel='stylesheet' type='text/css' href='".Plantilla\Views::getUrlRaiz()."/Vista/Plantilla/CSS/ProduccionStyle.css'>";
				echo "<input type='hidden' id='contTareas' value='".mysqli_num_rows($query)."'>";
				do
				{
					$tarea = BD\TareaBD::getTareaById(intval($fila["idTareas"]));
					$tipo = BD\TipoTareaBD::getTipoByTarea($tarea);

					echo "<div class='panel panel-default' rel='".$fila["id"]."'>";

					echo "<div class='panel-heading container-fluid'><article class='col-xs-6 text-left'><h4 class='panel-title'><strong>".$tipo->getDescripcion().":</strong> <span class='lead small'>".$tarea->getDescripcion()."</span></h4></article>";

					if(strnatcasecmp($estado->getTipo(),"abierto")==0){

                        echo "<article class='col-xs-6'><a class='tOp eliminar_tarea' rel='".$fila["id"]."'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a><!--<a class='tOp editar_tarea' rel='".$fila["id"]."'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>-->";
                        echo "<button name='botonModif' rel='".$fila["id"]."' style = 'background-color:transparent; position:relative; left:120px;' class='botonModif' id='botonModif'><span class=\"glyphicon glyphicon-edit\" style=\" color:blue; font-size: 16px\"></article>";//AITOR I
					}

					echo '</div><div class="panel-body">';

					if(!empty($fila["numeroHoras"])){
						echo "<span class='col-sm-4 col-xs-12'>Numero Horas: ".$fila["numeroHoras"]."</span>";
					}

					if(!empty($fila["paqueteEntrada"])&&!empty($fila["paqueteSalida"])){
						echo "<span class='col-sm-4 col-xs-12'>Nº Entrada: ".$fila["paqueteEntrada"]."</span><span class='col-sm-4 col-xs-12'>Nº Salida: ".$fila["paqueteSalida"]."</span><span class='col-sm-4 col-xs-12'>Total: ".($fila["paqueteSalida"]-$fila["paqueteEntrada"])."</span>";
					}

					echo "</div></div>";
				}
				while($fila=$query->fetch_array());

				if(strnatcasecmp($estado->getTipo(),"abierto")==0){
					echo "<button type='button' class='btn btn-primary pCerrar' rel='".$parte->getId()."'>Cerrar Parte</button> ";
					echo "<button type='button' class='btn btn-danger pBorrar' rel='".$parte->getId()."'>Eliminar Parte</button>";
				}elseif(strnatcasecmp($estado->getTipo(),"cerrado")==0){
					echo "<div class='panel panel-default'><div class='panel-body' >";


					if(count($parte->getHorariosParte())==1){
						echo "<p class='col-xs-12'><strong>Tipo Jornada: Continua de ";
					}else{
						echo "<p class='col-xs-12'><strong>Tipo Jornada: Partida de ";
					}

					$x = 1;

					foreach($parte->getHorariosParte() as $horarioParte){
						if($x>1){echo " y ";}
						$x++;

						echo $horarioParte->getHoraEntrada()." - ".$horarioParte->getHoraSalida();

					}

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

					echo "</div>";
				}

			}
		}else{
			echo "<div class='panel panel-default'><div class='panel-body'>El Parte no tiene ninguna Tarea.</div></div>";
			if(strnatcasecmp($estado->getTipo(),"abierto")==0){

				echo "<button type='button' class='btn btn-danger pBorrar' rel='".$parte->getId()."'>Eliminar Parte</button>";
			}
		}

		break;
	}
	case "addTarea":
	{
		//Obtenemos el trabajador de la session y creamos una variable fecha
		$worker = unserialize($_SESSION["trabajador"]);
		$fecha = str_replace("/","-",$_POST['fecha']);
		$fecha = new \DateTime($fecha);

		if (isset($_SESSION["parteProduccion"]))
		{
			//Obtenemos el parte de la sesion
			$parte = unserialize($_SESSION["parteProduccion"]);
			if (is_null($parte))
			{
				unset($_SESSION["parteProduccion"]);
			}
		}

		//Comprobamos si existe una sesion llamada parteProduccion
		if(isset($_SESSION["parteProduccion"])){//Si existe
			//Obtenemos el parte de la sesion
			$parte = unserialize($_SESSION["parteProduccion"]);

			//Compramos si la fecha del parte y de fecha introducida son diferentes
			if($parte->getFecha()!=$fecha){//Si lo son
				//Buscamos el parte con la fecha introducida y el trabajador
				$parte = Modelo\BD\ParteProduccionBD::getPartebyTrabajadorAndFecha($worker,$fecha->format("Y-m-d"));

				//Comprabamos si el parte esta en la base de datos
				if(is_null($parte)){//Si no esta
					//Buscamos el estado abierto en la base de datos
					$estado = BD\EstadoBD::selectEstdadoByTipo("abierto");
					//Creamos el parte lo guardamos y lo recuperamos
					$parte = new \Modelo\Base\ParteProduccion(null,$estado,$fecha->format("Y-m-d"),null,null,null,null,$worker,null,null);
					$parte->save();
					$parte = Modelo\BD\ParteProduccionBD::getPartebyTrabajadorAndFecha($worker,$fecha->format("Y-m-d"));
				}

				//Actualizamos la session con el nuevo parte
				$_SESSION["parteProduccion"]=serialize($parte);
			}

			//Creamos la tarea
			$tarea = new \Modelo\Base\Tarea($_POST["tarea"]);

			//Creamos ParteProduccionTarea y la guardamos
			$ppt = new \Modelo\Base\ParteProducionTarea(null,$_POST["numeroHoras"],$_POST["paquetesEntrada"],$_POST["paquetesSalida"],$tarea,$parte);
			echo "<div class='alert alert-success' id='fres' role='alert'>".$ppt->save()."</div>";

		}else{//Si no existe
			//Buscamos el parte con la fecha introducida y el trabajador
			$parte = Modelo\BD\ParteProduccionBD::getPartebyTrabajadorAndFecha($worker,$fecha->format("Y-m-d"));

			//Comprabamos si el parte esta en la base de datos
			if(!is_null($parte)){//Si el esta
				//Añadimos el parte a la session
				$_SESSION["parteProduccion"]=serialize($parte);

				//Creamos la tarea
				$tarea = new \Modelo\Base\Tarea($_POST["tarea"]);

				//Creamos ParteProduccionTarea y la guardamos
				$ppt = new \Modelo\Base\ParteProducionTarea(null,$_POST["numeroHoras"],$_POST["paquetesEntrada"],$_POST["paquetesSalida"],$tarea,$parte);
				echo "<div class='alert alert-success' id='fres' role='alert'>".$ppt->save()."</div>";

			}else{//Si el parte no existe


				//Buscamos el estado abierto en la base de datos
				$estado = BD\EstadoBD::selectEstdadoByTipo("abierto");
				//Creamos el Parte lo guardamos y lo restacamos.
				$parte = new \Modelo\Base\ParteProduccion(null,$estado,$fecha->format("Y-m-d"),null,null,null,null,$worker,null,null);
				$parte->save();
				$parte = Modelo\BD\ParteProduccionBD::getPartebyTrabajadorAndFecha($worker,$fecha->format("Y-m-d"));

				//Añadimos el parte a la session
				$_SESSION["parteProduccion"]=serialize($parte);

				//Creamos la tarea
				$tarea = new \Modelo\Base\Tarea($_POST["tarea"]);

				//Creamos el ParteProduccionTareas y lo guadamos
				$ppt = new \Modelo\Base\ParteProducionTarea(null,$_POST["numeroHoras"],$_POST["paquetesEntrada"],$_POST["paquetesSalida"],$tarea,$parte);
				echo "<div class='alert alert-success' id='fres' role='alert'>".$ppt->save()."</div>";
			}
		}
		break;
	}
	case "borrar_tarea":
	{
		$query=$db->query("delete from partesproducciontareas where id='".$_POST["id"]."' limit 1");
		if ($query) echo "<div class='alert alert-success col-xs-8 col-xs-offset-2' role='alert'>Tarea Eliminada</div>";
		else echo "<div class='alert alert-danger col-xs-8 col-xs-offset-2' role='alert'>Tarea No Eliminada</div>";
		break;
	}
	case "generar_calendario":
	{
		$fecha_calendario=array();
		if ($_POST["mes"]=="" || $_POST["anio"]=="")
		{
			$fecha_calendario[1]=intval(date("m"));
			if ($fecha_calendario[1]<10) $fecha_calendario[1]="0".$fecha_calendario[1];
			$fecha_calendario[0]=date("Y");
		}
		else
		{
			$fecha_calendario[1]=intval($_POST["mes"]);
			if ($fecha_calendario[1]<10) $fecha_calendario[1]="0".$fecha_calendario[1];
			else $fecha_calendario[1]=$fecha_calendario[1];
			$fecha_calendario[0]=$_POST["anio"];
		}
		$fecha_calendario[2]="01";

		/* obtenemos el dia de la semana del 1 del mes actual */
		$primeromes=date("N",mktime(0,0,0,$fecha_calendario[1],1,$fecha_calendario[0]));

		/* comprobamos si el a�o es bisiesto y creamos array de d�as */
		if (($fecha_calendario[0] % 4 == 0) && (($fecha_calendario[0] % 100 != 0) || ($fecha_calendario[0] % 400 == 0))) $dias=array("","31","29","31","30","31","30","31","31","30","31","30","31");
		else $dias=array("","31","28","31","30","31","30","31","31","30","31","30","31");

        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        date_default_timezone_set('Europe/Madrid');
        $dbhost="localhost";
        $dbname="himevico";
        $dbuser="root";
        $dbpass="root";
        $tabla="";
        $db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
        if ($db->connect_errno) {
            die ("<h1>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</h1>");
        }
        $eventos=array();
		$query=$db->query("select fecha,count(id) as total from partesproduccion where month(fecha)='".$fecha_calendario[1]."' and year(fecha)='".$fecha_calendario[0]."' group by fecha");
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
		echo "<h2>".$meses[intval($fecha_calendario[1])]." del ".$fecha_calendario[0]." <abbr title='S&oacute;lo se pueden agregar eventos en d&iacute;as h&aacute;biles y en fechas futuras (o la fecha actual).'></abbr></h2>";
		if (isset($mostrar)) echo $mostrar;

		echo "<table class='calendario table table-bordered table-responsive' cellspacing='0' cellpadding='0'>";
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
					if ($hayevento>0) {
						echo "<a href='#' data-evento='#evento" . $dia_actual . "' class='mod' rel='" . $fecha_completa . "' title='Hay un Parte' ";if (date("Y-m-d")==$fecha_completa) echo " style='font-weight:500;'";echo ">" . $dia . "</a>";
					}else echo "$dia";

					/* agregamos enlace a nuevo evento si la fecha no ha pasado */
					$worker = unserialize($_SESSION["trabajador"]);
					$parte = BD\ParteProduccionBD::getPartebyTrabajadorAndFecha($worker,$fecha_completa);
					$hoy = new \DateTime();

					if(!is_null($parte)){
						$estado = BD\EstadoBD::selectEstadoByParteProduccion($parte);
						if (strnatcasecmp($estado->getTipo(),"abierto")==0) echo "<a href='#' data-evento='#nuevo_evento' title='Agregar un Evento el ".fecha($fecha_completa)."' class='add agregar_evento' rel='".$fecha_completa."'>&nbsp;</a>";
					}else if($fecha_completa<=$hoy->format("Y-m-d")){
						echo "<a href='#' data-evento='#nuevo_evento' title='Agregar un Evento el ".fecha($fecha_completa)."' class='add agregar_evento' rel='".$fecha_completa."'>&nbsp;</a>";
					}

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
			$hoyEnlace = date("Y-m-d");

			echo "<ul class='pager'>
					<li><a href='#' rel='$mesanterior' class='anterior'><span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span>Mes Anterior</a></li>
					<li><a href='#' rel='$hoyEnlace' class='hoyEnlace'>Hoy</a></li>
					<li><a href='#' class='siguiente' rel='$messiguiente'>Mes Siguiente<span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span></a></li>";

		break;
	}
	case "borrar_parte":
	{
		$parte = new \Modelo\Base\ParteProduccion(intval($_POST["idParte"]));
		echo "<div class='alert alert-success col-xs-8 col-xs-offset-2' role='alert'>".$parte->remove()."</div>";
		break;
	}

	case "cerrar_parte":
	{
		$worker = unserialize($_SESSION["trabajador"]);
		$parte = BD\ParteProduccionBD::getParteById($_POST["idParte"]);

		$jornadaElegida = intval($_POST["jornadaElegida"]);

		for($x=1;$x<=$jornadaElegida;$x++){
			$horaEntrada = new \DateTime();
			$horaEntrada->setTime($_POST["horasInicio".$x],$_POST["minInicio".$x]);

			$horaSalida = new \DateTime();
			$horaSalida->setTime($_POST["horasFin".$x],$_POST["minFin".$x]);

			$horarioParte = new \Modelo\Base\HorarioParte(null,$horaEntrada->format("H:i:s"),$horaSalida->format("H:i:s"),$parte);

			$horarioParte->save();

		}

		$parte->setAutopista(floatval($_POST["autopista"]));
		$parte->setDieta(floatval($_POST["dietas"]));
		$parte->setOtroGasto(floatval($_POST["otrosGastos"]));
		$parte->setIncidencia($_POST["incidencias"]);
		$parte->setEstado(BD\EstadoBD::selectEstdadoByTipo("cerrado"));

		$parte->cerrarParte();

		echo "<div class='alert alert-success' id='fres' role='alert'>Parte Cerrado</div>";

		break;
	}
	//Aitor I
    case "modificar_tarea":{
        Modelo\BD\ParteProduccionTareaBD::ModificarParteTarea($_POST["id"], $_POST["tarea"], $_POST["numeroHoras"], $_POST["paqueteEntrada"], $_POST["paqueteSalida"]);
        break;
    }



}
?>