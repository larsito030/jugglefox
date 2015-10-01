<?php
session_start();
include("config.php");
include("functions.php");

	$username = $_POST['username'];
	$password = $_POST['password'];
	$password_hashed = md5($password);
	
	if(strlen($username) > 0/* && strlen($password) === 0*/) {
		$query = "SELECT id FROM login WHERE username = '$username'";
	} 

	if(strlen($username) > 0 && strlen($password) > 0) {
		$query2 = "SELECT id FROM login WHERE username = '$username' AND password = '$password_hashed'";
	}
	

$result = $db->prepare($query);
$result->execute();
$count1 = $result->rowCount();

$result2 = $db->prepare($query2);
$result2->execute();
$count2 = $result2->rowCount();

$count = $count1 + $count2;

echo $count;
//echo strlen($password);




