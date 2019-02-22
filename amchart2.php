<?php 
session_start();
include("fonctions/connexion.php");
include("fonctions/fonctions.php");
$rt=mysqli_query($wikeo,"SELECT sigle FROM cryptos_algos WHERE id_crypto_algo=".$_GET['crypto']);
$t=mysqli_fetch_assoc($rt);
if(isset($_GET['prec'])){$prec=$_GET['prec'];}else{$prec=1;}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>amCharts examples</title>
        <link rel="stylesheet" href="css/styles_persos.css" />
        <link rel="stylesheet" href="style.css" type="text/css">
        <script src="amchart/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="amchart/amcharts/serial.js" type="text/javascript"></script>

        <script type="text/javascript">
         var chartData = generateChartData();

var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
	"theme": "none",
    "pathToImages": "http://www.amcharts.com/lib/3/images/",
    "dataProvider": chartData,
	
    
	
	"valueAxes": [{
		"id": "axeDiff",
        "position": "right",
"axisColor": "red",
        "title": "Difficulté"
		
    },{
		"id": "axeCours",
        "position": "left",
"axisColor": "Blue",
        "title": "Cours"
    }],
    "graphs": [{
		"valueAxis": "axeDiff",
        //"fillAlphas": 0.2,
		"lineColor": "Red",
		"type": "smoothedLine",
		
        "valueField": "diff"
    },{
		"valueAxis": "axeCours",
		"type": "smoothedLine",
        "fillAlphas": 0.1,
		"dashLength": 0.01,
		"lengthAlhas": 0.1,
		//"balloonText":value,
		//"balloonColor":"Green",
		"lineColor": "Blue",
        "valueField": "cours"
    }],
    "chartScrollbar": {},
    "chartCursor": {
        "categoryBalloonDateFormat": "DD-MM-YYYY JJ:NN",
        "cursorPosition": "mouse"
    },
    "categoryField": "date",
	"dataDateFormat": "YYYY-MM-DD JJ:NN:SS",
    "categoryAxis": {
        "minPeriod": "mm",
        "parseDates": true
    }
});

chart.addListener("dataUpdated", zoomChart);
// when we apply theme, the dataUpdated event is fired even before we add listener, so
// we need to call zoomChart here
zoomChart();
// this method is called when chart is first inited as we listen for "dataUpdated" event
function zoomChart() {
    // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
    chart.zoomToIndexes(chartData.length - 250, chartData.length - 1);
}

// generate some random data, quite different range
function generateChartData() {
    var chartData = [];
    // current date
    var firstDate = new Date();
    // now set 500 minutes back
    firstDate.setMinutes(firstDate.getDate() - 1000);

    // and generate 500 data items
                    <?php
$r_hist=mysqli_query($wikeo,"SELECT moment,bid,diff FROM historique WHERE idcrypto=".$_GET['crypto']." order by moment");
$vernb=mysqli_num_rows($r_hist);

$nb=0;
$co=0;
while($hist=mysqli_fetch_assoc($r_hist)){; 
        if($co==0){?>
        chartData.push({
            date: "<?php echo $hist['moment'] ?>",
            diff: <?php echo $hist['diff'] ?>,
			cours: <?php echo $hist['bid'] ?>
        });
   <?php 
		}
    $co++;
		  if($co==$prec){$co=0;}
   } ?>
    return chartData;
}    </script>
    </head>

    <body>
     <div  class="etiquettes" style='width:250px;position:relative;align:center;float:right'>
       <form name="form1" method="get" action="amchart2.php">
       
         <label for="prec">Pr&eacute;cision :</label>
         <select name="prec" id="prec" onChange="submit()">
           <option value="1"  <?php if($prec==1){echo "selected='selected'";} ?>>10 Minutes</option>
           <option value="2" <?php if($prec==2){echo "selected='selected'";} ?>>20 Minutes</option>
           <option value="3" <?php if($prec==3){echo "selected='selected'";} ?>>30 Minutes</option>
           <option value="4" <?php if($prec==4){echo "selected='selected'";} ?>>40 Minutes</option>
           <option value="5" <?php if($prec==5){echo "selected='selected'";} ?>>50 Minutes</option>
           <option value="6" <?php if($prec==6){echo "selected='selected'";} ?>>1 Heure</option>
           <option value="12" <?php if($prec==12){echo "selected='selected'";} ?>>2 Heures</option>
           <option value="18" <?php if($prec==18){echo "selected='selected'";} ?>>3 Heures</option>
           <option value="24" <?php if($prec==24){echo "selected='selected'";} ?>>4 Heures</option>
           <option value="30" <?php if($prec==30){echo "selected='selected'";} ?>>5 Heures</option>
           <option value="36" <?php if($prec==36){echo "selected='selected'";} ?>>6 Heures</option>
           <option value="42" <?php if($prec==42){echo "selected='selected'";} ?>>7 Heures</option>
           <option value="144" <?php if($prec==144){echo "selected='selected'";} ?>>24 Heures</option>
         </select>
         <input name="crypto" type="hidden" id="crypto" value="<?php echo $_GET['crypto'] ?>">
       </form>
     </div>
    <div  class="etiquettes" style='width:300px;position:relative;align:center;'><span><form name="form1" method="get" action="amchart2.php">
       
         <label for="prec">Affichage des infos pour </label>
         <select name="crypto" id="crypto" onChange="submit()">
       <?php  $rc=mysqli_query($wikeo,"SELECT id_crypto_algo,sigle FROM cryptos_algos WHERE actif=1 order by sigle");
while($c=mysqli_fetch_assoc($rc)){; ?>
           <option value="<?php echo $c['id_crypto_algo'] ?>"  <?php if($_GET['crypto']==$c['id_crypto_algo']){echo "selected='selected'";} ?>><?php echo $c['sigle'] ?></option>
           <?php } ?>
         </select>
         <input name="prec" type="hidden" id="prec" value="<?php echo $prec ?>">
       </form></span></div>
    <div id="chartdiv" style="width: 100%; height: 400px;"></div>
    </body>

</html>