<?php 
session_start();
if(isset($_SESSION['user'])){
include("fonctions/connexion.php");
//get unique id
$up_id = uniqid(); 
?>

<?php

//process the forms and upload the files
if ($_POST) {

if($_POST['type']=="reponse"){
$folder = "uploads/wikidr".$_POST['id_post']."_"; 
$ins_opt="INSERT INTO uploads (id_reponse,nom_fichier)";
}else{
$folder = "uploads/wikidq".$_POST['id_post']."_"; 
$ins_opt="INSERT INTO uploads (id_question,nom_fichier)";
}

$ins_opt.="VALUES ('".$_POST['id_post']."','".$_FILES["file"]["name"]."')";
$wikeo->query($ins_opt);

//redirect user
move_uploaded_file($_FILES["file"]["tmp_name"], "$folder" .$_FILES["file"]["name"]);

echo"
<script xfor=window xevent=OnLoad> 
var indexWin=window.opener
indexWin.location=indexWin.location
window.close()
</script>
";
//header('Location: '.$redirect); die;
}
//

?>
<?php
/*

*/


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
<link href="style_progress.css" rel="stylesheet" type="text/css" />

<!--Get jQuery-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.js" type="text/javascript"></script>

<!--display bar only if file is chosen-->
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
</head>

<body>
 <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
 <iframe id="upload_frame" name="upload_frame" frameborder="0" border="0" src="" scrolling="no" scrollbar="no" > </iframe>
    <input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="<?php echo $up_id; ?>"/>
    <input name="id_post" type="hidden" id="id_post" value="<?php echo $_GET['id_post'] ?>">
    <input name="type" type="hidden" id="type" value="<?php echo $_GET['type'] ?>">
<!---->

    <input name="file" type="file" id="file" size="30"/>

<!--Include the iframe-->
    
    

<!---->

    <input name="Envoyer" type="submit" id="submit" value="Envoyer" />
  </form>
</body>
</html><?php } ?>