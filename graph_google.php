<?php 
include("fonctions/connexion.php");
include("fonctions/fonctions.php");



if(!isset($_POST['chekcomp'])){
$chcomp_a=mysqli_query($wikeo,"SELECT * FROM cartes WHERE nom!='RIG_Mixte' AND nom!='RIG_ATR' AND nom!='Location' order by nom");
while($sp3_a=mysqli_fetch_assoc($chcomp_a)){
	$chekcomp[]=$sp3_a['id'];
}
}else{
	$chekcomp=$_POST['chekcomp'];
	//echo $_POST['chekcomp'];
}




$nb=0;
foreach($chekcomp as $id_titles){
$quer=mysqli_query($wikeo,"SELECT id,nom FROM cartes WHERE id=$id_titles");
$nom_title=mysqli_fetch_assoc($quer);
$titres[]=$nom_title['nom'];
	
$r_sem=mysqli_query($wikeo,"SELECT id_hash,hashrate FROM hashrates WHERE idcarte=$id_titles AND idalgo=11");
$hash=mysqli_fetch_assoc($r_sem);


$hashrate=$hash['hashrate'];
$hash=$hashrate/1000000000;$unite="Mh";

$hashrates[]=$hash;
	$datas[]=$nom_title['nom'].":".$hash;
	$nb++;
}
?>
<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Carte', 'Hashrate'],
		  <?php 
		  $vernb=0;
		  foreach($datas as $infos){
			  $vernb++;
			  $i=explode(":",$infos);
			  
         echo "['".$i[0]."',  ".$i[1]."]";if($vernb<$nb){echo ",";}
		  }
		  ?>
		  

        ]);

        var options = {
          title: 'Hashrates pour l\'algo',
          hAxis: {title: 'Cartes', titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
  <?php  
  

		  $vernb=0;echo $nb;
		  foreach($datas as $infos){
			  $vernb++;
			  $i=explode(":",$infos);
			  
         echo "['".$i[0]."',  ".$i[1]."]";if($vernb<$nb){echo ",";}
		  }

  
  
  $chcomp=mysqli_query($wikeo,"SELECT * FROM cartes WHERE nom!='RIG_Mixte' AND nom!='RIG_ATR' AND nom!='Location' order by nom");
$compte=0;
while($sp3=mysqli_fetch_assoc($chcomp)){ ?>
        <?php
$chek3="";
foreach($chekcomp as $clem4 => $valeur4){
	//echo $valeur4;
	//echo $valeurm."<br>";
	if ($valeur4==$sp3['id']){ 
	//echo "ok";
	$chek3="checked";}
	
}

if($compte==0){ echo "<tr>";}
echo "
    <td><input type='checkbox' name='chekcomp[]' value='".$sp3['id']."' $chek3 onchange='submit()'>".$sp3['nom']."</td>";

$compte++;
if($compte==1){ echo "</tr>";
$compte=0;
}
}
?>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>