<?php 

require_once '/mnt/data/www/renta/html/jsonRPCClient.php'; 
include("/mnt/data/www/renta/html/fonctions/connexion.php");
include("/mnt/data/www/renta/html/fonctions/fonctions.php");


$monfichier = fopen('encours.txt', 'r+');
$cryptoencours = fgets($monfichier); // On lit la première ligne (nombre de pages vues)
fseek($monfichier, 0);
if($cryptoencours=="0XEC-K"){
echo "XEC-K";
fputs($monfichier, "0XEC-K"); // On écrit le nouveau nombre de pages vues
fclose($monfichier);
}


if($cryptoencours=="0XEC-K"){
echo "XEC-Q";
fputs($monfichier, "0XEC-K"); // On écrit le nouveau nombre de pages vues
fclose($monfichier);
}

	
?>