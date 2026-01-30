<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
require $_SERVER['DOCUMENT_ROOT']."/Modules/DBC_Branch_Ordering_System/class/Class.functions.php";
$function = new FDSFunctions;
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
if(isset($_SESSION['dbc_branch_appnameuser']))
{
	$app_user = $_SESSION['dbc_branch_appnameuser'];
}
$date_user = date("Y-m-d H:i:s");
$trans_date = date("Y-m-d");



if($mode == 'saveitemorderremarks'){
	
	
	
	
	$branch = $_POST['branch'];
    $controlno = $_POST['controlno'];
    $remarks = $_POST['remarks'];

    $query = "SELECT * FROM dbc_branch_order_remarks WHERE control_no = ? AND branch = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $controlno, $branch);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $updateQuery = "UPDATE dbc_branch_order_remarks SET remarks = ? WHERE control_no = ? AND branch = ?";

        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bind_param("sss", $remarks, $controlno, $branch);
        $updateStmt->execute();
        $updateStmt->close();

//        echo "Remarks updated successfully.";
    
    } else {

        $insertQuery = "INSERT INTO dbc_branch_order_remarks (branch, control_no, remarks) 
                        VALUES (?, ?, ?)";

        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->bind_param("sss", $branch, $controlno, $remarks);
        $insertStmt->execute();
        $insertStmt->close();

//        echo "Remarks saved successfully.";
    }

    $stmt->close();
	
	
	

}


if($mode == 'saveitemorderqty'){

	$branch = $_POST['branch'];
	$controlno = $_POST['controlno'];
	$itemcode = $_POST['itemcode'];
	$item = $_POST['item'];
	$uom = $_POST['uom'];
	$qty = $_POST['qty'];
	$transdate = $_POST['transdate'];
	$invending = $_POST['invending'];
	$app_user = $_SESSION['dbc_branch_appnameuser'];
    $date_user = date('Y-m-d H:i:s');
	$inv_ending_uom = $_POST['uom'];
	
    

	$query = "SELECT * FROM dbc_branch_order WHERE control_no = ? AND item_code = ? AND branch = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $controlno, $itemcode, $branch);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($qty == '' || $qty <= 0) {
        $deleteQuery = "DELETE FROM dbc_branch_order WHERE control_no = ? AND item_code = ? AND branch = ?";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->bind_param("sss", $controlno, $itemcode, $branch);
        $deleteStmt->execute();
        $deleteStmt->close();

//        echo "Item deleted successfully.";
    } else {
        if ($result->num_rows > 0) {
            // Update the existing record
            $updateQuery = "UPDATE dbc_branch_order 
                            SET item_code = ?, item_description = ?, uom = ?, quantity = ?, updated_by = ?, date_updated = ?, inv_ending = ?, inv_ending_uom = ? 
                            WHERE control_no = ? AND item_code = ? AND branch = ?";

            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param("sssdssdssss", $itemcode, $item, $uom, $qty, $app_user, $date_user, $invending, $inv_ending_uom, $controlno, $itemcode, $branch);
            $updateStmt->execute();
            $updateStmt->close();

//            echo "Item quantity updated successfully.";
        } else {
            // Insert a new record
            $insertQuery = "INSERT INTO dbc_branch_order (control_no, item_code, item_description, uom, quantity, trans_date, inv_ending, inv_ending_uom, branch, created_by, date_created) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $insertStmt = $db->prepare($insertQuery);
            $insertStmt->bind_param("ssssdsdssss", $controlno, $itemcode, $item, $uom, $qty, $transdate, $invending, $inv_ending_uom, $branch, $app_user, $date_user);
            $insertStmt->execute();
            $insertStmt->close();

//            echo "Item quantity saved successfully.";
        }
    }

    $stmt->close();    



  
}

if($mode == 'savemybranchreceived'){
	
	$rowid = $_POST['rowid'];
	$brachrcvd = $_POST['brachrcvd'];
	$ctrlno = $_POST['ctrlno'];
	
	$queryDataUpdate = "UPDATE dbc_branch_order SET branch_received='$brachrcvd', branch_received_status='1' WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		print_r('f
			<script>
				var ctrlno = "'.$ctrlno.'";
				swal("Success", "Saved", "success");
				orderTracking(ctrlno);
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

if($mode == 'voidorderrequest')
{
	$control_no = $_POST['control_no'];
	$queryDataUpdate = "UPDATE dbc_order_request SET status='Void' WHERE control_no='$control_no'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		print_r('
			<script>
				swal("Success", "Order request has been Void", "success");
				$("#" + sessionStorage.navfdsbos).trigger("click");
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
if($mode == 'sendingbackrequest')
{
	$control_no = $_POST['control_no'];
	$queryDataUpdate = "UPDATE dbc_order_request SET status='Open', checked=NULL, checked_by=NULL, checked_date=NULL WHERE control_no='$control_no'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		print_r('
			<script>
				swal("Success", "Order request has been sent back to Requester", "success");
				$("#" + sessionStorage.navfdsbos).trigger("click");
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
if($mode == 'getformtype')
{
	$form_type = $_POST['formType'];
	echo $function->LoadRecipient($form_type,$db);
}
if($mode == 'itemreceiptsave')
{
	$rowid = $_POST['rowid'];
	$actual_qty = $_POST['actual_qty'];
	$queryDataUpdate = "UPDATE dbc_branch_order SET item_receipt_by='$app_user', item_receipt=1, branch_received='$actual_qty', item_receipt_date='$date_user' WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
	} else {
		print_r('
			<script>
				swal("Warning", "'.$db->error.'", "warning");
			</script>
		');
	}
	mysqli_close($db);
}
if($mode == 'recieveddelivery')
{
	$control_no = $_POST['control_no'];
	
	
	if($function->branchOrderNULLCheking($control_no,$db) == '1'){
		print_r('
			<script>
				var a = "'.$control_no.'";
				orderTracking(a);	
				swal("System Message", "Please complete receive all items.", "warning");
							
			</script>
		');
		exit();
	}
	
	
	$queryDataUpdate = "UPDATE dbc_order_request SET order_accepted_by='$app_user', order_delivered=1, order_delivered_date='$date_user', status='Closed' WHERE control_no='$control_no'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		print_r('
			<script>
				swal("Success", "You have Received the Deliveries.", "success");
				$("#" + sessionStorage.navfdsbos).trigger("click");				
			</script>
		');		
	} else {
		print_r('
			<script>
				swal("Warning", "'.$db->error.'", "warning");
				$("#" + sessionStorage.navfdsbos).trigger("click");	
			</script>
		');
	}
	mysqli_close($db);
}
if($mode == 'approveorder')
{
	$control_no = $_POST['control_no'];
	$queryDataUpdate = "UPDATE dbc_order_request SET status='Submitted', approved='Approved', approved_by='$app_user', approved_date='$trans_date' WHERE control_no='$control_no'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		print_r('
			<script>
				swal("Success", "You have checked and review this Order", "success");
				orderApproval("'.$control_no.'");
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
if($mode == 'approvecheckreview')
{
	$control_no = $_POST['control_no'];
	$queryDataUpdate = "UPDATE dbc_order_request SET checked='Approved', checked_by='$app_user', checked_date='$trans_date' WHERE control_no='$control_no'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		print_r('
			<script>
				swal("Success", "You have checked and review this Order", "success");
				orderApproval("'.$control_no.'");
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
if($mode == 'submitorder')
{
	$control_no = $_POST['control_no'];
	
	$transdateget = $_POST['transdateget'];
	
	$user = $_SESSION['dbc_branch_appnameuser'];
	$dateNow = date('Y-m-d');
	$approvedby = 'System';
	
	
	if($function->dateLockChecking($transdateget,$db) == '1'){
		
		print_r('
			<script>
				swal("System Message", "DBC has enforced a lock on this date ordering '.$transdateget.'", "warning");
			</script>
		');
		exit();
	}
	
	$queryDataUpdate = "UPDATE dbc_order_request SET status='Approval',checked='Approved',date_created='$date_user',status='Submitted', approved='Approved',checked_by='$user',checked_date='$dateNow',approved_by='$approvedby',approved_date='$dateNow' WHERE control_no='$control_no'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		print_r('
			<script>
				swal("Success", "Order has been submitted for approval", "success");
				$("#" + sessionStorage.navfdsbos).trigger("click");
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
if($mode == 'deleteorderitem')
{
	$rowid = $_POST['editid'];
	$control_no = $_POST['control_no'];
	$queryDataDelete = "DELETE FROM dbc_branch_order WHERE id='$rowid' ";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				var controlno = "'.$control_no.'";
				swal("Success","Item has been removed", "success");
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
if($mode == 'displaybranch')
{
	
	if(isset($_POST['search']))
	{
		$search = $_POST['search'];
		$q = "WHERE branch LIKE '%$search%'";
	} else {
		$q = "";
	}
	$sqlQuery = "SELECT * FROM tbl_branch $q LIMIT 100";
	$results = mysqli_query($db, $sqlQuery);    
	echo '<ul class="searchlist">';
    if ( $results->num_rows > 0 ) 
    {
    	while($ITEMSROW = mysqli_fetch_array($results))  
		{
			$branch = $ITEMSROW['branch'];
			
?>
			<li onclick="setSearch('<?php echo $branch; ?>')"><?php echo $branch; ?></li>
<?php
		}
	} else {
		echo "<li>No Record.</li>";
	}
	
	mysqli_close($db);
}
if($mode == 'recevingstocks')
{
	$recipient = $_POST['recipient'];
	if(isset($_POST['search']))
	{
		$search = $_POST['search'];
		$q = "WHERE recipient='$recipient' AND active=1 AND item_description LIKE '%$search%' OR item_code LIKE '%$search%' OR qr_code LIKE '%$search%'";
	} else {
		$q = "WHERE recipient='$recipient' AND active=1";
	}
	$sqlQuery = "SELECT * FROM dbc_itemlist $q LIMIT 100";
	$results = mysqli_query($db, $sqlQuery);    
	echo '<ul class="searchlist">';
    if ( $results->num_rows > 0 ) 
    {
    	while($ITEMSROW = mysqli_fetch_array($results))  
		{
			$item_code = $ITEMSROW['item_code'];
			$item = $ITEMSROW['item_description'];
			$uom = $ITEMSROW['uom'];	
			$unitprice = $ITEMSROW['unit_price'];	
			$supplier_id = $ITEMSROW['supplier_id'];	
			
?>
			<li onclick="setSearch('<?php echo $item; ?>','<?php echo $item_code; ?>','<?php echo $unitprice; ?>','<?php echo $uom; ?>')"><?php echo $item; ?></li>
<?php
		}
	} else {
		echo "<li>No Record.</li>";
	}
	mysqli_close($db);
}
?>