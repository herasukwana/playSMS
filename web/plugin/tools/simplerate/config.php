<?php
defined('_SECURE_') or die('Forbidden');

if (auth_isadmin()) {
	$menutab = $core_config['menutab']['administration'];
	$menu_config[$menutab][] = array("index.php?app=main&inc=tools_simplerate&op=simplerate_list", _('Manage SMS rate'));
}
