/* Youtube-mobile.js	*/ 
// https://developers.google.com/youtube/iframe_api_reference


/*
<iframe id="player" type="text/html" width="640" height="390"
  src="http://www.youtube.com/embed/M7lc1UVf-VE?enablejsapi=1&origin=http://example.com"
  frameborder="0"></iframe>
  
*/


$(document).ready(function() {	
		console.log('youtube-mobile.js, on dom ready');
	  var status = $('.status');

      // This code loads the YT IFrame Player API code asynchronously.
      var tag = document.createElement('script');

      tag.src = "//www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // This function creates an <iframe> (and YouTube player)
      //    after the YT API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
		console.log('youtube-mobile.js, onYouTubeIframeAPIReady');
        player = new YT.Player('player_yt', {
          height: '390',
          width: '640',
          videoId: 'rba4cb0Npl4',								// &rel=0 important, perquè repetiria comentaris sobre un altre video.
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange,
			'onPlaying': onPlayProgress
          }
        });
      }

      // The YT API will call this function when the video player is ready.
      function onPlayerReady(event) {
		console.log('youtube-mobile.js, onPlayerReady');
        //event.target.playVideo();
		
		
		  
				var time = player.getCurrentTime();
					console.log('youtube-mobile.js, time: ' + time);
				status.text(time + 's played');
				tiempo_actual_play = time;
		
				//llamo a refresh cada segundo, no más
				var tiempo_desde_last_refresh = 0;
				tiempo_desde_last_refresh = time - last_refresh_time;
		//console.log('tiempo_desde_last_refresh: '+tiempo_desde_last_refresh);		
				if (Math.abs(tiempo_desde_last_refresh) > 1) {
					console.log('youtube-mobile.js onPlayerReady, tiempo_desde_last_refresh: ' + tiempo_desde_last_refresh + ', time: ' + time + '. Llamando comments_refresh. ');
					comments_refresh(time);
				}
				
      }

      // The YT API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      var done = false;
      function onPlayerStateChange(event) {
		console.log('youtube-mobile.js, onPlayerStateChange');
        do { 
        //  setTimeout(stopVideo, 6000);
        //  done = true;
		  
				var time = player.getCurrentTime();
					console.log('youtube-mobile.js, time: ' + time);
				status.text(time + 's played');
				tiempo_actual_play = time;
		
				//llamo a refresh cada segundo, no más
				var tiempo_desde_last_refresh = 0;
				tiempo_desde_last_refresh = time - last_refresh_time;
		//console.log('tiempo_desde_last_refresh: '+tiempo_desde_last_refresh);		
				if (Math.abs(tiempo_desde_last_refresh) > 1) {
					console.log('youtube-mobile.js, tiempo_desde_last_refresh: ' + tiempo_desde_last_refresh + ', time: ' + time + '. Llamando comments_refresh. ');
					comments_refresh(time);
				}
				
        } while (event.data == YT.PlayerState.PLAYING && !done);
      }
      function stopVideo() {
        //player.stopVideo();
      }
});
