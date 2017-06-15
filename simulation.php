<?php

// Connexion à la base de données

try

{

    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', 'root'); //en local
    //$bdd = new PDO('mysql:host=localhost;dbname=scs;charset=utf8', 'scs', '6hflparKWRNo');

}

catch(Exception $e)

{

        die('Erreur : '.$e->getMessage());

}


$reponse = $bdd->query('SELECT id, remplissage, date_remp FROM poubelle');

            while ($donnees = $reponse->fetch())
            {


            if ($donnees['id']==1) {
                $opts = array(
                'http'=>array(
                'method'=>"GET",
                'header'=>"apikey: 3Ivi5tcaf/oBJZ7T77031C3DnYp62UdEvU2UOauR7JRWu+zYKgtDBXOB3pmnwX0ph8IpxQuXAaH4kiCJJubAAg=="
                  )
                );

                $context = stream_context_create($opts);

                // Open the file using the HTTP headers set above
                $file = file_get_contents("https://api.objenious.com/v1/devices/562949953422026/messages", false, $context);
                $obj = json_decode($file, true);
                $x = 0;
                while ( ($obj['messages'][$x]['type'] != 'uplink') && ($x<=3) ) {
                    $x = $x +1;
                }
                $new_remplissage = $obj['messages'][$x]['payload'][0]['data']['temperature'];
                if (empty($new_remplissage)) {
                        $new_remplissage=-1;
                    } 

                $req2 = $bdd->prepare('UPDATE poubelle SET remplissage = :remplissage, date_remp = NOW() WHERE id = :id');
                $req2->execute(array(
                    'remplissage' => $new_remplissage,
                    'id' => $donnees['id']
                    ));
                $req1 = $bdd->prepare('INSERT INTO historique(num_poubelle, remplissage, date_remp) VALUES(:num_poubelle, :remplissage, NOW())');
                $req1->execute(array(
                'num_poubelle' => $donnees['id'],
                'remplissage' => $new_remplissage
                ));

            }
            
            else { 

                $type_poubelle = 0.1 + rand() / getrandmax() * (1 - 0.1);

                $remplissage_courant = $donnees['remplissage'];
                $date = $donnees['date_remp'];
                $dateUTC=strtotime($date);
                $date_actuelle = time();
                 if ($dateUTC<=$date_actuelle) {
                     $date = date("Y-m-d H:i:s");
                 }


                 for ($i=0; $i < 400; $i++) { 
                     
                 

                $new_date = strtotime($date)+3600;
                $date = date("Y-m-d H:i:s", $new_date);

                $heure = date("H", strtotime($date));

                if (($heure>21) || ($heure<7)) { // Moins de remplissage pendant la nuit
                    $facteur = $type_poubelle/3;
                    $constante = 0;
                } elseif (($heure==9) || ($heure==12) || ($heure==18)) { // Plus de remplissage aux heure de pointes
                    $facteur = $type_poubelle*2;
                    $constante = 1.5;
                } else {$facteur = $type_poubelle; // $type_poubelle est un coefficient différent pour chaque poubelle
                        $constante = 0.5;}

                if ($remplissage_courant<80) {
                    if ($remplissage_courant==0) {
                        $new_remplissage = $remplissage_courant+rand(1, 4)+$constante;
                    } else {$new_remplissage = $remplissage_courant+$facteur*rand(0, 6)+$constante;}
                   
                } else {
                    $k = rand(0, 100);
                    if ($k>50) {
                        $new_remplissage=0; //poubelle vidée 
                    }
                    else {
                        $ajout = $facteur*rand(0, 6)+$constante;
                        if ($remplissage_courant+$ajout<=100) {
                            $new_remplissage = $remplissage_courant+$ajout;
                        } else {$new_remplissage=rand($remplissage_courant, 100);}
                        
                    }
                }

                
                $new_remplissage = round( $new_remplissage, 2, PHP_ROUND_HALF_DOWN);
             
                
                $req1 = $bdd->prepare('INSERT INTO historique(num_poubelle, remplissage, date_remp) VALUES(:num_poubelle, :remplissage, :date_remplissage)');
                $req1->execute(array(
                'num_poubelle' => $donnees['id'],
                'remplissage' => $new_remplissage,
                'date_remplissage' => $date
                ));

                

                $remplissage_courant=$new_remplissage;
                


            }

            $req2 = $bdd->prepare('UPDATE poubelle SET remplissage = :remplissage, date_remp = :date_remp WHERE id = :id');
                $req2->execute(array(
                    'remplissage' => $remplissage_courant,
                    'id' => $donnees['id'],
                    'date_remp' => $date
                    ));

        }


            }
$reponse->closeCursor(); 

header('Location: prototype.php');

?>