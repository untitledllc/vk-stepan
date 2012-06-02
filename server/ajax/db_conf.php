<?php $uname = "foggy_razin";
$pass = "666666";
$serv = "localhost";
$dbname = "foggy_razin";
$connect = mysql_connect($serv, $uname, $pass) or die(mysql_error());
mysql_query("SET NAMES utf8;", $connect);
mysql_select_db($dbname, $connect);
?>