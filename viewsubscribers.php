<?php
//error_reporting(E_ALL); ini_set('display_errors', 1);
ob_start();
include_once "Connections/conn.php";
include_once "Connections/functions.php";
$adminname=$_SESSION['adminname'];
if(!isset($_SESSION['username']) and !isset($_SESSION['adminname'])){

  header("Location:login.php");
}
	if ($_POST['deletesubsc']){
     $hiddel = $_POST['hiddel'];
	$aWhere = array('id'=>"$hiddel"); 
$data1 = $db->delete('subscribers', $aWhere)->affectedRows();
if ($data1){
	$heifer = "Subscriber deleted successfully.";
}
	}
		if ($_POST['actsubsc']){
     $sid = $_POST['hiddel'];
	 $whereConditions = array('id ='=>$sid);
$qr1 = $db->select('subscribers',array('status'),$whereConditions)->results();
$status = $qr1[0]['status'];

if ($status== 1){
	$dataArray = array('status'=>"0");  
	$heifer = "Subscriber deactivated successfully.";
}else{
	$dataArray = array('status'=>"1");  	
	$heifer = "Subscriber activated successfully.";
}
		$aWhere = array('id'=>"$sid"); 
		$data1 = $db->update('subscribers', $dataArray, $aWhere)->affectedRows();

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
                        View Subscribers 
                    </div>
                    <div class="panel-body">
              <?php if ($data1){
						echo "<div class=\"alert alert-info\">$heifer</div>";
							}
				?>			
                      
                      <table  class="table table-bordered" id="hidden-table-info">
                           <thead>
                              <tr>
                                            <th>MSISDN</th>
                                            <th>Keyword</th>
                                            <th>Status</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Details</th>
                                            <th>Activate/Deactivate</th>
                                            <th>Delete</th>
                              </tr>
                           </thead>
                           <tbody>
                   <?php 
	if( $limit=""){$limit = 40; }else{$limit = $_POST['limval'];}
	$qry3 = "SELECT * FROM subscribers";			
	
	//$totalrows = count($conn->query($qry3)->fetchAll());
    $totalrows = $db->count('subscribers');

	$page=(int)$_GET['page'];
			if(empty($page)){
  			  $page = 1;
			}
			$limit = 40;	 	$page;
		$limitvalue = $page * $limit - ($limit);
		
		$i = 1;
	
	$sql = $conn->query($qry3." LIMIT $limitvalue,$limit");
		//if( $totalrows > 0){			
	$sdd1 = $sql->fetchAll(PDO::FETCH_ASSOC);
		foreach($sdd1 as $row){
				 $id = $row['id'];
				 $phoneno = $row['phoneno'];
?>
                <tr class="<?php if (is_odd($id)){ echo "gradeX";}else { echo "gradeA";} ?> ">
                    <td><?php echo $row['phoneno']; ?></td>
                    <td><?php echo $row['subcode']; ?></td>
                    <td class="center"><?php echo $row['status']; ?></td>
                    <td class="center"><?php echo date('d, M Y G:i A',$row['subtime']); ?></td>
                    <td class="center"><?php echo date('d, M Y G:i A',$row['endtime']); ?></td>
                    <td width="10%"><a href="subscrdetails.php?sid=<?= $phoneno;?>" class="btn btn-warning" data-toggle="modal" data-id="<?= $id;?>">Details</a></td>
             <?php
			      	 $whereConditions = array('id ='=>$id);
$qr1 = $db->select('subscribers',array('status'),$whereConditions)->results();
$status = $qr1[0]['status'];
				if ($status == '1'){  ?>
                    <td><a class="btn btn-default" href="#modal-custom-dialog1" data-toggle="modal" data-id="<?= $id;?>">Deactivate</a></td>
			<?php	}else{ ?>
                    <td width="10%"><a class="btn btn-default" href="#modal-custom-dialog1" data-toggle="modal" data-id="<?= $id;?>">Activate</a></td>
            <?php	}?>
                    <td width="10%"><a href="#modal-custom-dialog2" class="btn btn-danger" data-toggle="modal" data-id="<?= $id;?>">Delete</a></td>
                </tr>
<?php					}
	//	}
?>
                                      
                           </tbody>
                        </table>
                        
                        <?php
$pageprev = $page -1;
$page1 = $page + 1;
$page2 = $page + 2;
$page3 = $page + 3;
$pagenext = $page +4;
$numofpages = intval($totalrows / $limit) + 1;
$prespagelow = $page*$limit - $limit +1;
$prespagehigh = $page*$limit;
?>                                
                                <div class="tool-footer text-right"><p class="pull-left">Showing <?php echo $prespagelow." to ".$prespagehigh." of ".$totalrows; ?> entries</p>
                                    <ul class="pagination">
                                       <li><?php echo "<a href=\"?page=1&search2=yes\">First</a>"; ?></li>
                                        <li><?php echo "<a href=\"?page=$pageprev&search2=yes\">&laquo;</a>"; ?></li>
                                       <li><?php echo "<a href=\"?page=$page&search2=yes\"> $page </a>"; ?></li>
                                        <li><?php echo "<a href=\"?page=$page1&search2=yes\">$page1</a>"; ?></li>
                                        <li><?php echo "<a href=\"?page=$page2&search2=yes\">$page2</a>"; ?></li>
                                        <li><?php echo "<a href=\"?page=$page3&search2=yes\">$page3</a>"; ?></li>
                                        <li><?php echo "<a href=\"?page=$pagenext&search2=yes\">&raquo;</a>"; ?></li>
                                        <li><?php echo "<a href=\"?page=$numofpages&search2=yes\">Last</a>"; ?></li>
                                    </ul>
                                </div>
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
        <div id="modal-custom-dialog1" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">          
                             <form id="form1" name="form1" method="post" action="">  
                          <div class="modal-header">
                                <button name="emailclose" type="button" aria-hidden="true"
                                        class="close">&times;</button>

                                <h4 class="modal-title">Deactivate/Activate Subscriber Approval</h4></div>
                            <div class="modal-body">Are you sure you want to deactivate/activate this subscriber, this process is irreversible.<br><br>
                            <input name="hiddel" type="hidden">
                           </div>
                            <div class="modal-footer">
                                <input name="actsubsc" type="submit" class="btn btn-danger" value="Deactivate/Activate Subscriber!">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
</form>
                        </div>
                    </div>
          </div>
    <div id="modal-custom-dialog2" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">          
                           <form id="form1" name="form1" method="post" action="">  
                            <div class="modal-header">
                                <button name="emailclose" type="button" aria-hidden="true"
                                        class="close">&times;</button>

                                <h4 class="modal-title">Delete Subscriber Approval</h4></div>
                            <div class="modal-body">Are you sure you want to delete this subscriber, this process is irreversible.<br><br>
                            <input name="hiddel" type="hidden">
                           </div>
                            <div class="modal-footer">
                                <input name="deletesubsc" type="submit" class="btn btn-danger" value="Delete Subscriber!">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
							</form>
                        </div>
                    </div>
          </div>
<!-- Nav CSS -->
<link href="css/custom.css" rel="stylesheet">
<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<script>
$(document).ready(function(){
    $('#modal-custom-dialog1').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'delmodal.php', //Here you will fetch records 
            data :  'rowid='+ rowid, //Pass $id
            success : function(data){
            $('.fetched-data').html(data);//Show fetched data from database
			$("input[name='hiddel']").val(data);
            }
        });
     });
	     $('#modal-custom-dialog2').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'delmodal.php', //Here you will fetch records 
            data :  'rowid='+ rowid, //Pass $id
            success : function(data){
            $('.fetched-data').html(data);//Show fetched data from database
			$("input[name='hiddel']").val(data);
            }
        });
     });
});
$(document).ready(function() {
    $('#hidden-table-info').DataTable();
} );
</script> 
</body>
</html>