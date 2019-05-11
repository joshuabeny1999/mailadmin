<!DOCTYPE html>
<html lang="de">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Mailadmin - joshuah.ch</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
<header>
  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="/" class="brand-logo">Mailadmin</a>
      <ul class="right hide-on-med-and-down">
        <li><a href="?seite=domains">Domains</a></li>
        <li><a href="?seite=mailboxes">Mailboxes</a></li>
        <li><a href="?seite=aliases">Aliases</a></li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="?seite=domains">Domains</a></li>
        <li><a href="?seite=mailboxes">Mailboxes</a></li>
        <li><a href="?seite=aliases">Aliases</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>
</header>
<main> 
   <div class="container">
  <?php
  $conn = mysqli_connect("localhost","vmail","35k6clpV","vmail");
  $maindomain = "joshuah.ch";
  // Check connection
  if (mysqli_connect_errno())
  {
  echo "Datenbankverbindung zu MySQL fehlgeschlagen: " . mysqli_connect_error();
  }
  if(isset($_GET['seite'])){
      $seite = $_GET['seite'];
  } else {
      $seite = "domains";
  }

  if ($seite === "domains"){
     include("domains.php");
  } elseif ($seite === "mailboxes"){
     include("mailboxes.php");
  } elseif ($seite === "aliases"){
     include("aliases.php");
  } elseif ($seite === "add"){
     include("add.php");
  } elseif ($seite === "edit"){
     include("edit.php");
  } elseif ($seite === "del"){
     include("delete.php");
  }
  ?> 
  </div>
</main>
  <footer class="page-footer orange">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">&Uuml;ber Mailadmin</h5>
          <p class="grey-text text-lighten-4">Mailadmin ist eine PHP-Applikation um die E-Mail-Konten des Mailserver zu verwalten. Das Datenbankschema ist von der <a href='https://thomas-leister.de/sicherer-mailserver-dovecot-postfix-virtuellen-benutzern-mysql-ubuntu-server-xenial/'>Mailserver-Anleitung von Thomas Leister.</a></p>


        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Menu</h5>
          <ul>
            <li><a class="white-text" href="?seite=domains">Domains</a></li>
            <li><a class="white-text" href="?seite=mailboxes">Mailboxes</a></li>
            <li><a class="white-text" href="?seite=aliases">Aliases</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      Made with <a class="orange-text text-lighten-3" href="http://materializecss.com">Materialize</a>
      </div>
    </div>
  </footer>


  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>

  </body>
</html>
