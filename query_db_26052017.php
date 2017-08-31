<?php
error_reporting(0);
include ('Connections/conn.php');
date_default_timezone_set( "Africa/Lagos" );
$time = date('H:i:s');
$date = date('Y-m-d');
$thedate = time();
$reply = '';
$dest = $_REQUEST['DESTADDR'];
$phoneno = $_REQUEST['SOURCEADDR'];
$parameter= trim(strtoupper($_REQUEST['MESSAGE']));
/*$phoneno = $_REQUEST['to'];
if ($phoneno == ""){
	$phoneno = $_REQUEST['source'];
}*/
	$targets = array("0809", "0909", "0817", "0818", "234809", "234909", "234817", "234818");
				foreach($targets as $target) {
					//echo strstr($user,$target);
						if (strstr($phoneno,$target) != false ){  // if the user's no is an etisalat line and user is accesssing via mobile/tablet
							$heifer += 1;
							$MNO = 'EMTS';
						}else{ // if the user's no is an non-mtn line or not using mobile/tablet
							$heifer += 0;
						}
			 	} 

	$sqluserpass = $conn->query("select * from services WHERE unsubscribcode='$parameter'"); //unsubscribe process
	if (count($sqluserpass->fetchAll())> 0){
		$tot = $conn->query("select * from services WHERE unsubscribcode='$parameter'");
		$userpass = $tot->fetchAll(PDO::FETCH_ASSOC);
		foreach($userpass as $row){
			$services_code = $row['services_code'];
		}
		$conn->exec("UPDATE subscribers SET status='0',subtime='$thedate' where phoneno='$phoneno' AND subcode='$services_code'");
		exit;
	}
				
$query1 =$conn->query("select * from services WHERE confirmation_keyword='$parameter'");
$count_conf = count($query1->fetchAll());//count if the parameter is confirmation keyword

if ((strtolower($_REQUEST['MESSAGE']) != 'no' and strtolower($_REQUEST['MESSAGE']) != 'yes' and $count_conf<1) || isset($_SESSION['blah'])){
	echo "Are you sure you want to subscribe for this service?";
	$tot = $conn->query("select * from services WHERE services_code='$parameter'");
		$userpass = $tot->fetchAll(PDO::FETCH_ASSOC);
		foreach($userpass as $row){
			echo $confirmation_message = $row['confirmation_message'];
			$moshortcode = $row['moshortcode'];
		}
		
	$message = str_replace(" ", '+', $confirmation_message);
	$message = str_replace("++", '+', $message);
	$url = "http://184.173.247.178:13013/cgi-bin/sendsms?username=dailyapps2&password=da1lya9952&to=$phoneno&from=4662&text=$message";
	httpGet($url);	
		$conn->exec("INSERT INTO request (Parameter,Date,Time,Status,Phoneno) Values ('$parameter','$date','$time','confirmation required','$phoneno')");
	$_SESSION["blah"] = "conf_mess";	//the session variable that ensures the user once entered a variable
	exit;
}
if (strtolower($_REQUEST['MESSAGE']) == 'no'){
	//echo "Your subscription was not successful.Try again";
	$message = "Your subscription was not successful.Try again";
	$message = str_replace(" ", '+', $message);
	//$url = "http://184.173.247.178:13013/cgi-bin/sendsms?username=dailyapps2&password=da1lya9952&to=$phoneno&from=4662&text=$message";
	//httpGet($url);	
		$conn->exec("INSERT INTO request (Parameter,Date,Time,Status,Phoneno) Values ('$parameter','$date','$time','confirmation denied','$phoneno')");	
	exit;
}
	
//$_REQUEST['confirmation'] == '1' and 
if (strtolower($_REQUEST['MESSAGE']) != 'no' and $count_conf>0){//after a yes from the user, subscriber the user and forward the request to another app for content delivery
	$sqluserpass1 = $conn->query("select * from services WHERE services_code='$parameter' OR confirmation_keyword='$parameter'"); 
		$userpass = $sqluserpass1->fetchAll(PDO::FETCH_ASSOC);
		foreach($userpass as $row){
			$services_code = $row[services_code];
			$mtshortcode = $row[mtshortcode];
			$moshortcode = $row[moshortcode];
			$thanksyoumess = $row[thanksyoumess];
			$subsperiod = $row[subsperiod];
    	}
	$period = $subsperiod*24*60*60;

	$query1 =$conn->query("SELECT * FROM subscribers WHERE phoneno = '$phoneno' AND subcode='$services_code' ORDER BY id DESC LIMIT 1");
			 $query11 = $query1->fetch();
			 $subtime = $query11[subtime];
			
	$query1 =$conn->query("SELECT * FROM subscribers WHERE phoneno = '$phoneno' AND subcode='$services_code'");
	$count = count($query1->fetchAll());
	if($count>0){//check if the user had subscribed before
			//check if subscription hasn't expired
		$whereConditions = array('phoneno ='=>$phoneno, 'AND subcode ='=>$parameter);
		$q = $db->select('subscribers',array('*'),$whereConditions,'ORDER BY subtime DESC LIMIT 1')->results();
		$subtime = $q[0]['subtime'];
		if ($subtime > $thedate){//the user is still active
		$newperiod = $subtime + $period;
			$conn->exec("UPDATE subscribers SET status='1',subtime='$thedate',endtime='$newperiod' where phoneno='$phoneno' AND subcode='$services_code'");			
		}else{
		$newperiod = $thedate + $period;
			$conn->exec("UPDATE subscribers SET status='1',subtime='$thedate',endtime='$newperiod' where phoneno='$phoneno' AND subcode='$services_code'");			
			
		}
					
	}else{
		$newperiod = $thedate + $period;
		$conn->exec("INSERT INTO subscribers(phoneno,status,subcode,subtime,endtime) VALUES ('$phoneno','1','$services_code','$thedate','$newperiod')");  
	}
		$conn->exec("INSERT INTO request (Parameter,Date,Time,Status,Phoneno) Values ('$services_code','$date','$time','confirmation accepted','$phoneno')");	
	
	$thanksyoumess = str_replace(" ","+",$thanksyoumess);
	$message = str_replace("++", '+', $thanksyoumess);
	$url = "http://184.173.247.178:13013/cgi-bin/sendsms?username=dailyapps2&password=da1lya9952&to=$phoneno&from=4662&text=$message";
	httpGet($url);	

	//$url = "http://184.173.247.178:13013/cgi-bin/sendsms?username=cpa123&password=pass123&to=$phoneno&from=$mtshortcode&text=$thanksyoumess&dlr-mask=31&dlr-url=http%3A%2F%2Fwww.loadedsms.com%2Fetisalatmt%2Fdlr.php%3Freport%3D%25d%26time%3D%25t%26source%3D%25p%26boxid%3D%25I%26kwd%3D%25k%26dst%3D%25P%26msg%3D%25a%26deta%3D$services_code";
	$url = "https://mcontent.3wc4.com/subscription?MESSAGE=$services_code&SHORTCODE=$moshortcode&SENDER=$phoneno&MESSAGEID=[MID]&OPERATOR=$MNO&TIMESTAMP=$thedate";
	httpGet($url);
	
	unset($_SESSION["blah"]);
	session_destroy();
	exit;
}

?>
