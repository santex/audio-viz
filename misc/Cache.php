<?php
error_reporting(E_ALL);


class Cache {

    /**
    * @Boolean
    * Cache ein/aus (true/false)
    */
    private $caching = true;
    /**

    * @Int

    * Cache Lifetime in Sek.
    */
    private $cacheLifetime = 5;


    /**
    * @String
    * Cache-Filename generiert aus MD5 verschlüsselter ID + Template-Filename + .class
    */
    private $cacheFile = "/tmp/travelConections";



    private $data;

    public function __construct($configurl = "")
    {

        if(!is_file($this->cacheFile) ||
           !$this->isCache()) {

            $this->writeCache(func_get_args());


        }
        $this->makeJson();
    }



    public function makeJson()
    {

        $this->setCacheFileName(10*1,$this->cacheFile);





            $this->setCaching(true);
            $this->writeCache(file_get_contents($this->cacheFile));
            print_r($this);
            #echo "\nfresh cache\n",$ob;

            $this->doneTime = time();


    }


    public function transformJS2PHP() {

       print_r($this->data);
       $php = preg_replace("/new Array/","array",$this->data);
       $php = preg_replace("/var /","$",$php);
       $php = preg_replace("/routes\[/","\$routes[",$php);

       return isset($routes)?$routes:array();
    }



    /**
    * Cache-Filename kreieren
    */
    public function setCacheFileName($id, $tplFileName) {
    $this->setCacheLifetime = $id;

    $this->cacheFile = $tplFileName;
    }

    /**
    * Pfad zu den Cachefiles setzen
    */
    public function setCacheData($path) {
    }

    /**
    * caching ein-/ausschalten
    */
    public function setCaching($what) {
    $this->caching = $what;
    }

    /**
    * Cache Lebensdauer setzen
    */
    public function setCacheLifetime($ltime) {
    $this->cacheLifetime = intval($ltime);
    }

    /**
    * überprüfen ob ein Cachefile existiert. wenn ja, dann überprüfen ob es aktuell ist
    */
    public function isCache() {

        return true;


    }

    /**
    * Cachefile löschen
    */
    public function deleteCacheFile() {
        if(unlink($this->cacheFile)) {
        return true;
        }
    return false;
    }

    /**
    * Cachefile erstellen
    */
    public function writeCache($tpldata) {
        printf("\ncacheing to %s\n",$this->cacheFile);

          $serializedData = serialize($tpldata);
            if(!file_put_contents($this->cacheFile, $serializedData, LOCK_EX)) {
            return false;
            }
    }

    /**
    * Cachefile lesen
    */
    public function readCache() {
    $data = file_get_contents($this->cacheFile);
    $mdata = unserialize($data);
    return $mdata;
    }

}




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
if( count($_SERVER['argv']) == 0 ) die("need args!");


if($_SERVER ["argv"])


foreach($totals as $k => $v){


foreach($v as $k1 => $v1){


$totals[$k][$k1]=array("stockPriceRatio"=>round(array_sum($v1)),"elems"=>count($v1));
}
}

file_put_contents("./out.json",json_encode(array("stock"=>$all,"totals"=>$totals)));

file_put_contents("cats.json",json_encode(array("totals"=>$totals)));
$cache = new Cache();
$routes = $cache->transformJS2PHP();
print_r($cache);
?>
