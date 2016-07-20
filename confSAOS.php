<?php 

/*
   NOMBRE              : CONFIGURACIÓN GENERAL DEL SISTEMA - NomOS  - Nómina Open Source UDO
   VERSION             : V. 1.0.0	
   DESCRIPCION         : A TRAVÉS DE ESTE ARCHIVO SE PUEDE INCIALIZAR Y CONFIGURAR LAS
                         VARIABLES NECESARIAS EN TODO EL SISTEMA.
   FECHA DE CREACIÓN   : 24-10-2005 11:45 PM
   
   ................................    CONTROL DE MODIFICACIONES   ..................................
     MODICADO POR  /          FECHA 	  /                RESUMEN  DE LA MODIFICACIÓN	
   ..................................................................................................		
         	       /                        /                                                   
         	       /                        /                                                   
         	       /                        /                                                   
*/
	// NO PERMITE QUE SE VISUALICEN LOS ERRORES POR DEFECTOS DEL PHP
	
//  ini_set("session.gc_maxlifetime","120"); 
//	ini_set("session.gc_maxlifetime","20"); 
	session_start(); 

	//error_reporting(0); 
	$servidor = $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
	//VARIABLES PARA LA BASE DE DATOS
	$nomosConfig_db_user_tesis      = "postgres";    // Usuario
	$nomosConfig_db_passwd_tesis    = "gamn2090";    // Clave
	$nomosConfig_db                  = "Control_de_estudios"; // Servicio de Base de Datos
	$nomosConfig_db_ip               = ""; // Servidor de Base de Datos
	$nomosConfig_db_debug            = false;
	$nomosConfig_db_driver           = 'postgres';
	//
	// VARIABLES DEL SISTEMA
	$nomosConfig_host            =  $servidor;
	$nomosConfig_absolute_path   = 'C:/wamp/tesis/';
	//$nomosConfig_sitio           = 'http://'.$servidor.'/NomOS/';
	$nomosConfig_descarga		 = 'c:/servidorPHP/uploads';
	$conf_raiz_general           = 'http://'.$servidor.'/';
	$conf_raiz_general_disco     = 'C:/servidorPHP/web/';
	$nomosConfig_offline         = '0';  // 0 si esta fuera de servicio cuando sea 1 estara fuera
	$nomosConfig_offline_message = 'Este Web está temporalmente cerrado por mantenimiento.<br />Por favor, vuelve a visitarnos más tarde.';
	$nomosConfig_error_message   = 'Ha ocurrido un error del servidor.<br />Por favor, notifíquelo al administrador del sistema:  <a href=\\\\\"mailto:ffuentes@udo.edu.ve\\\\\">ffuentes@udo.edu.ve</a>';
	$nomosConfig_lifetime        = '900';
	$nomosConfig_MetaDesc        = 'NomOS - portal dinámico para el sistema de nómina de la universidad de oriente';
	$nomosConfig_MetaKeys        = 'NomOS';
	$nomosConfig_MetaAuthor      = '1';
	$nomosConfig_MetaTitle       = '1';
	$nomosConfig_titulo_paginas  = 'NomOS   Nomina Open Source -  Universidad de Oriente';
	$saosConfig_titulo_paginas   = 'Sistemas Administrativos -  Universidad de Oriente';
	$nomosConfig_error_reporting = '-1';
	//
	//variable para indicar el tipo de movimiento
	$nomosConfig_movimiento[1]   = "Agregar";
	$nomosConfig_movimiento[2]   = "Modificar";
	$nomosConfig_movimiento[3]   = "Eliminar";
	$nomosConfig_movimiento[4]   = "Graficar";	
	$nomosConfig_movimiento[5]   = "Asociar";	
	//
	// CONFIGURA LA HORA DEL SISTEMA  
	setlocale (LC_TIME, $nomosConfig_locale);
	//
	//GENERICO
	$generico['1'] = 'DOCENTE';
	$generico['2'] = 'ADMINISTRATIVO';
	$generico['3'] = 'OBRERO';
	
	//DEDICACION
	$dedicacion['0'] = 'NO APLICABLE';
	$dedicacion['1'] = 'TIEMPO EXCLUSIVO';
	$dedicacion['2'] = 'TIEMPO COMPLETO';
	$dedicacion['3'] = 'MEDIO TIEMPO';
	$dedicacion['4'] = 'TIEMPO PARCIAL';
	
	//ESPECIFICACION
	$especificacion['0'] = 'SIN ESPECIFICACION';
	$especificacion['1'] = 'SIN TITULO UNIVERSITARIO';
	$especificacion['2'] = 'AAUXILIAR DOCENTE';
	$especificacion['3'] = 'TECNOLOGO';
	$especificacion['4'] = 'CON TITULO UNIVERSITARIO';
	
	//CATEGORIA
	$categoria['0'] = 'NO APLICABLE';
	$categoria['1'] = 'INSTRUCTOR O NIVEL 1';
	$categoria['2'] = 'ASISTENTE  O NIVEL 2';
	$categoria['3'] = 'AGREGADO O NIVEL 3';
	$categoria['4'] = 'ASOCIADO O NIVEL 4';
	$categoria['5'] = 'TITULAR O NIVEL 5';
	
	//CONDICION LABORAL
	$condicion['0'] = 'NO APLICABLE';
	$condicion['1'] = 'ORDINARIO O FIJO';
	$condicion['2'] = 'CONTRATADO';
	$condicion['3'] = 'INTERINO O SUPLENTE';
	$condicion['4'] = 'JUBILADO';
	$condicion['5'] = 'PENSIONADO POR INCAPACIDAD';
	$condicion['6'] = 'PENSIONADO POR SOBREVIVENCIA';
	$condicion['7'] = 'BECARIO';
	$condicion['8'] = 'SABATICO O LICENCIA';
	$condicion['9'] = 'SUPERNUMERARIO';
		
	//NACIONALIDAD
	$nacionalidad['V'] = 'VENEZOLANO(A)';
	$nacionalidad['E'] = 'EXTRANJERO(A)';
	
	//INSTITUCION
	$tipo['P'] = 'PUBLICO';
	$tipo['R'] = 'PRIVADO';
	
	//FINANCIAMIENYO
	$finan['P'] = 'PUBLICO';
	$finan['U'] = 'PRIVADO';

	//SEXO
	$sexo['M'] = 'MASCULINO';
	$sexo['F'] = 'FEMENINO';	
	//
	//ESTADO CIVIL
	$edocivil['S'] = 'SOLTERO(A)';
	$edocivil['C'] = 'CASADO(A)';
	$edocivil['D'] = 'DIVORCIADO(A)';	
	$edocivil['V'] = 'VIUDO(A)';
	//
	//TIPO DE MOVIMIENTO DE LA NÓMINA
	$tipo_mov['1'] = 'SALDO CUOTA';
	$tipo_mov['2'] = 'CUOTA FIJA';
	$tipo_mov['3'] = 'PORCENTAJE';
	$tipo_mov['4'] = 'PORCENTAJE VARIABLE';
	//
	//CONTROL DE MOVIMIENTO DE CONCEPTOS
	$control_mov['1'] = 'INSERTAR';
	$control_mov['2'] = 'MODIFICAR';
	$control_mov['3'] = 'ELIMINAR';
	//
	//arreglo de tipo de movimiento de los boletines
	$mov[1] = "INGRESO";
	$mov[2] = "MODIFICACIÓN";
	$mov[3] = "RETIRO";
	$mov[4] = "REINGRESO";
	//
	//arreglo para tipo de cuenta  
	$cuenta['A'] = "AHORRO";
	$cuenta['C'] = "CORRIENTE";
	
	//arreglo de PARENTESCO
	$carga[1] = "PADRES";
	$carga[2] = "ESPOSO(A)";
	$carga[3] = "HIJO(A)";
	$carga[4] = "ABUELO(A)";
	$carga[5] = "HERMANO(A)";
	
	//arreglo de PARENTESCO
	$cargaF['P'] = "PADRES";
	$cargaF['C'] = "ESPOSO(A)";
	$cargaF['H'] = "HIJO(A)";
	$cargaF['A'] = "ABUELO(A)";
	$cargaF['E'] = "HERMANO(A)";
	$cargaF['O'] = "COMCUBINO(A)";
	
	
	//arreglo del nivel academico
	$nivel_ed['01'] = "BASICA";
	$nivel_ed['02'] = "MEDIA DIVERSIFICADA";
	$nivel_ed['03'] = "PREGRADO";
	$nivel_ed['04'] = "POSTGRADO";
	$nivel_ed['05'] = "INICIAL";
  
  	//arreglo del nivel educativo carga familiar
	$nivel_edcar['01'] = "INICIAL";
	$nivel_edcar['02'] = "BASICA I ETAPA (1,2,3)";
	$nivel_edcar['03'] = "BASICA II ETAPA (4,5,6)";
	$nivel_edcar['04'] = "BASICA III ETAPA (7,8,9)";
	$nivel_edcar['05'] = "MEDIA DIVERSIFICADA (1,2)";
	$nivel_edcar['06'] = "SUPERIOR";
	
  //ESTATUS DATOS ACADÉMICO
   $conf_status['1'] ='SOLICITUD';
   $conf_status['2'] ='APROBADO';
   $conf_status['3'] ='RECHAZADO';
   $conf_status['4'] ='EN ESTUDIO';
   $conf_status['5'] ='CULMINADO';      
   //NUCLEOS
    $nucleos[1] = 'RECTORADO';
	$nucleos[2] = 'SUCRE';
	$nucleos[3] = 'ANZOATEGUI';
	$nucleos[4] = 'MONAGAS';
	$nucleos[5] = 'BOLIVAR';
	$nucleos[6] = 'NVAESPARTA';

   //MESES DEL AÑO
    $mes['01'] = 'ENERO';
	$mes['02'] = 'FEBRERO';
	$mes['03'] = 'MARZO';
	$mes['04'] = 'ABRIL';
	$mes['05'] = 'MAYO';
	$mes['06'] = 'JUNIO';
	$mes['07'] = 'JULIO';
	$mes['08'] = 'AGOSTO';
	$mes['09'] = 'SEPTIEMBRE';
	$mes['10'] = 'OCTUBRE';
	$mes['11'] = 'NOVIEMBRE';
	$mes['12'] = 'DICIEMBRE';

	//COLORES EN HEXADECIMAL
	$color[0] = '#0033FF';
	$color[1] = '#00FFFF';
	$color[2] = '#CCCCCC';
	$color[3] = '#CCFF00';
	$color[4] = '#FFFF00';
	$color[5] = '#FF6600';
	


 if (!isset($_SESSION['sess_codigo_usuario']) || ($_SESSION['sess_codigo_usuario']=='')){
				header("location: ".$conf_raiz_general);
	} 	
 

?>