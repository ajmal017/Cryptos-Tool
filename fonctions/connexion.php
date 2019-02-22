<?php
error_reporting( E_ALL & ~( E_NOTICE | E_STRICT | E_DEPRECATED ) );

//include("Mail.php");
//include('Mail/mime.php');

$serveur='localhost';
$util='root';
$mdp='';
$expediteur="s.opros@gmail.com";
$interface="Rentas";


$wikeo = @mysqli_connect($serveur, $util, $mdp, 'renta');







if(isset($_SESSION['espace'])){
$ini_esp=mysqli_query($wikeo,"SELECT * FROM espaces WHERE id_espace=".$_SESSION['espace']);
$esp=mysqli_fetch_assoc($ini_esp);
}

if(isset($_SESSION['user'])){
$ini_user=mysqli_query($wikeo,"SELECT * FROM utilisateurs WHERE id_util=".$_SESSION['user']);
$user=mysqli_fetch_assoc($ini_user);

$ini_groupe_a=mysqli_query($wikeo,"SELECT * FROM groupes_utilisateur WHERE id_espace=".$_SESSION['espace']." AND id_util=".$_SESSION['user']);
$usergroupe=mysqli_fetch_assoc($ini_groupe_a);

}

              if(isset($_GET['afcat'])){
$_SESSION['afcat']['categorieg']="tout";
$_SESSION['afcat']['optiong']="tout";	
$_SESSION['afcat']['categorie']="tout";
$_SESSION['afcat']['option']="tout";
$_SESSION['tri']['tri']="reponse";
$_SESSION['afcat']['sens']="DESC";	
	


				  $_SESSION['afcat']['id']=$_GET['afcat'];}if(!isset($_SESSION['afcat']['id'])){$_SESSION['afcat']['id']=0;}$afcat=$_SESSION['afcat']['id'];
               if(isset($_GET['affiche'])){$affiche=$_GET['affiche'];}if(isset($_POST['affiche'])){$affiche=$_POST['affiche'];}if(!isset($affiche)){$affiche="resume";}
	
		if($_SESSION['afcat']['id']!=0)   {
		  $query_cat2="SELECT * FROM categories WHERE id_cat=".$_SESSION['afcat']['id'];
				if ($result_cat2 = $wikeo->query($query_cat2)) {
				$cat2 = $result_cat2->fetch_assoc();
				$nomcat=$cat2['titre_cat'];
				}}else{$nomcat="R&eacute;sum&eacute;";}
				
$fonction_mail=true;
			
$config_nb_message=8;
?>
