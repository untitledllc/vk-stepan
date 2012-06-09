<?php $uname = "stepan.vkapps";
$pass = "kilsEbeetAiWupWijpeshouvFuigJisp";
$serv = "localhost";
$dbname = "stepan.vkapps";
$connect = mysql_connect($serv, $uname, $pass) or die(mysql_error());
mysql_query("SET NAMES utf8;", $connect);
mysql_select_db($dbname, $connect);
?>