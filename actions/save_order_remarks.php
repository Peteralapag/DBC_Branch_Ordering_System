<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
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
$branch = $_POST['branch'];
$control_no = $_POST['control_no'];
$remarks = $_POST['remarks'];
if($mode == 'saveremarks')
{
	
	$query = "SELECT * FROM dbc_branch_order_remarks WHERE branch='$branch' AND control_no='$control_no'";
	$checkRes = mysqli_query($db, $query);    
    if ( $checkRes->num_rows === 0 ) 
    {
    	$column = "`branch`,`control_no`,`remarks`";
    	$insert = "'$branch','$control_no','$remarks'";
		$queryInsert = "INSERT INTO dbc_branch_order_remarks ($column)";
		$queryInsert .= "VALUES($insert)";
		if ($db->query($queryInsert) === TRUE)
		{
			print_r('
				<script>
					var controlno = "'.$control_no.'";
					swal("Success", "Remarks has been successfully added", "success");
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
				swal("Warning", "Remarks is already exists.", "warning");
			</script>
		');
	}
	mysqli_close($db);
}
if($mode == 'updateremarks')
{
	$update = "remarks='$remarks'";
	$queryDataUpdate = "UPDATE dbc_branch_order_remarks SET $update WHERE branch='$branch' AND control_no='$control_no'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		print_r('
			<script>
				var controlno = "'.$control_no.'";
				swal("Success", "Remarks has been successfully updated", "success");
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
