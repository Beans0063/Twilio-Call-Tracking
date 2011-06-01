<?php

	class DB {
		const DB_NAME = 'calls.sqlite';
		protected $db;

		function __construct() {
			$this->db = new PDO('sqlite:'.self::DB_NAME);
		}

		function init() {
			$this->db->exec('CREATE  TABLE IF NOT EXISTS calls ("CallSid" TEXT PRIMARY KEY  NOT NULL  UNIQUE , "DateCreated" DATETIME, "ToCountry" TEXT, "ToZip" TEXT, "ToState" TEXT, "ToCity" TEXT, "FromCountry" TEXT, "FromZip" TEXT, "ApiVersion" TEXT, "Direction" TEXT, "CallStatus" TEXT, "AccountSid" TEXT,  "CallTo" TEXT, "CallFrom" TEXT, "Status" TEXT, "StartTime" DATETIME, "EndTime" DATETIME, "Duration" INTEGER, "FromCity" TEXT, "FromState" TEXT, "DialCallSid" TEXT, "DialCallStatus" TEXT, "DialCallDuration" INTEGER, "RecordingUrl" TEXT);');
		}
	
		function save_call() {
			//http://www.twilio.com/docs/api/twiml/twilio_request#synchronous-request-parameters
			$CallSid = $_REQUEST['CallSid'];
			$AccountSid=$_REQUEST['AccountSid'];
			$CallFrom=$_REQUEST['From'];
			$CallTo=$_REQUEST['To'];
			$CallStatus=$_REQUEST['CallStatus'];
			$ApiVersion=$_REQUEST['ApiVersion'];
			$Direction=$_REQUEST['Direction'];

			if (isset($_REQUEST['FromCity'])){
				$FromCity=$_REQUEST['FromCity'];
				$FromState=$_REQUEST['FromState'];
				$FromZip=$_REQUEST['FromZip'];
				$FromCountry=$_REQUEST['FromCountry'];
			} else {
				$FromCity="";
				$FromState="";
				$FromZip="";
				$FromCountry="";
			}
			$ToCity=$_REQUEST['ToCity'];
			$ToState=$_REQUEST['ToState'];
			$ToZip=$_REQUEST['ToZip'];
			$ToCountry=$_REQUEST['ToCountry'];

			$stmt = $this->db->prepare('INSERT INTO calls (DateCreated,CallSid,AccountSid,CallFrom,CallTo,CallStatus,ApiVersion,Direction,FromCity,FromState,FromZip,FromCountry,ToCity,ToState,ToZip,ToCountry) VALUES (DATETIME(\'now\',\'localtime\'),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$vars=array($CallSid,$AccountSid,$CallFrom,$CallTo,$CallStatus,$ApiVersion,$Direction,$FromCity,$FromState,$FromZip,$FromCountry,$ToCity,$ToState,$ToZip,$ToCountry);
			$stmt->execute($vars);
		}

		function save_dialed_call() {
			$CallSid = $_REQUEST['CallSid'];
			$DialCallSid=$_REQUEST['DialCallSid'];
			$DialCallDuration=$_REQUEST['DialCallDuration'];
			$DialCallStatus=$_REQUEST['DialCallStatus'];
			$RecordingUrl=$_REQUEST['RecordingUrl'];

			$stmt = $this->db->prepare('UPDATE calls set DialCallSid=?, DialCallDuration=?, DialCallStatus=?, RecordingUrl=? WHERE CallSid=?');
			$stmt->execute(array($DialCallSid, $DialCallDuration,$DialCallStatus, $RecordingUrl, $CallSid));
		}
	
		function get_calls(){
			$result = $this->db->query('SELECT * FROM calls ORDER BY DateCreated DESC');
		
			$calls=array();

			foreach ($result as $row)
			{
				$call['CallSid'] = $row['CallSid'];
				$call['CallFrom'] = $row['CallFrom'];
				$call['CallTo'] = $row['CallTo'];
				$call['FromCity'] = $row['FromCity'];
				$call['FromState'] = $row['FromState'];
				$call['FromZip'] = $row['FromZip'];
				$call['DialCallDuration'] = $row['DialCallDuration'];
				$call['DialCallStatus'] = $row['DialCallStatus'];
				$call['RecordingUrl'] = $row['RecordingUrl'];
				$call['DateCreated'] = $row['DateCreated'];
				$calls[] = $call;
			}

			return $calls;
		
		}

		function get_calls_count(){
			$result = $this->db->query('SELECT count(*) as cnt, CallTo FROM calls GROUP BY CallTo ORDER BY cnt DESC');
		
			$calls=array();

			foreach ($result as $row)
			{
				$call['cnt'] = $row['cnt'];
				$call['CallTo'] = $row['CallTo'];
				$calls[] = $call;
			}

			return $calls;
		
		}


	}


	if (file_exists('calls.sqlite') != true)
	{
		$db = new DB();
		$db->init();
	}

?>
