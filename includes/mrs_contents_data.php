<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
define("MODULE_NAME", "DBC_Branch_Ordering_System");
require_once($_SERVER['DOCUMENT_ROOT']."/Modules/".MODULE_NAME."/class/Class.functions.php");
$branch = $_SESSION['dbc_branch_branch'];
$control_no = $_POST['control_no'];
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
		$form_type = $DATAROW['form_type'];
		
	}
}
if($form_type == 'POF')
{
	$FORM_TYPE = "PRODUCT ORDER FORM";
} else {
	$FORM_TYPE = "MATERIAL REQUISITION FORM";
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
</style>
<div style="padding:10px;">
<div class="mrs-wrappers" style="margin:0 auto">	
	<table style="width: 100%" class="table">
		<tr class="table-thth">
			<th colspan="7"><?php echo $FORM_TYPE; ?></th>
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
			<td id="transdateget" style="width:150px;border-bottom:1px solid #232323 !important;text-align:center"><?php echo $trans_date;?></td>
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
			<th style="width:75px;">Inv Ending</th>
		</tr>
<?php
	$sqlQueryData = "SELECT * FROM dbc_branch_order WHERE branch='$branch' AND control_no='$mrs_no'";
	$dataResults = mysqli_query($db, $sqlQueryData);    
	if ( $dataResults->num_rows > 0 ) 
	{
		$submit_text = "";
		$x=0;
		while($DATAROW = mysqli_fetch_array($dataResults))  
		{
			$x++;
			$editid = $DATAROW['id'];
			$remarks = $DATAROW['remarks'];
			$remarks = $function->shortenText($remarks,20);
?>		
		<tr ondblclick="editOrder('edit','<?php echo $control_no; ?>','<?php echo $editid; ?>')">
			<td style="text-align:center"><?php echo $x; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['item_code']; ?></td>
			<td><?php echo $DATAROW['item_description']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['uom']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['quantity']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['actual_quantity']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['inv_ending']." ".substr($DATAROW['inv_ending_uom'], 0, 3);; ?></td>
		</tr>
<?php } ?>
		<tr>
			<td colspan="8" style="padding:5px;white-space:normal">
				<span style="font-weight:600">REMARKS:</span> <?php echo $function->getOrderRemarks($mrs_no,$db); ?>
			</td>
		</tr>
<?php } else { $submit_text = "disabled"; ?>
		<tr>
			<td colspan="8" style="text-align:center">No Items</td>
		</tr>
<?php } mysqli_close($db); ?>	
	</table>
</div>
</div>
<div class="mrs-wrappers" style="margin:0 auto;margin-top:10px">
	<button <?php echo $submit_text; ?> class="btn btn-primary btn-sm" onclick="submitOrder('<?php echo $control_no; ?>')">Submit Order</button>
</div>
<div class="results"></div>
<script>
function submitOrder(controlno)
{
	app_confirm("Submit Order","Are you sure to finish and submit your order?","warning","submitOrderYes",controlno,"orange")
}
function submitOrderYes(controlno)
{
	var mode = 'submitorder';
	var transdateget = $('#transdateget').text();
		
	rms_reloaderOn("Submitting Order...");
	setTimeout(function()
	{
		$.post("./Modules/<?php echo MODULE_NAME; ?>/actions/actions.php", { mode: mode, control_no: controlno, transdateget: transdateget },
		function(data) {		
			$('.results').html(data);
			rms_reloaderOff();
		});
	},1000);
}
function editOrder(params,control_no,editid)
{
	$.post("./Modules/<?php echo MODULE_NAME; ?>/apps/order_form_input.php", { params: params, control_no: control_no, editid: editid },
	function(data) {		
		$('#orderform').html(data);
	});
}
</script>
