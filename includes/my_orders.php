<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
require $_SERVER['DOCUMENT_ROOT']."/Modules/" . MODULE_NAME . "/class/Class.functions.php";
$function = new FDSFunctions;
$cluster = $_SESSION['dbc_branch_cluster'];
$userlevel = $_SESSION['dbc_branch_userlevel'];
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
function selectBranch(value)
{
	if(value == '')
	{
		load_data();
	} 
	else
	{
		var module = '<?php echo MODULE_NAME; ?>';
		var limit = $('#limit').val();
		rms_reloaderOn("Loading data...");
		$.post("./Modules/" + module + "/includes/my_orders_page.php", { limit: limit, branch: value },
		function(data) {
			$('#smnavdata').html(data);
			rms_reloaderOff();
		});
	}
}

$(function()
{
	var module = '<?php echo MODULE_NAME; ?>';
	$('#search').keyup(function()
	{
		var limit = '';
		var search = $('#search').val();
		var branch = $('#branch').val()
		$.post("./Modules/" + module + "/includes/my_orders_page.php", { limit: limit, search: search, branch: branch },
		function(data) {
			$('#smnavdata').html(data);
		});

	});	
	load_data();
});
function reload_data()
{
	$('#' + sessionStorage.navfds).trigger('click');
}
function clearSearch()
{
	$('#search').val('');
	load_data();
}
function load_data()
{
	var module = '<?php echo MODULE_NAME; ?>';
	var limit = $('#limit').val();
	rms_reloaderOn("Loading data...");
	$.post("./Modules/" + module + "/includes/my_orders_page.php", { limit: limit },
	function(data) {
		$('#smnavdata').html(data);
		rms_reloaderOff();
	});
}
</script>