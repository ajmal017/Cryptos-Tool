<?php 
include("fonctions/connexion.php");
include("fonctions/fonctions.php");


$url = 'http://ltcgear.com/product/dual-mod-m10/';
$titre='LtcGear MOD_M10';

$contenu=addslashes(htmlspecialchars(implode('', file($url))));
$contenu=str_replace('"',"|",$contenu);


$ins_opt="INSERT INTO surveillance (type,titre,url,contenu)";
$ins_opt.="VALUES (1,'$titre','$url','$contenu')";
$wikeo->query($ins_opt);

$ins_opt="INSERT INTO surveillance (type,titre,url,contenu)";
$ins_opt.="VALUES (2,'$titre','$url','$contenu')";
$wikeo->query($ins_opt);
?> 

