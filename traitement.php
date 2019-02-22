<?php 
session_start();
if(isset($_SESSION['user'])){
include("fonctions/connexion.php");
include("fonctions/fonctions.php");




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
	$titre=addslashes($_POST['titre_modif_question']);
	$question=addslashes($_POST['question']);
$cat=$_POST['cat'];
$option=$_POST['option'];
$options=$_POST['options'];
$categories=$_POST['categories'];

	
$update=mysqli_query($wikeo,"UPDATE questions SET titre='$titre', question='$question' WHERE id_question='".$_SESSION["question"]."'");
$update2=mysqli_query($wikeo,"UPDATE questions_options SET id_option='$options' WHERE id_question_option='$option'");
$update3=mysqli_query($wikeo,"UPDATE questions_categories SET id_categorie='$categories' WHERE id_question_categorie='$cat'");
}


if(isset($_POST['modif_reponse'])){
	$update=mysqli_query($wikeo,"UPDATE reponses SET reponse='".$_POST['modif_reponse']."', edit=now() WHERE id_reponse='".$_POST['id_reponse']."'");
}
	?>
    
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-15"/>
<title>WIKEO : <?php echo $esp['titre'] ?></title>

</head>

<body>
        <?php include("header.php"); ?>
      
            
            <div class="container" >

			  <section class="box2" style='margin-top:69px;'>
              
              <?php 
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if(isset($_POST['reponse'])){
echo "Envoi de votre réponse";
$reponse=addslashes($_POST['reponse']);
$ins_af="INSERT INTO reponses (id_question,id_util,date_reponse,reponse)";
$ins_af.="VALUES ('".$_SESSION["question"]."','".$_SESSION["user"]."',now(),'$reponse')";
$insert=mysqli_query($wikeo,$ins_af);
$recup_post=mysqli_insert_id($insert);
$recupnum=$wikeo->insert_id;
$update=mysqli_query($wikeo,"UPDATE questions SET der_rep=now(),id_utilrep='".$_SESSION["user"]."', nb_posts=nb_posts+1 WHERE id_question='".$_SESSION["question"]."'");
$quest=mysqli_query($wikeo,"SELECT nb_posts FROM questions WHERE id_question=".$_SESSION['question']);
$row=mysqli_fetch_assoc($quest);


$totalDesMessages=$row['nb_posts'];
if($user['affiche']==0){
$nombreDeMessagesParPage = $config_nb_message;
}else{
	$nombreDeMessagesParPage = $user['affiche'];
}
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);

include("fonctions/fonctions_mails.php");
}

?>
              
              </section>
              </div>
</body>
</html>
<?php }else{header("Location:index.php?message=expire"); } ?>