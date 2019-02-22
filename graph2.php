<?php // content="text/plain; charset=utf-8"
// $Id: bar_csimex3.php,v 1.3 2002/08/31 20:03:46 aditus Exp $
// Horiontal bar graph with image maps
require_once ('fonctions/jpgraph/src/jpgraph.php');
require_once ('fonctions/jpgraph/src/jpgraph_bar.php');

//$data1y=array(5,8,19,3,10,5);

$datax=unserialize(urldecode($_GET['cartes']));
//$datax=array("testqsdfqsdfqsdfqsdfd","dd");

$data1y=unserialize(urldecode($_GET['hashrates']));

// Setup the basic parameters for the graph
$graph = new Graph(400,700);
$graph->SetAngle(90);
$graph->SetScale("textlin");

// The negative margins are necessary since we
// have rotated the image 90 degress and shifted the 
// meaning of width, and height. This means that the 
// left and right margins now becomes top and bottom
// calculated with the image width and not the height.
$graph->img->SetMargin(-80,-80,210,210);

$graph->SetMarginColor('white');

// Setup title for graph
$graph->title->Set('Horizontal bar graph');
$graph->title->SetFont(FF_FONT2,FS_BOLD);
$graph->subtitle->Set("With image map\nNote: The URL just points back to this image");

// Setup X-axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,10);

// Some extra margin looks nicer
$graph->xaxis->SetLabelMargin(10);

// Label align for X-axis
$graph->xaxis->SetLabelAlign('center','center');

// Setup Y-axis

// First we want it at the bottom, i.e. the 'max' value of the
// x-axis
$graph->yaxis->SetPos('max');

// Arrange the title
$graph->yaxis->SetTitle("Turnaround (mkr)",'center');
$graph->yaxis->SetTitleSide(SIDE_RIGHT);
$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD);
$graph->yaxis->title->SetAngle(0);
$graph->yaxis->title->Align('center','top');
$graph->yaxis->SetTitleMargin(30);

// Arrange the labels
$graph->yaxis->SetLabelSide(SIDE_RIGHT);
$graph->yaxis->SetLabelAlign('center','top');

// Create the bar plots with image maps
$b1plot = new BarPlot($data1y);
$b1plot->SetFillColor("orange");
$b1plot = new BarPlot($data1y);



// Create the accumulated bar plot
$abplot = new AccBarPlot(array($b1plot));


// We want to display the value of each bar at the top
$abplot->value->Show();
$abplot->value->SetFont(FF_FONT1,FS_NORMAL);
$abplot->value->SetAlign('left','center');
$abplot->value->SetColor("black","darkred");
$abplot->value->SetFormat('%.1f mkr');

// ...and add it to the graph
$graph->Add($abplot);

// Send back the HTML page which will call this script again
// to retrieve the image.
$graph->StrokeCSIM();

?>
