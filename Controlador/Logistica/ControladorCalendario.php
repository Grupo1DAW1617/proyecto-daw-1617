<?php
error_reporting(-1);
require_once __DIR__."/../../Modelo/BD/CalendarioBD.php";
require_once __DIR__."/../../Modelo/BD/PartesLogisticaBD.php";
require_once __DIR__."/../../Modelo/BD/TrabajadorBD.php";
require_once __DIR__."/../../Modelo/BD/ViajeBD.php";
require_once __DIR__."/../../Modelo/Base/LogisticaClass.php";
require_once __DIR__."/../../Modelo/Base/VehiculoClass.php";
require_once __DIR__."/../../Modelo/Base/ParteLogisticaClass.php";
require_once __DIR__."/../../Modelo/Base/ViajeClass.php";
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
		$trabajador=unserialize($_SESSION['trabajador']);
		$parte=Modelo\BD\PartelogisticaBD::getParteByFecha($trabajador,$_POST['fecha']);
		$viajes=Modelo\BD\ViajeBD::getViajeByParte($parte);
		echo "<div class='table-responsive'>";
		if($parte->getEstado()->getId()==1) {
			echo "<table class='table table-striped'><tr><th >ID</th><th >HORA INICIO</th><th >HORA FIN</th><th >VEHICULO</th><th >ALBARAN</th><th >ELIMINAR</th><th>MODIFICAR</th></tr>";
			foreach ($viajes as $viaje) {

				echo "<tr> <td>" . $viaje->getId() . "</td><td>" . $viaje->getHoraInicio() . "</td><td>" . $viaje->getHoraFin() . "</td><td>" . $viaje->getVehiculo()->getMatricula() . "</td><td>" . $viaje->getAlbaran() . "</td>   <td><a href='#' class='eliminar_evento' rel='" . $viaje->getId() . "' title='Eliminar este Evento del " . fecha($_POST["fecha"]) . "'><img src='" . \Vista\Plantilla\Views::getUrlRaiz() . "/Vista/Plantilla/IMG/delete.png'></a></td><td><button name='botonModif' rel='".$viaje->getId()."' class='botonModif' id='botonModif'><span class=\"glyphicon glyphicon-edit\" style=\"background-color:transparent; color:blue; font-size: 1.5em\">
                                    </span></button></td></tr>";//aitor I añadido modif
			}


			echo "</table>";

			echo '<button id="cerrarParte" class="btn-primary btn pull-left col-sm-2 cerrarParte">Cerrar Parte</button></div>';//Aitor I
		}
		else{
			echo "<table class='table table-striped'><tr><th >ID</th><th >HORA INICIO</th><th >HORA FIN</th><th >VEHICULO</th><th >ALBARAN</th></tr>";
			foreach ($viajes as $viaje) {

				echo "<tr> <td>" . $viaje->getId() . "</td><td>" . $viaje->getHoraInicio() . "</td><td>" . $viaje->getHoraFin() . "</td><td>" . $viaje->getVehiculo()->getMatricula() . "</td><td>" . $viaje->getAlbaran() . "</td></tr>";
			}


			echo "</table>";


		}
		echo '</div> </div><div><button id="cerrar" class="btn-warning btn pull-right col-sm-2 cerrar">Volver</button></div>';


		break;
	}
	case "guardar_evento":
	{
		$query=$db->query("insert into parteslogistica (fecha,evento) values ('".$_POST["fecha"]."','".strip_tags($_POST["evento"])."')");
		if ($query) echo "<p class='ok'>Evento guardado correctamente.</p>";
		else echo "<p class='error'>Se ha producido un error guardando el evento.</p>";
		break;
	}
	case "borrar_evento":
	{
		$query=$db->query("delete from viajes where id='".$_POST["id"]."' limit 1");
		if ($query) echo "<p class='ok'>Evento eliminado correctamente.</p>";
		else echo "<p class='error'>Se ha producido un error eliminando el evento.</p>";
		break;
	}
	case "cerrarParte":
	{
		$fecha = $_POST["fecha"];
		$trabajador = unserialize($_SESSION["trabajador"]);
		$nota = $_POST["nota"];
        //Añadido por Aitor I
        $autopista = floatval($_POST["autopistas"]);;
        $dietas = floatval($_POST["dieta"]);
        $otros_gasto = floatval($_POST["otroGastos"]);

		 Modelo\BD\PartelogisticaBD::cerrarEstadoParteByFecha($trabajador,$fecha,$nota,$autopista,$dietas,$otros_gasto);

		break;
	}
	case "generar_calendario":
	{
		$trabajador=unserialize($_SESSION['trabajador']);

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

		$eventos=array();
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

		$q="select fecha, COUNT(id) as total from parteslogistica where month(fecha)='".$fecha_calendario[1]."' and year(fecha)='".$fecha_calendario[0]."' and dniTrabajador='".$trabajador->getDni()."' group by fecha";
		$query=$db->query($q);


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
		echo "<h2 id='Prueba'>Calendario de Eventos para: ".$meses[intval($fecha_calendario[1])]." de ".$fecha_calendario[0]." <abbr title='S&oacute;lo se pueden agregar eventos en d&iacute;as h&aacute;biles y en fechas futuras (o la fecha actual).'>(?)</abbr></h2>";
		if (isset($mostrar)) echo $mostrar;
			
		echo "<table class='calendario table-responsive' cellspacing='0' cellpadding='0'>";
			echo "<tr><th >Lunes</th><th >Martes</th><th >Mi&eacute;rcoles</th><th >Jueves</th><th >Viernes</th><th >S&aacute;bado</th><th >Domingo</th></tr><tr>";
			
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
						if ($hayevento>0) echo "<a href='#' data-evento='#evento".$dia_actual."' class='mod' rel='".$fecha_completa."' title='Hay ".$hayevento." eventos'>".$dia."</a>";
						else echo "$dia";
						
						/* agregamos enlace a nuevo evento si la fecha no ha pasado */

						$busqueda=Modelo\BD\PartelogisticaBD::getEstadoParteByFecha($trabajador,$fecha_completa);
						if($busqueda==null || $busqueda==1){
							$hoy=new \DateTime();
							if($fecha_completa<=$hoy->format('Y-m-d')) {

								$agregar = true;
							}
							else{$agregar=false;}
						}
						else{
							$agregar=false;
						}

						if ($agregar) echo "<a href='#' data-evento='#nuevo_evento' title='Agregar un Evento el ".fecha($fecha_completa)."' class='add agregar_evento' rel='".$fecha_completa."'>&nbsp;</a>";
						
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
	case "addViaje":{
			//MIRO SESSION SI EXISTE PARTE
			if(isset($_SESSION['Parte']) && unserialize($_SESSION["Parte"])->getFecha() == $_POST["fecha"]){
				$parte=unserialize($_SESSION['Parte']);
				$viaje=new Modelo\Base\Viaje(null,$_POST['horaInicio'],$_POST['horaFin'],$_POST['albaran'],new Modelo\Base\Vehiculo		($_POST['vehiculo']),$parte);
				echo "<div class='alert alert-success' role='alert'>".Modelo\BD\ViajeBD::add($viaje)."</div>";
			}
			else{
				$trabajador=unserialize($_SESSION['trabajador']);
				$fecha=new \DateTime($_POST['fecha']);
				$parte=Modelo\BD\PartelogisticaBD::getParteByFecha($trabajador,$fecha->format('Y-m-d'));
				if($parte!=null){
					//insert viaje En ese parte
					$_SESSION['Parte']=serialize($parte);
					$viaje=new Modelo\Base\Viaje(null,$_POST['horaInicio'],$_POST['horaFin'],$_POST['albaran'],new Modelo\Base\Vehiculo		($_POST['vehiculo']),$parte);
					echo "<div class='alert alert-success' role='alert'>".Modelo\BD\ViajeBD::add($viaje)."</div>";

				}
				else{
					$fecha=new \DateTime($_POST['fecha']);
					$parte=new Modelo\Base\ParteLogistica(null,$fecha->format('Y-m-d'),null,null,null,null,new Modelo\Base\Estado(1,null), $trabajador,null);
					$id=Modelo\BD\PartelogisticaBD::add($parte);

					//$_POST['Parte']=serialize($parte);
					$parte->setId($id);

					$viaje=new Modelo\Base\Viaje(null,$_POST['horaInicio'],$_POST['horaFin'],$_POST['albaran'],new Modelo\Base\Vehiculo		($_POST['vehiculo']),$parte);
					echo "<div class='alert alert-success' role='alert'>".Modelo\BD\ViajeBD::add($viaje)."</div>";

				}
			}


		break;


	}
	//Aitor i
    case "modificar_evento":{

        Modelo\BD\ViajeBD::ModificarViaje($_POST["id"], $_POST["horaInicio"], $_POST["horaFin"], $_POST["albaran"], $_POST["fecha"],$_POST["vehiculo"]);




        break;
    }


}
?>