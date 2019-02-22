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
    "theme": "none",
    "type": "serial",
    "dataProvider": [<?php $rt=mysqli_query($wikeo,"SELECT a.algo,h.hashrate,h.conso FROM algos a LEFT JOIN hashrates h ON a.id=h.idalgo WHERE idcarte=".$_GET['carte']);
	$nb=mysqli_num_rows($rt);
	 $vernb=0;
while($h=mysqli_fetch_assoc($rt)){
?>
		{
			"algo": '<?php echo $h['algo'] ?>',
			"hashrate": <?php echo $h['hashrate']/1000000; ?>,
			"conso": <?php echo $h['conso'] ?>
		}<?php if($vernb<$nb){echo ",";}} ?>],
    "valueAxes": [{
        "unit": "%",
        "position": "left",
        "title": "GDP growth rate",
    }],
    "startDuration": 1,
	
"valueAxes": [{
		"id": "axeHash",
       "position": "left",
"axisColor": "Blue",
        "title": "Hashrate"
		
    },{
		"id": "axeConso",
        "position": "right",
"axisColor": "Red",
        "title": "Consommation"
    }],
	
    "graphs": [{
		"valueAxis": "axeConso",
        "balloonText": "GDP grow in [[category]] (2004): <b>[[value]]</b>",
        "fillAlphas": 0.9,
        "lineAlpha": 0.2,
        "title": "2004",
        "type": "column",
        "valueField": "conso"
    }, {
		"valueAxis": "axeHash",
        "balloonText": "GDP grow in [[category]] (2005): <b>[[value]]</b>",
        "fillAlphas": 0.9,
        "lineAlpha": 0.2,
        "title": "2005",
        "type": "column",
        "clustered":false,
        "columnWidth":0.5,
        "valueField": "hashrate"
    }],
    "plotAreaFillAlphas": 0.1,
    "categoryField": "algo",
    "categoryAxis": {
        "gridPosition": "start"
    }
});</script>
    </head>

    <body>

    <div id="chartdiv" style="width: 100%; height: 500px;"></div>
    </body>

</html>