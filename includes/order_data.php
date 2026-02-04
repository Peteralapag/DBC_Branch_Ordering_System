<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
require $_SERVER['DOCUMENT_ROOT']."/Modules/Frozen_Dough_Management/class/Class.inventory.php";
define("MODULE_NAME", "DBC_Branch_Ordering_System");
$inventory = new FDSInventory;
$cluster = $_SESSION['dbc_branch_cluster'];
$userlevel = $_SESSION['dbc_branch_userlevel'];
$branch = $_SESSION['dbc_branch_branch'];
$year = date("Y");
$month = date("m");
$min_leadtime = $inventory->GetLeadTime('average_leadtime',$db);
$max_leadtime = $inventory->GetLeadTime('max_leadtime',$db);
$days_count = 31;

$dateNow = date('Y-m-d');


if(isset($_POST['limit']))
{
	$limit = "LIMIT ".$_POST['limit'];
} else {
	$limit = "";
}
if(isset($_POST['search']) AND isset($_POST['branch']) AND $_POST['branch'] != '')
{
	$search = $_POST['search'];	
	$branch = $_POST['branch'];	
	$q = "WHERE (recipient LIKE '%$search%' OR control_no LIKE '%$search%') AND branch='$branch' AND status='Open' AND trans_date >= '$dateNow'";
}
else if(!(isset($_POST['search'])) AND isset($_POST['branch']))
{
	$branch = $_POST['branch'];	
	$q = "WHERE branch='$branch' AND status='Open' AND trans_date >= '$dateNow'";
}
else if(isset($_POST['search']) AND $_POST['branch'] == '')
{
	$search = $_POST['search'];	
	$q = "WHERE (recipient LIKE '%$search%' OR control_no LIKE '%$search%') AND cluster='$cluster' AND status='Open' AND trans_date >= '$dateNow'";
}
else 
{
	if($userlevel >= 50)
	{
		$q = "WHERE cluster='$cluster' AND status='Open' AND trans_date >= '$dateNow'";
	} else {
		$q = "WHERE branch='$branch' AND status='Open' AND trans_date >= '$dateNow'";
	}
}

require $_SERVER['DOCUMENT_ROOT']."/Modules/" . MODULE_NAME . "/class/Class.functions.php";
$function = new FDSFunctions;

$pendingRequest = $function->getPendingToReceive($branch,$db);


$clusterallowed = ['BANKID CLUSTER', 'DIGOS CLUSTER', 'GENSAN CLUSTER'];

$isAllowed = 0;
$stmt = $db->prepare("SELECT COUNT(*) as count FROM tbl_branch WHERE location IN (?, ?, ?) AND branch = ?");
$stmt->bind_param("ssss", $clusterallowed[0], $clusterallowed[1], $clusterallowed[2], $branch);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();


if($result['count'] > 0){
    $isAllowed = 1;
} else {
    $isAllowed = 0;
}
$stmt->close();

?>
<style>
.table td {
	padding:2px 5px 2px 5px !important;
}
</style>
<table style="width: 100%" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th style="width:50px;text-align:center">#</th>
			<th style="width:130px">BRANCH</th>
			<th style="width:130px">CONTROL No.</th>
			<th style="width:130px">FORM TYPE</th>
			<th>RECIPIENT</th>
			<th style="width:150px">ORDER BY</th>
			<th style="width:80px">ORDER DATE</th>
			<th style="width:70px">STATUS</th>
			<th style="width:70px">PRIORITY</th>
			<th style="width: 70PX">ACTIONS</th>
		</tr>
	</thead>
	<tbody>
<?PHP
	$sqlQuery = "SELECT * FROM dbc_order_request $q ORDER BY trans_date DESC";
	$results = mysqli_query($db, $sqlQuery);    
    if ( $results->num_rows > 0 ) 
    {
    	$i=0;
    	while($ORDERROW = mysqli_fetch_array($results))  
		{
			$i++;$average=0;
			$rowid = $ORDERROW ['request_id'];
			$form_type = $ORDERROW ['form_type'];
			$control_no = $ORDERROW ['control_no'];
			$orderdate = $ORDERROW['trans_date'];
			$branchdata = $ORDERROW['branch'];
			if($ORDERROW ['priority'] == 'Urgent')
			{
				$priority_indicator = 'background:#e34d5b;color:#fff';
			} else {
				$priority_indicator = '';
			}
			if($ORDERROW ['status'] == 'Closed')
			{
				$status_indicator = 'background:#e37b85;color:#fff';
			} else {
				$status_indicator = '';
			}
?>			
		<tr ondblclick="editRequest('edit','<?php echo $rowid; ?>')">
			<td style="width:50px;text-align:center"><?php echo $i; ?></td>
			<td><?php echo $ORDERROW['branch']; ?></td>
			<td><?php echo $ORDERROW['control_no']; ?></td>
			<td><?php echo $ORDERROW ['form_type']; ?></td>
			<td><?php echo $ORDERROW['recipient']; ?></td>
			<td><?php echo $ORDERROW['created_by']; ?></td>
			<td><?php echo $ORDERROW['trans_date']; ?></td>
			<td style="text-align:center;<?php echo $status_indicator; ?>"><?php echo $ORDERROW ['status']; ?></td>
			<td style="text-align:center;<?php echo $priority_indicator; ?>"><?php echo $ORDERROW ['priority']; ?></td>
			<td>
				<div class="change-btn btn-info" onclick="orderDetails('<?php echo $control_no; ?>','<?php echo $form_type; ?>','<?php echo $orderdate?>','<?php echo $branchdata?>')">Details</div>
			</td>
		</tr>
<?PHP 	} } else { ?>
		<tr>
			<td colspan="10" style="text-align:center"><i class="fa fa-bell"></i>&nbsp;&nbsp;No Records</td>
		</tr>
<?PHP } mysqli_close($db); ?>			
	</tbody>
</table>
<script>
function orderDetails(controlno,formtype,orderdate,branch)
{

	var isallowed = '<?php echo $isAllowed; ?>';
	var pr = '<?php echo $pendingRequest?>';
	if(isallowed == 0){
		
		if(pr > 0){
			swal("System Message","You have " +pr+ " Pending to Receive Order", "warning");
			return false;
		}
	}

	var module = '<?php echo MODULE_NAME; ?>';
	rms_reloaderOn("Loading...");
	$.post("./Modules/" + module + "/pages/order_page.php", { controlno: controlno, form_type: formtype, orderdate: orderdate, branch: branch },
	function(data) {		
		$('#smnavdata').html(data);
		rms_reloaderOff();
	});	
}
</script>
