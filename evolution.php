<?php

//on se connecte au wallet avec les info récupérer de la table de la crypto principale
$ini_dve = new jsonRPCClient("http://".$crypto['user'].":".$crypto['pass']."@".$crypto['ip'].":".$crypto['port']."/");
$dve = $ini_dve->getinfo();
$dve2 = $ini_dve->getmininginfo();

//On recherche tous les algos possibles pour la crypto

$ini_algo=mysqli_query($wikeo,"SELECT c.id_crypto_algo,c.sigle,c.nom,c.multi,a.id,a.algo FROM cryptos_algos c LEFT JOIN algos a ON c.algo=a.id WHERE c.actif='1'");


while($cryptalgo=mysqli_fetch_assoc($ini_algo)){ 

//on verifie si la crypto est une multi algos
if($cryptalgo['multi']!=""){
$multi=$cryptalgo['multi'];
$diff=$dve[$multi];
}else{
$diff=$ini_dve->getdifficulty();
}
//A verifier si ca marche pour la taille du bloc : $reward=$dve2['currentblocksize'];







$ini_cartes=mysqli_query($wikeo,"SELECT * FROM cartes");
			while($carte=mysqli_fetch_assoc($ini_cartes)){

				  $ini_hash=mysqli_query($wikeo,"SELECT * FROM hashrates WHERE idcarte=".$carte['id']." AND idalgo=".$cryptalgo['id']);
				  $hash=mysqli_fetch_assoc($ini_hash);
				  
				  
				  if($hash['hashrate']!=""){
				  $nbcoins= $crypto['bloc'] /($diff * (POW ( 2,32 )) / $hash['hashrate'] / 3600 / 24);
				 
				  }else{$nbcoins=0;}
				  $nbcoins=round($nbcoins);
				  
				  $gainbtc=round($bid*$nbcoins,4);


		if($hash['hashrate']!=""){		  
$ins_tab="INSERT INTO C".$carte['nom']." (sigle,nom,algo,diff,bloc,hashrate,nombre,bid,exchange,gainbtc)";
$ins_tab.="VALUES ('".$crypto["sigle"]."','".$crypto["nom"]."','".$cryptalgo["algo"]."','".round($diff,3)."','".$crypto['bloc']."','".$hash['hashrate']."','$nbcoins','".$bid."','$exchange','$gainbtc')";

$tab=mysqli_query($wikeo,$ins_tab);
		}
				  
			}






//fin de la boucle cryptos_algos
}

?>