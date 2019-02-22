<?php 
include("fonctions/connexion.php");
include("fonctions/fonctions.php");
include("fonctions/fonctions_mails.php");



/*

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
*/
$contenu=addslashes(htmlspecialchars(implode('', file("http://ltcgear.com/product/asic-share-1k6x/"))));

$mot="Out of stocks";
if (preg_match("/\b".$mot."\b/i", $contenu))
 {
    echo  "Le mot $mot a été trouvée dans la phrase <b>$phrase</b>";
 }
?>