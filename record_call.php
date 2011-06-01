<?php
	require_once('./include/config.php');
	header('Content-type: text/xml');
	$db = new DB();
	$db->save_dialed_call();
?>
<Response/>