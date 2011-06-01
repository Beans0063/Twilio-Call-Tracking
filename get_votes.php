<?php
	require_once('./include/db.php');

	$db = new DB();
	$teams = $db->get_teams();

	foreach ($teams as $team)
	{
		$votes[] = (int) $team['votes'];
	}

	echo json_encode($votes);
?>
