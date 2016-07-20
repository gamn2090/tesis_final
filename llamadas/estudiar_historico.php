<?php
session_start();
      $usuario=$_SESSION['usuario'];
      $nivel=$_SESSION['nivel'];  
      $bandera=$_SESSION['bandera'];   
  include ("../config.php");
  include('../procesos/CHistorico.php');
  $objHistorico = new Historico();


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Muestra de datos del estudiante</title>
  <link rel="shortcut icon" href="../udo.ico" />
	<link href="../css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="../css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/font.css">
    

</head>
<body>
<nav class="white" role="navigation">
    <div class="nav-wrapper container">
      <a id="logo-container" href="../coordinacion_principal.php" class="brand-logo"><img src="../img/udo.gif" alt=""></a>
      
    </div>
  </nav>

  <div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
      <div class="container">
        <br><br>
        <h1 id="nombre" class="header center blue-text text-darken-1">DECISIONES</h1>
        <div class="row center">          
        </div>        
        <br><br>
      </div>
    </div>
    <div  class="parallax"><img id="imagen" src="../img/decision.jpg" alt="Unsplashed background img 1"></div>
  </div>   
  <br><br>
	<div class="container">
		<div id="evaluar" class="content">

			<?php
				$cedula=$_GET['id']; 
        $soli=$_GET['soli'];
        $fecha=$_GET['fecha'];						
				$objHistorico->estudiar_decision($fecha,$cedula,$soli);
			?>
		
		</div>
    </div>
    
<div class="slider">
    <ul class="slides">
      <li>
        <img src="../img/udosucre.png"> <!-- random image -->
        <div class="caption right-align">
          <h3 class="black-text text-lighten-3">Universidad de Oriente</h3>
          <h5 class="white-text text-lighten-3">Excelencia académica</h5>
        </div>
      </li>
      <li>
        <img src="../img/udo_1.jpg"> <!-- random image -->
        <div class="caption left-align">
          <h3>Orgullosos</h3>
          <h5 class="black-text text-lighten-3">Creando nuevos profesionales</h5>
        </div>
      </li>
      <li>
        <img src="../img/Udistas.jpg"> <!-- random image -->
        <div class="caption center-align ">         
          <h3 class="black-text text-lighten-3">UDO por siempre</h3>
        </div>
      </li>
    </ul>
  </div>

  <footer class="page-footer teal">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">UNIVERSIDAD DE ORIENTE</h5>
          <p class="grey-text text-lighten-4">Contribuir a la formación de profesionales de excelencia, de valores éticos y morales, críticos, creativos e integrales en la prestación de servicios en las diferentes áreas del conocimiento y desarrollando actividades de investigación, docencia y extensión para cooperar en la construcción de una sociedad venezolana de la Región Oriental - Insular - Sur del país.</p>


        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Conocenos</h5>
          <ul>
            <li><a class="white-text" href="http://www.udo.edu.ve/" target="_blank">Universidad de Oriente</a></li>
            <li><a class="white-text" href="http://bibliotecadigital.udo.edu.ve/" target="_blank">Biblioteca General</a></li>
            <li><a class="white-text" href="http://servicios.sucre.udo.edu.ve/cacns/" target="_blank">Coordinación Académica Núcleo de Sucre</a></li>
            <li><a class="white-text" href="http://estudiantes.sucre.udo.edu.ve/" target="_blank">DACENS</a></li>
          </ul>
        </div>        
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      Hecho por <a class="brown-text text-lighten-3" href="https://www.facebook.com/gustavo.mattey" target="_blank">Gustavo Adolfo Mattey Nouaihed</a>
      </div>
    </div>
  </footer>


  <!--  Scripts-->
  <script type="text/javascript" src="../js/jquery.js"></script>
  <script src="../js/materialize.js"></script>
  <script src="../js/init.js"></script>



  </body>
</html>