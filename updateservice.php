<?php
ob_start();
include_once "Connections/conn.php";
include_once "Connections/functions.php";
$adminname=$_SESSION['adminname'];
if(!isset($_SESSION['adminname'])){	

  header("Location:login.php");
}?><!DOCTYPE HTML>
<html>
<head>
<title>3WC Content App Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="3wc,Content App,entertainment,health,callertune,education,origundate,playandwin,hotnews,religion,search,get content,Android,Smartphone,Nokia,Samsung,LG,SonyEricsson,Motorola" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!----webfonts--->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
<!---//webfonts--->  
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
</head>
<body>
<div id="wrapper">
     <!-- Navigation -->
        <?php include('nav.php');?>
                <?php
		if (!in_array(11,$allid1)){
			header('Location:restricted.php');
		}?>
        <div id="page-wrapper">
        <div class="graphs">
	     <div class="xs">
  	       <h3>Services</h3>
  	         
        <div class="col-md-12 inbox_right">
        	<div class="Compose-Message">               
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Edit Services 
                    </div>
                    <div class="panel-body">
                        <hr>
                                                                        
                         <?php

$id = $_GET['id'];
$whereCondition = array('services_id'=>"$id");
$qr = $db->select('services',array('*'),$whereCondition,'order BY services_id DESC')->results();
$q = $db->count('services',"services_id ='$id'"); 
		if ($q == 0 ) {
			echo ('<p>No such information in the Database </p>');		
		} else {
			foreach($qr as $row)
			{
				$services_id = $row['services_id'];
				$services_name = $row['services_name'];
				$services_code = $row['services_code'];
				$subsperiod = $row['subsperiod'];
				$subdescript = $row['subdescript'];
				$descript = $row['descript'];
				$thanksyoumess = $row['thanksyoumess'];
				$confirmation_keyword = $row['confirmation_keyword'];
				$confirmation_message = $row['confirmation_message'];
				$mtshortcode = $row['mtshortcode'];
				$moshortcode = $row['moshortcode'];
				$unsubscribcode = $row['unsubscribcode'];
				$isactive = $row['isactive'];
			}
			
}

	if ($_POST['close']){
	  header("Location:viewservices.php");
	}
	
?>

                        

          <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data"  class="col-md-12">
<?php
if ($_POST['update']){
	$id = $_GET['id'];
$servicename = $_POST['servicename'];	
$servicecode = strtoupper($_POST['servicecode']);	
$mtshortcode = $_POST['mtshortcode'];	
$moshortcode = $_POST['moshortcode'];	
$subsperiod = $_POST['subsperiod'];	
$optoutcode = $_POST['optoutcode'];	
$productid = $_POST['productid'];	
$shortdescript = $_POST['shortdescript'];	
$descript = $_POST['descript'];	
$messatosubcr = $_POST['messatosubc'];	
$confkeyword = $_POST['confkeyword'];	
$confmessage = $_POST['confmessage'];	
$help_keyword = $_POST['help_keyword'];	

	if ($err == ''){			
			$dataArray2 = array('services_name'=>"$servicename",'services_code'=>"$servicecode",'confirmation_keyword'=>"$confkeyword",'confirmation_message'=>"$confmessage",'thanksyoumess'=>"$messatosubcr",'subdescript'=>"$shortdescript",'descript'=>"$descript",'productid'=>"$productid",'help_keyword'=>"$help_keyword",'unsubscribcode'=>"$optoutcode",'mtshortcode'=>"$mtshortcode",'moshortcode'=>"$moshortcode",'subsperiod'=>"$subsperiod",'username'=>"$adminname",'isactive'=>"1"); 
		$aWhere = array('services_id'=>$id);
		$data = $db->update('services', $dataArray2, $aWhere)->affectedRows();
	}else{
			 echo "<div class=\"infobox clearfix infobox-close-wrapper success-bg mrg20B\"> $err  </div>";
	}

		  if ($data){		
			 echo "<div class=\"alert alert-info\"> Service updated successfully. Refresh to see chnages  </div>";
		  }
}
?>
								<br>					
                            <label for="">Service Name:</label>
                            <input name="servicename" value="<?php echo $services_name;?>" required class="form-control1 control3" type="text" size="50">
                            <label for=""> Service Code/Keyword:</label>
                            <input name="servicecode" value="<?php echo $services_code;?>" required class="form-control1 control3" type="text" size="50">
                            <label for="">Opt-out Code:</label>
                            <input name="optoutcode" value="<?php echo $unsubscribcode;?>" required class="form-control1 control3" type="text" size="50">
                       <!--     <label for=""> Product ID:</label>
                            <input name="productid" class="form-control1 control3" type="text" size="50">-->
                            <label for="">MT Shortcode:</label>
                            <input name="mtshortcode" value="<?php echo $mtshortcode;?>" required class="form-control1 control3" type="text" size="50">
                            <label for="">MO Shortcode:</label>
                            <input name="moshortcode" value="<?php echo $moshortcode;?>" required class="form-control1 control3" type="text" size="50">
                            <label>Confirmation Keyword: </label>
                        	<input name="confkeyword" value="<?php echo $confirmation_keyword;?>" required class="form-control1 control3" type="text" size="50">
                            <label>Confirmation Message: </label>
                        	<textarea rows="6" name="confmessage" class="form-control1 control2"><?php echo $confirmation_message;?></textarea>                            
                             <label for=""> Thank you Message:</label>
                            <input name="messatosubc" value="<?php echo $thanksyoumess;?>" required class="form-control1 control3" type="text" size="50">
                            <label for="">Subscription Period:</label>
                            <select name="subsperiod" class="form-control1 control3">
                             <option value=''>Select a Period</option>
                                         <?php 		$qr = $db->select('subsperiod',array('*'))->results();
													foreach($qr as $fet){
                                                        $period=$fet['period'];
                                                        $periodcode=$fet['periodcode'];
														?>
                                                       <option value='<?=$periodcode;?>' <?php if ($subsperiod==$periodcode) echo 'selected';?>> <?=$period;?> </option>
                                                       <?php } ?>
                                                                </select>
                            <label>HELP Keyword: </label>
                            <input name="help_keyword" value="<?php echo $help_keyword;?>" required class="form-control1 control3" type="text" size="50">

                            <input name="update" value="Update Service" class="btn btn-success btn-warng1" type="submit" />
                            <input name="Reset" value="Cancel" class="btn btn-warning btn-warng1" type="reset" />
                            </form>
                    </div>
                 </div>
              </div>
         </div>
         <div class="clearfix"> </div>
   </div>
    <div class="copy_layout">
         <p>Copyright © <?php echo date('Y');?> 3Way Communications. All Rights Reserved </p>
       </div>
   </div>
      </div>
      <!-- /#page-wrapper -->
   </div>
    <!-- /#wrapper -->
<!-- Nav CSS -->
<link href="css/custom.css" rel="stylesheet">
<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
