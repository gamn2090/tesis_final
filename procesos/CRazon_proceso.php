<?php

	/*include('funciones.php');	
	include('CRetiros.php');	
 	include('CReingresos.php');	
 	include('CCDE.php');	
 	//include('CRazon_proceso')
  	include('CHistorico.php');
  	include('CUsers.php')	
  	include('CSolicitudes.php')


 	$objRetiros = new Retiro();
 	$objHistorico = new Historico();
 	$objReingresos = new Reingreso();
 	$objCDE = new CDE();
 	//$objRazon = new Razon_proceso();
 	$objSolicitudes = new Solicitudes();
 	$objUsers = new User();*/

class Razon_proceso{
	
	var $conn;

function Razon_proceso()
{
	
	include('../config_objeto.php');
	
}

/*=======================================================================================================================
								FUNCION PARA INGRESAR NUEVAS RAZONES   	
======================================================================================================================*/
function ingresar_razon($proceso,$razon,$puntaje,$fecha)
{
	$query="INSERT INTO razon_proceso (proceso, razon, puntaje, fecha) VALUES ('$proceso', '$razon', '$puntaje', '$fecha')";	
	$result=$this->conn->Execute($query);
	if($result==false)
	{
		$bandera="2";
	}	
	else
	{
		$bandera="3";	
	}
	return($bandera);
	$this->conn->Close();
}

/*============================================================================================================================	
								FUNCION PARA MOSTRAR LOS PUNTAJES DE LAS RAZONES   	
======================================================================================================================*/
function mostrar_puntaje($proceso)
{//$proceso="Retiro";
	if($proceso=="Cambio de Especialidad")
	{$proceso="Cambio";}
	$query="SELECT * FROM razon_proceso WHERE proceso LIKE '%$proceso%'";	
	$result=$this->conn->Execute($query);
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
				$razon=$result->fields['razon'];
				$puntaje=$result->fields['puntaje'];							
				$puntaje2=base64_encode($puntaje);
				$razon2=base64_encode($razon);
				$proceso2=base64_encode($proceso);
				$link="<a href=\"cambio_valores.php?proceso=".$proceso2."&razon=".$razon2."&puntaje=".$puntaje2."\" target='_blank'>Evaluar</a>";						
				$result->MoveNext();											
				break;												
			}
			$data[$j]=array("proceso"=>$proceso,
							"razon"=> $razon,
							"puntaje"=> $puntaje,
							"opcion"=>$link);
			$j++;
		} 
		header('Content-type: application/json');
		return json_encode($data);
	}
	$this->conn->Close();
}
/*============================================================================================================================	
								FUNCION PARA CAMBIAR LOS PUNTAJES DE LAS RAZONES 1 	
======================================================================================================================*/
function cambiar_valor_TDD($proceso,$razon,$puntaje)
{	
	if($proceso=="Cambio de especialidad")
	{$proceso="Cambio";}
	$query="SELECT * FROM razon_proceso WHERE proceso LIKE '%$proceso%' AND razon LIKE '%$razon%'";
	$result=$this->conn->Execute($query);
	if($result==false)
	{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;}
	else
	{	
		include('CSolicitudes.php');
		$objSolicitudes = new Solicitudes();

		
		while(!$result->EOF) 
		{			
			for ($i=0, $max=$result->FieldCount(); $i<$max; $i++)
			{
				$proceso2=$result->fields[0];
				$razon=$result->fields[1];
				$puntaje=$result->fields[2];				
			
			}								
			$result->MoveNext();
		}
	}
	$fecha=$objSolicitudes->fecha_hoy();
?>	

<form class="col s12" id="cambiar_valor_TDD" action="../procesos/motor_funciones.php" method="POST">
           	        <input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha ?>">
	       			<input type="hidden" id="proceso" name="proceso" value="<?php echo $proceso ?>">
	       			<input type="hidden" id="razon" name="razon" value="<?php echo $razon ?>">
                    <div class="row">
                        <div class="input-field col s12 m6 offset-m3">
                            <input id="proceso" type="text" disabled class="validate" value="<?php echo $proceso ?>">
                            <label for="proceso">Proceso</label>
                        </div>
                        <div class="input-field col s12 m6 offset-m3">
                            <input id="usuario" disabled type="text" class="validate" value="<?php echo $razon ?>">
                            <label for="usuario">Razón</label>
                        </div>
                         <div class="input-field col s12 m6 offset-m3">
                            <select class="browser-default" id="puntaje" name="puntaje">
                              <option value="" disabled selected>Elija el Puntaje</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>                              
                            </select>
                         </div>       
                    </div>                
                    <div class="divider"></div>
                    <div class="row">                       
                        <div class="col m12 offset">
                            <p class="center-align">
                                <button class="btn btn-large waves-effect waves-light" id="crear" type="submit" value="actualizar_puntaje" name="accion" title="login">Actualizar</button>
                            </p>
                        </div>
                    </div>
        </form>  

<?php
$this->conn->Close();
}
/*============================================================================================================================	
						FUNCION PARA CAMBIAR LOS PUNTAJES DE LAS RAZONES 2  	
======================================================================================================================*/
function actualizar_puntaje($proceso,$razon,$puntaje,$fecha)
{
	$query="UPDATE razon_proceso SET proceso='$proceso', razon='$razon', puntaje='$puntaje', fecha='$fecha' WHERE ((proceso LIKE '%$proceso%') AND (razon LIKE '%$razon%'))";
	$result=$this->conn->Execute($query);
	if($result==false)
	{
		$bandera=0;}
	else
	{	
		$bandera=1;
	}
	return $bandera;
	$this->conn->Close();
}

}