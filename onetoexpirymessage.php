<?php
//error_reporting(0);
include ('Connections/conn.php');
date_default_timezone_set( "Africa/Lagos" );
$thedate = time();

	$targets = array("0809", "0909", "0817", "0818", "234809", "234909", "234817", "234818");
	foreach($targets as $target) {
		//echo strstr($user,$target);
		if (strstr($phoneno,$target) != false ){  // if the user's no is an etisalat line and user is accesssing via mobile/tablet
			$heifer += 1;
		}else{ // if the user's no is an non-etisalat line or not using mobile/tablet
			$heifer += 0;
		}
	} 

	//check those subscription who will end in less than 12hours and send subscription message to them
	$sqluserpass1 = $conn->query("select * from services"); 
		$userpass = $sqluserpass1->fetchAll(PDO::FETCH_ASSOC);
		foreach($userpass as $row){
			$services_code = $row[services_code];
			$services_name = $row['services_name'];
			$unsubscribcode = $row['unsubscribcode'];
			$cost = $row['cost'];
			$mtshortcode = $row[mtshortcode];
			$moshortcode = $row[moshortcode];
			$thanksyoumess = $row[thanksyoumess];
			$subsperiod = $row[subsperiod];
	
	$newperiod = $thedate + 24*60*60;
	$query1 =$conn->query("SELECT * FROM subscribers WHERE subcode='$services_code' AND endtime<$newperiod AND status='1'");
			 $query112 = $query1->fetchAll(PDO::FETCH_ASSOC);
			foreach($query112 as $query11){
				$subtime = $query11['subtime'];
				$phoneno = $query11['phoneno'];
				if($heifer == 1){//if it is an etisalat number, autorenew subscriber
					$message = "Hello, your $services_name subscription will expire in 1day. You will be charged $cost/$subsperiod days for renewal. To unsubscribe text $unsubscribcode to $moshortcode";
					$url = "http://184.173.247.178:13013/cgi-bin/sendsms?username=dailyapps2&password=da1lya9952&to=$phoneno&from=$mtshortcode&text=$message";
					httpGet($url);	
				}
			}
    	}

?>
