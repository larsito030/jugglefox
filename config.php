<?php
//LOCAL SERVER CONFIGURATION:

$user = "root";
$pass = "";
$db = new PDO("mysql:host=localhost;dbname=jugglefox",$user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
define('BASE_PATH', realpath(dirname(__FILE__)));
define("PATH","/Juggleflash");
define("LOCATION", "");
error_reporting(0);

//WEB SERVER CONFIGURATION:
/*
Strato:

$user = "U2242253";
$pass = "hamburg12";
$db = new PDO("mysql:host=rdbms.strato.de;dbname=DB2242253", $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
define("PATH","/mockup-elternfreund.com");
define("LOCATION", "http://mockup-elternfreund.com/");
*/
//All inkl:
/*
$user = "d01f35a7";
$pass = "Hamburg12";
$db = new PDO("mysql:host=larsitogames.com;dbname=d01f35a7", $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
define("PATH","/larsitogames.com");
define("LOCATION", "http://larsitogames.com/");

*/



