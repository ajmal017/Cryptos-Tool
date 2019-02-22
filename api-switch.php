<?php 

require_once '/mnt/data/www/renta/html/jsonRPCClient.php'; 
include("/mnt/data/www/renta/html/fonctions/connexion.php");
include("/mnt/data/www/renta/html/fonctions/fonctions.php");


$crypto=explode(",",$_GET['currencies']);





$ok=false;
$nbcryptos=sizeof($crypto);
$n=0;
$stop_boucle=0;



foreach ($crypto as $value) {
if($stop_boucle==0){
	
	if($ok==true){
echo "1";
$monfichier = fopen('encours.txt', 'r+');
fputs($monfichier, $value); // On écrit le nouveau nombre de pages vues
fclose($monfichier);
$stop_boucle=1;
		echo $value;

		
		}else{
			echo "2";
$monfichier = fopen('encours.txt', 'r+');
$cryptoencours = fgets($monfichier); // On lit la première ligne (nombre de pages vues)
fclose($monfichier);
	if($value==$cryptoencours AND $n!=$nbcryptos){
		$ok=true;
	

	echo "3";

	}}
	
	$n++;
	}}

		if($ok==false){
			echo "4";

$monfichier = fopen('encours.txt', 'r+');
fputs($monfichier, $crypto[0]); // On écrit le nouveau nombre de pages vues
fclose($monfichier);
echo $crypto[0];

		}

		
$cryptencours="";	
?>