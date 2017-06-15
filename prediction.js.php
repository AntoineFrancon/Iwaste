<script type="text/javascript">

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

            //$reponse = $bdd->query('SELECT * FROM poubelle');

            echo 'var nbPoub = '.$nbPoubelle.';';


?>

var prediction;
var prediction_remplissage_list = new Array(nbPoub+1).join('0').split('').map(parseFloat);

prediction = function(){
    
    <?php

    for ($k = 0; $k < $nbPoubelle; $k++) {

    echo 'var prediction_remplissage=[];';
                    
                    $historiquePoubelle = $bdd->prepare('SELECT j.remplissage remplissage, j.date_remp date_remp

                                                        FROM historique j

                                                        INNER JOIN poubelle p

                                                        ON j.num_poubelle = p.id

                                                        WHERE p.id = :id

                                                        ORDER BY j.date_remp DESC

                                                        LIMIT 0, 200

                                                        ');

                    $historiquePoubelle->execute(array('id' => ($k+1)));

                    while ($historique = $historiquePoubelle->fetch())
                                    
                           {

                             //echo $historique['remplissage'].'</br>';
                            // echo $historique['date_remp'].'</br>';
                            echo 'prediction_remplissage.push('.$historique['remplissage'].');';

                        }

                    $historiquePoubelle->closeCursor();


            
            echo 'var a_ete_vide =[];
            for (var iter = 0; iter < prediction_remplissage.length-1; iter++) {
                if (prediction_remplissage[iter]==0) {
                    a_ete_vide.push(iter);
                };                
            }
            var l = a_ete_vide.length;
            var somme =0;
            var temp_moy = 0;
            if ((l%2)==0) {
                for (var i = 0; i < l; i=i+2) {
                    somme = somme + a_ete_vide[i+1]-a_ete_vide[i];
                };
                temp_moy = 2*somme/l;
            } else {
                for (var i = 0; i < l-1; i=i+2) {
                    somme = somme + a_ete_vide[i+1]-a_ete_vide[i];
                };
                temp_moy = 2*somme/(l-1);
            };
            var temp_restant = temp_moy-a_ete_vide[0];

            prediction_remplissage_list['.$k.']=temp_restant.toFixed(2);';

        }


            ?>
     
};

</script>