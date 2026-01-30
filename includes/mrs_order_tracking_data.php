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
.tabletas th {border: 1px solid #232323;font-size:11px;font-weight: 600;padding:2px;text-align:center;}
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
	<div class="mrs-wrappers" style="margin:0 auto;margin-top:10px">		
		<table style="width: 100%">
			<tr>
				<th style="width:65px">Driver: </th>
				<td style="width:5px;">&nbsp;</td>
				<td><?php echo $function->GetOrderStatus($mrs_no,"delivery_driver",$db); ?></td>
				<td style="width:100px">&nbsp;</td>
				<th style="width:100px">Plate Number: </th>
				<td style="width:5px;">&nbsp;</td>
				<td style="width:100px;text-align:center"><?php echo $function->GetOrderStatus($mrs_no,"plate_number",$db); ?></td>
			</tr>
		</table>		
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
				<td style="width:170px"><span><?php echo $function->GetOrderStatus($mrs_no,"order_delivered_date",$db); ?></span></td>
			</tr>
		</table>
		
	</div>
</div>
<!-- ############################################################ -->
<div class="mrs-wrappers" style="margin:0 auto">	
	<table style="width: 100%;font-family:sans-serif" class="table">
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
			<th style="width:75px;">Inv Ending</th>
			<?php if($function->GetOrderStatus($mrs_no,"order_transit",$db) == 1) { ?>
				<th style="width:75px;">Brch Rcvd</th>
				<th style="width:75px;">Action</th>
			<?php } ?>
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
			$editid = $DATAROW['id'];
			$remarks = $DATAROW['remarks'];
			
			$controlno = $DATAROW['control_no'];
			
			$branchreceived = $DATAROW['branch_received'];
			$branchreceivedstatus = $DATAROW['branch_received_status'];
			
			$remarks = $function->shortenText($remarks,20);
			
			$actionButton = '';
			$branchrcvdstyle = '';	
			$brnchrcvdeditable = '';
			if($branchreceivedstatus == '0'){
				$brnchrcvdeditable = 'contenteditable="true"';
				$branchrcvdstyle = 'background-color:#f7e9d5';
			}
			
?>		
		<tr>
			<td style="text-align:center"><?php echo $x; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['item_code']; ?></td>
			<td><?php echo $DATAROW['item_description']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['uom']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['quantity']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['actual_quantity']; ?></td>
			<td style="text-align:center"><?php echo $DATAROW['inv_ending']; ?></td>
			
			<?php if($function->GetOrderStatus($mrs_no,"order_transit",$db) == 1) { ?>
			
				<td id="brachrcvd<?php echo $x?>" style="text-align:center; <?php echo $branchrcvdstyle?>" <?php echo $brnchrcvdeditable?>><?php echo $branchreceived?></td>
				
				<td style="text-align:center">
					<?php 
						if($branchreceivedstatus == '0'){ ?>
							<button class="btn btn-sm btn-success" onclick="savemyreceived('<?php echo $x?>','<?php echo $editid?>','<?php echo $mrs_no?>')">Receive</button>
					<?php
						} else { ?>
							<span><i class="fa fa-check" aria-hidden="true"></i></span>
					<?php
						}
					?>
				</td>
			<?php } ?>
		</tr>
<?php } ?>
		<tr>
			<td colspan="10" style="padding:5px;white-space:normal">
				<span style="font-weight:600">REMARKS:</span> <?php echo $function->getOrderRemarks($mrs_no,$db); ?>
			</td>
		</tr>
<?php } else { ?>		
		<tr>
			<td colspan="10" style="text-align:center">No Items</td>
		</tr>
<?php } ?>	
	</table>
</div>


<?php if($function->GetOrderStatus($mrs_no,"order_transit",$db) == 1) { 
	 if($function->GetOrderStatus($mrs_no,"status",$db) == 'Closed') { $rcvdel = 'disabled'; } else { $rcvdel = ''; }
?>
<div class="mrs-wrappers" style="margin:0 auto;margin-top:10px;text-align:center">
	<button <?php echo $rcvdel; ?> class="btn btn-primary" onclick="receivedDelivery('<?php echo $control_no; ?>')">Delivery Received</button>
</div>
<?php } mysqli_close($db); ?>
<div class="results"></div>
<script>

function orderTracking(controlno)
{
	var module = '<?php echo MODULE_NAME; ?>';
	$.post("./Modules/" + module + "/includes/mrs_order_tracking_data.php", { control_no: controlno },
	function(data) {		
		$('#smnavdata').html(data);
	});	
}

function savemyreceived(params,rowid,ctrlno){
	
	var mode = 'savemybranchreceived';
	var brachrcvd = $('#brachrcvd'+params).text();
	
	if(brachrcvd == ''){
		swal("System Message", "Please complete the section indicating the amount received.", "warning");
		return false;
	}
	
	rms_reloaderOn("Saving Receiving Delivery...");
	setTimeout(function()
	{
		$.post("./Modules/<?php echo MODULE_NAME; ?>/actions/actions.php", { mode: mode, brachrcvd: brachrcvd, rowid: rowid, ctrlno: ctrlno },
		function(data) {		
			$('.results').html(data);
			rms_reloaderOff();
		});
	},1000);

}

function receivedDelivery(controlno)
{
	var mode = 'recieveddelivery';
	rms_reloaderOn("Receiving Delivery...");
	setTimeout(function()
	{
		$.post("./Modules/<?php echo MODULE_NAME; ?>/actions/actions.php", { mode: mode, control_no: controlno },
		function(data) {		
			$('.results').html(data);
			rms_reloaderOff();
		});
	},1000);
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
$(function()
{
	
});
</script>
<script src="../Modules/<?php echo MODULE_NAME; ?>/scripts/script.js"></script>
