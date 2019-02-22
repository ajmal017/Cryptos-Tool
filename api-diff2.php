<?php 

require_once '/mnt/data/www/renta/html/jsonRPCClient.php'; 
include("/mnt/data/www/renta/html/fonctions/connexion.php");
include("/mnt/data/www/renta/html/fonctions/fonctions.php");


$couples=explode(",",$_GET['currencies']);
$ok=false;
foreach ($couples as $value) {
	if($ok==false){
		
	$devise=explode(":",$value);
	$devisecourt=explode("-",$devise[0]);
$ini_req=mysqli_query($wikeo,"SELECT user,pass,ip,port,id_crypto FROM cryptos WHERE sigle='".$devisecourt[0]."'");
$vernb=mysqli_num_rows($ini_req);


if($vernb!=0){
$crypto=mysqli_fetch_assoc($ini_req);
$ini_req2="SELECT multi,multi2,id_crypto FROM cryptos_algos WHERE sigle='".$devise[0]."'";
$cryptalgo=mysqli_fetch_assoc(mysqli_query($wikeo,$ini_req2));


$ini_dve = new jsonRPCClient("http://".$crypto['user'].":".$crypto['pass']."@".$crypto['ip'].":".$crypto['port']."/");
$dve = $ini_dve->getinfo();
$curblock=$dve['blocks'];
$req_rew=mysqli_query($wikeo,"SELECT * FROM blocs WHERE $curblock BETWEEN debut AND fin AND id_crypto=".$crypto['id_crypto']); 
$rew=mysqli_fetch_assoc($req_rew);
$reward=$rew['reward'];


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


	echo $devise[0]." ||| Bloc courant : ".$curblock." / Reward : ".$reward." / Diff : ".$diff."<br>";


}




	}
}

if($ok==false){echo $devise[0];}
?>