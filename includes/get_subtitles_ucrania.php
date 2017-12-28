<?php require_once('includes/defines.php'); ?>
<?php require_once ('mysql/models/comments.php'); ?>
<?php require_once('includes/ficheros_ucrania.php'); ?>
<?php //include_once("scripts/check_user.php"); ?>
<?php include_once("scripts/connect.php"); ?>
<?php 
$videoId=32;
$platform_videoId=164218427; // el id en YT-Vimeo
$videoId_subt=24819045;
$page_id=38;
// Lo llamamos desde save_subt.php, donde obtengo $get_caja
$comments = Comments::getCommentsVideoBox_vtt($videoId, $get_caja);
/*
print_r($comments);
exit;	
*/

function removeslashes($string)
{
    $string=implode("",explode("\\",$string));
    return stripslashes(trim($string));
}
function clean_numeric_utf8($input) {
	$output = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $input); 
	return $output;
}
function unichr($u) {
    return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
}
function cambia_single_quotes($string){
	return preg_replace( chr(ord("\'")), "'", $string );
}
/*
function convert_date_to_HHMMSS($seconds) {
	if($seconds < 3600){
		$format = 'i:s.u';
	}
	else {
		$format = 'G:i:s.u';
	}
	$time = ($seconds >= 3600) ? date('G', $seconds).':' : '';
	//$time .= intval(date('i',$seconds)).':'.intval(date('s', $seconds)).'.'.substr(date('u', $seconds),0,3);
	$time .= intval(date('i',$seconds)).':'.intval(date('s', $seconds)).'.'.udate('u', $seconds);
	$formatted_time = date($format, $time)
	return date($format, $time);
}
*/
//http://php.net/manual/en/datetime.format.php#115734
function udate($strFormat = 'u', $uTimeStamp = null)
{
    // If the time wasn't provided then fill it in
    if (is_null($uTimeStamp))
    {
        $uTimeStamp = microtime(true);
    }

    // Round the time down to the second
    $dtTimeStamp = floor($uTimeStamp);

    // Determine the millisecond value
    $intMilliseconds = round(($uTimeStamp - $dtTimeStamp) * 1000);
    // Format the milliseconds as a 6 character string
    $strMilliseconds = str_pad($intMilliseconds, 3, '0', STR_PAD_LEFT);

    // Replace the milliseconds in the date format string
    // Then use the date function to process the rest of the string
    return date(preg_replace('`(?<!\\\\)u`', $strMilliseconds, $strFormat), $dtTimeStamp);
}
function convert_date_to_HHMMSSmm_old($strFormat = 'u', $uTimeStamp) {
	
	list($usec, $sec) = explode(' ', microtime($uTimeStamp)); //split the microtime on space
                                               //with two tokens $usec and $sec
    // Round the time down to the second
    $dtTimeStamp = floor($usec);

    // Determine the millisecond value
    $intMilliseconds = round(($uTimeStamp - $dtTimeStamp) * 1000);
    // Format the milliseconds as a 6 character string
    $strMilliseconds = str_pad($intMilliseconds, 3, '0', STR_PAD_LEFT);

	$strMilliseconds = str_replace("0.", ".", $strMilliseconds);     //remove the leading '0.' from usec

	return date('H:i:s', $sec) . $strMilliseconds;       //appends the decimal portion of seconds
}
function convert_date_to_HHMMSSmm($strFormat = 'u', $uTimeStamp) {
	
	$date_ref = new DateTime('2001-01-01');
	$commTime = new DateTime('2001-01-01');
	$commTime->setTime(0, 0, $uTimeStamp);

	$uDiff = abs($commTime->format('u')-$date_ref->format('u')) / (1000 * 1000);
	$diff = $commTime->diff($date_ref);
	$strMilliseconds = $diff->format('%s')-$uDiff;
	return $commTime->format('H:i:s') . $strMilliseconds;       //appends the decimal portion of seconds
}

function convert_date_to_HHMMSSmm2($strFormat = 'u', $uTimeStamp) {
	$commTime = new DateTime('2001-01-01');
	$commTime->setTime(0, 0, $uTimeStamp);
	// DEBUG 		return $commTime->format('H:i:s') . '.' . udate('u', $uTimeStamp) ."\r\n". '[debug: uTimeStamp = '.$uTimeStamp.', commTime formatHis = '.$commTime->format('H:i:s').', commTime date_format = '.$commTime->format('Y-m-d H:i:s').']'."\r\n";  
	return $commTime->format('H:i:s') . '.' . udate('u', $uTimeStamp);
}



$contador_subtitulos = 0;
if( isset( $GLOBALS['comments'] ) && is_array( $comments ) ) {
	foreach( $comments as $key => $comment ):
	//$user = Commenters::getCommenter( $comment->userId );
	$user = Commenters::getMember( $comment->userId );


				// 01:27.311 --> 01:30.866
//				$output .= convert_date_to_HHMMSS($comment->time_in) . " --> ". convert_date_to_HHMMSS($comment->time_out);	
				// debug times
				//$output .= convert_date_to_HHMMSS($comment->time_in) . " (time_in: ".$comment->time_in." )". " --> ". convert_date_to_HHMMSS($comment->time_out) . " (time_in: ".$comment->time_out." )";	
				
				//$output .= 'convert_date_to_HHMMSSmm: '. convert_date_to_HHMMSSmm('u', $comment->time_in) . " (time_in: ".$comment->time_in." )". " --> ". convert_date_to_HHMMSSmm('u', $comment->time_out) . " (time_in: ".$comment->time_out." )";	
				//$output .= "\r\n";

				// DEBUG $output .= 'convert_date_to_HHMMSSmm2: '. convert_date_to_HHMMSSmm2('u', $comment->time_in) . " (time_in: ".$comment->time_in." )"."\r\n"." --> ". convert_date_to_HHMMSSmm2('u', $comment->time_out) . " (time_in: ".$comment->time_out." )";	

				// DeBUG $output .= 'udate                   : '. udate('u', $comment->time_in) . " (time_in: ".$comment->time_in." )". " --> ". udate('u', $comment->time_out) . " (time_in: ".$comment->time_out." )";	
				// DEBUG $output .= "\r\n";
				// DEBUG $output .= "\r\n";

//$subtitles_formato = "vtt" or "srt";
				$contador_subtitulos += 1;
				if ($fichero_formatname=="SRT") {
					// mejor con fichero_formatname, para independizar de $_GET (para VTT podría hacerlo también, pero no hace falta y mejor no depender de tolerancias)
					$output .= $contador_subtitulos;
					$output .= "\r\n";	// VTT permite; SRT obliga
				}
				$output .= convert_date_to_HHMMSSmm2('u', $comment->time_in) ." --> ". convert_date_to_HHMMSSmm2('u', $comment->time_out);	
				$output .= "\r\n";

	//echo ('<script>console.log("debug comment: ' . $comment->debug . ', debug user: ' . $user->debug . '")</script>');	

				
				/*
				//echo $comment->comment;
				//echo rawurlencode($comment->comment);
				//echo htmlentities(rawurldecode($comment->comment), ENT_QUOTES, "UTF-8");
				//$output .= html_entity_decode($comment->comment);
				//$output .= html_entity_decode(rawurldecode($comment->comment), ENT_QUOTES, "UTF-8");
				$output .= 'COMMENT LITERAL -> '. $comment->comment;					
				$output .= "\r\n";
				$output .= 'html_entity_decode -> '. html_entity_decode( $comment->comment, ENT_QUOTES, "UTF-8");				
				$output .= "\r\n";
				$output .= 'html_entity_decode (NO UTF-8) -> '. html_entity_decode( $comment->comment, ENT_QUOTES);				
				$output .= "\r\n";
				$output .= 'DOBLE html_entity_decode (NO UTF-8) -> '. html_entity_decode( html_entity_decode( removeslashes($comment->comment), ENT_QUOTES), ENT_QUOTES);				
				$output .= "\r\n";
				//$output .= 'cambia_single_quotes -> '. cambia_single_quotes($comment->comment);				
				$output .= "\r\n";
				$output .= 'clean_numeric_utf8 -> '. clean_numeric_utf8($comment->comment);				
				$output .= "\r\n";
				$output .= 'removeslashes -> '. removeslashes($comment->comment);				
				$output .= "\r\n";
				$output .= 'htmlspecialchars_decode -> '. htmlspecialchars_decode($comment->comment);				
				$output .= "\r\n";

				$output .= "\r\n";
				$output .= "\r\n";
				*/


				//$output .= html_entity_decode( html_entity_decode( removeslashes($comment->comment), ENT_QUOTES), ENT_QUOTES);		
				// reemplazo <br /> o <br> por CR (echo "\r\n")
				$output_conBR = html_entity_decode( html_entity_decode( removeslashes($comment->comment), ENT_QUOTES), ENT_QUOTES);	// ruso no imprime en cirilico
				//$output_conBR = html_entity_decode( html_entity_decode($comment->comment));
				$reemplaza = array("<br>", "<br/>", "<br />");
				$output .= str_ireplace($reemplaza, "\r", $output_conBR);
						
				$output .= "\r\n";
				$output .= "\r\n";
	endforeach;
//} else { echo ('<script>console.log("debug comment: ' . $comment->debug . ', debug user: ' . $user->debug . '")</script>'); }
} else { 
	echo ('<script>console.log("comment-box debug no encuentra comentarios!! ")</script>'); 
	$output .= "Subtitles were not found";
}
//endif;
 ?>