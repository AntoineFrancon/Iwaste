<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

    <title>Ramassage intelligent des déchets</title>


    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="style_poubelle.css" rel="stylesheet" type"text/css">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="icon" type="image/jpg" href="images/iwaste_logo.jpg" />

    
    
  </head>
  <body>

    <?php include("navbar.php"); ?>


</br></br>
    <div class="container">
      <div class="row">
        <!-- <div class="col-lg-7"> -->
        <div id="destinationForm">
          <div class="row">
                <b>Départ du camion:</b>
                <select id="origin">
                  <option value="1 Rue Joliot Curie, 91190 Gif-sur-Yvette, France">Supélec, Gif</option>
                  <option value="Route de Saclay, 91128 Palaiseau">Ecole polytechnique, Palaiseau</option>
                  <option value="15 Rue Georges Clemenceau, 91400 Orsay">Université Paris-Sud, Orsay</option>
                </select>
          </div>
          <div class="row">
                <b>Arrivée du camion:</b>
                <select id="destination">
                  <option value="1 Rue Joliot Curie, 91190 Gif-sur-Yvette, France">Supélec, Gif</option>
                  <option value="Route de Saclay, 91128 Palaiseau">Ecole polytechnique, Palaiseau</option>
                  <option value="15 Rue Georges Clemenceau, 91400 Orsay">Université Paris-Sud, Orsay</option>
                </select>
          </div>

          <div class="row">
            <input type="button" class="button3" value="Afficher/Masquer les poubelles à cocher" onclick="javascript:selectionCoche(1)">
          </div>

          <div id="toHide1" class="toHide1" style="display:none">
            <form>
           <?php
                try

                {

                    //$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', 'root'); //en local
                    $bdd = new PDO('mysql:host=localhost;dbname=scs;charset=utf8', 'scs', '6hflparKWRNo');

                }

                catch(Exception $e)

                {

                        die('Erreur : '.$e->getMessage());

                }

                $reponse = $bdd->query('SELECT id, adresse FROM poubelle');

                $compteur = 0;
            
                while ($donnees = $reponse->fetch())
                {
                  if (($compteur%8)==0) {
                    echo '<div class="row">
                            <label>
                              <input type="checkbox" id="cbox'.$donnees['id'].'" value="'.$donnees['adresse'].'">
                              Poubelle'.$donnees['id'].'    
                            </label>';
                          $compteur ++; } elseif (($compteur%8)==7) {
                            echo '<label>
                                    <input type="checkbox" id="cbox'.$donnees['id'].'" value="'.$donnees['adresse'].'">
                                    Poubelle'.$donnees['id'].'  
                                  </label>
                                </div>';
                          $compteur ++;
                          } else {
                            echo '<label>
                                    <input type="checkbox" id="cbox'.$donnees['id'].'" value="'.$donnees['adresse'].'">
                                    Poubelle'.$donnees['id'].'  
                                  </label>';
                                  $compteur ++;
                          }
                }
                $reponse->closeCursor();

              ?>

      </form>
          </div>
        </div>
        <div class="row">
        <div class="col-md-8">
            <div class="row">
                  <form action="random_remplissage.php" method="post" name="remplissage" id="remplissage">
                    <div class="info1">
                      <input type="submit" class="button1" value="Attribuer les taux de remplissage" alt="La poubelle 1 reçoit directement son taux depuis le capteur LoRa. Pour les autres poubelles celui-ci est géneré aléatoirement">
                      <span>La poubelle 1 reçoit directement son taux de remplissage depuis le capteur LoRa. Pour les autres poubelles celui-ci est géneré aléatoirement</span>
                      </div>
                  </form>
            </div>
            <div class="row">
                  <form action="simulation.php" method="post" name="simulation" id="simulation">
                    <div class="info1">
                      <input type="submit" class="button1" value="Simuler" alt="Simule les cycles de remplissage/ramassage des poubelles virtuelles durant un mois.">
                      <span>Simule les cycles de remplissage/ramassage des poubelles virtuelles durant une semaine, à raison d'une mesure par heure. Attention l'opération peut prendre quelques minutes.</span>
                      </div>
                  </form>
            </div>
            <div class="row">         
                  <form action="" method="get" name="priority" id="priority">
                    <div class="info1">
                    <input type="button" class="button1" value="Sélectionner les poubelles à vider" onclick="javascript:urgence()" alt="Les poubelles pleines dans moins de 48h sont selectionnées.">
                     <span>Les poubelles pleines dans moins de 12h sont selectionnées.</span>
                      </div>
                  </form>
                  <form action="" method="get" name="deselection" id="deselection">
                    <input type="button" class="button3" value="Tout selectionner" onclick="javascript:selection()">
                    <input type="button" class="button3" value="Tout déselectionner" onclick="javascript:selection2()">
                  </form>
            </div>
            </br>
            <div class="row">
                  <form action="" method="get" name="direction" id="direction">
                    <div class="info3">
                    <input type="button" class="button2" value="Calculer l'itinéraire" onclick="javascript:calculate()" alt="Résolution du problème de Voyageur du commerce (TSP) basée sur l'API Google Direction. Notre code ne permet pas toujours d'obtenir une solution optimale ou proche de l'optimale. Des euristiques seront rajoutées à l'avenir.">
                    <span>Résolution du problème de <i>Voyageur du commerce</i> (TSP) basée sur l'API Google Direction. Notre code ne permet pas toujours d'obtenir une solution optimale ou proche de l'optimale. Des euristiques seront rajoutées à l'avenir.</span>
                    </div>
                  </form>
                </br>
            </div>
          </div>
          <div class="col-md-4" style="border-style: solid; border-color: #091D9E; border-radius: 3px;">
          </br>
            <div class="row" style="margin-left:0.5px;">
                  <form action="new_bin.php" method="post" name="new_bin" id="new_bin">
                    <div class="row" style ="text-align: center;">
                    <b style="font-size: medium; text-decoration: underline; text-align: center; font-family: Arial;">AJOUTER UNE NOUVELLE POUBELLE</b>
                    </div>
                    <h5>
                        <label for="adresse" style="font-family: Arial;">Adresse :</label>
                        <input type="text" name="adresse" id="adresse" placeholder="1 Rue Joliot Curie, 91190 Gif-sur-Yvette, France" size="30" maxlength="150" />
                    </h5>
                    <div class="row" style ="text-align: center;">
                    <input type="submit" class="button1" value="Ajouter" alt="ajouter nouvelle poubelle"  style ="text-align: center;">
                    </div>
                  </form>
            </div>
          </br>
          </div>
        </div>
          </br>
          
      </div>


        </div>
        <div class="container">
          <div class="row">
              <div id="map" style="width:100%;height:470px;border:0; margin: auto;display:block;">
                  <p>Veuillez patienter pendant le chargement de la carte...</p>
              </div>
          </div>
          <div class="row">
            <div class="col-md-8">
                </br>
                <input type="button" class="button3" value="Plus de détail sur le trajet" onclick="javascript:selectionCoche(2)">
          

          <div id="toHide2" class="toHide2" style="display:none">
              <div id="panel"></div>
          </div>
          </div>
        </div> 
      </div>
    </br></br>

    <div class="container">
      <div class="row">
        <div class="col-md-8">

          <?php

          $reponse = $bdd->query('SELECT id FROM poubelle');
                $donnees = $reponse->fetch();
                $string_query = "loc0=start";
            
                while ($donnees = $reponse->fetch())
                {
                  $string_query .= "&loc".$donnees['id']."=dest".$donnees['id'];

                }

                $reponse->closeCursor();





       // <form NAME="roundtrip" METHOD="get" ACTION="http://gebweb.net/optimap/index.php?loc0=start&loc1=dest1&loc2=dest2&loc3=dest3&loc4=dest4&loc5=dest5&loc6=dest6&loc7=dest7&loc8=dest8&loc9=dest9&loc10=dest10&loc11=dest11&loc12=dest12&loc13=dest13&loc14=dest14&loc15=dest15&loc16=dest16&loc17=dest17&loc18=dest18&loc19=dest19&loc20=dest20&loc21=dest21&loc22=dest22&loc23=dest23&loc24=dest24&loc25=dest25&loc26=dest26&loc27=dest27&loc28=dest28&loc29=dest29&loc30=dest30&loc31=dest31&loc32=dest32&loc33=dest33" TARGET=_BLANK>
          echo '<form NAME="roundtrip" METHOD="get" ACTION="http://gebweb.net/optimap/index.php?'.$string_query.'" TARGET=_BLANK>';
          echo '<input NAME="loc0" TYPE="hidden" />';
                $reponse = $bdd->query('SELECT id FROM poubelle');
            
                while ($donnees = $reponse->fetch())
                {
                  
                  echo '<input NAME="loc'.$donnees['id'].'" TYPE="hidden" />';

                }

                echo '<div class="info2">
        <input TYPE="submit" class="button4" value="Comparer à la solution OPtiMap" alt="Réoslution de type Voyageur du commerce basé sur l\'API Google Direction et des algorithmes type \'force brute\', \'colonie de fourmie\' etc. La solution est optimale ou proche de l\'optimale."/>
        <span>Résolution du problème de <i>Voyageur du commerce</i> (TSP) basée sur l\'API Google Direction et divers algorithmes de type <i>force brute</i>, <i>colonie de fourmie</i> etc. La solution est optimale ou proche de l\'optimale.</span>
        </div>

        </form>';

                $reponse->closeCursor();

        ?>

        

        </br></br>
      </div>
        <div class="col-md-4">
          <div class="row">
                  <form action="vider_historique.php" method="post" name="vider_historique" id="vider_historique">
                      <input type="submit" class="button3" value="Supprimer l'historique" alt="Permet de vider l'historique">
                  </form>
          </div>
        </div>
      </div>
        

    </div>

    <div class="container">
      <div>
              <b class="temperature">La température à Gif-sur-Yvette est actuellement de </b>
      </div>
      </br>
      </br>
      <div>
              <b class="remplissage_1">Le taux de remplissage de la poubelle n°1 vaut </b>
      </div>
      

    </div>
  </div>
      <div class="centraleSupelec">
          </br></br></br></br>
          <A href="http://www.centralesupelec.fr/wordpress/" title="CentraleSupélec"><img class="img-fluid" alt="" src="images/CentraleSupelec_Logo.png"></A>
      </div>

    
    <!-- Include Javascript -->
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="jquery-ui-1.8.12.custom.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsFIDL-Zf_y8sh6cBZW1NSCnCJ9Bpl_kE&callback"
  type="text/javascript"></script>
  <script type="text/javascript" src="ajaxGet.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
   <!--<script type="text/javascript" src="functions.js"></script>-->
   <?php include("prediction.js.php"); ?>
    <?php include("functions.js.php"); ?>
    <?php include("ajax.js.php"); ?>
    <!-- <script src="ajax.js.php"></script> -->
<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>