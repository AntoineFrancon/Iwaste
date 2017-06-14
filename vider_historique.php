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

$reponse = $bdd->query('TRUNCATE historique');
$reponse->closeCursor(); 

$reponse2 = $bdd->query('SELECT id FROM poubelle');
while ($donnees = $reponse2->fetch())
            {
            	$req2 = $bdd->prepare('UPDATE poubelle SET remplissage = :remplissage, date_remp = NOW() WHERE id = :id');
                $req2->execute(array(
                    'remplissage' => 0,
                    'id' => $donnees['id']
                    ));
            }
$reponse2->closeCursor();

header('Location: prototype.php');

?>