<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
define("MODULE_NAME", "DBC_Branch_Ordering_System");
require $_SERVER['DOCUMENT_ROOT']."/Modules/" . MODULE_NAME . "/class/Class.functions.php";
$function = new FDSFunctions;

$username = $_SESSION['dbc_branch_username'];
$cluster = $_SESSION['dbc_branch_cluster'];
$sqlCluster = "SELECT * FROM dbc_cluster_settings WHERE username='$username'";
$clusterResults = mysqli_query($db, $sqlCluster);
$itemArray = array(); // Initialize an empty array

if ($clusterResults->num_rows > 0) {
    while ($CROWS = mysqli_fetch_array($clusterResults)) {
        $data = $CROWS['cluster_list'];
        $itemArray = explode(",", $data);
    }
}
?>
<style>
.cluster-settings-wrapper {
    width: 500px;
}
.cluster-settings-wrapper select {
	padding:10px;
	width:240px
}
</style>
<div class="cluster-settings-wrapper" class="form-control">
    <table style="width: 500px;border-collapse:collapse" class="table table-bordered">
    	<tr>
    		<th>CURRENT CLUSTER</th>
    		<th>CURRENT BRANCH</th>
    	</tr>
    	<tr>
    		<td><?php echo $_SESSION['dbc_branch_cluster']; ?></td>
    		<td><?php echo $_SESSION['dbc_branch_branch']; ?></td>
    	</tr>
		<tr>
			<td style="width:200px;">
			    <select multiple="multiple" name="a" size="10" onchange="changeCluster(this.value)">
			        <?php
			        for ($i = 0; $i < count($itemArray); $i++) {
			            echo "<option>" . $itemArray[$i] . "</option>";
			        }
			        ?>
			    </select>
			</td>
			<td style="width:200px">
				<select multiple="multiple" name="a" size="10" onchange="changeBranch(this.value)">
				<?php echo $function->LoadBranch($cluster,$db); ?>
				</select>
			</td>
		</tr>
	</table>	
</div>
<div id="clusterbranchresults"></div>
<script>
function changeCluster(cluster)
{
	var module = '<?php echo MODULE_NAME; ?>';
	rms_reloaderOn("Changing Cluster...");
	var mode = 'changecluster';
	setTimeout(function()
	{
		$.post("./Modules/" + module + "/actions/change_cluster.php", { mode: mode, cluster: cluster },
		function(data) {
			$('#clusterbranchresults').html(data);
			rms_reloaderOff();
			window.location.reload();
		});
	},1000);
}
function changeBranch(branch)
{
	var module = '<?php echo MODULE_NAME; ?>';
	rms_reloaderOn("Changing Branch...");
	var mode = 'changebranch';
	setTimeout(function()
	{
		$.post("./Modules/" + module + "/actions/change_cluster.php", { mode: mode, branch: branch },
		function(data) {
			$('#clusterbranchresults').html(data);
			rms_reloaderOff();
		});
	},1000);
}

</script>