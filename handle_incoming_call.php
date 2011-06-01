<?php
	require_once('./include/config.php');
	header('Content-type: text/xml');
	$db = new DB();
	$db->save_call();
?>
<Response>
	<Dial action="record_call.php" method="GET" record="true"><?php echo(AGENT_NUMBER);?></Dial>
</Response>
