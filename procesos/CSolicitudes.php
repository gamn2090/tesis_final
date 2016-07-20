<?php

	/*include('funciones.php');	
	include('CRetiros.php');	
 	include('CReingresos.php');	
 	include('CCDE.php');	
 	include('CRazon_proceso')
  	include('CHistorico.php');/*
  	include('CUsers.php')	
  	//include('CSolicitudes.php')


 	$objRetiros = new Retiro();
 	/*
 	$objReingresos = new Reingreso();
 	$objCDE = new CDE();
 	$objRazon = new Razon_proceso();
 	//$objSolicitudes = new Solicitudes();
 	$objUsers = new User();*/

class Solicitudes{
	
	var $conn;

function Solicitudes()
{
	
	include('../config_objeto.php');
	
}

/*===================================================================================================================================
					FUNCION PARA ALMACENAR LOS RETIROS/REINGRESOS/CAAMBIOS REALIZADOS POR LOS ESTUDIANTES
====================================================================================================================================*/

function ingresar_solicitud($cedula,$proceso,$fecha,$razon,$periodo,$anio,$especialidad,$nucleo,$estatus,$asignatura)
{
	if($proceso=='Retiro')
	{	
		$query= "INSERT INTO solicitudes (cedula, nucleo, especialidad, fecha, anio, periodo, t_solicitud, recaudos, estatus, observacion) 
				VALUES ('$cedula', '$nucleo', '$especialidad', '$fecha', '$anio', '$periodo', '$proceso', 'NO', '$estatus', '$razon' )";
	}
	else
	{
		if($proceso=='Reingreso')
		{	
			$query= "INSERT INTO solicitudes (cedula, nucleo, especialidad, fecha, anio, periodo, t_solicitud, recaudos, estatus, observacion) 
					VALUES ('$cedula', '$nucleo', '$especialidad', '$fecha', '$anio', '$periodo', '$proceso', 'NO', '$estatus', 'ninguna' )";
		}
		else
		{
			if($proceso=='Cambio')
			{	
				$query= "INSERT INTO solicitudes (cedula, nucleo, especialidad, fecha, anio, periodo, t_solicitud, recaudos, estatus, observacion) 
						VALUES ('$cedula', '$nucleo', '$especialidad', '$fecha', '$anio', '$periodo', '$proceso', 'NO', '$estatus', '$asignatura' )";
			}	
		}
	}
	$result=$this->conn->Execute($query);
	if($result==false)
	{$bandera=1;}
	else
	{$bandera=0;}
	return $bandera;	
	$this->conn->Close();
}

/*===================================================================================================================================
								FUNCION PARA LA CARGA DE SOLICITUDES DE LA TABLA SOLICITUD A LAS DE LA TESIS
===================================================================================================================================*/

function cargar_solicitudes($proceso)
{
	switch($proceso){
	case 'Retiro':
		$query="SELECT a.*
				FROM solicitudes a
				WHERE not exists ( select 1
								   from solicitudes_ret b, decisiones c
								   where b.cedula = a.cedula
								   and (CAST (b.fecha_solicitud  AS DATE)) =  a.fecha)
				and not exists   ( select 1
								   from decisiones c
								   where c.cedula = a.cedula
								   and (CAST (c.fecha_solicitud  AS DATE)) =  a.fecha)
				and a.t_solicitud = '".$proceso."' 
				and a.estatus	  = 'aprobado'";
	break;
	case 'Reingreso':
		$query="SELECT a.*
				FROM solicitudes a
				WHERE not exists ( select 1
								   from solicitudes_ret b, decisiones c
								   where b.cedula = a.cedula
								   and (CAST (b.fecha_solicitud  AS DATE)) =  a.fecha)
				and not exists   ( select 1
								   from decisiones c
								   where c.cedula = a.cedula
								   and (CAST (c.fecha_solicitud  AS DATE)) =  a.fecha)
				and a.t_solicitud = '".$proceso."' 
				and a.estatus	  = 'aprobado'";
	break;
	case 'Cambio':
		$query="SELECT a.*
				FROM solicitudes a
				WHERE not exists ( select 1
								   from solicitudes_ret b, decisiones c
								   where b.cedula = a.cedula
								   and (CAST (b.fecha_solicitud  AS DATE)) =  a.fecha)
				and not exists   ( select 1
								   from decisiones c
								   where c.cedula = a.cedula
								   and (CAST (c.fecha_solicitud  AS DATE)) =  a.fecha)
				and a.t_solicitud = '".$proceso."' 
				and a.estatus	  = 'aprobado'";		
	break;
	}
	$result  = $this->conn->Execute($query);
	if($result==false)
	{
		echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;
	}
	else
	{	$cont=0;
		if($proceso=='Retiro')
		{   
			while(!$result->EOF) 
				{	$cont++;		
					for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
					{				
						$cedula=$result->fields['cedula'];
						$razon=$result->fields['observacion'];
						$fecha_sol=$result->fields['fecha'];
						//$exp='-1';
						$proceso='Retiro';
						$result->MoveNext();											
						break;	
					}
					$objRetiro->ingresar_solicitud_ret($cedula,$proceso,$fecha_sol,$razon);
				}					
		}
		if($proceso=='Reingreso')
		{	$cont=0;
				while(!$result->EOF) 
				 {	$cont++;	
					for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
					{		
						$cedula=$result->fields['cedula'];
						$fecha_sol=$result->fields['fecha'];
						//$exp='-1';
						$proceso='Reingreso';
						$result->MoveNext();											
						break;	
					}
					$objReingreso->ingresar_solicitud_rein($cedula,$proceso,$fecha_sol);
				}				
		}			
		if($proceso=='Cambio')
		{	$cont=0;
				while(!$result->EOF) 
				{	$cont++;			
					for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
					{
						$cedula=$result->fields['cedula'];
						$carrera_pedi=$result->fields['observacion'];
						$fecha_sol=$result->fields['fecha'];
						//$exp='-1';
						$result->MoveNext();											
						break;	
					}
					$carrera_act=buscar_carrera($cedula);	
					$objCDE->ingresar_solicitud_cde($cedula,$fecha_sol,$carrera_act,$carrera_pedi);						
				}				
		}	
			
	}
	return $cont;
	$this->conn->Close();
}

/*============================================================================================================================
					FUNCION PARA SABER SI EL ALUMNO TIENE O NO DOCUMENTOS CONSIGNADOS
==============================================================================================================================*/

/*============================================================================================================================
					FUNCION PARA SABER SI EL ALUMNO TIENE O NO DOCUMENTOS CONSIGNADOS
==============================================================================================================================*/

function validar_solicitud($numero_soli,$anio,$fecha,$cedula,$razon,$solicitud_actual,$aval)
{	
	include('CHistorico.php');
	$objHistorico = new Historico();

	if($solicitud_actual=='Retiro')
	{
		$query="SELECT COUNT (exp) AS total_rt FROM solicitudes_ret WHERE (exp LIKE 'RT-%')";
				$result=$this->conn->Execute($query);
	}
	else
	{
		if($solicitud_actual=='Reingreso')
		{
			$query="SELECT COUNT (exp) AS total_rt FROM solicitudes_rein WHERE (exp LIKE 'RCC-%')";
			$result=$this->conn->Execute($query);
		}
		else
		{
			if($solicitud_actual=='Reingreso_tg')
			{
				$query="SELECT COUNT (exp) AS total_rt FROM solicitudes_rein WHERE (exp LIKE 'RTG-%')";
				$result=$this->conn->Execute($query);
			}
			else
			{
				if($solicitud_actual=='Cambio')
				{
					$query="SELECT COUNT (exp) AS total_rt FROM solicitudes_cde WHERE (exp LIKE 'CE-%')";
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
				$cont1=$result->fields['total_rt'];				
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
				$cont2=$result->fields['total_rt'];				
				$result->MoveNext();											
				break;												
			}			
		}
	} 
	$cont=$cont1+$cont2+1;

	$anio=substr($anio, -2);

	switch ($solicitud_actual)
	{
		case 'Retiro':
				if($aval=="Si")
				{
					$periodo=$objHistorico->saber_periodo($anio,$fecha);
					$aval="RT-".$cont.".".$anio.".".$periodo;
					$query2="UPDATE solicitudes_ret SET exp='$aval' WHERE numero_soli= $numero_soli";
					$result=$this->conn->Execute($query2);
					if($result==false)
					{
						$bandera=0;
					}
					else
					{	
						$bandera=1;
					}
				}
				else
				{
					$aval="0";
					$query2="UPDATE solicitudes_ret SET exp='$aval' WHERE numero_soli= $numero_soli";
					$result=$this->conn->Execute($query2);
					if($result==false)
					{
						$bandera=0;
					}
					else
					{	
						$bandera=1;
					}
				}
		break;		
		case 'Cambio':
				if($aval=="Si")
				{
					$periodo=$objHistorico->saber_periodo($anio,$fecha);
					$aval="CE-".$cont.".".$anio.".".$periodo;
					$query2="UPDATE solicitudes_cde SET exp='$aval' WHERE numero_soli= $numero_soli";
					$result=$this->conn->Execute($query2);
					if($result==false)
					{
						$bandera=0;
					}
					else
					{	
						$bandera=1;
					}	
				}
				else
				{
					$aval="0";		
					$query2="UPDATE solicitudes_cde SET exp='$aval' WHERE numero_soli= $numero_soli";
					$result=$this->conn->Execute($query2);
					if($result==false)
					{
						$bandera=0;
					}
					else
					{	
						$bandera=1;
					}	
				}
		break;

		case 'Reingreso':
				$aval1 = substr($aval, 0,2);
				$cambio = substr($aval, -2);
				$aval= $aval1;
				if($aval=="Si")
				{	if($cambio == 'Si')
					{$cambio=1;}
					else
					{$cambio=0;}
					$grupo=$objReingreso->evaluar_reingreso($cedula, $cambio, $fecha);
					$periodo=$objHistorico->saber_periodo($anio,$fecha);
					$aval="RCC-".$cont.".".$anio.".".$periodo;
					$query2="UPDATE solicitudes_rein SET grupo='$grupo', exp='$aval' WHERE numero_soli= $numero_soli";
					$result=$this->conn->Execute($query2);
					if($result==false)
					{
						$bandera=0;
					}
					else
					{	
						$bandera=1;
					}	
				}
				else
				{
					$aval="0";
					if($cambio == 'Si')
					{$cambio=1;}
					else
					{$cambio=0;}
					$grupo=$objReingreso->evaluar_reingreso($cedula, $cambio, $fecha);
					$periodo=$objHistorico->saber_periodo($anio,$fecha);					
					$query2="UPDATE solicitudes_rein SET grupo='$grupo', exp='$aval' WHERE numero_soli= $numero_soli";
					$result=$this->conn->Execute($query2);
					if($result==false)
					{
						$bandera=0;
					}
					else
					{	
						$bandera=1;
					}	

				}
			break;	
			case 'Reingreso_tg':
				if($aval=="Si")
				{
					$periodo=$objHistorico->saber_periodo($anio,$fecha);
					$aval="RTG-".$cont.".".$anio.".".$periodo;
					$query2="UPDATE solicitudes_rein SET exp='$aval' WHERE numero_soli= $numero_soli";
					$result=$this->conn->Execute($query2);
					if($result==false)
					{
						$bandera=0;
					}
					else
					{	
						$bandera=1;
					}	
				}
				else
				{
					$aval="0";
					$query2="UPDATE solicitudes_rein SET exp='$aval' WHERE numero_soli= $numero_soli";
					$result=$this->conn->Execute($query2);
					if($result==false)
					{
						$bandera=0;
					}
					else
					{	
						$bandera=1;
					}	

				}
			break;			
	}
	return $bandera;

}

/*============================================================================================================================
					FUNCION PARA EL CALCULO DE LOS PARAMETROS DE LOS TOMADORES DE DECISIONES
============================================================================================================================*/

function cargar_datos_estudiante($proceso,$numero_soli,$cedula,$nivel,$exp)
{	
	if($proceso=='Retiro')
	{
		$query="SELECT * FROM solicitudes_ret WHERE ((cedula LIKE '%$cedula%') AND (numero_soli = $numero_soli))";	
		$result=$this->conn->Execute($query);
	}
	else
	{
		if($proceso=='Reingreso')
		{
			$query="SELECT * FROM solicitudes_rein WHERE ((cedula LIKE '%$cedula%') AND (numero_soli = $numero_soli))";	
			$result=$this->conn->Execute($query);
		}
		else
		{
			$query="SELECT * FROM solicitudes_cde WHERE ((cedula LIKE '%$cedula%') AND (numero_soli = $numero_soli))";	
			$result=$this->conn->Execute($query);
		}
	}
	if($result==false)
	{
		echo "error al recuperar 1: ".$conn->ErrorMsg()."<br>" ;
	}
	else
	{	

		include('CHistorico.php');
		$objHistorico = new Historico();

		while (!$result->EOF)
		{
			for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
			{
				$fecha=$result->fields['fecha_solicitud'];
				$razon=$result->fields['razon'];
				if($proceso=='Reingreso')
				{
					$grupo=$result->fields['grupo'];
				}
				//$exp=$result->fields[6];
			}
			$result->MoveNext();
		}
	}

	//SABER SI TIENE AVAL
	if($exp!='-1' && $exp!="0")
	{$aval="Si";}
	else
	{$aval="No";}

	$solicitudes2=$objHistorico->buscar_historico($cedula,$proceso);
	if(($solicitudes2==0))
	{
		$solicitudes="No tiene";	
	}
	else
	{
		$solicitudes="Si tiene";	
	}		
	
	$query="SELECT * FROM estudiante WHERE (ced LIKE '%$cedula%')";	
	$result=$this->conn->Execute($query);
	if($result==false)
	{
		echo "error al recuperar 2: ".$conn->ErrorMsg()."<br>" ;
	}
	else
	{		
		while (!$result->EOF)
		{
			for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
			{
					$cedula=$result->fields[1];
					$nombre=$result->fields[3];
					$apellido=$result->fields[2];					
					$email=$result->fields[22];					
					$direccion=$result->fields[11];
					$promedio=$result->fields[25];
					$discapacidad=$result->fields[27];
					$nacionalidad=$result->fields[17];
					if($nacionalidad=='v')
					$nacionalidad='Venezolano';
					else
					$nacionalidad='Extrangero';
			}	  	
						
			$result->MoveNext();
		}
		if($nombre!=NULL)
		{
			$algo=1;
		}
		else
		{
			$algo=0;
		}
	}
	
	if($algo==1)
	{
		$cant_soli1=$objHistorico->cantidad_solicitud_historico($cedula,$proceso,$conn);
		if($cant_soli1==0)
		{
			$cant_soli=0;	
		}
		else
		{
			$cant_soli=$cant_soli1+$cant_soli2;	
		}
		if($exp!='-1')
		{
			$objHistorico->mostrar_datos_estudiante_coord($grupo,$aval,$cedula,$nombre,$apellido,$razon,$promedio,$discapacidad,$nacionalidad,$solicitudes,$proceso,$cant_soli,$fecha);

		}
		else
		{
			$objHistorico->mostrar_datos_estudiante_sec($grupo,$numero_soli,$cedula,$nombre,$apellido,$razon,$promedio,$discapacidad,$nacionalidad,$solicitudes,$proceso,$cant_soli,$fecha);
		}
	}
	else
	{
		echo "Lo sentimos, el estudiante solicitado no existe en la base de datos de control de estudios";	
	}		
}

/*============================================================================================================================
					FUNCION PARA MOSTRAR LOS DATOS DEL ESTUDIANTE PARA VER SI SON CORRECTOS PARA EL COORDINADOR
==============================================================================================================================*/

function mostrar_datos_estudiante_coord($grupo,$aval,$cedula,$nombre,$apellido,$razon,$promedio,$discapacidad,$nacionalidad,$solicitudes,$proceso,$cant_soli,$fecha)
{	
	include('CRetiros.php');
	$objRetiro = new Retiro();
	$anio=$objRetiro->obtener_anio($fecha);
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
                            <input id="fecha"  type="text" class="validate" disabled value="<?php echo $fecha; ?>">
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
					FUNCION PARA MOSTRAR LOS DATOS DEL ESTUDIANTE PARA VER SI SON CORRECTOS PARA LA SECRETARIA
==============================================================================================================================*/


function mostrar_datos_estudiante_sec($grupo,$numero_soli,$cedula,$nombre,$apellido,$razon,$promedio,$discapacidad,$nacionalidad,$solicitudes,$proceso,$cant_soli,$fecha)
{
	include('CRetiros.php');
	$objRetiro = new Retiro();
	$anio=$objRetiro->obtener_anio($fecha);
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
                   
                        <div class="input-field col s6 ">
                            <input id="razon" type="text" class="validate" disabled value="<?php echo $razon; ?>">
                            <label for="razon">Razon</label>
                        </div>
                   
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
						if($proceso=='Reingreso')
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
					FUNCION PARA CALCULAR LA FECHA DE HOY
==============================================================================================================================*/

function fecha_hoy()
{
	date_default_timezone_set("America/Caracas" ) ; 
	$tiempo = getdate(time()); 
	$dia = $tiempo['wday']; 
	$dia_mes=$tiempo['mday']; 
	$mes = $tiempo['mon']; 
	$year = $tiempo['year']; 
	$fecha=$dia_mes."-".$mes."-".$year;
	
return $fecha;
}


}

?>
