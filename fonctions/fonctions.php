<?php

function encode($str){
	return md5(sha1($str));
}
function hashrate($hashrate){
if($hashrate>=1000000000){$hash=$hashrate/1000000000;$unite="Gh";}elseif($hashrate>=1000000){$hash=$hashrate/1000000;$unite="Mh";}elseif($hashrate>=1000){$hash=$hashrate/1000;$unite="Kh";}else{$hash=$hashrate;$unite="H";}
$hashage['hash']=$hash;
$hashage['unite']=$unite;
return $hashage;
}




function maj($wikeo){
	
$iniver=mysqli_query($wikeo,"SELECT * FROM historique_btc order by moment DESC LIMIT 1");
$btc=mysqli_fetch_assoc($iniver);
$btc=$btc['valeur'];
$prix=0.138;	

	$jsona = file_get_contents("http://www.whattomine.com/coins.json");
$tableau = json_decode($jsona,true);
	
	$json2 = file_get_contents("https://api.kraken.com/0/public/Ticker?pair=XXBTZEUR");
$json_output2 = json_decode($json2,true);
$btck=round($json_output2['result']['XXBTZEUR']['c'][0]);

$json3=file_get_contents("https://btc-e.com/api/2/btc_eur/ticker");
$json_output3 = json_decode($json3,true);
$btcb=round($json_output3['ticker']['buy']);

if($btcb>$btck){$btc=$btcb;}else{$btc=$btck;}
	
	
$ins_tab2="INSERT INTO historique_btc (valeur,moment)";
$ins_tab2.="VALUES ('$btc',now())";
$tab2=mysqli_query($wikeo,$ins_tab2);

	
//On met a jour le block reward pour les cryptos en question
/*Obsolete : On verifie maintenant directement dans la table blocs pour toutes les cryptos


$inimajcrypto=mysqli_query($wikeo,"SELECT * FROM cryptos WHERE sigle='XST'");
$crypto=mysqli_fetch_assoc($inimajcrypto);
$ini_dve = new jsonRPCClient("http://".$crypto['user'].":".$crypto['pass']."@".$crypto['ip'].":".$crypto['port']."/");
$dve = $ini_dve->getinfo();
$block=$dve['blocks'];
if($block>=1700){$majblock=4000;}
if($block>=3140){$majblock=2000;}
if($block>=4580){$majblock=1000;}
if($block>=6020){$majblock=500;}
if($block>=7460){$majblock=250;}
if($block>=10340){$majblock=125;}
if($block>=11780){$majblock=31.25;}
if($block>=13220){$majblock=20.4;}
$update=mysqli_query($wikeo,"UPDATE cryptos SET bloc='$majblock' WHERE sigle='XST'");


$inimajcrypto=mysqli_query($wikeo,"SELECT * FROM cryptos WHERE sigle='FRSH'");
$crypto=mysqli_fetch_assoc($inimajcrypto);
$ini_dve = new jsonRPCClient("http://".$crypto['user'].":".$crypto['pass']."@".$crypto['ip'].":".$crypto['port']."/");
$dve = $ini_dve->getinfo();
$block=$dve['blocks'];
if($block>=4320){$majblock=250;}
if($block>=8641){$majblock=1000;}
if($block>=10081){$majblock=10;}
$update=mysqli_query($wikeo,"UPDATE cryptos SET bloc='$majblock' WHERE sigle='FRSH'");


$inimajcrypto=mysqli_query($wikeo,"SELECT * FROM cryptos WHERE sigle='NOAH'");
$crypto=mysqli_fetch_assoc($inimajcrypto);
$ini_dve = new jsonRPCClient("http://".$crypto['user'].":".$crypto['pass']."@".$crypto['ip'].":".$crypto['port']."/");
$dve = $ini_dve->getinfo();
$block=$dve['blocks'];
//echo $block;
if($block<=10000){$majblock=100;}
if($block>=10001){$majblock=150;}
if($block>=20001){$majblock=200;}
if($block>=30001){$majblock=150;}
if($block>=40001){$majblock=100;}
if($block>=50001){$majblock=200;}
if($block>=50001){$majblock=0;}
$update=mysqli_query($wikeo,"UPDATE cryptos SET bloc='$majblock' WHERE sigle='NOAH'");


*/



///on vide les tables de renta de chaque carte avant de les mettre à jour
/*
$ini_cartes=mysqli_query($wikeo,"SELECT * FROM cartes WHERE renta=1");
while($cartes=mysqli_fetch_assoc($ini_cartes)){ 
mysqli_query($wikeo,"TRUNCATE TABLE C".$cartes['nom']);
}
*/


///API Poloniex : retourne l'ensemble 
$json = file_get_contents("https://poloniex.com/public?command=returnTicker");
$json_output_polo = json_decode($json,true);



///on parcours la table des crypto afin d'aller chercher le cours puis les les differents algos possibles de chaque crypto
$ini_crypto=mysqli_query($wikeo,"SELECT * FROM cryptos WHERE actif=1");
//$ini_crypto=mysqli_query($wikeo,"SELECT * FROM cryptos WHERE sigle='XMR'");
while($crypto=mysqli_fetch_assoc($ini_crypto)){
	echo $crypto['nom']."<br>";
$exchange="";
$bid="";		
	
///On verifie si le cours n'est pas fixé en statique
if($crypto['coursprev']==""){
/////API Bittrex : on releve le bid de la crypto, on passe le market bittrex a zero si la crypto n'est pas trouvée	

$json2 = file_get_contents("https://bittrex.com/api/v1.1/public/getticker?market=BTC-".$crypto['sigle']);

$json_output2 = json_decode($json2,true);
if($json_output2['success']==1){
$market_bittrex=1;
$bid_bittrex=$json_output2['result']['Bid'];

}else{
$market_bittrex=0;
$bid_bittrex=0;
}


/////API Mintpal : on releve le bid de la crypto, on passe le market mintpal a zero si la crypto n'est pas trouvée

/*
$url = "https://api.mintpal.com/v1/market/stats/".$crypto['sigle']."/BTC";
$headers = @get_headers($url);
if(strpos($headers[0],'404') === false)
{
$json = file_get_contents("https://api.mintpal.com/v1/market/stats/".$crypto['sigle']."/BTC");
$json_output = json_decode($json,true);

$bid_mintpal=$json_output[0]['top_bid'];
$market_mintpal=1;
}else{

}

*/
$market_mintpal=0;
$bid_mintpal=0;
/////on releve le bid de la crypto dans l'ensemble du ticker relevé avant la boucle, on passe le market polo a zero si la crypto n'est pas trouvée
//$json = file_get_contents("https://poloniex.com/public?command=returnTicker");
//$json_output = json_decode($json,true);
$mark="BTC_".$crypto['sigle'];
$bid_polo= $json_output_polo[$mark]['highestBid'];
if($json_output_polo[$mark]['highestBid']!=""){
$market_polo=1;
$bid_polo= $json_output_polo[$mark]['highestBid'];

}else{
$market_polo=0;
$bid_polo=0;
}


///Si la crypto est trouvée dans sur cryptoin

		  $data = file_get_contents("https://cryptoine.com/api/1/ticker/".strtolower($crypto['sigle'])."_btc" );
   $tab = explode(':', $data);
  $bid=floatval($tab[6]);
if($bid_cryptoine!=""){
	$market_cryptoine=1;
$bid_cryptoine= $bid;

}else{
$market_cryptoine=0;
$bid_cryptoine=0;
}


///Si la crypto est trouvée dans sur allcoin

  $json = file_get_contents("https://www.allcoin.com/api2/pair/".$crypto['sigle']."_BTC");
$json_output = json_decode($json,true);
//var_dump($json_output);
  
if($json_output['data']!="pair not exists"){
$market_allcoin=1;
$bid_allcoin=$json_output['data']['top_bid'];

}else{
$market_allcoin=0;
$bid_allcoin=0;
}


///Api C-CEX
if($crypto['sigle']!="MYR"){
$json = file_get_contents("https://c-cex.com/t/".strtolower($crypto['sigle'])."-btc.json");
$json_output = json_decode($json,true);
  
if($json_output['ticker']['buy']!=""){
$market_ccex=1;
$bid_ccex=$json_output['ticker']['buy'];

}else{
$market_ccex=0;
$bid_ccex=0;
}
}
$market_ccex=0;
$bid_ccex=0;


//API bleutrad
/*
$json = file_get_contents("https://bleutrade.com/api/v2/public/getticker?market=".$crypto['sigle']."_BTC");
$json_output = json_decode($json,true);
  
if($json_output['success']==true){
$market_bleutrade=1;
$bid_bleutrade=$json_output['result']['0']['Bid'];

}else{
	*/
$market_bleutrade=0;
$bid_bleutrade=0;
//}
$pageDocument = @file_get_contents('https://api.coin-swap.net/market/stats/XMR/BTC');

if ($pageDocument === false) {
   echo "test";
}else{echo
"url ok";
}


$json = @file_get_contents("https://api.coin-swap.net/market/stats/".$crypto['sigle']."/BTC");
if ($json === false) {
	$market_swap=0;
$bid_swap=0;
}else{
$json_output = json_decode($json,true);
//$json_output = json_decode($json);
//var_dump($json_output);

$market_swap=1;
$bid_swap=$json_output['bid'];

}


/*
$json=file_get_contents("https://btc-e.com/api/2/".strtolower($crypto['sigle'])."_btc/ticker?ignore_invalid=1");
$json_output = json_decode($json,true);
//$json_output = json_decode($json);
//var_dump($json_output);
if(isset($json_output['ticker']['buy'])){
$market_btce=1;
$bid_btce=$json_output['ticker']['buy'];

}else{
*/
$market_btce=0;
$bid_btce=0;
//}




/*
$json = file_get_contents("https://alcurex.org/api/market.php?pair=".$crypto['sigle']."_btc&price=buy");
if($json!=""){
$bida= explode(",",$json);
$bida=explode(":",$bida[2]);
$market_alcurex=1;
$bid_alcurex=$bida[1];

}else{
	*/
$market_alcurex=0;
$bid_alcurex=0;
//}



//echo $crypto['sigle']." - Bid CCex : ".$bid_ccex."<br>";
//API Bter
/*
$json = file_get_contents("http://data.bter.com/api/1/ticker/".$crypto['sigle']."_BTC");
$json_output = json_decode($json,true);

if($json_output['buy']!=""){
$market_bter=1;
$bid_bter=$json_output['buy'];

}else{
$market_bter=0;
$bid_bter=0;
}
*/

///Si la crypto est trouvée dans un des exchange on recherche le meilleur Bid
if($market_bittrex==1 OR $market_mintpal==1 OR $market_polo==1 OR $market_cryptoine==1 OR $market_allcoin==1 OR $market_ccex==1 OR $market_bter==1 OR $market_bleutrade==1 OR $market_swap==1 OR $market_alcurex==1 OR $market_btce==1){
$bid=0;
if($bid_bittrex>$bid){
	$bid=$bid_bittrex;
	$exchange="Bittrex";
}

if($bid_mintpal>$bid){
	$bid=$bid_mintpal;
	$exchange="Mintpal";
}

if($bid_polo>$bid){
	$bid=$bid_polo;
	$exchange="Poloniex";
}

if($bid_cryptoine>$bid){
	$bid=$bid_cryptoine;
	$exchange="Cryptoine";
}

if($bid_allcoin>$bid){
	$bid=$bid_allcoin;
	$exchange="Allcoin";
}
if($bid_ccex>$bid){
	$bid=$bid_ccex;
	$exchange="C-cex";
}

if($bid_bter>$bid){
	$bid=$bid_bter;
	$exchange="Bter";
}
if($bid_bleutrade>$bid){
	$bid=$bid_bleutrade;
	$exchange="Bleutrade";
}
if($bid_swap>$bid){
	$bid=$bid_swap;
	$exchange="Swap";
}

if($bid_alcurex>$bid){
	$bid=$bid_alcurex;
	$exchange="Alcurex";
}

if($bid_btce>$bid){
	$bid=$bid_btce;
	$exchange="Btce";
}




}else{
	$bid=0;
	$exchange="";
}

//echo $crypto['sigle']." : ".$exchange."<br>";



}else{
	
	$bid=$crypto['coursprev'];
	$exchange="Statique";

}


//echo $crypto['sigle']." ".$exchange."<br>";


//on se connecte au wallet avec les info récupérer de la table de la crypto principale
if($crypto['cryptonight']==0){
$ini_dve = new jsonRPCClient("http://".$crypto['user'].":".$crypto['pass']."@".$crypto['ip'].":".$crypto['port']."/");
$dve = $ini_dve->getinfo();
$dve2 = $ini_dve->getmininginfo();
$balance = $dve['balance'];
$curblock=$dve['blocks'];


//On verifie le reward en fonction du bloc courant

$req_rew=mysqli_query($wikeo,"SELECT * FROM blocs WHERE $curblock BETWEEN debut AND fin AND id_crypto=".$crypto['id_crypto']); 
$rew=mysqli_fetch_assoc($req_rew);

$reward=$rew['reward'];
}

//On recherche tous les algos possibles pour la crypto


$ini_algo=mysqli_query($wikeo,"SELECT c.id_crypto_algo,c.sigle,c.nom,c.multi,c.multi2,a.id,a.algo FROM cryptos_algos c LEFT JOIN algos a ON c.algo=a.id WHERE c.actif='1' AND c.id_crypto=".$crypto['id_crypto']);


while($cryptalgo=mysqli_fetch_assoc($ini_algo)){ 

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


///On renseigne la table historique pour le suivi de la diff et du cours
$ins_tab2="INSERT INTO historique (idcrypto,diff,bid,moment)";
$ins_tab2.="VALUES ('".$cryptalgo["id_crypto_algo"]."','$diff','$bid',now())";
$tab2=mysqli_query($wikeo,$ins_tab2);
echo $cryptalgo["sigle"]." : ".$ins_tab2."<br>";

if($crypto['sigle']=="AID"){
$reward=round(8 * $diff / (525600+($curblock-1)) * 525600);
}
//echo $crypto['sigle'].":".$reward."<br>";
$ini_cartes=mysqli_query($wikeo,"SELECT * FROM cartes WHERE renta=1");
			while($carte=mysqli_fetch_assoc($ini_cartes)){

				  $ini_hash=mysqli_query($wikeo,"SELECT * FROM hashrates WHERE idcarte=".$carte['id']." AND idalgo=".$cryptalgo['id']);
				  $hash=mysqli_fetch_assoc($ini_hash);
				  

	
	
	
				  if($hash['hashrate']!=""){
					  
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
			/*	  
$ins_tab="INSERT INTO C".$carte['nom']." (sigle,nom,algo,diff,bloc,hashrate,nombre,bid,exchange,gainbtc";

if($carte['nom']=="RIG_MAISON" OR $carte['nom']=="RIG_ATR" AND $hashrate==true){
$ins_tab.=",bidprev,gainprev";
}
$ins_tab.=")";
$ins_tab.="VALUES ('".$cryptalgo["sigle"]."','".$crypto["nom"]."','".$cryptalgo["algo"]."','".round($diff,3)."','$reward','".$hash['hashrate']."','$nbcoins','".$bid."','$exchange','$gainbtc'";

if($carte['nom']=="RIG_MAISON" OR $carte['nom']=="RIG_ATR" AND $hashrate==true){
$gainprev=round($crypto['coursprev']*$nbcoins,4);
$ins_tab.=",'".$crypto['coursprev']."','$gainprev'";
}
$ins_tab.=")";
$tab=mysqli_query($wikeo,$ins_tab);
*/
//echo "SELECT sigle FROM C".$carte['nom']." WHERE sigle='".$cryptalgo["sigle"]."'";
$ver_algo=mysqli_query($wikeo,"SELECT sigle FROM C".$carte['nom']." WHERE sigle='".$cryptalgo["sigle"]."'");
$nbalgo=mysqli_num_rows($ver_algo);
$diff=round($diff,3);
if($nbalgo==0){
$ins_tab="INSERT INTO C".$carte['nom']." (id_crypto,sigle,nom,algo,diff,bloc,hashrate,nombre,bid,exchange,gainbtc,maj,cout,gainnet)";
$ins_tab.="VALUES ('".$crypto["id_crypto"]."','".$cryptalgo["sigle"]."','".$crypto["nom"]."','".$cryptalgo["algo"]."','$diff','$reward','".$hash['hashrate']."','$nbcoins','".$bid."','$exchange','$gainbtc',now(),'$cout','$gainnet')";
//echo $ins_tab."<br>";
$tab=mysqli_query($wikeo,$ins_tab);	
}else{
	//echo "UPDATE C".$carte['nom']." SET id_crypto='".$crypto["id_crypto"]."',diff='$diff',bloc='$reward',hashrate='".$hash['hashrate']."',nombre='$nbcoins',bid='$bid',exchange='$exchange',gainbtc='$gainbtc',maj=now() WHERE sigle='".$cryptalgo["sigle"]."'";
$update=mysqli_query($wikeo,"UPDATE C".$carte['nom']." SET id_crypto='".$crypto["id_crypto"]."',diff='$diff',bloc='$reward',hashrate='".$hash['hashrate']."',nombre='$nbcoins',bid='$bid',exchange='$exchange',gainbtc='$gainbtc',maj=now(),cout='$cout',gainnet='$gainnet' WHERE sigle='".$cryptalgo["sigle"]."'");
}


		}
  
			}










/*
echo $crypto['nom']." / ".$cryptalgo['algo']."<br>";
echo "diff : ".$diff;
echo $bid."<br>";
echo $exchange."<br><br>";
*/
//fin de la boucle cryptos_algos
}
if($crypto['cryptonight']==0){
$valeur=$bid*$balance;
$bloc=$dve['blocks'];
$version=$dve['version'];
$connect=$dve['connections'];
$update=mysqli_query($wikeo,"UPDATE cryptos SET version='$version',bloc='$bloc',connect='$connect',diff='$diff',montant_wallet='$balance',valeur='$valeur',exchange='$exchange' WHERE id_crypto='".$crypto["id_crypto"]."'");
echo "UPDATE cryptos SET version='$version',bloc='$bloc',connect='$connect',diff='$diff',montant_wallet='$balance',valeur='$valeur',exchange='$exchange' WHERE id_crypto='".$crypto["id_crypto"]."'";
}
///fin de la boucle des cryptos principales
}
//fin de la fonction maj
}









function datefr($dte,$long){
$jour=date('N',strtotime($dte));
$mois=date('n',strtotime($dte));
	
$date_complete=date("Y-m-j H:i:s", strtotime($dte));
$heure=explode(" ",$date_complete);
$heure=explode(":",$heure[1]);
$heure=$heure[0]."h".$heure[1];

 setlocale(LC_TIME, 'french');
 //$dte=date("Y-m-d",$dte);

 //return date('H:i:s', $dte);
//return strftime('%A %d %B %Y', strtotime($dte))."&nbsp;&agrave;&nbsp;".$heure;	

if($long=="court"){
$j=array("", "Lun", "Mar", "Mer", "Jeu", "Vend", "Sam", "Dim");
$m=array("", "Janv", "Fev", "Mars", "Avril", "Mai", "Juin", "Juil", "Aout", "Sept", "Oct", "Nov", "Dec");
}else{
$j=array("", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
$m=array("", "Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
}
        if ($jour>0&&$jour<8) {
            $jour=$j[$jour];
        } else {
            $jour=$jour;
        }
        if ($mois>0&&$mois<13) {
            $mois=$m[$mois];
        } else {
            return $mois;
        }
		
return $jour."&nbsp;".date('j',strtotime($dte))."&nbsp;".$mois."&nbsp;".date('Y',strtotime($dte))."&nbsp;&agrave;&nbsp;".$heure;		
}




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function MDP ($longueur = 8){
    // initialiser la variable $mdp
    $mdp = "";
 
    // Définir tout les caractères possibles dans le mot de passe,
    // Il est possible de rajouter des voyelles ou bien des caractères spéciaux
    $possible = "2346789_%-bcdfghj_%-kmnpqrtvw_%-xyzBCDFG_%-HJKLMNP_%-QRTVWXYZ_%-";
 
    // obtenir le nombre de caractères dans la chaîne précédente
    // cette valeur sera utilisé plus tard
    $longueurMax = strlen($possible);
 
    if ($longueur > $longueurMax) {
        $longueur = $longueurMax;
    }
 
    // initialiser le compteur
    $i = 0;
 
    // ajouter un caractère aléatoire à $mdp jusqu'à ce que $longueur soit atteint
    while ($i < $longueur) {
        // prendre un caractère aléatoire
        $caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);
 
        // vérifier si le caractère est déjà utilisé dans $mdp
        if (!strstr($mdp, $caractere)) {
            // Si non, ajouter le caractère à $mdp et augmenter le compteur
            $mdp .= $caractere;
            $i++;
        }
    }
 
    // retourner le résultat final
    return $mdp;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//verif perm sur un thread
function perm_question($wikeo,$id_question, $id_cat, $user_groupe, $user){
	if($id_question!=""){
			$quer_droits2=mysqli_query($wikeo,"SELECT * FROM questions_droits WHERE id_question=$id_question AND (id_groupe=$user_groupe OR id_util=$user)");
			$nbperm2=mysqli_num_rows($quer_droits2);
			if($nbperm2==0){$perm['question']=false;}else{$perm['question']=true;}
	}
	
	if($id_cat!=""){
$quer_droits=mysqli_query($wikeo,"SELECT * FROM categories_droits WHERE id_cat=$id_cat AND (id_groupe=$user_groupe OR id_util=$user)");
$test=mysqli_num_rows($quer_droits);
$perm['categorie']=$test;
	}
			 
			
return $perm;			
}



function smile($texte)
{
//Smileys
$texte = str_replace(':D ', '<img src="./images/smileys/heureux.gif" title="heureux" alt="heureux" />', $texte);
$texte = str_replace(':lol: ', '<img src="./images/smileys/lol.gif" title="lol" alt="lol" />', $texte);
$texte = str_replace(':triste:', '<img src="./images/smileys/triste.gif" title="triste" alt="triste" />', $texte);
$texte = str_replace(':frime:', '<img src="./images/smileys/cool.gif" title="cool" alt="cool" />', $texte);
$texte = str_replace(':rire:', '<img src="./images/smileys/rire.gif" title="rire" alt="rire" />', $texte);
$texte = str_replace(':s', '<img src="./images/smileys/confus.gif" title="confus" alt="confus" />', $texte);
$texte = str_replace(':O', '<img src="./images/smileys/choc.gif" title="choc" alt="choc" />', $texte);
$texte = str_replace(':question:', '<img src="./images/smileys/question.gif" title="?" alt="?" />', $texte);
$texte = str_replace(':exclamation:', '<img src="./images/smileys/exclamation.gif" title="!" alt="!" />', $texte);
//Mise en forme du texte
//gras
$texte = preg_replace('`\[g\](.+)\[/g\]`isU', '<strong>$1</strong>', $texte); 
//italique
$texte = preg_replace('`\[i\](.+)\[/i\]`isU', '<em>$1</em>', $texte);
//souligné
$texte = preg_replace('`\[s\](.+)\[/s\]`isU', '<u>$1</u>', $texte);
//lien
$texte = preg_replace('#http://[a-z0-9._/-]+#i', '<a href="$0">$0</a>', $texte);
//etc., etc.
//On retourne la variable texte
$texte = preg_replace('`\[quote\](.+)\[/quote\]`isU', '<div id="quote">$1</div>', $texte);
return $texte;
}




function graph_general($table,$denom,$champs,$type,$width,$height,$serveur,$util,$mdp){
	$wikeo = @mysqli_connect($serveur, $util, $mdp, 'rentas');
	foreach($champs as $id_titles){
$quer=mysqli_query($wikeo,"SELECT id,$denom FROM $table WHERE id=$id_titles");
$nom_title=mysqli_fetch_assoc($quer);
$titres[]=$nom_title['nom'];
	
$r_sem=mysqli_query($wikeo,"SELECT id_hash,hashrate FROM hashrates WHERE idcarte=$id_titles AND idalgo='$type'");
$hash=mysqli_fetch_assoc($r_sem);


$hashrate=$hash['hashrate'];
$hash=$hashrate/1000;$unite="Mh";

$hashrates[]=$hash;
	
}
$serialized_titres = urlencode(serialize($titres));
$serialized_semaines = urlencode(serialize($hashrates));
return "<img src='../graph.php?cartes=".$serialized_titres."&hashrates=".$serialized_semaines."&largeur=$width&hauteur=$height&'>";

}

function tag($message){
	//$question = eregi_replace("([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])",
//"<A HREF=\"\\1://\\2\\3\" TARGET=\"_blank\">\\1://\\2\\3</A>",$message);
    $question = $message;
	$question=nl2br($question);$question = str_replace("[quote]", "<div class='quote'>", $question);$question = str_replace("[/quote]", "</div>", $question);

return $question;
}



function tri_colonneb($lien,$col,$af_col,$se,$classe,$encours){
	
	if($encours==$col){
	if($se=="ASC"){ 
	
	//$fleche="<img src='/images/fleche_haut3.png' border='0' align='absmiddle'/>";}else{$fleche="<img src='/images/fleche_bas3.png' border='0'/>";}
	//$fleche="<div3.png' border='0' align='absmiddle'/>";}else{$fleche="<img src='/images/fleche_bas3.png' border='0'/>";}

    $nouveau_lien="<div class='tri-colonne' style=\"background-image:url('/images/fond-haut.gif');backgroud-repeat:repaet-x;height:28px;padding-top:5px;cursor:pointer;cursor:hand;color:red;\" onClick=\"Javascript: window.location='$lien&colonne=$col&sens=$se'\" class='$classe'>$af_col</a></div>";}else{
	
	$nouveau_lien="<div class='tri-colonne' style=\"background-image:url('/images/fond-bas.gif');backgroud-repeat:repaet-x;height:28px;padding-top:5px;cursor:pointer;cursor:hand;color:red;\" onClick=\"Javascript: window.location='$lien&colonne=$col&sens=$se'\" class='$classe'>$af_col</a></div>";	
	}

	}else{
		if($col!=""){
		$se="ASC";
		$nouveau_lien="<div class='tri-colonne' style=\"height:28px;padding-top:5px;cursor:pointer;cursor:hand;\" onClick=\"Javascript: window.location='$lien&colonne=$col&sens=$se'\" class='$classe'>$af_col</a></div>";
		}else{
		$se="ASC";
		$nouveau_lien="<div class='tri-colonne' style=\"height:28px;padding-top:5px;cursor:pointer;\">$af_col</div>";	
		}
		
	}
	
	//$nouveau_lien="<a href='$lien?rubrique=$navigation1&srubrique=$navigation2&colonne=$col&sens=$se' class='$classe'>$fleche &nbsp; $af_col &nbsp; $fleche";
	return $nouveau_lien;
}















function change($wikeo){
	$ini_ver=mysqli_query($wikeo,"SELECT * FROM surveillance WHERE type=1");
while($ver=mysqli_fetch_assoc($ini_ver)){
	
$contenu=addslashes(htmlspecialchars(implode('', file($ver['url']))));
$contenu=str_replace('"',"|",$contenu);
$update=mysqli_query($wikeo,"UPDATE surveillance SET contenu='$contenu' WHERE titre='".$ver["titre"]."' AND type=2");

$ini_ver2=mysqli_query($wikeo,"SELECT * FROM surveillance WHERE titre='".$ver['titre']."' AND type=2");
$ver2=mysqli_fetch_assoc($ini_ver2);

if($ver2['contenu']==$ver['contenu']){
	echo "Pas de changement pour ".$ver['titre']."<br>";
	
	
}else{
echo "changement detect&eacute; pour".$ver['titre']."<br>";
$update=mysqli_query($wikeo,"UPDATE surveillance SET contenu='$contenu' WHERE titre='".$ver["titre"]."' AND type=1");
$sujet = "Modification sur ".$ver['titre'];
$body = "Bonjour<br />

Un changement a &eacute;t&eacute; d&eacute;tect&eacute; sur ".$ver['url'];

$entete="Content-type:text/html\nFrom:$expediteur";


envoi_mail($body,$body,"","renta@atr-ingenierie.fr","s.opros@gmail.com",$sujet);
	
}
}

}





function change2($wikeo){
	
	$mot="Out of stock";
	
$ini_ver=mysqli_query($wikeo,"SELECT * FROM surveillance WHERE type=1");
while($ver=mysqli_fetch_assoc($ini_ver)){
	
$contenu=addslashes(htmlspecialchars(implode('', file($ver['url']))));
$contenu=str_replace('"',"|",$contenu);


if (preg_match("/\b".$mot."\b/i", $ver['contenu']))
 {
	if (preg_match("/\b".$mot."\b/i", $contenu))
 {
	 echo "aucun changement";
 }else{
	 
$update=mysqli_query($wikeo,"UPDATE surveillance SET contenu='$contenu' WHERE titre='".$ver["titre"]."' AND type=1");
$sujet = "Dispo du ".$ver['titre'];
$body = "Bonjour<br />
".$ver['titre']."est maintenant disponible : ".$ver['url'];
$entete="Content-type:text/html\nFrom:$expediteur";
envoi_mail($body,$body,"","renta@atr-ingenierie.fr","s.opros@gmail.com",$sujet);
 }
 
}else{
	if (preg_match("/\b".$mot."\b/i", $contenu))
 {
$update=mysqli_query($wikeo,"UPDATE surveillance SET contenu='$contenu' WHERE titre='".$ver["titre"]."' AND type=1");
 }
}
}
}
?>