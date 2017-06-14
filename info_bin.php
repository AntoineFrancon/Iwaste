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
      <?php include("Highcharts/examples/area-basic/indexBis.php"); ?>
    </div>
  </br>

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

            $requeteNbPoubelle = $bdd->query('SELECT COUNT(*) AS nbPoubelle FROM poubelle');
            $nbPoubelleTab = $requeteNbPoubelle->fetch();
            $nbPoubelle = $nbPoubelleTab['nbPoubelle'];
            $requeteNbPoubelle->closeCursor();


  ?>

  <div class="container">
    <div class="row">
      <div class="col-md-4">
                            <form action="info_bin.php" method="post" >
                                <h2>
                                    <?php if ($_POST['num_poubelle']==1) {
                                      echo '<input type="hidden" name="num_poubelle" value="'.($nbPoubelle).'"/>' ;
                                    } else {
                                    echo '<input type="hidden" name="num_poubelle" value="'.($_POST['num_poubelle']-1).'"/>';
                                    } ?>
                                    <input type="submit" style="background-color: #091D9E; font-weight: bold;  color: white; border-radius: 3px;" value="Poubelle précédente"/>
                                </h2>
                            </form>
    </div>
      <div class="col-md-4">
        <h2 style="color: #091d9e;"><a href="prototype.php" style="color: #091d9e;">Retour à la carte</a></h2>
      </div>
      <div class="col-md-4">
                            <form action="info_bin.php" method="post" >
                                <h2>
                                    <?php if ($_POST['num_poubelle']==$nbPoubelle) {
                                      echo '<input type="hidden" name="num_poubelle" value="'.(1).'"/>' ;
                                    } else {
                                      echo '<input type="hidden" name="num_poubelle" value="'.($_POST['num_poubelle']+1).'"/>';
                                    } ?>
                                    <input type="submit" style="background-color: #091D9E; font-weight: bold;  color: white; border-radius: 3px;" value="Poubelle suivante"/>
                                </h2>
                            </form>
    </div>
    </div>
  </div>



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
