<?php 
  include_once('../php/connectdb.php'); 
   if(!isset($_SESSION['user'])) header('location: ../');

  $page = '../php/utils/home.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>paani school</title>
    <!-- <link rel="icon" href="img/logo.png"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    
    <link rel="stylesheet" href="../css/simplePagination.css">
    <link rel="stylesheet" href="../css/dash.css">
    <link rel="stylesheet" href="../css/fakeLoader.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
    <!-- <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script> -->

    <meta name="viewport" content="width=device-width, initial-scale=1" />
  </head>
  <body>

    <header class="row topmenu">
      <div class="navbar-fixed">
        <nav class="blue col s12">
          <!--div class="nav-wrapper col m12 white"-->
            <font class="flow-text hide-on-med-and-down">Bienvenue</font>
          <!--/div-->
          <!-- <img src="../img/logo.png" alt="" style="max-width: 100%; height: 55px;" /> -->
          <ul class="right">
            <li><a href="../" class="white-text"><i class="material-icons left">arrow_back</i>Déconnexion</a></li>
            <li><a href="#" class="white-text btn_account"><i class="material-icons left">person</i><?php echo $_SESSION['user']->email; ?></a></li>
            <li><a href="#" class="white-text">A propos</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <div class="row">
        <input type="hidden" id="HOMEZONE" value="<?php echo $page; ?>" />
      <div class="fakeLoader LOADER"></div>
      <div class="col s12 load_area"></div>
    </div>
    
    <script type="text/javascript" src="../js/sweetalert.js"></script>
    <script type="text/javascript" src="../js/jquery.simplePagination.js"></script>
    <!-- <script type="text/javascript" src="../js/jquery.number.js"></script>   -->
    <!-- <script type="text/javascript" src="../js/system.js"></script> -->
    <!-- <script type="text/javascript" src="../js/jquery.routes.js"></script> -->
    <!-- <script type="text/javascript" src="../js/stat.js"></script> -->
    <script type="text/javascript" src="../js/fakeLoader.min.js"></script>
    <script>
      const PAGE_SIZE = 10;
      $.fakeLoader({ spinner: 'spinner1', bgColor: 'rgba(0,0,0,.6)' });
      function home(){
        $('.LOADER').show();
        $.post(
          '../php/'+$('#HOMEZONE').val(),
          {action: ''},
          function(data){
            $('.LOADER').hide();
            $('.load_area').html(data);
          }
        );
      }

      home();
	  </script>

  </body> 
</html>