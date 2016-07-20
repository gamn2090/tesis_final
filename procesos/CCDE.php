<?php

	/*include('funciones.php');	
	include('CRetiros.php');	
 	include('CReingresos.php');	
 	//include('CCDE.php');	
 	include('CRazon_proceso')
  	include('CHistorico.php');
  	include('CUsers.php')	
  	include('CSolicitudes.php')


 	$objRetiros = new Retiro();
 	$objHistorico = new Historico();
 	$objReingresos = new Reingreso();
 	//$objCDE = new CDE();
 	$objRazon = new Razon_proceso();
 	$objSolicitudes = new Solicitudes();
 	$objUsers = new User();*/

class CDE{
	
	var $conn;

function CDE()
{
	
	include('../config_objeto.php');
	
}
/*====================================================================================================================================
					FUNCION PARA ALMACENAR LOS RETIROS REALIZADOS POR LOS ESTUDIANTES
====================================================================================================================================*/

function ingresar_solicitud_cde($cedula,$fecha,$especialidad,$asignatura)
{
		
	$query= "INSERT INTO solicitudes_cde (cedula, especialidad_esta_estudiando,  especialidad_quiere_estudiar, fecha_solicitud, exp) 
			VALUES ('$cedula', '$especialidad', '$asignatura',  '$fecha', '-1')";
	
	$result=$this->conn->Execute($query);
	if($result==false)
	{$bandera=1;}
	else
	{$bandera=0;}
	return $bandera;	
	$this->conn->Close();
}

/*====================================================================================================================================
					FUNCION PARA MOSTRAR LOS RETIROS REALIZADOS POR LOS ESTUDIANTES
====================================================================================================================================*/

function mostrar_proceso($proceso,$bandera,$nivel)
{   	
			if($nivel==$bandera)
			{
				$query="SELECT * FROM solicitudes_cde WHERE (exp NOT LIKE '-1' AND exp NOT LIKE '0')";
				$result1=$this->conn->Execute($query);	
				if($result1==false)
				{
					echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
				}
				else
				{		
					$demanda=$result1->RowCount();
					?>
					<form id="Evaluar_estudiante" class="col s12" action="../procesos/motor_funciones.php" target="_blank" method="POST">
					    <input type="hidden" id="demanda" name="demanda"  value="<?php echo $demanda; ?>">
   	
					        <div class="row">
	                        	<div class="input-field col s6 offset-s3">
		                            <input id="oferta" name="oferta" type="text" class="validate" placeholder="Ingrese la cantidad de cupos" value="">
		                            <label for="nombre">Cupos disponibles</label>
					        	</div>
					        </div>
					        <div class="row">
					        <div class="input-field col s6 offset-s3">
						        <select class="browser-default" id="carrera" name="carrera">
						        <?php 
									$query2="SELECT * FROM esp";	
									$result=$this->conn->Execute($query2);
									if($result==false)
									{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;}
									else
									{	
										?>
										<option value="" disabled selected>Carrera a optar</option>
										<?php	
										while(!$result->EOF) 
											{			
												for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
												{
													$codigo=$result->fields[0];	
													$carrera=$result->fields[1];														
												}	
												?>
						                        <option><?php echo $carrera; ?></option>
						                        <?php					
												$result->MoveNext();
											}
									}
								?>						        
						        </select>
						    </div>
						   	</div>
                            <div class="divider"></div>
		                    <div class="row">                       
		                        <div class="col m12 offset">
		                            <p class="center-align">
		                                <button class="btn btn-large waves-effect waves-light" id="crear" type="submit" value="cambios" name="accion" title="login">Procesar Cambios</button>
		                            </p>
		                        </div>
		                    </div>
					</form>	
					<?php
					}
			}
			else
			{	$j=0;
				$query="SELECT * FROM solicitudes_cde WHERE (exp LIKE '-1')";
				$result=$this->conn->Execute($query);						
					while(!$result->EOF) 
					{			
						for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
						{
							$numero_sol=$result->fields[1];
							$cedula=$result->fields[0];
							$exp=$result->fields[5];
							$carrera_actual=$result->fields[2];
							$carrera_siguiente=$result->fields[3];
							$cedula2=base64_encode ($cedula);
							$exp2=base64_encode ($exp);
							$numero_sol2=base64_encode ($numero_sol);
							$proceso=base64_encode('Cambio');
							$link="<a href=\"evaluar.php?proceso=".$proceso."&exp=".$exp2."&id=".$cedula2."&numero=".$numero_sol2."\" target='_blank'>Evaluar</a>";							
							$query3="SELECT * FROM esp WHERE codigo LIKE '%$carrera_actual%'";
							$result2=$this->conn->Execute($query3);							
								while(!$result2->EOF) 
								{			
									for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
									{	
										$carrera_actual=$result2->fields[1];
									}
									break;
								}
								
							$query4="SELECT * FROM esp WHERE codigo LIKE '%$carrera_siguiente%'";
							$result3=$this->conn->Execute($query4);
							
								while(!$result3->EOF) 
								{			
									for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
									{	
										$carrera_siguiente=$result3->fields[1];
									}
									break;
								}									
							$result->MoveNext();											
							break;	
						}
						$data[$j]=array("cedula"=>$cedula,
										"numero"=> $numero_sol,
										"carrera_a"=> $carrera_actual,
										"carrera_s"=> $carrera_siguiente,
										"link"=>$link);
						$j++;
					} 
					header('Content-type: application/json');
					return json_encode($data);				
				
			}
		}



/*============================================================================================================================
					FUNCIONES PARA SABER EL AÑO
==============================================================================================================================*/

function obtener_anio($fecha)
{
	$fecha1=substr($fecha,0,4);
	return $fecha1;
}

/*============================================================================================================================
					FUNCION PARA VALIDAR SI UNA FECHA ESTÁ EN UN RANGO
==============================================================================================================================*/

function valida_fecha($inicio, $fin, $validar) 
{ 
    if(strtotime($validar)>=strtotime($inicio) && strtotime($validar)<=strtotime($fin)) 
		return true; 
    else 
		return false; 
} 

/*============================================================================================================================
					FUNCION PARA CALCULA DIFERENCIA ENTRE DOS FECHAS
==============================================================================================================================*/
function date_diff1($date1, $date2) 
{ 
    $current = $date1; 
    $datetime2 = date_create($date2); 
    $count = 0; 
    while(date_create($current) < $datetime2)
	{ 
        $current = gmdate("Y-m-d", strtotime("+1 day",strtotime($current))); 
        $count++; 
    } 
    return $count; 
} 

/*============================================================================================================================
					FUNCION PARA APROBAR O RECHAZAR CAMBIOS
==============================================================================================================================*/

function ingresar_cambio($demanda,$oferta,$carrera)
{
		
		$query="SELECT * FROM esp WHERE (nombre LIKE '%$carrera%')";
		$result=$this->conn->Execute($query);
		if($result==false)
		{
			echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
		}
		else
		{					
			while(!$result->EOF) 
			{			
				for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
				{
					$codigo=$result->fields[0];
				}
				$result->MoveNext();
			}
		}

	if(($demanda<$oferta) || $demanda==$oferta)
	{
		$id=3;		
		$query2="SELECT * FROM solicitudes_cde WHERE (exp NOT LIKE '-1') AND (especialidad_quiere_estudiar LIKE '%$codigo%')";
		$result=$this->conn->Execute($query2);	
		if($result==false)
		{
			echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
		}
		else
		{				
			while(!$result->EOF) 
			{			
				for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
				{
					$cedula=$result->fields[0];	
					$razon = 'No hay razón';				
					$fecha_solicitud=$result->fields[4];
					$exp=$result->fields[5];				
				}
					$query3="SELECT * FROM estudiante WHERE (ced LIKE '%$cedula%')";
					$result=$this->conn->Execute($query3);
					if($result==false)
					{
						echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
					}
					else
					{				
						while(!$result->EOF) 
						{			
							for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
							{
								$promedio=$result->fields[25];					
								$nombre=$result->fields[3];
							}
							$result->MoveNext();
						}
						if($promedio>5)
						{
							$promedio='Tiene promedio positivo, '.$promedio.'/10';
						}
						else
						{
							$promedio='Tiene promedio negativo, '.$promedio.'/10';
						}
					}

					$anio=$this->obtener_anio($fecha_solicitud);
					$tiempo_sol=$this->tiempo_solicitud_retiro($anio,$fecha_solicitud);
					if($tiempo_sol==-1)
					{
						$fecha='Ingreso la solicitud en el tiempo estimado';
					}
					else
					{
						$fecha='No ingreso la solicitud en el tiempo estimado, la ingreso '.$tiempo_sol.' dias tarde';
					}
					$solicitud_actual='Cambio';
					$aval='Presentó aval por la razon';


					$solicitudes2=$this->buscar_historico($cedula,'Cambio');
					if(($solicitudes2==0))
					{
						$solicitudes="No tiene";	
					}
					else
					{
						$solicitudes="Si tiene";	
					}		
					if($nombre!=NULL)
					{
						$algo=1;
					}
					else
					{
						$algo=0;
					}				
					
					if($algo==1)
					{
						$cant_soli1=$this->cantidad_solicitud_historico($cedula,'Cambio');
						if($cant_soli1==0)
						{
							$cant_soli=0;	
						}
						else
						{
							$cant_soli=$cant_soli1+$cant_soli2;	
						}
						$solicitudes="El estudiante tiene ".$cant_soli." Solicitudes anteriores";
					}
					$medi=0;
					$query4="SELECT * FROM medidas_academicas WHERE cedula LIKE '%$cedula%'";
					$result=$this->conn->Execute($query4);
					if($result==false)
					{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
						
					}	
					else
					{
						while(!$result->EOF) 
						{			
							$medi=1;
							$result->MoveNext();
						}			
					}
					if($medi==0)
					{
						$medidas="No tiene medidas";
					}
					else
					{
						$medidas="Tiene medidas";
					}
					$decision='Aprobado';
					$acuerdo='Si';
					$observaciones='Cambio a '.$carrera;



					$this->ingresar_historico($exp,$cedula,$fecha_solicitud,$razon,$promedio,$solicitudes,$solicitud_actual,$aval,$fecha,$medidas,$decision,$observaciones,$acuerdo);
				$result->MoveNext();	
			}
							
		}
		return $id;
	}	
	else
	{
		if($demanda>$oferta)
		{
			
			$query2="SELECT A.* FROM solicitudes_cde A, estudiante B WHERE (A.exp NOT LIKE '-1' AND A.especialidad_quiere_estudiar LIKE '%$codigo%') ORDER BY B.promedio DESC LIMIT $oferta";
			$result=$this->conn->Execute($query2);	
			if($result==false)
			{
				echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
			}
			else
			{				
				while(!$result->EOF) 
				{			
					for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
					{
						$cedula=$result->fields[0];
						$razon='No hay razon';
						$fecha_solicitud=$result->fields['fecha_solicitud'];
						$exp=$result->fields['exp'];				
					}
						$query3="SELECT * FROM estudiante WHERE (ced LIKE '%$cedula%')";
						$result=$this->conn->Execute($query3);
						if($result==false)
						{
							echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
						}
						else
						{				
							while(!$result->EOF) 
							{			
								for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
								{
									$promedio=$result->fields[25];					
									$nombre=$result->fields[3];
								}
								$result->MoveNext();
							}
							if($promedio>5)
							{
								$promedio='Tiene promedio positivo, '.$promedio.'/10';
							}
							else
							{
								$promedio='Tiene promedio negativo, '.$promedio.'/10';
							}
						}
						$anio=$this->obtener_anio($fecha_solicitud);
						$tiempo_sol=$this->tiempo_solicitud_retiro($anio,$fecha_solicitud);
						if($tiempo_sol==-1)
						{
							$fecha='Ingreso la solicitud en el tiempo estimado';
						}
						else
						{
							$fecha='No ingreso la solicitud en el tiempo estimado, la ingreso '.$tiempo_sol.' dias tarde';
						}
						$solicitud_actual='Cambio';
						$aval='Presentó aval por la razon';


						$solicitudes2=$this->buscar_historico($cedula,'Cambio');
						if(($solicitudes2==0))
						{
							$solicitudes="No tiene";	
						}
						else
						{
							$solicitudes="Si tiene";	
						}		
						if($nombre!=NULL)
						{
							$algo=1;
						}
						else
						{
							$algo=0;
						}						
						
						if($algo==1)
						{
							$cant_soli1=$this->cantidad_solicitud_historico($cedula,'Cambio');
							if($cant_soli1==0)
							{
								$cant_soli=0;	
							}
							else
							{
								$cant_soli=$cant_soli1+$cant_soli2;	
							}
							$solicitudes="El estudiante tiene ".$cant_soli." Solicitudes anteriores";
						}
						$medi=0;
						$query4="SELECT * FROM medidas_academicas WHERE cedula LIKE '%$cedula%'";
						$result=$this->conn->Execute($query4);
						if($result==false)
						{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
							
						}	
						else
						{
							while(!$result->EOF) 
							{			
								$medi=1;
								$result->MoveNext();
							}			
						}
						if($medi==0)
						{
							$medidas="No tiene medidas";
						}
						else
						{
							$medidas="Tiene medidas";
						}
						$decision='Aprobado';
						$acuerdo='Si';
						$observaciones='Cambio a '.$carrera;
					$result->MoveNext();	
				}
				
			   $this->ingresar_historico($exp,$cedula,$fecha_solicitud,$razon,$promedio,$solicitudes,$solicitud_actual,$aval,$fecha,$medidas,$decision,$observaciones,$acuerdo);

			}
			$query10="SELECT * FROM solicitudes_cde WHERE (exp NOT LIKE '-1') AND (especialidad_quiere_estudiar LIKE '%$codigo%') LIMIT $oferta";
			$result=$this->conn->Execute($query10);	
			if($result==false)
			{
				echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
			}
			else
			{				
				while(!$result->EOF) 
				{			
					for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
					{
						$cedula=$result->fields[0];
						$razon=$result->fields[2];
						$fecha_solicitud=$result->fields[5];
						$exp=$result->fields[6];				
					}
						$query12="SELECT * FROM estudiante WHERE (ced LIKE '%$cedula%')";
						$result=$this->conn->Execute($query12);
						if($result==false)
						{
							echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
						}
						else
						{				
							while(!$result->EOF) 
							{			
								for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
								{
									$promedio=$result->fields[25];					
									$nombre=$result->fields[3];
								}
								$result->MoveNext();
							}
							if($promedio>5)
							{
								$promedio='Tiene promedio positivo, '.$promedio.'/10';
							}
							else
							{
								$promedio='Tiene promedio negativo, '.$promedio.'/10';
							}
						}
						$anio=$this->obtener_anio($fecha_solicitud);
						$tiempo_sol=$this->tiempo_solicitud_retiro($anio,$fecha_solicitud);
						if($tiempo_sol==-1)
						{
							$fecha='Ingreso la solicitud en el tiempo estimado';
						}
						else
						{
							$fecha='No ingreso la solicitud en el tiempo estimado, la ingreso '.$tiempo_sol.' dias tarde';
						}
						$solicitud_actual='Cambio';
						if($exp!='0')
						{ 	
							$aval='Presentó aval por la razon';
						}
						else
						{
							$aval='No presentó aval por la razon';
						}

						$solicitudes2=$this->buscar_historico($cedula,'Cambio');
						if(($solicitudes2==0))
						{
							$solicitudes="No tiene";	
						}
						else
						{
							$solicitudes="Si tiene";	
						}		
						if($nombre!=NULL)
						{
							$algo=1;
						}
						else
						{
							$algo=0;
						}						
						
						if($algo==1)
						{
							$cant_soli1=$this->cantidad_solicitud_historico($cedula,'Cambio');
							if($cant_soli1==0)
							{
								$cant_soli=0;	
							}
							else
							{
								$cant_soli=$cant_soli1+$cant_soli2;	
							}
							$solicitudes="El estudiante tiene ".$cant_soli." Solicitudes anteriores";
						}
						$medi=0;
						$query11="SELECT * FROM medidas_academicas WHERE cedula LIKE '%$cedula%'";
						$result=$this->conn->Execute($query11);
						if($result==false)
						{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
							
						}	
						else
						{
							while(!$result->EOF) 
							{			
								$medi=1;
								$result->MoveNext();
							}			
						}
						if($medi==0)
						{
							$medidas="No tiene medidas";
						}
						else
						{
							$medidas="Tiene medidas";
						}
						$decision='Reprobado';
						$acuerdo='Si';
						$observaciones='Cambio a '.$carrera;
					$result->MoveNext();	
				}	
				$this->ingresar_historico($exp,$cedula,$fecha_solicitud,$razon,$promedio,$solicitudes,$solicitud_actual,$aval,$fecha,$medidas,$decision,$observaciones,$acuerdo);
	

			}

			return $id;
		}		
	}
$this->conn->Close();
}

/*============================================================================================================================
					FUNCION PARA VER LA CANTIDAD DE SOLICITUDES REALIZADAS POR UN ESTUDIANTE EN LA BD 
=============================================================================================================================*/

function cantidad_solicitud_historico($cedula,$proceso)
{	$cont=0;
	$query="SELECT * FROM decisiones WHERE ((cedula LIKE '%$cedula%') AND (solicitud_actual LIKE '%$proceso%'))";	
	$result=$this->conn->Execute($query);
	if($result==false)
	{
		echo "error al recuperar 3: ".$this->conn->ErrorMsg()."<br>" ;
	}
	else
	{
		while(!$result->EOF) 
		{					
			$cont++;
			$result->MoveNext();									
		}
			
	}
	return $cont;
	$this->conn->Close();
}

function ingresar_historico($exp,$cedula,$fecha_solicitud,$razon,$promedio,$solicitudes,$solicitud_actual,$aval,$fecha,$medidas,$decision,$observaciones,$acuerdo)
{	

	$anio=$this->obtener_anio($fecha_solicitud);
	if($acuerdo=='No')
	{
		if($decision=="Aprobado")
		{
			$decision="Reprobado";	
		}
		else
		{
			$decision="Aprobado";
		}
	}	
	if($exp=='0')
	{
		if($solicitud_actual=='Retiro')
		{
			$query="SELECT COUNT (exp) AS total_rt FROM decisiones WHERE (exp LIKE 'RT-%')";
			$result=$this->conn->Execute($query);
		}
		else
		{
			if($solicitud_actual=='Reingreso')
			{
				$query="SELECT COUNT (exp) AS total_rt FROM decisiones WHERE (exp LIKE 'RCC-%')";
				$result=$this->conn->Execute($query);
			}
			else
			{
				if($solicitud_actual=='Reingreso_tg')
				{
					$query="SELECT COUNT (exp) AS total_rt FROM decisiones WHERE (exp LIKE 'RTG-%')";
					$result=$this->conn->Execute($query);
				}
				else
				{
					if($solicitud_actual=='Cambio')
					{
						$query="SELECT COUNT (exp) AS total_rt FROM decisiones WHERE (exp LIKE 'CE-%')";
						$result=$this->conn->Execute($query);
					}
				}
			}
		}	
		if($result==false)
		{
			echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
		}
		else
		{		

			while(!$result->EOF) 
			{	
				for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
				{							
					$cont=$result->fields['total_rt'];				
					$result->MoveNext();											
					break;												
				}
				
				
			}
		} 
		if($solicitud_actual=='Retiro')
		{
			$query="SELECT COUNT (exp) AS total_rt FROM decisiones WHERE (exp LIKE 'RT-%')";
			$result=$this->conn->Execute($query);
		}
		else
		{
			if($solicitud_actual=='Reingreso')
			{
				$query="SELECT COUNT (exp) AS total_rt FROM decisiones WHERE (exp LIKE 'RCC-%')";
				$result=$this->conn->Execute($query);
			}
			else
			{
				if($solicitud_actual=='Reingreso_tg')
				{
					$query="SELECT COUNT (exp) AS total_rt FROM decisiones WHERE (exp LIKE 'RTG-%')";
					$result=$this->conn->Execute($query);
				}
				else
				{
					if($solicitud_actual=='Cambio')
					{
						$query="SELECT COUNT (exp) AS total_rt FROM decisiones WHERE (exp LIKE 'CE-%')";
						$result=$this->conn->Execute($query);
					}
				}
			}
		}	
		if($result==false)
		{
			echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
		}
		else
		{		

			while(!$result->EOF) 
			{	
				for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
				{							
					$cont=$result->fields['total_rt'];				
					$result->MoveNext();											
					break;												
				}				
				
			}
		} 
		$cont=$cont+1;
		$anio=substr($anio, -2);

		switch ($solicitud_actual)
		{
			

			case 'Retiro':			//BUSCAR ESTA FUNCION PERIODO		
						$periodo=$this->saber_periodo($anio,$fecha);
						$aval1="RT-".$cont.".".$anio.".".$periodo;
						$query2="UPDATE solicitudes_ret SET exp='$aval1' WHERE numero_soli= $numero_soli";
						$result=$this->conn->Execute($query2);
						if($result==false)
						{
							$bandera=0;
						}
						else
						{	
							$bandera=1;
						}					
			break;		
			case 'Cambio':
						$periodo=$this->saber_periodo($anio,$fecha);
						$aval1="CE-".$cont.".".$anio.".".$periodo;
						$query2="UPDATE solicitudes_cde SET exp='$aval1' WHERE numero_soli= $numero_soli";
						$result=$this->conn->Execute($query2);
						if($result==false)
						{
							$bandera=0;
						}
						else
						{	
							$bandera=1;
						}	
					
			break;
			case 'Reingreso':
						$periodo=$this->saber_periodo($anio,$fecha);
						$aval1="RCC-".$cont.".".$anio.".".$periodo;
						$query2="UPDATE solicitudes_rein SET exp='$aval1' WHERE numero_soli= $numero_soli";
						$result=$this->conn->Execute($query2);
						if($result==false)
						{
							$bandera=0;
						}
						else
						{	
							$bandera=1;
						}	
					
			break;	
				case 'Reingreso_tg':
						$periodo=$this->saber_periodo($anio,$fecha);
						$aval1="RTG-".$cont.".".$anio.".".$periodo;
						$query2="UPDATE solicitudes_rein SET exp='$aval1' WHERE numero_soli= $numero_soli";
						$result=$this->conn->Execute($query2);
						if($result==false)
						{
							$bandera=0;
						}
						else
						{	
							$bandera=1;
						}						
				break;	

		}
	}
	else
	{
		$aval1=$exp;
	}	
	$query= "INSERT INTO decisiones (cedula, fecha_solicitud, razon, promedio, solicitudes, solicitud_actual, aval, medidas, tiempo_sol, decision, observaciones, acuerdo, exp) VALUES ('$cedula', '$fecha_solicitud', '$razon', '$promedio', '$solicitudes', '$solicitud_actual', '$aval', '$medidas', '$fecha', '$decision', '$observaciones', '$acuerdo', '$aval1')"; 
	if($this->conn->Execute($query)==false)
	{
		$bandera=1;
		echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
	}
	else
	{
		$bandera=0;
		if($solicitud_actual =='Retiro')
		{
			$query2= "DELETE FROM solicitudes_ret WHERE proceso LIKE '%$solicitud_actual%' AND fecha_solicitud='%$fecha_solicitud%' AND cedula LIKE '%$cedula%' AND exp LIKE '%$exp%'"; 
			if($this->conn->Execute($query2)==false)
			{
				echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
			}
		}
		else
		{
			if(($solicitud_actual =='Reingreso')||($solicitud_actual =='Reingreso_tg'))
			{
				$query2= "DELETE FROM solicitudes_rein WHERE proceso LIKE '%$solicitud_actual%' AND fecha_solicitud='%$fecha_solicitud%' AND cedula LIKE '%$cedula%' AND exp LIKE '%$exp%'"; 
				if($this->conn->Execute($query2)==false)
				{
					echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
				}
			}
			else
			{
				$query2= "DELETE FROM solicitudes_cde WHERE fecha_solicitud='%$fecha_solicitud%' AND cedula LIKE '%$cedula%' AND exp LIKE '%$exp%'"; 
				if($this->conn->Execute($query2)==false)
				{
					echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
				}
			}
		}
	}
	
	
return $bandera;
$this->conn->Close();
}

/*============================================================================================================================
					FUNCION PARA SABER SI ESTÁ A TIEMPO O NO (RETIRO)
==============================================================================================================================*/
function tiempo_solicitud_retiro($anio,$fecha)
{
	$query="SELECT * FROM periodo WHERE (ano LIKE '%$anio%')";	
	$result=$this->conn->Execute($query);
	if($result==false)
	{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;}
	else
	{
		while(!$result->EOF) 
		{			
			for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
			{
				$fecha_inicio_sem=$result->fields[3];
				$fecha_fin_sem=$result->fields[4];
				if($this->valida_fecha($fecha_inicio_sem, $fecha_fin_sem, $fecha))
				{
					$fecha_inicio_ret=$result->fields[14];
					$fecha_fin_ret=$result->fields[15];
				}
			}
			$result->MoveNext();
		}
	}	

	if($this->valida_fecha($fecha_inicio_ret, $fecha_fin_ret, $fecha)) 
	{
		$cant_dias='-1';
	}
	else
	{
		$cant_dias=$this->date_diff1($fecha_fin_ret, $fecha);	
	}
	return $cant_dias;
	$this->conn->Close();
}

function buscar_historico($cedula,$proceso)
{
	
	$query2="SELECT * FROM decisiones WHERE (cedula LIKE '%$cedula%') AND solicitud_actual LIKE '%$proceso%'";	
	$result=$this->conn->Execute($query2);
	if($result==false)
	{echo "error al recuperar: ".$conn->ErrorMsg()."<br>" ;}
	else
	{		
				while(!$result->EOF) 
				{			
					for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
					{
						$solicitudes=$result->fields[5];					
						
					}
					if( $solicitudes==$proceso)
						{
							$solicitudes2=1;	
						}
						else
						{
							$solicitudes2=0;	
												
						}
					$result->MoveNext();
				}
				
	}	
	return $solicitudes2;
	$conn->Close();
}

/*============================================================================================================================
					FUNCION PARA SABER el periodo en el que se hace la solicitud
==============================================================================================================================*/

function saber_periodo($anio,$fecha)
{
	$query="SELECT * FROM periodo WHERE (ano LIKE '%$anio%')";	
	$result=$this->conn->Execute($query);
	if($result==false)
	{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;}
	else
	{
		while(!$result->EOF) 
		{			
			for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
			{
				$fecha_inicio_sem=$result->fields[3];
				$fecha_fin_sem=$result->fields[4];
				if($this->valida_fecha($fecha_inicio_sem, $fecha_fin_sem, $fecha))
				{
					$periodo=$result->fields[1];
				}
			}
			$result->MoveNext();
		}
	}
	return $periodo;
	$this->conn->Close();
}


}

?>
