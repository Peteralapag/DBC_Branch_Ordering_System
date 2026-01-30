<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
require $_SERVER['DOCUMENT_ROOT']."/Modules/Frozen_Dough_Management/class/Class.inventory.php";
define("MODULE_NAME", "DBC_Branch_Ordering_System");
$inventory = new FDSInventory;
$branch = $_SESSION['dbc_branch_branch'];
$year = date("Y");
$month = date("m");
$min_leadtime = $inventory->GetLeadTime('average_leadtime',$db);
$max_leadtime = $inventory->GetLeadTime('max_leadtime',$db);
$days_count = 31;

if($_POST['limit'] != '')
{
	$limit = "LIMIT ".$_POST['limit'];
} else {
	$limit = "";
}
if(isset($_POST['search']))
{
	$search = $_POST['search'];	
	$q = "WHERE branch='$branch' AND status = 'Approval' AND recipient LIKE '%$search%' OR control_no LIKE '%$search%'";
} else {
	$q = "WHERE branch='$branch' AND status = 'Submitted' AND checked='Approved'  AND approved='Approved' ORDER BY priority DESC $limit";
}
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
	$sqlQuery = "SELECT * FROM dbc_order_request $q";
	$results = mysqli_query($db, $sqlQuery);    
    if ( $results->num_rows > 0 ) 
    {
    	$i=0;
    	while($ORDERROW = mysqli_fetch_array($results))  
		{
			$i++;$average=0;
			$rowid = $ORDERROW ['request_id'];
			$control_no = $ORDERROW ['control_no'];
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
				<div class="change-btn btn-info" onclick="orderApproval('<?php echo $control_no; ?>')">View</div>
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
function orderApproval(controlno)
{
	var module = '<?php echo MODULE_NAME; ?>';
	$.post("./Modules/" + module + "/includes/mrs_order_tracking_data.php", { control_no: controlno },
	function(data) {		
		$('#smnavdata').html(data);
	});	
}
</script>
