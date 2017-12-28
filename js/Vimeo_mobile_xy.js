/* Vimeo_mobile.js */
// http://jsfiddle.net/bdougherty/HfwWY/light/

			//tiempo_actual_play = time;
		
        $(document).ready(function() {	
            var num_cajas = $(".caja_comentarios").length;
            //console.log('num_cajas: '+num_cajas);

			var iframe = $('#video-0')[0];
			//var iframe = $('#MidityVimeo_142343354')[0],
				player = $f(iframe);
			var status = $('.status');
			window.my_player = player;
			
			var seek_buttons = document.getElementById('seeks');
			var seekBtn = seek_buttons.querySelector('.seek');


			// When the player is ready, add listeners for pause, finish, and playProgress
			player.addEvent('ready', function() {
				status.text('ready');


				player.api("getCurrentTime", function (value, player_id) {
					curr_time_value = value;
					//curr_seconds_value = seconds;
					console.log('curr_time_value: '+curr_time_value);
				});

				player.api('getDuration', function (value, player_id) {
				    // Log out the value in the API Console
				    video_duration = value;
				    console.log('getDuration : ' + value);
				});


				player.addEvent('pause', onPause);
				player.addEvent('finish', onFinish);
				player.addEvent('playProgress', onPlayProgress);
			});

			function addEvent(element, eventName, callback) {
                if (element.addEventListener) {
                    element.addEventListener(eventName, callback, false);
                }
                else {
                    element.attachEvent('on' + eventName, callback);
                }
            }
            function addEvents_timeBtns() {
	            for (i_caja = 1; i_caja < num_cajas+1; i_caja++){
	            	var timeBtn = document.getElementById('btn_clock_'+i_caja+'_'+i_comment);
	            	addEvent(timeBtn, 'click', function(e) {
						var i_caja, i_comment;
						console.log('timeBtn, this.id: '+this.id);	// btn_clock_1_0
						var aux_full_id = this.id.split('_');
						i_caja = aux_full_id[2];
						i_comment = aux_full_id[3];
						console.log('added event en timeBtn y vamos a refresh_slider, i_caja: '+i_caja+', i_comment: '+i_comment);
						refresh_slider(i_caja, i_comment);
	                }, false);
	            }
            }
            //addEvents_timeBtns();


			function refresh_slider(i_caja, i_comment) {
				//
                player.api('getCurrentTime', function (value, player_id) {
	                // Log out the value in the API Console
					curr_time_value = value;
	                console.log('getCurrentTime : ' + value);
	            });
	            player.api('getDuration', function (value, player_id) {
	                // Log out the value in the API Console
	                video_duration = value;
	                //console.log('getDuration : ' + value);
	            });
	            
	            refresh_slider_slots(i_caja, i_comment, curr_time_value, video_duration);
						
			}
			//window.refresh_slider(caja,comm) = refresh_slider(caja, comm);
			window.refresh_slider_forzado = function(caja, comm){
				refresh_slider(caja, comm);
			}	


			// Call the API when a button is pressed
			$('button').bind('click', function() {
				player.api($(this).text().toLowerCase());
			});
				


	                        // Call seekTo when seek button clicked
	                        addEvent(seekBtn, 'click', function(e) {
	                            // Don't do anything if clicking on anything but the button (such as the input field)
	                            if (e.target != this) {
	                                return false;
	                            }

									curr_time_value = player.getCurrentTime();
									
	                            // Grab the value in the input field
	                            var seekVal = curr_time_value - this.querySelector('input').value;

	                            // Call the api via froogaloop
	                            player.api('seekTo', seekVal);
	                        }, false);




			function onPause(data, id) {
				//status.text('paused');
    						var aux_seconds = new Date(+1000*data.seconds);
    						//var aux_fecha = formatDateShortPIPS(aux_seconds);
    						var aux_fecha = formatDate(aux_seconds);
				status.text('Paused at '+aux_fecha);
			}

			function onFinish(id) {
				status.text('finished');
			}

			function onPlayProgress(data, id) {
				var log_data = JSON.stringify(data);
				console.log('log_data: '+log_data+', id: '+id);
				//status.text(data.seconds + 's played');
    						var aux_seconds = new Date(+1000*data.seconds);
    						//var aux_fecha = formatDateShortPIPS(aux_seconds);
    						var aux_fecha = formatDate(aux_seconds);
				status.text('Playing '+aux_fecha);
				var time = data.seconds;
				tiempo_actual_play = time;
		
				//llamo a refresh cada segundo, no más
				var tiempo_desde_last_refresh = 0;
				tiempo_desde_last_refresh = time - last_refresh_time;
					//var aux_bool_trigger_refresh = (Math.abs(tiempo_desde_last_refresh) > 0.5) ? "mayor******************************************************" : "menor";
					var refresh_time = 0.2;
					var aux_bool_trigger_refresh = (Math.abs(tiempo_desde_last_refresh) > refresh_time) ? true : false;
					//console.log('tiempo_desde_last_refresh: '+tiempo_desde_last_refresh+', mayor q '+refresh_time+'?: '+aux_bool_trigger_refresh);		
					//console.log('tiempo_actual_play: '+tiempo_actual_play);	
				//if (Math.abs(tiempo_desde_last_refresh) > 0.5) {
				if (aux_bool_trigger_refresh) {
					comments_refresh(time);
					
					//updateSliderRange ( tiempo_actual_play-1, tiempo_actual_play+10 );
					//clockSlider.noUiSlider.set([tiempo_actual_play, tiempo_actual_play+3]);

					//var clockSlider = document.getElementsByClassName('clock-slider');
					//var num_clocksliders = clockSlider.length;
					//for (i_slider = 0; i_slider < num_clocksliders; ++i_slider){
					for (i_caja = 1; i_caja < num_cajas+1; i_caja++){
						//i_comment = 0;	
						check_autorefresh[i_caja] = [];
						// foreach slider, necesito averiguar comment.
						for (i_comment in clockSlider[i_caja]) {	// i_comment es el indice de los elementos de clockslider[i_caja]
							var i_slider_full_id = clockSlider[i_caja][i_comment].id;
							//console.log('onPlayProgress, refresh or not refresh, i_slider_full_id: '+i_slider_full_id);
							check_autorefresh[i_caja][i_comment] = document.getElementById('autorefresh_checkbox_'+i_caja+'_'+i_comment).checked;
							//console.log('check_autorefresh (caja: '+i_caja+', comm: '+i_comment+') is checked?: '+check_autorefresh[i_caja][i_comment]);
							if (check_autorefresh[i_caja][i_comment]) {
								refresh_slider(i_caja, i_comment);
							}
						} // fin "foreach"
					}
				}
				
			}

			window.onPlayProgress_forzado = function(data, id){
				onPlayProgress(data, id);
			}	
			
        });
			
/*
// http://mikeheavers.com/main/code-item/a_simpler_vimeo_froogaloop_javascript_api_example
        $(document).ready(function() {
            // Listen for the ready event for any vimeo video players on the page
            var player = $('#video-0')[0];
			
            $f(player).addEvent('ready', ready);

            function addEvent(element, eventName, callback) {
                if (element.addEventListener) {
                    element.addEventListener(eventName, callback, false);
                }
                else {
                    element.attachEvent(eventName, callback, false);
                }
            }

            function ready(player_id) {
                console.log('ready!');
                var froogaloop = $f(player_id);

                function onPlay() {
                        froogaloop.addEvent('play', function(data) {
                            console.log('play');
                        });
                }


                function onFinish() {
                        froogaloop.addEvent('finish', function(data) {
                            console.log('finish');
                        });
                }

                onPlay();
                onFinish();
            }
        });
*/


