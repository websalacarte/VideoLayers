<?php require_once('includes/defines.php'); ?>
<?php require_once ('mysql/models/comments.php'); ?>
<?php require_once('includes/ficheros.php'); ?>

<?php include_once("scripts/check_user.php"); ?>
<?php 

/**** check_user devuelve:

$user_is_logged = false;
$log_user_id = "";
$log_uname = "";
$log_pass = "";
*/
?>


<?php 
	/*
	if ( !isset($userId) ) {
		$userId = 2; 
		$userName = $usernames[$userId - 1]; //la nostra 'cookie' amb dades del login. 
		$usuario_inicial = 'logado por defecto, usuario: ' . $userId . ', nombre: ' . $userName;
	}
	else {
		$usuario_inicial = 'usuario actual es: ' .$userId . ', nombre: ' . $userName;
	}
	*/
?>
<?php $videoId=34;	//73589408 Kevin Spacey (old: 22647358) // el id en nuestra BD ?>
<?php $platform_videoId='CKDfMTvmadU'; // el id en YT-Vimeo ?>
<?php $videoId_subt=24819045;	 ?>
<?php $page_id=40; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Exemple personatge</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<?php include_once("head_common-youtube.php"); ?>

<link rel="stylesheet" href="css/nouislider.min.css">
<link rel="stylesheet" href="css/nouislider.pips.css">
<link rel="stylesheet" href="css/nouislider.tooltips.css">
<style>
	#area_comentarios .wrapper {
	    width: 24%;
	}
	.clock-slider {
	    position: relative;
	    width: 90% !important;
	    padding: 20px 60px 50px 30px;
	    margin: 20px 20px 50px !important;
	}
	.clock-slider-val {
	    font: 400 12px Arial;
	    color: #888;
	    display: block;
	    margin: 20px 20px !important;
	    text-align: left;
	}
	.clock-slider-val.start:before {
	    content: "In: ";
	    font: 700 12px Arial;
	}
	.clock-slider-val.finish:before {
	    content: "Out: ";
	    font: 700 12px Arial;
	}
	.noUi-pips-horizontal {
	    padding: 10px 0 !important;
	}
	.noUi-value-horizontal {
	    margin-left: -20px !important;
	    padding-top: 20px !important;
	}
	/* handles: reduzco de 34/17 a 24/12; */
	.noUi-horizontal .noUi-handle {
	    width: 20px;
	    height: 28px;
	    left: -10px;
	    top: -6px;
	}

	.noUi-handle:after, .noUi-handle:before {
	    content: "";
	    display: block;
	    position: absolute;
	    height: 14px;
	    width: 1px;
	    background: #E8E7E6;
	    left: 7px;	/* era 14 */
	    top: 6px;
	}
	.noUi-handle:after {
	    left: 11px;
	}
	/* handles tooltips reposiciono */
	.noUi-tooltip {padding: 5px !important;}
	.noUi-horizontal .noUi-handle-upper .noUi-tooltip {
	    bottom: -25px;
	}
	.noUi-horizontal .noUi-handle-lower .noUi-tooltip {
	    top: -25px;
	}
	.noUi-horizontal .noUi-handle {
	background: rgba(105, 137, 204, 0.3);	/*rgba(63, 184, 175, 0.15)*/
	cursor: pointer;
	}
	.noUi-connect {
	    background: #6989cc;
	}

	/* tabla inputs */
	.inputs_headers {margin-left: 20px;}
	.inputs_headers .sep {font-size: 12px; padding: 5px;}
	.inputs_headers .val, .inputs_headers .input_mins, .inputs_headers .input_secs, .inputs_headers .input_milis {width: 35px;} 
	.inputs_headers .val {font-size: 10px; font-weight: 300; color: #888;}
	.inputs_headers .input_mins, .inputs_headers .input_secs, .inputs_headers .input_milis {
	    font: 500 12px Arial;
	    color: #000;
	    text-align: center;
	}
	.inputs_headers .clock_label {font-size: 10px; color: #888; font-weight: 700; text-align: left; padding-right: 10px;} 
	.inputs_headers .inicio, .noUi-horizontal .noUi-handle-lower .noUi-tooltip.active {color: #084;}
	.inputs_headers .fin, .noUi-horizontal .noUi-handle-upper .noUi-tooltip.active {color: #800;}
	.check_autorefresh {
	    text-align: left;
	    font-size: 10px;
	    margin-left: 20px !important;
	}    
	.manual_edit {text-align: left; color: black; font-weight: normal; margin: 20px auto 5px 20px !important;}

	/***************************************************************************************************/
	.who-says {display: none;}
	#area_comentarios .comment-post-btn-wrapper {
		position: relative;
	    margin: 10px 10%;
	    /* top: 5px; */
	    /* left: auto; */
	    /* right: 10px; */
	    width: 80%;
	}    
	.slr, .marge_temps {display: none !important;}
	#area_comentarios .edit_post .btn_clock i.fa {font-size: 24px;}
	/* btn_clock-clock */
	#area_comentarios .btn_clock {
	    width: 98%;
	    margin: 5px;
	    float: none; 
	    height: 25px;
	}
	#area_comentarios .edit_post .btn_clock i.fa {
	    font-size: 24px;
	    text-align: right;
	    width: 100%;
	    padding-right: 39px;
	}
	#area_comentarios .comments-list {
	    min-height: 70px;
	}
	#area_comentarios .comments-list .btn_clock {
	    margin: 25px 5px 5px 5px;	/* para dejar espacio al bot�n delete, comments-list porque s�lo es en los nuevos subt�tulos */
	}

	.btn_clock i.isvisible{color: gray;}
	.caja_comentarios h6 {text-align: left;}
	.#area_comentarios .comment-wrapper {border: none;}
	.inputs_headers td, .inputs_headers th {border-color: #A00;}
	.new_subtitles_header {
	    margin-left: 5px !important;
	    /* border-bottom: 1px solid #e1e1e1; */
	    text-align: left; 
	    font-family: Lato, Arial, sans-serif;
	    color: #F0A108;
	    font-weight: 400;
	    font-size: 15px;
	}
	#area_comentarios .comment-insert textarea.comment-insert-text {
		font-size: 18px;
	}
	#area_comentarios .ltr_text textarea.comment-insert-text {
		/*direction: rtl;*/ 
		unicode-bidi: bidi-override; 
		font-size: 24px;
		text-align: right;
	}
	#area_comentarios ul.comments-holder-ul li.comment-holder .comment-text p {margin: 10px; font-size: 18px;}
	#area_comentarios .ltr_text ul.comments-holder-ul li.comment-holder .comment-text p {/*direction: rtl;*/ unicode-bidi: bidi-override; text-align: right; font-size: 24px;}
	.container {
	    width: 100%;
	    }
	body #area_comentarios {width: 100%;}
	#my_video {text-align: left; width: 100%;}
	#my_video_iframe {text-align: left; width: 60%; display: inline-block; vertical-align: top;margin-left: 0;}
	#my_video_edit {text-align: center; width: 30%; display: inline-block;vertical-align: top;margin: 0 30px;}
	#my_video_edit #idioma, #my_video_iframe #my_video_playingcontrols {margin: 10px auto 30px 0;}
	#my_video_edit h6.actions {
		margin: 10px 10px 10px 0;
		font-family: Lato, Arial, sans-serif;
	    color: #F0A108;
	    font-weight: 400;
	    font-size: 18px;
	    text-align: left;
	}
	#my_video_edit #save_file {text-align: left;}



	table.dwnld_subt {   text-align: left; font-size: 14px;}
	table.dwnld_subt tr {border: 1px solid #ccc;}
	table.dwnld_subt td {border: 1px solid #ddd; padding: 10px; background-color: #eee;}
	table.dwnld_subt td a {padding: 10px;}
	table.dwnld_subt tr th {background-color: #ddd; text-align: center;}
	table.dwnld_subt td a i.fa {padding-right: 10px;}

	#area_comentarios .comment-insert .comment-insert-container {min-height: 65px !important;}
	#area_comentarios textarea.comment-insert-text {background-color: #FFFFE0;}


	/* zoom */
	.sp-zoom {
	    width:200px;
	    height:22px;
	    font-family:Arial;
	    vertical-align: middle;
	}
	.sp-minus {
	    width:20px;
	    height:20px;
	    border:1px solid #e1e1e1;
	    float:left;
	    text-align:center;
	    margin-left: 20px !important;
	}
	.sp-input {
	    width:20px;
	    height:20px;
	    border:1px solid #e1e1e1;
	    border-left:0px solid black;
	    float:left;
	}
	.sp-plus {
	    width:20px;
	    height:20px;
	    border:1px solid #e1e1e1;
	    border-left:0px solid #e1e1e1;
	    float:left;
	    text-align:center;
	}
	.sp-input input {
	    width:20px;
	    height:14px;
	    text-align:center;
	    font-family: Arial;    
	    font-size: 12px;
	    border: none;
	}
	.sp-input input:focus {
	    border:1px solid #e1e1e1;
	    border: none;
	}
	.sp-minus a, .sp-plus a {
	    display: block;
	    width: 100%;
	    height: 100%;
	    padding-top: 5px;
	}
	.sp-label {margin-top: 20px !important;}
	.slider_zoom_label {float: left;text-align: left; font-size: 10px; color: black; font-weight: normal; line-height: 18px; margin-left: 20px !important;}


	#seeks button {
	    background: #e9e9e9;
	    cursor: pointer;
	    border: 1px solid #ddd;
	    padding: 2px 13px;
	    -webkit-border-radius: 3px;
	    -moz-border-radius: 3px;
	    margin: 0;
	    cursor: pointer;
	}

	#my_video_playingcontrols #seeks {float: left; margin-left: 0;}
	#my_video_playingcontrols p {float: left; margin-left: 20px;}
	/*.header {display: none;}*/
</style>

<script>
	var tiempo_actual_play = 0;	// defino global. Informo durante control reproducci�n.
	var last_refresh_time = 0;
	var video_id_actual = <?php echo $videoId; ?>;
	var duracion_max_video = 294;	//4*60 + 54;
	var video_duration = duracion_max_video;
	var tiempo_slr_cajas = [];
	var coger_tiempo_slr = [];
	var coger_tiempo_slr_editpost = [];

	var tiempo_slr_cajas_post = [[]];
	var coger_tiempo_slr_post = [[]];	// dejar� de usarlo. S�lo necesito el post.
	var coger_tiempo_slr_editpost = [];	// lo inicializar� 
	var tiempo_slr_cajas_post = [];
	for(var x = 0; x < 100; x++){
	    tiempo_slr_cajas_post[x, 'min'] = [];    
	    tiempo_slr_cajas_post[x, 'max'] = [];
	    coger_tiempo_slr_post[x] = [];    
	    for(var y = 0; y < 100; y++){ 
	        tiempo_slr_cajas_post[x, 'min'][y] = false;    
	        tiempo_slr_cajas_post[x, 'max'][y] = false;    
	        coger_tiempo_slr_post[x][y] = false;    
	    }    
	}
	var check_autorefresh =[[]];
</script>

<script>
  // funcions slider
  $(function() {
	
	//$('.slr').slider(); // inicializaci�n bruta, porque al pasar de id's a classes no se inicializa bien.
	
    $( ".slr" ).slider({
      range: true,
      min: 0,
      max: duracion_max_video,
      //values: [ video_id_actual, video_id_actual+5 ],
      values: [ tiempo_actual_play, tiempo_actual_play+5 ],
      slide: function( event, ui ) {
        //$( "#amount_1" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		 
		//var sl_0 = $( "#slr_1" ).slider( "values", 0 );
		var sl_0 = $( this ).slider( "values", 0 );
		var sl_1 = $( this ).slider( "values", 1 );
		
		var min_ini = Math.floor( sl_0 / 60 ) < 10 ? "0"+Math.floor( sl_0 / 60 ) : Math.floor( sl_0 / 60 );
		var seg_ini = Math.floor( sl_0 % 60 ) < 10 ? "0"+Math.floor( sl_0 % 60 ) : Math.floor( sl_0 % 60 );
		var min_fin = Math.floor( sl_1 / 60 ) < 10 ? "0"+Math.floor( sl_1 / 60 ) : Math.floor( sl_1 / 60 );
		var seg_fin = Math.floor( sl_1 % 60 ) < 10 ? "0"+Math.floor( sl_1 % 60 ) : Math.floor( sl_1 % 60 );
		
		//$( "#amount_1" ).val( "De: " + min_ini + ':' + seg_ini + " a: " + min_fin + ':' + seg_fin );
		$( this ).parent().find('.amount').val( "De: " + min_ini + ':' + seg_ini + " a: " + min_fin + ':' + seg_fin );
		$( this ).parent().find( '.btn_slr' ).addClass( 'btn_set_pending' );
		// variable para indicar al bot�n 'POST' de esta caja que el tiempo correcto es el del slider.
		coger_tiempo_slr[ $( this ).parent().parent().find('id') ] = true;
      }
    });
	  
	//inicializo valores
	var sl_0 = $( ".slr" ).slider( "values", 0 );	
	var sl_1 = $( ".slr" ).slider( "values", 1 );
	
	var min_ini = Math.floor( sl_0 / 60 ) < 10 ? "0"+Math.floor( sl_0 / 60 ) : Math.floor( sl_0 / 60 );
	var seg_ini = Math.floor( sl_0 % 60 ) < 10 ? "0"+Math.floor( sl_0 % 60 ) : Math.floor( sl_0 % 60 );
	var min_fin = Math.floor( sl_1 / 60 ) < 10 ? "0"+Math.floor( sl_1 / 60 ) : Math.floor( sl_1 / 60 );
	var seg_fin = Math.floor( sl_1 % 60 ) < 10 ? "0"+Math.floor( sl_1 % 60 ) : Math.floor( sl_1 % 60 );
	$( this ).parent().find('.amount').val( "De: " + min_ini + ':' + seg_ini + " a: " + min_fin + ':' + seg_fin );
	// inicializo coger_tiempo_slr[ ] a false (para que hacer 'POST' sin tocar 'SET' coja el valor de tiempo del video, no el del slider.
	
	$('.caja_comentarios').each( function() {
		var caja_tmp = $( this ).attr('id').split("_");
		coger_tiempo_slr[ caja_tmp[2] ] = false;
	});
	
  });
</script>
<script>
  
	$( document ).ready( function(){
	    $( ".btn_clock i" ).click( function(){	
			var caja_tmp = $( this ).parent().attr('id').split("_");
			var caja = caja_tmp[2];

			var edit_comm_id = caja_tmp[3];
				console.log('clicado btn_clock. caja: ' + caja + ', edit_comm_id: ' + edit_comm_id + ', caja_tmp length: ' + caja_tmp.length);
			
			// inicializamos, pero s�lo si no existe ya.
			if ( !(edit_comm_id in clockSlider[caja]) ) {
				// no existe a�n.

				console.log('on_btn_clock_click_nuevoslider, caja: '+caja+', comm: '+edit_comm_id+', -> inicializa_nuevo_slider');
				inicializa_nuevo_slider( caja, edit_comm_id );
				console.log('on_btn_clock_click_nuevoslider, -> bind_clock_inputs');
				bind_clock_inputs(caja, edit_comm_id);
			}
			else {
				// ya existe, 1o destroy para 2o inicializacion de nuevo (si no, no lo muestra)
				clockSlider[caja][edit_comm_id].noUiSlider.destroy();

				console.log('on_btn_clock_click_nuevoslider, caja: '+caja+', comm: '+edit_comm_id+', -> inicializa_nuevo_slider');
				inicializa_nuevo_slider( caja, edit_comm_id );
				console.log('on_btn_clock_click_nuevoslider, -> bind_clock_inputs');
				bind_clock_inputs(caja, edit_comm_id);

			}
			//	$( ".slr" ).slider( "option", "values", [ tiempo_actual_play, tiempo_actual_play + 5 ] );
			//	actualiza_slider( tiempo_actual_play, caja );

			var time_in = $( '#_' + edit_comm_id ).attr( 'data-timein' );
			var time_out = $( '#_' + edit_comm_id ).attr( 'data-timeout' );
			//console.log('refresh_slider_slots pre. edit_comm_id: '+edit_comm_id+', time_in: '+time_in+', time_out: '+time_out);
			refresh_slider_slots(caja, edit_comm_id, time_in, video_duration);
			//console.log('refresh_slider_slots post');
			clockSlider[caja][edit_comm_id].noUiSlider.set([time_in, time_out]);	
			//console.log('clockSlider noUiSlider set ok');
			

			if( caja_tmp.length == 3 ) {
				// es btn_clock de 'POST'
				$("#clock_slr_"+caja).toggle();
			} else {
				var edit_comm_id = caja_tmp[3];
				// es btn_clock de 'EDIT'
				$( "#clock_slr_" + caja + "_" + edit_comm_id ).toggle();
				console.log('desde index, toggle #clock_slr_'+caja+'_'+edit_comm_id);
				if ( $( "#clock_slr_" + caja + "_" + edit_comm_id ).is(":visible") ) {
					// a�ado class 'isvisible' para estilar timeBtn y cambiar su tooltip title
					$( "#btn_clock_" + caja + "_" + edit_comm_id ).attr("title", "Hide the timing area for this comment");
					$( "#btn_clock_" + caja + "_" + edit_comm_id + " i ").addClass("isvisible");
				}
				else{
					// quito class 'isvisible' para estilar timeBtn y cambiar su tooltip title
					$( "#btn_clock_" + caja + "_" + edit_comm_id ).attr("title", "Show the timing area for this comment");
					$( "#btn_clock_" + caja + "_" + edit_comm_id + " i ").removeClass("isvisible");
				}
			}
		});
		
	});

</script>
</head>
<body>
<div class="main_container">
<?php include_once("header_template.php"); ?>
	 
	<!-- CONTAINER -->
	<h2>Exemple personatge - <span>JosepFB</span></h2>
	<div class="container">
		<div id="form">
		   
		   
			<!--
			<div id="idioma" class="subs_selector">
				<div id="subchooser" class="subchooser">
					<form name="subselect" class="form" action="">            
						<p><label for="subs">Choose the subjects you are interested in:</label>
						
						<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_1" VALUE="1" onchange="show_comments('subs_1')" checked ><label for="subs_1">descripció personatge</label></div>
						<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_2" VALUE="2" onchange="show_comments('subs_2')" checked ><label for="subs_2">altres personatges</label></div>
						<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_3" VALUE="3" onchange="show_comments('subs_3')" checked ><label for="subs_3">actuació actriu</label></div>
						<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_4" VALUE="4" onchange="show_comments('subs_4')" checked ><label for="subs_4">props i direcció</label></div>
						
						<BR>

					</p>
					</form>   
				</div>
			</div>
			-->

			<!-- https://vimeo.com/73589408 Kevin Spacey (old: 22647358) -->
			
			
			<div id="my_video">
				<div id="player_yt"></div>
				<div id="my_video_playingcontrols">
					<!--<button>Play</button> <button>Pause</button>-->
					<div id="seeks">
						<button class="seek">Enrera -5s <input type="hidden" value="5" size="3" maxlength="3" /></button>
						<!--<button class="seek">Back -10s<input type="hidden" value="10" size="3" maxlength="3" /></button>-->
					</div>
					<p>Video status: <span class="status">...</span></p>
				</div>

			
				<div id="my_video_edit">

					<div id="idioma" class="subs_selector">
						<div id="subchooser" class="subchooser">
							<form name="subselect" class="form" action="">            
								<h6 class="actions">Escull els temes que t'interessen:</h6>
								
								<!--<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_0" VALUE="none" onchange="show_comments('subs_none')" ><label for="subs_none">sin cajas</label></div>-->
								<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_1" VALUE="1" onchange="show_comments('subs_1')" checked ><label for="subs_1">Projecte i col·laboració</label></div>
								<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_2" VALUE="2" onchange="show_comments('subs_2')" checked ><label for="subs_2">Rodatge</label></div>
								<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_3" VALUE="3" onchange="show_comments('subs_3')" checked ><label for="subs_3">Persones</label></div>
								<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_4" VALUE="4" onchange="show_comments('subs_4')" checked ><label for="subs_4">Història</label></div>
								
							</form>   
						</div>
					</div>

				</div>
			</div>	
		
			<div class="clr"></div>
			<style>
				#area_comentarios .comment-wrapper {
					border: 0 !important;
				}
				#area_comentarios .caja_comentarios .nav-tabs {
				    border-bottom: 0;
				}
				#area_comentarios .caja_comentarios .nav-tabs > li {
				  width: 50%;
				  border-bottom: 1px solid #e1e1e1;
				}
				#area_comentarios .caja_comentarios .nav-tabs > li.active {
				  border-bottom: 0 !important;
				}
				#area_comentarios .caja_comentarios .nav-tabs > li > a {
				  border-radius: 4px 4px 0 0 ;
				  text-align: center;
				}
				#area_comentarios .caja_comentarios .nav-tabs>li.active>a, #area_comentarios .caja_comentarios .nav-tabs>li.active>a:hover, #area_comentarios .caja_comentarios .nav-tabs>li.active>a:focus {
				    
				  background-color: transparent;
				}

				#area_comentarios .caja_comentarios .nav-tabs > li a i.fa {
				  font-size: 1.5em;
				  padding: 0.25em 0.5em;
				}
				#area_comentarios .caja_comentarios .nav-tabs > li a span {
					display: none;
				}
				#area_comentarios .caja_comentarios .tab-content {
				  padding : 5px 15px;
				  border: 1px solid #e1e1e1;
				  border-top: 0;
				}
				#area_comentarios ul.comments-holder-ul li.comment-holder {border: 1px solid #eee;}
				#area_comentarios .comment-insert {
					border: 0;
				}
				#area_comentarios .container {
					min-height: 250px;
				}
				.debug {display: none;}
			</style>
			
				<script>
			      	// This code loads the YT IFrame Player API code asynchronously.
					  	var sigue_onPlaying;
					  	var sigue_refreshing_slider_slots = [];

						var tag = document.createElement('script');

						tag.src = "//www.youtube.com/iframe_api";
						var firstScriptTag = document.getElementsByTagName('script')[0];
						firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

						// This function creates an <iframe> (and YouTube player)
						//    after the YT API code downloads.

						var hay_boton_status = ($('body').find('status')) ? true : false;
						if (hay_boton_status) {
						  	var status = $('.status');
							var seek_buttons = document.getElementById('seeks');
							var seekBtn = seek_buttons.querySelector('.seek');
						}
						var player;
						var time;
						var done = false;
						var revisa_comments;
						function onYouTubeIframeAPIReady() {
							console.log('youtube-mobile.js, onYouTubeIframeAPIReady');
							player = new YT.Player('player_yt', {
							  height: '390',									// pendiente obtenci�n desde API youtube durante validaci�n
							  width: '640',
							  videoId: 'CKDfMTvmadU',
							  events: {
							    'onReady': onPlayerReady,
							    'onStateChange': onPlayerStateChange
							  }
							});
						}

						/*
						window.requestAnimFrame = (function(){
							return  window.requestAnimationFrame		||
									window.webkitRequestAnimationFrame	||
									window.mozRequestAnimationFrame 	||
									function( callback ){
										window.setTimeout(callback, 1000 / 60);
									}
						});
						*/

						// RAF polyfill by Paul Irish.
						    var lastTime = 0;
						    var vendors = ['ms', 'moz', 'webkit', 'o'];
						    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
						        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
						        window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame'] 
						                                   || window[vendors[x]+'CancelRequestAnimationFrame'];
						    }
						 
						    if (!window.requestAnimationFrame)
						        window.requestAnimationFrame = function(callback, element) {
						            var currTime = new Date().getTime();
						            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
						            var id = window.setTimeout(function() { callback(currTime + timeToCall); }, 
						              timeToCall);
						            lastTime = currTime + timeToCall;
						            return id;
						        };
						 
						    if (!window.cancelAnimationFrame)
						        window.cancelAnimationFrame = function(id) {
						            clearTimeout(id);
						        };
					    // fin RAF polyfill Paul Irish.

						function onPlaying() {
							console.log('onplaying #586');
							time = player.getCurrentTime();
							var time_readable = Math.floor(time * 100) / 100;
							revisa_comments = setInterval(function(){check_comments()},1000 / 30);	
							//revisa_comments = window.requestAnimationFrame(function(){check_comments()});	
							if (hay_boton_status) $('.status').text(time_readable + 's played');
							video_duration = player.getDuration();
							//requestAnimFrame(onPlaying);
							//sigue_onPlaying = setInterval(function(){onPlaying()},1000);	

								if (isDefined(refresh_slider_slots)) {
									curr_time_value = player.getCurrentTime();
					                video_duration = player.getDuration();
									for (var i_cajita = 1; i_cajita <= num_cajas; i_cajita++){
										var aux_cajita = i_cajita;	// por delayed, i_cajita==5 !
										console.log('sigue_refreshing con caja: '+i_cajita+' (num_cajas: '+num_cajas+'), curr_time_value: '+curr_time_value);
										//sigue_refreshing_slider_slots[i_cajita] = setInterval(refresh_slider_slots(i_cajita, 0, curr_time_value, video_duration),1000);
										
										sigue_refreshing_slider_slots[aux_cajita] = setInterval(refresh_slider(aux_cajita, 0),1000 / 30);
										//sigue_refreshing_slider_slots[aux_cajita] = window.requestAnimationFrame(function(){refresh_slider_slots(aux_cajita, 0, curr_time_value, video_duration)});
									}
									//sigue_refreshing_slider_slots = setInterval(function(){refresh_slider_slots()},1000 / 60);
								}

						}
						function check_comments() {
							var time = player.getCurrentTime();
							tiempo_actual_play = time;

							//llamo a refresh cada segundo, no m?
							var tiempo_desde_last_refresh = 0;
							tiempo_desde_last_refresh = time - last_refresh_time;
							if (Math.abs(tiempo_desde_last_refresh) > 1) {
								comments_refresh(time);
							}

						}
						// The YT API will call this function when the video player is ready.
						function onPlayerReady(event) {
							console.log('youtube-mobile.js, onPlayerReady');
							var time = player.getCurrentTime();
							tiempo_actual_play = time;
							var time_readable = Math.floor(time * 100) / 100;
							if (hay_boton_status) $('.status').text(time_readable + 's played');
							var tiempo_desde_last_refresh = 0;
							tiempo_desde_last_refresh = time - last_refresh_time;
						}

						// The YT API calls this function when the player's state changes.
							//  The function indicates that when playing a video (state=1),
							//  the player should play for six seconds and then stop.
						function onPlayerStateChange(event) {
							console.log('onPlayerStateChange, evento: '+event.data);
							if (event.data == YT.PlayerState.PLAYING && !done) {
							//  setTimeout(stopVideo, 6000);
							//  done = true;
								console.log('onPlayerStateChange #626 playing');
								//onPlaying();
								sigue_onPlaying = setInterval(function(){onPlaying()},1000 / 30);
								//sigue_onPlaying = window.requestAnimationFrame(function(){onPlaying()});
								/*
								if (isDefined(refresh_slider_slots)) {
									curr_time_value = player.getCurrentTime();
					                video_duration = player.getDuration();
									for (var i_cajita = 1; i_cajita <= num_cajas; i_cajita++){
										console.log('sigue_refreshing con caja: '+i_cajita+' (num_cajas: '+num_cajas+'), curr_time_value: '+curr_time_value);
										//sigue_refreshing_slider_slots[i_cajita] = setInterval(refresh_slider_slots(i_cajita, 0, curr_time_value, video_duration),1000);
										sigue_refreshing_slider_slots[i_cajita] = setInterval(refresh_slider(i_cajita, 0),1000);
									}
									//sigue_refreshing_slider_slots = setInterval(function(){refresh_slider_slots()},1000 / 60);
								}
								*/

							} 
							if (event.data == YT.PlayerState.PAUSED && !done) {
								console.log('onPlayerStateChange pausado');
								clearInterval(revisa_comments);
								//window.cancelAnimationFrame(revisa_comments);
								clearInterval(sigue_onPlaying);
								//window.cancelAnimationFrame(sigue_onPlaying);
								if (sigue_refreshing_slider_slots) {
									for (var i_cajita = 1; i_cajita <= num_cajas; i_cajita++){
										var aux_cajita = i_cajita;	// por delayed, i_cajita==5 !
										clearInterval(sigue_refreshing_slider_slots[aux_cajita]);
										//window.cancelAnimationFrame(sigue_refreshing_slider_slots[i_cajita]);
									}
								}
							}
						}
						function stopVideo() {
							player.stopVideo();
								clearInterval(sigue_onPlaying);
								if (sigue_refreshing_slider_slots) {
									for (var i_cajita = 1; i_cajita <= num_cajas; i_cajita++){
										var aux_cajita = i_cajita;	// por delayed, i_cajita==5 !
										clearInterval(sigue_refreshing_slider_slots[aux_cajita]);
										//window.cancelAnimationFrame(sigue_refreshing_slider_slots[i_cajita]);
									}
								}
						}

						function isDefined(foo){
						    return foo !== undefined
						}


	  			</script>


			<div id="area_comentarios">
			
				<div id="comentarios_<?php echo $videoId; ?>_1" class="caja_comentarios wrapper">
					<div class="page-data">descripció personatge</div>				
					<div class="comment-wrapper container">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#llegir_1" data-toggle="tab"><i class="fa fa-eye"></i><span>Llegir</span></a></li>
							<li><a href="#crear_1" data-toggle="tab"><i class="fa fa-plus"></i><span>Crear</span></a></li>
						</ul>
						<div class="tab-content clearfix">
							<div class="tab-pane active" id="llegir_1">
								<h6 class="new_subtitles_header">Comentaris actuals:</h6>
								<div class="comments-list">
									<ul id="comments-holder-ul_<?php echo $videoId; ?>_1" class="comments-holder-ul">
										<?php //$comments = array("a", "b", "c", "d", "e"); ?>
										<?php //$comments = Comments::getComments(); ?>
										<?php 
											// 'ES': box_id: 1; 'EN': box_id: 2; 'FR': box_id: 3, 'RU': box_id: 4
											$comments = Comments::getCommentsVideoBox($videoId, 1); 								// tiene que ser el subtitulado?	--> match con Midity
										?>
										<?php 
											//$debug = json_encode( (array)$comments );
											//echo ('<!-- getCommentsVideoBox: inicio, debug comment: ' . $debug  . ' -->'); 
										?>	

										<?php require (INC . 'comment_box.php'); ?>
									</ul>
								</div>
							</div>
							<div class="tab-pane" id="crear_1">

								<!--<h3 class="comment-title">Introduce un nuevo comentario</h3>-->
						
								<h6 class="new_subtitles_header">Crea nou comentari:</h6>
								<div class="comment-insert">
									<!--<h3 class="who-says"><span>Says:</span> <?php echo $log_uname; //$userName; ?></h3>-->
									<div class="edit_post">
										<div id="btn_clock_1_0" class="btn_clock btn_clock_1">
											<i class="fa fa-clock-o"></i>
										</div>
										<div id="clock_slr_1_0" class="clock_slr" style="display: none;">
											<p class="marge_temps" style="display: none;">
												<label for="amount_1">Margen tiempo:</label>
												<input type="text" id="amount_1" class="amount" style="border:0; color:#f6931f; font-weight:bold;">
											</p>
											<div id="slr_1" class="slr slider-range" style="display: none;"></div>
											<!--
											<div class="comment-set-btn-wrapper">
												<div id="btn_slr_1" class="btn_slr ">Set</div>
											</div>
											-->


											<div id="clock-slider_1_0" class="clock-slider"></div>
											
											<span class="clock-slider-val start" id="event-start_1_0" style="display: none;">0</span>
											<span class="clock-slider-val finish" id="event-end_1_0" style="display: none;">0</span>
											
											<div class='check_autorefresh' id="check_autorefresh_1_0">
												<label for="autorefresh_checkbox_1_0">Auto-refresh slider?</label>
												<input type="checkbox" value="auto-refresh" name="autorefresh_checkbox" id="autorefresh_checkbox_1_0" checked="checked">
											</div>
											<!-- zoom -->
											<div class="sp-zoom" id="zoom_1_0">
											    <div class='sp-label'><h4 class='slider_zoom_label'>slider zoom:</h4></div>
											    <div class="sp-minus fff"> <a class="zoom_btn zoom_minus" href="#"><i class="fa fa-minus"></i></a>
											    </div>
											    <div class="sp-input">
											        <input type="text" class="zoom-input" value="3" />
											    </div>
											    <div class="sp-plus fff"> <a class="zoom_btn zoom_plus" href="#"><i class="fa fa-plus"></i></a>
											    </div>
											</div>

											<h4 class='manual_edit' id="manual_edit_1">Manual edit:</h4>
											<table class='inputs_headers' id="inputs_headers_1_0">
												<tr>
													<th><span class="clock_label">&nbsp;</span></th>
													<th><span class="val">min</span></th>
													<th><span id="sep1" class="sep">&nbsp;</span></th>
													<th><span class="val">sec</span></th>
													<th><span id="sep2" class="sep">&nbsp;</span></th>
													<th><span class="val">milisec</span></th>
												</tr>
												<tr id="inputs_start_1_0">
													<td><span class="clock_label inicio">In:</span></td>
													<td><input class="input_mins inicio" id="clock-slider-val-start-mins_1_0" value="00" /></td>
													<td><span class="sep inicio">:</span></td>
													<td><input class="input_secs inicio" id="clock-slider-val-start-secs_1_0" value="00" /></td>
													<td><span class="sep inicio">.</span></td>
													<td><input class="input_milis inicio" id="clock-slider-val-start-milis_1_0" value="000" /></td>
												</tr>
												<tr id="inputs_finish_1_0">	
													<td><span class="clock_label fin">Out:</span></td>
													<td><input class="input_mins fin" id="clock-slider-val-finish-mins_1_0" value="00" /></td>
													<td><span class="sep fin">:</span></td>
													<td><input class="input_secs fin" id="clock-slider-val-finish-secs_1_0" value="00" /></td>
													<td><span class="sep fin">.</span></td>
													<td><input class="input_milis fin" id="clock-slider-val-finish-milis_1_0" value="000" /></td>
												</tr>
											</table>


											<div class="clr"></div>
										</div>
									
									</div>
									
									<div id="comment-post-container" class="comment-insert-container">
										<textarea id="comment-post-text_<?php echo $videoId; ?>_1" class="comment-post-text comment-insert-text"></textarea>								<!-- tmb podria id="comment_post_text_(video)_(box)" pero no hace falta.-->	
										
									</div>	

									<div id="comment-post-btn_<?php echo $videoId; ?>_1" class="comment-post-btn_con_slr comment-post-btn-wrapper">Post
									</div>
									
								</div>
							</div>
						</div>
						
					</div>
				</div>
				
				<div id="comentarios_<?php echo $videoId; ?>_2" class="caja_comentarios wrapper">
					<div class="page-data">altres personatges</div>				
					<div class="comment-wrapper container">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#llegir_2" data-toggle="tab"><i class="fa fa-eye"></i><span>Llegir</span></a></li>
							<li><a href="#crear_2" data-toggle="tab"><i class="fa fa-plus"></i><span>Crear</span></a></li>
						</ul>
						<div class="tab-content clearfix">
							<div class="tab-pane active" id="llegir_2">
								<h6 class="new_subtitles_header">Comentaris actuals:</h6>
								<div class="comments-list">
									<ul id="comments-holder-ul_<?php echo $videoId; ?>_2" class="comments-holder-ul">
										<?php //$comments = array("a", "b", "c", "d", "e"); ?>
										<?php //$comments = Comments::getComments(); ?>
										<?php 
											// 'ES': box_id: 1; 'EN': box_id: 2; 'FR': box_id: 3, 'RU': box_id: 4
											$comments = Comments::getCommentsVideoBox($videoId, 2); 								// tiene que ser el subtitulado?	--> match con Midity
										?>
										<?php 
											//$debug = json_encode( (array)$comments );
											//echo ('<!-- getCommentsVideoBox: inicio, debug comment: ' . $debug  . ' -->'); 
										?>	

										<?php require (INC . 'comment_box.php'); ?>
									</ul>
								</div>
							</div>
							<div class="tab-pane" id="crear_2">

								<!--<h3 class="comment-title">Introduce un nuevo comentario</h3>-->
						
								<h6 class="new_subtitles_header">Crea nou comentari:</h6>
								<div class="comment-insert">
									<!--<h3 class="who-says"><span>Says:</span> <?php echo $log_uname; //$userName; ?></h3>-->
									<div class="edit_post">
										<div id="btn_clock_2_0" class="btn_clock btn_clock_2">
											<i class="fa fa-clock-o"></i>
										</div>
										<div id="clock_slr_2_0" class="clock_slr" style="display: none;">
											<p class="marge_temps" style="display: none;">
												<label for="amount_2">Margen tiempo:</label>
												<input type="text" id="amount_2" class="amount" style="border:0; color:#f6931f; font-weight:bold;">
											</p>
											<div id="slr_2" class="slr slider-range" style="display: none;"></div>
											<!--
											<div class="comment-set-btn-wrapper">
												<div id="btn_slr_2" class="btn_slr ">Set</div>
											</div>
											-->


											<div id="clock-slider_2_0" class="clock-slider"></div>
											
											<span class="clock-slider-val start" id="event-start_2_0" style="display: none;">0</span>
											<span class="clock-slider-val finish" id="event-end_2_0" style="display: none;">0</span>
											
											<div class='check_autorefresh' id="check_autorefresh_2_0">
												<label for="autorefresh_checkbox_2_0">Auto-refresh slider?</label>
												<input type="checkbox" value="auto-refresh" name="autorefresh_checkbox" id="autorefresh_checkbox_2_0" checked="checked">
											</div>
											<!-- zoom -->
											<div class="sp-zoom" id="zoom_2_0">
											    <div class='sp-label'><h4 class='slider_zoom_label'>slider zoom:</h4></div>
											    <div class="sp-minus fff"> <a class="zoom_btn zoom_minus" href="#"><i class="fa fa-minus"></i></a>
											    </div>
											    <div class="sp-input">
											        <input type="text" class="zoom-input" value="3" />
											    </div>
											    <div class="sp-plus fff"> <a class="zoom_btn zoom_plus" href="#"><i class="fa fa-plus"></i></a>
											    </div>
											</div>

											<h4 class='manual_edit' id="manual_edit_2">Manual edit:</h4>
											<table class='inputs_headers' id="inputs_headers_2_0">
												<tr>
													<th><span class="clock_label">&nbsp;</span></th>
													<th><span class="val">min</span></th>
													<th><span id="sep1" class="sep">&nbsp;</span></th>
													<th><span class="val">sec</span></th>
													<th><span id="sep2" class="sep">&nbsp;</span></th>
													<th><span class="val">milisec</span></th>
												</tr>
												<tr id="inputs_start_2_0">
													<td><span class="clock_label inicio">In:</span></td>
													<td><input class="input_mins inicio" id="clock-slider-val-start-mins_2_0" value="00" /></td>
													<td><span class="sep inicio">:</span></td>
													<td><input class="input_secs inicio" id="clock-slider-val-start-secs_2_0" value="00" /></td>
													<td><span class="sep inicio">.</span></td>
													<td><input class="input_milis inicio" id="clock-slider-val-start-milis_2_0" value="000" /></td>
												</tr>
												<tr id="inputs_finish_2_0">	
													<td><span class="clock_label fin">Out:</span></td>
													<td><input class="input_mins fin" id="clock-slider-val-finish-mins_2_0" value="00" /></td>
													<td><span class="sep fin">:</span></td>
													<td><input class="input_secs fin" id="clock-slider-val-finish-secs_2_0" value="00" /></td>
													<td><span class="sep fin">.</span></td>
													<td><input class="input_milis fin" id="clock-slider-val-finish-milis_2_0" value="000" /></td>
												</tr>
											</table>


											<div class="clr"></div>
										</div>
									
									</div>
									
									<div id="comment-post-container" class="comment-insert-container">
										<textarea id="comment-post-text_<?php echo $videoId; ?>_2" class="comment-post-text comment-insert-text"></textarea>								<!-- tmb podria id="comment_post_text_(video)_(box)" pero no hace falta.-->	
										
									</div>	

									<div id="comment-post-btn_<?php echo $videoId; ?>_2" class="comment-post-btn_con_slr comment-post-btn-wrapper">Post
									</div>
									
								</div>
							</div>
						</div>
						
					</div>
				</div>
				<div id="comentarios_<?php echo $videoId; ?>_3" class="caja_comentarios wrapper">
					<div class="page-data">actuació actriu</div>				
					<div class="comment-wrapper container">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#llegir_3" data-toggle="tab"><i class="fa fa-eye"></i><span>Llegir</span></a></li>
							<li><a href="#crear_3" data-toggle="tab"><i class="fa fa-plus"></i><span>Crear</span></a></li>
						</ul>
						<div class="tab-content clearfix">
							<div class="tab-pane active" id="llegir_3">
								<h6 class="new_subtitles_header">Comentaris actuals:</h6>
								<div class="comments-list">
									<ul id="comments-holder-ul_<?php echo $videoId; ?>_3" class="comments-holder-ul">
										<?php //$comments = array("a", "b", "c", "d", "e"); ?>
										<?php //$comments = Comments::getComments(); ?>
										<?php 
											// 'ES': box_id: 1; 'EN': box_id: 2; 'FR': box_id: 3, 'RU': box_id: 4
											$comments = Comments::getCommentsVideoBox($videoId, 3); 								// tiene que ser el subtitulado?	--> match con Midity
										?>
										<?php 
											//$debug = json_encode( (array)$comments );
											//echo ('<!-- getCommentsVideoBox: inicio, debug comment: ' . $debug  . ' -->'); 
										?>	

										<?php require (INC . 'comment_box.php'); ?>
									</ul>
								</div>
							</div>
							<div class="tab-pane" id="crear_3">

								<!--<h3 class="comment-title">Introduce un nuevo comentario</h3>-->
						
								<h6 class="new_subtitles_header">Crea nou comentari:</h6>
								<div class="comment-insert">
									<!--<h3 class="who-says"><span>Says:</span> <?php echo $log_uname; //$userName; ?></h3>-->
									<div class="edit_post">
										<div id="btn_clock_3_0" class="btn_clock btn_clock_3">
											<i class="fa fa-clock-o"></i>
										</div>
										<div id="clock_slr_3_0" class="clock_slr" style="display: none;">
											<p class="marge_temps" style="display: none;">
												<label for="amount_3">Margen tiempo:</label>
												<input type="text" id="amount_3" class="amount" style="border:0; color:#f6931f; font-weight:bold;">
											</p>
											<div id="slr_3" class="slr slider-range" style="display: none;"></div>
											<!--
											<div class="comment-set-btn-wrapper">
												<div id="btn_slr_1" class="btn_slr ">Set</div>
											</div>
											-->


											<div id="clock-slider_3_0" class="clock-slider"></div>
											
											<span class="clock-slider-val start" id="event-start_3_0" style="display: none;">0</span>
											<span class="clock-slider-val finish" id="event-end_3_0" style="display: none;">0</span>
											
											<div class='check_autorefresh' id="check_autorefresh_3_0">
												<label for="autorefresh_checkbox_3_0">Auto-refresh slider?</label>
												<input type="checkbox" value="auto-refresh" name="autorefresh_checkbox" id="autorefresh_checkbox_3_0" checked="checked">
											</div>
											<!-- zoom -->
											<div class="sp-zoom" id="zoom_3_0">
											    <div class='sp-label'><h4 class='slider_zoom_label'>slider zoom:</h4></div>
											    <div class="sp-minus fff"> <a class="zoom_btn zoom_minus" href="#"><i class="fa fa-minus"></i></a>
											    </div>
											    <div class="sp-input">
											        <input type="text" class="zoom-input" value="3" />
											    </div>
											    <div class="sp-plus fff"> <a class="zoom_btn zoom_plus" href="#"><i class="fa fa-plus"></i></a>
											    </div>
											</div>

											<h4 class='manual_edit' id="manual_edit_3">Manual edit:</h4>
											<table class='inputs_headers' id="inputs_headers_3_0">
												<tr>
													<th><span class="clock_label">&nbsp;</span></th>
													<th><span class="val">min</span></th>
													<th><span id="sep1" class="sep">&nbsp;</span></th>
													<th><span class="val">sec</span></th>
													<th><span id="sep2" class="sep">&nbsp;</span></th>
													<th><span class="val">milisec</span></th>
												</tr>
												<tr id="inputs_start_3_0">
													<td><span class="clock_label inicio">In:</span></td>
													<td><input class="input_mins inicio" id="clock-slider-val-start-mins_3_0" value="00" /></td>
													<td><span class="sep inicio">:</span></td>
													<td><input class="input_secs inicio" id="clock-slider-val-start-secs_3_0" value="00" /></td>
													<td><span class="sep inicio">.</span></td>
													<td><input class="input_milis inicio" id="clock-slider-val-start-milis_3_0" value="000" /></td>
												</tr>
												<tr id="inputs_finish_3_0">	
													<td><span class="clock_label fin">Out:</span></td>
													<td><input class="input_mins fin" id="clock-slider-val-finish-mins_3_0" value="00" /></td>
													<td><span class="sep fin">:</span></td>
													<td><input class="input_secs fin" id="clock-slider-val-finish-secs_3_0" value="00" /></td>
													<td><span class="sep fin">.</span></td>
													<td><input class="input_milis fin" id="clock-slider-val-finish-milis_3_0" value="000" /></td>
												</tr>
											</table>


											<div class="clr"></div>
										</div>
									
									</div>
									
									<div id="comment-post-container" class="comment-insert-container">
										<textarea id="comment-post-text_<?php echo $videoId; ?>_3" class="comment-post-text comment-insert-text"></textarea>								<!-- tmb podria id="comment_post_text_(video)_(box)" pero no hace falta.-->	
										
									</div>	

									<div id="comment-post-btn_<?php echo $videoId; ?>_3" class="comment-post-btn_con_slr comment-post-btn-wrapper">Post
									</div>
									
								</div>
							</div>
						</div>
						
					</div>
				</div>
				<div id="comentarios_<?php echo $videoId; ?>_4" class="caja_comentarios wrapper">
					<div class="page-data">props i direcció</div>				
					<div class="comment-wrapper container">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#llegir_4" data-toggle="tab"><i class="fa fa-eye"></i><span>Llegir</span></a></li>
							<li><a href="#crear_4" data-toggle="tab"><i class="fa fa-plus"></i><span>Crear</span></a></li>
						</ul>
						<div class="tab-content clearfix">
							<div class="tab-pane active" id="llegir_4">
								<h6 class="new_subtitles_header">Comentaris actuals:</h6>
								<div class="comments-list">
									<ul id="comments-holder-ul_<?php echo $videoId; ?>_4" class="comments-holder-ul">
										<?php //$comments = array("a", "b", "c", "d", "e"); ?>
										<?php //$comments = Comments::getComments(); ?>
										<?php 
											// 'ES': box_id: 1; 'EN': box_id: 2; 'FR': box_id: 3, 'RU': box_id: 4
											$comments = Comments::getCommentsVideoBox($videoId, 4); 								// tiene que ser el subtitulado?	--> match con Midity
										?>
										<?php 
											//$debug = json_encode( (array)$comments );
											//echo ('<!-- getCommentsVideoBox: inicio, debug comment: ' . $debug  . ' -->'); 
										?>	

										<?php require (INC . 'comment_box.php'); ?>
									</ul>
								</div>
							</div>
							<div class="tab-pane" id="crear_4">

								<!--<h3 class="comment-title">Introduce un nuevo comentario</h3>-->
						
								<h6 class="new_subtitles_header">Crea nou comentari:</h6>
								<div class="comment-insert">
									<!--<h3 class="who-says"><span>Says:</span> <?php echo $log_uname; //$userName; ?></h3>-->
									<div class="edit_post">
										<div id="btn_clock_4_0" class="btn_clock btn_clock_4">
											<i class="fa fa-clock-o"></i>
										</div>
										<div id="clock_slr_4_0" class="clock_slr" style="display: none;">
											<p class="marge_temps" style="display: none;">
												<label for="amount_4">Margen tiempo:</label>
												<input type="text" id="amount_4" class="amount" style="border:0; color:#f6931f; font-weight:bold;">
											</p>
											<div id="slr_4" class="slr slider-range" style="display: none;"></div>
											<!--
											<div class="comment-set-btn-wrapper">
												<div id="btn_slr_1" class="btn_slr ">Set</div>
											</div>
											-->


											<div id="clock-slider_4_0" class="clock-slider"></div>
											
											<span class="clock-slider-val start" id="event-start_4_0" style="display: none;">0</span>
											<span class="clock-slider-val finish" id="event-end_4_0" style="display: none;">0</span>
											
											<div class='check_autorefresh' id="check_autorefresh_4_0">
												<label for="autorefresh_checkbox_4_0">Auto-refresh slider?</label>
												<input type="checkbox" value="auto-refresh" name="autorefresh_checkbox" id="autorefresh_checkbox_4_0" checked="checked">
											</div>
											<!-- zoom -->
											<div class="sp-zoom" id="zoom_4_0">
											    <div class='sp-label'><h4 class='slider_zoom_label'>slider zoom:</h4></div>
											    <div class="sp-minus fff"> <a class="zoom_btn zoom_minus" href="#"><i class="fa fa-minus"></i></a>
											    </div>
											    <div class="sp-input">
											        <input type="text" class="zoom-input" value="3" />
											    </div>
											    <div class="sp-plus fff"> <a class="zoom_btn zoom_plus" href="#"><i class="fa fa-plus"></i></a>
											    </div>
											</div>

											<h4 class='manual_edit' id="manual_edit_4">Manual edit:</h4>
											<table class='inputs_headers' id="inputs_headers_4_0">
												<tr>
													<th><span class="clock_label">&nbsp;</span></th>
													<th><span class="val">min</span></th>
													<th><span id="sep1" class="sep">&nbsp;</span></th>
													<th><span class="val">sec</span></th>
													<th><span id="sep2" class="sep">&nbsp;</span></th>
													<th><span class="val">milisec</span></th>
												</tr>
												<tr id="inputs_start_4_0">
													<td><span class="clock_label inicio">In:</span></td>
													<td><input class="input_mins inicio" id="clock-slider-val-start-mins_4_0" value="00" /></td>
													<td><span class="sep inicio">:</span></td>
													<td><input class="input_secs inicio" id="clock-slider-val-start-secs_4_0" value="00" /></td>
													<td><span class="sep inicio">.</span></td>
													<td><input class="input_milis inicio" id="clock-slider-val-start-milis_4_0" value="000" /></td>
												</tr>
												<tr id="inputs_finish_4_0">	
													<td><span class="clock_label fin">Out:</span></td>
													<td><input class="input_mins fin" id="clock-slider-val-finish-mins_4_0" value="00" /></td>
													<td><span class="sep fin">:</span></td>
													<td><input class="input_secs fin" id="clock-slider-val-finish-secs_4_0" value="00" /></td>
													<td><span class="sep fin">.</span></td>
													<td><input class="input_milis fin" id="clock-slider-val-finish-milis_4_0" value="000" /></td>
												</tr>
											</table>


											<div class="clr"></div>
										</div>
									
									</div>
									
									<div id="comment-post-container" class="comment-insert-container">
										<textarea id="comment-post-text_<?php echo $videoId; ?>_4" class="comment-post-text comment-insert-text"></textarea>								<!-- tmb podria id="comment_post_text_(video)_(box)" pero no hace falta.-->	
										
									</div>	

									<div id="comment-post-btn_<?php echo $videoId; ?>_4" class="comment-post-btn_con_slr comment-post-btn-wrapper">Post
									</div>
									
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="clr"></div>
			<p class="descripcio">&copy; websalacarte.com</p>
		
			
		</div>
		<!-- END FORM -->
	</div>
	<!-- END CONTAINER -->
	<input type="hidden" id="userId" value="<?php 
												//echo $userId; 
												echo $log_user_id; 
												?>" />
	<input type="hidden" id="userName" value="<?php 
												//echo $userName; 
												echo $log_uname; 
												?>" />
	<input type="hidden" id="pageId" value="<?php 
												//echo $userId; 
												echo $page_id; 
												?>" />
	
	
</div>
<?php include_once("foot_common.php"); ?>



<script src="js/nouislider.min.js"></script>
<script src="js/wNumb.js"></script>
<script>
var video_duration;
var curr_time_value;
//var clockSlider = [[]];	// clockSlider[i_caja][num_comentario] es el clockslider de cada comentario en la p�gina. inicialmente, s�lo 2 (1 para cada "comentario 0").
// objeto, no array		(ver: http://stackoverflow.com/a/11648716/1414176 )
var clockSlider = {};
var num_clocksliders = []; // cada 'caja' tiene un num_clocksliders (1+num_comentarios)
var dateValues = {}; // uno para cada clockslider, dentro de caja. Su puntero es [handle] --> dateValues[i_caja][numero_de_comentario][handle]=tiempo.
var tipHandles = {};
var tooltips = {};
var slider_range_zoom = 1;

var num_cajas = $(".caja_comentarios").length;
//console.log('num_cajas: '+num_cajas);


for (i_caja = 1; i_caja < num_cajas+1; i_caja++){
	inicializa_nuevo_slider(i_caja, 0);
}

function inicializa_nuevo_slider(i_caja, i_comment) {
	//
	// pointers a sliders por caja y comentario (o 0)
	//clockSlider[i_caja] = [];
	if ( !(i_caja in clockSlider) ) {
		clockSlider[i_caja] = {};
	}
	clockSlider[i_caja][i_comment] = document.getElementById('clock-slider_'+i_caja+'_'+i_comment);
	//clockSlider[i_caja][i_comment] = document.getElementById('clock-slider_'+i_caja+'_'+i_comment);
	//console.log('creando noUISlider, con clockSlider[icaja='+i_caja+'][i_comment='+i_comment+'] = '+clockSlider[i_caja][i_comment]);
	noUiSlider.create(clockSlider[i_caja][i_comment], {
		// Create two timestamps to define a range.
		    range: {
		        //min: tiempo_actual_play - 1,
		        //max: tiempo_actual_play + 9
		        min: 0,
		        max: 60
		    },
		    // conecto los handlers
			behaviour: 'drag',
			connect: true,
			pips: {
				mode: 'range',
				density: 20,
				format: {
    				to: function ( value ) {
    						var aux_value = new Date(+1000*value);
    						var aux_return = formatDateShortPIPS(aux_value);
    						//console.log('pips format, value='+value+', aux_value='+aux_value+'aux_return='+aux_return);
							return formatDateShortPIPS(new Date(+1000*value))
						}
    			}
			},
		    // show tooltips
			//tooltips: [ wNumb({ decimals: 3 }), wNumb({ decimals: 3 }) ],
			tooltips: true,
			// Steps of one week
		    step: 0.500,
			// Two more timestamps indicate the handle starting positions.
		    start: [ tiempo_actual_play, (tiempo_actual_play+3) ],
		});

		//dateValues[i_caja] = [];
		if ( !(i_caja in dateValues) ) {
			dateValues[i_caja] = {};
		}
		dateValues[i_caja][i_comment] = [
			document.getElementById('event-start_'+i_caja+'_'+i_comment),
			document.getElementById('event-end_'+i_caja+'_'+i_comment)
		];

		clockSlider[i_caja][i_comment].noUiSlider.on('update', function( values, handle ) {
			var esto = $(this).get(0);	
				//	div#clock-slider_1_0.clock-slider.noUi-target.noUi-ltr.noUi-horizontal.noUi-background
				//	div id="clock-slider_1_0"
						// class="clock-slider noUi-target noUi-ltr noUi-horizontal noUi-background"
			var demas = this.target;
			var demas_id = $(demas).attr("id");
			/*
			var esto_otro = $(this).attr("id");	
			var aquello = $(event.target);
			var aquello_id1 = $(event.target).attr("id");
			var aquello_node = $(event.target).nodeName;
			var aquello_class = $(event.target).className;
			var aquello_id = $(event.target).id;
			console.log('clockslider on update, aquello: '+aquello+', aquello_id1: '+aquello_id1+', aquello_node: '+aquello_node+', aquello_class: '+aquello_class+', aquello_id: '+aquello_id);
			console.log('clockslider on update, con i_caja: '+i_caja+', esto: '+esto+', esto_otro: '+esto_otro+', aquello: '+aquello);
			*/
			var esto_id = demas_id.split('_');
			i_caja = esto_id[1];
			//console.log('(para caja '+i_caja+' y comment '+i_comment+') updating dateValues fields with values[handle='+handle+']: '+values[handle]);
			//dateValues[handle].innerHTML = formatDate(new Date(+values[handle]));
			dateValues[i_caja][i_comment][handle].innerHTML = formatDate(new Date(+1000*values[handle]));
		});


	// Para cada uno de los sliders, preparamos los tips
	//tipHandles[i_caja] = [];
	if ( !(i_caja in tipHandles) ) {
		tipHandles[i_caja] = {};
	}
	//tooltips[i_caja] = [];
	if ( !(i_caja in tooltips) ) {
		tooltips[i_caja] = {};
	}
	tipHandles[i_caja][i_comment] = clockSlider[i_caja][i_comment].getElementsByClassName('noUi-handle'),
	    tooltips[i_caja][i_comment] = [];

    // Add divs to the slider handles.
    for ( var i = 0; i < tipHandles[i_caja][i_comment].length; i++ ){
        tooltips[i_caja][i_comment][i] = document.createElement('div');
        tooltips[i_caja][i_comment][i].setAttribute('class', 'noUi-tooltip active');
        tipHandles[i_caja][i_comment][i].appendChild(tooltips[i_caja][i_comment][i]);
    }

    // When the slider changes, write the value to the tooltips.
    clockSlider[i_caja][i_comment].noUiSlider.on('update', function( values, handle, unencoded ){
    	var fecha_formato_lectura = formatDate(new Date(+1000*values[handle]));
        tooltips[i_caja][i_comment][handle].innerHTML = fecha_formato_lectura;	// (unencoded)
        update_clock_inputs(i_caja, i_comment, values, handle, fecha_formato_lectura);
        
        // TIEMPO CAJA PARA BD
        //update_tiempo_slr_cajas(caja, fecha_formato_lectura);
        //tiempo_slr_cajas[ 'caja_'+caja, 'min' ] = reverseFormatDate(fecha_formato_lectura);
        var aux_minmax;																					
        aux_minmax = (handle==0) ? 'min' : 'max';
        //console.log('updating tiempo_slr_cajas, values[handle] vale: '+values[handle]+', y con reverse-fecha obtengo: '+reverseFormatDate(fecha_formato_lectura));
        tiempo_slr_cajas[ 'caja_'+i_caja, aux_minmax ] = values[handle];	/* ************************ */
    });
}







/*
clockSlider.noUiSlider.on('change', function(){
	dateValues[handle].innerHTML = formatDate(new Date(+1000*values[handle]));
});
*/

// Create a string representation of the time.
function formatDate ( date ) {
	//date_formated = date.getMinutes() + ":" +
    //    date.getSeconds() + "." +
    //    date.getMilliseconds();
	var m = date.getMinutes() > 9 ? date.getMinutes() : '0'+date.getMinutes();
	var s = date.getSeconds() > 9 ? date.getSeconds() : '0'+date.getSeconds();
	var ms = date.getMilliseconds() > 9 ? date.getMilliseconds() : '00'+date.getMilliseconds();
	date_formated = m + ":" + s + "." + ms;
	//console.log('date is: '+date.getMinutes()+' min, '+date.getSeconds()+' s, '+date.getMilliseconds()+' ms, formated to: '+date_formated);
    return date_formated;
}
function reverseFormatDate ( value ) {
	//en formatDate pasamos de 123.999 (123 segundos con 999 milisegs, ie, 2 min 3 seg 999 miliseg) --> a 02:03.999
	// aqui pasamos de 02:03.999 --> a: 123.999 (y se supone que se tratar� como fecha)
	
		var value_partes = value.split('.');
		var value_min_sec = value_partes[0].split(':');
		var num_min = value_min_sec[0];
		var num_seg = value_min_sec[1];
		var num_milis = value_partes[1];
		var num_segundos = num_min*60 + num_seg + Math.floor(num_milis/1000);
		return num_segundos/1000;
	
}
// Create a string representation of the time.
function formatDateShortPIPS ( date ) {
	//date_formated = date.getMinutes() + ":" +
    //    date.getSeconds() + "." +
    //    date.getMilliseconds();
	var m = date.getMinutes();
	var s = date.getSeconds() > 9 ? date.getSeconds() : '0'+date.getSeconds();
	date_formated = m + ":" + s;
	//console.log('date is: '+date.getMinutes()+' min, '+date.getSeconds()+' s, '+date.getMilliseconds()+' ms, formated to: '+date_formated);
    return date_formated;
}

//clockSlider.noUiSlider.set([null, 14]);
function updateSliderRange ( i_caja, i_comment, min, max ) {
	//console.log('updateSliderRange con i_caja: '+i_caja+', con min: '+min+' y max: '+max);
	//i_comment = 0;

	clockSlider[i_caja][i_comment].noUiSlider.updateOptions({
		range: {
			'min': min,
			'max': max
		}
	});
	var punto_medio = Math.floor( (max-min)/2 );
	var old_pips = clockSlider[i_caja][i_comment].noUiSlider.target.querySelectorAll('.noUi-pips');
	
	var index = 0;
	 for( index=0; index < old_pips.length; index++ ) {
	       old_pips[index].outerHTML = '';
	 }

	//clockSlider[i_caja][i_comment].noUiSlider.pips({mode: 'count', values: 3, density: 20});
	clockSlider[i_caja][i_comment].noUiSlider.pips({
		mode: 'count', 
		values: 3, 
		density: 20, 
		format: {
			to: function ( value ) {
						var aux_value = new Date(+1000*value);
						var aux_return = formatDateShortPIPS(aux_value);
						//console.log('pips format, value='+value+', aux_value='+aux_value+'aux_return='+aux_return);
						return formatDateShortPIPS(new Date(+1000*value))
					}

		} 
	});
}

/*
	<div id="inputs_start">
										<input class="input_mins" id="clock-slider-val-start-mins"/>
										<input class="input_secs" id="clock-slider-val-start-secs"/>
										<input class="input_milis" id="clock-slider-val-start-milis"/>
	</div>
	<div id="inputs_finish">
										<input class="input_mins" id="clock-slider-val-finish-mins"/>
										<input class="input_secs" id="clock-slider-val-finish-secs"/>
										<input class="input_milis" id="clock-slider-val-finish-milis"/>
	</div>
*/
var inputFormat_start_mins = {}, inputFormat_start_secs = {}, inputFormat_start_milis = {}, 
	inputFormat_finish_mins = {}, inputFormat_finish_secs = {}, inputFormat_finish_milis = {};

for (i_caja = 1; i_caja < num_cajas+1; i_caja++){
	i_comment = 0;
	bind_clock_inputs(i_caja, i_comment);
}
function bind_clock_inputs(i_caja, i_comment) {
	if ( !(i_caja in inputFormat_start_mins) ) {
		// definimos todas juntas, no hacen falta 6 validaciones
		inputFormat_start_mins[i_caja] = {};
		inputFormat_start_secs[i_caja] = {};
		inputFormat_start_milis[i_caja] = {};
		inputFormat_finish_mins[i_caja] = {};
		inputFormat_finish_secs[i_caja] = {};
		inputFormat_finish_milis[i_caja] = {};
	}

	inputFormat_start_mins[i_caja][i_comment] = document.getElementById('clock-slider-val-start-mins_'+i_caja+'_'+i_comment);
	inputFormat_start_secs[i_caja][i_comment] = document.getElementById('clock-slider-val-start-secs_'+i_caja+'_'+i_comment);
	inputFormat_start_milis[i_caja][i_comment] = document.getElementById('clock-slider-val-start-milis_'+i_caja+'_'+i_comment);
	inputFormat_finish_mins[i_caja][i_comment] = document.getElementById('clock-slider-val-finish-mins_'+i_caja+'_'+i_comment);
	inputFormat_finish_secs[i_caja][i_comment] = document.getElementById('clock-slider-val-finish-secs_'+i_caja+'_'+i_comment);
	inputFormat_finish_milis[i_caja][i_comment] = document.getElementById('clock-slider-val-finish-milis_'+i_caja+'_'+i_comment);


	inputFormat_start_mins[i_caja][i_comment].addEventListener('change', function(i_caja){
		var esto = this.getAttribute('id');	//.get( 0 ).getAttr('class');
		var esto_otro = esto.split('_');	// http://stackoverflow.com/questions/33689525/nouislider-update-input-value-multiple-sliders
		i_caja = esto_otro[1];
		i_comment = esto_otro[2];
		//console.log('inputFormat_start_mins, caja: '+i_caja+', comment_id: '+i_comment+', esto: '+esto+', esto_otro: '+esto_otro);
		var inp_mins = parseInt(inputFormat_start_mins[i_caja][i_comment].value);
		var inp_secs = parseInt(inputFormat_start_secs[i_caja][i_comment].value);
		var inp_milis = parseInt(inputFormat_start_milis[i_caja][i_comment].value);
		var inp_value = (inp_mins*60) + (inp_secs) + inp_milis/1000;
		//console.log('inputFormat_start_mins change, inp_mins:'+inp_mins+', inp_secs:'+inp_secs+', inp_milis:'+inp_milis+', inp_value:'+inp_value);
		clockSlider[i_caja][i_comment].noUiSlider.set([inp_value, null]);
	});
	inputFormat_start_secs[i_caja][i_comment].addEventListener('change', function(event, values, handle, i_caja){
		var esto = this.getAttribute('id');	
		var esto_otro = esto.split('_');	// this.getAttribute('class');	// this: input#clock-slider-val-start-secs_1_0.input_secs.inicio
		i_caja = esto_otro[1];
		i_comment = esto_otro[2];
		//console.log('inputFormat_start_secs, caja: '+i_caja+', comment_id: '+i_comment+', esto: '+esto+', esto_otro: '+esto_otro);
		var inp_mins = parseInt(inputFormat_start_mins[i_caja][i_comment].value);
		var inp_secs = parseInt(inputFormat_start_secs[i_caja][i_comment].value);
		var inp_milis = parseInt(inputFormat_start_milis[i_caja][i_comment].value);
		var inp_value = (inp_mins*60) + (inp_secs) + inp_milis/1000;
		console.log('inputFormat_start_secs change, inp_mins:'+inp_mins+', inp_secs:'+inp_secs+', inp_milis:'+inp_milis+', inp_value:'+inp_value);
		clockSlider[i_caja][i_comment].noUiSlider.set([inp_value, null]);
	});
	inputFormat_start_milis[i_caja][i_comment].addEventListener('change', function(i_caja){
		var esto = this.getAttribute('id');
		var esto_otro = esto.split('_');
		i_caja = esto_otro[1];
		i_comment = esto_otro[2];
		//console.log('inputFormat_start_milis, caja: '+i_caja+', comment_id: '+i_comment+', esto: '+esto+', esto_otro: '+esto_otro);
		var inp_mins = parseInt(inputFormat_start_mins[i_caja][i_comment].value);
		var inp_secs = parseInt(inputFormat_start_secs[i_caja][i_comment].value);
		var inp_milis = parseInt(inputFormat_start_milis[i_caja][i_comment].value);
		var inp_value = (inp_mins*60) + (inp_secs) + inp_milis/1000;
		//console.log('inputFormat_start_milis change, inp_mins:'+inp_mins+', inp_secs:'+inp_secs+', inp_milis:'+inp_milis+', inp_value:'+inp_value);
		clockSlider[i_caja][i_comment].noUiSlider.set([inp_value, null]);
	});
	inputFormat_finish_mins[i_caja][i_comment].addEventListener('change', function(i_caja){
		var esto = this.getAttribute('id');
		var esto_otro = esto.split('_');
		i_caja = esto_otro[1];
		i_comment = esto_otro[2];
		var inp_mins = parseInt(inputFormat_finish_mins[i_caja][i_comment].value);
		var inp_secs = parseInt(inputFormat_finish_secs[i_caja][i_comment].value);
		var inp_milis = parseInt(inputFormat_finish_milis[i_caja][i_comment].value);
		var inp_value = (inp_mins*60) + (inp_secs) + inp_milis/1000;
		//console.log('inputFormat_finish_mins change, inp_mins:'+inp_mins+', inp_secs:'+inp_secs+', inp_milis:'+inp_milis+', inp_value:'+inp_value);
		clockSlider[i_caja][i_comment].noUiSlider.set([null, inp_value]);
	});
	inputFormat_finish_secs[i_caja][i_comment].addEventListener('change', function(i_caja){
		var esto = this.getAttribute('id');
		var esto_otro = esto.split('_');
		i_caja = esto_otro[1];
		i_comment = esto_otro[2];
		var inp_mins = parseInt(inputFormat_finish_mins[i_caja][i_comment].value);
		var inp_secs = parseInt(inputFormat_finish_secs[i_caja][i_comment].value);
		var inp_milis = parseInt(inputFormat_finish_milis[i_caja][i_comment].value);
		var inp_value = (inp_mins*60) + (inp_secs) + inp_milis/1000;
		//console.log('inputFormat_finish_secs change, inp_mins:'+inp_mins+', inp_secs:'+inp_secs+', inp_milis:'+inp_milis+', inp_value:'+inp_value);
		clockSlider[i_caja][i_comment].noUiSlider.set([null, inp_value]);
	});
	inputFormat_finish_milis[i_caja][i_comment].addEventListener('change', function(i_caja){
		var esto = this.getAttribute('id');
		var esto_otro = esto.split('_');
		i_caja = esto_otro[1];
		i_comment = esto_otro[2];
		var inp_mins = parseInt(inputFormat_finish_mins[i_caja][i_comment].value);
		var inp_secs = parseInt(inputFormat_finish_secs[i_caja][i_comment].value);
		var inp_milis = parseInt(inputFormat_finish_milis[i_caja][i_comment].value);
		var inp_value = (inp_mins*60) + (inp_secs) + inp_milis/1000;
		//console.log('inputFormat_finish_milis change, inp_mins:'+inp_mins+', inp_secs:'+inp_secs+', inp_milis:'+inp_milis+', inp_value:'+inp_value);
		clockSlider[i_caja][i_comment].noUiSlider.set([null, inp_value]);
	});

}
function update_clock_inputs (i_caja, i_comment, values, handle, fecha_formato_lectura) {
	//
	var clock_input;
	//console.log('fecha_formato_lectura: '+fecha_formato_lectura);

	var value_partes = fecha_formato_lectura.split('.');
	var value_min_sec = value_partes[0].split(':');
	var num_min = value_min_sec[0];
	var num_seg = value_min_sec[1];
	var num_milis = value_partes[1];
	//console.log('num_min: '+num_min+', num_seg: '+num_seg+', num_milis: '+num_milis);

	if (handle==0) {
		clock_input = 'start';
	}
	else {
		clock_input = 'finish';
	}
	//console.log('update_clock_inputs, clock-slider-val-'+clock_input+'-mins_'+i_caja+'_'+i_comment);
	document.getElementById('clock-slider-val-'+clock_input+'-mins_'+i_caja+'_'+i_comment).value = num_min;
	document.getElementById('clock-slider-val-'+clock_input+'-secs_'+i_caja+'_'+i_comment).value = num_seg;
	document.getElementById('clock-slider-val-'+clock_input+'-milis_'+i_caja+'_'+i_comment).value = num_milis;

}


function refresh_slider_slots (i_caja, i_comment, curr_time_value, video_duration) {
	//console.log('en refresh_slider_slots, con caja: '+i_caja);
    //updateSliderRange(0, video_duration);
    // divido el slider en franjas de 20seg, 
    var num_franjas = 20*(1+slider_range_zoom)/2;		// slider_range_zoom=1 --> num_franjas=20; cada click en +zoom aumenta (ve mayor detalle) 50%
    var intSlotCurrent = Math.floor(curr_time_value / num_franjas);
    var numSlots = Math.floor(video_duration / num_franjas) + 1;
    // y muestro 3: el anterior, el actual, y el siguiente
    var intSlotAnterior = intSlotCurrent > 0 ? intSlotCurrent - 1 : 0;
    var intSlotNext 	= (numSlots > 3 && intSlotCurrent > 2) ? intSlotCurrent + 1 : 2;
    // ej: dur_video < 20s (1 slot), curr_time: 11s --> Ant: 0, Curr: 0, Next: 3 --> Ranges: (0, min(20,60))						// atencion: limitar a duraci�n
    // ej: dur_video = 35s (2 slots), curr_time: 24s --> Ant: 0, Curr: 1, Next: 3 --> Ranges: (0, min(35, 60))
    // ej: dur_video = 55s (3 slots), curr_time: 44s --> Ant: 1, Curr: 2, Next: 3 --> Ranges: (20, min(55, 60))
    //console.log('numSlots: '+numSlots+', intSlotAnterior: '+intSlotAnterior+', intSlotCurrent: '+intSlotCurrent+', intSlotNext: '+intSlotNext);

	var rango_max = Math.min((intSlotNext+1)*num_franjas, video_duration);
	// PARA EL SLIDER PASADO PoR PARAMETRO (desde onPlaying)
		//updateSliderRange(intSlotAnterior*20, rango_max);
			// y tras m�rgenes, updateo handlers, a: curr_time_value - 1, y curr_time_value + 9
		//clockSlider.noUiSlider.set([curr_time_value - 1, curr_time_value + 9]);
	
	//console.log('en refresh_slider_slots, con caja: '+i_caja+', llamando a updateSliderRange');
	updateSliderRange(i_caja, i_comment, intSlotAnterior*num_franjas, rango_max);							
	// y tras m�rgenes, updateo handlers, a: curr_time_value - 1, y curr_time_value + 9
	//clockSlider[i_caja][i_comment].noUiSlider.set([curr_time_value - 1, curr_time_value + 9]);	
	clockSlider[i_caja][i_comment].noUiSlider.set([curr_time_value, curr_time_value + 5]);			
	//console.log('en refresh_slider_slots, con caja: '+i_caja+', terminado clockslider');
								
	//sigue_refreshing_slider_slots = setInterval(function(){refresh_slider_slots()},1000);				
}

// zoom
$(".zoom_btn").on("click", function (e) {

	e.preventDefault();	// stop acting like a link
	var $button = $(this);
   	var oldValue = $button.closest('.sp-zoom').find("input.zoom-input").val();

	//if ($button.text() == "+") {
	if ($button.hasClass("zoom_plus")) {
	        var newVal = parseFloat(oldValue) + 1;
	} 
	else {
	        // Don't allow decrementing below zero
	        if (oldValue > 0) {
	            var newVal = parseFloat(oldValue) - 1;
	        } 
	        else {
	            newVal = 0;
	        }
	}

	$button.closest('.sp-zoom').find("input.zoom-input").val(newVal);
	slider_range_zoom = newVal;
	// get i_caja, i_comment
	var zoom_id = $button.closest('.sp-zoom').attr('id');
	var zoom_id_aux = zoom_id.split("_");
	var i_caja_zoom = zoom_id_aux[1];
	var i_comment_zoom = zoom_id_aux[2];
	refresh_slider_slots (i_caja_zoom, i_comment_zoom, curr_time_value, video_duration);
});

function anyade_evento_zoom(i_caja_zoom, i_comment_zoom) {
	// evento para zoom_cajai_commi a�adido (lo a�ado con id porque el resto ya tienen el evento)
	$("#zoom_" + i_caja_zoom + '_' + i_comment_zoom +" .zoom_btn").on("click", function (e) {

    	e.preventDefault();	// stop acting like a link
    	var $button = $(this);
	   	var oldValue = $button.closest('.sp-zoom').find("input.zoom-input").val();

		//if ($button.text() == "+") {
		if ($button.hasClass("zoom_plus")) {
		        var newVal = parseFloat(oldValue) + 1;
		} 
		else {
		        // Don't allow decrementing below zero
		        if (oldValue > 0) {
		            var newVal = parseFloat(oldValue) - 1;
		        } 
		        else {
		            newVal = 0;
		        }
		}

		$button.closest('.sp-zoom').find("input.zoom-input").val(newVal);
		slider_range_zoom = newVal;
		refresh_slider_slots (i_caja_zoom, i_comment_zoom, curr_time_value, video_duration);
	});

}






						function addEvent(element, eventName, callback) {
							if (element) {
								//console.log('addEvent para id: '+element.id);
				                if (element && element.addEventListener) {
				                    element.addEventListener(eventName, callback, false);
				                }
				                else {
				                    element.attachEvent('on' + eventName, callback);
				                }
				            }
			            }
			            

			            for (i_caja = 1; i_caja <= num_cajas; i_caja++){
			            	var timeBtn = document.getElementById('btn_clock_'+i_caja+'_'+i_comment);
							//console.log('en for, i_caja: '+i_caja+', llamando addEvent con i_caja: '+i_caja+', i_comment: '+i_comment);
			            	addEvent(timeBtn, 'click', function(e) {
								var i_caja, i_comment;
								//console.log('timeBtn, this.id: '+this.id);	// btn_clock_1_0
								var aux_full_id = this.id.split('_');
								i_caja = aux_full_id[2];
								i_comment = aux_full_id[3];
								//console.log('added event en timeBtn y vamos a refresh_slider, i_caja: '+i_caja+', i_comment: '+i_comment);
								refresh_slider(i_caja, i_comment);
			                }, false);
			            }

						function refresh_slider(i_caja, i_comment) {
							//
							curr_time_value = player.getCurrentTime();
			                video_duration = player.getDuration();
			                console.log('refresh_slider caja: '+i_caja);
			                refresh_slider_slots(i_caja, i_comment, curr_time_value, video_duration);
						}

						
						// Call the API when a button is pressed
						/*
						$('button').bind('click', function() {
							//player.api($(this).text().toLowerCase());
							player($(this).text().toLowerCase());
						});
						*/
						

                        // Call seekTo when seek button clicked
                        addEvent(seekBtn, 'click', function(e) {
                            // Don't do anything if clicking on anything but the button (such as the input field)
                            if (e.target != this) {
                                return false;
                            }

							curr_time_value = player.getCurrentTime();
                            // Grab the value in the input field
                            var seekVal = curr_time_value - this.querySelector('input').value;

                            player.seekTo(seekVal);	// yt: player.seekTo(seconds)
                        }, false);
                        



</script>

	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</body>
</html>