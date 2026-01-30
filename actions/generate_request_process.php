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


if (!isset($_SESSION['dbc_branch_appnameuser'])) {    
    print_r('
		<script>
			swal("User Warning", "The user login has been expired", "warning");
			location.reload();
		</script>
	');
    exit();
}



//$form_type = $_POST['form_type'];
//$control_no = $_POST['mrs_no'];
$control_no = $year.$function->GetMRSNumber($db);
$trans_date = $_POST['trans_date'];
$cluster = $_SESSION['dbc_branch_cluster'];
$branch = $_POST['branch'];
$recipient = $_POST['recipient'];
$created_by = $_POST['created_by'];
$priority = $_POST['priority'];
$app_user = $_SESSION['dbc_branch_username'];
$date_user = date("Y-m-d H:i:s");
if($mode == 'newrequest')
{
	$controlno = str_replace($year, "", $control_no);	
	$queryCN = "SELECT * FROM dbc_form_numbering WHERE mrs_number='$controlno'";
	$checkCN = mysqli_query($db, $queryCN);    
    if ( $checkCN->num_rows > 0 ) 
    {
		$number  = intval($controlno);
		$number += 1;
		$new_control_no = str_pad($number, strlen($controlno), '0', STR_PAD_LEFT);
    } else {
    	$control_no_ = str_replace($year, "", $function->GetMRSNumber($db));
    	$number  = intval($control_no_);
		$number += 1;
		$new_control_no = str_pad($number, strlen($control_no_), '0', STR_PAD_LEFT);
    }
	$query = "SELECT * FROM dbc_order_request WHERE control_no='$control_no'";
	$checkRes = mysqli_query($db, $query);    
    if ( $checkRes->num_rows === 0 ) 
    {
    	$column = "`control_no`,`trans_date`,`cluster`,`branch`,`recipient`,`created_by`,`priority`,`date_created`";
    	$insert = "'$control_no','$trans_date','$cluster','$branch','$recipient','$created_by','$priority','$date_user'";
		$queryInsert = "INSERT INTO dbc_order_request ($column)";
		$queryInsert .= "VALUES($insert)";
		if ($db->query($queryInsert) === TRUE)
		{
			$queryDataUpdate = "UPDATE dbc_form_numbering SET mrs_number='$new_control_no' WHERE id=1";
			if ($db->query($queryDataUpdate) === TRUE)
			{
				print_r('
					<script>
						load_data();
						swal("Success", "Request has been successfully added", "success");
						closeModal("formmodal");
					</script>
				');
			} else {
				print_r('
					<script>
						swal("Numbering Error:", "'.$db->error.'", "warning");
					</script>
				');
			}
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
				swal("Warning", "Order with '.$control_no.' number is already exists.", "warning");
			</script>
		');
	}
	mysqli_close($db);
}
if($mode == 'updaterequest')
{

	$request_id = $_POST['rowid'];
	$status = $_POST['status'];
	

	$currentDate = date('Y-m-d');
	
	if ($currentDate > $trans_date) {
	    print_r('
			<script>
				swal("System Message", "The selected date has already passed. The process cannot continue.", "warning");
			</script>
		');
	    exit;
	}
	
	
	$update = "cluster='$cluster',branch='$branch',recipient='$recipient',trans_date='$trans_date',updated_by='$app_user',date_updated='$date_user',priority='$priority',status='$status'";
	$queryDataUpdate = "UPDATE dbc_order_request SET $update WHERE request_id='$request_id'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
	
		updateTransDate($control_no, $trans_date, $db);
		
		print_r('
			<script>
				swal("Success", "Request has been successfully updated '.$trans_date.' now'.$date_user.'", "success");
				load_data();
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

function updateTransDate($control_no, $transdate, $db){
	
    $query = "UPDATE dbc_branch_order SET trans_date = ? WHERE control_no = ?";
    
    if ($stmt = $db->prepare($query)) {

        $stmt->bind_param("ss", $transdate, $control_no);

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            echo "Error executing query: " . $stmt->error;
            return false;
        }
        
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $db->error;
        return false;
    }
		
}
?>