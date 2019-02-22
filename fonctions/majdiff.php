<?php 
require_once '/volume1/web/jsonRPCClient.php'; 
include("/volume1/web/fonctions/connexion.php");
include("/volume1/web/fonctions/fonctions.php");

$jsona = file_get_contents("http://www.whattomine.com/coins.json");
$tableau = json_decode($jsona,true);

$iniver=mysqli_query($wikeo,"SELECT * FROM historique_btc order by moment DESC LIMIT 1");
$btc=mysqli_fetch_assoc($iniver);
$btc=$btc['valeur'];
$prix=0.138;

///on parcours la table des crypto afin d'aller chercher le cours puis les les differents algos possibles de chaque crypto
$ini_crypto=mysqli_query($wikeo,"SELECT * FROM cryptos WHERE actif=1");
//$ini_crypto=mysqli_query($wikeo,"SELECT * FROM cryptos WHERE sigle='XMR'");
while($crypto=mysqli_fetch_assoc($ini_crypto)){
	echo "<br><br><br>".$crypto['nom']."<br>";

//on se connecte au wallet avec les info récupérer de la table de la crypto principale
if($crypto['cryptonight']==0){
$ini_dve = new jsonRPCClient("http://".$crypto['user'].":".$crypto['pass']."@".$crypto['ip'].":".$crypto['port']."/");
$dve = $ini_dve->getinfo();
$dve2 = $ini_dve->getmininginfo();
$curblock=$dve['blocks'];


//On verifie le reward en fonction du bloc courant

$req_rew=mysqli_query($wikeo,"SELECT * FROM blocs WHERE $curblock BETWEEN debut AND fin AND id_crypto=".$crypto['id_crypto']); 
$rew=mysqli_fetch_assoc($req_rew);

$reward=$rew['reward'];
}

//On recherche tous les algos possibles pour la crypto


$ini_algo=mysqli_query($wikeo,"SELECT c.id_crypto_algo,c.sigle,c.nom,c.multi,c.multi2,a.id,a.algo FROM cryptos_algos c LEFT JOIN algos a ON c.algo=a.id WHERE c.actif='1' AND c.id_crypto=".$crypto['id_crypto']);


while($cryptalgo=mysqli_fetch_assoc($ini_algo)){ 

$reqbid=mysqli_query($wikeo,"SELECT bid FROM historique WHERE idcrypto=".$cryptalgo['id_crypto_algo']." order by moment DESC LIMIT 1");
$bidres=mysqli_fetch_assoc($reqbid);
$bid=$bidres['bid'];
echo "bid : ".$bid."<br>";

//on verifie si la crypto est une multi algos
//echo $cryptalgo['algo'];
if($cryptalgo['algo']!="CryptoNight"){
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
}else{

$coin=$cryptalgo['nom'];
$reward=$tableau['coins'][$coin]['block_reward'];
$diff=$tableau['coins'][$coin]['difficulty'];
}

	//echo "<br>".$crypto['sigle']." : ".$coin." / diff = ".$diff." reward = ".$reward;

echo $cryptalgo["sigle"]." : ".$ins_tab2."<br>";

if($crypto['sigle']=="AID"){
$reward=round(8 * $diff / (525600+($curblock-1)) * 525600);
}
//echo $crypto['sigle'].":".$reward."<br>";
$ini_cartes=mysqli_query($wikeo,"SELECT * FROM cartes WHERE renta=1");
			while($carte=mysqli_fetch_assoc($ini_cartes)){

				  $ini_hash=mysqli_query($wikeo,"SELECT * FROM hashrates WHERE idcarte=".$carte['id']." AND idalgo=".$cryptalgo['id']);
				  $ver_hash=mysqli_num_rows($ini_hash);
				  

	
	
	
				  if($ver_hash!=0){
					  $hash=mysqli_fetch_assoc($ini_hash);
					  $hashrate=true;
			if($cryptalgo['algo']!="CryptoNight"){
				  $nbcoins= $reward /($diff * (POW ( 2,32 )) / $hash['hashrate'] / 3600 / 24);
			}else{
				
				
				$nbcoins= ((60*60*24*$hash['hashrate'])/$diff)*$reward;
				
			}
				  }else{$nbcoins=0;$hashrate=false;}
				  
				  $nbcoins=round($nbcoins,5);
				  //echo $nbcoins."/";
				  $gainbtc=round($bid*$nbcoins,5);
				  
				 				  if($hash['conso']!=0){
					$cout_heure=($hash['conso']*$prix)/1000;
					$cout=round(($cout_heure*24),2);
					$grainbrut=$gainbtc*$btc;
					$gainnet=round(($grainbrut-$cout),2);
				  }else{
					 $cout=0;
					$gainnet=0;
					
				  }
echo "conso : ".$hash['conso']."*".$prix."/1000 = cout heure * 24 = ".$cout."<br>";

		if($hash['hashrate']!=""){

$ver_algo=mysqli_query($wikeo,"SELECT sigle FROM C".$carte['nom']." WHERE sigle='".$cryptalgo["sigle"]."'");
echo "SELECT sigle FROM C".$carte['nom']." WHERE sigle='".$cryptalgo["sigle"]."'"."<br>";
$nbalgo=mysqli_num_rows($ver_algo);
$diff=round($diff,3);
if($nbalgo!=0){
	$maj="UPDATE C".$carte['nom']." SET diff='$diff',bloc='$reward',hashrate='".$hash['hashrate']."',nombre='$nbcoins',bid='$bid',gainbtc='$gainbtc',maj=now(),nom='".$cryptalgo["nom"]."',cout='$cout',gainnet='$gainnet' WHERE sigle='".$cryptalgo["sigle"]."'";
	echo "maj".$cryptalgo["sigle"]."<br>";
$update=mysqli_query($wikeo,$maj);
}


		}
  
			}


}
}
//fin de la fonction maj


?>