<?php
// fichero de configuracion de VideoLayers
// requiere clase config
// ver http://stackoverflow.com/a/3724689/1414176 y http://stackoverflow.com/a/2237315/1414176

// Version 		Fecha 		Descripcion cambios
// 1.1 			16-12-2017 	Adaptacion desde VocTours.

include (MODELS_DIR . 'config.php'); 
/*
Settings::setProtected('db_hostname', 'localhost');
Settings::setProtected('db_username', 'root');
Settings::setProtected('db_password', '');
Settings::setProtected('db_database', 'root');
Settings::setProtected('db_charset', 'UTF-8');
//...
echo Settings::getProtected('db_hostname'); // localhost
//...
Settings::setPublic('config_site_title', 'MySiteTitle');
Settings::setPublic('config_site_charset', 'UTF-8');
Settings::setPublic('config_site_root', 'http://localhost/dev/');
*/
$debug_settings = "";	// llamado en voces_box.


# paths
Settings::setPublic('Path_AppBase_Public', '/');	// server
Settings::setPublic('Path_AppBase_Local', '/localhost/videolayers/v0.9/');			// local, dev.
$app_base_path = Settings::getPublic('Path_AppBase_Local');
Settings::setPublic('Path_AppBase', $app_base_path);
Settings::setPublic('Path_Modules', $app_base_path.'modules/');
Settings::setPublic('Path_Modules_VideoLayers', $app_base_path.'');					// era en VT: modules/vl/
$path_vl = Settings::getPublic('Path_Modules_VideoLayers');
Settings::setPublic('Path_Modules_Social', $app_base_path.'social/');				// era en VT: modules/vl/social/
$path_social = Settings::getPublic('Path_Modules_Social');


# StyleSets por default

Settings::setPublic('Default_StyleSet_Id', 1);
Settings::setPublic('Default_StyleSet_Classname', 'bombolla-blue');
Settings::setPublic('Default_StyleSet_Iconpath', 'img3m/bubbleblue-bl.png');
Settings::setPublic('Default_StyleSet_Color', 'blue');
Settings::setPublic('Default_StyleSet_Arrow_Updown', 'down');
Settings::setPublic('Default_StyleSet_Arrow_Leftright', 'left');


# ROLES

Settings::setPublic('Roles_Admin_Edit_Admin_Voces', true);
Settings::setPublic('Roles_Admin_Edit_Agent_Voces', true);
Settings::setPublic('Roles_Admin_Edit_User_Voces', true);
Settings::setPublic('Roles_Admin_Edit_Guest_Voces', true);

Settings::setPublic('Roles_Agent_Edit_Admin_Voces', false);
Settings::setPublic('Roles_Agent_Edit_Agent_Voces', true);
Settings::setPublic('Roles_Agent_Edit_User_Voces', true);
Settings::setPublic('Roles_Agent_Edit_Guest_Voces', true);

Settings::setPublic('Roles_User_Edit_Admin_Voces', false);
Settings::setPublic('Roles_User_Edit_Agent_Voces', false);
Settings::setPublic('Roles_User_Edit_User_Voces', true);
Settings::setPublic('Roles_User_Edit_Guest_Voces', false);

Settings::setPublic('Roles_Guest_Edit_Admin_Voces', false);
Settings::setPublic('Roles_Guest_Edit_Agent_Voces', false);
Settings::setPublic('Roles_Guest_Edit_User_Voces', false);
Settings::setPublic('Roles_Guest_Edit_Guest_Voces', false);
# fin ROLES

$logged_user_id 	= isset($_SESSION['uid']) ? $_SESSION['uid'] : 36;								// id del usuario "guest"
$logged_uname 		= isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
$logged_user_role 	= isset($_SESSION['role_name']) ? $_SESSION['role_name'] : "guest";

if ($logged_user_role == 'admin') {
	Settings::setPublic('logged_user_can_edit', true);
	Settings::setPublic('logged_user_can_delete', true);
	Settings::setPublic('logged_user_can_reply', true);
	Settings::setPublic('debug', '1');
}
if ($logged_user_role == 'agent') {
	Settings::setPublic('logged_user_can_edit', true);
	Settings::setPublic('logged_user_can_delete', true);
	Settings::setPublic('logged_user_can_reply', true);
	Settings::setPublic('debug', '2');
}
if ($logged_user_role == 'user') {
	// public
	Settings::setPublic('logged_user_can_edit_public_own', true);
	Settings::setPublic('logged_user_can_delete_public_own', true);
	Settings::setPublic('logged_user_can_reply_public_own', true);

	Settings::setPublic('logged_user_can_edit_public_others', false);
	Settings::setPublic('logged_user_can_delete_public_others', false);
	Settings::setPublic('logged_user_can_reply_public_others', true);
	// private
	Settings::setPublic('logged_user_can_edit_private_own', true);	// logged_user_can_edit_private_own
	Settings::setPublic('logged_user_can_delete_private_own', true);
	Settings::setPublic('logged_user_can_reply_private_own', true);

	Settings::setPublic('logged_user_can_edit_private_others', false);
	Settings::setPublic('logged_user_can_delete_private_others', false);
	Settings::setPublic('logged_user_can_reply_private_others', false);
	Settings::setPublic('debug', '3');
}
if ($logged_user_role == 'guest') {
	Settings::setPublic('logged_user_can_edit', false);
	Settings::setPublic('logged_user_can_delete', false);
	Settings::setPublic('logged_user_can_reply', true);
	Settings::setPublic('debug', '4');
}


$logged_user_can_edit = Settings::getPublic('logged_user_can_edit');
$logged_user_can_delete = Settings::getPublic('logged_user_can_delete');
$logged_user_can_reply = Settings::getPublic('logged_user_can_reply');

?>
