<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
  <title>Coordinación Académica</title>
    <link rel="shortcut icon" href="udo.ico" />

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
  <nav class="white" role="navigation">
    <div class="nav-wrapper container">
      <a id="logo-container" href="#" class="brand-logo"><img src="img/udo.gif" alt=""></a>
      <ul class="right hide-on-med-and-down">                  
      
      <a href="#" data-activates="slide-out" class="button-collapse"><i class="mdi-navigation-menu">menu</i></a>
    </div>
  </nav>

  <div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
      <div class="container">
        <br><br>
        <h1 class="header center blue-text text-darken-1">Coordinación Académica</h1>
        <div class="row center">
          <h5 class="header col s12 light white-text text-darken-5">Sistema de Apoyo a la Toma de decisiciones de la Coordinación Académica de la Universidad de Oriente</h5>
        </div>
        
        <br><br>

      </div>
    </div>
    <div class="parallax"><img src="img/Udo.jpg" alt="Unsplashed background img 1"></div>
  </div>


  <div class="container">
    <div class="row">        
        <div class="col m6 offset-m3">
            <h2 class="center-align">Accesar</h2>
            <div class="row">
                <form id="login" class="col s12" action="procesos/motor_funciones.php" method="POST">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="usuario" name="usuario" type="text" class="validate">
                            <label for="usuario">Usuario</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="contraseña" name="contraseña" type="password" class="validate">
                            <label for="contraseña">Contraseña</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <p>
                                <input type="checkbox" id="remember">
                                <label for="remember">Recordarme</label>
                            </p>
                        </div>
                    </div>    
                    <?php
                        if(isset ($_GET['id']) && $_GET['id']== 1){
                        echo "<tr><td colspan='2' align= 'center'>El login de Usuario o la Clave Insertada Es Incorrecta! </td></tr>";// el '<tr><td es para abrir una fila'                        
                    }?>                               
                    <div class="divider"></div>
                    <div class="row">                       
                        <div class="col m12 offset">
                            <p class="center-align">
                                <button class="btn btn-large waves-effect waves-light" id="login" type="submit" value="Accesar" name="accion" title="login">Aceptar</button>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
  <script src="js/jquery.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>

  </body>
</html>
