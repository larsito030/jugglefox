<?php 
session_start();
	include("config.php");
	include("functions.php");
$score = intval(json_decode($_POST['new_score']));
$count = get_highscore($db,'count');
$place = get_highscore($db,'place');
$ov_highscore = intval(get_highscore($db,'ov_highscore'));
$ps_highscore = intval(get_highscore($db,'personal_highscore'));
$ranking = $place/$count;

//echo $score."/".$count."/".$place."/overall highscore:".$ov_highscore." ".$ps_highscore."/".$ranking;
if($score < $ov_highscore) {
	echo "New overall high score!!!";
	update_highscore($db, $score);
}elseif($score < $ps_highscore || $ps_highscore === 0) {
	echo "New personal high score!";
	update_highscore($db, $score);
} elseif($ranking < 0.5) {
	echo "You're awesome!";
} elseif($ranking < 0.25) {
	echo "You're a genius!";
} else {
	echo "Not all that bad!";
}

