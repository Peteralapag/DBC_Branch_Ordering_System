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
	}
}
?>
<style>
.mrs-wrappers {width: 8.5in;border:1px solid #aeaeae;padding:0.2in;}
.table-thth th{background: #838383;color: #fff;font-weight:normal !important;padding:5px;text-align:center;}
.table-tdth {}
.table td {padding:4px;font-size:12px;font-weight: normal;border:0 !important;}
.table-hover:hover {background: #f7f8eb;}
.tabletas th {border: 1px solid #232323;font-size:11px;font-weight: 600;padding:5px;text-align:center;}
.tabletas td {border: 1px solid #232323;font-size:11px;padding:2px;font-weight: normal;}
.form-footer td {text-align: center;font-size: 12px;}
.btn-thin {padding: 0 !important}
.approval-text {font-weight: 600;font-size:18px;}
.names {font-weight: 600;}
.dates {font-style:italic;color: #aeaeae;}
.button {padding: 2px 7px 2px 7px !important;}
.order-circle {display: flex;width:50px;height:50px;border:5px solid green;border-radius:50%;justify-content: center;align-items: center;font-size:24px}
.order-circle-gray {display: flex;width:50px;height:50px;border:5px solid #aeaeae;border-radius:50%;justify-content: center;align-items: center;font-size:24px}
.status-text {text-align:center;font-size: 11px}
.status-date {text-align:center;text-align:center;font-style:italic;color:#AEAEAE;}
.icontext-color { color: #aeaeae; }
.bar-color {border: 2px solid green}
.bar-color-gray {border: 2px solid #aeaeae}
.item-received {font-size: 18px;}
.item-received-not {font-size: 18px;cursor: pointer;}
.item-received-not:hover {color:red;}
.inptactqty {
	box-sizing: border-box; 
	height:25px;
	width:100%;
	outline: none;
	border:0;
	text-align:center;
	background:#faf9eb;
}
</style>
<?php
if($function->GetOrderStatus($mrs_no,"order_received",$db) == 1)
{
	$class_or = "order-circle";
	$icon_color_or = "";
	$bar_color_or = "bar-color";
} else {
	$class_or = "order-circle-gray";
	$icon_color_or = "icontext-color";
	$bar_color_or = "bar-color-gray";
}
if($function->GetOrderStatus($mrs_no,"order_preparing",$db) == 1)
{
	$class_prep = "order-circle";
	$icon_color_prep = "";
	$bar_color_prep = "bar-color";
} else {
	$class_prep = "order-circle-gray";
	$icon_color_prep = "icontext-color";
	$bar_color_prep = "bar-color-gray";
}
if($function->GetOrderStatus($mrs_no,"order_transit",$db) == 1)
{
	$class_trans = "order-circle";
	$icon_color_trans = "";
	$bar_color_trans = "bar-color";
} else {
	$class_trans = "order-circle-gray";
	$icon_color_trans = "icontext-color";
	$bar_color_trans = "bar-color-gray";
}
if($function->GetOrderStatus($mrs_no,"order_delivered",$db) == 1)
{
	$class_del = "order-circle";
	$icon_color_del = "";
	$bar_color_del = "bar-color-gray";
} else {
	$class_del = "order-circle-gray";
	$icon_color_del = "icontext-color";
	$bar_color_del = "bar-color-gray";
}
?>
<div style="padding:10px">
	<div class="mrs-wrappers" style="margin:0 auto;margin-top:10px;text-align:center; display: flex; align-items: center">
		<span><strong>ORDER COMPLETED&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-circle-check color-green" style="font-size:20px"></i></strong></span><span style="margin-left: auto"><button class="btn btn-warning btn-sm color-white" onclick="load_data()">Return</button></span>
	</div>
	<div class="mrs-wrappers" style="margin:0 auto;margin-top:10px">		
		<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width:50px"></td>
				<td style="width:50px;">
					<div class="<?php echo $class_or; ?>"><i class="fa-sharp fa-solid fa-note <?php echo $icon_color_or; ?>"></i></div>
				</td>
				<td><div style="width:100%;height:1px" class="<?php echo $bar_color_or; ?>"></div></td>
				<td style="width:50px;">
					<div class="<?php echo $class_prep; ?>"><i class="fa-solid fa-cart-flatbed-boxes <?php echo $icon_color_prep; ?>"></i></div>
				</td>
				<td><div style="width:100%;height:1px" class="<?php echo $bar_color_prep; ?>"></div></td>
				<td style="width:50px;">
					<div class="<?php echo $class_trans; ?>"><i class="fa-solid fa-truck-fast <?php echo $icon_color_trans; ?>"></i></div>
				</td>
				<td><div style="width:100%;height:1px" class="<?php echo $bar_color_trans; ?>"></div></td>
				<td style="width:50px;">
					<div class="<?php echo $class_del; ?>"><i class="fa-solid fa-inbox-in <?php echo $icon_color_del; ?>"></i></div>
				</td>
				<td style="width:50px"></td>
			</tr>
			<tr>
				<td style="height:10px;"></td>
				<td style="height:10px;"></td>
				<td style="height:10px;"></td>
				<td style="height:10px;"></td>
				<td style="height:10px;"></td>
				<td style="height:10px;"></td>
				<td style="height:10px;"></td>
				<td style="height:10px;"></td>
				<td style="height:10px;"></td>
			</tr>
		</table>		
		<table style="width: 100%">
			<tr class="status-text">
				<td style="width:170px">Order Received</td>
				<td>&nbsp;</td>
				<td style="width:170px">Prepairing Order</td>
				<td>&nbsp;</td>
				<td style="width:170px">In Transit</td>
				<td>&nbsp;</td>
				<td style="width:170px">Order Delivered</td>
			</tr>
			<tr class="status-date">
				<td style="width:170px"><span><?php echo $function->GetOrderStatus($mrs_no,"order_received_date",$db); ?></span></td>
				<td>&nbsp;</td>
				<td style="width:170px"><span><?php echo $function->GetOrderStatus($mrs_no,"order_preparing_date",$db); ?></span></td>
				<td>&nbsp;</td>
				<td style="width:170px"><span><?php echo $function->GetOrderStatus($mrs_no,"order_transit_date",$db); ?></span></td>
				<td>&nbsp;</td>
				<td style="width:170px"><span><?php echo date("Y-m-d", strtotime($function->GetOrderStatus($mrs_no,"order_delivered_date",$db))); ?></span></td>
			</tr>
		</table>
		
	</div>
</div>
<!-- ############################################################ -->
<div class="mrs-wrappers" style="margin:0 auto">	
	<table style="width: 100%" class="table">
		<tr class="table-thth">
			<th colspan="7">ITEM RECEIPT</th>
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
			<th style="width:75px;">W.H. Qty</th>
			<th style="width:75px;">Act. Qty</th>

		</tr>
<?php
	$sqlQueryData = "SELECT * FROM dbc_branch_order WHERE branch='$branch' AND control_no='$mrs_no'";
	$dataResults = mysqli_query($db, $sqlQueryData);    
	if ( $dataResults->num_rows > 0 ) 
	{
		$x=0;
		while($DATAROW = mysqli_fetch_array($dataResults))  
		{
			$x++;
			$rowid = $DATAROW['id'];
			$remarks = $DATAROW['remarks'];
			$item_receipt = $DATAROW['item_receipt'];
			$remarks = $function->shortenText($remarks,20);
			if($DATAROW['item_receipt'] == 1)
			{
				$act_qty = 'disabled';
			}
			else if($DATAROW['item_receipt'] == 0)
			{
				$act_qty = '';
			}
			
			if($DATAROW['branch_received_status'] == '1'){ $act_qty = 'disabled'; }
?>		
		<tr>
			<td style="text-align:center"><?php echo $x; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['item_code']; ?></td>
			<td><?php echo $DATAROW['item_description']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['uom']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['quantity']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['wh_quantity']; ?></td>
			<td style="text-align:center;padding:0 !important;position:relative !important">
				<input id="actual_qty<?php echo $x; ?>" <?php echo $act_qty; ?> class="inptactqty" type="text" value="<?php echo $DATAROW['branch_received']; ?>">
			</td>
			<!--td style="padding: 0 !important;text-align:center">
			<?php if($item_receipt == 1) { ?>
				<i id="recyes<?php echo $x; ?>" class="fa-solid fa-circle-check item-received color-green"></i>
			<?php } else { ?>
				<i id="recno<?php echo $x; ?>" class="fa-regular fa-circle item-received-not" onclick="receiptThis('<?php echo $rowid; ?>','<?php echo $x; ?>')"></i>
			<?php } ?>
			</td-->
		</tr>
<?php } ?>
		<tr>
			<td colspan="9" style="padding:5px;white-space:normal">
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
					<button class="btn btn-danger btn-thin btn-sm button" onclick="Check_Accesss('<?php echo $control_no; ?>','p_approver',approveReview)">Approve?</button>
				<?php } else { echo $function->GetChecked($control_no,"checked_by",$db); } ?>
			</td>		
			<td style="width:5%">&nbsp;</td>
			<td style="width:30%;height:30px;border-bottom: 1px solid #232323">
				<?php if($function->GetChecked($control_no,"approved",$db) == NULL) { ?>
					<button class="btn btn-primary btn-thin btn-sm button" onclick="Check_Accesss('<?php echo $control_no; ?>','p_approver',aproveOrder)">Approve?</button>
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
<?php if($function->GetOrderStatus($mrs_no,"order_transit",$db) == 1) { 
	 if($function->GetOrderStatus($mrs_no,"status",$db) == 'Closed') { $rcvdel = 'disabled'; } else { $rcvdel = ''; }
?>
<div class="mrs-wrappers" style="margin:0 auto;margin-top:10px;text-align:center">
	You have received the deliveries last <?php echo date("F d, Y @ h:i A", strtotime($function->GetOrderStatus($mrs_no,"order_delivered_date",$db))); ?>
	<div id="recresults"></div>
</div>
<?php } ?>
<script>
function receiptThis(rowid,eid)
{
	var module = '<?php echo MODULE_NAME; ?>';
	$('#recno' + eid).removeClass("fa-regular fa-circle item-received-not");
	$('#recno' + eid).addClass("fa-solid fa-circle-check item-received color-green");
	var actual_qty = $('#actual_qty' + eid).val()
	var mode = 'itemreceiptsave';
	$.post("./Modules/" + module + "/actions/actions.php", { mode: mode, rowid: rowid, actual_qty: actual_qty },
	function(data) {		
		$('#recresults').html(data);
	});
}
</script>
<script src="../Modules/<?php echo MODULE_NAME; ?>/scripts/script.js"></script>
<?php mysqli_close($db); ?>
