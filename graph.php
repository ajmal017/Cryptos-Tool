<?php // content="text/plain; charset=utf-8"
require_once ('fonctions/jpgraph/src/jpgraph.php');
require_once ('fonctions/jpgraph/src/jpgraph_bar.php');
// Some data




//$testvar = unserialize(rawurldecode($_GET['testvar']))
$datax=unserialize(urldecode($_GET['cartes']));
//$datax=array("testqsdfqsdfqsdfqsdfd","dd");

$datay=unserialize(urldecode($_GET['hashrates']));
//$datay=array(1,2);

function mh($hashrate) {
   // return sprintf("%.1f%%",100*$aVal); // Convert to string
if($hashrate>=1000000000){$hash=$hashrate/1000000000;$unite="Gh";}elseif($hashrate>=1000000){$hash=$hashrate/1000000;$unite="Mh";}elseif($hashrate>=1000){$hash=$hashrate/1000;$unite="Kh";}else{$hash=$hashrate;$unite="H";}
$hashage=$hash." ".$unite;
return $hashage;
}



// Size of graph
$width=$_GET['largeur']; 
$height=$_GET['hauteur'];

// Set the basic parameters of the graph 
$graph = new Graph($width,$height,'auto');
$graph->SetScale("textlin");

$top = 20;
$bottom = 60;
$left = 160;
$right = 20;
//$graph->Set90AndMargin($left,$right,$top,$bottom);
$graph->img->SetMargin(80,20,20,30);


$graph->xaxis->SetPos('min');

// Nice shadow
$graph->SetShadow();

// Setup title
$graph->title->Set("");
$graph->title->SetFont(FF_VERDANA,FS_BOLD,12);
$graph->subtitle->Set("");

// Setup X-axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,10);

// Some extra margin looks nicer
$graph->xaxis->SetLabelMargin(10);

// Label align for X-axis
$graph->xaxis->SetLabelAlign('center','center');

// Add some grace to y-axis so the bars doesn't go
// all the way to the end of the plot area
$graph->yaxis->scale->SetGrace(5);

// Setup the Y-axis to be displayed in the bottom of the 
// graph. We also finetune the exact layout of the title,
// ticks and labels to make them look nice.
$graph->yaxis->SetPos('min');

// First make the labels look right
$graph->yaxis->SetLabelAlign('right','top');
$graph->yaxis->SetLabelFormat('%d');
$graph->yaxis->SetLabelSide(SIDE_LEFT);

// The fix the tick marks
//$graph->yaxis->SetTickSide(SIDE_LEFT);

// Finally setup the title
$graph->yaxis->SetTitleSide(SIDE_RIGHT);
$graph->yaxis->SetTitleMargin(0);

$graph->yaxis->SetTitle("Turnaround (mkr)",'center');
$graph->yaxis->SetTitleSide(SIDE_RIGHT);
$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD);
$graph->yaxis->title->SetAngle(0);
$graph->yaxis->title->Align('center','top');
$graph->yaxis->SetTitleMargin(30);


// To align the title to the right use :
$graph->yaxis->SetTitle('','high');
$graph->yaxis->title->Align('left');

// To center the title use :
//$graph->yaxis->SetTitle('Turnaround 2002','center');
//$graph->yaxis->title->Align('center');

$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,10);
$graph->yaxis->title->SetAngle(0);
	

$graph->img->SetAntiAliasing();

$graph->yaxis->SetFont(FF_ARIAL,FS_BOLD);
$graph->yaxis->SetLabelAngle(0);
// If you want the labels at an angle other than 0 or 90
// you need to use TTF fonts
//$graph->yaxis->SetLabelAngle(0);

// Now create a bar pot
$bplot = new BarPlot($datay);


//You can change the width of the bars if you like
//$bplot->SetWidth(0.5);

// We want to display the value of each bar at the top
$bplot->value->SetFormatCallback("mh");
$bplot->value->Show();

$bplot->SetShadow();

// Add the bar to the graph
$graph->Add($bplot);


$graph->Stroke();

?>