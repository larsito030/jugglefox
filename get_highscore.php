<?php

include("config.php");
include("functions.php");
	$username = $_POST['username'];
	$query = "SELECT * FROM highscores INNER JOIN login";
	$query .= "ON highscores.id = login.id";
	$query .= "WHERE login.username = '$username'";
	$data = $db->prepare($query);
    $data->execute();
    $result = $data->fetch();
    $highscore = $result[1];

echo "your highscore: ".$highscore;