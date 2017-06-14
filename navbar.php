<?php

echo '

<div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.php">I-waste</a>
        </div>
        <ul class="nav navbar-nav">
          <li class="'.($_SERVER['PHP_SELF'] == "/avancement_confirmation.php" ? "active" : "").'"><a href="avancement_confirmation.php">Avancement du projet</a></li>
          <li class="'.($_SERVER['PHP_SELF'] == "/prototype.php" ? "active" : "").'"><a href="prototype.php">Prototype</a></li>
          <li class="'.($_SERVER['PHP_SELF'] == "/portofolio.php" ? "active" : "").'"><a href="portofolio.php">Portfolio</a></li>
          <li class="'.($_SERVER['PHP_SELF'] == "/accueil.php" ? "active" : "").'"><a href="accueil.php">Retour aux autres projets</a></li>
        </ul>
      </div>
    </div>

'
?>