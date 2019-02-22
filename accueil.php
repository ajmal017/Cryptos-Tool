<?php 
session_start();
if(isset($_SESSION['user'])){
require_once 'jsonRPCClient.php'; 
include("fonctions/connexion.php");
include("fonctions/fonctions.php");

if(isset($_GET["maj"])){ maj($wikeo);}

$up_id = uniqid(); 

function scat($gcat,$calque,$filtre,$serveur,$util,$mdp){
$response = new xajaxResponse();
$wikeo = @mysqli_connect($serveur, $util, $mdp, 'rentas');
if($gcat!="tout"){
$af="<select name=\"categories\" id=\"categories\">";
if($filtre=="1"){$af.="<option value=\"tout\">Tout afficher</option>";}
$rec_ocats=mysqli_query($wikeo,"SELECT id_cat,titre_cat FROM categories WHERE id_groupe_cat=$gcat order by titre_cat");
while($pcats=mysqli_fetch_assoc($rec_ocats)){

$af.="<option value=\"".$pcats['id_cat']."\">".$pcats['titre_cat']."</option>";
}
$af.="</select>";
}else{
	$af="";
}

$response->assign($calque, 'innerHTML', $af);

return $response;
}





function sopt($gopt,$calque,$filtre,$serveur,$util,$mdp){
$response = new xajaxResponse();
$wikeo = @mysqli_connect($serveur, $util, $mdp, 'rentas');
if($gopt!="tout"){
$af="<select name=\"options\" id=\"options\">";
if($filtre=="1"){$af.="<option value=\"tout\">Tout afficher</option>";}
$rec_ocats=mysqli_query($wikeo,"SELECT id_option,titre_option FROM options WHERE id_groupe_option=$gopt order by titre_option");
while($pcats=mysqli_fetch_assoc($rec_ocats)){

$af.="<option value=\"".$pcats['id_option']."\">".$pcats['titre_option']."</option>";
}
$af.="</select>";
}else{
	$af="";
}

$response->assign($calque, 'innerHTML', $af);

return $response;
}




                    
                   
function notifswork($notif,$question,$id_util,$calque,$serveur,$util,$mdp)
{
	    $response = new xajaxResponse();
	$wikeo = @mysqli_connect($serveur, $util, $mdp, 'rentas');
	if($notif==0){
		$ins_notif="INSERT INTO suivi_bloque (id_question,id_util)";
$ins_notif.="VALUES ('$question','$id_util')";
$wikeo->query($ins_notif);
		$image="
		<img src='images/off_mini.png' width='60' height='24' onclick='xajax_notifs(\"1\",\"$question\",\"$id_util\",\"$calque\",\"$serveur\",\"$util\",\"$mdp\")' style='cursor:pointer'>";
		}else{
			$dele=mysqli_query($wikeo,"DELETE FROM suivi_bloque WHERE id_question=$question AND id_util=$id_util");

			$image="
		<img src='images/on_mini.png' width='60' height='24' onclick='xajax_notifs(\"0\",\"$question\",\"$id_util\",\"$calque\",\"$serveur\",\"$util\",\"$mdp\")' style='cursor:pointer'>";
        }
		


$response->assign($calque, 'innerHTML', $image);

return $response;
}




function notifs($notif,$question,$id_util,$calque,$serveur,$util,$mdp)
{
	    $response = new xajaxResponse();
	$wikeo = @mysqli_connect($serveur, $util, $mdp, 'rentas');
	if($notif==0){
		$ins_notif="INSERT INTO suivi_active (id_question,id_util)";
$ins_notif.="VALUES ('$question','$id_util')";
$wikeo->query($ins_notif);
		$image="
		<img src='images/on_mini.png' width='60' height='24' onclick='xajax_notifs(\"1\",\"$question\",\"$id_util\",\"$calque\",\"$serveur\",\"$util\",\"$mdp\")' style='cursor:pointer'>";
		}else{
			$dele=mysqli_query($wikeo,"DELETE FROM suivi_active WHERE id_question=$question AND id_util=$id_util");

			$image="
		<img src='images/off_mini.png' width='60' height='24' onclick='xajax_notifs(\"0\",\"$question\",\"$id_util\",\"$calque\",\"$serveur\",\"$util\",\"$mdp\")' style='cursor:pointer'>";
        }
		


$response->assign($calque, 'innerHTML', $image);

return $response;
}








@require 'xajax_core/xajax.inc.php';
$xajax = new xajax(); // On initialise l'objet xajax.
$xajax->setCharEncoding('iso-8859-1');// On précise à xAjax qu'on souhaite travailler en ISO-8859-1.
$xajax->register(XAJAX_FUNCTION, 'notifs');// On enregistre nos fonctions.
$xajax->register(XAJAX_FUNCTION, 'notifswork');
$xajax->register(XAJAX_FUNCTION, 'scat');
$xajax->register(XAJAX_FUNCTION, 'sopt');
$xajax->processRequest();// Fonction qui va se charger de générer le Javascript, à partir des données que l'on a fournies à xAjax APRÈS AVOIR DÉCLARÉ NOS FONCTIONS.







if(isset($_POST['titre_nv_question'])){
	$titre=addslashes($_POST['titre_nv_question']);
	$question=addslashes($_POST['question']);
	$categorie=$POST['categorie'];
	
$ins_af="INSERT INTO questions (id_espace,id_cat_princ,id_util,date_question,titre,question,der_rep)";
$ins_af.="VALUES ('".$_SESSION["espace"]."','".$_SESSION["afcat"]['id']."','".$_SESSION["user"]."',now(),'$titre','$question',now())";
$wikeo->query($ins_af);
$recupnum=$wikeo->insert_id;

include("fonctions/fonctions_mails.php");


$ins_opt="INSERT INTO questions_options (id_question,id_option)";
$ins_opt.="VALUES ('$recupnum','".$_POST['options']."')";
$wikeo->query($ins_opt);


$ins_opt="INSERT INTO questions_categories (id_question,id_categorie)";
$ins_opt.="VALUES ('$recupnum','".$_POST['categories']."')";
$wikeo->query($ins_opt);

}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if(isset($_POST['reponse'])){
$reponse=addslashes($_POST['reponse']);
$ins_af="INSERT INTO reponses (id_question,id_util,date_reponse,reponse)";
$ins_af.="VALUES ('".$_SESSION["question"]."','".$_SESSION["user"]."',now(),'$reponse')";
$wikeo->query($ins_af);
$recupnum=$wikeo->insert_id;
$update=mysqli_query($wikeo,"UPDATE questions SET der_rep=now(),id_utilrep='".$_SESSION["user"]."', nb_posts=nb_posts+1 WHERE id_question='".$_SESSION["question"]."'");
include("fonctions/fonctions_mails.php");
}



if(isset($_POST['supgps'])){
	foreach($_POST['supgps'] as $cle=>$valeur){ 
$dele=mysqli_query($wikeo,"DELETE FROM questions_droits WHERE id_droit_question=$valeur");
}
}

if(isset($_POST['ajoutgps'])){
foreach($_POST['ajoutgps'] as $cle=>$valeur){ 
$ins_af="INSERT INTO questions_droits (id_question,id_groupe)";
$ins_af.="VALUES ('".$_SESSION["question"]."','$valeur')";
$wikeo->query($ins_af);
include("fonctions/fonctions_mails.php");
}
}






if(isset($_POST['titre_modif_question'])){
//	echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br>test";
$titre=addslashes($_POST['titre_modif_question']);
$question=addslashes($_POST['question']);
$cat=$_POST['cat'];
$option=$_POST['option'];
$options=$_POST['options'];
$categories=$_POST['categories'];

	
$update=mysqli_query($wikeo,"UPDATE questions SET titre='$titre', question='$question', edit=now() WHERE id_question='".$_SESSION["question"]."'");
$update2=mysqli_query($wikeo,"UPDATE questions_options SET id_option='$options' WHERE id_question_option='$option'");
$update3=mysqli_query($wikeo,"UPDATE questions_categories SET id_categorie='$categories' WHERE id_question_categorie='$cat'");
}


if(isset($_POST['modif_reponse'])){
	//echo "<br><br><br><br><br><br><br><br><br><br><br><br>test";
	$modif_reponse=addslashes($_POST['modif_reponse']);
	$update=mysqli_query($wikeo,"UPDATE reponses SET reponse='$modif_reponse', edit=now() WHERE id_reponse='".$_POST['id_reponse']."'");
}
	
	
	
	
	if(isset($_POST['reponse'])){
	$page=$nombreDePages;

}else{
$page = (isset($_GET['page']))?intval($_GET['page']):1;	
}


	
	
	
	
	
	?>
    
<!DOCTYPE HTML>
<html><head>

<?php

if($affiche=="hashrates" AND isset($_GET['algo'])){
if(!isset($_POST['chekcomp'])){
$chcomp_a=mysqli_query($wikeo,"SELECT * FROM cartes WHERE nom!='RIG_Mixte' AND nom!='RIG_ATR' AND nom!='Location' AND nom!='Asic' order by nom");
while($sp3_a=mysqli_fetch_assoc($chcomp_a)){
	$chekcomp[]=$sp3_a['id'];
}
}else{
	$chekcomp=$_POST['chekcomp'];
	//echo $_POST['chekcomp'];
}




$nb=0;
foreach($chekcomp as $id_titles){
$quer=mysqli_query($wikeo,"SELECT id,nom FROM cartes WHERE id=$id_titles");
$nom_title=mysqli_fetch_assoc($quer);
$titres[]=$nom_title['nom'];
	
$r_sem=mysqli_query($wikeo,"SELECT id_hash,hashrate,conso FROM hashrates WHERE idcarte=$id_titles AND idalgo=".$_GET['algo']);
$hash=mysqli_fetch_assoc($r_sem);

if(mysqli_num_rows($r_sem)==1){
$hashrate=$hash['hashrate'];
$hashd=$hashrate/1000000;$unite="Mh";
$conso=$hash['conso'];
}else{
	$hashd=0;
	$conso=0;
}
//echo $conso."<br>";

	$datas[]=$nom_title['nom'].":".$hashd.":".$conso;
	$nb++;
}
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">


	function popupcentree(page,largeur,hauteur,options) {
  var top=(screen.height-hauteur)/2;
  var left=(screen.width-largeur)/2;
  window.open(page,"","top="+top+",left="+left+",width="+largeur+",height="+hauteur+","+options);
}


      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Carte', 'Hashrate', 'Conso'],
		  <?php 
		  $vernb=0;
		  foreach($datas as $infos){
			  $vernb++;
			  $i=explode(":",$infos);
			  
         echo "['".$i[0]."',  ".$i[1].",  ".$i[2]."]";if($vernb<$nb){echo ",";}
		  }
		  ?>
		  

        ]);
		



        var options = {
			
			



         
		  animation:{
        duration: 1000,
        easing: 'out',
      },

          hAxis: {title: 'Cartes', titleTextStyle: {color: 'red'}},
		  
		   vAxes: {0: {gridlines: {color: 'transparent'},
format:"# Mh"},
1: {gridlines: {color: 'transparent'},
format:"# W"},
},
series: {0: {targetAxisIndex:0},
1:{targetAxisIndex:1},

},


        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
	  
	  
	  
    </script>
    
    
    <?php } ?>



<meta http-equiv="content-type" content="text/html;charset=ISO-8859-15"/>
<title>WIKEO : <?php echo $esp['titre'] ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
        <?php if($affiche=="listing" OR $affiche=="wallets"){ ?>
        <meta http-equiv="refresh" content="10" />
        <?php } ?>
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,600,700" rel="stylesheet" />
		<script src="js/jquery.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		  <link rel="stylesheet" href="css/styles_persos.css" />
		<noscript>

		</noscript>
        
            <script language="javascript">
                function newwindow() {
                    var showme = document.getElementById("filtres");
                     if( filtres.style.visibility == "hidden" )
    {
        filtres.style.visibility = "visible";
    }
    else
    {
        filtres.style.visibility = "hidden";
    }
	
	
                }
				
				function popupcentree(page,largeur,hauteur,options) {
  var top=(screen.height-hauteur)/2;
  var left=(screen.width-largeur)/2;
  window.open(page,"","top="+top+",left="+left+",width="+largeur+",height="+hauteur+","+options);
}
            </script>
            <script>

$(document).ready(function() { 
//

//show the progress bar only if a file field was clicked
	var show_bar = 0;
    $('input[type="file"]').click(function(){
		show_bar = 1;
    });

//show iframe on form submit
    $("#form1").submit(function(){

		if (show_bar === 1) { 
			$('#upload_frame').show();
			function set () {
				$('#upload_frame').attr('src','upload_frame.php?up_id=<?php echo $up_id; ?>');
			}
			setTimeout(set);
		}
    });
//

});

</script>

            <link href="style_progress.css" rel="stylesheet" type="text/css" />

<!--Get jQuery-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.js" type="text/javascript"></script>

<!--display bar only if file is chosen-->

			
			
        <?php $xajax->printJavascript(); /* Affiche le Javascript */?>
</head>

<body>
        <?php include("header.php"); 
$iniver=mysqli_query($wikeo,"SELECT * FROM historique_btc order by moment DESC LIMIT 1");
$btc=mysqli_fetch_assoc($iniver);
$btc=$btc['valeur'];
		
		?>
      
            
            <div class="container" >

			  <section class="box2" style='margin-top:69px;'>

                <?php if ($_SESSION['afcat']['id']==0 AND $affiche=="resume"){ ?>
<div style='font-size:16px;font-weight:bold;color:#2B7DAE;padding-top:10px'>Bienvenu Sur votre espace <?php echo $esp['titre'] ?></div>
<div style='font-size:16px;font-weight:bold;color:#2B7DAE;padding-top:10px'></div>
<div class='entete_sommaire'>Important : le principe</div>
<div class='texte_sommaire'>
  <p>Au contraire de Singman je n'ai pas voulu rendre publique cet espace. <br>
    Ceci pour plusieurs raisons : <br>
    - Le serveur n'est pas super puissant.<br>
    - La connexion n'est pas d&eacute;di&eacute;e.<br>
    - 
    Mon script n'est pas optimis&eacute; (j'ai quelques id&eacute;es pour am&eacute;liorer la chose, mais c'est pas encore fait.)<br>
    Je ne fais pas non plus d'annonce sur le forum HFR, je n'ai pas envie de g&eacute;rer les demandes et je pref&egrave;re inviter les personnes &agrave; mon initiative (ou via vos demandes sur le post d&eacute;di&eacute;). Merci donc de rester discret quant &agrave; l'existance de cet espace<br>
    <br>
    J'ai cr&eacute;&eacute; cet espace de toutes pi&egrave;ces, &agrave; la base c'est un de mes d&eacute;veloppements mis en place pour mon entreprise, j'ai repris l'interface et fait quelques modifs. Il n'a jamais &eacute;t&eacute; mis en production et ne poss&egrave;de pas toutes les fonctionnalit&eacute;s d'un forum, merci donc de votre comprehension.</p>
  <p>Lors de la cr&eacute;ation d' un nouveau sujet, vous verrez qu'il y a la possibilit&eacute; de choisir des cat&eacute;gorie et des options, celles-ci n'ont aucun rapport avec cet espace et je n'ai pas travaill&eacute; dessus, merci donc de ne rien choisir.<br>
    Je compte sur vous pour m'aider &agrave; trouver des cat&eacute;gories pertinantes (elles peuvent etre utiles ensuite pour appliquer des filtres sur les sujets)
  que je metterai en place par la suite.<br>
  </p>
</div>
<div class='entete_sommaire'>Liste des inscrits</div>
<div class='texte_sommaire'>
  <p><?php $ini_utils=mysqli_query($wikeo,"SELECT * FROM utilisateurs u LEFT JOIN espaces_droits e ON u.id_util=e.id_util LEFT JOIN groupes_utilisateur gu ON gu.id_util=u.id_util LEFT JOIN groupes g ON g.id_groupe=gu.id_groupe WHERE e.id_espace=".$_SESSION['espace']." AND prenom!='Mig6r2'");
						  while($row=mysqli_fetch_assoc($ini_utils)){echo $row['prenom']."&nbsp;|&nbsp;";} ?></p>
</div>
<div class='entete_sommaire'>API</div>
<div class='texte_sommaire'>
  <p>A venir</p>
</div>
             
              <div class='entete_sommaire'>Parametrez votre espace</div>
              <div class='texte_sommaire'>
               Les param&egrave;tres de votre espace peuvent &ecirc;tre configur&eacute;s en affichant le menu au survol de votre nom (en haut à droite de l'interface). Cliquez ensuite sur "Parametres"<br>
                  <br>
                  Vous pourrez alors configurer :<br>
                - Un nouveau mot de passe<br>
                - Le nombre de messages par page pour les r&eacute;ponses aux sujets.</div>
              <div class='entete_sommaire'>Fonctionnement des notifications mail</div>
              <div class='texte_sommaire'>
                <p>Lors de la cr&eacute;ation de votre compte, celui-ci est associ&eacute; &agrave; un profil de flux de notifications.<br>
                  Deux profils sont possibles :                <br>
                  <br>
                 - Flux total : Tous les nouveaux sujets ainsi que toutes les r&eacute;ponses aux sujets vous sont notifi&eacute;s par d&eacute;faut pour le sous syst&egrave;me auquel vous &ecirc;tes affect&eacute;.<br>
                 <br>
                 - Partiel : Par d&eacute;faut vous ne recevez de notification que pour les r&eacute;ponses sur les sujets dont vous &ecirc;tes le cr&eacute;ateur ou sur lesquels vous &ecirc;tes d&eacute;ja intervenu.<br>
                 <br>
                 Sur chaque sujet, les deux profils ont la possiblit&eacute; d'activer ou de bloquer les notifications en cliquant sur &quot;l'interrupteur&quot; ON/OFF situ&eacute; &agrave; droite
                de la liste des sujets.</p>
              </div>
               <?php }elseif ($_SESSION['afcat']['id']==1000){ 
			   
	/**		   
$json2 = file_get_contents("https://api.kraken.com/0/public/Ticker?pair=XXBTZEUR");
$json_output2 = json_decode($json2,true);
$btc=round($json_output2['result']['XXBTZEUR']['c'][0]);
*/



				  
			   ?>
<div style="float:right;margin-right:10px;margin-top:10px"><form name="form1" method="post" action="accueil.php?afcat=1000&affiche=listing">
<label for="btc"><span class="fa featured  fa-bar-chart" style='font-size:14px;display:inline;color:#61b8db;cursor:pointer;padding:3px' onClick="javascript:popupcentree('amchartbtc.php',1400,480,'menubar=no,scrollbars=yes,statusbar=no,location=no,toolbar=no,status=no')"></span> BTC:</label>
                  <input name="btc" type="text" id="btc" value="<?php echo $btc ?>" size="2">
                  &euro;
                 </form></div>  <div style='float:left'><div class='<?php if($affiche=="listing"){echo "boutonvert";}else{echo "bouton";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block;clear:both' onClick="Javascript: window.location='accueil.php?afcat=1000&affiche=listing'" >Tableau</div>
                 <div class='<?php if($affiche=="hashrates"){echo "boutonvert";}else{echo "bouton";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block;clear:both' onClick="Javascript: window.location='accueil.php?afcat=1000&affiche=hashrates'" >Hashrates</div>
                   <?php if($_SESSION['super_admin']==1 OR $_SESSION['admin']==1){ ?>
            <div class='bouton' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='accueil.php?afcat=1000&affiche=wallets'">Wallets</div>
            	<?php } ?><div class='bouton' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='accueil.php?afcat=1000&affiche=calcul'">Calcul</div></div>
                
                			
                
                
              <div style='font-size:16px;font-weight:bold;color:#2B7DAE;padding-top:10px;clear:both'>
              
   <?php if ($affiche=="listing"){ ?>                            
               <div class='entete_encarts'>Tableau des rentabilit&eacute;s</div>
                  
                <?php

$ini_cartes=mysqli_query($wikeo,"SELECT * FROM cartes WHERE renta=1 order by ordre ASC");
while($carte=mysqli_fetch_assoc($ini_cartes)){ 


if(!isset($_GET['colonne'])){
	
if(!isset($_SESSION['call']['colonne'])){
				$_SESSION['call']['colonne']='gainbtc';
				$_SESSION['call']['sens']='DESC';
			
				}
					}else{
						
						$_SESSION['call']['colonne']=$_GET['colonne'];
						$_SESSION['call']['sens']=$_GET['sens'];
					}
					
					
					
if($_SESSION['call']['sens']=='ASC'){$sens="ASC";$sens_invers="DESC";}else{$sens="DESC";$sens_invers="ASC";}
$colonne=$_SESSION['call']['colonne'];	


$order="order by $colonne $sens";


 

?><div style='float:left;margin-left:10px;margin-bottom:10px'>
<?php echo $carte['nom'];
?></div>
                <table width="100%" border="1" cellspacing="2" style="rentas">
                  <tr class="bandeau_cat" height='28px'>
                  <td width="2%" style='background-color:#FFF'></td>
                    <td width="5%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","sigle","Sigle",$sens_invers,"",$colonne); ?></span></td>
                    <td width="18%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","nom","Nom",$sens_invers,"",$colonne); ?></span></td>
                    <td width="4%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","algo","Algo",$sens_invers,"",$colonne); ?></span></td>
                    <td width="4%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","","Difficult&eacute;",$sens_invers,"",$colonne); ?></span></td>
                    <td width="5%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","bloc","Reward",$sens_invers,"",$colonne); ?></span></td>
                    <td width="6%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","","Hashrate",$sens_invers,"",$colonne); ?></span></td>
                    <td width="9%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","nombre","Nb Coins",$sens_invers,"",$colonne); ?></span></td>
                    <td colspan="2"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","bid","Bid",$sens_invers,"",$colonne); ?></span></td>
                    <td width="7%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","gainbtc","Gain BTC",$sens_invers,"",$colonne); ?></span></td>
                    <td width="7%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","gainbtc","Brut &euro;",$sens_invers,"",$colonne); ?></span></td>
                    <td width="7%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","cout","Cout &euro;",$sens_invers,"",$colonne); ?></span></td>
                    <td width="7%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","gainnet","Net &euro;",$sens_invers,"",$colonne); ?></span></td>
                     <td width="7%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","gainbtc","BTC/GH",$sens_invers,"",$colonne); ?></span></td>
                    <td width="13%"><span style="text-align: center"><?php echo tri_colonneb("accueil.php?afcat=1000&affiche=listing","maj","MAJ",$sens_invers,"",$colonne); ?></span></td>
                    
                    <td width="2%" style='background-color:#FFF'></td>
                  </tr>
                  <?php 
				  $test="SELECT * FROM C".$carte['nom']." ".$order." nom ASC,nombre DESC";
				  //echo $test;
				  $ini_liste=mysqli_query($wikeo,"SELECT * FROM C".$carte['nom']." ".$order.", nom ASC,nombre DESC");
				  while($liste=mysqli_fetch_assoc($ini_liste)){

					  $ini_ex=mysqli_query($wikeo,"SELECT * FROM exchanges WHERE nom='".$liste['exchange']."'");
					  $ex=mysqli_fetch_assoc($ini_ex);
					  
					  $ini_cr=mysqli_query($wikeo,"SELECT id_crypto_algo FROM cryptos_algos WHERE sigle='".$liste['sigle']."'");
					  $cr=mysqli_fetch_assoc($ini_cr);
					  
					  $ini_cr=mysqli_query($wikeo,"SELECT id_crypto_algo,public FROM cryptos_algos WHERE sigle='".$liste['sigle']."'");
					  $cr=mysqli_fetch_assoc($ini_cr);
					  
					 if($cr['public']==1 OR $_SESSION['user']==1){
				  ?>
                  <tr class="ligne"  onMouseOver="Javascript:ChangeCouleurLigne('lignesurvol','ligne',this,1)" onMouseOut="Javascript:ChangeCouleurLigne('lignesurvol','ligne',this,0)">
                  <td style='background-color:#FFF'><span class="fa featured  fa-bar-chart" style='font-size:14px;display:inline;color:#61b8db;cursor:pointer;padding:3px' onClick="javascript:popupcentree('amchart2.php?crypto=<?php echo  $cr['id_crypto_algo']?>',1400,480,'menubar=no,scrollbars=yes,statusbar=no,location=no,toolbar=no,status=no')"></span></td>
                    <td style='padding:5px'><?php  echo $liste['sigle'] ?></td>
                    <td ><?php echo $liste['nom']; ?></td>
                    <td ><?php echo $liste['algo'] ?></td>
                    <td ><?php echo $liste['diff'] ?></td>
                    <td><?php echo $liste['bloc']; ?></td>
                    <td ><?php $hashrate=hashrate($liste['hashrate']);echo $hashrate['hash']." ".$hashrate['unite']; ?></td>
                    <td><?php echo $liste['nombre'] ?></td>
                    <td width="3%"><?php if ($ex['logo']!=""){ 
					if($ex['minuscule']==1){$afsigle=strtolower($liste['sigle']);}else{$afsigle=$liste['sigle'];}
					$afsigle=explode("-",$afsigle);
					$afsigle=$afsigle[0];
					$montant=0;
				$ini_crypto=mysqli_query($wikeo,"SELECT btk FROM cryptos WHERE sigle='".$afsigle."'");
				  
				  $crypt=mysqli_fetch_assoc($ini_crypto);
					
					  $montant=$montant+round((($liste['valeur'])*$btc));
					?>
                    <a href="<?php echo $ex['debutlien'].$afsigle.$ex['finlien'] ?>" target="_blank"><img src="images/<?php echo $ex['logo']; ?>" name="dd" id="dd"></a>                  <?php }else{echo $ex['nom'];} ?></td>
                    <td width="8%"><?php echo $liste['bid'] ?></td>
                    <td><?php echo $liste['gainbtc']; ?></td>
                    <td><?php echo round(($liste['gainbtc']*$btc),2) ?></td>
                    <td><?php echo round(($liste['cout']),2); ?></td>
                    <td><?php echo round(($liste['gainnet']),2); ?></td>
                    <td><?php $rentagh=($liste['gainbtc']*1000000000)/$liste['hashrate'];if($rentagh<1){$ro=3;}elseif($renta>10){$ro=1;}elseif($renta>1){$ro=2;}echo round($rentagh,$ro);  ?></td>
                    <td><?php echo datefr($liste['maj'],'court') ?></td>
                    <td style='background-color:#FFF'><a href="<?php echo $crypt['btk'] ?>" target='_blank' ><span class="fa featured  fa-comments-o" style='color:#61b8db;font-size:14px;cursor:pointer'></span></a></td>
                  </tr>
                  <?php }} ?>
                </table>
                <?php }  ?>
                <p>
                </p>
                <?php }elseif($affiche=="wallets"){ ?>
			    <div class='entete_encarts'>Mes Wallets</div>
                     
                <table width="752" border="1" cellspacing="2" style="rentas">
                       <tr class="bandeau_cat">
                         <td width="8%" bgcolor="#999999">Sigle</td>
                         <td width="15%" bgcolor="#999999">Nom</td>
                         <td width="11%" bgcolor="#999999">Version </td>
                         <td width="13%" bgcolor="#999999">Bloc </td>
                         <td width="13%" bgcolor="#999999">Connect</td>
                         
                         
                         <td width="11%" bgcolor="#999999">Montant</td>
                         <td width="13%" bgcolor="#999999">Valeur Btc</td>
                         <td width="11%" bgcolor="#999999">Valeur &euro;</td>
                       </tr>
                       <?php 
				  $ini_liste=mysqli_query($wikeo,"SELECT * FROM cryptos ORDER BY valeur DESC");
				  $montantbtc=0;
				  while($liste=mysqli_fetch_assoc($ini_liste)){
					  $montantbtc=$montantbtc+round($liste['valeur'],3);
					  $montant=$montant+round((($liste['valeur'])*$btc),1);
					  
					  if($liste['montant_wallet']!='0'){
				  ?>
                       <tr class="ligne"  onMouseOver="Javascript:ChangeCouleurLigne('lignesurvol','ligne',this,1)" onMouseOut="Javascript:ChangeCouleurLigne('lignesurvol','ligne',this,0)">
                         <td style='padding:5px'><?php  echo $liste['sigle'] ?></td>
                         <td ><?php echo $liste['nom'] ?></td>
                         <td><?php echo $liste['version'] ?></td>
                         <td><?php echo $liste['bloc'] ?></td>
                         <td><?php echo $liste['connect'] ?></td>
                         
                     
                         <td><?php echo round($liste['montant_wallet'],2) ?></td>
                         <td><?php echo round($liste['valeur'],4) ?></td>
                         <td><?php echo round((($liste['valeur'])*$btc)) ?>&euro;</td>
                       </tr>
                       
                       <?php }} ?>
                       <tr class="ligne"  onMouseOver="Javascript:ChangeCouleurLigne('lignesurvol','ligne',this,1)" onMouseOut="Javascript:ChangeCouleurLigne('lignesurvol','ligne',this,0)">
                         <td colspan="9" align="right" bgcolor="#FFFFFF" style='padding:5px'>Total : <?php echo $montant ?>&euro;</td>
                       </tr>
                </table>
                <?php }elseif($affiche=="calcul"){ 
				if(isset($_POST['reward'])){
					$carte=$_POST['carte'];
					$algo=$_POST['algo'];
					$reward=$_POST['reward'];
					$cours=$_POST['cours'];
					$diff=$_POST['diff'];
					
					$ini_hash=mysqli_query($wikeo,"SELECT * FROM hashrates WHERE idcarte=$carte AND idalgo=$algo");
					$hash=mysqli_fetch_assoc($ini_hash);
					
					
					$nbcoins= $reward /($diff * (POW ( 2,32 )) / $hash['hashrate'] / 3600 / 24);
					$gainbtc=round($cours*$nbcoins,5);
					$gainte=round($gainbtc*$btc,2);
					
				}else{
					$carte="";
					$algo="";
					$reward="";
					$cours="";
					$diff="";
				}
				?>
                <div class='entete_encarts'>Calcul manuel de la rentabilit&eacute;</div><form name="form5" method="post" action="accueil.php?affiche=calcul">
               <div style='margin-left:10px'>
                <table width="787" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="22%" height="50" align="left"><label for="sexe3">Algo</label></td>
                    <td width="78%" align="left"><label for="prenom3"></label>
                      <select name="algo" id="algo">
                        <option value=""></option>
                        <?php $ini_soc=mysqli_query($wikeo,"SELECT * FROM algos order by algo");
						  while($row2=mysqli_fetch_assoc($ini_soc)){
							  ?>
                        <option value="<?php echo $row2['id'] ?>" <?php if($algo==$row2['id']){echo "selected='selected'";} ?>><?php echo $row2['algo'] ?></option>
                        <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="40" align="left">Carte</td>
                    <td align="left"><select name="carte" id="carte">
                      <option value=""></option>
                      <?php $ini_c=mysqli_query($wikeo,"SELECT * FROM cartes order by nom");
						  while($c=mysqli_fetch_assoc($ini_c)){
							  ?>
                      <option value="<?php echo $c['id'] ?>" <?php if($carte==$c['id']){echo "selected='selected'";} ?>><?php echo $c['nom'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="42" align="left">Reward</td>
                    <td align="left"><input name="reward" type="text" id="reward" value="<?php echo $reward ?>" size="30"></td>
                  </tr>
                  <tr>
                    <td height="39" align="left">Cours</td>
                    <td align="left"><input name="cours" type="text" id="cours" value="<?php echo $cours ?>" size="30"></td>
                  </tr>
                                    <tr>
                    <td height="39" align="left">Diff</td>
                    <td align="left"><input name="diff" type="text" id="diff" value="<?php echo $diff ?>" size="10"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="button3" id="button3" value="Calculer"  class='button' style='padding-top:3px;padding-bottom:3px'></td>
                  </tr>
                </table>
                </div>
                </form>
                
                <?php if(isset($gainbtc)){ ?>
                <div style='margin-bottom:30px;margin-left:50px;text-align:left;'>
                
                 
<div class='entete_encarts' style='width:350px;color:#06C;margin-bottom:10px'>Renta</div>
				  <?php
				  
				 
                echo "Gain Bitcoin : $gainbtc <br> Gain Euro : $gainte"; ?></div>
                 <?php } ?>
                 
                <?php }elseif($affiche=="hashrates"){ 
				  if(isset($_GET['algo'])){$algo=$_GET['algo'];}else{$algo=="";}
				  ?>
                <div class='entete_encarts' style='padding-bottom:15px'><?php $rlistegp=mysqli_query($wikeo,"SELECT * FROM algos order by algo ASC");
				while($liste=mysqli_fetch_assoc($rlistegp)){ 
			
				
				?>
    <div class='<?php if($algo==$liste['id']){ echo "boutonvert";}else{echo "boutonrouge";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='accueil.php?affiche=hashrates&algo=<?php echo $liste['id'] ?>'"><?php echo $liste['algo'] ?></div>      <?php }  ?>
</div>
<?php if(isset($_GET['algo'])){ 
$inialgo=mysqli_query($wikeo,"SELECT * FROM algos WHERE id='".$_GET['algo']."'");
$talgo=mysqli_fetch_assoc($inialgo);

if(!isset($_POST['chekcomp'])){
$chcomp_a=mysqli_query($wikeo,"SELECT * FROM cartes WHERE nom!='RIG_Mixte' AND nom!='RIG_ATR' AND nom!='Location' AND nom!='Asic' order by nom");
while($sp3_a=mysqli_fetch_assoc($chcomp_a)){
	$chekcomp[]=$sp3_a['id'];
}
}else{
	$chekcomp=$_POST['chekcomp'];
	//echo $_POST['chekcomp'];
}
?>


<div  style='margin-left:50px'>
</div><div class='texte_sommaire' style='text-align:center'><form name='form4' method='post' action='accueil.php?afcat=1000&affiche=hashrates&algo=<?php echo $algo ?>'><?php 
      
      
	

  //$met4=explode($chekcomp);
  //echo $met4[1];
  $chcomp=mysqli_query($wikeo,"SELECT * FROM cartes WHERE nom!='RIG_Mixte' AND nom!='RIG_ATR' AND nom!='Location' AND nom!='Asic' order by nom");
$compte=0;
while($sp3=mysqli_fetch_assoc($chcomp)){ ?>
        <?php
$chek3="";
foreach($chekcomp as $clem4 => $valeur4){
	//echo $valeur4;
	//echo $valeurm."<br>";
	if ($valeur4==$sp3['id']){ 
	//echo "ok";
	$chek3="checked";}
	
}


echo "<input type='checkbox' name='chekcomp[]' value='".$sp3['id']."' $chek3 onchange='submit()'>".$sp3['nom'];


}
?>
        
        </form></div>
  <div id="chart_div" style="width: 1150px; height: 400px;"></div>
                 </div>
<?php }}?>	 
               
              </div>
              <?php }elseif ($affiche=="questions"){ 
			  
if(isset($_POST['filtres'])){
	$_SESSION['afcat']['categorieg']=$_POST['gcat'];
	if($_SESSION['afcat']['categorieg']!="tout"){$_SESSION['afcat']['categorie']=$_POST['categories'];}else{$_SESSION['afcat']['categorie']="tout";}
	
	$_SESSION['afcat']['optiong']=$_POST['gopt'];
	if($_SESSION['afcat']['optiong']!="tout"){$_SESSION['afcat']['option']=$_POST['options'];}else{$_SESSION['afcat']['option']="tout";}
	
	$_SESSION['afcat']['tri']=$_POST['tri'];
	$_SESSION['afcat']['sens']=$_POST['sens'];

}
	
$permcat=perm_question($wikeo,'',$_SESSION['afcat']['id'],$usergroupe['id_groupe'], $_SESSION['user']);

if(!isset($_SESSION['afcat']['categorieg'])){$_SESSION['afcat']['categorieg']="tout";}
if(!isset($_SESSION['afcat']['optiong'])){$_SESSION['afcat']['optiong']="tout";}		
if(!isset($_SESSION['afcat']['categorie'])){$_SESSION['afcat']['categorie']="tout";}
if(!isset($_SESSION['afcat']['option'])){$_SESSION['afcat']['option']="tout";}		
if(!isset($_SESSION['afcat']['tri'])){$_SESSION['afcat']['tri']="reponse";}	
if(!isset($_SESSION['afcat']['sens'])){$_SESSION['afcat']['sens']="DESC";}	
$sens=$_SESSION['afcat']['sens'];

if($_SESSION['afcat']['categorieg']!="tout"){
	if($_SESSION['afcat']['categorie']=="tout"){
		$where2="AND c.id_groupe_cat=".$_SESSION['afcat']['categorieg'];}else{$where2="AND id_cat=".$_SESSION['afcat']['categorie'];}
}else{
	$where2="";
}




if($_SESSION['afcat']['optiong']!="tout"){
	if($_SESSION['afcat']['option']=="tout"){
		$where3="AND o.id_groupe_option=".$_SESSION['afcat']['optiong'];}else{$where3="AND o.id_option=".$_SESSION['afcat']['option'];}
}else{
	$where3="";
}



if($_SESSION['afcat']['tri']=="reponse"){
	$ordre="order by der_rep $sens";
}
if($_SESSION['afcat']['tri']=="creation"){
	$ordre="order by date_question $sens";
}
if($_SESSION['afcat']['tri']=="ini_sujet"){
	$ordre="order by u.prenom ASC, u.nom ASC, date_question $sens";
}

if($_SESSION['afcat']['tri']=="ini_reponse"){
	$ordre="order by u.prenom ASC, u.nom ASC, der_rep $sens";
}



 ?>

<div class="ariane"><a href='accueil.php?afcat=0'><?php echo $esp['titre'] ?></a> -> <a href='accueil.php?affiche=questions'><?php echo $nomcat; ?></a> -> Liste des sujets</div>
              <?php  if($permcat['categorie']!=0){ 
			  $nb_topics=$cat2['nb_topics'];
			  ?>
              <div id="test" class="bouton" style='float:right;margin-right:10px;margin-top:12px;'onClick="Javascript: window.location='accueil.php?affiche=ajout_question'">Nouveau sujet</div><?php }else{
				  $requ_nb_topic=mysqli_query($wikeo,"SELECT id_droit_question FROM questions_droits WHERE id_cat=".$cat2['id_cat']);
				  $nb_topics=mysqli_num_rows($requ_nb_topic);}
				   ?>
                   
              <div class="bandeau_cat" style='margin-top:50px;text-align:center;height:30px;'>
                <div id='filtres' class="box2" style='position:absolute;z-index:500;color:#666;text-align:left;font-weight:bold;font-size:10px;padding:10px;margin-top:22px;visibility:hidden'>
                <form name="form3" method="post" action="accueil.php?affiche=questions">
                  <p><?php echo $esp['nom_cat'] ?><br>
                  
                     <select name="gcat" id="gcat" onchange="xajax_scat(this.value,'scat','1','<?php echo $serveur ?>','<?php echo $util ?>','<?php echo $mdp ?>')">
					<option value="tout"  <?php if($_SESSION['afcat']['categorie']=="tout"){echo "selected='selected'";} ?>>Tout afficher</option>
					<?php $rl_cat=mysqli_query($wikeo,"SELECT titre_gcat,id_groupe_cat FROM categories_groupes  WHERE id_cat_parent=".$_SESSION['afcat']['id']);
while($l_cat=mysqli_fetch_assoc($rl_cat)){ ?>
<option value="<?php echo $l_cat['id_groupe_cat'] ?>" <?php if($_SESSION['afcat']['categorieg']==$l_cat['id_groupe_cat']){echo "selected='selected'";} ?>><?php echo $l_cat['titre_gcat'] ?></option><?php } ?></select>
<span id='scat'>
<?php if($_SESSION['afcat']['categorieg']!="tout"){ ?>
<select name="categories" id="categories">";
<option value="tout" <?php if($_SESSION['afcat']['categorie']=="tout"){echo "selected='selected'";} ?>>Tout afficher</option>
<?php $rec_ocats=mysqli_query($wikeo,"SELECT id_cat,titre_cat FROM categories WHERE id_groupe_cat=".$_SESSION['afcat']['categorieg']." order by titre_cat");
while($pcats=mysqli_fetch_assoc($rec_ocats)){
?>
<option value="<?php echo $pcats['id_cat'] ?>" <?php if($_SESSION['afcat']['categorie']==$pcats['id_cat']){echo "selected='selected'";} ?>><?php echo $pcats['titre_cat'] ?></option>
<?php } ?>
</select>
<?php } ?>
</span></p>


                  </p>
                  <p><?php echo $esp['nom_option'] ?><br>

 <select name="gopt" id="gopt" onchange="xajax_sopt(this.value,'sopt','1','<?php echo $serveur ?>','<?php echo $util ?>','<?php echo $mdp ?>')">
                      <option value="tout" <?php if($_SESSION['afcat']['optiong']=="tout"){echo "selected='selected'";} ?>>Tout afficher</option>
					  <?php $ini_option=mysqli_query($wikeo,"SELECT * FROM options_groupes WHERE id_espace=".$_SESSION['espace']);
				  while($option=mysqli_fetch_assoc($ini_option)){ ?>
                  
					 
                        <option value="<?php echo $option['id_groupe_option'] ?>"  <?php if($_SESSION['afcat']['optiong']==$option['id_groupe_option']){echo "selected='selected'";} ?>><?php echo $option['titre_groupe'] ?></option>
         <?php } ?>
                    </select>
                    <span id='sopt'>
                    <?php if($_SESSION['afcat']['optiong']!="tout"){ ?>
                    <select name="options" id="options">
<option value="tout" <?php if($_SESSION['afcat']['option']=="tout"){echo "selected='selected'";} ?>>Tout afficher</option>
<?php $rec_ocats=mysqli_query($wikeo,"SELECT id_option,titre_option FROM options WHERE id_groupe_option=".$_SESSION['afcat']['optiong']." order by titre_option");
while($pcats=mysqli_fetch_assoc($rec_ocats)){
?>
<option value="<?php echo $pcats['id_option']?>" <?php if($_SESSION['afcat']['option']==$pcats['id_option']){echo "selected='selected'";} ?>><?php echo $pcats['titre_option'] ?></option>
<?php } ?>
</select><?php } ?>
                    </span>
                  </p>
                  <p>Colonne de tri<br>
                    <label for="tri"></label>
                    <select name="tri" id="tri">
                     <option value="reponse" <?php if($_SESSION['afcat']['tri']=="reponse"){echo "selected='selected'";} ?>>Derniere reponse</option>
                     <option value="creation" <?php if($_SESSION['afcat']['tri']=="creation"){echo "selected='selected'";} ?>>Date du sujet</option>
                     <option value="ini_sujet" <?php if($_SESSION['afcat']['tri']=="ini_sujet"){echo "selected='selected'";} ?>>Createur + date du sujet</option>
                     <option value="ini_reponse" <?php if($_SESSION['afcat']['tri']=="ini_reponse"){echo "selected='selected'";} ?>>Initiateur + date de la derniere reponse</option>
                    </select>
                  <p>Sens de tri<br>
                    <label for="sens"></label>
                    <select name="sens" id="sens">
                     <option value="ASC" <?php if($_SESSION['afcat']['sens']=="ASC"){echo "selected='selected'";} ?>>Croissant</option>
                     <option value="DESC" <?php if($_SESSION['afcat']['sens']=="DESC"){echo "selected='selected'";} ?>>Decroissant</option>
                    </select>
                    <input name="filtres" type="hidden" id="filtres" value="1">
                  </p>
                  <p style='text-align:center'><input type="submit" name="button" id="button" value="Filtrer" class='bouton' style='padding:2px;font-size:10px;border-radius:2px;align:center'></p>
                </form>
                
              </div>
                <div id='choix_filtre' style='border-radius:2px;font-size:10px;font-weight:bold;width:120px;background-color:#2B7DAE;text-align:center;padding:2px;margin-top:3px' onclick="newwindow()">Gestion des filtres</div>
              </div>
             
                <?php

				
			   $quest="SELECT q.id_question,q.id_util,q.date_question,q.edit,q.titre,q.id_utilrep,q.der_rep,q.nb_posts,q.nb_lectures,u.sexe,u.nom,u.prenom,u.workflow FROM questions q LEFT JOIN utilisateurs u ON q.id_util=u.id_util LEFT JOIN questions_categories qc ON q.id_question=qc.id_question LEFT JOIN categories c ON qc.id_categorie=c.id_cat LEFT JOIN categories_groupes gc ON gc.id_groupe_cat=c.id_groupe_cat LEFT JOIN questions_options qo ON qo.id_question=q.id_question LEFT JOIN options o ON qo.id_option=o.id_option LEFT JOIN options_groupes og ON og.id_groupe_option=o.id_groupe_option WHERE id_cat_princ=".$_SESSION['afcat']['id']." $where2 $where3 GROUP BY q.id_question $ordre";
			   
			 
			   

				?>
               
<?php
				if($result=$wikeo->query($quest)){; 
				while($row=$result->fetch_assoc()){
					
					
$req_rep=mysqli_query($wikeo,"SELECT id_util,sexe,nom,prenom FROM utilisateurs WHERE id_util=".$row['id_utilrep']);
$rep=mysqli_fetch_array($req_rep);
 
	$req_nb=mysqli_query($wikeo,"SELECT id_reponse FROM reponses r LEFT JOIN utilisateurs u ON r.id_util=u.id_util WHERE id_question=".$row['id_question']);
$nb_rep=mysqli_num_rows($req_nb);

	
$req_nbq=mysqli_query($wikeo,"SELECT * FROM questions_trace WHERE id_question=".$row['id_question']);
$nb_repq=mysqli_num_rows($req_nbq); 
if($nb_repq!=0){
 $req_der_trace=mysqli_query($wikeo,"SELECT * FROM questions_trace WHERE id_question=".$row['id_question']." AND id_util=".$_SESSION['user']." order by moment DESC LIMIT 1");
$der_trac=mysqli_fetch_assoc($req_der_trace);
 }

if($nb_rep!=0){
 $req_der=mysqli_query($wikeo,"SELECT * FROM reponses r LEFT JOIN utilisateurs u ON r.id_util=u.id_util WHERE id_question=".$row['id_question']." order by date_reponse DESC LIMIT 1");
$der=mysqli_fetch_assoc($req_der);
 }
 
if(strtotime($der_trac['moment']) < strtotime($der['date_reponse'])) {
	$nouveau=true;}else{$nouveau=false;}

if($nb_rep==0){ 
if($row['id_util']==$_SESSION['user']){$nouveau=false;}else{
	if(mysqli_num_rows($req_der_trace)!=0 AND $nb_repq!=0){$nouveau=false;}else{$nouveau=true;}
}
}














if($mesrep>0){$workflow=true;}else{
	if($row['id_util']==$_SESSION['user']){$workflow=true;}else{$workflow=false;}
}

			if($permcat['categorie']==0){
				$perm=false;
$permquest=perm_question($wikeo,$row['id_question'],'', $usergroupe['id_groupe'], $_SESSION['user']);
$perm=$permquest['question'];

			}else{$perm=true;}
			
				if($perm==true){
				?>
                <div class='sujet'>

   <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
     
       <td height="144"><div class="element" style='text-align:left;'><div class='titre_cellules'  style='text-align:left;'>       <?php $quer_goptions=mysqli_query($wikeo,"SELECT q.id_option,o.titre_option,g.titre_groupe FROM questions_options q LEFT JOIN options o ON o.id_option=q.id_option LEFT JOIN options_groupes g ON o.id_groupe_option=g.id_groupe_option WHERE q.id_question=".$row['id_question']);
	   while($goptions=mysqli_fetch_assoc($quer_goptions)){ echo $goptions['titre_groupe']." -> <span class='cats'>".$goptions['titre_option']."</span> | ";
	   
	   } $quer_qcat=mysqli_query($wikeo,"SELECT q.id_categorie,c.titre_cat,g.titre_gcat FROM questions_categories q LEFT JOIN categories c ON c.id_cat=q.id_categorie LEFT JOIN categories_groupes g ON c.id_groupe_cat=g.id_groupe_cat WHERE q.id_question=".$row['id_question']);
	   while($qcat=mysqli_fetch_assoc($quer_qcat)){ echo $qcat['titre_gcat']." -> <span class='cats'>".$qcat['titre_cat']."</span> | "; }?>
       
       
       

       
       </div><div style='float:left;width:50px'><span class="fa featured  fa-comments-o" style='color:<?php if($nouveau==true){echo "#61b8db;";}else{echo "#ccc";} ?>;font-size:22px;padding-left:10px;padding-top:12px'></div><div class='selement1' style='margin-left:50px'><a href='accueil.php?affiche=thread&question=<?php echo $row['id_question'] ?>'><?php echo $row['titre'] ?></a></div><div class='selement2' style='margin-left:50px'>Par <?php echo $row['prenom']."&nbsp;".$row['nom'] ?> -> <?php echo datefr($row['date_question'],'court') ?></div>
       
       
       
       
       
       

       
       
       </div></td>
       
       <td width='70px'><div class='element'><div class='titre_cellules'>R&eacute;ponses</div><div class='centrage_cellules'><?php echo $row['nb_posts'] ?></div></div></td>
       
       <td width='40px'> <div class='element'><div class='titre_cellules'>Vues </div><div class='centrage_cellules'><?php echo $row['nb_lectures'] ?></div></div></td>
       
       <td width='180px'><div class='element' style='text-align:left;'><div class='titre_cellules'>Dernier message </div><?php if($row['nb_posts']!=0){ ?><div class='selement1'> <?php echo $rep['prenom']."&nbsp;".$rep['nom'];?></div><div class='selement2'><?php echo datefr($row['der_rep'],'court'); ?></div><?php } ?></div></td>
      
       <td width='90px'><div class='element_fin'><div class='titre_cellules'>Notifications</div>
       <?php 
	   if($workflow==true OR $user['workflow']==1){
	   $quer_notif=mysqli_query($wikeo,"SELECT * FROM suivi_bloque WHERE id_question=".$row['id_question']." AND id_util=".$_SESSION['user']);
	   $nbbloque=mysqli_num_rows($quer_notif); ?>
       
       <div id='notif_<?php echo $row['id_question'] ?>' class='centrage_cellules'><img src="images/<?php if($nbbloque==0){ echo "on_mini.png";}else{echo "off_mini.png";} ?>" width="60" height="24" onclick="xajax_notifswork('<?php echo $nbbloque ?>','<?php echo $row['id_question'] ?>','<?php echo $_SESSION['user'] ?>','<?php echo "notif_".$row['id_question'] ?>','<?php echo $serveur ?>','<?php echo $util ?>','<?php echo $mdp ?>')" style='cursor:pointer'></div>
 <?php }else{   
       	   $quer_notif=mysqli_query($wikeo,"SELECT * FROM suivi_active WHERE id_question=".$row['id_question']." AND id_util=".$_SESSION['user']);
	   $nbactive=mysqli_num_rows($quer_notif); ?>
       
       <div id='notif_<?php echo $row['id_question'] ?>' class='centrage_cellules'><img src="images/<?php if($nbactive==0){ echo "off_mini.png";}else{echo "on_mini.png";} ?>" width="60" height="24" onclick="xajax_notifs('<?php echo $nbactive ?>','<?php echo $row['id_question'] ?>','<?php echo $_SESSION['user'] ?>','<?php echo "notif_".$row['id_question'] ?>','<?php echo $serveur ?>','<?php echo $util ?>','<?php echo $mdp ?>')" style='cursor:pointer'></div>
 <?php } ?>  
       </div> </td>
     </tr>
     
   </table>
 
                
                
                </div><div style='margin-bottom:30px'></div>
                  <?php }} ?>
				  <?php }else{ echo "Pas de sujet";} ?>
              
                
          
              <?php }elseif ($affiche=="ajout_question"){ 
$rec_id_titre=mysqli_query($wikeo,"SELECT id_cat,id_groupe_cat,titre_cat FROM categories WHERE id_cat=".$_SESSION['afcat']['id']);
$id_titre=mysqli_fetch_assoc($rec_id_titre);


			  ?>
              <div class="ariane"><a href='accueil.php?afcat=0'><?php echo $esp['titre'] ?></a> -> <a href='accueil.php?affiche=questions'><?php echo $nomcat; ?></a></div>
              
              <div class='encarts'>
             
                <div class='entete_encarts'><?php echo $id_titre['titre_cat'] ?> : Nouveau sujet</div>
                <form name="form1" method="post" action="accueil.php?affiche=questions">
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="18%" height="40" align="left">Titre</td>
                    <td width="82%" height="40" align="left"><label for="titre_nv_question"></label>
                    <input name="titre_nv_question" type="text" id="titre_nv_question" size="80"></td>
                  </tr>
                  <tr>
                    <td height="40" align="left"><?php echo $esp['nom_cat']; ?>
</td>
                    <td height="40" align="left"> <div style='display:inline-block'>
                    <select name="gcat" id="gcat" onchange="xajax_scat(this.value,'scat','','<?php echo $serveur ?>','<?php echo $util ?>','<?php echo $mdp ?>')">
					<option value=""></option>
					<?php $ini_titre=mysqli_query($wikeo,"SELECT titre_gcat,id_groupe_cat FROM categories_groupes  WHERE id_cat_parent=".$_SESSION['afcat']['id']);
while($titre_scat=mysqli_fetch_assoc($ini_titre)){ ?>
<option value="<?php echo $titre_scat['id_groupe_cat'] ?>"><?php echo $titre_scat['titre_gcat'] ?></option><?php } ?></select></div>
<div id='scat' style='display:inline-block'>
</div></td>
                  </tr>
                  
                  <tr>
                    <td height="40" align="left"><?php echo $esp['nom_option']; ?></td>
                    <td height="40" align="left">
                   <div style='display:inline-block'>
                      <select name="gopt" id="gopt" onchange="xajax_sopt(this.value,'sopt','','<?php echo $serveur ?>','<?php echo $util ?>','<?php echo $mdp ?>')">
                      <option value="tout"></option>
					  <?php $ini_option=mysqli_query($wikeo,"SELECT * FROM options_groupes WHERE id_espace=".$_SESSION['espace']);
				  while($option=mysqli_fetch_assoc($ini_option)){ ?>
                  
					 
                        <option value="<?php echo $option['id_groupe_option'] ?>"><?php echo $option['titre_groupe'] ?></option>
         <?php } ?>
                    </select></div><div id='sopt' style='display:inline-block'></div></td>
                  </tr>
                 
                  <tr>
                    <td height="200" align="left" valign="top">&nbsp;</td>
                    <td align="left"><br>                      
                    <textarea name="question" cols="110" rows="10" id="question"></textarea></td>
                  </tr>
                  <tr>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left">&nbsp;</td>
                    <td align="left"><input type="submit" name="button" id="button" value="Envoyer" class='bouton' ></td>
                  </tr>
                </table>
                </form>
              </div>
              <?php }elseif ($affiche=="thread"){
				
			  if(isset($_GET['question'])){$_SESSION['question']=$_GET['question'];}
			  
			  $quer_trace=mysqli_query($wikeo,"SELECT * FROM questions_trace WHERE id_question=".$_SESSION['question']. " AND id_util=". $_SESSION['user']);
			  if(mysqli_num_rows($quer_trace)==1){
				  
				  $trace=mysqli_fetch_assoc($quer_trace);
				  
				  $update=mysqli_query($wikeo,"UPDATE questions_trace SET moment=now() WHERE id_trace_question='".$trace['id_trace_question']."'");
			  }else{
				  $ins_esp="INSERT INTO questions_trace (id_question,id_util,moment)";
$ins_esp.="VALUES ('".$_SESSION['question']."','".$_SESSION['user']."',now())";
$wikeo->query($ins_esp);

$update=mysqli_query($wikeo,"UPDATE questions SET nb_lectures=nb_lectures+1 WHERE id_question='".$_SESSION["question"]."'");
			  }
			  
			  
			  
			  
			  
			  
			  $quest="SELECT * FROM questions q LEFT JOIN utilisateurs u ON q.id_util=u.id_util LEFT JOIN societes s ON s.id_societe=u.id_societe WHERE id_question=".$_SESSION['question'];
			
				if ($result = $wikeo->query($quest)) {
				$row = mysqli_fetch_assoc($result); 
				
				$perm=perm_question($wikeo,$row['id_question'],$row['id_cat_princ'],$usergroupe['id_groupe'], $_SESSION['user']);
				}
				if($perm['question']==true OR $perm['categorie']!=0){
					

$totalDesMessages=$row['nb_posts'];
if($user['affiche']==0){
$nombreDeMessagesParPage = $config_nb_message;
}else{
	$nombreDeMessagesParPage = $user['affiche'];
}
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);
				
			  ?>
              <div class="ariane"><a href='accueil.php?afcat=0'><?php echo $esp['titre'] ?></a> -> <a href='accueil.php?affiche=questions'><?php echo $nomcat; ?></a> -> <a href='accueil.php?affiche=thread&question=<?php echo $_SESSION['question'] ?>'><?php echo $row['titre']; ?></a><div style='float:right'><span style="text-align:right;width:98%;margin:auto;padding:5px">
                <?php 


if($nombreDePages>=1){
echo 'Page : ';
}
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) //On affiche pas la page actuelle en lien
    {
    echo "<span style='color:#F00'>$i</span>";
    }
    else
    {
    echo '<a href="accueil.php?affiche=thread&page='.$i.'">
    ' . $i . '</a> ';
    }
}
 
$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

$quer_fichiersq=mysqli_query($wikeo,"SELECT * FROM uploads WHERE id_question=".$row['id_question']);
$nb_fichiersq=mysqli_num_rows($quer_fichiersq);

?>
              </span></div></div>
              
              
<div style='text-align:right;width:98%;margin:auto;padding:5px'></div>   
 
 
<div style='text-align:right;'> <div class='bouton' style='margin-right:10px;text-align:left;margin-left:15px;border-radius:5px;' onClick="Javascript: window.location='accueil.php?affiche=repondre'">repondre</div></div>

<div class='entete_posts' > 
  <div style='display: inline-block;'>Auteur<a id="quest"></a></div><div style='display: inline-block; margin-left:100px;font-weight:bold;'><?php echo $row['titre'] ?></div></div>
<div class='posts' style='background-color:#F7F7F7'>
<?php if($page==1){ 



?>

              <div class='etiquettes'><?php echo $row['prenom']."<br>".$row['nom']."<br><span class='soc_etiquettes'>".$row['societe']."</span>" ?></div>
              
             <div style='text-align:left;'> <div class='titres_posts' style='float:left'>Le <?php echo datefr($row['date_question'],"long") ?></div></div>
              
              
              
              
      <div style='clear:both;margin-left:140px;margin-right:20px;text-align:left;padding:10px;'><?php echo tag($row['question']) ?></div>
      
      
 <div style='margin-left:140px;margin-right:20px;text-align:left;padding:10px;min-height:60px'><?php if($nb_fichiersq!=0){ ?>
                <span class="soc_etiquettes">Fichiers joints :</span><br>
    <?php while($fiq=mysqli_fetch_assoc($quer_fichiersq)){ ?> 
    <span class="fa featured  fa-times-circle" style='font-size:12px;display:inline;color:#F00;cursor:pointer' onClick="javascript:popupcentree('suprim_fichier.php?id_fichier=<?php echo $fiq['id_fichier']?>&nom_fichier=<?php echo $fiq['nom_fichier']?>&chemin_fichier=<?php echo "wikidq".$fiq['id_question']."_".$fiq['nom_fichier'] ?>',320,270,'menubar=no,scrollbars=no,statusbar=no,location=no,toolbar=no,status=no')"></span>
	
	<?php echo "<a href='http://renta.atr-ingenierie.fr/uploads/wikidq".$fiq['id_question']."_".$fiq['nom_fichier']."' style='text-decoration:none'>".$fiq['nom_fichier']."</a><br>";}} ?></div>
    <div style='height:27px'><?php if ($row['edit']!="0000-00-00 00:00:00"){ ?>
			  <div style='display:inline-block;font-size:10px;font-style:italic;color:#069;text-align:left;padding-bottom:5px;margin-left:145px;width:400px;border-color:#FFF;border-style:solid;border-top-width:1px;padding-top:7px'>
              Dernière édition le <?php echo datefr($row['edit'],"long") ?></div>
			  <?php } ?>
              
			  <div style='float:right;text-align:right;margin-right:8px;'><?php if($row['id_util']==$_SESSION['user']){ ?><a href="accueil.php?affiche=modif_question" class="fa featured   fa-file-text" style='font-size:19px;display:inline;color:#4E87DC;cursor:pointer'></a>&nbsp;<span class="fa featured  fa-cloud-upload" style='font-size:20px;display:inline;color:#4E87DC;cursor:pointer' onClick="javascript:popupcentree('fichier_joint.php?id_post=<?php echo $row['id_question']; ?>&type=question',350,100,'menubar=no,scrollbars=no,statusbar=no,location=no,toolbar=no,status=no')"></span><?php }if($_SESSION['admin']==1){ ?>&nbsp;<a href='accueil.php?affiche=permissions' class="fa featured fa-cog" style='font-size:20px;display:inline;color:#4E87DC;cursor:pointer'></a><?php } ?></div>
              
                
              <?php ?></div>
              
              
              
              
              
      

              
       <div class='bas_posts'></div>  
       
      <?php } ?>
              <?php $quer_rep=mysqli_query($wikeo,"SELECT * FROM reponses r LEFT JOIN utilisateurs u ON r.id_util=u.id_util WHERE id_question=".$row['id_question']." LIMIT $premierMessageAafficher, $nombreDeMessagesParPage");
              while($rep=mysqli_fetch_assoc($quer_rep)){
				  $quer_fichiers=mysqli_query($wikeo,"SELECT * FROM uploads WHERE id_reponse=".$rep['id_reponse']);
				  $nb_fichiers=mysqli_num_rows($quer_fichiers);

				  ?>

              
    <div style='text-align:left'> <div class='etiquettes' style='float:left;' id="rep_<?php echo $rep['id_reponse'] ?>"><?php echo $rep['prenom']."<br>".$rep['nom']."<br><span class='soc_etiquettes'>".$row['societe']."</span>" ?></div>
                            
                            <div class='titres_posts' >Le <?php echo datefr($rep['date_reponse'],"long") ?></div></div>
              
              <div style='margin-left:140px;margin-right:20px;text-align:left;padding:10px;padding-bottom:0px;min-height:60px'><?php echo tag($rep['reponse']) ?></div>
              <div style='margin-left:140px;margin-right:20px;text-align:left;padding:10px;min-height:60px'><?php if($nb_fichiers!=0){ ?>
                <span class="soc_etiquettes">Fichiers joints :</span><br>
    <?php while($fi=mysqli_fetch_assoc($quer_fichiers)){ ?>
	 
    <span class="fa featured  fa-times-circle" style='font-size:12px;display:inline;color:#F00;cursor:pointer' onClick="javascript:popupcentree('suprim_fichier.php?id_fichier=<?php echo $fi['id_fichier']?>&nom_fichier=<?php echo $fi['nom_fichier']?>&chemin_fichier=<?php echo "wikidr".$fi['id_reponse']."_".$fiq['nom_fichier'] ?>',320,270,'menubar=no,scrollbars=no,statusbar=no,location=no,toolbar=no,status=no')"></span>
	
	<?php echo "<a href='http://renta.atr-ingenierie.fr/uploads/wikidr".$fi['id_reponse']."_".$fi['nom_fichier']."' style='text-decoration:none'>".$fi['nom_fichier']."</a><br>";}} ?></div>
    <div style='height:27px'><?php if ($rep['edit']!="0000-00-00 00:00:00"){ ?>
			  <div style='display:inline-block;font-size:10px;font-style:italic;color:#069;text-align:left;padding-bottom:5px;margin-left:145px;width:400px;border-color:#FFF;border-style:solid;border-top-width:1px;padding-top:7px'>
              Dernière édition le <?php echo datefr($rep['edit'],"long") ?></div>
			  <?php } ?>
              
			  <div style='float:right;text-align:right;margin-right:10px;'><?php if($rep['id_util']==$_SESSION['user']){ ?><a href="accueil.php?affiche=modif_reponse&id_reponse=<?php echo $rep['id_reponse'] ?>&page=<?php echo $page ?>" class="fa featured   fa-file-text" style='font-size:19px;display:inline;color:#4E87DC;cursor:pointer'></a>&nbsp;<span class="fa featured  fa-cloud-upload" style='font-size:20px;display:inline;color:#4E87DC;cursor:pointer' onClick="javascript:popupcentree('fichier_joint.php?id_post=<?php echo $rep['id_reponse']; ?>&type=reponse',350,100,'menubar=no,scrollbars=no,statusbar=no,location=no,toolbar=no,status=no')"></span>
              
                
              <?php } ?></div></div>
              
              
              
      <div class='bas_posts'></div>
              <?php } ?>
</div>
<div style="height:60px;">
<div style='text-align:right;margin-bottom:10px'> <div class='bouton' style='margin-right:10px;text-align:left;margin-left:15px;border-radius:5px;margin-top:0px' onClick="Javascript: window.location='accueil.php?affiche=repondre'">repondre</div></div>
<div style='float:right'>
<span style="text-align:right;width:98%;margin:auto;padding:5px" class='ariane'>
 <?php 

if(isset($_POST['reponse'])){
	$page=$nombreDePages;

}else{
$page = (isset($_GET['page']))?intval($_GET['page']):1;	
}
if($nombreDePages>=1){
echo 'Page : ';
}
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) //On affiche pas la page actuelle en lien
    {
    echo "<span style='color:#F00'>$i</span>";
    }
    else
    {
    echo '<a href="accueil.php?affiche=thread&page='.$i.'">
    ' . $i . '</a> ';
    }
}
 
$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

?>
    </span></div></div>
              
              
<div style='text-align:right;width:98%;margin:auto;padding:5px'></div> 
 
 

              <?php }else{ echo"<br>Vous n'avez pas la permission d'afficher ce sujet";}
			 
			  }elseif($affiche=="repondre"){ 
              $quest="SELECT * FROM questions q LEFT JOIN utilisateurs u ON q.id_util=u.id_util WHERE id_question=".$_SESSION['question'];
				if ($result = $wikeo->query($quest)) {
				$row = $result->fetch_assoc();
				$perm=perm_question($wikeo,$row['id_question'],$row['id_cat_princ'],$usergroupe['id_groupe'], $_SESSION['user']);
				}
				if($perm['question']==true OR $perm['categorie']!=0){?>
			  <div class="ariane"><a href='accueil.php?afcat=0'><?php echo $esp['titre'] ?></a> -> <a href='accueil.php?affiche=questions'><?php echo $nomcat; ?></a> -> <a href='accueil.php?affiche=thread&question=<?php echo $_SESSION['question'] ?>'><?php echo $row['titre']; ?></a> -> Nouvelle r&eacute;ponse</div>
              <div class='encarts'>
              <div class='entete_encarts'>R&eacute;ponse au sujet : <?php echo $row['titre']; ?></div>
              <form name="form2" method="post" action="traitement.php">
              <div>
               <p>
                  <textarea name="reponse" cols="110" rows="10" id="reponse"></textarea>
                </p>
                <input type="submit" value="Envoyer" class='button' style='margin-top:10px' />
               
              </div>
              </form>
			  <?php 
			  }else{ echo"<br>Vous n'avez pas la permission d'intervenir sur ce sujet";}
			  ?>
			  </div>
			  <?php
              }elseif($affiche=='permissions'){ 
			  if($_SESSION['admin']==1){
			 $quest="SELECT * FROM questions q LEFT JOIN utilisateurs u ON q.id_util=u.id_util WHERE id_question=".$_SESSION['question'];
			if ($result = $wikeo->query($quest)) {
			$row = mysqli_fetch_assoc($result);
				
			}
			  ?>
               <div class="ariane"><a href='accueil.php?afcat=0'><?php echo $esp['titre'] ?></a> -> <a href='accueil.php?affiche=questions'><?php echo $nomcat; ?></a> -> <a href='accueil.php?affiche=thread&question=<?php echo $_SESSION['question'] ?>'><?php echo $row['titre']; ?></a> -> Gestion des permissions</div>
               
               <div class='encarts' style='text-align:left;'>
                 <div class='entete_encarts'>Ajout de groupes ayants acc&egrave;s &agrave; cette question</div>
                  <div  style='float:left'>
                 <form name="form1" method="post" action="accueil.php?affiche=permissions">
                <div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Groupes disponible</div>
                <?php $quergp=mysqli_query($wikeo,"SELECT * FROM groupes WHERE id_espace=".$_SESSION['espace']);
				while($gps=mysqli_fetch_assoc($quergp)){ 
				$afgp=true;
				$verifgp=mysqli_query($wikeo,"SELECT * FROM categories_droits WHERE id_groupe=".$gps['id_groupe']." AND id_cat=".$row['id_cat_princ']);
				if(mysqli_num_rows($verifgp)==0){$afgp=true;}else{$afgp=false;}
				$prepspec="SELECT * FROM questions_droits WHERE id_groupe=".$gps['id_groupe']." AND id_question=".$row['id_question'];
			
				$verifgp_spec=mysqli_query($wikeo,$prepspec);
				if(mysqli_num_rows($verifgp_spec)==1){$afgp=false;}
				if($afgp==true){
				?>
                <input name="ajoutgps[]" type="checkbox" id="ajoutgps[]" value="<?php echo $gps['id_groupe'] ?>"><?php echo $gps['titre'] ?><br>
               <?php }} ?>
               
               <input type="submit" name="button2" id="button2" value="Ajouter le(s) groupe(s)" class='bouton' style='margin-top:10px;'>
                 </form>
                 </div>
                <div style='float:left;margin-bottom:30px'>
                
                 <form name="form1" method="post" action="accueil.php?affiche=permissions">
                  <div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Groupes affect&eacutes</div>
				  <?php
                $rlistegp=mysqli_query($wikeo,"SELECT c.id_groupe,g.titre FROM categories_droits c LEFT JOIN groupes g ON c.id_groupe=g.id_groupe WHERE id_cat=".$row['id_cat_princ']);
				while($liste=mysqli_fetch_assoc($rlistegp)){echo "<span style='margin-left:17px'>".$liste['titre']."</span><br>";}
				
				$prepspec2=mysqli_query($wikeo,"SELECT q.id_droit_question,g.titre FROM questions_droits q LEFT JOIN groupes g ON q.id_groupe=g.id_groupe WHERE q.id_question=".$row['id_question']);
				while($dquest=mysqli_fetch_array($prepspec2)){ 
				?>
                <input name="supgps[]" type="checkbox" id="supgps[]" value="<?php echo $dquest['id_droit_question'] ?>"><?php echo $dquest['titre'] ?><br><?php } ?>
                  <input type="submit" name="button2" id="button2" value="supprimer le(s) groupe(s)" class='bouton' style='margin-top:10px;'>
                 </form></div>
               <div style='clear:both;margin-bottom:30px;'></div></div>
               
               
               <?php }   }elseif ($affiche=="modif_question"){ 
			   
			   $ret_quest=mysqli_query($wikeo,"SELECT * FROM questions WHERE id_question=".$_SESSION['question']);
			   $quest=mysqli_fetch_assoc($ret_quest);



			  ?>
              <div class="ariane"><a href='accueil.php?afcat=0'><?php echo $esp['titre'] ?></a> -> <a href='accueil.php?affiche=questions'><?php echo $nomcat; ?></a> -> <a href='accueil.php?affiche=thread'><?php echo $quest['titre']; ?></a> -> Modification du sujet</div>
              <?php if($quest['id_util']==$_SESSION['user']){ ?>
              
              <div class='encarts'>
               
                <div class='entete_encarts'><?php echo $quest['titre']; ?> : Modification</div>
                <form name="form1" method="post" action="accueil.php?affiche=thread">
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="18%" height="40" align="left">Titre</td>
                    <td width="82%" height="40" align="left"><label for="titre_modif_question"></label>
                    <input name="titre_modif_question" type="text" id="titre_modif_question" value="<?php echo $quest['titre'] ?>" size="80"></td>
                  </tr>
                  <tr>
                    <td height="40" align="left"><?php echo $esp['nom_cat'];?>
</td>
                    <td height="40" align="left"> <div style='display:inline-block'>
                    <select name="gcat" id="gcat" onchange="xajax_scat(this.value,'scat','','<?php echo $serveur ?>','<?php echo $util ?>','<?php echo $mdp ?>')">
					<option value=""></option>
					<?php 
					$ret_cat=mysqli_query($wikeo,"SELECT q.id_categorie,q.id_question_categorie,c.id_cat,g.id_groupe_cat FROM questions_categories q LEFT JOIN categories c ON q.id_categorie=c.id_cat LEFT JOIN categories_groupes g ON c.id_groupe_cat=g.id_groupe_cat WHERE q.id_question=".$_SESSION['question']);
					$retcat=mysqli_fetch_assoc($ret_cat);
					$ini_titre=mysqli_query($wikeo,"SELECT titre_gcat,id_groupe_cat FROM categories_groupes  WHERE id_cat_parent=".$_SESSION['afcat']['id']);
while($titre_scat=mysqli_fetch_assoc($ini_titre)){ ?>
<option value="<?php echo $titre_scat['id_groupe_cat'] ?>" <?php if($retcat['id_groupe_cat']==$titre_scat['id_groupe_cat']){echo "selected=selected";} ?>><?php echo $titre_scat['titre_gcat'] ?></option><?php } ?></select></div>
<div id='scat' style='display:inline-block'>
<select name="categories" id="categories">
<?php
$rec_ocats=mysqli_query($wikeo,"SELECT id_cat,titre_cat FROM categories WHERE id_groupe_cat=".$retcat['id_groupe_cat']." order by titre_cat");
while($pcats=mysqli_fetch_assoc($rec_ocats)){
?>
<option value="<?php echo $pcats['id_cat'] ?>" <?php if($retcat['id_cat']==$pcats['id_cat']){echo "selected='selected'";} ?>><?php  echo $pcats['titre_cat']  ?></option>
<?php } ?>
</select>

</div></td>
                  </tr>
                  
                  <tr>
                    <td height="40" align="left"><?php echo $esp['nom_option']; ?></td>
                    <td height="40" align="left">
                   <div style='display:inline-block'>
                      <select name="gopt" id="gopt" onchange="xajax_sopt(this.value,'sopt','','<?php echo $serveur ?>','<?php echo $util ?>','<?php echo $mdp ?>')">
                      <option value=""></option>
					  <?php 
					  $ret_opt=mysqli_query($wikeo,"SELECT q.id_option,q.id_question_option,g.id_groupe_option FROM questions_options q LEFT JOIN options o ON q.id_option=o.id_option LEFT JOIN options_groupes g ON o.id_groupe_option=g.id_groupe_option WHERE q.id_question=".$_SESSION['question']);
					$retopt=mysqli_fetch_assoc($ret_opt);
					
					
					  $ini_option=mysqli_query($wikeo,"SELECT * FROM options_groupes WHERE id_espace=".$_SESSION['espace']);
				  while($option=mysqli_fetch_assoc($ini_option)){ ?>
                  
					 
                        <option value="<?php echo $option['id_groupe_option'] ?>" <?php if($option['id_groupe_option']==$retopt['id_groupe_option']){echo "selected='selected'";} ?>><?php echo $option['titre_groupe'];?></option>
         <?php } ?>
                    </select></div><div id='sopt' style='display:inline-block'>
                    
                    <select name="options" id="options">
                    <?php $rec_opts=mysqli_query($wikeo,"SELECT id_option,titre_option FROM options WHERE id_groupe_option=".$retopt['id_groupe_option']." order by titre_option");
while($popts=mysqli_fetch_assoc($rec_opts)){
?>
<option value="<?php echo $popts['id_option'] ?>" <?php if($retopt['id_option']==$popts['id_option']){echo "selected='selected'";} ?>><?php echo $popts['titre_option']?></option>
<?php } ?>
</select>
                    
                    </div></td>
                  </tr>
                 
                  <tr>
                    <td height="200" align="left" valign="top">&nbsp;</td>
                    <td align="left"><br>                      
                    <textarea name="question" cols="110" rows="10" id="question"><?php echo $quest['question'] ?></textarea></td>
                  </tr>
                  <tr>
                    <td align="left">&nbsp;</td>
                    <td align="left">     </td>
                  </tr>
                  <tr>
                    <td align="left">&nbsp;</td>
                    <td align="left"><input type="submit" name="button" id="button" value="Envoyer" class='bouton'></td>
                  </tr>
                </table>
                <input name="option" type="hidden" id="option" value="<?php echo $retopt['id_question_option'] ?>">
                <input name="cat" type="hidden" id="option" value="<?php echo $retcat['id_question_categorie'] ?>">
                </form>
              </div><?php }else{echo "<br><br>Pas d'autorisation pour modifier ce sujet !";}
			  
			  }elseif($affiche=="modif_reponse"){ 
           $quest=mysqli_query($wikeo,"SELECT * FROM questions q LEFT JOIN utilisateurs u ON q.id_util=u.id_util WHERE id_question=".$_SESSION['question']);
			  $rep=mysqli_query($wikeo,"SELECT * FROM reponses WHERE id_reponse=".$_GET['id_reponse']);
				
				$row = mysqli_fetch_assoc($quest);
				$rowr=mysqli_fetch_assoc($rep);
				
				?>
			  <div class="ariane"><a href='accueil.php?afcat=0'><?php echo $esp['titre'] ?></a> -> <a href='accueil.php?affiche=questions'><?php echo $nomcat; ?></a> -> <a href='accueil.php?affiche=thread&question=<?php echo $_SESSION['question'] ?>'><?php echo $row['titre']; ?></a></div>
              <?php if($rowr['id_util']==$_SESSION['user']){ ?>
              <div class='encarts'>
              <div class='entete_encarts'>Modification de votre R&eacute;ponse</div>
              <form name="form2" method="post" action="accueil.php?affiche=thread&page=<?php echo $_GET['page'] ?>#rep_<?php echo $_GET['id_reponse'] ?>">
              <div>
               <p>
                  <textarea name="modif_reponse" cols="110" rows="10" id="modif_reponse"><?php echo $rowr['reponse'] ?></textarea>
                 <input name="id_reponse" type="hidden" id="id_reponse" value="<?php echo $rowr['id_reponse'] ?>">
               </p>
                <input type="submit" value="Envoyer" class='button' style='margin-top:10px' />
               
              </div>
              </form> <?php 
			  }else{echo "Pas d'autorisation pour modifier cette r&eacute;ponse";} ?></div>
			  <?php 
			} ?>
             
<div style='margin-bottom:10px;'>
               <br>
              </div>
              </div>
</body>
</html>
<?php }else{header("Location:index.php?message=expire"); } ?>