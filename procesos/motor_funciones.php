<?php 
	session_start();
	$nivel=$_SESSION['nivel'];
	$bandera=$_SESSION['bandera'];
 	include('../config.php');
 	include('CRetiros.php');	
 	include('CReingresos.php');	
 	include('CCDE.php');	
 	include('CRazon_proceso.php');
  	include('CHistorico.php');
  	include('CUsers.php');
  	include('CSolicitudes.php');


 	$objRetiros = new Retiro();
 	$objHistorico = new Historico();
 	$objReingresos = new Reingreso();
 	$objCDE = new CDE();
 	$objRazon = new Razon_proceso();
 	$objSolicitudes = new Solicitudes();
 	$objUsers = new User();

 	//$objRetiros = new Retiro;

    if(isset($_POST['accion'])){ $accion=$_POST['accion']; 
    switch ($accion){ 		
		case 'Guardar':/*ingresa una nueva solicitud*/	  	
			//var_dump($conn);
		  	if(isset($_POST['cedula'])  
			&& isset($_POST['proceso']) 
			&& isset($_POST['razon'])) {
			$cedula=$_POST['cedula'];
			$proceso=$_POST['proceso'];
			$razon=$_POST['razon'];
			
	  		$objSolicitudes->Ingresar_solicitud($cedula, $razon, $proceso);
			}		
		break; 		
 		/*case 'Ingresar':/*insertar nuevas relaciones procesos/razones
		//echo "holis";
		//var_dump($conn);
			if(isset($_POST['Proceso'])  
			&& isset($_POST['Razon']) 
			&& isset($_POST['Puntaje'])
			&& isset($_POST['Porcentaje'])) {
			$proceso=$_POST['Proceso'];
			$razon=$_POST['Razon'];
			$puntaje=$_POST['Puntaje'];
			$porcentaje=$_POST['Porcentaje'];			
			/*echo $porcentaje, $puntaje,$razon,$proceso;			
 			ingresar_procesos($proceso, $razon, $puntaje);
			}			
 		break; */		
 		case 'Entrar':/*login estudiante*/
 		 	if(isset($_POST['cedula'])  
			&& isset($_POST['contraseña']))
			{
				session_start();
				$cedula = $_POST['cedula'];
				$contraseña = $_POST['contraseña'];
			$objUsers->loguear($cedula,$contraseña);			
			}			
		break;
 		case 'Accesar':/*loguear en la coordinación*/
 		 	if(isset($_POST['usuario'])  
			&& isset($_POST['contraseña']))
			{	
				$usuario = $_POST['usuario'];
				$contraseña = $_POST['contraseña'];
			//var_dump($conn); 						
			$objUsers->loguear_coord($usuario,$contraseña);			
			}			
 		break; 		
 		/*case 'Evaluar Bachiller': /*evaluar bachiller
 		 		mostrar($cedula,$nombre,$apellido,$email,$celular,$direccion,$promedio,$discapacidad,$nacionalidad,$solicitudes2,$proceso);
 		break;	*/
 		case 'mostrar_proceso_ret': 				
 				$array = $objRetiros->mostrar_proceso('Retiro',$bandera,$nivel);
 				echo $array;
 		break;	
 		case 'mostrar_proceso_rei':
 				$array=$objReingresos->mostrar_proceso('Reingreso',$bandera,$nivel);
 				echo $array;
 		break;
 		case 'mostrar_proceso_cde': 
 				$array=$objCDE->mostrar_proceso('Cambio',$bandera,$nivel);
 				echo $array;			
 		break;
 		case 'mostrar_historico': 
 				$array=$objHistorico->visualizar_historico($nivel);
 				echo $array;			
 		break;
 		case 'ingresar_historico':
 					$exp=$_POST['exp'];
					$cedula=$_POST['cedula'];					
					$razon=$_POST['razon'];					
					$promedio=$_POST['promedio'];					
					$solicitudes=$_POST['soli_ant'];
					$solicitud_actual=$_POST['solicitud'];
					$aval=$_POST['aval'];					
					$fecha=$_POST['fecha'];	
					$medidas=$_POST['medidas'];	
					$decision=$_POST['decision'];
					$fecha_solicitud=$_POST['fecha_solicitud'];	
					$acuerdo=$_POST['acuerdo'];
					$observaciones=$_POST['observaciones'];			
					if($acuerdo==NULL)
					{
						$acuerdo='Si';
					}
						if($decision=="Indefinida")
						{
						$mensaje1=$objHistorico->ingresar_historico($exp,$cedula,$fecha_solicitud,$razon,$promedio,$solicitudes,$solicitud_actual,$aval,$fecha,$medidas,$decision2,$observaciones,$acuerdo);
						}
						else
						{					
							$mensaje1=$objHistorico->ingresar_historico($exp,$cedula,$fecha_solicitud,$razon,$promedio,$solicitudes,$solicitud_actual,$aval,$fecha,$medidas,$decision,$observaciones,$acuerdo);
						}
						$mensaje=$exp;
						header("location: ../llamadas/resultado.php?mensaje=$mensaje");
 		break;
 		case 'actualizar_puntaje':
 				$proceso=$_POST['proceso'];
				$puntaje=$_POST['puntaje'];
				$fecha=$_POST['fecha'];
				$razon=$_POST['razon'];
				$bandera=$objRazon->actualizar_puntaje($proceso, $razon, $puntaje,$fecha);
				header("location: ../llamadas/cambio_valores.php?bandera=$bandera");	
 		break;
 		case 'crear_cuenta':
 			$cedula=$_POST['cedula'];
			$nombre=$_POST['Nombre'];
			$apellido=$_POST['Apellido'];
			$sexo=$_POST['sexo'];
			$usuario=$_POST['usuario'];
			$contraseña=$_POST['contraseña'];
			$privilegios=$_POST['privilegios'];
			$funcion=$objUsers->crear_cuenta($cedula,$nombre,$apellido,$sexo,$usuario,$contraseña,$privilegios);
			if($funcion==1)
				{
					header("location: ../coordinacion_principal.php?mensaje=$funcion");
				}
				else
				{
					header("location: ../coordinacion_principal.php?&mensaje=$funcion");
				}							
		break;
		case 'ingresar_razon':
				$proceso=$_POST['proceso'];
				$puntaje=$_POST['puntaje'];
				$ponderacion=$_POST['ponderacion'];
				$razon=$_POST['razon'];
				$fecha=$objSolicitudes->fecha_hoy();
				$funcion=$objRazon->ingresar_razon($proceso,$razon,$puntaje,$fecha);				
				header("location: ../coordinacion_principal.php?mensaje=$funcion");					
		break;
		case 'mostrar_valores_iguales':

				/*if($_POST['razon']!= NULL)
				{
					var_dump($conn);
				}*/
					$razon=$_POST['razon'];		//=			
					$promedio=$_POST['promedio'];	//=				
					$solicitudes=$_POST['soli_ant']; //=
					$solicitud_actual=$_POST['solicitud']; //=
					$aval=$_POST['aval'];	//=				
					$tiempo_sol=$_POST['fecha'];	//=
					$medidas=$_POST['medidas'];	//=
					$observaciones=$_POST['observaciones'];	
					$array=$objHistorico->visualizar_antecedentes($razon,$promedio,$solicitudes,$solicitud_actual,$aval,$tiempo_sol,$medidas,$conn);	
					echo $array;				
		break;
		case 'cambios':
				$demanda=$_POST['demanda'];
				$carrera=$_POST['carrera'];
				$oferta=$_POST['oferta'];
				if($demanda > 0)
				{
					$mensaje=$objCDE->ingresar_cambio($demanda,$oferta,$carrera);
					header("location: ../llamadas/resultado.php?mensaje=$mensaje");
				}
				else
				{
					header("location: ../llamadas/guardar.php?mensaje=4");
				}

		break;

		case 'mostrar_valor_ret': 				
 				$array=$objRazon->mostrar_puntaje('Retiro');
 				echo $array;
 		break;			
		case 'mostrar_valor_rein': 				
 				$array=$objRazon->mostrar_puntaje('Reingreso');
 				echo $array;
 		break;	
		/*case 'Correcto': 
		//echo "holis";					
 				$cedula=$_POST['cedula'];
				$proceso=$_POST['solicitud'];
				$fecha=$_POST['fecha'];
				$razon=$_POST['razon'];
				$periodo=$_POST['periodo'];
				$anio=$_POST['anio'];
				$especialidad=$_POST['especialidad'];
				$nucleo=$_POST['nucleo'];
				$estatus=$_POST['estatus'];
				$asignatura=$_POST['asignatura'];
			    $bandera=ingresar_solicitud($cedula,$proceso,$fecha,$razon,$periodo,$anio,$especialidad,$nucleo,$estatus,$asignatura,$conn);
			    $cedula2=base64_encode($cedula);
				header("location: pantalla_retiro.php?cedula=$cedula2&bandera=$bandera");
 		break;*/
		case 'cargar': 
				$proceso=$_POST['proceso'];		
				if($proceso!='')
				{		
	 				$bandera=$objSolicitudes->cargar_solicitudes($proceso);
	 				header("location: ../llamadas/obtener_procesos.php?mensaje=$bandera");
 				}
 				else
 				{	
 					$bandera='900';
 					header("location: ../llamadas/obtener_procesos.php?mensaje=$bandera");
 				}
 		break;	

		}//fin switch	
	}//fin isset[$_post[accion]]

?>