<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
require $_SERVER['DOCUMENT_ROOT']."/Modules/DBC_Branch_Ordering_System/class/Class.functions.php";
$function = new FDSFunctions;
$cluster = $_SESSION['dbc_branch_cluster'];
$userlevel = $_SESSION['dbc_branch_userlevel'];
$currentMonthDays = date('t');
$date_from = date("Y-m-01");
$date_to = date("Y-m-".$currentMonthDays);
$branch = $_SESSION['dbc_branch_branch'];
?>
<style>
.smnav-header input[type=text] {width:100%;padding-left: 25px;padding-right:27px; margin-left:0}
.smnav-header select {margin-left: 10px;width:270px;}
.reload-data {display: flex;gap: 15px;margin-left: auto;right:0;}
.search-shell {position: relative;margin-left:10px;width:270px;}
.search-mglass {position:absolute;top:3px;margin-left:5px;}
.search-xmark {position:absolute;top: 1px;right: 5px;font-size:20px;cursor: pointer;}
.search-xmark:hover {color: red;}
.tableFixHead {margin-top:15px;background:#fff;}
.tableFixHead  { overflow: auto; height: calc(100vh - 222px); width:100% }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1; background:#0cccae; color:#fff }
.tableFixHead table  { border-collapse: collapse;}
.tableFixHead th, .tableFixHead td { font-size:14px; white-space:nowrap } 
</style>
<div class="smnav-header">
	<?php if($userlevel >= 50) { ?>
	<div class="select-shell" style="margin-left:-10px">
		<select id="branch" class="form-control form-control-sm" onchange="selectBranch(this.value)">
			<?php echo $function->LoadBranch($cluster,$db); ?>
		</select>
	</div>
	<?php } else { ?>
	<div class="search-shell" style="margin-left:0px;width:200px;">
		<input id="branch" type="text" style="text-align:center" class="form-control form-control-sm" value="<?php echo $_SESSION['dbc_branch_branch']; ?>" disabled>
	</div>
	<?php } ?>
	<div class="search-shell">
		<input id="search" type="text" class="form-control form-control-sm" placeholder="Recipient / Control No.">		
		<i class="fa-solid fa-magnifying-glass search-mglass"></i>
		<i class="fa-solid fa-circle-xmark search-xmark" onclick="clearSearch()"></i>
	</div>
	<span class="reload-data">
		<span style="margin-left:20px;margin-top:4px;">Show</span>
		<select id="limit" style="width:70px;text-align:center" class="form-control form-control-sm" onchange="load_data()">
			<?php echo $function->GetRowLimit(); ?>
		</select>
	</span>
</div>
<div class="tableFixHead" id="smnavdata">Loading... <i class="fa fa-spinner fa-spin"></i></div>

<script>
$(function()
{
	$('#search').keyup(function()
	{
		var search = $('#search').val();
		var _branch = $('#branch').val();
		if(_branch != '')
		{
			var branch = $('#branch').val();
		} else {
			var branch = '';
		}
		$.post("./Modules/" + '<?php echo MODULE_NAME; ?>' + "/includes/order_data.php", { search: search, branch: branch },
		function(data) {
			$('#smnavdata').html(data);
		});
	});
	load_data();
});
function selectBranch(branch)
{
	console.log(branch)
	var limit = $('#limit').val();
	$.post("./Modules/" + '<?php echo MODULE_NAME; ?>' + "/includes/order_data.php", { limit: limit, branch: branch },
	function(data) {
		$('#smnavdata').html(data);
	});
}
function clearSearch()
{
	$('#search').val('');
	reload_data();
}
function reload_data()
{
	$('#' + sessionStorage.navfdsbos).trigger('click');
}
function load_data()
{
	var limit = $('#limit').val();
	$.post("./Modules/" + '<?php echo MODULE_NAME; ?>' + "/includes/order_data.php", { limit: limit },
	function(data) {
		$('#smnavdata').html(data);
	});
}
</script>