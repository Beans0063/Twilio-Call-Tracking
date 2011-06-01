<?php
	require_once('./include/db.php');

	if (file_exists('votes.sqlite') != true)
	{
		$db = new DB();
		$db->init();

		echo 'Database created.';
	}
	else {
		echo 'Database already exists. Feel free to delete me.';
	}
?>
