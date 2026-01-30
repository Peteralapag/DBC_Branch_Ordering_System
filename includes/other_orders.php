<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
require $_SERVER['DOCUMENT_ROOT']."/Modules/DBC_Branch_Ordering_System/class/Class.functions.php";
$function = new FDSFunctions;
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
		<div class="btn-group" role="group" aria-label="Basic example">
			<button class="btn btn-primary btn-sm" onclick="genererateRequest('new')">Generate MRS</button>
			<button class="btn btn-warning btn-sm">Load MRS Data</button>
			<button class="btn btn-success btn-sm">Load Approved Request</button>
		</div>
		<input id="form_type" type="hidden" value="MRS">
		<input id="recipient" type="hidden" value="PROPERTY CUSTODIAN">
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
function genererateRequest(params)
{
	var module = '<?php echo MODULE_NAME; ?>';
	var form_type = $('#form_type').val();
	var recipient = $('#recipient').val();
	$('#modaltitle').html("CREATE ORDER REQUEST");
	$.post("./Modules/" + module + "/apps/request_form_others.php", { params: params, form_type: form_type, recipient: recipient },
	function(data) {		
		$('#formmodal_page').html(data);
		$('#formmodal').show();
	});
}
</script>