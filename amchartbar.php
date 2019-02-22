<?php 
session_start();
include("fonctions/connexion.php");
include("fonctions/fonctions.php");



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
         var chart = AmCharts.makeChart("chartdiv", {
	"type": "serial",
	"pathToImages": "http://cdn.amcharts.com/lib/3/images/",
	"categoryField": "algo",
	"rotate": true,
	"startDuration": 1,
	"categoryAxis": {
		"gridPosition": "start",
		"position": "left"
	},
	"trendLines": [],
		"valueAxes": [{
		"id": "axeHash",
       
"axisColor": "Blue",
        "title": "Hashrate"
		
    },{
		"id": "axeConso",
        "position": "Top",
"axisColor": "Red",
        "title": "Consommation"
    }],
	"graphs": [
		{
			"valueAxis": "axeConso",
        //"fillAlphas": 0.2,
		"lineColor": "Red",
			"balloonText": "Conso:[[value]]",
			"fillAlphas": 0.8,
			"id": "AmGraph-1",
			"lineAlpha": 0.2,
			"title": "Consommation",
			"type": "column",
			"valueField": "conso"
		},
		{
			"valueAxis": "axeHash",
        //"fillAlphas": 0.2,
		"lineColor": "Blue",
			"balloonText": "Hashrate:[[value]]",
			"fillAlphas": 0.8,
			"id": "AmGraph-2",
			"lineAlpha": 0.2,
			"title": "Hashrate",
			"type": "column",
			"valueField": "hashrate"
		}
	],
	"guides": [],
	"valueAxes": [
	
		{
			"id": "ValueAxis-1",
			"position": "top",
			"axisAlpha": 0
		}
	],
	"allLabels": [],
	"amExport": {
		"right": 20,
		"top": 20
	},
	"balloon": {},
	"titles": [],
	"dataProvider": [
	<?php $rt=mysqli_query($wikeo,"SELECT a.algo,h.hashrate,h.conso FROM algos a LEFT JOIN hashrates h ON a.id=h.idalgo WHERE idcarte=".$_GET['carte']);
	$nb=mysqli_num_rows($rt);
	 $vernb=0;
while($h=mysqli_fetch_assoc($rt)){
?>
		{
			"algo": '<?php echo $h['algo'] ?>',
			"hashrate": <?php echo $h['hashrate']/1000000; ?>,
			"conso": <?php echo $h['conso'] ?>
		}<?php if($vernb<$nb){echo ",";}} ?>
		
	]
});</script>
    </head>

    <body>

    <div id="chartdiv" style="width: 100%; height: 1200px;"></div>
    </body>

</html>