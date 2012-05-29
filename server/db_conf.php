<?php $uname = "root";
//$pass = "qwerty";
$serv = "localhost";
$dbname = "razin_promo";
$connect = mysql_connect($serv, $uname) or die(mysql_error());
mysql_query("SET NAMES utf8;", $connect);
mysql_select_db($dbname, $connect);
?>