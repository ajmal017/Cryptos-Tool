<?php

function envoi_mail($corps_text,$corps_html,$fichier,$from,$to,$sujet){
$text = $corps_text;
$html = "<html><body>".$corps_html."</body></html>";
$file = $fichier;
$crlf = "\n";

$headers = array(
              'From'    => $from,
			  'To' => $to,
              'Subject' => $sujet
              );

$mime = new Mail_mime($crlf);

$mime->setTXTBody($text);
$mime->setHTMLBody($html);
//$mime->addAttachment($file, 'text/plain');

$body = $mime->get();

$hdrs = $mime->headers($headers);

$params["host"] = "192.168.1.230";
$params["port"] = "25";
$params["auth"] = false;
//$params["username"] = "noreply@atr-ingenierie.fr";
//$params["password"] = "NewAtr2008";

$mail =& Mail::factory("smtp", $params);

$mail->send($to, $hdrs, $body);

}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////Envoi du mot de passe
if(isset($_POST['envoi_passe'])){
$req_ch=mysqli_query($wikeo,"SELECT prenom FROM utilisateurs WHERE mail='".$_POST['envoi_passe']."'");
$verif=mysqli_num_rows($req_ch);
if($verif!=0){
$donne_passe=mysqli_fetch_array($req_ch);
$nv_pass=MDP();
$code=encode($nv_pass);
$update=mysqli_query($wikeo,"UPDATE utilisateurs SET passe='$code' WHERE mail='".$_POST['envoi_passe']."'");
$sujet = "Perte de vos identifiants $interface";
$body = "Bonjour ".$donne_passe['prenom'].",<br />

Suite &agrave; votre demande, voici votre nouveau mot de passe de connexion &agrave; l'espace Rentas :<br />

Mot de passe : $nv_pass<br />

Bonne r&eacute;ception<br />";

$entete="Content-type:text/html\nFrom:$expediteur";


if($fonction_mail==true){
envoi_mail($body,$body,"",$expediteur,$_POST['envoi_passe'],$sujet);
}
$message="Votre identifiant a bien &eacute;t&eacute; envoy&eacute";
/*
if(mail($donne_passe['email'], $sujet, $body, $entete)){
$message="Votre identifiant a bien &eacute;t&eacute; envoy&eacute";
}else{
$message="Un probl&egrave;me c'est produit, votre identifiant n'a pas &eacute;t&eacute; envoy&eacute;";
}
*/
}else{
$message="Votre mail n'a pas &eacute;t&eacute; trouv&eacute; dans la liste des utilisateurs";
	}
	
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////Creation d'un nouvel utilisateur

if(isset($creation_compte)){

$sujet = "Creation de votre compte $interface sur l'espace $titre_espace";
$body = "Bonjour $prenom,<br />

Votre compte vient d'&ecirc;tre cr&eacute;&eacute; sur la plateforme <br>
Vous avez d&eacute;sormais acc&egrave;s &agrave; l'espace $titre_espace.<br>
<p>Voici les informations pour acc&eacute;der &agrave; cette plateforme :</p>
Adresse : http://renta.atr-ingenierie.fr<br>
Mot de passe de connexion : $nv_pass<br>
Choisissez $titre_espace dans le menu d&eacute;roulant<br>
<p>Vous pourrez ensuite modifier votre mot de passe dans les param&egrave;tres de votre profil.</p>
Bonne r&eacute;ception<br>";

$entete="Content-type:text/html\nFrom:$expediteur";

if($fonction_mail==true){
envoi_mail($body,$body,"",$expediteur,"$mail",$sujet);
}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////AVetissement nouveau sujet


if(isset($_POST['titre_nv_question'])){

	$req_droits="SELECT id_util,id_groupe FROM categories_droits WHERE id_cat=".$_SESSION['afcat']['id'];
	
	if($quer_droits=$wikeo->query($req_droits)){; 
				while($droit=$quer_droits->fetch_assoc()){;
				
				$req_utils="SELECT gu.id_groupe,u.workflow,u.id_util,u.prenom,u.mail FROM utilisateurs u LEFT JOIN groupes_utilisateur gu ON u.id_util=gu.id_util WHERE gu.id_groupe=".$droit['id_groupe']." OR u.id_util=".$droit['id_util'];
	if($quer_utils=$wikeo->query($req_utils)){; 
				while($util=$quer_utils->fetch_assoc()){;
				
				if($util['id_util']!=$_SESSION['user'] AND $util['workflow']==1){
			

				$sujet="Nouveau sujet $interface sur l'espace ".$esp['titre'];
	$body="Bonjour ".$util['prenom'].",<br>
	Un nouveau sujet vient d'&ecirc;tre cr&eacute;&eacute; dans la categorie \"". $cat2['titre_cat']."\" de l'espace ".$esp['titre']." <br>
	<p>Titre : ".stripslashes($titre)."</p>
	<p>Message : <br>".stripslashes(nl2br($question))."</p>
	http://renta.atr-ingenierie.fr";
	$entete="Content-type:text/html\nFrom:$expediteur";
if($fonction_mail==true){

envoi_mail($body,$body,"",$expediteur,$util['mail'],$sujet);
}
				}
				}}
				}}
				
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////AVetissement nouvelle reponse

if(isset($_POST['reponse'])){

$req_droits="SELECT u.id_util,u.prenom,u.nom,u.mail,g.id_groupe,u.workflow,u.affiche FROM utilisateurs u LEFT JOIN groupes_utilisateur g ON u.id_util=g.id_util WHERE g.id_espace=".$_SESSION['espace'];
if($quer_droits=$wikeo->query($req_droits)){; 
while($droit=$quer_droits->fetch_assoc()){;

$perm=perm_question($wikeo,$_SESSION['question'],$_SESSION['afcat']['id'],$droit['id_groupe'], $droit['id_util']);
				

if($perm['question']==true OR $perm['categorie']!=0){

$ini_suivi="SELECT id_bloque FROM suivi_bloque WHERE id_util=".$droit['id_util']." AND id_question=".$_SESSION['question'];
$req_suivi=mysqli_query($wikeo,$ini_suivi);
$nbbloque=mysqli_num_rows($req_suivi);	



if($droit['workflow']==1){
	if($nbbloque==0){$envoi=true;}else{$envoi=false;}

}else{
$req_nb=mysqli_query($wikeo,"SELECT id_reponse FROM reponses WHERE id_question=".$_SESSION['question']." AND id_util=".$droit['id_util']);
$mesrep=mysqli_num_rows($req_nb);

$req_suivi=mysqli_query($wikeo,"SELECT id_active FROM suivi_active WHERE id_question=".$_SESSION['question']." AND id_util=".$droit['id_util']);
$messuivi=mysqli_num_rows($req_suivi);

$reqor=mysqli_query($wikeo,"SELECT id_util FROM questions WHERE id_question=".$_SESSION['question']);
$or=mysqli_num_rows($reqor);

if($droit['id_util']==$or['id_util'] OR $mesrep!=0){
if($nbbloque==0){$envoi=true;}else{$envoi=false;}
}else{
	if($messuivi==1){$envoi=true;}else{$envoi=false;}
}
}




if($envoi==true){	
if($droit['id_util']!=$_SESSION['user']){		

if($droit['affiche']==0){
$nombreDeMessagesParPage = $config_nb_message;
}else{
	$nombreDeMessagesParPage = $droit['affiche'];
}
$nombreDePagesUtil = ceil($totalDesMessages / $nombreDeMessagesParPage);

	$sujet="Nouvelle reponse $interface dans l'espace ".$esp['titre'];
	$body="Bonjour ".$droit['prenom'].",<br>
	<p>Une nouvelle r&eacute;ponse pour un sujet que vous suivez a &eacute;t&eacute; post&eacute;e</p>
	
	<a href='http://renta.atr-ingenierie.fr/accueil.php?afcat=".$_SESSION['afcat']['id']."&affiche=thread&question=".$_SESSION['question']."&page=$nombreDePagesUtil#rep_$recupnum'>Voir la question (vous devez &ecirc;tre identifi&eacute; pour que ce lien fonctionne)</a>";

	$entete="Content-type:text/html\nFrom:$expediteur";
	if($fonction_mail==true){
envoi_mail($body,$body,"",$expediteur,$droit['mail'],$sujet);
	}
}
}

}

}

}



header("Location:accueil.php?affiche=thread&page=$nombreDePages#rep_$recupnum");

}



if(isset($_POST['ajoutgps'])){
	
$req_utilsgp=mysqli_query($wikeo,"SELECT u.prenom,u.nom,u.mail,g.id_groupe FROM utilisateurs u LEFT JOIN groupes_utilisateur g ON u.id_util=g.id_util WHERE g.id_groupe=$valeur");
$req_question=mysqli_query($wikeo,"SELECT * FROM questions q LEFT JOIN categories c ON q.id_cat_princ=c.id_cat WHERE q.id_question=".$_SESSION['question']);
$ques=mysqli_fetch_assoc($req_question);
while($utilsgp=mysqli_fetch_assoc($req_utilsgp)){; 
$sujet="Mise a jour des droits $interface sur l'espace ".$esp['titre'];
	$body="Bonjour ".$utilsgp['prenom'].",<br>
	Des droits viennent de vous &ecirc;tre ajout&eacute;s pour une question.
	<p>Cat&eacute;gorie : ".$ques['titre_cat']." </p>
	<p>Titre : <br>".$ques['titre']." </p>
	<p>Texte : <br>".nl2br($ques['question'])."</p>
	http://renta.atr-ingenierie.fr";
	$entete="Content-type:text/html\nFrom:$expediteur";
	if($fonction_mail==true){
envoi_mail($body,$body,"",$expediteur,$utilsgp['mail'],$sujet);
	}

}
}
			
?>