<?php 
session_start();
if($_SESSION['super_admin']==1 OR $_SESSION['admin']==1){
include("fonctions/connexion.php");
include("fonctions/fonctions.php");


if(isset($_POST['ajout'])){
	$nom=addslashes($_POST['nom']);
	$prenom=addslashes($_POST['prenom']);
	$societe=$_POST['societe'];
	$sexe=$_POST['genre'];
	$mail=$_POST['mail'];
	$groupe=$_POST['groupe'];
	$espace=$_SESSION['espace'];
	$nv_pass=MDP();
	$code=encode($nv_pass);
	if($_POST['admin']==1){$admin=1;}else{$admin=0;}
	if($_POST['workflow']==1){$workflow=1;}else{$workflow=0;}

$titre_espace=$esp['titre'];

$ins_util="INSERT INTO utilisateurs (sexe,nom,prenom,mail,id_societe,passe,workflow)";
$ins_util.="VALUES ('$sexe','$nom','$prenom','$mail','$societe','$code','$workflow')";
$wikeo->query($ins_util);
$recupnum=$wikeo->insert_id;

$ins_esp="INSERT INTO espaces_droits (id_espace,id_util,admin)";
$ins_esp.="VALUES ('$espace','$recupnum','$admin')";
$wikeo->query($ins_esp);

$ins_gp="INSERT INTO groupes_utilisateur (id_espace,id_groupe,id_util)";
$ins_gp.="VALUES ('$espace','$groupe','$recupnum')";
$wikeo->query($ins_gp);

$creation_compte=true;
include("fonctions/fonctions_mails.php");
}




if(isset($_POST['modification_compte'])){
	$nom=addslashes($_POST['nom']);
	$prenom=addslashes($_POST['prenom']);
	$societe=$_POST['societe'];
	$sexe=$_POST['genre'];
	$mail=$_POST['mail'];
$passe=$_POST['passe'];
$groupe=$_POST['groupe'];

	if($_POST['admin']==1){$admin=1;}else{$admin=0;}
	if($_POST['workflow']==1){$workflow=1;}else{$workflow=0;}

$requette="UPDATE utilisateurs SET nom='$nom',prenom='$prenom',id_societe='$societe',sexe='$sexe',mail='$mail',workflow='$workflow'";
if($passe!=""){
$code=encode($passe);

$requette.=",passe='$code'";
}
$requette.=" WHERE id_util='".$_POST['modification_compte']."'";

$update=mysqli_query($wikeo,$requette);
$update2=mysqli_query($wikeo,"UPDATE espaces_droits SET admin='$admin' WHERE id_util='".$_POST['modification_compte']."'");
$update3=mysqli_query($wikeo,"UPDATE groupes_utilisateur SET id_groupe='$groupe' WHERE id_util='".$_POST['modification_compte']."'");

}


if(isset($_POST['ajout_soc'])){
	$societe=$_POST['societe'];
$ins_util="INSERT INTO societes (id_espace,societe)";
$ins_util.="VALUES ('".$_SESSION['espace']."','$societe')";
$wikeo->query($ins_util);
}
if(isset($_GET['suprim_soc'])){
	$dele=mysqli_query($wikeo,"DELETE FROM societes WHERE id_societe=".$_GET['suprim_soc']);
}


if(isset($_POST['ajout_carte'])){
	$carte=$_POST['carte'];
$ins_util="INSERT INTO cartes (idutil,nom)";
$ins_util.="VALUES ('1','$carte')";
$wikeo->query($ins_util);
$ins_carte="CREATE TABLE C$carte LIKE C280X";
$wikeo->query($ins_carte);
}
if(isset($_GET['suprim_carte'])){
	$carte=$_GET['carte'];
	$dele=mysqli_query($wikeo,"DELETE FROM cartes WHERE id=".$_GET['suprim_carte']);
	$sql = mysqli_query($wikeo,"DROP TABLE C$carte");
}


if(isset($_POST['ajout_algo'])){
	$algo=$_POST['algo'];
$ins_util="INSERT INTO algos (algo)";
$ins_util.="VALUES ('$algo')";
$wikeo->query($ins_util);
}
if(isset($_GET['suprim_algo'])){
	$dele=mysqli_query($wikeo,"DELETE FROM algos WHERE id=".$_GET['suprim_algo']);
}


if(isset($_POST['ajout_hash'])){
	
	
	$idalgo=$_POST['idalgo'];
	$idcarte=$_POST['idcarte'];
	
	
$ins_util="INSERT INTO hashrates (idcarte,idalgo)";
$ins_util.="VALUES ('$idcarte','$idalgo')";
$wikeo->query($ins_util);
}
if(isset($_GET['suprim_hash'])){
	$dele=mysqli_query($wikeo,"DELETE FROM hashrates WHERE id_hash=".$_GET['suprim_hash']);
}


if(isset($_POST['modif_hash'])){
$modif_hash=$_POST['modif_hash'];
$hashrate=$_POST['hashrate'];
$unite=$_POST['unite'];
if($unite=="H"){$hashrate=($hashrate);}
if($unite=="Kh"){$hashrate=($hashrate*1000);}
if($unite=="Mh"){$hashrate=($hashrate*1000000);}
if($unite=="Gh"){$hashrate=($hashrate*1000000000);}

$update2=mysqli_query($wikeo,"UPDATE hashrates SET hashrate='$hashrate',conso='".$_POST['conso']."' WHERE id_hash='$modif_hash'");
}



if(isset($_POST['ins_reglage'])){
	$ins_reglage=$_POST['ins_reglage'];
	$texte=stripslashes($_POST['ins_texte']);
	$update2=mysqli_query($wikeo,"UPDATE reglages SET reglage='$texte' WHERE id='$ins_reglage'");
	
}

if(isset($_POST['ins_crypto'])){
if($_POST['algo']!=22){
$ins_util="INSERT INTO cryptos (sigle,nom,user,pass,ip,port,actif,coursprev,btk,cryptonight)";
$ins_util.="VALUES ('".$_POST['sigle']."','".$_POST['nom']."','".$_POST['user']."','".$_POST['pass']."','".$_POST['ip']."','".$_POST['port']."','1','".$_POST['coursprev']."','".$_POST['btk']."','0')";
}else{
	$ins_util="INSERT INTO cryptos (sigle,nom,user,pass,ip,port,actif,coursprev,btk,cryptonight)";
$ins_util.="VALUES ('".$_POST['sigle']."','".$_POST['nom']."','','','','','1','".$_POST['coursprev']."','".$_POST['btk']."','1')";
}
$wikeo->query($ins_util);
$recupid=$wikeo->insert_id;


if($_POST['algo']!=""){
$ins_util="INSERT INTO cryptos_algos (id_crypto,sigle,nom,algo,actif)";
$ins_util.="VALUES ('$recupid','".$_POST['sigle']."','".$_POST['nom']."','".$_POST['algo']."','1')";
$wikeo->query($ins_util);
}
}


if(isset($_GET['suprim_crypto'])){
	$dele=mysqli_query($wikeo,"DELETE FROM cryptos WHERE id_crypto=".$_GET['suprim_crypto']);
	$dele=mysqli_query($wikeo,"DELETE FROM cryptos_algos WHERE id_crypto=".$_GET['suprim_crypto']);
}

if(isset($_POST['modif_crypto'])){ 

$update2=mysqli_query($wikeo,"UPDATE cryptos SET sigle='".$_POST['sigle']."',nom='".$_POST['nom']."',user='".$_POST['user']."',pass='".$_POST['pass']."',ip='".$_POST['ip']."',port='".$_POST['port']."',btk='".$_POST['btk']."',actif='".$_POST['actif']."',coursprev='".$_POST['coursprev']."' WHERE id_crypto='".$_POST['modif_crypto']."'");

$update3=mysqli_query($wikeo,"UPDATE cryptos_algos SET nom='".$_POST['nom']."' WHERE id_crypto='".$_POST['modif_crypto']."'");
//echo "<br><br><br><br><br><br><br><br>";
if($_POST['actif']==0){
$ini_cartes=mysqli_query($wikeo,"SELECT * FROM cartes");
$update3=mysqli_query($wikeo,"UPDATE cryptos_algos SET actif='0' WHERE id_crypto='".$_POST['modif_crypto']."'");
while($cartes=mysqli_fetch_assoc($ini_cartes)){ 
$dele=mysqli_query($wikeo,"DELETE FROM C".$cartes['nom']." WHERE id_crypto=".$_POST['modif_crypto']);
//echo "<br>DELETE FROM C".$cartes['nom']." WHERE id_crypto=".$_POST['modif_crypto'];
}
}else{
$update3=mysqli_query($wikeo,"UPDATE cryptos_algos SET actif='1' WHERE id_crypto='".$_POST['modif_crypto']."'");
}


}


if(isset($_POST['ajout_bloc'])){
$ins_util="INSERT INTO blocs (id_crypto,debut,fin,reward)";
$ins_util.="VALUES (".$_POST['ajout_bloc'].",'".$_POST['debut']."','".$_POST['fin']."',".$_POST['reward'].")";
$wikeo->query($ins_util);
$modif_crypto=$_POST['ajout_bloc'];
}

if(isset($_GET['suprim_bloc'])){
	$dele=mysqli_query($wikeo,"DELETE FROM blocs WHERE id=".$_GET['suprim_bloc']);
$modif_crypto=$_GET['modif_crypto'];
}

if(isset($_POST['modif_bloc'])){ 

$update2=mysqli_query($wikeo,"UPDATE blocs SET debut='".$_POST['debut']."',fin='".$_POST['fin']."',reward=".$_POST['reward']." WHERE id='".$_POST['modif_bloc']."'");
}

if(isset($_POST['ajout_cryptalgo'])){
$ins_util="INSERT INTO cryptos_algos (id_crypto,sigle,nom,algo,multi,multi2,actif)";
$ins_util.="VALUES (".$_POST['ajout_cryptalgo'].",'".$_POST['sigle']."','".$_POST['nom']."','".$_POST['algo']."','".$_POST['multi']."','".$_POST['multi2']."','1')";

$wikeo->query($ins_util);
$modif_crypto=$_POST['ajout_cryptalgo'];
}

if(isset($_GET['suprim_cryptalgo'])){
	
	$dele=mysqli_query($wikeo,"DELETE FROM cryptos_algos WHERE id_crypto_algo=".$_GET['suprim_cryptalgo']);
$modif_crypto=$_GET['modif_crypto'];
}

if(isset($_POST['modif_cryptalgo'])){ 

$update2=mysqli_query($wikeo,"UPDATE cryptos_algos SET sigle='".$_POST['sigle']."',nom='".$_POST['nom']."',algo='".$_POST['algo']."',multi='".$_POST['multi']."',multi2='".$_POST['multi2']."',actif='".$_POST['actif']."' WHERE id_crypto_algo='".$_POST['modif_cryptalgo']."'");
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
</head>

<body>
        <?php 
	
		include("header.php"); 
		

		  
		?>
            
            <div class="container" >
            <section class="box2" style='margin-top:69px;padding:5px'>
            
            <div style='text-align:left;'>
            <div class='<?php if($affiche=="societes"){echo "boutonvert";}else{echo "bouton";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='configuration.php?affiche=societes'">Soci&eacutet&eacute;s</div>
            
            	<div class='<?php if($affiche=="utilisateurs"){echo "boutonvert";}else{echo "bouton";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='configuration.php?affiche=utilisateurs'">Utilisateurs</div>
                
                            	<div class='<?php if($affiche=="cartes"){echo "boutonvert";}else{echo "bouton";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='configuration.php?affiche=cartes'">Cartes</div>
                                                                                            	<div class='<?php if($affiche=="algos"){echo "boutonvert";}else{echo "bouton";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='configuration.php?affiche=algos'">Algos</div>
                                                                                                
                                            	<div class='<?php if($affiche=="hashrates"){echo "boutonvert";}else{echo "bouton";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='configuration.php?affiche=hashrates'">Hashrates</div>
                                                
                                                

                                                                
                                                                            	<div class='<?php if($affiche=="cryptos"){echo "boutonvert";}else{echo "bouton";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='configuration.php?affiche=cryptos'">Cryptos</div>
                                                                                
                                                                                            	<div class='<?php if($affiche=="connexions"){echo "boutonvert";}else{echo "bouton";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='configuration.php?affiche=connexions'">Connexions</div>
            	
</div>
             
             
              <div class='encarts' style='margin-top:10px;margin-bottom:20px;padding-bottom:20px'>
<?php if($affiche=="utilisateurs"){ ?>              
                <div class='entete_encarts'>Gestion des utilisateurs</div>
                <form name="form1" method="post" action="configuration.php?affiche=utilisateurs">

                    <div class='stitre_encart'>Ajouter un utilisateur</div>
                    <fieldset>
                      <table width="787" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="20%" height="26" align="left">&nbsp;</td>
                          <td width="29%" align="left">Pr&eacute;nom</td>
                          <td width="51%" align="left">Nom</td>
                        </tr>
                        <tr>
                          <td height="50" align="left"><label for="sexe3"></label>
                            <select name="genre" id="sexe3">
                              <option value="M.">M.</option>
                              <option value="Mme">Mme</option>
                          </select></td>
                          <td align="left"><label for="prenom3"></label>
                            <input name="prenom" type="text" id="prenom3" size="20"></td>
                          <td align="left"><input name="nom" type="text" id="nom" size="20"></td>
                        </tr>
                        <tr>
                          <td height="40" align="left">Soci&eacute;t&eacute;</td>
                          <td colspan="2" align="left"><select name="societe" id="societe">
                            <option value=""></option>
                            <?php $ini_soc=mysqli_query($wikeo,"SELECT * FROM societes WHERE id_espace=".$_SESSION['espace']);
						  while($row2=mysqli_fetch_assoc($ini_soc)){
							  ?>
                            <option value="<?php echo $row2['id_societe'] ?>"><?php echo $row2['societe'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td height="42" align="left">Mail</td>
                          <td colspan="2" align="left"><input name="mail" type="text" id="mail" size="30"></td>
                        </tr>
                        <tr>
                          <td height="39" align="left">groupe</td>
                          <td colspan="2" align="left"><select name="groupe" id="groupe">
                            <option value=""></option>
                            <?php $ini_gp=mysqli_query($wikeo,"SELECT * FROM groupes WHERE id_espace=".$_SESSION['espace']." order by titre");
						  while($gp=mysqli_fetch_assoc($ini_gp)){
							  ?>
                            <option value="<?php echo $gp['id_groupe'] ?>"><?php echo $gp['titre'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td height="29" align="left">Admin</td>
                          <td align="left"><input name="admin" type="checkbox" id="admin" value="1">
                          <input name="ajout" type="hidden" id="ajout" value="util"></td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="35" align="left">Workflow complet</td>
                          <td align="left"><input name="workflow" type="checkbox" id="workflow" value="1"></td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><input type="submit" name="button" id="button" value="Ajouter"  class='button' style='padding-top:3px;padding-bottom:3px'></td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </fieldset></form>
                    <div class='stitre_encart'>Liste des utilisateurs avec droits sur l'espace</div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="7%" height="26" align="left">&nbsp;</td>
                        <td width="11%" align="left">Pr&eacute;nom</td>
                        <td width="11%" align="left">Nom</td>
                        <td width="11%" align="left">Soci&eacute;t&eacute;</td>
                        <td width="16%" align="left">Mail</td>
                        <td width="15%" align="left">Passe</td>
                        <td width="6%" align="left">Admin</td>
                        <td width="8%" align="left">Groupe</td>
                        <td width="6%" align="left">Workflow</td>
                        <td width="9%" align="left">&nbsp;</td>
                      </tr>
                    </table>
                    <?php $ini_utils=mysqli_query($wikeo,"SELECT * FROM utilisateurs u LEFT JOIN espaces_droits e ON u.id_util=e.id_util LEFT JOIN groupes_utilisateur gu ON gu.id_util=u.id_util LEFT JOIN groupes g ON g.id_groupe=gu.id_groupe WHERE e.id_espace=".$_SESSION['espace']);
						  while($row=mysqli_fetch_assoc($ini_utils)){
							  ?>
                              <form name="form1" method="post" action="configuration.php?affiche=utilisateurs">
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="7%" align="left"><label for="societe"></label>
                          <select name="genre" id="genre">
                            <option value="M." <?php if ($row['sexe']=="M."){echo "selected='selected'";} ?>>M.</option>
                            <option value="Mme" <?php if ($row['sexe']=="Mme"){echo "selected='selected'";} ?>>Mme</option>
                          </select></td>
                        <td width="11%" align="left"><input name="prenom" type="text" id="prenom3" value="<?php echo $row['prenom'] ?>" size="12"></td>
                        <td width="11%" align="left"><input name="nom" type="text" id="nom" value="<?php echo $row['nom'] ?>" size="12"></td>
                        <td width="12%" align="left"><select name="societe" id="societe">
                          <?php $ini_soc=mysqli_query($wikeo,"SELECT * FROM societes WHERE id_espace=".$_SESSION['espace']);
						  while($row2=mysqli_fetch_assoc($ini_soc)){
							  ?>
                          <option value="<?php echo $row2['id_societe'] ?>"><?php echo $row2['societe'] ?></option>
                          <?php } ?>
                        </select></td>
                        <td width="15%" align="left"><input name="mail" type="text" id="mail" value="<?php echo $row['mail'] ?>" size="15"></td>
                        <td width="16%" align="left"><input name="passe" type="text" id="passe" value="" size="10">
                          <input name="modification_compte" type="hidden" id="modification_compte" value="<?php echo $row['id_util'] ?>"></td>
                        <td width="5%" align="left"><input name="admin" type="checkbox" id="admin" value="1" <?php if($row['admin']==1){echo "checked";} ?>>
                          <label for="admin"></label></td>
                        <td width="9%" align="left"><select name="groupe" id="groupe">
                            <option value=""></option>
                            <?php $ini_gp=mysqli_query($wikeo,"SELECT * FROM groupes WHERE id_espace=".$_SESSION['espace']." order by titre");
						  while($gp=mysqli_fetch_assoc($ini_gp)){
							  ?>
                            <option value="<?php echo $gp['id_groupe'] ?>" <?php if($row['id_groupe']==$gp['id_groupe']){echo "selected='selected'";} ?>><?php echo $gp['titre'] ?></option>
                            <?php } ?>
                          </select></td>
                        <td width="5%" align="left"><input name="workflow" type="checkbox" id="workflow" value="1" <?php if($row['workflow']==1){echo "checked";} ?>></td>
                        <td width="9%" align="left"><input type="submit" name="button2" id="button2" value="Modifier" class='button' style='padding-top:3px;padding-bottom:3px'></td>
                      </tr>
                    </table></form> 
                    <?php } ?>
          
                
                
<?php }elseif($affiche=="societes"){ ?>

<div  style='float:left'>
        <form name="form1" method="post" action="configuration.php?affiche=societes">
          <div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Nouvelle soci&eacute;t&eacute;
            <input name="ajout_soc" type="hidden" id="ajout_soc" value="1">
          </div>
          <label for="societe"></label>
                <input type="text" name="societe" id="societe">
          <input type="submit" name="button3" id="button3" value="Ajouter" class='bouton'>
        </form>
                 </div>

                <div style='float:left;margin-bottom:30px;margin-left:50px;text-align:left;'>
                
                 <form name="form1" method="post" action="accueil.php?affiche=permissions">
<div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Soci&eacute;t&eacute;s d&eacute;ja cr&eacute;&eacute;es</div>
				  <?php
                $rlistegp=mysqli_query($wikeo,"SELECT * FROM societes WHERE id_espace=".$_SESSION['espace']);
				while($liste=mysqli_fetch_assoc($rlistegp)){ ?><div style='float:left'>
                <a href=configuration.php?suprim_soc=<?php
				echo $liste['id_societe'] ?>&affiche=societes><img src='images/supprimer.png' width='18' height='18' align='absmiddle'></a></div><div style='line height:28px;margin-left:25px;'><?php
				echo $liste['societe'] ?></div><div style='clear:both;height:5px'></div>
                
                
              <?php
				} ?></div>
               <div style='clear:both;margin-bottom:30px;'></div>
<?php }elseif($affiche=="cartes"){ ?>

<div  style='float:left'>
        <form name="form1" method="post" action="configuration.php?affiche=cartes">
          <div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Nouvelle carte
            <input name="ajout_carte" type="hidden" id="ajout_carte" value="1">
          </div>
          <label for="carte"></label>
                <input type="text" name="carte" id="carte">
          <input type="submit" name="button3" id="button3" value="Ajouter" class='bouton'>
        </form>
                 </div>

                <div style='float:left;margin-bottom:30px;margin-left:50px;text-align:left;'>
                
                
<div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Cartes existantes</div>
				  <?php
                $rlistegp=mysqli_query($wikeo,"SELECT * FROM cartes order by nom ASC");
				while($liste=mysqli_fetch_assoc($rlistegp)){ ?><div style='float:left'>
                <?php
				echo "<a href=configuration.php?suprim_carte=".$liste['id']."&carte=".$liste['nom']."&affiche=cartes><img src='images/supprimer.png' width='18' height='18' align='absmiddle'></a></div><div style='line height:28px;margin-left:25px;'>".$liste['nom'] ?><?php echo "</div><div style='clear:both;height:5px'></div>";
                
                
                 } ?>
                
				  </div>
               <div style='clear:both;margin-bottom:30px;'></div>
<?php }elseif($affiche=="connexions"){ 

 if(isset($_POST['userchange']) AND $_POST['userchange']!=""){
	$triutil=$_POST['userchange'];
					  $ajoututil=" WHERE user=".$_POST['userchange']." ";
				  }else{
					  $ajoututil="";
					  $triutil="";
				  }
?>

<div  style='float:left'>
        <form name="form1" method="post" action="configuration.php?affiche=connexions">
          <div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Filtrer
            
          </div>
          <label for="carte"></label>
                <select name="userchange" id="userchange" style='margin-bottom:5px'  placeholder="User" onChange="submit()">
          <option value=""></option>
          <?php $queralgo=mysqli_query($wikeo,"SELECT * FROM utilisateurs order by prenom ASC");
		  while($alg=mysqli_fetch_assoc($queralgo)){ 

		  ?>
            <option value="<?php echo $alg['id_util']  ?>" <?php if ($alg['id_util']==$triutil){echo "selected='selected'";} ?>><?php echo $alg['prenom']  ?></option>
          <?Php } ?>
          </select>
          
        </form>
                 </div>

                <div style='float:left;margin-bottom:30px;margin-left:50px;text-align:left;'>
                
                 
<div class='entete_encarts' style='width:350px;color:#06C;margin-bottom:10px'>Connexions</div>
				  <?php
				  
				 
                $rlistegp=mysqli_query($wikeo,"SELECT * FROM connexions $ajoututil order by moment DESC");
				while($liste=mysqli_fetch_assoc($rlistegp)){ ?>
                <?php
				echo "<div style='line height:28px;'>".$liste['nomuser']."&nbsp;|&nbsp;".datefr($liste['moment'],'court')."&nbsp;|&nbsp;".$liste['ip']."</div>";
                
                
                 } ?></div>
               <div style='clear:both;margin-bottom:30px;'></div>
<?php }elseif($affiche=="algos"){ ?>

<div  style='float:left'>
        <form name="form1" method="post" action="configuration.php?affiche=algos">
          <div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Nouvel algo
            <input name="ajout_algo" type="hidden" id="ajout_algo" value="1">
          </div>
          <label for="carte"></label>
                <input type="text" name="algo" id="algo">
          <input type="submit" name="button3" id="button3" value="Ajouter" class='bouton'>
        </form>
                 </div>

                <div style='float:left;margin-bottom:30px;margin-left:50px;text-align:left;'>
                
                 
<div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Algos existants</div>
				  <?php
                $rlistegp=mysqli_query($wikeo,"SELECT * FROM algos order by algo ASC");
				while($liste=mysqli_fetch_assoc($rlistegp)){ ?><div style='float:left'>
                <?php
				echo "<a href=configuration.php?suprim_algo=".$liste['id']."&affiche=algos><img src='images/supprimer.png' width='18' height='18' align='absmiddle'></a></div><div style='line height:28px;margin-left:25px;'>".$liste['algo']."</div><div style='clear:both;height:5px'></div>";
                
                
                 } ?></div>
               <div style='clear:both;margin-bottom:30px;'></div>
<?php }elseif($affiche=="hashrates"){
if(isset($_GET['carte'])){$carte=$_GET['carte'];}else{$carte="";}
?>
<div class='entete_encarts'><?php $rlistegp=mysqli_query($wikeo,"SELECT * FROM cartes order by nom ASC");
				while($liste=mysqli_fetch_assoc($rlistegp)){ ?>
    <div class='<?php if($carte==$liste['id']){ echo "boutonvert";}else{echo "boutonrouge";} ?>' style='text-align:left;margin-left:10px;margin-top:10px;display:inline-block' onClick="Javascript: window.location='configuration.php?affiche=hashrates&carte=<?php echo $liste['id'] ?>'"><?php echo $liste['nom'] ?></div>      <?php }  ?>
</div> <?php if($carte!=""){ 
 if(!isset($_GET['reglage'])){
 ?>      
               
<div  style='float:left'>
        <form name="form1" method="post" action="configuration.php?affiche=hashrates&carte=<?php echo $carte ?>">
          <div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Nouveau Hashrate
            <input name="ajout_hash" type="hidden" id="ajout_hash" value="1">
            <input name="idcarte" type="hidden" id="idcarte" value="<?php echo $carte ?>">
          </div>
          <label for="hashrate"></label>
                <select name="idalgo" >
                <?php 
				
				$ini_listhash=mysqli_query($wikeo,"SELECT * FROM algos order by algo");
				while($listhash=mysqli_fetch_assoc($ini_listhash)){ 
				$reqsql="SELECT * FROM hashrates WHERE idalgo=".$listhash['id']. " AND idcarte=$carte";
				
				$ini_verif=mysqli_query($wikeo,$reqsql);
				$verif=mysqli_num_rows($ini_verif);
				if($verif==0){
				?>
                  <option value="<?php echo $listhash['id'] ?>"><?php echo $listhash['algo'] ?></option>
                  <?php }} ?>
                </select>
          <input type="submit" name="button3" id="button3" value="Ajouter" class='bouton'>
        </form>
                 </div>

                <div style='float:left;margin-bottom:30px;margin-left:50px;text-align:left;'>
                
                 
<div class='entete_encarts' style='width:380px;color:#06C;margin-bottom:10px'>Algos existants</div>
				  <?php
                $rlistegp2=mysqli_query($wikeo,"SELECT * FROM hashrates h LEFT JOIN algos a ON h.idalgo=a.id where h.idcarte=$carte order by a.algo ASC");
				while($listeh=mysqli_fetch_assoc($rlistegp2)){ $hashrate= hashrate($listeh['hashrate']);
				
			   $ini_reglage=mysqli_query($wikeo,"SELECT * FROM reglages WHERE id_algo=".$listeh['idalgo']." AND id_carte=$carte");
			   $verif_reglage=mysqli_num_rows($ini_reglage);
			   
				?><form name="form<?php echo $listeh['id'] ?>" method="post" action="configuration.php?affiche=hashrates&carte=<?php echo $carte ?>"><div style='float:left'>
                
                <?php
				echo "<a href=configuration.php?suprim_hash=".$listeh['id_hash']."&affiche=hashrates&carte=$carte><img src='images/supprimer.png' width='18' height='18' align='absmiddle'></a></div><div style='line height:28px;float:left;margin-left:15px;'>".$listeh['algo']." : </div>"; ?><div style='float:right;margin-left:10px'><select name="unite" class='bouton'>
                  <option value="H" <?php if($hashrate['unite']=="H"){echo "selected='selected'";} ?>>H</option>
                  <option value="Kh" <?php if($hashrate['unite']=="Kh"){echo "selected='selected'";} ?>>Kh</option>
                  <option value="Mh" <?php if($hashrate['unite']=="Mh"){echo "selected='selected'";} ?>>Mh</option>
                  <option value="Gh" <?php if($hashrate['unite']=="Gh"){echo "selected='selected'";} ?>>Gh</option>
                </select><input type='text' name='conso' id='conso' value="<?php echo $listeh['conso'] ?>" size='3' style='margin-left:5px'><input name="modif_hash" type="hidden" id="modif_hash" value="<?php echo $listeh['id_hash'] ?>"><input type="submit" name="button3" id="button3" value="Modifier" class='bouton' style='margin-left:10px'>
                <a href="configuration.php?affiche=hashrates&carte=<?php echo $carte ?>&reglage=<?php echo $listeh['idalgo'] ?>" class="fa featured   fa-file-text" style='font-size:19px;display:inline;color:<?php if($verif_reglage==1){echo "#4E87DC";}else{echo "#B7C1C1";} ?>;cursor:pointer;margin-left:10px'></a>
                </div> 
                <?php echo "<div style='float:right;margin-left:25px;'><input type='text' name='hashrate' id='hashrate' value='".$hashrate['hash']."' size='3'></div>"; ?>
                <div style='clear:both;height:5px'></div>
               
                </form>
               <?php  } ?>
			   
			   
			   <?php }else{ 
			   $reglage=$_GET['reglage'];
			   
			   $ini_algo=mysqli_query($wikeo,"SELECT * FROM algos WHERE id=$reglage");
			   $algo=mysqli_fetch_assoc($ini_algo);
			   $inirec="SELECT * FROM reglages WHERE id_algo=$reglage AND id_carte=$carte";
			   $ini_reglage=mysqli_query($wikeo,$inirec);
			   $verif_reglage=mysqli_num_rows($ini_reglage);
			   if($verif_reglage==0){
$ins_util="INSERT INTO reglages (id_algo,id_carte)";
$ins_util.="VALUES ('$reglage','$carte')";
//echo $ins_util;
$wikeo->query($ins_util);
$idreglage=$wikeo->insert_id;
$texte="";
}else{
	$reglage=mysqli_fetch_assoc($ini_reglage);
	$idreglage=$reglage['id'];
	$texte=$reglage['reglage'];
			   }
			   ?>
               
                  <div style='float:left;margin-bottom:30px;margin-left:50px;text-align:left;'>
                
                 
<div class='entete_encarts' style='width:350px;color:#06C;margin-bottom:10px'>Reglages sur l'algorithme <?php echo  $algo['algo'] ?></div>

<form name="form2" method="post" action="configuration.php?affiche=hashrates&carte=<?php echo $carte ?>">
              <div>
               <p>
                  <textarea name="ins_texte" cols="110" rows="15" id="ins_texte"><?php echo $texte ?></textarea>
                </p>
                <input name="ins_reglage" type="hidden" id="ins_reglage" value="<?php echo $idreglage ?>">
                <input type="submit" value="Envoyer" class='button' style='margin-top:10px' />
               
              </div>
              </form>
</div>

<?php } ?> <div style='clear:both;margin-bottom:30px;'></div> <?php } ?></div>
               <div style='clear:both;margin-bottom:30px;'></div>
<?php }elseif($affiche=="cryptos"){ ?>

<div  style='float:left;text-align:left'>
        <form name="form1" method="post" action="configuration.php?affiche=cryptos" style='margin-bottom:5px'>
          <div class='entete_encarts' style='width:250px;color:#06C;margin-bottom:10px'>Ajout
           
          </div>
          <label for="carte"></label>
                <input name="sigle" type="text" id="sigle" value="" size="5" style='margin-bottom:5px' placeholder="Sigle">
          <input name="nom" type="text" id="nom" value=""style='margin-bottom:5px' placeholder="nom"><br>
          <input name="ip" type="text" id="ip" value="192.168.1.233" size="12" style='margin-bottom:5px'>
          <select name="algo" id="algo" style='margin-bottom:5px'  placeholder="Algo">
          <option value=""></option>
          <?php $queralgo=mysqli_query($wikeo,"SELECT * FROM algos");
		  while($alg=mysqli_fetch_assoc($queralgo)){ 
		  $retmaxport=mysqli_query($wikeo,"SELECT port FROM cryptos order by port DESC LIMIT 1");
		  $maxport=mysqli_fetch_assoc($retmaxport);
		  ?>
            <option value="<?php echo $alg['id']  ?>"><?php echo $alg['algo']  ?></option>
          <?Php } ?>
          </select>
          <br>
                <input name="user" type="text" id="user" value="solo" size="5"style='margin-bottom:5px'>
                <input name="pass" type="text" id="pass" value="test" size="5"style='margin-bottom:5px'>
                
                <input name="port" type="text" id="port" value="<?php echo $maxport['port']+1; ?>" size="5"style='margin-bottom:5px'><br>
                
                <input name="coursprev" type="text" id="coursprev" value="" size="8"style='margin-bottom:5px' placeholder="Cours-prev"><br>
                <input name="btk" type="text" id="btk" value="" size="18"style='margin-bottom:5px' placeholder="BticoinTalk"><br>
                <input name="ins_crypto" type="hidden" id="ins_crypto" value="1">
          <input type="submit" name="button3" id="button3" value="Ajouter" class='bouton'>
        </form>
                 </div>

                <div style='float:left;margin-bottom:30px;margin-left:50px;text-align:left;'>
                
                 
<div class='entete_encarts' style='width:250px;color:#06C;margin-bottom:10px'>Cryptos enregistr&eacute;s</div>
				  <?php
                $rlistegp=mysqli_query($wikeo,"SELECT * FROM cryptos order by nom ASC");
				while($liste=mysqli_fetch_assoc($rlistegp)){ ?><div style='float:left;text-align:left;margin-right:5px;'>
                <?php
				echo "<a href=configuration.php?modif_crypto=".$liste['id_crypto']."&affiche=cryptos><img src='images/edit.png' width='18' height='18'></a>&nbsp;<a href=configuration.php?suprim_crypto=".$liste['id_crypto']."&affiche=cryptos><img src='images/supprimer.png' width='18' height='18' align='absmiddle'></a></div><div style='line height:28px;margin-left:25px;";if($liste['actif']==0){echo "color:#FEC2C9";} echo "'>".$liste['nom']."&nbsp;[".$liste['port']."]</div><div style='clear:both;height:5px'></div>";
                
                
                 } ?>
                </div>
                 <?php 
				 
				  if (isset($_GET['modif_crypto'])){$modif_crypto=$_GET['modif_crypto'];} 
				 
				 if (isset($modif_crypto)){
				 $rmodc=mysqli_query($wikeo,"SELECT * FROM cryptos WHERE id_crypto=$modif_crypto");
				 $modc=mysqli_fetch_assoc($rmodc);
				 ?>
                 <div  style='float:left;text-align:left'><div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Modification
            
          </div>
        <form name="form1" method="post" action="configuration.php?affiche=cryptos" style='margin-bottom:5px'>
          
          <label for="carte"></label>
                <input name="sigle" type="text" id="sigle" value="<?php echo $modc['sigle'] ?>" size="5" style='margin-bottom:5px' placeholder="Sigle">
          <input name="nom" type="text" id="nom" value="<?php echo $modc['nom'] ?>"style='margin-bottom:5px' placeholder="nom"><br>
          <input name="ip" type="text" id="ip" value="<?php echo $modc['ip'] ?>" size="12" style='margin-bottom:5px'><br>
                <input name="user" type="text" id="user" value="<?php echo $modc['user'] ?>" size="5"style='margin-bottom:5px'>
                <input name="pass" type="text" id="pass" value="<?php echo $modc['pass'] ?>" size="5"style='margin-bottom:5px'>
                
                <input name="port" type="text" id="port" value="<?php echo $modc['port'] ?>" size="5"style='margin-bottom:5px'><br>
               
                
          <input name="coursprev" type="text" id="coursprev" value="<?php echo $modc['coursprev'] ?>" size="8"style='margin-bottom:5px' placeholder="Cours-prev"><br>
                <input name="btk" type="text" id="btk" size="50" style='margin-bottom:5px' value="<?php echo $modc['btk'] ?>" placeholder="BticoinTalk"><br>
                Actif : 
                <select name="actif" id="actif" style='margin-bottom:5px'>
                     
                          <option value="0"<?php if($modc['actif']==0){echo "selected='selected'";}  ?>>Non</option>
                          <option value="1" <?php if($modc['actif']==1){echo "selected='selected'";}  ?>>Oui</option>
          </select>
                <input name="modif_crypto" type="hidden" id="modif_crypto" value="<?php echo $modif_crypto ?>">
                <br>
          <input type="submit" name="button3" id="button3" value="Modifier" class='bouton'>
        </form>
        
        
        
        <div class='entete_encarts' style='width:480px;color:#06C;margin-bottom:10px'>Algos
            
          </div>
          <form name="form2" method="post" action="configuration.php?affiche=cryptos" style='margin-bottom:5px'>
          
         
                 <select name="algo" id="algo" style='margin-bottom:5px'  placeholder="Algo">
         
          <?php $queralgob=mysqli_query($wikeo,"SELECT * FROM algos");
		  while($lalgo=mysqli_fetch_assoc($queralgob)){ 
		  $reqverif=mysqli_query($wikeo,"SELECT * FROM cryptos_algos WHERE id_crypto=$modif_crypto AND algo=".$lalgo['id']);
		  $verif=mysqli_num_rows($reqverif);
		  if($verif==0){
		  ?>
            <option value="<?php echo $lalgo['id']  ?>" <?php if($lalgo['id']==$algo['algo']){echo "selected='selected'";} ?>><?php echo $lalgo['algo']  ?></option>
          <?Php }} ?>
          </select>
          <input name="sigle" type="text" id="sigle"  size="4" style='margin-bottom:5px' value="<?php echo $modc['sigle'] ?>">
          <input name="multi" type="text" id="multi"  size="5" style='margin-bottom:5px'  placeholder="Multi">
          <input name="multi2" type="text" id="multi2"  size="5" style='margin-bottom:5px'  placeholder="Multi2">
                <input name="ajout_cryptalgo" type="hidden" id="ajout_bloc" value="<?php echo $modif_crypto ?>">
                 <input name="nom" type="hidden" id="nom" value="<?php echo $modc['nom'] ?>">
          <input type="submit" name="button3" id="button3" value="Ajouter" class='bouton'>
        </form>
        
        
        <?php 
		
		$quer_algos=mysqli_query($wikeo,"SELECT * FROM cryptos_algos WHERE id_crypto=$modif_crypto");
		$i=7;
	
		while($algo=mysqli_fetch_assoc($quer_algos)){
			
			?>
            <div style='float:left;margin-right:5px;'><a href='configuration.php?suprim_cryptalgo=<?php echo $algo['id_crypto_algo'] ?>&affiche=cryptos&modif_crypto=<?php echo $modif_crypto ?>'><img src='images/supprimer.png' width='18' height='18' align='absmiddle'></a></div>
         <form name="form<?php echo $i ?>" method="post" action="configuration.php?affiche=cryptos&modif_crypto=<?php echo $modif_crypto ?>" style='margin-bottom:5px'>
          
         
                <select name="algo" id="algo" style='margin-bottom:5px'  placeholder="Algo">
          <option value=""></option>
          <?php $queralgob=mysqli_query($wikeo,"SELECT * FROM algos");
		  while($lalgo=mysqli_fetch_assoc($queralgob)){ 
		 
		  ?>
            <option value="<?php echo $lalgo['id']  ?>" <?php if($lalgo['id']==$algo['algo']){echo "selected='selected'";} ?>><?php echo $lalgo['algo']  ?></option>
          <?Php } ?>
          </select>
           <input name="sigle" type="text" id="sigle"  size="4" style='margin-bottom:5px' value="<?php echo $algo['sigle'] ?>">
          <input name="multi" type="text" id="multi"  size="5" style='margin-bottom:5px' value="<?php echo $algo['multi'] ?>" placeholder="Multi">
          <input name="multi2" type="text" id="multi2"  size="5" style='margin-bottom:5px' value="<?php echo $algo['multi2'] ?>" placeholder="Multi2">
           
                <select name="actif" id="actif" style='margin-bottom:5px'>
                     
                          <option value="0"<?php if($algo['actif']==0){echo "selected='selected'";}  ?>>Désactivé</option>
                          <option value="1" <?php if($algo['actif']==1){echo "selected='selected'";}  ?>>Activé</option>
          </select>
               <input name="modif_cryptalgo" type="hidden" id="modif_cryptalgo" value="<?php echo $algo['id_crypto_algo'] ?>">
               <input name="nom" type="hidden" id="nom" value="<?php echo $algo['nom'] ?>">
              
          <input type="submit" name="button3" id="button3" value="Modifier" class='bouton'>
        </form>
        <?php $i++; } ?>
        
        
        
        
        
        
        
        <div class='entete_encarts' style='width:300px;color:#06C;margin-bottom:10px'>Blocs
            
          </div>
          <form name="form2" method="post" action="configuration.php?affiche=cryptos" style='margin-bottom:5px'>
          
         
                <input name="debut" type="text" id="debut"  size="5"   style='margin-bottom:5px' placeholder="Début">
          <input name="fin" type="text" id="fin"  size="5" style='margin-bottom:5px'  placeholder="Fin">
          <input name="reward" type="text" id="reward"  size="5" style='margin-bottom:5px'  placeholder="Reward">
                <input name="ajout_bloc" type="hidden" id="ajout_bloc" value="<?php echo $modif_crypto ?>">
                
          
          <input type="submit" name="button3" id="button3" value="Ajouter" class='bouton'>
        </form>
        
        
        <?php 
		
		$quer_blocs=mysqli_query($wikeo,"SELECT * FROM blocs WHERE id_crypto=$modif_crypto");
		$i=3;
		while($bloc=mysqli_fetch_assoc($quer_blocs)){
			
			?>
            <div style='float:left;margin-right:5px;'><a href='configuration.php?suprim_bloc=<?php echo $bloc['id'] ?>&affiche=cryptos&modif_crypto=<?php echo $modif_crypto ?>'><img src='images/supprimer.png' width='18' height='18' align='absmiddle'></a></div>
         <form name="form<?php echo $i ?>" method="post" action="configuration.php?affiche=cryptos&modif_crypto=<?php echo $modif_crypto ?>" style='margin-bottom:5px'>
          
         
                <input name="debut" type="text" id="debut"  size="5" style='margin-bottom:5px' value="<?php echo $bloc['debut'] ?>" placeholder="Début">
          <input name="fin" type="text" id="fin"  size="5" style='margin-bottom:5px' value="<?php echo $bloc['fin'] ?>" placeholder="Fin">
          <input name="reward" type="text" id="reward"  size="5" style='margin-bottom:5px' value="<?php echo $bloc['reward'] ?>" placeholder="Reward">
               <input name="modif_bloc" type="hidden" id="modif_bloc" value="<?php echo $bloc['id'] ?>">
              
          <input type="submit" name="button3" id="button3" value="Modifier" class='bouton'>
        </form>
        <?php $i++; } ?>
        
        
        
        
        
        
                 </div>
                 <?php } ?>
                 
               <div style='clear:both;margin-bottom:30px;'></div>
<?php } ?>


              </div>
              
            </section>
            </div>
</body>
</html>
<?php }else{die("Vous n'avez pas l'autorisation d'afficher cette page !"); } ?>