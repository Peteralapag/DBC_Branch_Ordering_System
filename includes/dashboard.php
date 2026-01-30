<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
require $_SERVER['DOCUMENT_ROOT']."/Modules/" . MODULE_NAME . "/class/Class.functions.php";
$function = new FDSFunctions;
$branch = $_SESSION['dbc_branch_branch'];
?>
<style>
.box-wrapper {display: flex;width:100%;gap: 15px;;flex-wrap: wrap;}
.dash-box {display: flex;padding:20px;width:270px;height:180px;border-radius:20px;color: #fff;flex-direction: column;align-items: center;
box-shadow: 10px 10px 5px -8px rgba(0,0,0,0.46);-webkit-box-shadow: 10px 10px 5px -8px rgba(0,0,0,0.46);-moz-box-shadow: 10px 10px 5px -8px rgba(0,0,0,0.46)}
.dash-box button {width:200px;}
.dash-box:hover {box-shadow: 10px 10px 5px -2px rgba(0,0,0,0.46);-webkit-box-shadow: 10px 10px 5px -2px rgba(0,0,0,0.46);-moz-box-shadow: 10px 10px 5px -2px rgba(0,0,0,0.46);}
.dash-value {margin-top: auto;margin-bottom: auto;text-align: center;text-align:center;font-size:24px;font-weight:bold;}
.dash-box label { color: #fff; }
.pro {background: #007171;}
.pro:hover {background: #0a9191;}
.par {background: #00bbbb}
.par:hover {background: #1cdada}
.por {background: #f29b09}
.por:hover {background: #f4b750}
</style>
<div class="box-wrapper">
	<div class="dash-box pro">
		<label style="text-align:center">Pending to Receive Order</label>
		<div class="dash-value"><?php echo $function->getPendingToReceive($branch,$db); ?></div>
		<span><button class="btn btn-success" onclick="walaPa('order_tracking')">Open</button></span>
	</div>
	<!--div class="dash-box par">
		<label style="text-align:center">Pending Approval Request</label>
		<div class="dash-value"><?php echo $function->getForApproval($branch,$db); if($function->getForApproval($branch,$db) <= 0) { $par_btn = 'disabled'; } else { $par_btn = ''; } ?></div>
		<span><button <?php echo $par_btn; ?> class="btn btn-info" onclick="walaPa()">Open</button></span>
	</div-->
	<!--div class="dash-box por">
		<label style="text-align:center">Pending Order Request</label>
		<div class="dash-value"><?php echo $function->getSubmittedOrder($branch,$db); if($function->getSubmittedOrder($branch,$db) <= 0) { $por_btn = 'disabled'; } else { $por_btn = ''; } ?></div>
		<span><button <?php echo $por_btn; ?> class="btn btn-warning" onclick="walaPa('order_tracking')">Open</button></span>
	</div-->
</div>
<script>
function walaPa(params)
{
	createRequest(params);
}
</script>