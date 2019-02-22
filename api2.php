<?php 
$force="";
if($force==""){
require_once '/mnt/data/www/renta/html/jsonRPCClient.php'; 
include("/mnt/data/www/renta/html/fonctions/connexion.php");
include("/mnt/data/www/renta/html/fonctions/fonctions.php");


$devises=explode(",",$_GET['currencies']);
$i=0;
foreach ($devises as &$value) {
	if($i==0){$requ="sigle='$value'";}
    $requ.=" OR sigle='$value'";
	$i++;
}

$base="C".$_GET['carte'];

$ini_req="SELECT sigle FROM $base WHERE $requ ORDER by gainbtc DESC LIMIT 1";

$req=mysqli_fetch_assoc(mysqli_query($wikeo,$ini_req));
echo $req['sigle'];
}else{
	echo $force;
}


?>