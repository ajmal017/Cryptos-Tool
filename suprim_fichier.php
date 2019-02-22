<?php 
session_start();
if(isset($_SESSION['user'])){
include("fonctions/connexion.php");

if(isset($_GET['sup_fichier'])){
$sup_fichier=$_GET['sup_fichier'];
unlink("uploads/".$_GET['fichier']);
$mod=mysqli_query($wikeo,"DELETE FROM uploads WHERE id_fichier=$sup_fichier");
$fermeture=true;
} 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if($fermeture==true){ ?>
<script xfor=window xevent=OnLoad> 
var indexWin=window.opener
indexWin.location=indexWin.location
window.close()
</script>
<?php }elseif(isset($_GET['ferm'])){ ?>
<script xfor=window xevent=OnLoad>

window.close()
</script>
<?php } ?>
<title>Document sans nom</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;

}
</style>
</head>

<body>
<div style="padding: 0px;
	padding-top: 5px;
	position: relative;
	margin: 5px;
	z-index: 9;
	border-radius: 20px 20px 20px 20px;
	text-align: left;
	background-color: #fff;
	height: 23px;
	font: 13px Geneva, Arial, Helvetica, sans-serif;
	font-weight:bolder;
	color:#F00;
	text-align: center;
	width: 300px;height: 230px;">
  <table width="100%" height="238" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td height="181" align="center"><img src="images/attention.png" width="80" height="80" /><br />
        <span class="menuon">Attention vous &ecirc;tes sur le point de supprimer
          
          un fichier          !</span>
        </p>
        <p><span style='color:#096'>
          <?php 
		  echo $_GET['nom_fichier']
			  ?>
      </span></p></td>
    </tr>
    <tr>
      <td height="55" align="center"><table width="74%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="49%" align="center"><a href='suprim_fichier.php?ferm=oui'><img src="images/refus.gif" width="46" height="24" border='0' /></a></td>
          <td width="51%" align="center"><a href='suprim_fichier.php?sup_fichier=<?php echo $_GET['id_fichier'] ?>&fichier=<?php echo $_GET['chemin_fichier'] ?>'><img src="images/corbeille.png" width="25" height="25" align="absmiddle" border='0' /></a></td>
        </tr>
      </table>
        <br />
        <br /></td>
    </tr>
  </table>
</div>
</body>
</html>
<?php } ?>