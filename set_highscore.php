<?php  
	session_start();
	include("config.php");
	include("functions.php");
	$score = intval(json_decode($_POST['score']));
	echo "this is the new score: ".$score;
    $username = $_SESSION['username'];
    $ps_highscore = get_highscore($db, 'personal_highscore');

   if(($score < $ps_highscore) && $score != 0 && isset($_POST['score'])) {
    	$query =  "UPDATE highscores ";
        $query .= "INNER JOIN login ";
        $query .= "ON highscores.user_id = login.id ";
        $query .= "WHERE login.username = $username";
    	$result = $db->prepare($query);
    	$result->execute();
    	$db->execute("DELETE * FROM highscores WHERE highscore = '0'");
    } 

    	return $ov_highscore;


