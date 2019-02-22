<?php session_start();
if(isset($_GET['deconnexion'])){
$_SESSION = array();
session_destroy();
header("Location:index.php");
}

if(!isset($_SESSION['user'])){
include("fonctions/connexion.php");
include("fonctions/fonctions.php");


/*
$requette="UPDATE utilisateurs SET ";

$code=encode("manas");

$requette.="passe='$code'";

$requette.=" WHERE id_util=1";

$update=mysqli_query($wikeo,$requette);

*/

function verifier($formulaire,$serveur,$util,$mdp)
{

    $response = new xajaxResponse();
	$wikeo = @mysqli_connect($serveur, $util, $mdp, 'renta');
	$mail=$formulaire['mailatr'];
	$passe=encode($formulaire['passe']);
	$espace=$formulaire['espace'];
	if($mail==""){
	$af="Veuillez renseigner votre Adresse Mail";
	$response->assign('avertissement', 'innerHTML', $af);
	}elseif($passe==""){
	$af="Veuillez renseigner votre mot de passe";
	$response->assign('avertissement', 'innerHTML', $af);
	}else{
		
		
		
	$quer1=mysqli_query($wikeo,"SELECT id_util, prenom, mail, passe, supadmin FROM utilisateurs WHERE mail='$mail'");
	if(mysqli_num_rows($quer1)==1){
	$resquer=mysqli_fetch_assoc($quer1);
	if($resquer['passe']==$passe){
					

$ins_util="INSERT INTO connexions (moment,ip,user,nomuser)";
$ins_util.="VALUES (now(),'".$_SERVER["REMOTE_ADDR"]."','".$resquer['id_util']."','".$resquer['prenom']."')";
$wikeo->query($ins_util);

	if($resquer['supadmin']==1){
	$_SESSION['user']=$resquer['id_util'];
	$_SESSION['espace']=$espace;
	$_SESSION['super_admin']=1;
	$_SESSION['admin']=1;
	$response->redirect("accueil.php?afcat=1000&affiche=listing");
			}else{
	$quer2=mysqli_query($wikeo,"SELECT id_util,admin FROM espaces_droits WHERE id_util=".$resquer['id_util']." AND id_espace=$espace");
	if(mysqli_num_rows($quer2)==1){
	$resquer2=mysqli_fetch_assoc($quer2);	
	$_SESSION['user']=$resquer['id_util'];
	$_SESSION['espace']=$espace;
	$_SESSION['super_admin']=0;
	$_SESSION['admin']=$resquer2['admin'];
	$response->redirect("accueil.php?afcat=1000&affiche=listing");
	}else{
	$af="Vous n'avez pas l'autorisation pour d'acc&eacute;der a cet espace";
	$response->assign('avertissement', 'innerHTML', $af);
	}
			}
	}else{
	$af="L'adresse mail et le mot de passe ne correspondent pas.";
	$response->assign('avertissement', 'innerHTML', $af);
	
	}
	}else{
$af="Votre Adresse Mail n'a pas &eacute;t&eacute; trouv&eacute;e dans la liste des utilisateurs";
$response->assign('avertissement', 'innerHTML', $af);
	}
	
	
	
	
	
	} 
//$response->assign('logo', 'innerHTML', "test");

return $response;
}


@require 'xajax_core/xajax.inc.php';
$xajax = new xajax(); // On initialise l'objet xajax.
$xajax->setCharEncoding('iso-8859-1');// On précise à xAjax qu'on souhaite travailler en ISO-8859-1.
$xajax->register(XAJAX_FUNCTION, 'verifier');// On enregistre nos fonctions.
$xajax->processRequest();// Fonction qui va se charger de générer le Javascript, à partir des données que l'on a fournies à xAjax APRÈS AVOIR DÉCLARÉ NOS FONCTIONS.


if(isset($_POST['envoi_passe'])){
include ("fonctions/fonctions_mails.php");
}

if(isset($_GET['message'])){$message=$_GET['message'];
if($message=="expire"){$message="Votre session a expir&eacute;e";}
}


?>
<!DOCTYPE HTML>
<!--
	Miniport 2.5 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Rentabilit&eacutees</title>
        
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,600,700" rel="stylesheet" />
		<script src="js/jquery.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-desktop.css" />
		</noscript>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
		<!--[if lte IE 7]><link rel="stylesheet" href="css/ie7.css" /><![endif]-->
		<?php $xajax->printJavascript(); /* Affiche le Javascript */?>
	</head>
	<body>

		<!-- Nav -->

    <?php if(isset($_GET['demande'])){ ?>
    
    <div class="container" style='width:550px'>

			  <section class="box box-style1" style='margin-top:130px;'> <span class="fa featured fa-user" style='color:#61b8db;font-size:60px;'></span>
			    <h3 style='font-size:18px'>Cr&eacute;ation d'un nouveau mot de passe</h3>
			    <div class="avertissement" id='avertissement' style='margin-bottom:20px;'><?php if(isset($message)){echo $message;} ?></div>
			    <form method="post" action="">
               <fieldset>
				  <div style='width:300px;margin:0 auto;text-align:center;'>
                    <p><span class="fa featured fa-envelope-o" style='color:#61b8db;font-size:25px;display:inline'>	</span>
                      <input type="text" name="envoi_passe" id="envoi_passe" placeholder="E.Mail" style='margin-left:8px'><br>
                      
                      
                      <input type="submit" value="Envoyer" class='button' style='margin-top:10px' />
		         </p>
			     </div>
                </fieldset>
                </form>
			    <div id='mdp' style="color: #333; font-style: italic; font-size: 12px; font-family: Verdana, Geneva, sans-serif; text-align: right;"><a href="index.php?avert=mdp_ok">J'ai mon mot de passe</a></div>
		      </section></div>
    
    
    
    <?php }else{ ?>

<div class="container" style='width:550px'>

			  <section class="box box-style1" style='margin-top:130px;'> <span class="fa featured fa-user" style='color:#61b8db;font-size:60px;'></span>
			    <h3 style='font-size:18px'>Merci de vous identifier</h3><div class="avertissement" id='avertissement' style='margin-bottom:20px;'><?php if(isset($message)){echo $message;} ?></div>
			    <form method="post" action="">
               <fieldset>
				  <div style='width:300px;margin:0 auto;text-align:center;'>
                    <p><span class="fa featured fa-envelope-o" style='color:#61b8db;font-size:25px;display:inline'>	</span>
                      <input type="text" name="mailatr" id="mailatr" placeholder="E.Mail" style='margin-left:8px'><br>
                      <span class="fa featured fa-lock" style='color:#61b8db;font-size:25px;display:inline'></span> 
                      <input type="password" name="passe" id="passe" placeholder="Mot de passe" / style='margin-left:20px'>
                      <br>
                      <select name="espace" id="espace" style='margin-left:20px;margin-top:5px;'>
                        <?php $prep_list=mysqli_query($wikeo,"SELECT * FROM espaces order by titre");
					  while($list=mysqli_fetch_assoc($prep_list)){ ?>
                        <option value="<?php echo $list['id_espace'] ?>"><?php echo $list['titre'] ?></option>
                        <?php } ?>
                      </select>
                      <br>
                      
                      <input type="submit" value="Envoyer" onclick="xajax_verifier(xajax.getFormValues(this.form),'<?php echo $serveur ?>','<?php echo $util ?>','<?php echo $mdp ?>'); return false;" class='button' style='margin-top:10px' />
		         </p>
			     </div>
                </fieldset>
                </form>
			    <div id='mdp' style="color: #333; font-style: italic; font-size: 12px; font-family: Verdana, Geneva, sans-serif; text-align: right;"><a href="index.php?demande=mdp">Mot de passe oubli&eacute; ?</a></div>
		      </section></div>
              <?php } ?>
	</body>
</html>
<?php }else{ ?>

<script language="javascript">
	 top.window.location.replace('/accueil.php');
	 </script>
     
 <?php     } ?>