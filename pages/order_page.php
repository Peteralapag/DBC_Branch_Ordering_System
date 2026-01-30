<?php
session_start();
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
require $_SERVER['DOCUMENT_ROOT']."/Modules/DBC_Branch_Ordering_System/class/Class.functions.php";
$function = new FDSFunctions;
define("MODULE_NAME", "DBC_Branch_Ordering_System");
$controlno = $_POST['controlno'];
$form_type = $_POST['form_type'];
$creator = $_SESSION['dbc_branch_appnameuser'];
$orderdate = $_POST['orderdate'];
$branch = $_POST['branch'];
$creatego = $function->getOrderCreator($creator,$controlno,$db);
?>

<div class="container mt-2">
	
	<table class="table table-bordered">
		
		<thead class="table-dark">
		
			<tr>
				<th colspan="3">Branch : &nbsp;<?php echo $branch?></th>
				<th colspan="2">Control No.:&nbsp;<span style="color:red"><?php echo $controlno?></span></th>
				<th colspan="2">Date: &nbsp;<?php echo $orderdate?></th>
			</tr>
			<tr>
				<th>#</th>
				<th>ITEM DESCRIPTION</th>
				<th>ITEM CODE</th>
				<th>CATEGORY</th>
				<th>UOM</th>
				<th style="width:150px">QUANTITY</th>
				<th style="width:150px">INVENTORY ENDING</th>
			</tr>
		</thead>
		<tbody>
		
		    <?php
		    	
		    	$query = "SELECT id, item_code, item_description, category, uom FROM dbc_itemlist WHERE active=1 ORDER BY ordered ASC";
				$result = $db->query($query);
				$i = 1;
				if ($result->num_rows > 0) {
				  while($row = $result->fetch_assoc()) {
				  	$rowid = $row['id'];
				  	$item = $row['item_description'];
				  	$itemcode = $row['item_code'];
				  	$category = $row['category'];
				  	$uom = $row['uom'];
				  	$quantity = $function->branchorderopengetmodulevalue('quantity',$branch,$controlno,$itemcode,$db);
					$invending = $function->branchorderopengetmodulevalue('inv_ending',$branch,$controlno,$itemcode,$db);
					
					$backColorStat = $quantity ? '#dcf3e3' : '';					

			?>
				    <tr id="columnStatus<?php echo $rowid?>" style="background:<?php echo $backColorStat?>">
					    <td><?php echo $i?></td>
					    <td id="item<?php echo $rowid?>"><?php echo $item?></td>
					    <td id="itemcode<?php echo $rowid?>"><?php echo $itemcode?></td>
					    <td id="category<?php echo $rowid?>"><?php echo $category?></td>
					    <td id="uom<?php echo $rowid?>"><?php echo $uom?></td>
					    <td><input type="number" id="qty<?php echo $rowid?>" class="form-control form-control-sm" min="0" step="1" onchange="savingthisvalue('<?php echo $rowid?>', '<?php echo $itemcode?>')" autocomplete="off" value="<?php echo $quantity?>"></td>
						<td><input type="number" id="invending<?php echo $rowid?>" class="form-control form-control-sm" min="0" step="1" onchange="savingthisvalue('<?php echo $rowid?>', '<?php echo $itemcode?>')" autocomplete="off" value="<?php echo $invending?>"></td>
				    </tr>
			<?php
				    $i++;
				  }
			?>
			
				<td colspan="7">
					<textarea id="remarks<?php echo $controlno?>" class="form-control form-control-sm" placeholder="Remarks Optional" onchange="savingthisvalueremarks('<?php echo $controlno?>')"></textarea>
				</td>

			
			<?php
				} else {
				  echo "<tr><td colspan='7'>No items found</td></tr>";
				}        
		    ?>
		
		</tbody>
	</table>
	
	<div style="float:right">
		<button class="btn btn-primary btn-sm" onclick="submitOrder('<?php echo $controlno; ?>')"><i class="fa fa-bookmark" aria-hidden="true"></i> &nbsp; Submit Order</button>
		<br><br>
	</div>
</div>
<div id="orderpageresult"></div>
<script>

function submitOrder(controlno)
{
	var hasOrder = false; 
    $("input[id^='qty']").each(function() {
        if ($(this).val() > 0) {
            hasOrder = true;
            return false;
        }
    });
    if (!hasOrder) {
        app_alert("System Message", "You cannot submit an order without any items.", "warning", "Ok", "order", "focus");
        return;
    }
	
	app_confirm("Submit Order","Are you sure to finish and submit your order?","warning","submitOrderYes",controlno,"orange")
}
function submitOrderYes(controlno)
{
	var mode = 'submitorder';
	var transdateget = '<?php echo $orderdate?>';
		
	rms_reloaderOn("Submitting Order...");
	setTimeout(function()
	{
		$.post("./Modules/<?php echo MODULE_NAME; ?>/actions/actions.php", { mode: mode, control_no: controlno, transdateget: transdateget },
		function(data) {		
			$('#orderpageresult').html(data);
			rms_reloaderOff();
		});
	},1000);
}


function savingthisvalueremarks(controlno){
	
	var mode = 'saveitemorderremarks';
	var module = '<?php echo MODULE_NAME; ?>';
	var branch = '<?php echo $branch?>';
	var remarks = $('#remarks' + controlno).val();	
		
	rms_reloaderOn("Loading...");
	
	$.post("./Modules/" + module + "/actions/actions.php", { mode: mode, branch: branch, remarks: remarks, controlno: controlno },
	function(data) {		
		$('#orderpageresult').html(data);
		rms_reloaderOff();
	});


}




function savingthisvalue(rowid, itemcode) {
    var mode = 'saveitemorderqty';
    var module = '<?php echo MODULE_NAME; ?>';
    
    var branch = '<?php echo $branch; ?>';
    var controlno = '<?php echo $controlno; ?>';
    var itemcode = $('#itemcode' + rowid).text();
    var item = $('#item' + rowid).text();
    var uom = $('#uom' + rowid).text();
    var qty = $('#qty' + rowid).val();
    var transdate = '<?php echo $orderdate; ?>';
    var invending = $('#invending' + rowid).val() || 0;
    

    let numericQuantity = Number(qty);

    // Minimum order validation for FGC - Jelly Roll
    if (itemcode === '01688') {
    	var inputField = document.getElementById('qty'+ rowid);
        const allowedQuantities = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150, 160, 170, 180, 190, 200];
        if (!allowedQuantities.includes(numericQuantity)) {
            app_alert("System Message", "For FGC Jelly Roll, it should be ordered in multiples of 10.", "warning", "Ok", "quantity", "focus");
			qty = '';
			inputField.style.border = '2px solid red';
        } else {
        	inputField.style.border = '';
		}  
    }

    // Minimum order validation for FGS1 - Banana Cake
    if (itemcode === '01621') {
    	var inputField = document.getElementById('qty'+ rowid);
        const allowedQuantities = [20, 40, 60, 80, 100, 120, 140, 160, 180, 200];
        if (!allowedQuantities.includes(numericQuantity)) {
            app_alert("System Message", "For FGS1 - Banana Cake, it should be ordered in multiples of 20.", "warning", "Ok", "quantity", "focus");
            qty = '';
			inputField.style.border = '2px solid red';
        } else {
        	inputField.style.border = '';
        }
    }
    
    // Minimum order validation for FGS1 - Pineapple Pie
    if (itemcode === '01649') {
    	var inputField = document.getElementById('qty'+ rowid);
        const allowedQuantities = [20, 40, 60, 80, 100, 120, 140, 160, 180, 200];
        if (!allowedQuantities.includes(numericQuantity)) {
            app_alert("System Message", "For FGS1 - Pineapple Pie, it should be ordered in multiples of 20.", "warning", "Ok", "quantity", "focus");
            qty = '';
			inputField.style.border = '2px solid red';
        } else {
        	inputField.style.border = '';
        }
    }
	
	rms_reloaderOn("Loading...");
    $.post("./Modules/" + module + "/actions/actions.php", {
        mode: mode,
        branch: branch,
        controlno: controlno,
        itemcode: itemcode,
        item: item,
        uom: uom,
        qty: qty,
        transdate: transdate,
        invending: invending
    }, function(data) {		
        $('#orderpageresult').html(data);
        columnStatus(rowid, itemcode, qty);
        rms_reloaderOff();
    });
}




function columnStatus(rowid,itemcode,qty){
	
    if (qty !== '' && !isNaN(qty) && Number(qty) >= 0) {
        $('#columnStatus' + rowid).css('background', '#dcf3e3');
    } else {
        $('#columnStatus' + rowid).css('background', '');
        $('#invending' + rowid).val('');
    }
    	
}
</script>
