<?php 

require_once '/mnt/data/www/renta/html/jsonRPCClient.php'; 
include("/mnt/data/www/renta/html/fonctions/connexion.php");
include("/mnt/data/www/renta/html/fonctions/fonctions.php");



$ini_req="SELECT user,pass,ip,port FROM cryptos WHERE sigle='TTY'";
$crypto=mysqli_fetch_assoc(mysqli_query($wikeo,$ini_req));
$ini_req2="SELECT multi,multi2 FROM cryptos_algos WHERE sigle='TTY'";
$cryptalgo=mysqli_fetch_assoc(mysqli_query($wikeo,$ini_req2));


$ini_dve = new jsonRPCClient("http://".$crypto['user'].":".$crypto['pass']."@".$crypto['ip'].":".$crypto['port']."/");
$dve = $ini_dve->getinfo();



if($cryptalgo['multi']!=""){

if($cryptalgo['multi2']!=""){
	$multi=$cryptalgo['multi'];
	$multi2=$cryptalgo['multi2'];
	$diff=$dve[$multi][$multi2];
	
	}else{
		$multi=$cryptalgo['multi'];
		$diff=$dve[$multi];
		}

}else{
$diff=$ini_dve->getdifficulty();
}


if($diff<=3){
	echo "TTY";
	
}else{
	echo "OEC";
}


?>