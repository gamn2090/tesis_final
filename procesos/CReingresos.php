<?php

	/*include('funciones.php');	
	include('CRetiros.php');	
 	//include('CReingresos.php');	
 	include('CCDE.php');	
 	include('CRazon_proceso')
  	include('CHistorico.php');
  	include('CUsers.php')	
  	include('CSolicitudes.php')


 	$objRetiros = new Retiro();
 	$objHistorico = new Historico();
 	//$objReingresos = new Reingreso();
 	$objCDE = new CDE();
 	$objRazon = new Razon_proceso();
 	$objSolicitudes = new Solicitudes();
 	$objUsers = new User();*/

class Reingreso{
	
	var $conn;

function Reingreso()
{
	
	include('../config_objeto.php');
	
}
/*====================================================================================================================================
					FUNCION PARA ALMACENAR LOS REINGRESOS REALIZADOS POR LOS ESTUDIANTES
====================================================================================================================================*/

function ingresar_solicitud_rein($cedula,$proceso,$razon,$fecha)
{
	
			
	$query= "INSERT INTO solicitudes_rein (cedula, razon, proceso, fecha_solicitud, exp)
			VALUES ('$cedula', '$razon', '$proceso', '$fecha', '-1')";

	$result=$this->conn->Execute($query);
	if($result==false)
	{$bandera=1;}
	else
	{$bandera=0;}
	return $bandera;	
	$this->conn->Close();
}

/*====================================================================================================================================
					FUNCION PARA MOSTRAR LOS REINGRESOS REALIZADOS POR LOS ESTUDIANTES
====================================================================================================================================*/

function mostrar_proceso($proceso,$bandera,$nivel)
{   	
	if($nivel==$bandera)
				{
					$query="SELECT * FROM solicitudes_rein WHERE (proceso LIKE '$proceso%')";
					$result=$this->conn->Execute($query);	
						
						$j=0;
						while(!$result->EOF) 
						{	
							for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
							{	
								$proceso=$result->fields['proceso'];						
								$cedula=$result->fields[0];
								$numero_sol=$result->fields[1];
								$grupo=$result->fields[2];	
								$exp=$result->fields['exp'];
								$razon=$result->fields['razon'];
								$cedula2=base64_encode($cedula);
								$numero_sol2=base64_encode($numero_sol);
								$proceso=base64_encode($proceso);
								$exp=base64_encode($exp);
								$link="<a href=\"evaluar.php?proceso=".$proceso."&exp=".$exp."&id=".$cedula2."&numero=".$numero_sol2."\" target='_blank'>Evaluar</a>";
								$result->MoveNext();											
								break;												
							}
							$data[$j]=array("cedula"=>$cedula,
											"numero"=> $numero_sol,
											"grupo"=> $grupo,
											"razon"=>$razon,
											"link"=>$link);
							$j++;
						} 
						header('Content-type: application/json');
						return json_encode($data);
					
				}
				else
				{
					$query="SELECT * FROM solicitudes_rein WHERE (proceso LIKE '$proceso%' AND exp LIKE '-1')";
					$result=$this->conn->Execute($query);	
				}		
					if($result==false)
					{
						echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
					}
					else
					{		
						$j=0;
						while(!$result->EOF) 
						{	
							for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
							{	
								$proceso=$result->fields[4];						
								$cedula=$result->fields[0];
								$numero_sol=$result->fields[1];
								$exp=$result->fields['exp'];
								$cedula2=base64_encode($cedula);
								$exp=base64_encode($exp);
								$numero_sol2=base64_encode($numero_sol);
								$proceso=base64_encode($proceso);
								$link="<a href=\"evaluar.php?proceso=".$proceso."&exp=".$exp."&id=".$cedula2."&numero=".$numero_sol2."\" target='_blank'>Evaluar</a>";						
								$result->MoveNext();											
								break;												
							}
							$data[$j]=array("cedula"=>$cedula,
											"numero"=> $numero_sol,
											"link"=>$link);
							$j++;
						} 
						header('Content-type: application/json');
						return json_encode($data);
					}
}
		





/*============================================================================================================================
					FUNCION PARA SABER SI ESTÁ A TIEMPO O NO (REINGRESO)
==============================================================================================================================*/
function tiempo_solicitud_reingreso($anio,$fecha)
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

/*=================================================================================================================================
								CALCULAR GRUPO
===================================================================================================================================*/
function evaluar_reingreso($cedula, $cambios, $fecha)
{
	$query="SELECT COUNT(distinct ano) cont FROM notas WHERE ((per = '1' AND nota > '4') OR (per = '2' AND nota > '4')) AND cedula = '".$cedula."' ";
	$result = $this->conn->Execute($query);
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
				$cont=$result->fields[0];
				$result->MoveNext();											
				break;	
			}
		}
	}
	$query2="SELECT SUM(to_number(substr((A.asignatura),7,1),'9')) credito, B.credito_titulo total_credito from notas as A, esp as B 
	WHERE (A.nota > '4' AND 
	A.cedula = '".$cedula."' AND 
	A.especialidad = B.codigo) GROUP BY B.credito_titulo";
	$result2 = $this->conn->Execute($query2);
	if($result2==false)
	{
		echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
	}
	else
	{
		while(!$result2->EOF) 
		{			
			for ($i=0, $max=$result2->FieldCount(); $i<$max; $i++)
			{
				$creditos_apro=$result2->fields[0];
				$creditos_titulo=$result2->fields[1];
				$result2->MoveNext();											
				break;	
			}
		}
	}
	$query3="SELECT tipo_medida FROM medidas_academicas WHERE cedula LIKE '".$cedula."'";
	$result3 = $this->conn->Execute($query3);
	if($result3==false)
	{
		echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
	}
	else
	{ $cont_medida=0;
		while(!$result3->EOF) 
		{	$cont_medida++;		
			for ($i=0, $max=$result3->FieldCount(); $i<$max; $i++)
			{
				$medida=$result3->fields['tipo_medida'];
				$result3->MoveNext();											
				break;	
			}
		}
	}
	/*
		"1" -> "repite 1 vez"
		"2" -> "repite 2 o 3 veces"
		"3" -> "repite 4 veces"
		"4" -> "repite 5 veces"
		"5" -> "repite 6 veces"
		"6" -> "permanencia del 25%"
	*/
	
	if(($cont >= 10) && ($creditos_apro < ($creditos_titulo/2)) && $cambios > 0)
	{
		$grupo = '1';
	}
	else
	{
		if(($cont >= 10) && ($creditos_apro < ($creditos_titulo/2)) && $cambios == 0)
		{
			$grupo = '2';
		}
		else
		{
			if($medida=='4')	
			{
				$grupo = '3';
			}
			else
			{
				if($cambios > 0 && $cont_medida == 0)
				{
					$grupo = '4';
				}	
				else
				{
					if($medida=='5')
					{
						$grupo= '5';
					}	
					else
					{
						if($medida=='6')
						{
							$grupo = '6';	
						}	
						else
						{
							if($cambios == 0 && $cont_medida == 0)
							{
								$grupo = '7';
							}	
							else
							{
								$grupo= '7';
							}
						}
					}
				}
			}
		}	
	}
	return $grupo;
	
}


}

?>
