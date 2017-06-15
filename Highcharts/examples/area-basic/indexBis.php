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

<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>



		<script type="text/javascript">

        var remplissage =[];
        var vitesse_remplissage = [];

            <?php

                    if ($_POST['num_poubelle']==1) {
                        $historiquePoubelle = $bdd->prepare('SELECT j.remplissage AS remplissage, DAY(j.date_remp) AS jour, MONTH(j.date_remp) AS mois, YEAR(j.date_remp) AS annee, HOUR(j.date_remp) AS heure, MINUTE(j.date_remp) AS minute, SECOND(j.date_remp) AS seconde

                                                        FROM historique j

                                                        INNER JOIN poubelle p

                                                        ON j.num_poubelle = p.id

                                                        WHERE p.id = :id

                                                        ORDER BY j.date_remp DESC

                                                        LIMIT 0, 10

                                                        ');

                    $historiquePoubelle->execute(array('id' => $_POST['num_poubelle'])); } 
                    else {
                    $historiquePoubelle = $bdd->prepare('SELECT j.remplissage AS remplissage, DAY(j.date_remp) AS jour, MONTH(j.date_remp) AS mois, YEAR(j.date_remp) AS annee, HOUR(j.date_remp) AS heure, MINUTE(j.date_remp) AS minute, SECOND(j.date_remp) AS seconde

                                                        FROM historique j

                                                        INNER JOIN poubelle p

                                                        ON j.num_poubelle = p.id

                                                        WHERE p.id = :id

                                                        ORDER BY j.date_remp DESC

                                                        LIMIT 0, 168

                                                        ');

                    $historiquePoubelle->execute(array('id' => $_POST['num_poubelle']));
                }

                    while ($historique = $historiquePoubelle->fetch())
                                    
                           {

                            echo 'remplissage.push([Date.UTC('.$historique['annee'].', '.(($historique['mois']-1)%12).', '.$historique['jour'].', '.$historique['heure'].', '.$historique['minute'].', '.$historique['seconde'].'), '.$historique['remplissage'].']);';
   

                        }

                    $historiquePoubelle->closeCursor();


            ?>

            vitesse_remplissage = JSON.parse(JSON.stringify(remplissage));
            vitesse_remplissage[remplissage.length-1][1] = 0;
            for (var iter = 0; iter < remplissage.length-1; iter++) {
                vitesse_remplissage[iter][1] = ((remplissage[iter][1]-remplissage[iter+1][1])/(remplissage[iter][0]-remplissage[iter+1][0]))*360000;
                vitesse_remplissage[iter][1] = Math.round(vitesse_remplissage[iter][1]*100)/100;
            }

Highcharts.chart('container', {
    chart: {
        zoomType: 'xy'
    },
    title: {
        <?php echo 'text: \'Historique de remplissage de la poubelle n°'.$info['id'].'\''; ?>
    },
    subtitle: {
        <?php echo 'text: \'Adresse: '.$info['adresse'].' / Taux de remplissage actuel: '.$info['remplissage'].'%\''; ?>
    },
    xAxis: [{

        type: 'datetime',
        // dateTimeLabelFormats: { // don't display the dummy year
        //     month: '%e. %b',
        //     year: '%b'
        // },
        title: {
            text: 'Date'
        }
    }],
    yAxis: [{ // Primary yAxis
        labels: {
            format: '{value}%',
            style: {
                color: Highcharts.getOptions().colors[0]
            }
        },
        title: {
            text: 'Remplissage',
            style: {
                color: Highcharts.getOptions().colors[0]
            }
        },
        opposite: false,
        min: 0,
        max: 100


    }, { // Tertiary yAxis
        title: {
            text: 'Vitesse de remplissage',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        labels: {
            format: '{value} %/H',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        opposite: true,
        min: -1
    }],
    tooltip: {
        shared: true
    },
    legend: {
        layout: 'vertical',
        align: 'left',
        x: 80,
        verticalAlign: 'top',
        y: 55,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
    },
    series: [{
        name: 'Remplissage',
        type: 'areaspline',
        data: remplissage,
        tooltip: {
            valueSuffix: '%'
        }

    }, {
        name: 'Vitesse de remplissage',
        type: 'spline',
        yAxis: 1,
        data: vitesse_remplissage,
        marker: {
            enabled: false
        },
        dashStyle: 'shortdot',
        tooltip: {
            valueSuffix: ' %/H'
        }
    }]
});




		</script>

