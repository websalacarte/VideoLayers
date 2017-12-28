<?php
/* test config_file.php */
/* Variables */


// client variables
//include 'client_vars.php'
//$client_id = '1234';

// common to create video pages
//$root_dir = "/";
$root_dir_sys = "/home5/websalac/public_html/".$ruta_dominio.$path_vl; 	// "/home5/websalac/public_html/videolayers/v0.8/";	//	"C:/xampp/xampp/htdocs/videolayers/v0.8/";
$root_dir = $protocolo_no_ssl.$ruta_dominio.$path_vl;	//"http://www.websalacarte.com/videolayers/v0.8/";
$members_path = "";					//$client_id . "/";
$members_video_dir = "";			//"videos/";
$template_config_path = $root_dir;
$video_dir = "";					//"videos/";

$tpl_file = "index-template-VL-Vimeo.php";		// "profile.html";
$tpl_file_yt = "index-template-yt.php";
$tpl_path = "/home5/websalac/public_html/".$path_vl;	// "/home5/websalac/public_html/videolayers/v0.8/";	//"../templates/";	// "/home/ricky/public_html/templates/";	//	/home5/websalac/public_html/videolayers/v0.7/	/home5/websalac/public_html/

?>