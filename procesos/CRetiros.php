<?php
 	//include('funciones.php');	
 	//include('CRetiros.php');	
 	/*include('CReingresos.php');	
 	include('CCDE.php');	
 	include('CRazon_proceso')
  	include('CHistorico.php');
  	include('CUsers.php')	
  	include('CSolicitudes.php')


 	//$objRetiros = new Retiro();
 	$objHistorico = new Historico();
 	$objReingresos = new Reingreso();
 	$objCDE = new CDE();
 	$objRazon = new Razon_proceso();
 	$objSolicitudes = new Solicitudes();
 	$objUsers = new User();*/

class Retiro{
	
	var $conn;

function Retiro()
{
	
	include('../config_objeto.php');
	
}
/*====================================================================================================================================
					FUNCION PARA ALMACENAR LOS RETIROS REALIZADOS POR LOS ESTUDIANTES
====================================================================================================================================*/

function ingresar_solicitud_ret($cedula,$proceso,$fecha,$razon)
{
	
	$query= "INSERT INTO solicitudes_ret (cedula, razon, proceso, fecha_solicitud, exp)
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
					FUNCION PARA MOSTRAR LOS RETIROS REALIZADOS POR LOS ESTUDIANTES
====================================================================================================================================*/

function mostrar_proceso($proceso,$bandera,$nivel)
{   	
	if($nivel==$bandera)
				{
					$query="SELECT * FROM solicitudes_ret WHERE (proceso LIKE '$proceso%')";
					$result=$this->conn->Execute($query);	
						
						$j=0;
						while(!$result->EOF) 
						{	
							for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
							{	
								$proceso=$result->fields['proceso'];						
								$cedula=$result->fields['cedula'];
								$numero_sol=$result->fields['numero_soli'];
								$exp=$result->fields['exp'];
								$razon = $result->fields['razon'];
								if($exp == '-1')
								{
									$aval = 'No avalado';
								}
								else
								{
									$aval = 'Avalado';
								}
								//$aval2 = base64_encode($aval);
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
											"aval"=> $aval,
											"razon"=> $razon,
											"link"=>$link);
							$j++;
						} 
						header('Content-type: application/json');
						return json_encode($data);
					
				}
				else
				{
					$query="SELECT * FROM solicitudes_ret WHERE (proceso LIKE '$proceso%' AND exp LIKE '-1')";
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
								$proceso=$result->fields['proceso'];						
								$cedula=$result->fields['cedula'];
								$numero_sol=$result->fields['numero_soli'];
								$exp=$result->fields['exp'];
								$razon = $result->fields['razon'];
								if($exp == '-1')
								{
									$aval = 'No avalado';
								}
								else
								{
									$aval = 'Avalado';
								}
								//$aval2 = base64_encode($aval);
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
											"aval"=> $aval,
											"razon"=> $razon,
											"link"=>$link);
							$j++;
						} 
						header('Content-type: application/json');
						return json_encode($data);
					}
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


}

?>
