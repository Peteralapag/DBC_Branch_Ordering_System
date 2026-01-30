function Check_Accesss(params,permission,action)
{
	var module = sessionStorage.module_name;
	$.post("./Modules/DBC_Branch_Ordering_System/actions/check_permissions.php", { permission: permission, module: module },
	function(data) {
		if(data == 1)
		{
			action(params);
		}
		else if(data == 0)
		{
			swal("Un-Authorized", "You have unsificient Privilege. Please contact System Administrator","warning");
		}
	});
}
function Check_Permissions(permission,action,page,module)
{
	if(page == 'dashboard')
	{
		action('dashboard');
	} else {
		sessionStorage.setItem("page_name", page);
		sessionStorage.setItem("module_name", module);
		$.post("./Modules/DBC_Branch_Ordering_System/actions/check_permissions.php", { permission: permission, module: module },
		function(data) {
			if(data == 1)
			{
				action(page);
			}
			else if(data == 0)
			{
				swal("Access Denied","You have insufficient access. Please contact System Administrator","warning");
			}
		});
	}
}
