<?php
//error_reporting(E_ALL); ini_set('display_errors', 1);
ob_start();
include_once "Connections/conn.php";
include_once "Connections/functions.php";
include_once "Connections/class_database.php";
$adminname=$_SESSION['adminname'];
if(!isset($_SESSION['username']) and !isset($_SESSION['adminname'])){

  header("Location:login.php");
}
	
		if ($_POST['submit']){
			$selmonth = $_POST['selmonth'];
			$selyear = $_POST['selyear']; 
			$specperiod = date('F, Y',mktime(0, 0, 0, $selmonth+1, 0, $selyear));
			echo $startdate = strtotime("$selyear-$selmonth-01");
			$enddate = strtotime("$selyear-$selmonth-31");
		  $query = "SELECT * FROM services WHERE isactive='1'"; //those that register more than 3 days and haven't upgraded
		  $result = $db_handle->runQuery($query);
		  while( $row = mysqli_fetch_array($result)) {
			  $subcode = $row['services_code'];
			  $services_name = $row['services_name'];
			  $subtime = $row['subtime'];
			  $endtime = $row['endtime'];
			  //$query = "SELECT * FROM subscribers WHERE MONTH(datereg)='$selmonth' AND YEAR(datereg)='$selyear' AND subcode='$subcode'";
			  //$query = "SELECT * FROM subscribers WHERE MONTH(from_unixtime(subtime,'%Y%m%d'))='$selmonth' AND YEAR(from_unixtime(subtime,'%Y%m%d'))='$selyear' AND subcode='$subcode'";
			  $query = "SELECT * FROM subscribers WHERE subtime>='$startdate' AND endtime<='$enddate' AND subcode='$subcode'";
			  $totalrows = $db_handle->numRows($query);
			$message_success .= "Total subscribers for the month of $specperiod ON Service($services_name) is $totalrows persons<br>";
			$totsubcr += $totalrows;

		  }
			$message_success .= "Total subscribers for the month of $specperiod is $totsubcr";
			
			
$query = "SELECT * FROM subscribers WHERE MONTH(datereg)='$selmonth' AND YEAR(datereg)='$selyear' order by id DESC ";
$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination
// for search results
if (isset($_POST['search_text'])) {
    $rowsperpage = $numrows;
} else {
    $rowsperpage = 20;
}

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
   $currentpage = (int) $_GET['pg'];
} else {
   $currentpage = 1;
}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$gethelp = $db_handle->fetchAssoc($result);

	}
	?><!DOCTYPE HTML>
<html>
<head>
<title>3WC Content App Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="3wc,Content App,entertainment,health,callertune,education,origundate,playandwin,hotnews,religion,search,get content,Android,Smartphone,Nokia,Samsung,LG,SonyEricsson,Motorola" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel='stylesheet' type='text/css' />

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
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
 

</head>
<body>
<div id="wrapper">
     <!-- Navigation -->
        <?php include('nav.php');?>
                <?php
		if (!in_array(5,$allid1)){
			header('Location:restricted.php');
		}?>
        <div id="page-wrapper">
        <div class="graphs">
	     <div class="xs">
  	       <h3>Admin Section</h3>
  	         
        <div class="col-md-12 inbox_right">
        	<div class="Compose-Message">               
                <div class="panel panel-default">
                    <div class="panel-heading">
                        View Subscribers Count
                    </div>
                    <div class="panel-body">
              			<?php require_once 'layouts/feedback_message.php'; ?>
                        <form id="form1" name="form1" method="post" action="">
                          Select Month And Year:   
                          <select name="selmonth" id="selmonth">
                            <option>Select Month</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                            </select>
                           <select name="selyear" id="selyear">
                             <option>Select Year</option>
                             <option value="2017">2017</option>
                             <option value="2018">2018</option>
                             <option value="2019">2019</option>
                             <option value="2020">2020</option>
                           </select>
                           <input type="submit" name="submit" id="submit" value="Submit" />
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