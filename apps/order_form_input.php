<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
define("MODULE_NAME", "DBC_Branch_Ordering_System");
require_once($_SERVER['DOCUMENT_ROOT']."/Modules/".MODULE_NAME."/class/Class.functions.php");
$control_no = $_POST['control_no'];
$branch = $_SESSION['dbc_branch_branch'];
$function = new FDSFunctions;
$creator = $_SESSION['dbc_branch_appnameuser'];
//$formdisabled = $_POST['creatego'];
$formdisabled = $function->getOrderCreator($creator,$control_no,$db);
$sqlQuery = "SELECT * FROM dbc_order_request WHERE control_no='$control_no'";
$results = mysqli_query($db, $sqlQuery);    
if ( $results->num_rows > 0 ) 
{
	$i=0;
	while($ORDERROW = mysqli_fetch_array($results))  
	{
		$branch = $ORDERROW['branch'];
		$control_no = $ORDERROW['control_no'];
		$mrs_no = $ORDERROW['control_no'];
		$recipient = $ORDERROW['recipient'];
	//	$item_location = $ORDERROW['item_location'];
		$form_type = $ORDERROW['form_type'];
		if($form_type = 'MRS')
		{
			$form_text = 'MRS';
		}
		else if($form_type = 'POF')
		{
			$form_text = 'POF';
		}

	}
} else {
	echo "";
}
if(isset($_POST['editid']) && isset($_POST['params']))
{
	$editid = $_POST['editid'];
	$sqlQueryEdit = "SELECT * FROM dbc_branch_order WHERE id='$editid'";
	$editResults = mysqli_query($db, $sqlQueryEdit);    
	if ( $editResults->num_rows > 0 ) 
	{
		$i=0;
		while($EDITROW = mysqli_fetch_array($editResults))  
		{
			$branch = $EDITROW['branch'];
			$mrs_no = $EDITROW['control_no'];
			$uom = $EDITROW['uom'];
			$item_code = $EDITROW['item_code'];
			$item_description = $EDITROW['item_description'];
			$unit_price = '';
			$quantity = $EDITROW['quantity'];
			$inv_ending = $EDITROW['inv_ending'];
		}
	}
	
	$sqlQueryEditRemarks = "SELECT * FROM dbc_branch_order_remarks WHERE control_no='$mrs_no'";
	$remarksEditResults = mysqli_query($db, $sqlQueryEditRemarks);    
	if ( $remarksEditResults->num_rows > 0 ) 
	{
		$i=0;
		while($REMARKSROW = mysqli_fetch_array($remarksEditResults))  
		{
			$remarks = $REMARKSROW['remarks'];			
			$update_r = 1;
		}
	} else {
		$remarks = "";
		$update_r = 0;
	}
} else {
	$editid = "";
	$item_description = "";
	$item_code = "";
	$unit_price = "";
	$quantity =  "";
	$remarks =  "";
	$inv_ending = "";
}
?>
<style>
.item-dd-wrapper {position:absolute;display: none;width:100%;padding:5px;height:300px;font-size:11px;background:#fff;border-radius:5px;box-shadow: 0 0 10px rgba(0, 0, 0, .4);}
.search-data {max-height: 222px;overflow: auto;}
.searchbtn {position:absolute;text-align:right;padding:5px 0px 5px 0px;bottom:0;right:5px;width:100%;}
.searchlist {list-style-type: none;margin:0;padding:0;}
.searchlist li {padding:5px;border-bottom:1px solid #aeaeae;}
.addremarks {display: none;}
#remarks {
	height:60px !important
}
</style>
<div style="padding:10px">
	<table style="width: 100%">
		<tr>
			<th>BRANCH</th>
		</tr>
		<tr>
			<td>
				<input id="editid" type="hidden" value="<?php echo $editid; ?>">
				<input id="controlno" type="hidden" value="<?php echo $control_no; ?>">
				<input id="branchname" type="text" class="form-control form-control-sm" value="<?php echo $branch; ?>" disabled>	
			</td>
		</tr>
		<tr>
			<td style="height:5px;"></td>
		</tr>
		<tr>
			<th><?php echo $form_text; ?> No.</th>
		</tr>
		<tr>
			<td><input id="control_no" type="text" class="form-control form-control-sm" value="<?php echo $mrs_no; ?>" disabled></td>
		</tr>
		<tr>
			<td style="height:5px;"></td>
		</tr>
		<tr>
			<th>RECIPIENT</th>
		</tr>
		<tr>
			<td><input id="recipient" type="text" class="form-control form-control-sm" value="<?php echo $recipient; ?>" disabled></td>
		</tr>
		<tr>
			<td style="height:5px;"></td>
		</tr>
		<tr>
			<th>ITEM DESCRIPTION</th>
		</tr>
		<tr>
			<td style="position:relative">
				<input id="item_description" type="text" class="form-control form-control-sm searchinput" placeholder="Select Item here" value="<?php echo $item_description; ?>" autocomplete="off" readonly>
				<div class="item-dd-wrapper" id="itemddwrapper">
					<input id="searchinput" type="text" class="form-control form-control-sm" placeholder="Search Item" autocomplete="offduty">
					<div class="search-data" id="searchdata"></div>
					<div class="searchbtn"><button class="btn btn-danger btn-sm" onclick="closeItemSearch()"><i class="fa-solid fa-xmark"></i> Close</button></div>
				</div>
			</td>
		</tr>
		<tr>
			<th>ITEM CODE</th>
		</tr>
		<tr>
			<td>
				<input id="item_code" type="text" class="form-control form-control-sm" value="<?php echo $item_code; ?>" disabled>	
			</td>
		</tr>
		<tr>
			<td style="height:5px;"></td>
		</tr>
		<tr>
			<th>UNITS OF MEASURE</th>
		</tr>
		<tr>
			<td>
				<select id="uom" class="form-control form-control-sm" disabled>
					<?php echo $function->GetUOM($uom,$db)?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="height:5px;"></td>
		</tr>
	<?php if(!isset($_POST['params'])) { ?>
		<tr>
			<th>UNIT PRICE</th>
		</tr>
		<tr>
			<td><input id="unit_price" type="text" class="form-control form-control-sm" disabled></td>
		</tr>
		<tr>
			<td style="height:5px;"></td>
		</tr>
	<?php } ?>
		<tr>
			<th>QUANTITY</th>
		</tr>
		<tr>
			<td><input id="quantity" type="number" class="form-control form-control-sm" value="<?php echo $quantity; ?>"></td>
		</tr>
		<tr>
			<td style="height:20px;"></td>
		</tr>
		<tr>
			<th>INVENTORY ENDING</th>
		</tr>
		<tr>
			<td>				
				<table style="width:100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
					<tr>
						<td style="text-align:center;font-size:12px;width:60%">WEIGHT/QTY</td>
						<td style="width:5px"></td>
						<td style="text-align:center;font-size:12px">UOM</td>
					</tr>
					<tr>
						<td><input id="inv_ending" type="number" class="form-control form-control-sm" value="<?php echo $inv_ending; ?>"></td>
						<td></td>
						<td><select id="inv_ending_uom" class="form-control form-control-sm" disabled><?php echo $function->GetUOM('Piece(s)',$db)?></select></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="height:20px;"></td>
		</tr>
		<tr>
			<td style="text-align:right" id="formbtn">
		<?php if(!isset($_POST['params'])) { ?>
				<button class="btn btn-success btn-sm" onclick="SaveOrder('add')">Save Order</button>
				<button class="btn btn-warning btn-sm color-white" onclick="addRemarks()">Remarks</button>
		<?php } else { ?>
				<button class="btn btn-info btn-sm color-white" onclick="SaveOrder('edit')">Update</button>
				<button class="btn btn-warning btn-sm color-white" onclick="addRemarks()">Remarks</button>
				<button class="btn btn-success btn-sm" onclick="get_input_form('<?php echo $control_no; ?>');">Cancel</button>
				<button class="btn btn-danger btn-sm" title="Delete" onclick="deleteItem('<?php echo $editid; ?>')"><i class="fa fa-trash"></i></button>
		<?php } ?>
			</td>
		</tr>
		<tr>
			<td style="height:20px;"></td>
		</tr>
		<tr class="addremarks">
			<th>REMARKS</th>
		</tr>
		<tr class="addremarks">
			<td>
				<textarea id="remarks" class="form-control form-control-sm" placeholder="Optional"><?php echo $remarks; ?></textarea>
			</td>
		</tr>
		<tr class="addremarks">
			<td style="height:5px;"></td>
		</tr>
		<tr class="addremarks">
			<td style="text-align:right">
			<?php if(!isset($_POST['params'])) { ?>
				<button class="btn btn-primary btn-sm" onclick="saveRemarks('add')">Save Remarks</button>
			<?php } else { if($update_r == 1) { ?>
				<button class="btn btn-primary btn-sm" onclick="saveRemarks('edit')">Update Remarks</button>
			<?php } else { ?>
				<button class="btn btn-primary btn-sm" onclick="saveRemarks('add')">Add Remarks</button>
			<?php } } ?>
			</td>
		</tr>
	</table>
</div>
<script>
function deleteItem(editid)
{
	var module = '<?php echo MODULE_NAME; ?>';
	var mode = "deleteorderitem";
	var control_no = $('#control_no').val();
	rms_reloaderOn("Deleting...");
	setTimeout(function()
	{
		$.post("./Modules/" + module + "/actions/actions.php", { mode: mode, editid: editid, control_no: control_no },
		function(data) {		
			$('.results').html(data);
			rms_reloaderOff();
		});
	},500);
}
function SaveOrder(params)
{
	var module = '<?php echo MODULE_NAME; ?>';
	var editid = $('#editid').val();
	var rowid = $('#rowid').val();
	var branch = $('#branchname').val();
	var control_no = $('#control_no').val();
	var item_code = $('#item_code').val();
	var item_description = $('#item_description').val();
	var uom = $('#uom').val();
	var unit_price = $('#unit_price').val();
	var quantity = $('#quantity').val();
	var ending = $('#inv_ending').val();
	var inv_ending_uom = $('#inv_ending_uom').val();
	
	if(item_description === '')
	{
		app_alert("Item Description","Please select Item","warning","Ok","item_description","focus");
		return false;
	}	
	if(uom === '')
	{
		app_alert("Unit of Measures","Units of Measures is missing please check your itemlist or report to the system Administrator","warning","Ok","uom","focus");
		return false;
	}
	if(item_code === '')
	{
		app_alert("Item Code","Item Code is missing please check your itemlist or report to the system Administrator","warning","Ok","uom","focus");
		return false;
	}
	if(quantity <= 0 || quantity === '')
	{
		app_alert("Order Quantity","Please enter Quantity","warning","Ok","quantity","focus");
		return false;
	}
	if(ending === '')
	{
		app_alert("Inventory Ending","Please enter Quantity of your Inventory Ending","warning","Ok","inv_ending","focus");
		return false;
	}
	if(inv_ending_uom === '')
	{
		app_alert("Inventory Ending UOM","Please select Units of Measure of your Inventory Ending","warning","Ok","inv_ending_uom","focus");
		return false;
	}
	////// minimum order FGC - Jelly Roll
	if(item_code == '01688'){

		let numericQuantity = Number(quantity);	
		const allowedQuantities = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150, 160, 170, 180, 190, 200];
	    if (!allowedQuantities.includes(numericQuantity)) {
	        app_alert("System Message", "For FGC Jelly Roll, It should be ordered in multiples of 10, such as 10, 20, 30, 40, and so on.", "warning", "Ok", "quantity", "focus");
	        return false;
	    }   
	}
	
	////// minimum order FGS1 - Banana Cake
	if(item_code == '01621'){
		
		let numericQuantity = Number(quantity); 	
		const allowedQuantities = [20, 40, 60, 80, 100, 120, 140, 160, 180, 200];
	    if (!allowedQuantities.includes(numericQuantity)) {
	        app_alert("System Message", "For FGC Jelly Roll, It should be ordered in multiples of 10, such as 10, 20, 30, 40, and so on.", "warning", "Ok", "quantity", "focus");
	        return false;
	    }
	    
	}
	
	////// minimum order FGS1 - Pineapple Pie
	if(item_code == '01649'){
		
		let numericQuantity = Number(quantity);	
		const allowedQuantities = [20, 40, 60, 80, 100, 120, 140, 160, 180, 200];
	    if (!allowedQuantities.includes(numericQuantity)) {
	        app_alert("System Message", "For FGS1 - Pineapple Pie, It should be ordered in multiples of 10, such as 10, 20, 30, 40, and so on.", "warning", "Ok", "quantity", "focus");
	        return false;
	    }

		
		
		
	}

	
	if(params == 'edit')
	{
		var mode = "updateorder";
		rms_reloaderOn("Updating...");
	}
	if(params == 'add')
	{
		var mode = "saveorder";
		rms_reloaderOn("Saving...")
	}
	setTimeout(function()
	{
		$.post("./Modules/" + module + "/actions/create_order_process.php",
		{
			mode: mode,
			editid: editid,
			rowid: rowid,
			branch: branch,
			control_no: control_no,
			item_code: item_code,
			item_description: item_description,
			uom: uom,
			unit_price: unit_price,
			quantity: quantity,
			ending: ending,
			inv_ending_uom: inv_ending_uom
		},
		function(data) {		
			$('.results').html(data);
			rms_reloaderOff();
		});
	},1000);
}
function addRemarks()
{
	if($('.addremarks').is(":visible"))
	{
		$('.addremarks').hide();
	} else {
		$('.addremarks').show();
	}
}
function saveRemarks(params)
{
	var module = '<?php echo MODULE_NAME; ?>';
	var rowid = $('#rowid').val();
	var branch = $('#branch').val();
	var control_no = $('#control_no').val();
	var remarks = $('#remarks').val();	
	if(remarks === '')
	{
		return false;
	}
	if(params == 'add')
	{
		rms_reloaderOn("Saving...");
		var mode = 'saveremarks';		
	}
	if(params == 'edit')
	{
		rms_reloaderOn("Updating...");
		var mode = 'updateremarks';		
	}
	setTimeout(function()
	{
		$.post("./Modules/" + module + "/actions/save_order_remarks.php",
		{
			mode: mode,
			rowid: rowid,
			branch: branch,
			control_no: control_no,
			remarks: remarks,
		},
		function(data) {		
			$('.results').html(data);
			rms_reloaderOff();
		});
	},500);
}
$(function()
{
	var form = '<?php echo $formdisabled ?>';
	if(form == 0)
	{
		$("#formbtn :input").prop("disabled", true);
		$("#formbtn :button").prop("disabled", true);
		swal("Not Allowed", "Placing an order request on behalf of someone else is not allowed", "warning");
	}
	var module = '<?php echo MODULE_NAME; ?>';
	$('#searchinput').keyup(function()
	{
		var mode = "recevingstocks";
		var search = $('#searchinput').val();
		var recipient = $('#recipient').val();
		$.post("./Modules/" + module + "/actions/actions.php", { mode: mode, search: search, recipient: recipient },
		function(data) {		
			$('#searchdata').html(data);
		});
	});
	$('#item_description').focus(function()
	{
		var mode = "recevingstocks";
		var recipient = $('#recipient').val();
		$.post("./Modules/" + module + "/actions/actions.php", { mode: mode, recipient: recipient },
		function(data) {		
			$('#searchdata').html(data);
			$('#itemddwrapper').slideDown();
			document.getElementById('searchinput').focus();
		});
	});
});
function setSearch(item,itemcode,unitprice,uom)
{
	$('#item_description').val(item);
	$('#item_code').val(itemcode);
	$('#unit_price').val(unitprice);
	$('#itemddwrapper').slideUp();
	$('#quantity').prop("disabled", false);
	$("#uom option[value='" + uom +"']").attr('selected', 'selected');
	$("#uom").val(uom);
	$("#inv_ending_uom").val(uom);
}
function orderDetails(rowid)
{
	$.post("./Modules/" + module + "/includes/request_details_data.php", { rowid: rowid  },
	function(data) {		
		$('#smnavdata').html(data);
	});	
}
function closeItemSearch()
{
	$('#itemddwrapper').slideUp();
}
</script>


