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
if($mode=='c10cc6b684e1417e8ffa924de1e58373')
{
	$uname = $_POST['username'];
	$pass = $_POST['password'];
	$user_app = "DBC Branch Ordering System";
	$mods = str_replace(" ", "_", $user_app);
	$username = mysqli_real_escape_string($db, $uname);
	$password = $encpass->encryptedPassword($pass,$db);
	$sqlLogin = "SELECT * FROM tbl_system_user WHERE username='$username' AND password='$password'";
	$result = mysqli_query($db, $sqlLogin);    
    if ( $result->num_rows > 0 ) 
    { 
	    while($listrow = mysqli_fetch_array($result))  
		{
			$void = $listrow['void_access'];
			$company = $listrow['company'];
			$cluster = $listrow['cluster'];
			$branch = $listrow['branch'];
			$username = $listrow['username'];
			$user_level = $listrow['level'];
			$user_role = $listrow['role'];
			$employee = $listrow['firstname']." ".$listrow['lastname'];	
			$void = $listrow['void_access'];
		}
		if($user_role != 'Administrator' AND $user_level < 80)
		{
			$checkPolicy = $functions->checkPolicy($username,$user_app,$db);
			if($checkPolicy == 0)
			{
				$cmd = '';
				$cmd .='				
					<script>
						app_alert("Access Denied","You have no access to this application","warning","Ok","","");
					</script>
				';
				print_r($cmd);
				exit();
			}
		}
		if($void == 0)
		{
			mysqli_close($db);
			$_SESSION['dbc_branch_username'] = $username;
			$_SESSION['dbc_branch_company'] = $company;
			$_SESSION['dbc_branch_branch'] = $branch;
			$_SESSION['dbc_branch_appnameuser'] = $employee;
			$_SESSION['dbc_branch_userlevel'] = $user_level;
			$_SESSION['dbc_branch_userrole'] = $user_role;
			$_SESSION['dbc_branch_application'] = $user_app;
			$_SESSION['dbc_branch_cluster'] = $cluster;
			$cmd = '';
			$cmd .='				
				<script>
					$("#formmodal").hide();
					sessionStorage.setItem("company", "'.$company.'");
					sessionStorage.setItem("username", "'.$username.'");
					sessionStorage.setItem("module", "'.$mods.'");
					app_alert("Sign In Success","You have successfuly Signing-In","success","Ok","","appyes");
				</script>
			';
			print_r($cmd);
			exit();			
		} else {
			$cmd = '';
			$cmd .='				
				<script>
					app_alert("System Message","Your account is locked, Please contact the sytem Administrator","warning","Ok","","");
				</script>
			';
			print_r($cmd);
		}
	} else {
		$cmd = '';
		$cmd .='				
			<script>
				$("#upass").val("");
				app_alert("Login Error","Invalid Username or Password","warning","Ok","","");
			</script>
		';
		print_r($cmd);
		exit();
	}
	mysqli_close($db);
}
function checkPolicy($idcode,$user_app,$db)
{
	$checkPolicy = "SELECT * FROM tbl_system_permission WHERE idcode='$idcode' AND applications='$user_app'";
	$pRes = mysqli_query($db, $checkPolicy);    
    if ( $pRes->num_rows > 0 ) 
    {
    	return 1;
	} else {
		return 0;
	}
	mysqli_close($db);
}
