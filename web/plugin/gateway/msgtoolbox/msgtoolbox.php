<?php
defined('_SECURE_') or die('Forbidden');
if(!auth_isadmin()){auth_block();};

include $core_config['apps_path']['plug']."/gateway/msgtoolbox/config.php";

$gw = core_gateway_get();

if ($gw == $plugin_config['msgtoolbox']['name']) {
	$status_active = "<span class=status_active />";
} else {
	$status_active = "<span class=status_inactive />";
}


switch (_OP_) {
	case "manage":
		if ($err = $_SESSION['error_string']) {
			$content = "<div class=error_string>$err</div>";
		}
		$content .= "
			<h2>"._('Manage msgtoolbox')."</h2>
			<form action=index.php?app=main&inc=gateway_msgtoolbox&op=manage_save method=post>
			"._CSRF_FORM_."
			<table class=playsms-table>
				<tbody>
				<tr>
					<td class=label-sizer>"._('Gateway name')."</td><td>msgtoolbox $status_active</td>
				</tr>
				<tr>
					<td>"._('msgtoolbox URL')."</td><td><input type=text size=30 maxlength=250 name=up_url value=\"".$plugin_config['msgtoolbox']['url']."\"></td>
				</tr>
				<tr>
					<td>"._('Route')."</td><td><input type=text size=5 maxlength=5 name=up_route value=\"".$plugin_config['msgtoolbox']['route']."\"></td>
				</tr>
				<td>"._('Username')."</td><td><input type=text size=30 maxlength=30 name=up_username value=\"".$plugin_config['msgtoolbox']['username']."\"></td>
				</tr>
				<tr>
					<td>"._('Password')."</td><td><input type=password size=30 maxlength=30 name=up_password value=\"\"> "._hint(_('Fill to change the password'))."</td>
				</tr>
				<tr>
					<td>"._('Module sender ID')."</td><td><input type=text size=30 maxlength=16 name=up_global_sender value=\"".$plugin_config['msgtoolbox']['global_sender']."\"> "._hint(_('Max. 16 numeric or 11 alphanumeric char. empty to disable'))."</td>
				</tr>
				<tr>
					<td>"._('Module timezone')."</td><td><input type=text size=5 maxlength=5 name=up_global_timezone value=\"".$plugin_config['msgtoolbox']['datetime_timezone']."\"> "._hint(_('Eg: +0700 for Jakarta/Bangkok timezone'))."</td>
				</tr>
				</tbody>
			</table>
			<p><input type=submit class=button value=\""._('Save')."\">
			</form>";
		$content .= _back('index.php?app=main&inc=tools_gatewaymanager&op=gatewaymanager_list');
		_p($content);
		break;
	case "manage_save":
		$up_url = $_POST['up_url'];
		$up_route = $_POST['up_route'];
		$up_username = $_POST['up_username'];
		$up_password = $_POST['up_password'];
		$up_global_sender = $_POST['up_global_sender'];
		$up_global_timezone = $_POST['up_global_timezone'];
		$_SESSION['error_string'] = _('No changes has been made');
		if ($up_url && $up_username) {
			if ($up_password) {
				$password_change = "cfg_password='$up_password',";
			}
			$db_query = "
				UPDATE "._DB_PREF_."_gatewayMsgtoolbox_config 
				SET c_timestamp='".mktime()."',
				cfg_url='$up_url',
				cfg_route='$up_route',
				cfg_username='$up_username',
				".$password_change."
				cfg_global_sender='$up_global_sender',
				cfg_datetime_timezone='$up_global_timezone'
			";
			if (@dba_affected_rows($db_query)) {
				$_SESSION['error_string'] = _('Gateway module configurations has been saved');
			}
		}
		header("Location: "._u('index.php?app=main&inc=gateway_msgtoolbox&op=manage'));
		exit();
		break;
}
