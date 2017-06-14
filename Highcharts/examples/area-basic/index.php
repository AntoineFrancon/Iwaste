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

$infoPoubelle = $bdd->prepare('SELECT * FROM poubelle WHERE id = :id');
$infoPoubelle->execute(array('id' => $_POST['num_poubelle']));

$info = $infoPoubelle->fetch();
$infoPoubelle->closeCursor(); 

?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 100%; height: 100%; margin: 0 auto"></div>



		<script type="text/javascript">

        var remplissage =[];
        var vitesse_remplissage = [];

            <?php
                    $historiquePoubelle = $bdd->prepare('SELECT j.remplissage AS remplissage, DAY(j.date_remp) AS jour, MONTH(j.date_remp) AS mois, YEAR(j.date_remp) AS annee, HOUR(j.date_remp) AS heure, MINUTE(j.date_remp) AS minute, SECOND(j.date_remp) AS seconde

                                                        FROM historique j

                                                        INNER JOIN poubelle p

                                                        ON j.num_poubelle = p.id

                                                        WHERE p.id = :id

                                                        ORDER BY date_remp DESC

                                                        LIMIT 0, 10

                                                        ');

                    $historiquePoubelle->execute(array('id' => $_POST['num_poubelle']));

                    while ($historique = $historiquePoubelle->fetch())
                                    
                           {

                            // echo $historique['remplissage'].'</br>';
                            // echo $historique['date_remp'].'</br>';
                            echo 'remplissage.push([Date.UTC('.$historique['annee'].', '.(($historique['mois']-1)%12).', '.$historique['jour'].', '.$historique['heure'].', '.$historique['minute'].', '.$historique['seconde'].'), '.$historique['remplissage'].']);';
                           // echo 'date_remp.push(['.$historique['jour'].', '.$historique['mois'].', '.$historique['annee'].', '.$historique['heure'].', '.$historique['minute'].', '.$historique['seconde'].' ]);';

                        }

                    $historiquePoubelle->closeCursor();


            ?>

            vitesse_remplissage = JSON.parse(JSON.stringify(remplissage));
            vitesse_remplissage[0][1] = 0;
            for (var iter = 1; iter < remplissage.length; iter++) {
                vitesse_remplissage[iter][1] = (remplissage[iter][1]-remplissage[iter-1][1])/(remplissage[iter][0]-remplissage[iter-1][0]);
            }
             


Highcharts.chart('container', {
    chart: {
        type: 'area'
    },
    title: {
        <?php echo 'text: \'Historique de remplissage de la poubelle n°'.$info['id'].'\''; ?>
    },
    subtitle: {
        <?php echo 'text: \'Adresse: '.$info['adresse'].' / Taux de remplissage actuel: '.$info['remplissage'].'%\''; ?>
    },

    xAxis: {
        type: 'datetime',
        // dateTimeLabelFormats: { // don't display the dummy year
        //     month: '%e. %b',
        //     year: '%b'
        // },
        title: {
            text: 'Date'
        }
    },
    yAxis: {
        title: {
            text: 'Taux de remplissage'
        },
        labels :{
            format: '{value}%'
        },
        min: 0,
        max: 100
    }, 
    // {
    //     title: {
    //         text: 'Vitess de remplissage'
    //     },
    //     labels :{
    //         format: '{value}%/H'
    //     },
    // },
    tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: 'Le taux de remplissage était de <b>{point.y}%</b> à la date du {point.x:%e.%b %Y %H:%M:%S}'
    },

    plotOptions: {
        area: {
            marker: {
                enabled: false,
                symbol: 'circle',
                radius: 2,
                states: {
                    hover: {
                        enabled: true
                     }
                }
            }
        }
    },

    series: [{
        <?php echo 'name: \'Poubelle n°'.$info['id'].'\''?>,
        // Define the data points. All series have a dummy year
        // of 1970/71 in order to be compared on the same x axis. Note
        // that in JavaScript, months start at 0 for January, 1 for February etc.
        data: remplissage,
        color: '#091D9E'
        
    }
    // {   <?php echo 'name: \'Poubelle Bis n°'.$info['id'].'\''?>,
    //     // Define the data points. All series have a dummy year
    //     // of 1970/71 in order to be compared on the same x axis. Note
    //     // that in JavaScript, months start at 0 for January, 1 for February etc.
    //     yAxis: 1,
    //     data: vitesse_remplissage,
    //     color: '#FE1B00'

    // }
    ]
});
		</script>
