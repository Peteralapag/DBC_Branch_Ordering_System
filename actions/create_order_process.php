<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
define("MODULE_NAME", "DBC_Branch_Ordering_System");
require_once($_SERVER['DOCUMENT_ROOT']."/Modules/".MODULE_NAME."/class/Class.functions.php");
$function = new FDSFunctions;
$year = date("Y-");
if(isset($_POST['mode']))
{
	$mode = $_POST['mode'];
} else {
	print_r('
		<script>
			app_alert("Warning"," The Mode you are trying to pass does not exist","warning","Ok","","no");
		</script>
	');
	exit();
}
$editid = $_POST['editid'];
$branch = $_POST['branch'];
$control_no = $_POST['control_no'];
$item_code = $_POST['item_code'];
$item_description = $_POST['item_description'];
$uom = $_POST['uom'];
$quantity = $_POST['quantity'];
$ending = $_POST['ending'];
$inv_ending_uom = $_POST['inv_ending_uom'];

$app_user = $_SESSION['dbc_branch_username'];
$date_user = date("Y-m-d H:i:s");

$trans_date = $function->GetOrderStatus($control_no,'trans_date',$db);


if($mode == 'saveorder')
{
/*	if($function->GetItemStock($item_code,$db) <= 0)
	{
		print_r('
			<script>
				swal("Warning", "Sorry the '.$item_description.' is out of stock.", "warning");
			</script>
		');
		exit();
	}
	elseif($function->GetItemStock($item_code,$db) < $quantity)
	{
		print_r('
			<script>
				swal("Warning", "Sorry your Quantity is higher than the current stock.", "warning");
			</script>
		');
		exit();
	}
*/ 
	$query = "SELECT * FROM dbc_branch_order WHERE item_code='$item_code' AND branch='$branch' AND control_no='$control_no'";
	$checkRes = mysqli_query($db, $query);    
    if ( $checkRes->num_rows === 0 ) 
    {
    	$column = "`branch`,`control_no`,`item_code`,`item_description`,`uom`,`quantity`,`created_by`,`trans_date`,`date_created`,`status`,`inv_ending`,`inv_ending_uom`";
    	$insert = "'$branch','$control_no','$item_code','$item_description','$uom','$quantity','$app_user','$trans_date','$date_user','Approval','$ending','$inv_ending_uom'";
		$queryInsert = "INSERT INTO dbc_branch_order ($column)";
		$queryInsert .= "VALUES($insert)";
		if ($db->query($queryInsert) === TRUE)
		{
			print_r('
				<script>
					var controlno = "'.$control_no.'";
					swal("Success", "Item has been successfully added", "success");
					load_order_form(controlno);
					get_input_form(controlno);
				</script>
			');
		} else {
			print_r('
				<script>
					swal("Warning", "'.$db->error.'", "warning");
				</script>
			');
		}	
	} else {
		print_r('
			<script>
				swal("Warning", "Item name '.$item_description.' is already exists.", "warning");
			</script>
		');
	}
	mysqli_close($db);	
}
if($mode == 'updateorder')
{
	$update = "item_code='$item_code',item_description='$item_description',uom='$uom',quantity='$quantity',updated_by='$app_user',date_updated='$date_user',inv_ending='$ending',inv_ending_uom='$inv_ending_uom'";
	$queryDataUpdate = "UPDATE dbc_branch_order SET $update WHERE id='$editid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		print_r('
			<script>
				var controlno = "'.$control_no.'";
				swal("Success", "Order has been successfully updated", "success");
				load_order_form(controlno);
				get_input_form(controlno);
			</script>
		');		
	} else {
		print_r('
			<script>
				swal("Warning", "'.$db->error.'", "warning");
			</script>
		');
	}
	mysqli_close($db);
}
?>