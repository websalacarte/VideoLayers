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
<?php $videoId=7;	//73589408 Kevin Spacey (old: 22647358) // el id en nuestra BD ?>
<?php $platform_videoId='qJeN_Z8sErA'; // el id en YT-Vimeo ?>
<?php $videoId_subt=24819045;	 ?>
<?php $page_id=11; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Newsroom test</title>
<?php include_once("head_common-youtube.php"); ?>

		
<script>
var tiempo_actual_play = 0;	// defino global. Informo durante control reproducci�n.
var last_refresh_time = 0;
var video_id_actual = <?php echo $videoId; ?>;
var duracion_max_video = 294;	//4*60 + 54;
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
console.log('clicado btn_clock. caja: ' + caja + ', edit_comm_id: ' + edit_comm_id + ', caja_tmp length: ' + caja_tmp.length);
			//console.log('clicado btn_clock con id:' + caja_tmp);		
			$( ".slr" ).slider( "option", "values", [ tiempo_actual_play, tiempo_actual_play + 5 ] );
			//actualiza_slider( tiempo_actual_play );
			actualiza_slider( tiempo_actual_play, caja );
		if( caja_tmp.length == 3 ) {
			// es btn_clock de 'POST'
			$("#clock_slr_"+caja).toggle();
		} else {
			var edit_comm_id = caja_tmp[3];
			// es btn_clock de 'EDIT'
			$( "#clock_slr_" + caja + "_" + edit_comm_id ).toggle();
			console.log('desde index, toggle #clock_slr_'+caja+'_'+edit_comm_id);
		}
	});
	
});

  </script>
</head>
<body>
<div class="main_container">
<?php include_once("header_template.php"); ?>
	 
	<!-- CONTAINER -->
	<h2>Newsroom test - <span>Websalacarte</span></h2>
	<div class="container">
		<div id="form">
		   
		   
			<div id="idioma" class="subs_selector">
				<div id="subchooser" class="subchooser">
					<form name="subselect" class="form" action="">            
						<p><label for="subs">Choose the subjects you are interested in:</label>
						
						<!--<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_0" VALUE="none" onchange="show_comments('subs_none')" ><label for="subs_none">sin cajas</label></div>-->
						<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_1" VALUE="1" onchange="show_comments('subs_1')" checked ><label for="subs_1">This episode</label></div>
						<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_2" VALUE="2" onchange="show_comments('subs_2')" checked ><label for="subs_2">Characters</label></div>
						<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_3" VALUE="3" onchange="show_comments('subs_3')" checked ><label for="subs_3">Script</label></div>
						<div class="checks"><INPUT TYPE="checkbox" NAME="subs" id="subs_4" VALUE="4" onchange="show_comments('subs_4')" checked ><label for="subs_4">Crew</label></div>
						
						<BR>

					</p>
					</form>   
				</div>
			</div>

			<!-- https://vimeo.com/73589408 Kevin Spacey (old: 22647358) -->
			
			
			<div id="player_yt"></div>
			<script>
      // This code loads the YT IFrame Player API code asynchronously.
	  
      var tag = document.createElement('script');

      tag.src = "//www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // This function creates an <iframe> (and YouTube player)
      //    after the YT API code downloads.
      var player;
	  var time;
	  var done = false;
	  var revisa_comments;
      function onYouTubeIframeAPIReady() {
		console.log('youtube-mobile.js, onYouTubeIframeAPIReady');
        player = new YT.Player('player_yt', {
          height: '390',									// pendiente obtenci�n desde API youtube durante validaci�n
          width: '640',
          videoId: 'qJeN_Z8sErA',
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }
		
	  function onPlaying() {
			console.log('onplaying');
			time = player.getCurrentTime();
			revisa_comments = setInterval(function(){check_comments()},1000);	
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
      }

      // The YT API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      function onPlayerStateChange(event) {
		console.log('youtube-mobile.js, onPlayerStateChange');
        if (event.data == YT.PlayerState.PLAYING && !done) {
        //  setTimeout(stopVideo, 6000);
        //  done = true;
			console.log('onPlayerStateChange playing');
			onPlaying();
			
        } 
        if (event.data == YT.PlayerState.PAUSED && !done) {
			console.log('onPlayerStateChange pausado');
			clearInterval(revisa_comments);
		}
      }
      function stopVideo() {
        player.stopVideo();
      }
	  </script>

		
		
			<div class="clr"></div>
			
			<div id="area_comentarios">
			
				<div id="comentarios_<?php echo $videoId; ?>_1" class="caja_comentarios wrapper">
					<div class="page-data">This episode</div>				
					<div class="comment-wrapper">
						
						<!--<h3 class="comment-title">Introduce un nuevo comentario</h3>-->
						
						<div class="comment-insert">
							<h3 class="who-says"><span>Says:</span> <?php echo $log_uname; //$userName; ?></h3>
							<div class="edit_post">
								<div id="btn_clock_1" class="btn_clock">
									<i class="fa fa-clock-o"></i>
									<!--<i class="fa fa-comment"></i>-->
								</div>
								<div id="clock_slr_1" class="clock_slr" style="display: none;">
									<p class="marge_temps">
										<label for="amount_1">Margen tiempo:</label>
										<input type="text" id="amount_1" class="amount" style="border:0; color:#f6931f; font-weight:bold;">
									</p>
									<div id="slr_1" class="slr slider-range"></div>
									<!--<button id="btn_slr_1" class="btn_slr">Set</button>-->
									<div class="comment-set-btn-wrapper">
										<div id="btn_slr_1" class="btn_slr ">Set</div>
									</div>
									<div class="clr"></div>
								</div>
							
							</div>
							
							<div id="comment-post-container" class="comment-insert-container">
								<!--<textarea id="comment-post-text" class="comment-insert-text"></textarea>-->					<!-- **************REVISAR ESTILOS id's -> class's ************-->
								<textarea id="comment-post-text_<?php echo $videoId; ?>_1" class="comment-post-text comment-insert-text"></textarea>								<!-- tmb podria id="comment_post_text_(video)_(box)" pero no hace falta.-->	
								
							</div>	
<!--							<div id="comment-post-btn_<?php echo $videoId; ?>_1" class="comment-post-btn comment-post-btn-wrapper">Post
							</div>-->
							<div id="comment-post-btn_<?php echo $videoId; ?>_1" class="comment-post-btn_con_slr comment-post-btn-wrapper">Post
							</div>
							
						</div>
						
						<div class="comments-list">
							<ul id="comments-holder-ul_<?php echo $videoId; ?>_1" class="comments-holder-ul">
								<?php //$comments = array("a", "b", "c", "d", "e"); ?>
								<?php //$comments = Comments::getComments(); ?>
								<?php 
									// 'ES': box_id: 1; 'EN': box_id: 2; 'FR': box_id: 3, 'RU': box_id: 4
									$comments = Comments::getCommentsVideoBox($videoId, 1); 								// tiene que ser el subtitulado?	--> match con Midity
								?>
								<?php require (INC . 'comment_box.php'); ?>
							</ul>
						</div>
						
					</div>
				</div>
				
				<div id="comentarios_<?php echo $videoId; ?>_2" class="caja_comentarios wrapper">
					<div class="page-data">Characters</div>				
					<div class="comment-wrapper">
						
						<!--<h3 class="comment-title">Introduce un nuevo comentario</h3>-->
						
						<div class="comment-insert">
							<h3 class="who-says"><span>Says:</span> <?php echo $log_uname; //$userName; ?></h3>
							<div class="edit_post">
								<div id="btn_clock_2" class="btn_clock">
									<i class="fa fa-clock-o"></i>
									<!--<i class="fa fa-comment"></i>-->
								</div>
								<div id="clock_slr_2" class="clock_slr" style="display: none;">
									<p class="marge_temps">
										<label for="amount_2">Margen tiempo:</label>
										<input type="text" id="amount_2" class="amount" style="border:0; color:#f6931f; font-weight:bold;">
									</p>
									<div id="slr_2" class="slr slider-range"></div>
									<div class="comment-set-btn-wrapper">
										<div id="btn_slr_2" class="btn_slr ">Set</div>
									</div>
									<div class="clr"></div>
								</div>
							
							</div>
							<div id="comment-post-container" class="comment-insert-container">
								<textarea id="comment-post-text_<?php echo $videoId; ?>_2" class="comment-post-text comment-insert-text"></textarea>
							</div>
							<div id="comment-post-btn_<?php echo $videoId; ?>_2" class="comment-post-btn_con_slr comment-post-btn-wrapper">Post
							</div>
						</div>
						
						<div class="comments-list">
							<ul id="comments-holder-ul_<?php echo $videoId; ?>_2" class="comments-holder-ul">
								<?php $comments = Comments::getCommentsVideoBox($videoId, 2); ?>
								<?php require (INC . 'comment_box.php'); ?>
							</ul>
						</div>
						
					</div>
				</div>
				<div id="comentarios_<?php echo $videoId; ?>_3" class="caja_comentarios wrapper">
					<div class="page-data">Script</div>				
					<div class="comment-wrapper">
						
						<!--<h3 class="comment-title">Introduce un nuevo comentario</h3>-->
						
						<div class="comment-insert">
							<h3 class="who-says"><span>Says:</span> <?php echo $log_uname; //$userName; ?></h3>
							<div class="edit_post">
								<div id="btn_clock_3" class="btn_clock">
									<i class="fa fa-clock-o"></i>
									<!--<i class="fa fa-comment"></i>-->
								</div>
								<div id="clock_slr_3" class="clock_slr" style="display: none;">
									<p class="marge_temps">
										<label for="amount_3">Margen tiempo:</label>
										<input type="text" id="amount_3" class="amount" style="border:0; color:#f6931f; font-weight:bold;">
									</p>
									<div id="slr_3" class="slr slider-range"></div>
									<div class="comment-set-btn-wrapper">
										<div id="btn_slr_3" class="btn_slr ">Set</div>
									</div>
									<div class="clr"></div>
								</div>
							
							</div>
							<div id="comment-post-container" class="comment-insert-container">
								<textarea id="comment-post-text_<?php echo $videoId; ?>_3" class="comment-post-text comment-insert-text"></textarea>
							</div>
							<div id="comment-post-btn_<?php echo $videoId; ?>_3" class="comment-post-btn_con_slr comment-post-btn-wrapper">Post
							</div>
						</div>
						
						<div class="comments-list">
							<ul id="comments-holder-ul_<?php echo $videoId; ?>_3" class="comments-holder-ul">
								<?php $comments = Comments::getCommentsVideoBox($videoId, 3); ?>
								<?php require (INC . 'comment_box.php'); ?>
							</ul>
						</div>
						
					</div>
				</div>
				<div id="comentarios_<?php echo $videoId; ?>_4" class="caja_comentarios wrapper">
					<div class="page-data">Crew</div>				
					<div class="comment-wrapper">
						<!--<h3 class="comment-title">Introduce un nuevo comentario</h3>-->
						
						<div class="comment-insert">
							<h3 class="who-says"><span>Says:</span> <?php echo $log_uname; //$userName; ?></h3>
							<div class="edit_post">
								<div id="btn_clock_4" class="btn_clock">
									<i class="fa fa-clock-o"></i>
									<!--<i class="fa fa-comment"></i>-->
								</div>
								<div id="clock_slr_4" class="clock_slr" style="display: none;">
									<p class="marge_temps">
										<label for="amount_4">Margen tiempo:</label>
										<input type="text" id="amount_4" class="amount" style="border:0; color:#f6931f; font-weight:bold;">
									</p>
									<div id="slr_4" class="slr slider-range"></div>
									<div class="comment-set-btn-wrapper">
										<div id="btn_slr_4" class="btn_slr ">Set</div>
									</div>
									<div class="clr"></div>
								</div>
							
							</div>
							<div id="comment-post-container" class="comment-insert-container">
								<textarea id="comment-post-text_<?php echo $videoId; ?>_4" class="comment-post-text comment-insert-text"></textarea>
							</div>
							<div id="comment-post-btn_<?php echo $videoId; ?>_4" class="comment-post-btn_con_slr comment-post-btn-wrapper">Post
							</div>
						</div>
						
						<div class="comments-list">
							<ul id="comments-holder-ul_<?php echo $videoId; ?>_4" class="comments-holder-ul">
								<?php $comments = Comments::getCommentsVideoBox($videoId, 4); ?>
								<?php require (INC . 'comment_box.php'); ?>
							</ul>
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

</body>
</html>