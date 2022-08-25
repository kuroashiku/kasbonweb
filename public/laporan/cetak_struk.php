<?php
//Import the PhpJasperLibrary
include_once("tcpdf/tcpdf.php");
include_once("PHPJasperXML.inc.php");
$qid = $_GET['varqid'];
//database connection details
$server="192.254.236.195";
$db="reenduxs_rsaqueue";
$user="reenduxs_rsaq";
$pass="123rsaqueue#@!";
$version="0.8b";
$pgport=5432;
$pchartfolder="./class/pchart2";

//display errors should be off in the php.ini file
//ini_set('display_errors', 0);

//setting the path to the created jrxml file
$xml = simplexml_load_file("struk-antrian.jrxml");

$PHPJasperXML = new PHPJasperXML();
//$PHPJasperXML->debugsql=true;
$PHPJasperXML->arrayParameter=array("qid"=>$qid);
$PHPJasperXML->xml_dismantle($xml);
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I"); // page output method I:standard output D:Download file
?>
