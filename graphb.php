<?php 
include("fonctions/connexion.php");
include("fonctions/fonctions.php");

$r_hist=mysqli_query($wikeo,"SELECT moment,bid,diff FROM historique WHERE idcrypto=".$_GET['crypto']." order by moment LIMIT 10");
$vernb=mysqli_num_rows($r_hist);

$nb=0;
$co=0;
while($hist=mysqli_fetch_assoc($r_hist)){;
		  
		  //if($co==0){
		  //echo "['".$hist['moment']."',  ".$hist['diff'].", ".$hist['bid']."]";$nb++;if($vernb>$nb){echo ",";echo "<br>";}
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
          ['Date', 'Diff', 'Cours'],
		  		  <?php
$r_hist=mysqli_query($wikeo,"SELECT moment,bid,diff FROM historique WHERE idcrypto=".$_GET['crypto']." order by moment");
$vernb=mysqli_num_rows($r_hist);

$nb=0;
$co=0;
while($hist=mysqli_fetch_assoc($r_hist)){;
		  
		  if($co==0){
		  echo "['".$hist['moment']."',  ".$hist['diff'].", ".$hist['bid']."]";$nb++;if($vernb>$nb){echo ",";}
		  }
		  $co++;
		  if($co==10){$co=0;}
}
		  ?>
		  

        ]);
		



        var options = {
			
			



         
		  animation:{
        duration: 1000,
        easing: 'out',
      },

          
		  
		   vAxes: {0: {gridlines: {color: 'transparent'},
},
1: {gridlines: {color: 'transparent'},
},
},
series: {0: {targetAxisIndex:0},
1:{targetAxisIndex:1},

},


        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
	  
	  
	  
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 1400px; height: 300px;"></div>
  </body>
</html>