<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Page protégée par mot de passe</title>

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">


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

      <div class="container">
      </br></br>
        <p>Veuillez entrer le mot de passe pour accéder à ce contenu :</p>
        <form action="avancement.php" method="post">
            <p>
            <input type="password" name="mot_de_passe" />
            <input type="submit" class="button4" value="Valider" />
            </p>
        </form>
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