<?php
	require_once('./include/db.php');

	header('Content-type: text/xml');
	echo '<Response>';

	$phone_number = $_REQUEST['From'];
	$team_number = (int) $_REQUEST['Body'];

	if ( (strlen($phone_number) >= 10) && ($team_number > 0) )
	{
		$db = new DB();

		$response = $db->save_vote($phone_number, $team_number);
	}
	else {
		$response = 'Sorry, I didn\'t understand that. Text the team number to vote. For example, texting 1 will vote for Team 1.';
	}

	echo '<Sms>'.$response.'</Sms>';
	echo '</Response>';
?>
