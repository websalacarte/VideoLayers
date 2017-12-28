<?php
$subtitles_caja = $_GET['subt'];
$subtitles_formato = $_GET['f'];
if ($subtitles_formato == "srt") {
	// SRT
	$fichero_ext = "srt";
	$fichero_formatname = "SRT";
}
else {
	// VTT
	$fichero_ext = "vtt";
	$fichero_formatname = "WebVTT";
}
//$fichero_ext = $subtitles_formato == "vtt" ? "vtt" : "srt";

$filename="ucrania/subtitles_".$subtitles_caja.".".$fichero_ext;
header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
//header("Content-Type: application/force-download");
header("Content-Type: text; charset=utf-8");
header("Content-Length: " . filesize($filename));
header("Connection: close");
// get the subtitles from the DB, create a file (to folder hanaa/) and force download


require_once('includes/ficheros_ucrania.php');	// necesito nombre del video

//$output .= "Write this text in the file";

$fecha_hoy = new DateTime();	//new DateTime('06/31/2011');
//$fecha_hoy_ahora = date_format($fecha_hoy, 'Y-m-d H:i:s'); // 2011-07-01 00:00:00
$fecha_hoy_ahora = date_format($fecha_hoy, DateTime::COOKIE);	// formato Sunday, 28-Feb-16 20:27:50 CET (server timezone)

$fecha_iso = new DateTime();	//$fecha_iso->format(DateTime::ISO8601);	// ISO 8601

//$output = "WebVTT subtitles for " . title_video . ", edited and saved from Websalacarte on " . $fecha_iso->format(DateTime::ISO8601);
$output = $fichero_formatname . " subtitles for " . title_video . ", edited and saved from Websalacarte on ".$fecha_hoy_ahora;
$output .= "\r\n";
$output .= "\r\n";

include('includes/get_subtitles_ucrania.php');

$file = file_put_contents ($filename, $output);
$content = file_get_contents($filename);


//print preg_replace( chr(ord("'")), "'", "&#039;1quote&#039;" );
print "$content";

?>