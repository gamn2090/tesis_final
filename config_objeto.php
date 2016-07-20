<?php

		$Config_absolute_path   		= 'C:/wamp/www/tesis/';
		include_once($Config_absolute_path.'adodb5/adodb.inc.php');
		$this->conn = ADONewConnection('postgres'); 
		$this->conn->NConnect('host=localhost port=5432 dbname=Control_de_estudios user=postgres password=gamn2090');

?>