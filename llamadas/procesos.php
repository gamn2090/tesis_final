<?php
  session_start();
      $usuario=$_SESSION['usuario'];
      $nivel=$_SESSION['nivel'];  
      $bandera=$_SESSION['bandera'];  
  include ("../procesos/CUsers.php");
  $objUsers = new User();
  //include ("../config.php");
  
if($usuario!= NULL)
{
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>

  <title>Coordinación Académica</title>
  <link rel="shortcut icon" href="../udo.ico" />

  <!-- CSS  -->
  <link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="../css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="../css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/font.css">
</head>
<body>
  <nav class="white" role="navigation">
    <div class="nav-wrapper container">
      <a id="logo-container" href="../coordinacion_principal.php" class="brand-logo"><img src="../img/udo.gif" alt=""></a>
      <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="../coordinacion_principal.php">Inicio</a></li>
        <li><a href="procesos.php">Procesos</a></li>
        <li><a href="obtener_procesos.php">Cargar Procesos</a></li>
        <?php
            if($nivel==$bandera)
            {   
            ?>
            <li><a href="mantenimiento.php">Mantenimiento</a></li>
            <?php
            }
            ?>
        <li><a href="cerrar_sesion.php">Cerrar sesion</a></li>
      </ul>
      <ul class="side-nav" id="mobile-demo">
         <li><a href="../coordinacion_principal.php">Inicio</a></li>
        <li><a href="procesos.php">Procesos</a></li>
        <li><a href="obtener_procesos.php">Cargar Procesos</a></li>
        <?php
            if($nivel==$bandera)
            {   
            ?>
            <li><a href="llamadas/mantenimiento.php">Mantenimiento</a></li>
            <?php
            }
            ?>
        <li><a href="llamadas/cerrar_sesion.php">Cerrar sesion</a></li>
      </ul>        
         
    </div>
  </nav>

  <div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
      <div class="container">
        <br><br>
        <h1 id="nombre" class="header center blue-text text-darken-1">HISTORICO DE DECISIONES</h1>
        <div class="row center">          
        </div>        
        <br><br>
      </div>
    </div>
    <div  class="parallax"><img id="imagen" src="../img/historico.jpg" alt="Unsplashed background img 1"></div>
  </div>
  <div class="col m3 offset-m9">    
        <div class="user_container" id="user"><i class=" icon-user-check"></i>
          <span id="userName">        
            <?php     
              $objUsers->cargar_datos_coord($usuario);
            ?>
          </spam>
        </div>   
  </div>  
  <br><br>
  <div class="container">
    <div class="section">

    <div class="row">
      <div class="col s12"> 
      <div class="clear_div"></div>      
        <ul class="tabs">
          <li id="historico" data-url="historico.php" class="tab col s3"><a href="#">Ver Histórico</a></li>
          <li id="retiros" data-url="retiros.php" class="tab col s3"><a href="#">Retiros</a></li>
          <li id="reingresos" data-url="reingreso.php" class="tab col s3"><a href="#">Reingresos</a></li>
          <li id="CDE" data-url="cambio_especialid.php" class="tab col s3"><a href="#">Cambio de Especialidades</a></li>
        </ul> 
      <div class="clear_div"></div>       
      </div>       
        <div id="principal" class="col s12"> 
        </div>
    </div> 
    </div> 
   </div> 

 <div class="slider">
    <ul class="slides">
      <li>
        <img src="../img/udosucre.png"> <!-- random image -->
        <div class="caption right-align">
          <h3 class="black-text text-lighten-3">Universidad de Oriente</h3>
          <h5 class="white-text text-lighten-3">"Del pueblo venimos y hacia el pueblo vamos"</h5>
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
  <script src="../js/jquery.js"></script>
  <script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>  
  <script src="../js/materialize.js"></script> 
  <script src="../js/init.js"></script>
  </body>
</html>
<?php
}else
{header("location: ../index.php");}
?>
