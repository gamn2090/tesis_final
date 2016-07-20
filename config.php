<?php

	$tesis_user      				 = "postgres";    // Usuario
	$tesis_pw   					 = "gamn2090";    // Clave
	$tesis_dbname       			 = "Control_de_estudios"; // Servicio de Base de Datos
	$tesis_dbip         			 = "localhost"; // Servidor de Base de Datos
	$tesis_port         			 = "5432";
	$Config_db_driver				= 'postgres';
	$Config_absolute_path   		= 'C:/wamp/www/tesis/';
	
		global $tesis_dbip;
		global $tesis_dbname;
		global $tesis_user;
		global $tesis_pw;
		global $tesis_port;
		global $nomosConfig_db_driver;

	include_once($Config_absolute_path.'adodb5/adodb.inc.php');
	/*$conn = ADONewConnection('postgres'); 
	$conn->PConnect('host=localhost port=5432 dbname=Tesis user=postgres password=gamn2090');
if (!$conn) {
echo "Error en la conexion.\n";
}*/
		$conn = ADONewConnection('postgres'); 
		/*$tesis_host = 'localhost';
		$tesis_dbname = 'Control_de_estudios';
		$tesis_user = 'postgres';
		$tesis_pw = 'gamn2090';
		$tesis_port = '5432';*/
		
		$conn->NConnect('host=localhost port=5432 dbname=Control_de_estudios user=postgres password=gamn2090');
		//$this->conn->SetFetchMode(ADODB_FETCH_ASSOC);
?>