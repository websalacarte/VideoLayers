/* Vimeo_mobile.js */
// http://jsfiddle.net/bdougherty/HfwWY/light/

			//tiempo_actual_play = time;
		
        $(document).ready(function() {	
			var iframe = $('#video-0')[0],
			//var iframe = $('#MidityVimeo_142343354')[0],
				player = $f(iframe),
				status = $('.status');


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

			//clock_slr_1
			var timeBtn = document.getElementById('btn_clock_1');
			function addEvent(element, eventName, callback) {
                    if (element.addEventListener) {
                        element.addEventListener(eventName, callback, false);
                    }
                    else {
                        element.attachEvent('on' + eventName, callback);
                    }
                }

			addEvent(timeBtn, 'click', function(e) {

                            player.api('getCurrentTime', function (value, player_id) {
                                // Log out the value in the API Console
											curr_time_value = value;
                                console.log('getCurrentTime : ' + value);
                            });
                            player.api('getDuration', function (value, player_id) {
                                // Log out the value in the API Console
                                video_duration = value;
                                console.log('getDuration : ' + value);
                            });
                            
                            //updateSliderRange(0, video_duration);
                            // divido el slider en franjas de 20seg, 
                            var intSlotCurrent = Math.floor(curr_time_value / 20);
                            var numSlots = Math.floor(video_duration / 20) + 1;
                            // y muestro 3: el anterior, el actual, y el siguiente
                            var intSlotAnterior = intSlotCurrent > 0 ? intSlotCurrent - 1 : 0;
                            var intSlotNext 	= (numSlots > 3 && intSlotCurrent > 2) ? intSlotCurrent + 1 : 2;
                            // ej: dur_video < 20s (1 slot), curr_time: 11s --> Ant: 0, Curr: 0, Next: 3 --> Ranges: (0, min(20,60))						// atencion: limitar a duración
                            // ej: dur_video = 35s (2 slots), curr_time: 24s --> Ant: 0, Curr: 1, Next: 3 --> Ranges: (0, min(35, 60))
                            // ej: dur_video = 55s (3 slots), curr_time: 44s --> Ant: 1, Curr: 2, Next: 3 --> Ranges: (20, min(55, 60))
                            console.log('numSlots: '+numSlots+', intSlotAnterior: '+intSlotAnterior+', intSlotCurrent: '+intSlotCurrent+', intSlotNext: '+intSlotNext);
							
							var rango_max = Math.min((intSlotNext+1)*20, video_duration);
							updateSliderRange(intSlotAnterior*20, rango_max);
							
							// y tras márgenes, updateo handlers, a: curr_time_value - 1, y curr_time_value + 9
							clockSlider.noUiSlider.set([curr_time_value - 1, curr_time_value + 9]);

                        }, false);

			// Call the API when a button is pressed
			$('button').bind('click', function() {
				player.api($(this).text().toLowerCase());
			});

			function onPause(id) {
				status.text('paused');
			}

			function onFinish(id) {
				status.text('finished');
			}

			function onPlayProgress(data, id) {
				status.text(data.seconds + 's played');
				var time = data.seconds;
				tiempo_actual_play = time;
		
				//llamo a refresh cada segundo, no más
				var tiempo_desde_last_refresh = 0;
				tiempo_desde_last_refresh = time - last_refresh_time;
					//console.log('tiempo_desde_last_refresh: '+tiempo_desde_last_refresh);		
					console.log('tiempo_actual_play: '+tiempo_actual_play);	
				if (Math.abs(tiempo_desde_last_refresh) > 1) {
					comments_refresh(time);
					
					//updateSliderRange ( tiempo_actual_play-1, tiempo_actual_play+10 );
					//clockSlider.noUiSlider.set([tiempo_actual_play, tiempo_actual_play+3]);
				}
				
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


