<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ramassage intelligent des déchets</title>


    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="style_poubelle.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="icon" type="image/jpg" href="images/iwaste_logo.jpg" />
    
  </head>

  <body>

    <?php include("navbar.php"); ?>

    <?php
    if (isset($_POST['mot_de_passe']) AND $_POST['mot_de_passe'] ==  "supelec2018") // Si le mot de passe est bon
    {
    // On affiche les codes
    ?>
    <div class="container">
        <h2>Vous pouvez ici consulter l'avancement de notre projet</h2></br>
        <p>
          <strong>Septembre 2016</strong>
          <ul>
            <li>Constitution de l’équipe</li>
            <li>Choix du sujet du projet</li>
          </ul> 
        </p>
        <p>
          <strong>Octobre 2016</strong>
          <ul>
            <li>Elaboration d’un plan de travail sur l’année</li>
            <li>Différents tests du capteur</li>
            <li>Définition d’un organigramme de Gantt</li>
            <li>Choix du capteur : capteur à ultrasons HY-SRF05 compatible avec l'arduino</li>
          </ul> 
        </p>
        <p>
          <strong>Novembre 2016</strong>
          <ul>
            <li>Implémentation du code Arduino permettant de récupérer les données du capteur</li>
            <li>Documentation sur Objenious et sur le réseau LoRa</li>
            <li>Réflexions sur le marché visé</li>
            <li>Définition d’un cahier des charges pour l’interface utilisateur</li>
            <li>Début de l’implémentation de l’interface : interface en ligne (HTML, CSS, Javascript, PHP)</li>
            <li>Choix de l’API pour l’algorithme de plus court chemin: Google Direction</li>
          </ul> 
        </p>
        <p>
          <strong>Décembre 2016</strong>
          <ul>
            <li>Connexion du capteur à ultrasons au réseau Objenious et lecture des données sur leur plateforme</li>
            <li>Optimisation du code Arduino</li>
            <li>Implémentation d’un outil de visualisation des poubelles</li>
            <li>Implémentation d’un outil d’optimisation du trajet des camions poubelles avec l’API Google Direction</li>
            <li>Participation à un hackaton Objenious</li>
          </ul> 
        </p>
        <p>
          <strong>Janvier 2017</strong>
          <ul>
            <li>Définition d’un nom : IWaste, et d’un logo pour le projet</li>
            <li>Analyse de la consommation pour chaque phase du boitier</li>
            <li>Mise en ligne de notre site Internet : iwaste.larez.fr</li>
          </ul> 
        </p>
        <p>
          <strong>Février 2017</strong>
          <ul>
            <li>Recherche d’un logiciel OpenSource pour remplacer l’API Google dans l’optimisation des trajets</li>
            <li>Ajout de fonctionnalités sur notre site</li>
            <li>Travail sur le design du site</li>
          </ul> 
        </p>
        </br>
        </br>
        <p>
          <a href="https://www.tomsplanner.fr/public/poubelleconnecte?">Notre diagramme de Gantt</a>
          (Mot de passe:"supélec")
        </p>
    </div>


    
        <?php

    }
    else // Sinon, on affiche un message d'erreur
    {
        echo '<p>Mot de passe incorrect</p>';
    }
    ?>





    <div class="centraleSupelec">
    </br></br></br></br>        
        <A href="http://www.centralesupelec.fr/wordpress/" title="CentraleSupélec"><img class="img-fluid" alt="" src="images/CentraleSupelec_Logo.png"></A>
    </div>

<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>