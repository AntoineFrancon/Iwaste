<?php

function getXmlCoordsFromAdress($address)
{
$coords=array();
$base_url="http://maps.googleapis.com/maps/api/geocode/xml?";
// ajouter &region=FR si ambiguité (lieu de la requete pris par défaut)
$request_url = $base_url . "address=" . urlencode($address).'&sensor=false';
$xml = simplexml_load_file($request_url) or die("url not loading");
//print_r($xml);
$coords['lat']=$coords['lon']='';
$coords['status'] = $xml->status ;
if($coords['status']=='OK')
{
 $coords['lat'] = $xml->result->geometry->location->lat ;
 $coords['lon'] = $xml->result->geometry->location->lng ;
}
return $coords;
}

$coords=getXmlCoordsFromAdress(htmlspecialchars($_POST['adresse']));
//echo $coords['status']." ".$coords['lat']." ".$coords['lon'];

function get_distance_m($lat1, $lng1, $lat2, $lng2) {
  $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
  $rlo1 = deg2rad($lng1);
  $rla1 = deg2rad($lat1);
  $rlo2 = deg2rad($lng2);
  $rla2 = deg2rad($lat2);
  $dlo = ($rlo2 - $rlo1) / 2;
  $dla = ($rla2 - $rla1) / 2;
  $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo
));
  $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
  return ($earth_radius * $d);
}


// Connexion à la base de données

if ($coords['status'] == 'OK') {

	

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

            $compteur=0;

            $reponse = $bdd->query('SELECT latitude, longitude FROM poubelle');

            //if ((round(get_distance_m($coords['lat'], $coords['lon'], 48.725227, 2.166109) / 1000, 3))<60.OOO) {  
            
            //on vérifie que la nouvelle poubelle est située à moins de 60km à vol d'oiseau du point de départ
            
            
                while ($donnees = $reponse->fetch())
                {
                	
                    if ($coords['lat']!=$donnees['latitude'] && $coords['lon']!=$donnees['longitude']) {
                    	$compteur ++;
                    }
                }
                $reponse->closeCursor();



    	if ($compteur == $nbPoubelle) {


    	$req = $bdd->prepare('INSERT INTO poubelle(id, adresse, latitude, longitude) VALUES(:id, :adresse, :latitude, :longitude)');
    	$req->execute(array(
            'id' => $compteur+1,
    		'adresse' => htmlspecialchars($_POST['adresse']),
    		'latitude' => $coords['lat'],
    		'longitude' => $coords['lon']
    		));

    	}
    //}
}


header('Location: prototype.php');

?>