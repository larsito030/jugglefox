<?php  
	session_start();
	include("config.php");
	include("functions.php");
	$score = intval(json_decode($_POST['score']));
    $data = $db->prepare("SELECT * FROM highscores ORDER BY highscore  DESC");
    $data->execute();
    $result = $data->fetch();
    $id = $result[0];
    $highscore = $result[1];
    $num = $data->rowCount();
   if($score < $highscore && $score != 0 && isset($_POST['score'])) {
    	$query = "UPDATE highscores SET highscore = $score WHERE (id = '$id')";
    	$result = $db->prepare($query);
    	$result->execute();
    	//$db->execute("DELETE * FROM highscores WHERE highscore = '0'");
    } 

    	return $highscore;


