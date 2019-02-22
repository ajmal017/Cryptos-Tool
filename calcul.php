<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<?php
/*
$h_groestl_myr=135000000;
$h_scryptn=
$h_groestl=
$h_blake=
$h_x11=
$h_x13=
*/
?>

<?php 

  require_once 'jsonRPCClient.php';

/*
$json = file_get_contents("https://api.mintpal.com/v1/market/stats/GRS/BTC");
$json_output = json_decode($json,true);
//echo $json_output[0]['top_bid'];
var_dump($json_output);
echo"<br>";echo"<br>";

$json2 = file_get_contents("https://api.kraken.com/0/public/Ticker?pair=XXBTZEUR");
$json_output2 = json_decode($json2,true);
echo $json_output2['result']['XXBTZEUR']['c'][0];
//var_dump($json_output2);



$json2 = file_get_contents("https://bittrex.com/api/v1/public/getticker?market=BTC-GRS");
$json_output2 = json_decode($json2,true);
//var_dump($json_output2);
if(isset($json_output2['result']['Bid'])){ 
print_r ($json_output2['result']['Bid']);
$bitrex=$json_output2['result']['Bid'];
}
echo "<br>";
$json2 = file_get_contents("https://api.mintpal.com/v1/market/stats/GRS/BTC");
$json_output2 = json_decode($json2,true);
//var_dump($json_output2);
if(isset($json_output2[0]['top_bid'])){ 
print_r ($json_output2[0]['top_bid']);
$mintpal=$json_output2[0]['top_bid'];
}
$url = "https://api.mintpal.com/v1/market/stats/GS/BTC";
$headers = @get_headers($url);
if(strpos($headers[0],'404') === false)
{
  echo "URL Exists";
}
else
{
  echo "URL Not Exists";
}


echo "<br>";
echo $bitrex-$mintpal;


$url = "http://poloniex.com/public?command=returnOrderBook&currencyPair=BTC_".$crypto['sigle'];

$headers = @get_headers($url);
if(strpos($headers[0],'404') === false)
{
$json = file_get_contents("https://poloniex.com/public?command=returnTicker");
$json_output = json_decode($json,true);
//var_dump($json_output);
echo $json_output['BTC_GRS']['highestBid'];
$bid_mintpal=$json_output[0]['top_bid'];
$market_mintpal=1;
}else{
$market_mintpal=0;
$bid_mintpal=0;
}

$json = file_get_contents("https://api.mintpal.com/v1/market/summary/BTC");
$json_output = json_decode($json,true);
var_dump($json_output);





//echo $json_output['BTC_GRS']['highestBid'];
//$bid_mintpal=$json_output[0]['top_bid'];


//echo $ini_dve->getdifficulty();
echo $dve['difficulty'];
$json = file_get_contents("https://cryptoine.com/api/1/ticker/xst_btc");
$json_output = json_decode($json);
var_dump($json_output);
		  $data = file_get_contents("https://cryptoine.com/api/1/ticker/xst_btc" );
   $tab = explode(':', $data);
  echo floatval($tab[6]);
 


 $ini_dve = new jsonRPCClient("http://solo:test@192.168.1.233:33353/");
$dve=$ini_dve->getinfo();
echo $dve['difficulty']
 

  $json = file_get_contents("https://api.mintpal.com/v1/market/stats/SPEC/BTC");
$json_output = json_decode($json,true);
//var_dump($json_output);
  if($json_output['success']==1){echo "ok";}else{echo "pas bon";}
//if($json_output['data']=="pair not exists"){echo "Pas de market dispo";}else{echo "Market OK";}


$json = file_get_contents("https://api.coin-swap.net/market/orders/CAI/BTC/BUY");
$json_output = json_decode($json,true);
$json_output = json_decode($json);
var_dump($json_output);
//echo $json_output['buy'];



$hash=470;
$diff=1094149514;
$reward=15.095899682865;
echo ((60*60*24*$hash)/$diff)*$reward;

$json = file_get_contents("http://www.whattomine.com/coins.json");
$json_output = json_decode($json,true);
var_dump($json_output);



$json = file_get_contents("https://alcurex.org/api/market.php?pair=dcn_btc&price=buy");
if($json!=""){
$bid= explode(",",$json);
$bid=explode(":",$bid[2]);
echo $bid[1];
}
*/
//echo $json_output;
 $ini_dve = new jsonRPCClient("http://solo:test@192.168.1.233:33383/");


	
	



$dve=$ini_dve->getinfo();
echo $dve['difficulty'];
echo $dve['balance'];

/*


if($connect="ok"){echo "c'est tout bon";}
$json3=file_get_contents("https://btc-e.com/api/2/btc_eur/ticker");
$json_output2 = json_decode($json3,true);
$btcb=round($json_output2['ticker']['buy']);
echo $btcb;*/
?>

</body>
</html>