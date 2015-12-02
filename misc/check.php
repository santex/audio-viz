<?php
$file="/home/santex/Downloads/interdiscount-thor.xml";
$info = file_get_contents($file);
#$info = htmlspecialchars($info);
#print $info;
$info = preg_replace("/[&]/","//",$info);
$xml = simplexml_load_string($info);

#$db = new mysqli('localhost', 'username', 'password', 'database');
#$xml = simplexml_load_string($xml_string); // or load file
#print_r($xml);
$x=0;
$all = array();
$totals = array("products"=>array(),"category"=>array(),"manufacturer"=>array());
foreach($xml as $k=> $group) {

  $r=(array)$group;
  #unset($r["features"]);



  if(isset($r["cnetID"]) && isset($r["category"]) && isset($r["name"])){


    unset($r["features"]);

    if($r["category"] == "" || $r["cnetID"]=="" || $r["name"]=="") continue;

    $all[$r["category"]][$r["manufacturer"]][$r["cnetID"]]=array("name"=>$r["name"],"stock"=>$r["stock"],"price"=>$r["price"]);
    $totals["category"][$r["category"]][]= $r["stock"]* $r["price"];
    $totals["manufacturer"][$r["manufacturer"]][]= $r["stock"]* $r["price"];
    $totals["products"][$r["cnetID"]][]= $r["stock"]* $r["price"];


    unset($all[$r["category"]][$r["manufacturer"]][$r["cnetID"]]["category"]);

    unset($all[$r["category"]][$r["manufacturer"]][$r["cnetID"]]["cnetID"]);

    unset($all[$r["category"]][$r["manufacturer"]][$r["cnetID"]]["manufacturer"]);

    }

}


foreach($totals as $k => $v){


foreach($v as $k1 => $v1){


$totals[$k][$k1]=array("stockPriceRatio"=>round(array_sum($v1)),"elems"=>count($v1));
}
}
file_put_contents("cats.json",json_encode(array("totals"=>$totals)));
?>
