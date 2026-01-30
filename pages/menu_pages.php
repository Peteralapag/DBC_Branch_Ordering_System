<?php
define("MODULE_NAME", "DBC_Branch_Ordering_System");
$app_path = $_SERVER['DOCUMENT_ROOT']."/Modules/".MODULE_NAME;
if($_POST['page'] == 'dashboard')
{
	include ($app_path.'/includes/dashboard.php');
} else {
	$page = $_POST['page'];
	include ($app_path.'/includes/'.$page.'.php');
}
?>