<?php
	/*include('funciones.php');	
 	include('CRetiros.php');	
 	include('CReingresos.php');	
 	include('CCDE.php');	
 	include('CRazon_proceso')
  	include('CHistorico.php');
  	//include('CUsers.php')	
  	include('CSolicitudes.php')


 	$objRetiros = new Retiro();
 	$objHistorico = new Historico();
 	$objReingresos = new Reingreso();
 	$objCDE = new CDE();
 	$objRazon = new Razon_proceso();
 	$objSolicitudes = new Solicitudes();
 	//$objUsers = new User();*/

class User{
	
	var $conn;

function User()
{	
		$Config_absolute_path   		= 'C:/wamp/www/tesis/';
		include_once($Config_absolute_path.'adodb5/adodb.inc.php');
		$this->conn = ADONewConnection('postgres'); 
		$this->conn->NConnect('host=localhost port=5432 dbname=Control_de_estudios user=postgres password=gamn2090');
		//include('../config_objeto.php');
}
/*============================================================================================================================	
                                FUNCION PARA CREAR UNA CUENTA NUEVA   	
======================================================================================================================*/

function crear_cuenta($cedula,$nombre,$apellido,$sexo,$usuario,$contraseña,$privilegios)
{	
	$privilegios2=$privilegios;
	if($privilegios2==1)
	{
		$privilegios="OverNineThousand";
		$privilegios=sha1(md5($privilegios));	
	}
	else
	{
		$privilegios="Normal";
		$privilegios=sha1(md5($privilegios));
	}
	$contraseña=sha1(md5($contraseña));
	$query="INSERT INTO users (cedula, pass, nivel_priv, nombre, apellido, usuario, sexo) VALUES ('$cedula', '$contraseña', '$privilegios', '$nombre', '$apellido', '$usuario', '$sexo')";	
	$result=$this->conn->Execute($query);
	if($result==false)
	{
		$bandera="0";
	}	
	else
	{
		$bandera="1";	
	}
	return($bandera);
	$this->conn->Close();
}

/*====================================================================================================================================
												FUNCION PARA EL LOGIN DE LOS ESTUDIANTES
====================================================================================================================================*/

function loguear($cedula,$contraseña)
{	//$contraseña=sha1(md5($contraseña));	
	$query="SELECT password FROM login WHERE ((password LIKE '%$contraseña%') AND (cedula LIKE '%$cedula%'))";
	$result=$this->conn->Execute($query);
	if($result==false)
	{echo "error al insertar: ".$conn->ErrorMsg()."<br>" ;}
	else
	{	
		while (!$result->EOF) 
		{ 
			for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
			{	
				if(($cedula==$result->fields[0]))
				{ 
					$id=2; 
				}
				else
				{
					$id=1;
				}
			}			
			$result->MoveNext();							
		}
		session_start();
		$_SESSION['estudiante_ced']=$cedula;
		if($id==2)
			{ 			
				//echo "<br> Bienvenido! " . $nombre. " " . $apellido['apellido'];
				$cedula=base64_encode($cedula);
				header("location: ../llamadas/seleccion_proceso.php"); 
			}
			else
			{		
					$id=1;
					header("location: ../login_estudiante.php?id=$id");
				
			}
		
	}
	$this->conn->Close();
		
}
/*====================================================================================================================================
												FUNCION PARA EL LOGIN DE LA COORDINACIÓN
====================================================================================================================================*/

function loguear_coord($usuario,$contraseña)
{	
	$contraseña=sha1(md5($contraseña));
	//var_dump($contraseña);
	$query="SELECT * FROM users WHERE ((usuario LIKE '%$usuario%') AND (pass LIKE '%$contraseña%'))";
	$result=$this->conn->Execute($query);
	if($result==false)
	{
		echo "error al insertar: ".$this->conn->ErrorMsg()."<br>" ;
	}
	else
	{
		while(!$result->EOF) 
		{ 
			for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
			{	$usuario2=$result->fields[5];
				$nivel=$result->fields[2];
				if($usuario == $result->fields[5])
				{ 
					$id=2; 
					/*aquí hacer lo de la variable de sesion*/
					session_start();
					$_SESSION['nivel']=$nivel;
					$_SESSION['usuario']=$usuario;
					$prueba1="OverNineThousand";
					$prueba1=sha1(md5($prueba1));	
					$_SESSION['bandera']=$prueba1;
								
				}
			}				
			$result->MoveNext();							
		}
		if($id==2)
			{ 							
				header("location: ../coordinacion_principal.php"); 
			}
			else
			{		
				$id=1;
				header("location: ../index.php?id=$id");				
			}
	
	}
	$this->conn->Close();
}

/*======================================================================================================================
									FUNCION PARA CARGAR Y MOSTRAR LOS DATOS DE LOS ESTUDIANTES AL LOGUEAR
======================================================================================================================*/


function cargar_datos($cedula)
{	
	$query="SELECT * FROM estudiante WHERE (ced LIKE '%$cedula%')";	
	$result=$this->conn->Execute($query);
	if($result==false)
	{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;}
	else
	{
		while (!$result->EOF) 
		{
			for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
		  	print $result->estudiante[$i].' ';
			$result->MoveNext();
			
 		} 	
	}
	$this->conn->Close();
}

/*=======================================================================================================================
							FUNCION PARA EL LOGIN DE LOS USUARIOS DE LA COORDINACION AL LOGUEAR
=======================================================================================================================*/


function cargar_datos_coord($usuario)
{	
	$query="SELECT * FROM users WHERE (usuario LIKE '%$usuario%')";	
	$result=$this->conn->Execute($query);
	if($result==false)
	{echo "error al recuperar: ".$this->conn->ErrorMsg()."<br>" ;}
	else
	{
		while (!$result->EOF)
		{
			for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
			{
				$nombre=$result->fields[3];
				$apellido=$result->fields[4];
				$nivel=$result->fields[2];
				$sexo=$result->fields[6];
			}
				if($sexo=='m' || $sexo=='M'){
				echo "Bienvenido ";}
				else{ echo "Bienvenida ";}
				if($sexo=='m' || $sexo=='M')
				{echo "Licenciado ";} 
				else{echo "Licenciada ";}
				echo $nombre. " " .$apellido;
			
			$result->MoveNext();					
		} 	
	}
	$this->conn->Close();
}

/*======================================================================================================================
 									FUNCION PARA CERRAR LA SESSION
=======================================================================================================================*/
function cerrar_sesion()
{
	session_start();
	session_destroy();
	header("location: ../index.php"); 

}


}
