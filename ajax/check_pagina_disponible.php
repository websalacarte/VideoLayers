<?php
// The back-end then will determine if the page is available or not,
// and finally returns a JSON { "valid": true } or { "valid": false }
include_once('../includes/defines.php');

$page = $_POST['new_video_pg'];
$isAvailable = false;

$filename = $_SERVER['DOCUMENT_ROOT'] . '/' . $path_vl  . $page . '.php';	// $_SERVER['DOCUMENT_ROOT'] . '/videolayers/v0.9/' . $page . '.php';
//$filename = DOC_ROOT . 'videolayers/v0.7/addpage_old.php';
//$filename = $_SERVER['DOCUMENT_ROOT'] . '/videolayers/v0.7/addpage_old.php';

if (file_exists($filename)) {
    //echo "The file $filename exists";
	$isAvailable = false;
} else {
    //echo "The file $filename does not exist";
	$isAvailable = true;
}


// Finally, return a JSON
echo json_encode(array(
    'valid' => $isAvailable,
));
?>
