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
          ['Date', 'Diff', 'Cours'],
		  		  <?php
$r_hist=mysqli_query($wikeo,"SELECT moment,bid,diff FROM historique WHERE idcrypto=1 order by moment");
$vernb=mysqli_num_rows($r_hist);

$nb=0;
while($hist=mysqli_fetch_assoc($r_hist)){;
		  
		  echo "['".$hist['moment']."',  ".$hist['diff'].", ".$hist['bid']."]";$nb++;if($vernb>$nb){echo ",";}
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