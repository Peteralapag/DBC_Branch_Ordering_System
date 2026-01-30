<?php
include '../../../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
define("MODULE_NAME", "DBC_Branch_Ordering_System");
?>
<style>
.sidebar-nav { list-style-type:none; margin:0;padding:0 }
.navpadleft {margin-left:10px;cursor:pointer; width:100%;}
.sidebar-nav li { display: flex; padding:5px 5px 5px 5px;border-bottom: 1px solid #aeaeae; width:100%; gap: 15px;cursor:pointer}
.sidebar-nav li:hover {background:#e7e7e7;}
.sidebar-nav .nav-icon {width:30px;text-align:center;font-size:18px;}
.sidebar-nav span {right: 0;}
.sidebar-nav .caret-right {margin-left: auto;}
.active-nav {background: #dcdfe0;}
.active {border: 1px solid blue;}
.nav-bottom-btn {
	position:absolute;
	bottom: 2px;
	margin-left:3px;
	width: 98%;
}

</style>
<ul class="sidebar-nav">
<?php
$sqlMenu = "SELECT * FROM dbc_branch_navigation WHERE active=1";
$MenuResults = mysqli_query($db, $sqlMenu);    
if ( $MenuResults->num_rows > 0 ) 
{
	$m=0;
	while($MENUROW = mysqli_fetch_array($MenuResults))  
	{
		$m++;
?>
	<li id="nav<?php echo $m; ?>" data-nav="nav<?php echo $m; ?>" onclick="Check_Permissions('p_view',createRequest,'<?php echo $MENUROW['page_name']; ?>','<?php echo $MENUROW['menu_name']; ?>')">
		<div class="nav-icon"><?php echo $MENUROW['icon_class']; ?></div> <span><?php echo $MENUROW['menu_name']; ?></span>
	</li>
<?php } } else { echo "<li>Menu is Empty.</li>"; }?>
</ul>
<div class="btn-group nav-bottom-btn" role="group" aria-label="Ronan Sarbon">
	<?php if($_SESSION['dbc_branch_userlevel'] == 50 || $_SESSION['dbc_branch_userlevel'] >= 80) { ?>
	<button class="btn btn-secondary" onclick="clusterSettings()">Cluster Settings <i class="fa-solid fa-gear"></i></button>
	<?php } ?>
	<button class="btn btn-danger" onclick="closeApps()">Exit <i class="fa-solid fa-right-from-bracket"></i></button>
</div>
<script>
function clusterSettings()
{
	var module = '<?php echo MODULE_NAME; ?>';
	$('#modaltitle').html("CLUSTER SETTINGS");
	$.post("./Modules/" + module + "/apps/cluster_settings.php", { },
	function(data) {
		$('#formmodal_page').html(data);
		$('#formmodal').show();
	});
}

function createRequest(page)
{
	var module = '<?php echo MODULE_NAME; ?>';
	$.post("./Modules/" + module + "/pages/menu_pages.php", { page: page },
	function(data) {
		$('#contents').html(data);
	});
}
$(function()	
{
	if (!sessionStorage.getItem('navfdsbos')) {
		$("#nav1").addClass('active-nav');
		$("#nav1").trigger('click');
	} else {
		$("#"+sessionStorage.navfdsbos).addClass('active-nav');
		$("#"+sessionStorage.navfdsbos).trigger('click');
	}
	
/*	if(sessionStorage.navfdsbos !== null)
	{
		$("#"+sessionStorage.navfdsbos).addClass('active-nav');
		$("#"+sessionStorage.navfdsbos).trigger('click');
	}
	else if(sessionStorage.navfdsbos === null || sessionStorage.navfdsbos === undefined || sessionStorage.navfdsbos === '')
	{
		$("#nav1").addClass('active-nav');
		$("#nav1").trigger('click');
		console.log("A");
	} */
	$('.sidebar-nav li').click(function()
	{
		var tab_id = $(this).attr('data-nav');
		sessionStorage.setItem("navfdsbos",tab_id);
		$('.sidebar-nav li').removeClass('active-nav');
		$(this).addClass('sidebar-nav');
		$("#"+tab_id).addClass('active-nav');	
	});
});
function closeApps()
{
	var module = '<?php echo MODULE_NAME; ?>';
	$.post("./Modules/" + module + "/actions/close_applications.php", { },
	function(data) {
		$('#contents').html(data);
	});
}
</script>
