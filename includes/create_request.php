<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
require $_SERVER['DOCUMENT_ROOT']."/Modules/DBC_Branch_Ordering_System/class/Class.functions.php";
$function = new FDSFunctions;

$branch = $_SESSION['dbc_branch_branch'];
$pendingRequest = $function->getPendingToReceive($branch,$db);

$clusterallowed = ['BANKID CLUSTER', 'DIGOS CLUSTER', 'GENSAN CLUSTER'];

$isAllowed = 0;
$stmt = $db->prepare("SELECT COUNT(*) as count FROM tbl_branch WHERE location IN (?, ?, ?) AND branch = ?");
$stmt->bind_param("ssss", $clusterallowed[0], $clusterallowed[1], $clusterallowed[2], $branch);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();


if($result['count'] > 0){
    $isAllowed = 1;
} else {
    $isAllowed = 0;
}
$stmt->close();



?>
<style>
.smnav-header input[type=text] {width:100%;padding-left: 25px;padding-right:27px}
.smnav-header select {width:200px;}
.reload-data {display: flex;gap: 15px;margin-left: auto;right:0;}
.tableFixHead {margin-top:15px;background:#fff;}
.tableFixHead  { overflow: auto; height: calc(100vh - 222px); width:100% }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1; background:#0cccae; color:#fff }
.tableFixHead table  { border-collapse: collapse;}
.tableFixHead th, .tableFixHead td { font-size:14px; white-space:nowrap } 
</style>
<div class="smnav-header">
	<div class="select-shell">
		<span>FORM TYPE</span>
		<select id="form_type" class="form-control form-control-sm" style="width:100PX"  disabled>
			
		</select>
		<span style="margin-left:20PX;">RECIPIENT</span>
		<!--select id="recipient" class="form-control form-control-sm"></select-->
		<input type="text" id="recipient" class="form-control form-control-sm" value="DAVAO BAKING CENTER" disabled>
		<button class="btn btn-primary btn-sm" onclick="genererateRequest('new')">Generate</button>
	</div>
	<span class="reload-data">
		<span style="margin-left:20px;margin-top:4px;">Show</span>
		<select id="limit" style="width:70px" class="form-control form-control-sm" onchange="load_data()">
			<?php echo $function->GetRowLimit(); ?>
		</select>
	</span>
</div>
<div class="tableFixHead" id="smnavdata">Loading... <i class="fa fa-spinner fa-spin"></i></div>
<script>
function selectFormType(formType)
{
	var module = '<?php echo MODULE_NAME; ?>';
	var mode = 'getformtype';
	$.post("./Modules/" + module + "/actions/actions.php", { mode: mode, formType: formType },
	function(data) {		
		$('#recipient').html(data);
	});
}
function genererateRequest(params)
{
	var isallowed = '<?php echo $isAllowed; ?>';
	var pr = '<?php echo $pendingRequest?>';
	if(isallowed == 0){
		
		if(pr > 0){
			swal("System Message","You have " +pr+ " Pending to Receive Order", "warning");
			return false;
		}
	}

	var module = '<?php echo MODULE_NAME; ?>';
	var form_type = $('#form_type').val();
	var recipient = $('#recipient').val();
	$('#modaltitle').html("CREATE ORDER REQUEST");
	$.post("./Modules/" + module + "/apps/request_form.php", { params: params, form_type: form_type, recipient: recipient },
	function(data) {		
		$('#formmodal_page').html(data);
		$('#formmodal').show();
	});
}
$(function()
{
	var module = '<?php echo MODULE_NAME; ?>';
	$('#search').keyup(function()
	{
		var limit = '';
		var search = $('#search').val();
		$.post("./Modules/" + module + "/includes/request_data.php", { limit: limit, search: search },
		function(data) {
			$('#smnavdata').html(data);
		});

	});
	selectFormType("MRS");
	load_data();
});
function clearSearch()
{
	$('#search').val('');
	reload_data();
}
function load_data()
{
	var module = '<?php echo MODULE_NAME; ?>';
	var limit = $('#limit').val();
	$.post("./Modules/" + module + "/includes/request_data.php", { limit: limit },
	function(data) {
		$('#smnavdata').html(data);
	});
}
</script>