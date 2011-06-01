<?php
	require_once('./include/config.php');
    $client = new TwilioRestClient($AccountSid, $AuthToken);

	if ( isset($_REQUEST['Sid'])){
		//update a Twilio number
	    $data = array(
	    	"FriendlyName" => $_REQUEST['friendly_name'],
	    	"VoiceUrl" => $_REQUEST['url']
	    );
	    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/IncomingPhoneNumbers/" . $_REQUEST['Sid'], "POST", $data); 
	    if($response->IsError)
	    	echo "Error updating phone number: {$response->ErrorMessage}\n";
		else
	    	echo "<div class=\"confirm\"><img src=\"images/check.png\">Updated: {$response->ResponseXml->IncomingPhoneNumber->PhoneNumber}</div>";
	}

	if ( isset($_REQUEST['area_code'])){
		//purchase new Twilio number
	    $data = array(
	    	"FriendlyName" => $_REQUEST['friendly_name'],
	    	"VoiceUrl" => $_REQUEST['url'],	 
	    	"AreaCode" => $_REQUEST['area_code']
	    );
	    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/IncomingPhoneNumbers", "POST", $data); 
	    if($response->IsError)
	    	echo "Error purchasing phone number: {$response->ErrorMessage}\n";
		else
	    	echo "<div class=\"confirm\"><img src=\"images/check.png\">Purchased: {$response->ResponseXml->IncomingPhoneNumber->PhoneNumber}</div>";
	}

	$twilio_numbers=Util::get_all_twilio_numbers();
    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/IncomingPhoneNumbers", "GET"); // Get all phone numbers
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
				<h1>Your Twilio Numbers</h1>
				<? if (count($twilio_numbers)>0){ ?>
					<table id="rounded-corner">
				    <thead>
				    	<tr>
				        	<th scope="col" class="rounded-company">Number</th>
				            <th scope="col" class="rounded-q1">FriendlyName</th>
				            <th scope="col" class="rounded-q4">URL</th>
				        </tr>
				    </thead>
				    <tbody>
					<?php foreach($response->ResponseXml->IncomingPhoneNumbers->IncomingPhoneNumber AS $number){ ?>
						<tr>
							<form method="POST">
							<input type="hidden" name="Sid" value="<? echo($number->Sid);?>">
							<td><? echo($number->PhoneNumber);?></td>
							<td><input type="text" name="friendly_name" value="<? echo($number->FriendlyName);?>" size="30"></td>
							<td><input type="text" value="<? echo($number->VoiceUrl);?>" size="60" name="url"> <input type="submit" value="Update"></td>
							</form>
						</tr>
						<?php } ?>
		    		</tbody>
				</table>
				<? } else { ?>
					Your account has not purchased any Twilio Incoming Numbers.  You may purchase numbers using the form below.
					<br/><br/>Or, you can use your free trial by setting your Sandbox Voice URL to: <span id="sandbox"></span><br/>
					You can change this by <a href="https://www.twilio.com/user/account" target="_blank">clicking here</a> and looking under the Developer Tools/Sandbox heading.
				<? } ?>
			<h1>Purchase a number</h1>
			<table id="rounded-corner">
		    <thead>
		    	<tr>
		        	<th scope="col" class="rounded-company">Area Code</th>
		            <th scope="col" class="rounded-q1">FriendlyName</th>
		            <th scope="col" class="rounded-q4">URL</th>
		        </tr>
		    </thead>
		    <tbody>
				<tr>
					<form method="POST">
					<td><input type="text" name="area_code"></td>
					<td><input type="text" name="friendly_name" size="30"></td>
					<td><input type="text" name="url" id="new_url" value="http://yourserver/call_tracking/handle_incoming_call.php" size="60"> <input type="submit" value="Purchase"></td>
					</form>
				</tr>
			</tbody>
			</table>
	
			<div id="footer">
				<h3><a href="index.php">back to home</a></h3>
				<p><a href="http://www.twilio.com/"><img src="./images/twilio_logo.png" border="0"></a></p>
			</div>
		</div>
		
		<script>
			document.getElementById('new_url').value=document.location.href.split("setup_phone_numbers.php")[0] + 'handle_incoming_call.php';
			document.getElementById('sandbox').innerHTML=document.location.href.split("setup_phone_numbers.php")[0] + 'handle_incoming_call.php';
		</script>
		
	</body>
</html>

