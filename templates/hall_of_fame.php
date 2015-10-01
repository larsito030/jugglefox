<?php
include('../config.php');
include("../functions.php"); 
$query = "SELECT username, highscore FROM login AS l INNER JOIN highscores AS h ON l.id = h.user_id WHERE h.highscore > 0 ORDER BY h.highscore ASC LIMIT 10 ";
$data = $db->prepare($query);
$data->execute();
$result = $data->fetchAll();
$no = 1;
		$table = "<h2 id=\"hof_heading\">Hall of fame</h2><br>";
		$table .= "<table id=\"top10\" class=\"col-xs-12\">";
		foreach($result as $el) {
				$ov_highscore = $el['highscore'];
				$table .= "<tr>";
				$table .= "<td class=\"col-xs-1\">".$no.". </td><td class=\"col-xs-5\">".$el['username']."</td class=\"col-xs-5\"><td>".get_time_format('ov',$ov_highscore,$ps_highscore)."</td>";
				$table .= "</tr>";
				$no++;
				}
		$table .= "</table><br><br>";
		$table .= "<a class=\"back\" href=\"#\">back</a>";
		echo $table;
?>
