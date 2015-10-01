<?php
session_start();
include("config.php");
include("functions.php");

$username = $_POST['username'];
$password = $_POST['password'];
$password2= $_POST['password2'];
$email = $_POST['email'];
$inputfield = $_POST['inputfield'];

if($inputfield === "signup_username" || $inputfield === "signup_email") {
	if($inputfield === "signup_username") {
		if(strlen($username) < 6) {
			$count = 2;
		} else {
			$query = "SELECT id FROM login WHERE username = '$username'";
			$result = $db->prepare($query);
			$result->execute();
			$count = $result->rowCount();
		}
		
	} elseif($inputfield === "signup_email") {
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {	
			$count = 5;
		}else {
			$query = "SELECT id FROM login WHERE email = '$email'";
			$result = $db->prepare($query);
			$result->execute();
			$count = $result->rowCount();
			$count = ($count === 0) ? 0 : 6;
		}	
	}
	
	} elseif($inputfield === "signup_password") {
		$count = (strlen($password) > 5) ? 0 : 3;
	} elseif($inputfield === "password2") {
		$count = ($password === $password2) ? 0 : 4;
	}

echo $count;
/*
Your username needs to contain at least 6 characters! (2)
This username is already taken! (1)

Your password needs to have at least 6 characters! (3)

Your passwords are not matching! (4)

This is not a valid email address! (5)
This email address is already registered! (6)
*/

