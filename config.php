<?php
//LOCAL SERVER CONFIGURATION:

$user = "root";
$pass = "";
$db = new PDO("mysql:host=localhost;dbname=jugglefox",$user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
define('BASE_PATH', realpath(dirname(__FILE__)));
define("PATH","/Juggleflash");
define("LOCATION", "");
error_reporting(0);





