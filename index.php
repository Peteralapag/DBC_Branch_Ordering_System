<?php
session_start();
define("MODULE_NAME", "DBC_Branch_Ordering_System");
if(!isset($_SESSION['dbc_branch_username'])) { ?>
<script>
$(function()
{
	var module = '<?php echo MODULE_NAME; ?>';
	$('#modaltitle').html("Branch Ordering Login");
	$('#modalicon').html('<i class="fa-solid fa-user color-dodger"></i>');	
	$.post("../Modules/" + module + "/apps/login.php", { module: module },
	function(data) {
		$('#formmodal_page').html(data);		
		$('#formmodal').show();		
	});
});
</script>	
<?php exit(); } ?>
<link rel="stylesheet" href="../Modules/<?php echo MODULE_NAME; ?>/styles/styles.css">
<script src="../Modules/<?php echo MODULE_NAME; ?>/scripts/script.js"></script>
<!-- @@@@@@@@@@ ################### @@@@@@@@@@@ -->
<div class="sidebar">
	<div class="logo-title" id="logotitle"></div>
	<div class="navigation" id="navigation"></div>
</div>
<div class="content-wrapper">
	<div class="contents" id="contents"></div>
	<div id="checkDR"></div>
</div>
<script>
$(function()
{
	var module = '<?php echo MODULE_NAME; ?>';
	$('#logotitle').load("../Modules/" + module + "/apps/logo_title.php");
	$('#navigation').load("../Modules/" + module + "/pages/sidebar_navigation.php");
//	checkDeliveries();
});
function checkDeliveries()
{
	$.post("./Modules/<?php echo MODULE_NAME; ?>/includes/check_deliveries.php", { },
	function(data) {
		$('#checkDR').html(data);
	});
}
</script>