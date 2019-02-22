<?php 
include("fonctions/connexion.php");
include("fonctions/fonctions.php");


?>
<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Diff', 'Reward', 'Gain'],
		  		  <?php
				  $d=0;
for($i=0.5;$i<50000;$i++){
	$d=30;
$reward=round(8 * $d / (525600+$i) * 525600);
$nbcoins= round($reward /($d * (POW ( 2,32 )) / 16000000 / 3600 / 24));


		  
		  echo "['".round($d)."',  ".$reward.", ".$nbcoins."]";if($i!=49999){echo ",";}
}
		  ?>

        ]);

       var options = {
    title: 'Evolution de la diff SFR-G',
    curveType: 'function',
    legend: { position: 'bottom' }
  };
  
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 1500px; height: 500px;"></div>
  </body>
</html>