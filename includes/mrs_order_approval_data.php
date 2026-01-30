<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
define("MODULE_NAME", "DBC_Branch_Ordering_System");
require_once($_SERVER['DOCUMENT_ROOT']."/Modules/".MODULE_NAME."/class/Class.functions.php");
$branch = $_SESSION['dbc_branch_branch'];
$control_no = $_POST['control_no'];
$userlevel = $_SESSION['dbc_branch_userlevel'];
$function = new FDSFunctions;
$sqlQueryData = "SELECT * FROM dbc_order_request WHERE control_no='$control_no'";
$dataResults = mysqli_query($db, $sqlQueryData);    
if ( $dataResults->num_rows > 0 ) 
{
	$i=0;
	while($DATAROW = mysqli_fetch_array($dataResults))  
	{
		$branch = $DATAROW['branch'];
		$mrs_no = $DATAROW['control_no'];
		$recipient = $DATAROW['recipient'];
		$trans_date = $DATAROW['trans_date'];
		if($DATAROW['checked_by'] != '' && $DATAROW['checked_date'] != '')
		{
			$checked_en = '';
		} else {
			$checked_en = 'disabled';
		}
	}
}
?>
<style>
.mrs-wrappers {width: 8.5in;border:1px solid #aeaeae;padding:0.2in;}
.table-thth th{background: #838383;color: #fff;font-weight:normal !important;padding:5px;text-align:center;}
.table-tdth {}
.table td {padding:4px;font-size:12px;font-weight: normal;border:0 !important;}
.table-hover:hover {background: #f7f8eb;}
.tabletas th {border: 1px solid #232323;font-size:11px;font-weight: 600;padding:2px;text-align:center;}
.tabletas td {border: 1px solid #232323;font-size:11px;padding:2px;font-weight: normal;}
.form-footer td {text-align: center;font-size: 12px;}
.btn-thin {padding: 0 !important}
.approval-text {font-weight: 600;font-size:18px;}
.names {font-weight: 600;}
.dates {font-style:italic;color: #aeaeae;}
.button {padding: 2px 7px 2px 7px !important;}
</style>
<div style="padding:10px">
<div class="mrs-wrappers" style="margin:0 auto">	
	<table style="width: 100%" class="table">
		<tr class="table-thth">
			<th colspan="7">MATERIAL REQUISITION FORM</th>
		</tr>
		<tr>
			<td style="width:120px">Requesting Section/Branch:</td>
			<td style="width:5px !important;">&nbsp;</td>
			<td style="border-bottom:1px solid #232323 !important;width:400px"><?php echo $branch;?></td>
			<td style="width:">&nbsp;</td>
			<td>Control No.:</td>
			<td style="width:3px !important;"></td>
			<td style="width:150px;border-bottom:1px solid #232323 !important;text-align:center;color:red;font-weight:bold"><?php echo $mrs_no;?></td>
		</tr>
		<tr>
			<td style="width:120px">&nbsp;</td>
			<td style="width:5px !important;">&nbsp;</td>
			<td style=";width:400px">&nbsp;</td>
			<td style="width:">&nbsp;</td>
			<td>Date:</td>
			<td style="width:3px !important;">&nbsp;</td>
			<td style="width:150px;border-bottom:1px solid #232323 !important;text-align:center"><?php echo $trans_date;?></td>
		</tr>
	</table>
	<table style="width: 100%" class="tabletas">
		<tr>
			<th style="width:40px;text-align:center">#</th>
			<th style="width:80px">Item Code</th>
			<th>Description</th>
			<th style="width:100px">Units (UOM)</th>
			<th style="width:75px">Quantity</th>
			<th style="width:75px;">Actual Qty</th>
			<th style="width:75px;">Inv. Ending</th>
		</tr>
<?php
	$sqlQueryData = "SELECT * FROM dbc_branch_order WHERE branch='$branch' AND control_no='$mrs_no' ORDER BY id DESC";
	$dataResults = mysqli_query($db, $sqlQueryData);    
	if ( $dataResults->num_rows > 0 ) 
	{
		$x=0;
		while($DATAROW = mysqli_fetch_array($dataResults))  
		{
			$x++;
			$editid = $DATAROW['id'];
			$remarks = $DATAROW['remarks'];
			$remarks = $function->shortenText($remarks,20);
?>		
		<tr>
			<td style="text-align:center"><?php echo $x; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['item_code']; ?></td>
			<td><?php echo $DATAROW['item_description']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['uom']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['quantity']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['actual_quantity']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['inv_ending']; ?></td>
		</tr>
<?php } ?>
		<tr>
			<td colspan="8" style="padding:5px;white-space:normal">
				<span style="font-weight:600">REMARKS:</span> <?php echo $function->getOrderRemarks($mrs_no,$db); ?>
			</td>
		</tr>
<?php } else { ?>		
		<tr>
			<td colspan="8" style="text-align:center">No Items</td>
		</tr>
<?php } ?>	
	</table>
</div>
<div class="mrs-wrappers" style="margin:0 auto;margin-top:10px">	
	<table style="width: 100%;margin: 0 auto;" class="form-footer">
		<tr>
			<td style="width:30%">Requested By:</td>
			<td style="width:5%">&nbsp;</td>
			<td style="width:30%">Reviewed/Checked By:</td>
			<td style="width:5%">&nbsp;</td>
			<td style="width:30%">Approved By:</td>
		</tr>
		<tr class="approval-text">
			<td style="width:31%;height:30px;border-bottom: 1px solid #232323">
				<?php echo $function->GetChecked($control_no,"created_by",$db); ?>
			</td>
			<td style="width:5%">&nbsp;</td>		
			<td style="width:30%;height:30px;border-bottom: 1px solid #232323">
				<?php if($function->GetChecked($control_no,"checked",$db) == NULL) { ?>				
					<button class="btn btn-warning btn-thin btn-sm button color-white" onclick="Check_Accesss('<?php echo $control_no; ?>','p_approver',approveReview)">Approve?</button>
				<?php } else { echo $function->GetChecked($control_no,"checked_by",$db); } ?>
			</td>		
			<td style="width:5%">&nbsp;</td>
			<td style="width:30%;height:30px;border-bottom: 1px solid #232323">
				<?php if($function->GetChecked($control_no,"approved",$db) == NULL) { ?>
					<button class="btn btn-primary btn-thin btn-sm button" onclick="Check_Accesss('<?php echo $control_no; ?>','p_approver',aproveOrder)" <?php echo $checked_en; ?>>Approve?</button>
				<?php } else { echo $function->GetChecked($control_no,"approved_by",$db); } ?>
			</td>
		</tr>
		<tr class="dates">
			<td style="width:30%"><?php echo $function->GetChecked($control_no,"trans_date",$db) ?></td>
			<td style="width:5%">&nbsp;</td>
			<td style="width:30%"><?php echo $function->GetChecked($control_no,"checked_date",$db) ?></td>
			<td style="width:5%">&nbsp;</td>
			<td style="width:30%"><?php echo $function->GetChecked($control_no,"approved_date",$db) ?></td>
		</tr>
	</table>
</div>
<?php if($function->GetChecked($control_no,"checked",$db) == NULL && $function->GetChecked($control_no,"approved",$db) == NULL) { ?>
<div class="mrs-wrappers" style="margin:0 auto;margin-top:10px;text-align:center">	
	<button class="btn btn-success btn-sm" onclick="returnToChange('<?php echo $control_no; ?>')"><i class="fa-solid fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Return For Changes</button>
	<button class="btn btn-danger btn-sm color-white" onclick="voidOrderRequest('<?php echo $control_no; ?>')"><i class="fa-solid fa-ban"></i>&nbsp;&nbsp;&nbsp;Void Order Request</button>
</div>
<?php } ?>
<div class="results"></div>
<script>
function voidOrderRequest(controlno)
{
	var mode = 'voidorderrequest';
	rms_reloaderOn("Voiding Order Request!...");
	$.post("./Modules/<?php echo MODULE_NAME; ?>/actions/actions.php", { mode: mode, control_no: controlno },
	function(data) {		
		$('.results').html(data);
		rms_reloaderOff();
	});
}
function returnToChange(controlno)
{
	var mode = 'sendingbackrequest';
	rms_reloaderOn("Sending Back!");
	$.post("./Modules/<?php echo MODULE_NAME; ?>/actions/actions.php", { mode: mode, control_no: controlno },
	function(data) {		
		$('.results').html(data);
		rms_reloaderOff();
	});

}
function aproveOrder(controlno)
{
	var mode = 'approveorder';
	var userlevel = '<?php echo $userlevel; ?>';
	if(userlevel >= 50)
	{		
		rms_reloaderOn("Approving...");
		setTimeout(function()
		{
			$.post("./Modules/<?php echo MODULE_NAME; ?>/actions/actions.php", { mode: mode, control_no: controlno },
			function(data) {		
				$('.results').html(data);
				rms_reloaderOff();
			});
		},1000);
	} else {
		swal("Un-Authorized", "You have unsificient priviledge.", "warning");
	}
}
function approveReview(controlno)
{
	var mode = 'approvecheckreview';
	var userlevel = '<?php echo $userlevel; ?>';
	if(userlevel >= 10 || userlevel <= 50 || userlevel >= 80)
	{		
		rms_reloaderOn("Approving...");
		setTimeout(function()
		{
			$.post("./Modules/<?php echo MODULE_NAME; ?>/actions/actions.php", { mode: mode, control_no: controlno },
			function(data) {		
				$('.results').html(data);
				rms_reloaderOff();
			});
		},1000);
	} else {
		swal("Un-Authorized", "You have unsificient priviledge.", "warning");
	}
}
function orderApproval(controlno)
{
	var module = '<?php echo MODULE_NAME; ?>';
	$.post("./Modules/" + module + "/includes/mrs_order_approval_data.php", { control_no: controlno },
	function(data) {		
		$('#smnavdata').html(data);
	});	
}
$(function()
{
	
});
</script>
<script src="../Modules/<?php echo MODULE_NAME; ?>/scripts/script.js"></script>
<?php mysqli_close($db); ?>