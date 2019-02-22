<?php 
session_start();

include("fonctions/connexion.php");
include("fonctions/fonctions.php");



 function code($mdp){
$response = new xajaxResponse();



 
// On r�cup�re la longueur du mot de passe	
if($mdp!=""){
$longueur = strlen($mdp);
 
// On fait une boucle pour lire chaque lettre
for($i = 0; $i < $longueur; $i++) 	{
 
	// On s�lectionne une � une chaque lettre
	// $i �tant � 0 lors du premier passage de la boucle
	$lettre = $mdp[$i];
 
	if ($lettre>='a' && $lettre<='z'){
		// On ajoute 1 point pour une minuscule
		$point = $point + 1;
 
		// On rajoute le bonus pour une minuscule
		$point_min = 1;
	}
	else if ($lettre>='A' && $lettre <='Z'){
		// On ajoute 2 points pour une majuscule
		$point = $point + 3;
 
		// On rajoute le bonus pour une majuscule
		$point_maj = 3;
	}
	else if ($lettre>='0' && $lettre<='9'){
		// On ajoute 3 points pour un chiffre
		$point = $point + 4;
 
		// On rajoute le bonus pour un chiffre
		$point_chiffre = 4;
	}
	else {
		// On ajoute 5 points pour un caract�re autre
		$point = $point + 5;
 
		// On rajoute le bonus pour un caract�re autre
		$point_caracteres = 5;
	}
}
 
// Calcul du coefficient points/longueur
$etape1 = $point / $longueur;
 
// Calcul du coefficient de la diversit� des types de caract�res...
$etape2 = $point_min + $point_maj + $point_chiffre + $point_caracteres;
 
// Multiplication du coefficient de diversit� avec celui de la longueur
$resultat = $etape1 * $etape2;
 
// Multiplication du r�sultat par la longueur de la cha�ne
$final = $resultat * $longueur;
}else{
	$final=0;}

	if($final<100){
		$couleur="red";
		$texte="FAIBLE";
		$niv_force=0;
	}elseif($final>100 AND $final<150){
		$couleur="orange";
		$texte="MOYENNE";
		$niv_force=1;
	}else{
		$couleur="green";
		$texte="FORTE";
		$niv_force=1;
	}
		
 
$force="<div style='margin:auto;padding:5px 0px 5px 0px;border-radius:5px;width:80px;color:#fff;background-color:".$couleur."'><span style='font-size:11px'>".$final." pts</span><br>".$texte."</div>";
if($niv_force==1){
	
$response->assign('submit', 'style.visibility', 'visible');
}else{
$response->assign('submit', 'style.visibility', 'hidden');
}
$response->assign("force", 'innerHTML', $force);


return $response;
}





function verifier($formulaire,$serveur,$util,$mdp)
{
$response = new xajaxResponse();
$wikeo = @mysqli_connect($serveur, $util, $mdp, 'rentas');
$message="<div style='display:inline-block;height:30px;margin:auto;color:#FFF;padding:margin:auto;text-align:center;padding:5px 15px 5px 15px;border-radius:5px;margin-bottom:10px;";
if($formulaire['passe']!=""){
$passe=encode($formulaire['passe']);
$quer1=mysqli_query($wikeo,"SELECT passe FROM utilisateurs WHERE id_util=".$_SESSION['user']);
$quer=mysqli_fetch_assoc($quer1);


if($passe!=$quer['passe']){
$message.="background-color:#FFB7B9;'>";
$message.="Votre ancien mot de passe ne correspond pas !</div>";
}elseif($formulaire['nv_passe']!=$formulaire['confirme_passe']){
	$message.="background-color:#FFB7B9;'>";
	$message.="Les nouveaux mots de passe ne sont pas identiques</div>";
}else{
	$message.="background-color:#61b8db;'>";
	$nv_passe=encode($formulaire['nv_passe']);
	$update=mysqli_query($wikeo,"UPDATE utilisateurs SET passe='$nv_passe' WHERE id_util=".$_SESSION['user']);
	$message.="Le mot de passe a &eacute;t&eacute; modifi&eacute</div>";
}
}else{
$message.="background-color:#FFB7B9;'>";
$message.="Merci de rensgeigner votre mot de passe actuel</div>";
}

$response->assign("informe", 'innerHTML', $message);


return $response;
	}





@require 'xajax_core/xajax.inc.php';
$xajax = new xajax(); // On initialise l'objet xajax.
$xajax->setCharEncoding('iso-8859-1');// On pr�cise � xAjax qu'on souhaite travailler en ISO-8859-1.
$xajax->register(XAJAX_FUNCTION, 'code');// On enregistre nos fonctions.
$xajax->register(XAJAX_FUNCTION, 'verifier');

$xajax->processRequest();// Fonction qui va se charger de g�n�rer le Javascript, � partir des donn�es que l'on a fournies � xAjax APR�S AVOIR D�CLAR� NOS FONCTIONS.


if(isset($_POST['messages'])){
	$update=mysqli_query($wikeo,"UPDATE utilisateurs SET affiche='".$_POST['messages']."' WHERE id_util=".$_SESSION['user']);

}


	?>
    
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-15"/>
<title>WIKEO : <?php echo $esp['titre'] ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,600,700" rel="stylesheet" />
		<script src="js/jquery.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		  <link rel="stylesheet" href="css/styles_persos.css" />
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
          
			<link rel="stylesheet" href="css/style-desktop.css" />
		</noscript>
         <?php $xajax->printJavascript(); /* Affiche le Javascript */?>
</head>

<body>
        <?php 
	
		include("header.php"); 
		if(!isset($affiche)){$affiche="parametres";}
		  
		?>
            
            <div class="container" >
            <section class="box2" style='margin-top:69px;padding:5px'>
            
            <div style='text-align:left;'>
            <div class='bouton' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='espace.php?affiche=mdp'">Mot de passe</div><div class='bouton' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='espace.php?affiche=affichage'">Affichage</div>
            </div>
             
             
              <div class='encarts' style='margin-top:10px;margin-bottom:20px;padding-bottom:20px;'>

<?php if($affiche === "mdp"){ ?>



<div class='entete_encarts'>Vos parametres : modification du mot de passe<div id='informe' style='float:right;text-align:right;'></div></div>
                <form name="form1" method="post" action="">
<fieldset>
                    <div class='stitre_encart;' ></div>
                    
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="19%" height="26">Mot de passe actuel</td>
                          <td width="17%">Nouveau mot de passe</td>
                          <td width="23%">Confirmation </td>
                          <td width="17%">Force du mot de passe</td>
                          <td width="18%">&nbsp;</td>
                        </tr>
                        <tr valign="top">
                          <td>
                            <input name="passe" type="password" id="passe" size="12"></td>
                          <td><input name="nv_passe" type="password" id="nv_passe" size="12" onkeypress="xajax_code(this.value)" onclick="javascript:this.value='';"></td>
                          <td><input name="confirme_passe" type="password" id="confirme_passe" size="12" ></td>
                          <td align="center"><div id='force'></div></td>
                          <td><div id='submit' style='visibility:hidden'><input type='submit' name='button' id='button' value='Modifier'  class='button' style='padding-top:3px;padding-bottom:3px' onclick="xajax_verifier(xajax.getFormValues(this.form),'<?php echo $serveur ?>','<?php echo $util ?>','<?php echo $mdp ?>'); return false;"></div></td>
                        </tr>
                      </table>
                      
                  </fieldset>
                </form>
                <div class='titres_posts' style='width:100%;margin-left:200px;border-width:0'>Le nouveau mot de passe doit &ecirc;tre de force moyenne (100 pts) au minimum</div>
                <?php }elseif($affiche=="affichage"){ ?>
                <div class='entete_encarts'>Vos parametres : Nombre de messages par page</div>
                <span style='text-align:left'>
                <form name="form1" method="post" action="">
                  <fieldset>
                    <div class='stitre_encart;' ></div>
                    <table width="1014" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="36%">Choisissez le nombre de messages par page :</td>
                        <td width="7%">
                        <?php $ver_u=mysqli_query($wikeo,"SELECT affiche FROM utilisateurs WHERE id_util=".$_SESSION['user']);
						$u=mysqli_fetch_assoc($ver_u);
						?>
                        <label for="messages"></label>
                          <select name="messages" id="messages">
                          <?php if($u['affiche']==0){ ?>
                          <option value="0"></option>
                          <?php } ?>
                          <?php for($i=1;$i<51;$i++){ ?>
                            <option value="<?php echo $i ?>" <?php if($u['affiche']==$i){echo "selected='selected'";} ?>><?php echo $i ?></option>
                            <?php } ?>
                        </select></td>
                        <td width="57%"><input type="submit" name="button3" id="button3" value="Modifier" class='button' style='padding-top:3px;padding-bottom:3px'></td>
                      </tr>
                    </table>
                  </fieldset>
                </form>
                </span>
                <?php } ?>
               </div>
              
            </section>
            </div>
</body>
</html>
