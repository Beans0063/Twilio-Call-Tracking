<?php
	require_once('./include/config.php');
	$db = new DB();
	$calls = $db->get_calls();
	$calls_count = $db->get_calls_count();
	$twilio_numbers=Util::get_all_twilio_numbers();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
	<head>
		<title>Call Tracking</title>

		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="./css/main.css" media="screen">
	</head>
	<body>
		<div id="container">
			<h1>Call Tracking</h1>
			<h3>
				<?php foreach ($calls_count as $number) { 
					if (array_key_exists(format_phone($number['CallTo']), $twilio_numbers))
						$campaign=$twilio_numbers[format_phone($number['CallTo'])];
					else
						$campaign=$number['CallTo'];
					$label="call";
					if ($number['cnt']>1)
						$label="calls";
					echo ($number['cnt'] . " $label to " . $campaign . " &nbsp; &nbsp; ");
				} ?>
			</h3>
			<?php if (count($calls)>0){ ?>
				<table id="rounded-corner">
			    <thead>
			    	<tr>
			        	<th scope="col" class="rounded-company">Date</th>
			            <th scope="col" class="rounded-q1">Campaign</th>
			            <th scope="col" class="rounded-q1">From</th>
			            <th scope="col" class="rounded-q1">To</th>
			            <th scope="col" class="rounded-q1">From City</th>
			            <th scope="col" class="rounded-q1">From State</th>
			            <th scope="col" class="rounded-q1">From Zip</th>
			            <th scope="col" class="rounded-q1">Duration (seconds)</th>
			            <th scope="col" class="rounded-q1">Agent Call</th>
			            <th scope="col" class="rounded-q4">Recording</th>
			        </tr>
			    </thead>
			    <tbody>
				<?php
					foreach ($calls as $call)
					{
						if (array_key_exists(format_phone($call['CallTo']), $twilio_numbers))
							$campaign=$twilio_numbers[format_phone($call['CallTo'])];
						else
							$campaign="";

						echo("<tr>");
						echo("<td nowrap>" . $call['DateCreated']. "</td>");
						echo("<td>" . $campaign . "</td>");
						echo("<td>" . $call['CallFrom']. "</td>");
						echo("<td>" . $call['CallTo']. "</td>");
						echo("<td>" . $call['FromCity']. "</td>");
						echo("<td>" . $call['FromState']. "</td>");
						echo("<td>" . $call['FromZip']. "</td>");
						echo("<td>" . $call['DialCallDuration']. "</td>");
						echo("<td>" . $call['DialCallStatus']. "</td>");
						if ($call['RecordingUrl']!="")
							echo("<td><audio src=\"" . $call['RecordingUrl']. "\" controls preload=\"auto\" autobuffer style=\"width:10em;\"></audio></td>");
						else
							echo("<td></td>");
						echo("</tr>");
					}
				?>
			    </tbody>
				</table>
			<? } else { ?>
				You haven't received any calls yet.  Do you need to <a href="setup_phone_numbers.php">purchase and configure phone numbers?</a>
			<? } ?>
				
			<div id="footer">
				<h3><a href="setup_phone_numbers.php">setup phone numbers</a></h3>
				<p><a href="http://www.twilio.com/"><img src="./images/twilio_logo.png" border="0"></a></p>
			</div>
		</div>
	</body>
</html>
