<?php
session_start();
include("functions.php"); 
include("config.php");
$email = user_data($db, 'email', 'username', $_SESSION['username']);
//echo $_POST['new_password']." / ".$_POST['current_password']." / ".$_SESSION['username'];;
change_password($email, $db, 'new');

?>
<html>
<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="app.css" type="stylesheet"/>
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    	<script type="text/javascript" src="app.js"></script>
	</head>
	<body>
	<div class="container">	
			<h4>Please choose a new password!</h4>
			<form action="change_password.php" method="post">
				<p>current password:</p>
				<input type="password" name="current_password" size="30">
				<p>new password:</p>
				<input type="password" name="new_password" size="30">
				<p>new password again:</p>
				<input type="password" name="new_password_again" size="30">
				<input type="submit" value="ok" size="30" class="btn btn-success id="changepw">
			</form>	
	</div>
	</body>