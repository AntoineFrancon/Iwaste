<?php

// Connexion à la base de données

try

{

    //$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', 'root'); //en local
    $bdd = new PDO('mysql:host=localhost;dbname=scs;charset=utf8', 'scs', '6hflparKWRNo');

}

catch(Exception $e)

{

        die('Erreur : '.$e->getMessage());

}


$reponse = $bdd->query('SELECT id, remplissage, date_remp FROM poubelle');

            while ($donnees = $reponse->fetch())
            {

            $type_poubelle = 0.1 + rand() / getrandmax() * (1 - 0.1);
            $date = $donnees['date_remp'];
            $dateUTC=strtotime($date);
            $date_actuelle = time();
                 if ($dateUTC<=$date_actuelle) {
                     $date = date("Y-m-d H:i:s");
                 }

            if ($donnees['id']==1) {
                $opts = array(
                'http'=>array(
                'method'=>"GET",
                'header'=>"apikey: 3uCDfWUJL8ALQm1np72o7/QPP6ktt/qbTzSjQSbNcq/ZrnSTQWsrncaN+DWnweVG5Ul6MQ5h+z1ifqSS8J+poA=="
                  )
                );

                $context = stream_context_create($opts);

                // Open the file using the HTTP headers set above
                $file = file_get_contents("https://api.objenious.com/v1/devices/562949953422033/messages", false, $context);
                $obj = json_decode($file, true);
                $new_remplissage = $obj['messages'][1]['payload'][0]['data']['temperature'];
                if (empty($new_remplissage)) {
                        $new_remplissage=-1;
                    } 
            }
            
            else { 
                if ($donnees['remplissage']<85) {
                    if ($donnees['remplissage']==0) {
                        $new_remplissage = $donnees['remplissage']+rand(1, 5);
                    } else {$new_remplissage = $donnees['remplissage']+rand(0, 5);}
                } else {
                    $k = rand(0, 100);
                    if ($k>50) {
                        $new_remplissage=0; //poubelle vidée
                    }
                    else {
                        $new_remplissage=rand($donnees['remplissage'], 100);
                    }
                }
            }

                
                $req2 = $bdd->prepare('UPDATE poubelle SET remplissage = :remplissage, date_remp = :date_remp WHERE id = :id');
                $req2->execute(array(
                    'remplissage' => $new_remplissage,
                    'id' => $donnees['id'],
                    'date_remp' => $date
                    ));
                $req1 = $bdd->prepare('INSERT INTO historique(num_poubelle, remplissage, date_remp) VALUES(:num_poubelle, :remplissage, :date_remp)');
                $req1->execute(array(
                'num_poubelle' => $donnees['id'],
                'remplissage' => $new_remplissage,
                'date_remp' => $date
                ));


            }
$reponse->closeCursor(); 

header('Location: prototype.php');

?>