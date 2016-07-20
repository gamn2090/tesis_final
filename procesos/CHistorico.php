<?php
	/*include('funciones.php');	
	include('CRetiros.php');	
 	include('CReingresos.php');	
 	include('CCDE.php');	
 	include('CRazon_proceso')
  	//include('CHistorico.php');
  	include('CUsers.php')	
  	include('CSolicitudes.php')


 	$objRetiros = new Retiro();
 	//$objHistorico = new Historico();
 	$objReingresos = new Reingreso();
 	$objCDE = new CDE();
 	$objRazon = new Razon_proceso();
 	$objSolicitudes = new Solicitudes();
 	$objUsers = new User();*/

class Historico{
	
	var $conn;

function Historico()
{
	
	include('../config_objeto.php');
	
}

/*============================================================================================================================
 								FUNCION PARA INGRESAR EN LA BASE DE DATOS HISTORICA  	
 ======================================================================================================================*/

function ingresar_historico($exp,$cedula,$fecha_solicitud,$razon,$promedio,$solicitudes,$solicitud_actual,$aval,$fecha,$medidas,$decision,$observaciones,$acuerdo)
{	

	$consulta="SELECT per,ano FROM periodo WHERE '".$fecha_solicitud."' BETWEEN f_inicio AND f_final";
	$result24=$this->conn->Execute($consulta);
							
		$per=$result24->fields['per'];	
		$ano=$result24->fields['ano'];				

															
																
			
		$semestre=$per."-".$ano;

	$anio=$this->obtener_anio($fecha_solicitud);
	if($acuerdo=='No')
	{
		if($decision=="Aprobada")
		{
			$decision="Reprobada";	
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

	$query= "INSERT INTO decisiones (cedula, fecha_solicitud, razon, promedio, solicitudes, solicitud_actual, aval, medidas, tiempo_sol, decision, observaciones, acuerdo, exp, semestre) VALUES ('$cedula', '$fecha_solicitud', '$razon', '$promedio', '$solicitudes', '$solicitud_actual', '$aval', '$medidas', '$fecha', '$decision', '$observaciones', '$acuerdo', '$aval1', '$semestre')"; 
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

/*===========================================================================================================================
 						FUNCION PARA VISUALIZAR ANTIGUAS DECISIONES	
============================================================================================================================*/

function estudiar_decision($fecha,$cedula,$solicitud)
{

	$query2="SELECT * FROM estudiante WHERE ced LIKE '%$cedula%'";
	$result=$this->conn->Execute($query2);
	if($result==false)
	{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;}
	else
	{	
		while(!$result->EOF) 
		{			
			for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
			{
				$nombre=$result->fields[3];
				$apellido=$result->fields[2];				
			
			}								
			$result->MoveNext();
		}
	}

	$query="SELECT * FROM decisiones WHERE (cedula LIKE '%$cedula%' AND fecha_solicitud LIKE '%$fecha%' AND solicitud_actual LIKE '%$solicitud%')";
	$result=$this->conn->Execute($query);
	if($result==false)
	{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;}
	else
	{	
		while(!$result->EOF) 
		{			
			for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
			{
				$tiempo_soli=$result->fields[8];
				$solicitud_actual=$result->fields[5];
				$razon=$result->fields[2];
				$solicitudes_anteriores=$result->fields[4];	
				$pros_aca=$result->fields[3];
				$medidas_resul=$result->fields[7];		
				$aval=$result->fields[6];
				$decision=$result->fields[9];
				$observaciones=$result->fields[10];
				$acuerdo=$result->fields[11];
			}								
			$result->MoveNext();
		}
	}
	
?>		
		<form class="col s12" id="decision" method="POST">           	        
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $nombre." ".$apellido ; ?>">
                            <label>El estudiante: </label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input disabled type="text" class="validate" value="<?php echo $cedula?>">
                            <label >Portador de la cedula: </label>
                        </div>
                    </div>
                   	<div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $solicitud_actual ?>">
                            <label >Quien ha solicitado un: </label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $razon ?>">
                            <label>A razon de: </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input  type="text" class="validate" disabled value="<?php echo $tiempo_soli ?>">                            
                        </div>
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $solicitudes_anteriores; ?>">                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input disabled type="text" class="validate" value="<?php echo $pros_aca; ?>">                            
                        </div>
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $medidas_resul; ?>">                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $aval ?>">
                            <label>Y </label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input disabled type="text" class="validate" value="<?php echo $decision ?>">
                            <label >Por lo tanto, mediante la aplicación del maximo valor esperado, su solicitud esta: </label>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input disabled type="text" class="validate" value="<?php echo $acuerdo ?>">
                            <label >¿El coordinador estuvo de acuerdo?: </label>
                        </div>                                             
                        <div class="input-field col s12 m6">
				          <textarea id="observaciones" disabled name="observaciones" class="materialize-textarea"><?php echo $observaciones ?></textarea>
				          <label for="observaciones">Observaciones</label>
				        </div>
				    </div>  
				    <div class="col m12 offset">
                            <p class="center-align">
                                <button class="btn btn-large waves-effect waves-light" id="cerrar" onClick="myFunction()" >Aceptar</button>
                            </p>
                    </div>                                    
        </form> 
		<script>
            function myFunction() {
                window.close();
            }
        </script>
<?php
$this->conn->Close();
}

/*===================================================================================================================================================
											FUNCION PARA MOSTRAR LAS SOLICITUDES ANTERIORES HECHAS
=====================================================================================================================================================*/
function visualizar_antecedentes($razon,$promedio,$solicitudes,$solicitud_actual,$aval,$tiempo_sol,$medidas)
{	

			$tiempo_sol=substr($tiempo_sol,0,20);
			$solicitudes=substr($solicitudes,32);
			$query="SELECT * FROM decisiones WHERE (tiempo_sol LIKE '$tiempo_sol%' AND aval LIKE '%$aval%' AND razon LIKE '%$razon%' AND solicitudes LIKE '%$solicitudes' AND medidas LIKE '%$medidas%' AND solicitud_actual LIKE '%$solicitud_actual%' AND promedio LIKE '%$promedio%')";	
			$result=$this->conn->Execute($query);		
			if($result==false)
			{
				echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
			}
			else
			{	$j=0;
				while(!$result->EOF) 
				{			
					for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
					{
							$cedula=$result->fields[0];
							$fecha=$result->fields[1];					
							$solicitud=$result->fields[5];
							$razon=$result->fields[2];	
							$resultado=$result->fields[9];
							$link="<a href=\"estudiar_historico.php?id=".$cedula."&soli=".$solicitud."&fecha=".$fecha."\" target='_blank'>Evaluar</a>";							
							$result->MoveNext();											
							break;	
					}					
					$data[$j]=array("cedula"=>$cedula,
									"solicitud"=> $solicitud,
									"fecha"=>$fecha,
									"razon"=> $razon,
									"resultado"=>$resultado,
									"link"=>$link);
					$j++;									
				}
				header('Content-type: application/json');
				return json_encode($data);
			
			}
				$this->conn->Close();			
}

/*====================================================================================================================================================
												FUNCIÓN PARA TOMAR DECISIONES DE REINGRESO
=====================================================================================================================================================*/

function decision_rein($fecha,$grupo,$cedula,$razon,$nombre,$apellido,$discapacidad,$promedio,$solicitudes,$solicitud_actual,$aval,$cant_soli,$periodo  )
{
	$query="SELECT puntaje, (porcentaje/100) ponderacion FROM razon_proceso WHERE (proceso LIKE '%$solicitud_actual%') AND (razon LIKE '%$razon%')";
	$result=$this->conn->Execute($query);
	
		$fecha_solicitud=$fecha;
		
				$puntaje=$result->fields['puntaje'];
				$porcentaje=$result->fields['ponderacion'];
			
		$aprobado_temp=0;
		$reprobado_temp=0;
		
		//Aquí comienza la fiesta xD CONDICIONALES
		//=========================================================aval============================================================
		if($aval=='Si')
		{
			$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje);
			$razon_aval="Presentó aval por la razon";	
		}
		else
		{
			$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje);
			$razon_aval="No pesentó aval por la razon";
		}
		//===========================================================FIN AVAL=====================================================
	
	//============================================tiempo de solicitud=============================================================
		$query="SELECT * FROM consideraciones WHERE proceso LIKE '%$solicitud_actual%' AND consideracion LIKE '%Tiem_solicitud%'";	
		$result2=$this->conn->Execute($query);
		
					$porcentaje_tiempo=$result2->fields['ponderacion']/100;
				
		if($periodo==-1)
		{
			$tiempo_soli="Ingresó el retiro en el periodo estipulado";
			$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_tiempo);
		}
		else
		{
			$tiempo_soli="No ingresó el retiro en el periodo estipulado, lo ingresó ".$periodo." días tarde";
			$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje_tiempo);
			
		}
		//=========================================================FIN TIEMPO SOLICITUD=========================================================
		//=====================================================normas de repitencia===============================================
		
		$medidas=0;
		$query2="SELECT * FROM medidas_academicas WHERE cedula LIKE '%$cedula%'";
		$result=$this->conn->Execute($query2);
		if($result==false)
		{
			$medidas=0;
		}	
		else
		{
						
				$medidas=1;
					
		}
		$query="SELECT (ponderacion/100) ponderacion FROM consideraciones WHERE proceso LIKE '%$solicitud_actual%' AND consideracion LIKE '%Repite%'";	
		$result2=$this->conn->Execute($query);
		
					$porcentaje_medidas=$result2->fields['ponderacion'];
				
		if($medidas==0)
		{
			$medidas_resul="No tiene medidas";
			$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_medidas);
		}
		else
		{
			$medidas_resul="Tiene medidas";
			$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje_medidas);	
		}
		//=================================================FIN NORMAS REPITENCIAS====================================================
		
		//=====================================================Prosecucion académica================================================
		$query="SELECT (ponderacion/100) ponderacion FROM consideraciones WHERE proceso LIKE '%$solicitud_actual%' AND consideracion LIKE '%Prosec%'";	
		$result2=$this->conn->Execute($query);
		
					$porcentaje_prosecucion=$result2->fields['ponderacion'];
				
		if($promedio>5 || $promedio==5)
		{
			$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_prosecucion);
			$pros_aca="Tiene promedio positivo, ".$promedio."/10";
		}
		else
		{
			$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje_prosecucion);
			$pros_aca="Tiene promedio negativo, ".$promedio."/10";
		}
		//=================================================FIN PROSECUCION ACADEMICA=================================================
			
		$query="SELECT COUNT(*) cont FROM notas WHERE ((per = '1') OR (per = '2')) AND cedula = '".$cedula."' ";
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
					$semestres=$result->fields[0];				
				}
				$result->MoveNext();
			}
		}

		$query3="SELECT * FROM consideraciones WHERE proceso LIKE '%$solicitud_actual%' AND consideracion LIKE '%Grupo%'";	
		$result3=$this->conn->Execute($query);
		if($result==false)
		{
			echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
		}
		else
		{
			while(!$result3->EOF)
			{
				for($i=0, $max=$result3->FieldCount(); $i<$max; $i++)
				{
					$porcentaje_grupo=$result3->fields['ponderacion']/100;
				}
				$result3->MoveNext();
			}
		}

	if($grupo =='1')
	{
		$query="SELECT * FROM decisiones WHERE solicitud_actual LIKE '%Cambio%' AND cedula LIKE '%$cedula' AND decision LIKE '%Aprobado%'";	
		$result=$this->conn->Execute($query);		
		if($result==false)
		{
			echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
		}
		else
		{	$j=0;
			while(!$result->EOF) 
			{			
				for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
				{
					$fecha=$result->fields['fecha_solicitud'];					
				}	
				$result->MoveNext();
			}			
		}
		$query="SELECT * FROM periodo WHERE '$fecha' BETWEEN f_inicio AND f_final";	
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
					$periodo=$result->fields['per'];	
					$anio=$result->fields['ano'];					
				}	
				$result->MoveNext();
			}		
		}

		$query="SELECT SUM(to_number(substr((A.asignatura),7,1),'9')) credito from notas as A, esp as B 
		WHERE (A.nota > '4' AND 
		A.cedula = '".$cedula."' AND 
		A.especialidad = B.codigo) AND
		A.ano = '".$anio."' OR A.ano < '".$anio."'";		
		$result2 = $this->conn->Execute($query);
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
					$creditos_apro_antes=$result2->fields[0];						
				}
				$result2->MoveNext();											
				
			}
		}

		$query2="SELECT SUM(to_number(substr((A.asignatura),7,1),'9')) credito from notas as A, esp as B 
		WHERE (A.nota > '4' AND 
		A.cedula = '".$cedula."' AND 
		A.especialidad = B.codigo) AND
		((A.ano > '".$anio."' AND A.per >'".$periodo."') OR (A.ano = '".$anio."' AND A.per >'".$periodo."') OR (A.ano > '".$anio."' AND A.per ='".$periodo."')) ";		
		$result = $this->conn->Execute($query2);
		if($result2==false)
		{
			echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
		}
		else
		{
			while(!$result->EOF) 
			{			
				for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
				{
					$creditos_apro_desp=$result->fields[0];
					$result->MoveNext();											
					break;	
				}
			}
		}	

		$cred_aceptable = ($creditos_apro_antes+($creditos_apro_antes/2) -15 );

		if($creditos_apro_desp > $cred_aceptable || $creditos_apro_desp == $cred_aceptable)		
		{
			$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_grupo);
			$punt_grupo="Pertenece al grupo 1, y después del cambio presentó mejoría en su desempeño";
		}
		else
		{
			$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje_grupo);
			$punt_grupo="Pertenece al grupo 1, y después del cambio no presentó mejoría alguna";
		}

	}
	else
	{
		if($grupo == '3')
		{
			$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_grupo);
			$punt_grupo="Pertenece al grupo 3";
		}
		else
		{
			if($grupo == '4')
			{
				$query="SELECT count(distinct ano) cont FROM notas WHERE per = '1' OR per = '2' AND cedula = '".$cedula."'";
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
							$cont_sem=$result->fields['cont'];
							$result->MoveNext();											
							break;	
						}
					
					}
				}
				$query2="SELECT SUM(to_number(substr((A.asignatura),7,1),'9')) credito, B.credito_titulo total_credito from notas as A, esp as B 
				WHERE (A.nota > '4' AND 
				A.cedula = '".$cedula."' AND 
				A.especialidad = B.codigo) GROUP BY B.credito_titulo";
				$result = $this->conn->Execute($query2);
				if($result2==false)
				{
					echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
				}
				else
				{
					while(!$result->EOF) 
					{			
						for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
						{
							$creditos_apro=$result->fields['credito'];
							$total=$result->fields['total_credito'];
							$result->MoveNext();											
							break;	
						}
					}
				}
				if($cont_sem < 7 && ($creditos_apro > ($total/2)) || ($creditos_apro = ($total/2)) )
				{
					$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_grupo);
					$punt_grupo="Pertenece al grupo 4 y la cantidad de creditos aprobados en su semestre numero ".$cont_sem." es mayor a la mitad del total de su carrera";
				}
				else
				{
					$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje_grupo);
					$punt_grupo="Pertenece al grupo 4 y la cantidad de creditos aprobados en su semestre numero ".$cont_sem." es menor a la mitad del total de su carrera";
				}
			}
			else
			{
				$query="SELECT count(distinct ano) cont FROM notas WHERE per = '1' OR per = '2' AND cedula = '".$cedula."'";
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
							$cont_sem=$result->fields[0];
							$result->MoveNext();											
							break;	
						}
					
					}
				}
				$query2="SELECT SUM(to_number(substr((A.asignatura),7,1),'9')) credito, B.credito_titulo total_credito from notas as A, esp as B 
				WHERE (A.nota > '4' AND 
				A.cedula = '".$cedula."' AND 
				A.especialidad = B.codigo) GROUP BY B.credito_titulo";
				$result = $this->conn->Execute($query2);
				if($result==false)
				{
					echo "error al recuperar:asdasdsa ".$this->conn->ErrorMsg()."<br>" ;
				}
				else
				{
					while(!$result->EOF) 
					{			
						for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
						{
							$creditos_apro=$result->fields['credito'];
							$total=$result->fields['total_credito'];
							$result->MoveNext();											
							break;	
						}
					}
				}
				if($cont_sem < 7 && ($creditos_apro > ($total/2)) || ($creditos_apro = ($total/2)) )
				{
					$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_grupo);
					$punt_grupo="Pertenece al grupo 7, ".$cont_sem." semestres cursados con mas del 50% de creditos aprobados";
				}
				else
				{
					$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje_grupo);
					$punt_grupo="Pertenece al grupo 7 con ".$cont_sem." semestres cursados y menos del 50% de los creditos aprobados";
				}
			}
		}


	}
	
	if($aprobado_temp>$reprobado_temp)
	{
		$decision="Aprobada";	
	}
	else
	{
		if($aprobado_temp<$reprobado_temp)
		{
			$decision="Reprobada";
		}	
		else
		{
			if($aprobado_temp==$reprobado_temp)
			{
				$decision="Indefinida";
			}	
		}
	}

		$query3="SELECT * FROM solicitudes_rein WHERE (cedula LIKE '%$cedula%' AND fecha_solicitud = '%$fecha_solicitud%')";
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
					$exp=$result->fields[5];
				}
				$result->MoveNext();
			}
		}
		/*$solicitud_actual = $aprobado_temp;
		$razon =$reprobado_temp;*/

	$solicitudes_anteriores=$punt_grupo;
	$this->mostrar_decision($exp,$fecha_solicitud,$cedula,$pros_aca,$razon,$solicitud_actual,$decision,$solicitudes_anteriores,$medidas_resul,$tiempo_soli,$razon_aval,$conn);
	$this->conn->Close();
}


/*=========================================================================================================================================================
				DECISION DE RETIRO
=========================================================================================================================================================*/


function DECISION($fecha,$cedula,$razon,$nombre,$apellido,$discapacidad,$promedio,$solicitudes,$solicitud_actual,$aval,$cant_soli,$periodo,$conn)
{	

	$aprobado_temp=0;
	$reprobado_temp=0;

	$query="SELECT * FROM razon_proceso WHERE (proceso LIKE '%".$solicitud_actual."%') AND (razon LIKE '%$razon%')";
	$result=$this->conn->Execute($query);
		
		$fecha_solicitud=$fecha;
		
				$puntaje=$result->fields['puntaje'];
				$porcentaje=$result->fields['porcentaje']/100;
				
			
		//Aquí comienza la fiesta xD CONDICIONALES
		//=========================================================aval============================================================
		if($aval=='Si')
		{
			$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje);
			$razon_aval="Presentó aval por la razon";	
		}
		else
		{
			$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje);
			$razon_aval="No pesentó aval por la razon";
		}
		//===========================================================FIN AVAL=====================================================
		//============================================tiempo de solicitud=============================================================
		$query="SELECT (ponderacion/100) FROM consideraciones WHERE proceso LIKE '%$solicitud_actual%' AND consideracion LIKE '%Ts'";	
		$result2=$this->conn->Execute($query);
		
			$porcentaje_tiempo=$result2->fields[0];
		
		if($periodo==-1)
		{
			$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_tiempo);
			$tiempo_soli="Ingresó el retiro en el periodo estipulado";			
		}
		else
		{
			$reprobado_temp = $reprobado_temp+($puntaje*$porcentaje_tiempo);
			$tiempo_soli="No ingresó el retiro en el periodo estipulado, lo ingresó ".$periodo." días tarde";			
		}
		//=========================================================FIN TIEMPO SOLICITUD=========================================================
		//=====================================================normas de repitencia===============================================
		
		$medidas=0;
		$query2="SELECT * FROM medidas_academicas WHERE cedula LIKE '%$cedula%'";
		$result=$this->conn->Execute($query2);
		if($result==true)
		{	
			$ced=$result->fields['cedula'];	
			if($ced == $cedula)
			{
				$medidas=1;
			}	
		}	
		
		$query="SELECT (ponderacion/100) FROM consideraciones WHERE proceso LIKE '%$solicitud_actual%' AND consideracion LIKE '%Repitencia%'";	
		$result2=$this->conn->Execute($query);
		
					$porcentaje_medidas=$result2->fields[0];
				
		if($medidas==0)
		{	$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_medidas);
			$medidas_resul="No tiene medidas";
			
		}
		else
		{	$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje_medidas);	
			$medidas_resul="Tiene medidas";
			
		}
		//=================================================FIN NORMAS REPITENCIAS====================================================
	
		//==================================================Retiros Anteriores======================================================
		$query="SELECT (ponderacion/100) FROM consideraciones WHERE proceso LIKE '%$solicitud_actual%' AND consideracion LIKE '%Retiros%'";	
		$result2=$this->conn->Execute($query);
		
					$porcentaje_retiros=$result2->fields[0];
				
		if($cant_soli>0 && $cant_soli==1)
		{	
			//se le da el beneficio de la duda
			$aprobado_temp=$aprobado_temp+(($puntaje/2)*$porcentaje_retiros);
			$solicitudes_anteriores="Tiene 1 solicitud anterior, se le da el beneficio de la duda";
		}
		else
		{
			if($cant_soli>1)
			{
				//esta evadiendo responsabilidad y se le castiga por eso
				$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje_retiros);	
				$solicitudes_anteriores="Tiene ".$cant_soli." solicitudes anteriores, se considera evasion de responsabilidad";
			}
			else
			{
				$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_retiros);
				$solicitudes_anteriores="No tiene solicitudes anteriores";	
			}
		}
		
	
	//=====================================================FIN RETIROS ANTERIORES==============================================

	//=====================================================Prosecucion académica================================================
	$query="SELECT (ponderacion/100) FROM consideraciones WHERE proceso LIKE '%$solicitud_actual%' AND consideracion LIKE '%Prosecucion%'";	
	$result2=$this->conn->Execute($query);
	
				$porcentaje_prosecucion=$result2->fields[0];
			
	if($promedio>5 || $promedio==5)
	{
		$aprobado_temp=$aprobado_temp+($puntaje*$porcentaje_prosecucion);
		$pros_aca="Tiene promedio positivo, ".$promedio."/10";
	}
	else
	{
		$reprobado_temp=$reprobado_temp+($puntaje*$porcentaje_prosecucion);
		$pros_aca="Tiene promedio negativo, ".$promedio."/10";
	}
	//=================================================FIN PROSECUCION ACADEMICA=================================================
	if($aprobado_temp > $reprobado_temp)
	{
		$decision="Aprobada";	
	}
	else
	{
		if($aprobado_temp < $reprobado_temp)
		{
			$decision="Reprobada";
		}	
		else
		{
			if($aprobado_temp == $reprobado_temp)
			{
				$decision="Indefinida";
			}	
		}
	}

	$query3="SELECT * FROM solicitudes_ret WHERE (cedula LIKE '%$cedula%' AND fecha_solicitud = '%$fecha_solicitud%')";
		$result=$this->conn->Execute($query3);
		if($result==false)
		{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
			
		}	
		else
		{
			while(!$result->EOF) 
			{
				for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
				{
					$exp=$result->fields[5];
				}
				$result->MoveNext();
			}
		}

		/*$solicitud_actual = $aprobado_temp;
		$razon =$reprobado_temp;*/

	$this->mostrar_decision($exp,$fecha_solicitud,$cedula,$pros_aca,$razon,$solicitud_actual,$decision,$solicitudes_anteriores,$medidas_resul,$tiempo_soli,$razon_aval);
	$this->conn->Close();
}
/*============================================================================================================================																																								                                            
						FUNCION PARA MOSTRAR LAS DECISIONES REALIZADAS
======================================================================================================================*/

function mostrar_decision($exp,$fecha_solicitud,$cedula,$pros_aca,$razon,$solicitud_actual,$decision,$solicitudes_anteriores,$medidas_resul,$tiempo_soli,$razon_aval)
{
	$query2="SELECT * FROM estudiante WHERE ced LIKE '%$cedula%'";
	$result=$this->conn->Execute($query2);
	if($result==false)
	{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;}
	else
	{	
		while(!$result->EOF) 
		{			
			for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
			{
				$cedula=$result->fields[1];
				$nombre=$result->fields[3];
				$apellido=$result->fields[2];				
			
			}								
			$result->MoveNext();
		}
	}
	
?>		
	<form class="col s12" id="decision" action="../procesos/motor_funciones.php" method="POST">
           	        <input type="hidden" id="fecha_solicitud" name="fecha_solicitud" value="<?php echo $fecha_solicitud; ?>">
        			<input type="hidden" id="nombre" name="nombre"  value="<?php echo $nombre; ?>">
					<input type="hidden" id="exp" name="exp"  value="<?php echo $exp; ?>">
			        <input type="hidden" id="cedula" name="cedula"  value="<?php echo $cedula; ?>">
			        <input type="hidden" id="solicitud" name="solicitud" value="<?php echo $solicitud_actual ?>">
			        <input type="hidden" id="razon" name="razon" value="<?php echo $razon ?>">
			        <input type="hidden" id="fecha" name="fecha" value="<?php echo $tiempo_soli ?>">
			        <input type="hidden" id="soli_ant" name="soli_ant"  value="<?php echo $solicitudes_anteriores; ?>">
			        <input type="hidden" id="promedio" name="promedio"  value="<?php echo $pros_aca; ?>">
			        <input type="hidden" id="medidas" name="medidas"  value="<?php echo $medidas_resul; ?>">
			        <input type="hidden" id="aval" name="aval" value="<?php echo $razon_aval ?>"> 
			        <input type="hidden" id="decision" name="decision" value="<?php echo $decision ?>">

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $nombre." ".$apellido ; ?>">
                            <label>El estudiante: </label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input disabled type="text" class="validate" value="<?php echo $cedula?>">
                            <label >Portador de la cedula: </label>
                        </div>
                     </div>
                     <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $solicitud_actual ?>">
                            <label >Quien ha solicitado un: </label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $razon ?>">
                            <label>A razon de: </label>
                        </div>
                     </div>
                     <div class="row">
                        <div class="input-field col s12 m6">
                            <input  type="text" class="validate" disabled value="<?php echo $tiempo_soli ?>">                            
                        </div>                       
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $solicitudes_anteriores; ?>">                            
                        </div>                       
                     </div>
                     <div class="row">
                        <div class="input-field col s12 m6">
                            <input disabled type="text" class="validate" value="<?php echo $pros_aca; ?>">                            
                        </div>
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $medidas_resul; ?>">                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" class="validate" disabled value="<?php echo $razon_aval ?>">
                            <label>Y </label>
                        </div>
                        <div class="input-field col s12 m6 ">
                            <input disabled type="text" class="validate" value="<?php echo $decision ?>">
                            <label >Por lo tanto, mediante la aplicación del maximo valor esperado, su solicitud esta: </label>
                        </div>
                    </div>
                    <div class="row">
                         <?php
							If($decision=="Aprobada" || $decision=="Reprobada")
							{
							?>  							        
							    <div class="input-field col s12 m6">             
		                          <select class="browser-default" id="acuerdo" name="acuerdo">
		                            <option value="" disabled selected>¿Está de acuerdo con la decisión?</option>
		                            <option value="Si">Si</option>
		                            <option value="No">No</option>                            
		                          </select>
		                		</div>
							<?php
							}
							else
							{
									If($decision=='Indefinida')
									{
							?>   
								<div class="input-field col s12 m6"> 
							        <select class="browser-default" id="decision" name="decision">
							        <option value="" disabled selected>Ya que el sistema no pudo decidir, tome la decision usted </option>>
							        <option value="Aprobado">Aprobado</option>
							        <option value="Reprobado">Reprobado</option>
							        </select> 
							    </div>

							<?php
							}
							}
							?> 
                    
				        <div class="input-field col s12 m6 ">
				          <textarea id="observaciones" name="observaciones" class="materialize-textarea"></textarea>
				          <label for="observaciones">Observaciones</label>
				        </div>
				      </div>
                   </div>    
                    <div class="divider"></div>
                    <div class="row">                       
                        <div class="col m12">
                            <p class="center-align">
                                <button class="btn btn-large waves-effect waves-light" id="crear" type="submit" value="ingresar_historico" name="accion" title="login">DECISION</button>
                            </p>
                        </div>
                        
                    </div>
        </form> 
	
		<form class="col s12" id="decision" action="../llamadas/antecedentes.php" method="POST" target="_blank">
           	        <input type="hidden" id="fecha_solicitud" name="fecha_solicitud" value="<?php echo $fecha_solicitud; ?>">
        			<input type="hidden" id="nombre" name="nombre"  value="<?php echo $nombre; ?>">
					<input type="hidden" id="exp" name="exp"  value="<?php echo $exp; ?>">
			        <input type="hidden" id="cedula" name="cedula"  value="<?php echo $cedula; ?>">
			        <input type="hidden" id="solicitud" name="solicitud" value="<?php echo $solicitud_actual ?>">
			        <input type="hidden" id="razon" name="razon" value="<?php echo $razon ?>">
			        <input type="hidden" id="fecha" name="fecha" value="<?php echo $tiempo_soli ?>">
			        <input type="hidden" id="soli_ant" name="soli_ant"  value="<?php echo $solicitudes_anteriores; ?>">
			        <input type="hidden" id="promedio" name="promedio"  value="<?php echo $pros_aca; ?>">
			        <input type="hidden" id="medidas" name="medidas"  value="<?php echo $medidas_resul; ?>">
			        <input type="hidden" id="aval" name="aval" value="<?php echo $razon_aval ?>"> 
			        <input type="hidden" id="decision" name="decision" value="<?php echo $decision ?>">
			        <div class="row">
				     	<div class="col m12">
	                            <p class="center-align">
	                                <button class="btn btn-large waves-effect waves-light" id="crear" type="submit" value="mostrar_valores_iguales" name="accion"  title="login">ANTECEDENTES</button>
	                            </p>
	                    </div>
	                </div>
        </form> 
<?php
$this->conn->Close();
}

/*====================================================================================================================================
									 FUNCION PARA VISUALIZAR LA BASE DE DATOS HISTORICA  	
====================================================================================================================================*/

function visualizar_historico($nivel)
{
			$query="SELECT * FROM decisiones";	
			$result=$this->conn->Execute($query);		
			if($result==false)
			{
				echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
			}
			else
			{	$j=0;
				while(!$result->EOF) 
				{			
					for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
					{
							$cedula=$result->fields[0];
							$semestre=$result->fields['semestre'];	
							$solicitud=$result->fields[5];
							$razon=$result->fields[2];	
							$resultado=$result->fields[9];

							$link="<a href=\"estudiar_historico.php?id=".$cedula."&soli=".$solicitud."&fecha=".$fecha."\" target='_blank'>Evaluar</a>";							
							$result->MoveNext();											
							break;	
					}
					
					if($nivel==sha1(md5("OverNineThousand")))
					{
						$data[$j]=array("cedula"=>$cedula,
										"solicitud"=> $solicitud,
										"semestre"=>$semestre,
										"razon"=> $razon,
										"resultado"=>$resultado,
										"link"=>$link);
					$j++;
					}
					else
					{
						$data[$j]=array("cedula"=>$cedula,
										"solicitud"=> $solicitud,
										"semestre"=>$semestre,
										"razon"=> $razon,
										"resultado"=>$resultado);
									
					$j++;
					}					
				}
				header('Content-type: application/json');
				return json_encode($data);
			
			}
				$this->conn->Close();			
}


/*============================================================================================================================
					FUNCION PARA MOSTRAR LOS DATOS DEL ESTUDIANTE PARA VER SI SON CORRECTOS PARA LA SECRETARIA
==============================================================================================================================*/


function mostrar_datos_estudiante_sec($grupo,$numero_soli,$cedula,$nombre,$apellido,$razon,$promedio,$discapacidad,$nacionalidad,$solicitudes,$proceso,$cant_soli,$fecha)
{


	$anio=$this->obtener_anio($fecha);
?>
        
	 <form id="Evaluar_estudiante" class="col s12" action="guardar.php" method="POST">
	 					<!--                    todos los datos hidden                       -->

                        	<input type="hidden" id="anio" name="anio"  value="<?php echo $anio; ?>">
                        	<input type="hidden" id="numero_soli" name="numero_soli"  value="<?php echo $numero_soli; ?>">
                        	<input type="hidden" id="Nombre" name="Nombre"  value="<?php echo $nombre; ?>">
                   	        <input type="hidden" id="Apellido" name="Apellido"  value="<?php echo $apellido; ?>">
        					<input type="hidden" id="cedula" name="cedula"  value="<?php echo $cedula; ?>">
					        <input type="hidden" id="fecha" name="fecha"  value="<?php echo $fecha; ?>">
					        <input type="hidden" id="razon" name="razon"  value="<?php echo $razon; ?>">
			                <input type="hidden" id="Promedio" name="Promedio" value="<?php echo $promedio; ?>">
        					<input type="hidden" id="discapacidad" name="discapacidad"value="<?php echo $discapacidad; ?>">
        					<input type="hidden" id="nacionalidad" name="nacionalidad" value="<?php echo $nacionalidad; ?>">
					        <input type="hidden" id="solicitudes" name="solicitudes" value="<?php echo $solicitudes; ?>">
					        <input type="hidden" id="cant_soli" name="cant_soli"  value="<?php echo $cant_soli; ?>">
					        <input type="hidden" id="Sol_actual" name="Sol_actual"  value="<?php echo $proceso; ?>">
					        <input type="hidden" id="grupo" name="grupo"  value="<?php echo $grupo; ?>">


					    <!--                    todos los datos hidden                       -->

                    <div class="row">
                        <div class="input-field col s6">
                            <input id="Nombre" type="text" disabled class="validate"  value="<?php echo $nombre; ?>">
                            <label for="nombre">Nombre</label>
                        </div>
                        <div class="input-field col s6 ">
                        	<input id="apellido"  type="text" class="validate"  disabled value="<?php echo $apellido; ?>">
                            <label for="apellido">Apellido</label>                            
                        </div>
                    </div> 
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="cedula" type="text" class="validate" disabled value="<?php echo $cedula; ?>">
                            <label for="cedula">Cédula</label>
                        </div>   
                        <div class="input-field col s6">             
                          	<input id="Promedio" type="text" class="validate" disabled value="<?php echo $promedio; ?>">
                            <label for="Promedio">Promedio</label>
                        </div>   
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="fecha"  type="text" class="validate" disabled value="<?php echo $fecha; ?>">
                            <label for="fecha">Fecha de Solicitud</label>
                        </div>
                   <?php						
						if($proceso == 'Retiro')
						{
						?>

                        <div class="input-field col s6 ">
                            <input id="razon" type="text" class="validate" disabled value="<?php echo $razon; ?>">
                            <label for="razon">Razon</label>
                        </div>
                   <?php
						}
						?>	
                    </div>                
	                <div class="row">
	                	
	                	
	                	 <div class="input-field col s6">
						    <select id="Aval" name="aval">
						      <option value="" disabled selected>¿Presentó soportes?</option>
						      <option value="Si">Si</option>
						      <option value="No">No</option>
						    </select>
						 </div>	
						<?php						
						if($proceso == 'Reingreso')
						{
						?>
							<div class="input-field col s6">
						    <select id="cambio" name="cambio">
						      <option value="" disabled selected>¿Tiene cambios de especialidad?</option>
						      <option value="Si">Si</option>
						      <option value="No">No</option>
						    </select>
						 </div>	
						<?php
						}
						?>	                
		                <div class="input-field col s6 ">
                            <input id="discapacidad"  type="text" class="validate" disabled value="<?php echo $discapacidad; ?>">
                            <label for="discapacidad">¿Tiene discapacidad?</label>
                        </div>
	                </div>


	                <div class="row">
                        <div class="input-field col s6">
                            <input id="solicitudes"  type="text" class="validate" disabled value="<?php echo $solicitudes; ?>">
                            <label for="solicitudes">Solicitudes anteriores</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="Sol_actual" type="text" class="validate" disabled value="<?php echo $proceso; ?>">
                            <label for="Sol_actual">Solicitud Actual</label>
                        </div>                    
                    </div> 
                    <div class="row">
                     <?php
					if($solicitudes=="Si tiene")
					{
					?>  
                        <div class="input-field col s6 ">
                            <input id="cant_soli" type="text" class="validate" disabled value="<?php echo $cant_soli; ?>">
                            <label for="cant_soli">Cantidad de solicitudes</label>
                        </div>
                    <?php
                	}
                    ?>                    
                    </div> 
                    <div class="divider"></div>
                    <div class="row">                       
                        <div class="col m12 offset">
                            <p class="center-align">
                                <button class="btn btn-large waves-effect waves-light" id="crear" type="submit" value="Guardar" name="accion" title="login">Guardar</button>
                            </p>
                        </div>
                    </div>
        </form> 
<?php

}

/*============================================================================================================================
					FUNCION PARA MOSTRAR LOS DATOS DEL ESTUDIANTE PARA VER SI SON CORRECTOS PARA EL COORDINADOR
==============================================================================================================================*/

function mostrar_datos_estudiante_coord($grupo,$aval,$cedula,$nombre,$apellido,$razon,$promedio,$discapacidad,$nacionalidad,$solicitudes,$proceso,$cant_soli,$fecha)
{
	$anio=$this->obtener_anio($fecha);
?>
        
	 <form id="Evaluar_estudiante" class="col s12" action="resultado.php" method="POST">
	 					<!--                    todos los datos hidden                       -->

                        	<input type="hidden" id="anio" name="anio"  value="<?php echo $anio; ?>">
                        	<input type="hidden" id="Nombre" name="Nombre"  value="<?php echo $nombre; ?>">
                   	        <input type="hidden" id="Apellido" name="Apellido"  value="<?php echo $apellido; ?>">
        					<input type="hidden" id="cedula" name="cedula"  value="<?php echo $cedula; ?>">
					        <input type="hidden" id="fecha" name="fecha"  value="<?php echo $fecha; ?>">
					        <input type="hidden" id="razon" name="razon"  value="<?php echo $razon; ?>">
					        <input type="hidden" id="aval" name="aval"  value="<?php echo $aval; ?>">
			                <input type="hidden" id="Promedio" name="Promedio" value="<?php echo $promedio; ?>">
        					<input type="hidden" id="discapacidad" name="discapacidad"value="<?php echo $discapacidad; ?>">
        					<input type="hidden" id="nacionalidad" name="nacionalidad" value="<?php echo $nacionalidad; ?>">
					        <input type="hidden" id="solicitudes" name="solicitudes" value="<?php echo $solicitudes; ?>">
					        <input type="hidden" id="cant_soli" name="cant_soli"  value="<?php echo $cant_soli; ?>">
					        <input type="hidden" id="Sol_actual" name="Sol_actual"  value="<?php echo $proceso; ?>">
					        <input type="hidden" id="grupo" name="grupo"  value="<?php echo $grupo; ?>">
					    <!--                    todos los datos hidden                       -->

                    <div class="row">
                        <div class="input-field col s6">
                            <input id="Nombre" type="text" disabled class="validate"  value="<?php echo $nombre; ?>">
                            <label for="nombre">Nombre</label>
                        </div>
                        <div class="input-field col s6 ">
                        	<input id="apellido"  type="text" class="validate"  disabled value="<?php echo $apellido; ?>">
                            <label for="apellido">Apellido</label>                            
                        </div>
                    </div> 
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="cedula" type="text" class="validate" disabled value="<?php echo $cedula; ?>">
                            <label for="cedula">Cédula</label>
                        </div>   
                        <div class="input-field col s6">             
                          	<input id="Promedio" type="text" class="validate" disabled value="<?php echo $promedio; ?>">
                            <label for="Promedio">Promedio</label>
                        </div>   
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="fecha"  type="text" class="validate" disabled value="<?php echo "nada"; ?>">
                            <label for="fecha">Fecha de Solicitud</label>
                        </div>
                       
                        <div class="input-field col s6 ">
                            <input id="razon" type="text" class="validate" disabled value="<?php echo $razon; ?>">
                            <label for="razon">Razon</label>
                        </div>
                        
                    </div>                
	                <div class="row">
		                <div class="input-field col s6 ">
                            <input id="discapacidad"  type="text" class="validate" disabled value="<?php echo $discapacidad; ?>">
                            <label for="discapacidad">¿Tiene discapacidad?</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="Sol_actual" type="text" class="validate" disabled value="<?php echo $proceso; ?>">
                            <label for="Sol_actual">Solicitud Actual</label>
                        </div>                    
                    </div> 
                    <div class="row">
                    	<div class="input-field col s6">
                            <input id="solicitudes"  type="text" class="validate" disabled value="<?php echo $solicitudes; ?>">
                            <label for="solicitudes">Solicitudes anteriores</label>
                        </div>
                     <?php
					if($solicitudes=="Si tiene")
					{
					?>  
                        <div class="input-field col s6 ">
                            <input id="cant_soli" type="text" class="validate" disabled value="<?php echo $cant_soli; ?>">
                            <label for="cant_soli">Cantidad de solicitudes</label>
                        </div>
                    <?php
                	}
                	if($proceso=="Reingreso")
					{
					?>  
                        <div class="input-field col s6 ">
                            <input id="grupo" type="text" class="validate" disabled value="<?php echo $grupo; ?>">
                            <label for="grupo">Grupo</label>
                        </div>
                    <?php
                	}
                    ?>                    
                    </div> 
                    <div class="divider"></div>
                    <div class="row">                       
                        <div class="col m12 offset">
                            <p class="center-align">
                                <button class="btn btn-large waves-effect waves-light" id="crear" type="submit" value="Evaluar" name="accion" title="login">Evaluar</button>
                            </p>
                        </div>
                    </div>
        </form> 
<?php

}

/*============================================================================================================================
					FUNCION PARA VER SI TIENE O NO SOLICITUDES EN LA BD 
============================================================================================================================*/

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
					FUNCION PARA VER LA CANTIDAD DE SOLICITUDES REALIZADAS POR UN ESTUDIANTE EN LA BD 
=============================================================================================================================*/

function cantidad_solicitud_historico($cedula,$proceso,$conn)
{	$cont=0;
	$query="SELECT * FROM decisiones WHERE ((cedula LIKE '%$cedula%') AND (solicitud_actual LIKE '%$proceso%'))";	
	$result=$this->conn->Execute($query);
	if($result==false)
	{
		echo "error al recuperar 3: ".$conn->ErrorMsg()."<br>" ;
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
	$conn->Close();
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

/*============================================================================================================================
					FUNCION DE COMPARACION DE FECHA
==============================================================================================================================*/
function valida_fecha($inicio, $fin, $validar) 
{ 
    if(strtotime($validar)>=strtotime($inicio) && strtotime($validar)<=strtotime($fin)) 
		return true; 
    else 
		return false; 
} 


}