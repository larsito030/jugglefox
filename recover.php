<?php
session_start();
include("functions.php"); 
include("config.php");
recover($db);

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
	<?php get_html_for_recover_page(); ?>
			<!--
			<h4>Please enter your email address! We will send you your <?php //echo $_GET['mode']; ?> by email.</h4>
			<form action="recover.php?mode=<?php //echo urlencode($_GET['mode'])?>" method="post">
				<input type="email" name="email" size="30">
				<input type="submit" value="ok" class="btn btn-success">
			</form>	
			-->
	</div>
	</body>