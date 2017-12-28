<?php
// The back-end then will determine if the username is available or not,
// and finally returns a JSON { "valid": true } or { "valid": false }
// The code bellow demonstrates a simple back-end written in PHP

// Get the username from request
		$yo_vimeoUserName = 'user4845041';
		$yo_videoId = '73589408';
$new_video_id = $_POST['new_video_id'];
$isAvailable = false;
// Change this to your username to load in your videos
//$vimeo_user_name = ($_GET['user']) ? $_GET['user'] : 'brad';
$vimeo_user_name = $yo_vimeoUserName;

// API endpoint
//var cb = 'http://vimeo.com/api/v2/video/' + _vid_id + '.json?callback=showThumbs';
/*
$api_endpoint_original = 'http://vimeo.com/api/v2/' . $vimeo_user_name;
$api_endpoint = 'http://vimeo.com/api/v2/video/' . $new_video_id;
*/
$oembed_endpoint = 'http://vimeo.com/api/oembed';
$video_url = 'http://vimeo.com/' . $new_video_id;

// Create the URLs
$json_url = $oembed_endpoint . '.json?url=' . rawurlencode($video_url) . '&width=640';
$xml_url = $oembed_endpoint . '.xml?url=' . rawurlencode($video_url) . '&width=640';

// Curl helper function
function curl_get($url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	$return = curl_exec($curl);
	curl_close($curl);
	return $return;
}
/*
// Load the user info and clips
$user = simplexml_load_string(curl_get($api_endpoint_original . '/info.xml'));
$videos = simplexml_load_string(curl_get($api_endpoint_original . '/videos.xml'));
//$video_infoxml = simplexml_load_string(curl_get($api_endpoint . '/info.xml'));
$video_videosxml = simplexml_load_string(curl_get($api_endpoint . '/videos.xml'));
*/

// Load in the oEmbed XML
$oembed = simplexml_load_string(curl_get($xml_url));
// retornos de Vimeo:
$vimeo_title = $oembed->title;
$vimeo_videoid = $oembed->video_id;
$vimeo_authorurl = $oembed->author_url;			// https://vimeo.com/staff
$vimeo_authorname = $oembed->author_name;		// Vimeo Staff
$vimeo_html = $oembed->html;
$vimeo_duration = $oembed->duration;
$vimeo_width = $oembed->width;
$vimeo_height = $oembed->height;
$vimeo_thumburl = $oembed->thumbnail_url;
$vimeo_thumbwidth = $oembed->thumbnail_width;
$vimeo_thumbheight = $oembed->thumbnail_height;
	if ($vimeo_videoid == $new_video_id) {
		$isAvailable = true; // or false	
	}

/*
foreach ($videos->video as $video):
	$video_url = $video->url;
	$video_thumbmed = $video->thumbnail_medium;
	$video_id_vimeo = $video->id;
	if ($video_id_vimeo == $new_video_id) {
		$isAvailable = true; // or false	
	}
endforeach;
*/
/*
foreach ($video_videosxml->video as $video): 
	$video_url = $video->url;
	$video_thumbmed = $video->thumbnail_medium;
	$isAvailable = true; // or false
endforeach;
*/

// Finally, return a JSON
echo json_encode(array(
    'valid' => $isAvailable,
));
?>