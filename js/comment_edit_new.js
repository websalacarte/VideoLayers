	$( document ).ready( function(){
		$('.comment-edit-btn_con_slr').click(function(){			// no se usa porque va ligado a evento.
			var _comm_id = $(this).parents( '.comment-holder' ).attr( 'id' );
			var _caja_tmp = $(this).attr('id').split("_");	
			var _video = _caja_tmp[1];
			var _caja = _caja_tmp[2];
			// modificado: quitado 'if (slider...)' --> siempre se actualiza el slider (y tiempo_slr_cajas)
			update_video_box_edit_btn_click(_video, _caja, tiempo_slr_cajas[ 'caja_'+_caja, 'min' ], tiempo_slr_cajas[ 'caja_'+_caja, 'max' ]);
		});
	});

	function update_video_box_edit_btn_click( _vid, _box, _post_id, _comment, _userId, _timein, _timeout ) {		
		// llamado con "actualiza_post_editado_db( video_id, caja_id, post_id, contenido_post, edit_user, timein, timeout );"
		// desde función "on_btn_edit_ok_click( video_id, caja_id, post_id )"
		var _comm_id = _post_id.split("_");	// _post_id = '_88'; comm_id = '88'
		var comm_id = _comm_id[1];
		console.log( 'update comentario: _post_id: ' + _post_id + ', comm_id: ' + comm_id );
		
		if(_comment.length > 0 && _userId != null) {
			//proceed with ajax callback
			$('#comment-edit_'+_box+''+_post_id+' #comment-edit-container').css('border', '1px solid #e1e1e1');
			
			$.post("ajax/comment_update_new.php", 
				{
					task : "comment_update",
					userId : _userId,
					comment_id : comm_id,
					comment : _comment,
					vidId : _vid,
					boxId : _box,
					timein : _timein,
					timeout : _timeout
				}
			)
			.error(
				function()
				{
					console.log("Error: ");
				}
				)
			.success(
				function(data)
				{
					//Success
					//Task: Update html into the ul/li
					console.log("en comment_insert.js, #97, success -> ResponseText: " + data);
					comment_video_box_update( jQuery.parseJSON( data ) );	// para actualizar la pagina, aunque ya lo hacemos desde evento. Reportamos retorno del update en db.
				}
				);
			//console.log(_comment + "User name: " + _userName + ", User Id: " + _userId);
		}
		else {
			console.log("The text area was empty");
			$('#comment-edit_'+_box+''+_post_id+' #comment-edit-container').css('border', '1px solid #f00');
		}
		// remove the text from the textarea ready for another edit (aunque luego borramos el area entera).
		$('#comment-edit_'+_box+''+_post_id+' #comment-post-text_'+_vid ).val("");
	}

	function comment_video_box_update(data) {
		//ya lo hemos hecho en funcions_utils (función "actualiza_post_editado_pagina")
		console.log("updated comentario en DB: comm_id: " + data.comment.comment_id + ", comentario: " + data.comment.comment + ", user: " + data.comment.userId);
	}

	function restablece_btn_edit( video_id, caja_id, post_id, user_id ) {
		// tras edit, (ok o cancel), cuando escribimos post en página (antes de DB y refrescar página), tenemos que poder editar este mismo post.
		// necesito el user_id para la salida del addEventListener (-> para hacer post editable cuando clique)
		// 1) volver a habilitar el botón edit
		// 2) añadir evento al nuevo elemento en DOM.
		// 3) si xy, quitar las clases de editing
		
		// 1)
			// boton 'edit' habilitado
			$( 'li#' + post_id + '.edit-btn' ).removeClass('deshabilitado');	// --> display: inline-block;
		// fin 1)
		// 2)
			console.log('comment_edit#73 - restablece_btn_edit: añadiendo evento para edit post_id: ' + post_id );			// el evento es sobre '<i class="fa">'
			/*
			var btn_edit = $( 'li#_' + post_id + '.edit-btn .fa' );			//$( 'li#_' + post_id + '.edit-btn .fa' );	document.getElementsByTagName("li#_" + post_id + ".edit-btn .fa");
			btn_edit.bind('click', function() {			
				//on_btn_edit_click( post_id, user_id );
				console.log('evento añadido (bind)');
			}, false);
			*/
			/*
			$( 'li#' + post_id + '.edit-btn .fa' ).on('click', function() {		
				on_btn_edit_click( post_id, user_id );
				console.log('evento añadido (bind)');
			}, false);		
			*/
			$( '#area_comentarios' ).on('click', 'li#' + post_id + '.edit-btn .fa', function() {		
				on_btn_edit_click( post_id, user_id );
				console.log('evento añadido (bind)');
			});
		// fin 2)
		// 3)
			var is_overlaid = $('body').hasClass('is_overlaid');
			var is_xy = $('body').hasClass('is_commsallover');
			if (is_overlaid && is_xy) {
				$( "#" + post_id ).removeClass("editing_comms_wrapper");
				$( "#" + post_id + " .comm_handle").removeClass("comm_handle_own");
				$( "#" + post_id + " .comm_content .comment-body").removeClass("editing_comm_content");
			}

		// fin 3)
	}

	/* funcion en comment_insert */
	/*	Borrar funcion tras verificar
	function ___anyadeEventoVote( video_id, caja_id, post_id, user_id ) {
		$( '#area_comentarios' ).on('click', 'li#' + post_id + '.edit-btn .fa', function() {		
			//			************* on_btn_vote_click( post_id, user_id );
			console.log('evento añadido (bind)');
		});
	}
	*/
	
	function anyadeEventoDelete( video_id, caja_id, post_id, user_id ) {
		$( '#area_comentarios' ).on('click', 'li#' + post_id + '.edit-btn .fa', function() {		
			//			************* on_btn_delete_click( post_id, user_id );
			console.log('evento añadido (bind)');
		});
	}

	function on_btn_edit_click( post_id, user_id ) {
		// la llamamos cuando añadimos evento para boton edit, tras publicar post (comment_edit.js #73)
		console.log('funcions_utils, -> .edit-btn clicked');	
		haz_post_editable('_'+post_id, user_id);	// pequeño hack para conservar formatos de haz_post_editable
		// boton 'edit' deshabilitado
		$( 'li#' + post_id + '.edit-btn' ).addClass('deshabilitado');	// display -> none
		console.log('funcions_utils#94 (edit_post) -> deshabilitado edit ' + 'li#' + post_id + '.edit-btn' );
	}
	
	function on_btn_edit_ok_click_nuevoslider( video_id, caja_id, post_id ) {
		//$( ".comment-edit-btn-wrapper .fa" ).click(  function() {		no funciona por jQuery porque lo hemos insertado tras load y jquery no ha podido insertar el listener al inicio.
		// usuario ha editado un comentario, y ha hecho click en 'ok' (lo ha aceptado).

		// NUEVO SLIDER NO TIENE 'SET'
		var post_id_aux = post_id.substr(1, post_id.length);	// corto el "_" inicial

		var aux = clockSlider[caja_id][post_id_aux].noUiSlider.get();
		
		$( '#' + post_id ).attr( 'data-timein', aux[0] );
		$( '#' + post_id ).attr( 'data-timeout', aux[1] );
		// FIN ADAPTACION NUEVO SLIDER

		var contenido_post = $( '#comment-edit_'+caja_id+''+post_id+' textarea' ).val().trim();
		var caja_destino_id = "comment-text_" + video_id + "_" + caja_id + "" + post_id;
		var edit_user = $( '#userId' ).val();
		var timein = $( '#' + post_id ).attr( 'data-timein' );
		var timeout = $( '#' + post_id ).attr( 'data-timeout' );
		console.log( 'comment-edit-btn-wrapper .fa, con video_id: ' + video_id + ', caja_id: ' + caja_id + ', post_id: ' + post_id + ', contenido_post: ' + contenido_post + ', edit_user: ' + edit_user + ', timein: ' + timein + ', timeout: ' + timeout );		
		actualiza_post_editado_db( video_id, caja_id, post_id, contenido_post, edit_user, timein, timeout );																					//***********  donde estoy **********/
		actualiza_post_editado_pagina( video_id, caja_id, post_id, contenido_post, edit_user, caja_destino_id, timein, timeout );
		
		// boton 'edit' habilitado
		var aux_post_id = post_id.split("_");
		$( 'li#' + aux_post_id[1] + '.edit-btn' ).removeClass('deshabilitado');	// --> display: inline-block;
	}
	
	function on_btn_edit_ok_click( video_id, caja_id, post_id ) {
		//$( ".comment-edit-btn-wrapper .fa" ).click(  function() {		no funciona por jQuery porque lo hemos insertado tras load y jquery no ha podido insertar el listener al inicio.
		// usuario ha editado un comentario, y ha hecho click en 'ok' (lo ha aceptado).

		var contenido_post = $( '#comment-edit_'+caja_id+''+post_id+' textarea' ).val().trim();
		var caja_destino_id = "comment-text_" + video_id + "_" + caja_id + "" + post_id;
		var edit_user = $( '#userId' ).val();
		var timein = $( '#' + post_id ).attr( 'data-timein' );
		var timeout = $( '#' + post_id ).attr( 'data-timeout' );
		console.log( 'comment-edit-btn-wrapper .fa, con video_id: ' + video_id + ', caja_id: ' + caja_id + ', post_id: ' + post_id + ', contenido_post: ' + contenido_post + ', edit_user: ' + edit_user + ', timein: ' + timein + ', timeout: ' + timeout );		
		actualiza_post_editado_db( video_id, caja_id, post_id, contenido_post, edit_user, timein, timeout );																					//***********  donde estoy **********/
		actualiza_post_editado_pagina( video_id, caja_id, post_id, contenido_post, edit_user, caja_destino_id, timein, timeout );
		
		// boton 'edit' habilitado
		var aux_post_id = post_id.split("_");
		$( 'li#' + aux_post_id[1] + '.edit-btn' ).removeClass('deshabilitado');	// --> display: inline-block;
	}
	
	function on_btn_edit_cancel_click( video_id, caja_id, post_id ) {
		console.log( 'pendiente btn cancelar' );
		//  "<div class='edit_post' id='edit_post_" + caja_id + '' + post_id + "'>";
		$( '#comment-text_' + video_id + '_' + caja_id + '' + post_id ).css( 'display', 'block' );
		$( '#comment-edit_'+caja_id+''+post_id ).remove();
		// boton 'edit' habilitado
		var aux_post_id = post_id.split("_");
		$( 'li#' + aux_post_id[1] + '.edit-btn' ).removeClass('deshabilitado');	// --> display: inline-block;
	
		var is_overlaid = $('body').hasClass('is_overlaid');
		var is_xy = $('body').hasClass('is_commsallover');
		if (is_overlaid && is_xy) {
			$( "#" + post_id ).removeClass("editing_comms_wrapper");
			$( "#" + post_id + " .comm_handle").removeClass("comm_handle_own");
			$( "#" + post_id + " .comm_content .comment-body").removeClass("editing_comm_content");
		}
	}
	
	function actualiza_post_editado_db(video_id, caja_id, post_id, contenido_post, edit_user, timein, timeout) {
		//contenido textarea -> update BD.
		console.log('en actualiza_post_editado_db -> pendiente: actualización edit post a BD');
		// js -> php -> connect + update -> retorno Ajax -> js -> pg (o no).
		update_video_box_edit_btn_click( video_id, caja_id, post_id, contenido_post, edit_user, timein, timeout );
	}

	function actualiza_post_editado_pagina( video_id, caja_id, post_id, contenido_post, edit_user, caja_destino_id, timein, timeout ) {
		//contenido textarea -> comment-text 
		// recoger tiempos del slider
		// comment-text = $( this ).parent().parent().children( '.comment-text' ).val() = $contenido_textarea
		//var contenido_textarea = 

		//$( '#' + caja_destino_id ).text( contenido_post );
		// modifico BRs
		var comm_con_CRLF = $( '#' + caja_destino_id ).text( contenido_post );

		var comm_con_BR = comm_con_CRLF.text().replace(/\r\n|\r|\n/g,"<br />");
		// en GET: 
		//_comment_con_CR = _comment_con_BR.replace(/<br\s?\/?>/g,"\n");

		$( '#' + caja_destino_id ).html( "<p>" + comm_con_BR + "</p>");

		// antes de mostrar el comentario editado, escondemos/eliminamos la caja de edición			-> .comment-edit, <div id='comment-edit_" + caja_id + '' + post_id + "' class='comment-edit'>
		$( '#comment-edit_' + caja_id + '' + post_id ).css( 'display', 'none');	// pendiente DELETE.
		// mostramos comentario editado
		$( '#' + caja_destino_id ).css( 'display', 'block' );
		console.log('en actualiza_post_editado_pagina -> actualizado EDIT en página: ' + caja_destino_id + ' | pendiente Tiempos.' );
		// restablecemos boton edit y añadimos evento
		restablece_btn_edit( video_id, caja_id, post_id, edit_user );
	}
  
	function haz_post_editable(post_id, user) {
		//substituye <div class="comment-text"> por textarea.
		var post_contenido_antiguo = $( "#" + post_id ).find( ".comment-text" ).text().trim();	// post_id = _88..
		//var aux_comment_text = $( "#" + post_id ).find( ".comment-text" ).html().replace(/<br\s?\/?>/g,"");
		var aux_comment_text = $( "#" + post_id ).find( ".comment-text" ).html().trim().replace(/<br\s?\/?>/g,"\r");		// con \n me escribe doble CR. Añado TRIM porque añade tabuladores al inicio (debo quitarlos)
		var aux_comment_text_sin_P = aux_comment_text.replace(/<\/?p>/g,"");
		console.log('comment_edit haz_post_editable, comment-text: '+aux_comment_text+', aux_comment_text_sin_P: '+aux_comment_text_sin_P);
		var post_video_id = $( "#" + post_id ).parent().attr( 'id' ).split("_");	// id="comments-holder-ul_(vidid)_(cajaid)"
		var video_id = post_video_id[1];
		var caja_id = post_video_id[2];
		/*
			<div class="comment-insert">	
				<h3 class="who-says"><span>Dice:</span> JosepFB</h3>
				<div class="edit_post">
					<div id="btn_clock_1" class="btn_clock">
						<i class="fa fa-clock-o"></i>
					</div>
					<div id="clock_slr_1" class="clock_slr" style="display: none;">
						<p class="marge_temps">
							<label for="amount_1">Margen tiempo:</label>
							<input type="text" id="amount_1" class="amount" style="border:0; color:#f6931f; font-weight:bold;">
						</p>
						<div id="slr_1" class="slr slider-range"></div>
						<div class="comment-set-btn-wrapper">
							<div id="btn_slr_1" class="btn_slr ">Set</div>
						</div>
						<div class="clr"></div>
					</div>
				</div>
				<div id="comment-post-container" class="comment-insert-container">
					<textarea id="comment-post-text_73589408_1" class="comment-post-text comment-insert-text"></textarea>
				</div>	
				<div id="comment-post-btn_73589408_1" class="comment-post-btn_con_slr comment-post-btn-wrapper">Post</div>
			</div>
		*/

		var edit_area = "";
		edit_area += "<div class='clr'></div>";
		
		var is_overlaid = $('body').hasClass('is_overlaid');
		var is_xy = $('body').hasClass('is_commsallover');
		/*
		if (is_overlaid && is_xy) {
			edit_area += "<div id='editing_comms_wrapper' class='editing_comms_wrapper'>";
			edit_area += "<div id='mi_editing_comm_" + caja_id + '' + post_id + "' class='comm_handle comm_handle_own'><a><i class='fa fa-user-o'></i></a></div>";
			edit_area += "<div id='editing_comm_content_" + caja_id + '' + post_id + "' class='editing_comm_content'>";
		}
		*/

		//edit_area += "<div class='comment-insert'><h3 class='who-says'><span>Dice:</span> " + $( '#userId' ).val() + "</h3>";
			edit_area += "<div id='comment-edit_" + caja_id + '' + post_id + "' class='comment-edit'>";
			edit_area += "<div class='edit_post' id='edit_post_" + caja_id + '' + post_id + "'>";
			edit_area += 	"<div id='btn_clock_" + caja_id + '' + post_id + "' class='btn_clock btn_clock_" + caja_id + "'><i class='fa fa-clock-o'></i></div>";
			edit_area += 	"<div id='clock_slr_" + caja_id + '' + post_id + "' class='clock_slr' style='display: none;'>";
			edit_area += 		"<p class='marge_temps' style='display: none;'><label for='amount_" + caja_id + '' + post_id + "'>Margen tiempo:</label><input type='text' id='amount_" + caja_id + '' + post_id + "' class='amount' style='border:0; color:#f6931f; font-weight:bold;'></p>";
			edit_area += 		"<div id='slr_" + caja_id + '' + post_id + "' class='slr slider-range' style='display: none;'></div>";
			//edit_area += 		"<div class='comment-set-btn-wrapper'><div id='btn_slr_" + caja_id + '' + post_id + "' class='btn_slr '>Set</div></div>";

			// Añado el slider synchronizado

			edit_area += "								<div id='clock-slider_" + caja_id + '' + post_id + "' class='clock-slider clock-slider_" + caja_id + "'></div>";
											
			edit_area += "								<span class='clock-slider-val start' id='event-start_" + caja_id + '' + post_id + "' style='display: none;'>0</span>";
			edit_area += "								<span class='clock-slider-val finish' id='event-end_" + caja_id + '' + post_id + "' style='display: none;'>0</span>";
											
			edit_area += "								<div class='check_autorefresh' id='check_autorefresh_" + caja_id + '' + post_id + "'>";
			edit_area += "									<label for='autorefresh_checkbox_" + caja_id + '' + post_id + "'>Auto-refresh slider?</label>";
			edit_area += "									<input type='checkbox' value='auto-refresh' name='autorefresh_checkbox' id='autorefresh_checkbox_" + caja_id + '' + post_id + "'>";
			edit_area += "								</div>";
			edit_area += "																<!-- zoom -->";
			edit_area += "																<div class='sp-zoom' id='zoom_" + caja_id + '' + post_id + "'>";
			edit_area += "																    <div class='sp-label'><h4 class='slider_zoom_label'>slider zoom:</h4></div>";
			edit_area += "																    <div class='sp-minus fff'> <a class='zoom_btn zoom_minus' href='#''><i class='fa fa-minus'></i></a>";
			edit_area += "																    </div>";
			edit_area += "																    <div class='sp-input'>";
			edit_area += "																        <input type='text' class='zoom-input' value='1' />";
			edit_area += "																    </div>";
			edit_area += "																    <div class='sp-plus fff'> <a class='zoom_btn zoom_plus' href='#''><i class='fa fa-plus'></i></a>";
			edit_area += "																    </div>";
			edit_area += "																</div>";

			edit_area += "								<h4 class='manual_edit' id='manual_edit_" + caja_id + '' + post_id + "'>Manual edit:</h4>";
			edit_area += "								<table class='inputs_headers' id='inputs_headers_" + caja_id + '' + post_id + "'>";
			edit_area += "									<tr>";
			edit_area += "										<th><span class='clock_label'>&nbsp;</span></th>";
			edit_area += "										<th><span class='val'>min</span></th>";
			edit_area += "										<th><span id='sep1' class='sep'>&nbsp;</span></th>";
			edit_area += "										<th><span class='val'>sec</span></th>";
			edit_area += "										<th><span id='sep2' class='sep'>&nbsp;</span></th>";
			edit_area += "										<th><span class='val'>milisec</span></th>";
			edit_area += "									</tr>";
			edit_area += "									<tr id='inputs_start_" + caja_id + '' + post_id + "'>";
			edit_area += "										<td><span class='clock_label inicio'>In:</span></td>";
			edit_area += "										<td><input class='input_mins inicio' id='clock-slider-val-start-mins_" + caja_id + '' + post_id + "' value='00' /></td>";
			edit_area += "										<td><span class='sep inicio'>:</span></td>";
			edit_area += "										<td><input class='input_secs inicio' id='clock-slider-val-start-secs_" + caja_id + '' + post_id + "' value='00' /></td>";
			edit_area += "										<td><span class='sep inicio'>.</span></td>";
			edit_area += "										<td><input class='input_milis inicio' id='clock-slider-val-start-milis_" + caja_id + '' + post_id + "' value='000' /></td>";
			edit_area += "									</tr>";
			edit_area += "									<tr id='inputs_finish_" + caja_id + '' + post_id + "'>";
			edit_area += "										<td><span class='clock_label fin'>Out:</span></td>";
			edit_area += "										<td><input class='input_mins fin' id='clock-slider-val-finish-mins_" + caja_id + '' + post_id + "' value='00' /></td>";
			edit_area += "										<td><span class='sep fin'>:</span></td>";
			edit_area += "										<td><input class='input_secs fin' id='clock-slider-val-finish-secs_" + caja_id + '' + post_id + "' value='00' /></td>";
			edit_area += "										<td><span class='sep fin'>.</span></td>";
			edit_area += "										<td><input class='input_milis fin' id='clock-slider-val-finish-milis_" + caja_id + '' + post_id + "' value='000' /></td>";
			edit_area += "									</tr>";
			edit_area += "								</table>";


			edit_area += "<div class='clr'></div>";
			edit_area += "</div></div>";
			edit_area += "";
			edit_area += "<div id='comment-edit-container' class='comment-insert-container'>";
			edit_area += "<textarea id='edit-post-text_" + video_id + "' class='comment-post-text edit-post-text comment-insert-text'>" + aux_comment_text_sin_P + "</textarea>" ;
			edit_area += "<div id='comment-setedit-btn_" + video_id + "_" + caja_id + '' + post_id + "' class='comment-post-btn_con_slr comment-edit-btn-wrapper'><i class='fa fa-check' title='Save modifications to this subtitle'></i></div>";				// 'SET edit'
			edit_area += "<div id='comment-canceledit-btn_" + video_id + "_" + caja_id + '' + post_id + "' class='comment-post-btn_con_slr comment-noedit-btn-wrapper'><i class='fa fa-times' title='Cancel modifications to this subtitle'></i></div>";			// 'CANCEL edit'

			edit_area += '	  <div id="pos_comm">';
			edit_area += '	  	<input type="hidden" class="posx" id="posx_' + caja_id + '' + post_id + '" value="" />';
			edit_area += '	  	<input type="hidden" class="posy" id="posy_' + caja_id + '' + post_id + '" value="" />';
			edit_area += '	  	<input type="hidden" class="width" id="width_' + caja_id + '' + post_id + '" value="" />';
			edit_area += '	  	<input type="hidden" class="height" id="height_' + caja_id + '' + post_id + '" value="" />';
			edit_area += '	  </div>';

			edit_area += "</div>";
		// fin edit_area (no xy)
		
		/*
		if (is_overlaid && is_xy) {
			edit_area += "</div>";
			edit_area += "</div>";
			// anyado clase ".editing_comm_content" (tras handle) equivalente a "#new_comm_content" para estilado.
		}
		*/

		$( "#" + post_id + " .comment-text" ).after( edit_area );	// id: EDIT-post-text_(vidid)
		$( "#" + post_id + " .comment-text" ).css( 'display', 'none' );
		
		// si xy, modifico clases:
		if (is_overlaid && is_xy) {
			$( "#" + post_id ).addClass("editing_comms_wrapper");
			$( "#" + post_id + " .comm_handle").addClass("comm_handle_own");
			$( "#" + post_id + " .comm_content .comment-body").addClass("editing_comm_content");
		}
		// fin modificacion clases (si xy)

		// anyado eventos (xy)
		if (is_overlaid && is_xy) {
			anyadir_evento_click_boton_post( $('#comment-post-btn_'+video_id_actual+'_'+caja_actual) );
			//onBtnClockClick(this);	// FUERZO la inicializacion del slider para registrarlo.
	    	$('#editing_comm_content_' + caja_id + '' + post_id + ' .btn_clock i').on('click touchstart', function() {
	    		// inicializo boton clock
	    		onBtnClockClick(this);	// ver #896
				console.log('anyadido evento click en btnClock editing box comm');
	    	});
	    	$("#editing_comms_wrapper").draggable({
	    		containment: "#area_comentarios", 
	    		scroll: false, 
	    		drag: function(event, ui) {	// !important (si no, solo lo llama en click)
	    			//posiciona_nuevo_comentario();
					x = ui.position.left; //window.event.pageX,
					y = ui.position.top; //window.event.pageY;
					//console.log(x, y); 
					//alert("x: "+x+", y: "+y);
					$('#editing_comms_wrapper .posx').val(x);
					$('#editing_comms_wrapper .posy').val(y);
	    		}, 
	    		stop: posiciona_nuevo_comentario()
	    	} );	//({ handle: ".comm_handle" });	//$(".comm_handle").draggable({ handle: "a" });
	    	$( "#editing_comms_wrapper" ).disableSelection();

	    	// si hacemos click en el handle:
	    	var mi_editing_comm = $('mi_editing_comm_' + caja_id + '' + post_id);	// $('#'+id_new_comm);
	    	mi_editing_comm.on( 'click touchstart', function(){
		    	$('.editing_comm_content').toggle();	// toggles visibility only.
			});
	    	/*
		    	// + anyade onclick event al boton Cancel
				$("#area_comentarios .editing_comms_wrapper .comment-cancel-btn-wrapper").on('click touchstart', function() {
		    		newcommentbox_already_opened = false;
		    		//destruye newcommentbox en DOM, si existe.
		    		$('#area_comentarios .new_comms_wrapper').remove();
			    });
			*/	    	
		}
		
		coger_tiempo_slr_post[ caja_id ][post_id] = false;
		coger_tiempo_slr_editpost[post_id] = false;

		//inicializa_slider( "slr_" + caja_id + '' + post_id );
		// primero verificaciones

		// inicializamos, pero sólo si no existe ya.
		var post_id_aux = post_id.substr(1, post_id.length);
		if ( (typeof clockSlider[caja_id] == "undefined") || !(post_id_aux in clockSlider[caja_id]) ) {
			// no existe aún.

			console.log('haz_post_editable, inicializando slider, caja_id: '+caja_id+', comm: '+post_id_aux+', -> inicializa_nuevo_slider');
			inicializa_nuevo_slider( caja_id, post_id_aux );
			console.log('on_btn_clock_click_nuevoslider, -> bind_clock_inputs');
			bind_clock_inputs(caja_id, post_id_aux);
		}
		else {
			// ya existe, 1o destroy para 2o inicializacion de nuevo (si no, no lo muestra)
			clockSlider[caja_id][post_id_aux].noUiSlider.destroy();
			console.log('haz_post_editable, inicializando slider, caja_id: '+caja_id+', comm: '+post_id_aux+', -> inicializa_nuevo_slider');
			inicializa_nuevo_slider( caja_id, post_id_aux );
			console.log('on_btn_clock_click_nuevoslider, -> bind_clock_inputs');
			bind_clock_inputs(caja_id, post_id_aux);
		}
		// actualizar valores originales en (->& inputs)
		var time_in = $( '#_' + post_id_aux ).attr( 'data-timein' );
		var time_out = $( '#_' + post_id_aux ).attr( 'data-timeout' );
		// antes de actualizar el slider, debo posicionarlo en el slot que necesito.
		console.log('haz_post_editable, -> refresh_slider_slots(caja_id='+caja_id+', post_id_aux='+post_id_aux+', time_in='+time_in+', video_duration='+video_duration+')');
		refresh_slider_slots(caja_id, post_id_aux, time_in, video_duration);
		clockSlider[caja_id][post_id_aux].noUiSlider.set([time_in, time_out]);	//formato correcto (slider)

		anyade_evento_zoom(caja_id, post_id_aux);
		console.log('anyade_evento_zoom para caja: '+caja_id+', comentario: '+post_id_aux);



		console.log('editando post');
		// boton 'edit' deshabilitado
		var aux_post_id = post_id.split("_");
		$( 'li#' + aux_post_id[1] + '.edit-btn' ).addClass('deshabilitado');	// display -> none
		console.log('comment-edit#175 -> deshabilitado edit ' + 'li#' + aux_post_id[1] + '.edit-btn' );
		
		// añado listener para btn_clock click (ya que lo hemos insertado en DOM después de $.ready)
		var btn_edit_clock = document.getElementById( 'btn_clock_' + caja_id + post_id );
		btn_edit_clock.addEventListener('click', function() {
			//OLD on_btn_clock_click( 'btn_clock_' + caja_id + post_id );
			on_btn_clock_click_nuevoslider( 'btn_clock_' + caja_id + post_id );
		}, false);
		
		/*
			// añado listener para SLIDER de edit (ya que lo hemos insertado en DOM después de $.ready)
			var btn_slider = document.getElementById( 'slr_' + caja_id + '' + post_id );
			btn_slider.addEventListener('click', function() {
				on_slider_click( video_id, caja_id, post_id );											// ********* PENDIENTE ************** //
			}, false);
			
			// añado listener para btn_Set_SLIDER de edit (ya que lo hemos insertado en DOM después de $.ready)
			var btn_slider_set = document.getElementById( 'btn_slr_' + caja_id + '' + post_id );
			btn_slider_set.addEventListener('click', function() {
				on_slider_set_click( video_id, caja_id, post_id );											// ********* PENDIENTE ************** //
			}, false);
		*/
		
		// añado listener para btn_edit_ok click (botón 'Ok') (ya que lo hemos insertado en DOM después de $.ready)
		var btn_edit_ok = document.getElementById( 'comment-setedit-btn_' + video_id + '_' + caja_id + post_id );
		btn_edit_ok.addEventListener('click', function() {
			on_btn_edit_ok_click_nuevoslider( video_id, caja_id, post_id );
		}, false);
		
		// añado listener para btn_edit_cancel click (ya que lo hemos insertado en DOM después de $.ready)
		var btn_edit_cancel = document.getElementById( 'comment-canceledit-btn_' + video_id + '_' + caja_id + post_id );
		btn_edit_cancel.addEventListener('click', function() {
			on_btn_edit_cancel_click( video_id, caja_id, post_id );											//********* PENDIENTE **************//
		}, false);

		// si xy, posicionamiento (drag)
		function posiciona_nuevo_comentario() {
			x = window.event.pageX,
			y = window.event.pageY;
			console.log(x, y); 
			//alert("x: "+x+", y: "+y);
			$('.editing_comms_wrapper .posx').val(x);
			$('.editing_comms_wrapper .posy').val(y);
			//$('#new_comms_wrapper .width').val(w);
			//$('#new_comms_wrapper .height').val(h);
		}
		$(".editing_comms_wrapper").draggable({
			    		containment: "#area_comentarios", 
			    		scroll: false, 
			    		drag: function(event, ui) {	// !important (si no, solo lo llama en click)
			    			//posiciona_nuevo_comentario();
							x = ui.position.left; //window.event.pageX,
							y = ui.position.top; //window.event.pageY;
							//console.log(x, y); 
							//alert("x: "+x+", y: "+y);
							$('.editing_comms_wrapper .posx').val(x);
							$('.editing_comms_wrapper .posy').val(y);
			    		}, 
			    		stop: posiciona_nuevo_comentario()
			    	} );	
	}
  
  	function on_slider_click( video_id, caja_id, post_id ) {			// recibe el evento "inicializa"
		console.log( 'on_slider_click (pendiente), con video_id: '+video_id+', caja_id: '+caja_id+', post_id: '+post_id );
		/*
			$( '#slr_' + caja_id + '' + post_id ).slider({
				range: true,
				min: 0,
				max: duracion_max_video,
				//values: [ video_id_actual, video_id_actual+5 ],
				values: [ tiempo_actual_play, tiempo_actual_play+5 ],
				slide: function( event, ui ) {
					//$( "#amount_1" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
					console.log(event);
					console.log(ui);		 
					//var sl_0 = $( "#slr_1" ).slider( "values", 0 );
					var sl_0 = $( this ).slider( "values", 0 );
					var sl_1 = $( this ).slider( "values", 1 );

					var min_ini = Math.floor( sl_0 / 60 ) < 10 ? "0"+Math.floor( sl_0 / 60 ) : Math.floor( sl_0 / 60 );
					var seg_ini = Math.floor( sl_0 % 60 ) < 10 ? "0"+Math.floor( sl_0 % 60 ) : Math.floor( sl_0 % 60 );
					var min_fin = Math.floor( sl_1 / 60 ) < 10 ? "0"+Math.floor( sl_1 / 60 ) : Math.floor( sl_1 / 60 );
					var seg_fin = Math.floor( sl_1 % 60 ) < 10 ? "0"+Math.floor( sl_1 % 60 ) : Math.floor( sl_1 % 60 );

					//$( "#amount_1" ).val( "De: " + min_ini + ':' + seg_ini + " a: " + min_fin + ':' + seg_fin );
					//NO TENEMOS (this) --> comentamos $( this ).parent().find('.amount').val( "De: " + min_ini + ':' + seg_ini + " a: " + min_fin + ':' + seg_fin );
					$( "#amount_"+caja_id+""+post_id ).parent().find('.amount').val( "De: " + min_ini + ':' + seg_ini + " a: " + min_fin + ':' + seg_fin );
					//$( this ).parent().find( '.btn_slr' ).addClass( 'btn_set_pending' );
					$( "#amount_"+caja_id+""+post_id ).parent().parent().find( '.btn_slr' ).addClass( 'btn_set_pending' );
					// variable para indicar al botón 'OK' de esta caja que el tiempo correcto es el del slider.
					coger_tiempo_slr_post[ caja_id ][post_id] = true;
					//console.log('coger_tiempo inicializado a true: ' + coger_tiempo_slr_post[ caja_id ][post_id] );
					console.log('on_slider_click, #203' + ", -> De: " + min_ini + ':' + seg_ini + " a: " + min_fin + ':' + seg_fin);
				}
		    });
		*/	
  	}
  
  	function on_slider_set_click( video_id, caja_id, post_id ) {
		console.log( 'on_slider_set_click (pendiente), con video_id: '+video_id+', caja_id: '+caja_id+', post_id: '+post_id );
		
		var aux = $( '#slr_' + caja_id + '' + post_id ).slider( "option", "values");
		console.log('aux[0]: ' + aux[0] + ', aux[1]: ' + aux[1]);			
		tiempo_slr_cajas_post[ 'caja_'+caja_id, 'min' ][ post_id ] = aux[0];
		tiempo_slr_cajas_post[ 'caja_'+caja_id, 'max' ][ post_id ] = aux[1];
		console.log(' min: ' + tiempo_slr_cajas_post[ 'caja_'+caja_id, 'min' ][ post_id ] + ', max: ' + tiempo_slr_cajas_post[ 'caja_'+caja_id, 'max' ][ post_id ] );
		$( '#btn_slr_' + caja_id + '' + post_id ).removeClass( 'btn_set_pending' );																	// ¿ Existe como doc.ElemByid ??
		
		coger_tiempo_slr_editpost[post_id] = true;	// ******** Aquí siempre será true, porque para eso está SET.
		
		console.log('coger_tiempo: ' + coger_tiempo_slr_editpost[post_id] );
		//if( coger_tiempo_slr_post[ caja_id ][post_id] ) {
		if( coger_tiempo_slr_editpost[post_id] ) {
			//comment_video_box_post_btn_click(video_id, caja_id, tiempo_slr_cajas_post[ 'caja_'+caja_id, 'min' ][ post_id ], tiempo_slr_cajas_post[ 'caja_'+caja_id, 'max' ][ post_id ] );
			console.log( 'pendiente update, con valores tiempo_slr_cajas: ' + tiempo_slr_cajas_post[ "caja_"+caja_id, "min" ][ post_id ] + ', ' + tiempo_slr_cajas_post[ "caja_"+caja_id, "max" ][ post_id ] );
			
			// modificar comment-holder data-timein y data-timeout
			//$( '#slr_' + caja_id + '' + post_id ).parents( post_id ).attr( 'data-timein', tiempo_slr_cajas_post[ "caja_"+caja_id, "min" ][ post_id ] );
			//$( '#slr_' + caja_id + '' + post_id ).parents( post_id ).attr( 'data-timeout', tiempo_slr_cajas_post[ "caja_"+caja_id, "max" ][ post_id ] );
			$( '#' + post_id ).attr( 'data-timein', tiempo_slr_cajas_post[ "caja_"+caja_id, "min" ][ post_id ] );
			$( '#' + post_id ).attr( 'data-timeout', tiempo_slr_cajas_post[ "caja_"+caja_id, "max" ][ post_id ] );
		}
		else {
			_timein = tiempo_actual_play;
			_timeout = tiempo_actual_play + 5;
			//comment_video_box_post_btn_click(video_id, caja_id, _timein, _timeout);
			console.log( 'pendiente update, con valores timein: ' + _timein + ', timeout: ' + _timeout );
			// comment-holder data-timein...
			$( '#' + post_id ).attr( 'data-timein', _timein );
			$( '#' + post_id ).attr( 'data-timeout', _timeout );
		}
		
		
		// era toggle, pero debe ser 'close' sí o sí. (si venimos de 'SET' será close, y si hacemos post sin 'SET' permanecerá closed.
		//$("#clock_slr_"+caja_id+''+post_id).toggle();
  	}
  
  	function on_btn_clock_click_nuevoslider(btn_id) {
  		//nuevo slider, visibilidad y inicialización
	  	var caja_tmp = btn_id.split("_");
		var caja = caja_tmp[2];
		var edit_comm_id = caja_tmp[3];

		// inicializamos, pero sólo si no existe ya.
		if ( !(edit_comm_id in clockSlider[caja]) ) {
			// no existe aún.

			console.log('on_btn_clock_click_nuevoslider, caja: '+caja+', comm: '+edit_comm_id+', -> inicializa_nuevo_slider');
			inicializa_nuevo_slider( caja, edit_comm_id );
			console.log('on_btn_clock_click_nuevoslider, -> bind_clock_inputs');
			bind_clock_inputs(caja, edit_comm_id);
		}
		else {
			// ya existe, sólo refrescamos. ie, aquí do nothing.
		}
		// actualizar valores originales en (->& inputs)
		var time_in = $( '#_' + edit_comm_id ).attr( 'data-timein' );
		var time_out = $( '#_' + edit_comm_id ).attr( 'data-timeout' );
		// antes de actualizar el slider, debo posicionarlo en el slot que necesito.
		console.log('on_btn_clock_click_nuevoslider, -> refresh_slider_slots(caja='+caja+', edit_comm_id='+edit_comm_id+', time_in='+time_in+', video_duration='+video_duration+')');
		refresh_slider_slots(caja, edit_comm_id, time_in, video_duration);
		clockSlider[caja][edit_comm_id].noUiSlider.set([time_in, time_out]);	//formato correcto (slider)

		// set auto-update to DON'T AUTo-UPDATE slider.
		//..

		console.log('on_btn_clock_click_nuevoslider, -> toggle');
		$( "#clock_slr_" + caja + "_" + edit_comm_id ).toggle();
		if ( $( "#clock_slr_" + caja + "_" + edit_comm_id ).is(":visible") ) {
			// añado class 'isvisible' para estilar timeBtn y cambiar su tooltip title
			$( "#btn_clock_" + caja + "_" + edit_comm_id ).attr("title", "Hide the timing area for this subtitle");
			$( "#btn_clock_" + caja + "_" + edit_comm_id + " i ").addClass("isvisible");
		}
		else{
			// quito class 'isvisible' para estilar timeBtn y cambiar su tooltip title
			$( "#btn_clock_" + caja + "_" + edit_comm_id ).attr("title", "Show the timing area for this subtitle");
			$( "#btn_clock_" + caja + "_" + edit_comm_id + " i ").removeClass("isvisible");
		}
  	}

	function on_btn_clock_click(btn_id) {
		//var caja_tmp = $('#'+btn_id).parent().attr('id').split("_");			// <div id='btn_clock_" + caja_id + '' + post_id + "' class='btn_clock'><i class='fa fa-clock-o'></i></div>
		var caja_tmp = btn_id.split("_");
		var caja = caja_tmp[2];
		var edit_comm_id = caja_tmp[3];
	  
	  
		inicializa_slider( "slr_" + caja + '_' + edit_comm_id );
		
		//console.log('clicado btn_clock con id:' + caja_tmp);		
		$( "#slr_" + caja + '_' + edit_comm_id ).slider( "option", "values", [ tiempo_actual_play, tiempo_actual_play + 5 ] );
		//actualiza_slider( tiempo_actual_play, caja );
		$( "#clock_slr_" + caja + "_" + edit_comm_id ).toggle();
		console.log('(por addeventlistener. Clicado btn_clock. caja: ' + caja + ', edit_comm_id: ' + edit_comm_id + ', caja_tmp length: ' + caja_tmp.length);
	}	

  	function inicializa_slider( id_slider ) {
	
		$( "#" + id_slider ).slider({
			range: true,
			min: 0,
			max: duracion_max_video,
			values: [ tiempo_actual_play, tiempo_actual_play+5 ],
			slide: function( event, ui ) {
			var sl_0 = $( this ).slider( "values", 0 );
			var sl_1 = $( this ).slider( "values", 1 );

			var min_ini = Math.floor( sl_0 / 60 ) < 10 ? "0"+Math.floor( sl_0 / 60 ) : Math.floor( sl_0 / 60 );
			var seg_ini = Math.floor( sl_0 % 60 ) < 10 ? "0"+Math.floor( sl_0 % 60 ) : Math.floor( sl_0 % 60 );
			var min_fin = Math.floor( sl_1 / 60 ) < 10 ? "0"+Math.floor( sl_1 / 60 ) : Math.floor( sl_1 / 60 );
			var seg_fin = Math.floor( sl_1 % 60 ) < 10 ? "0"+Math.floor( sl_1 % 60 ) : Math.floor( sl_1 % 60 );

			$( this ).parent().find('.amount').val( "De: " + min_ini + ':' + seg_ini + " a: " + min_fin + ':' + seg_fin );
			$( this ).parent().find( '.btn_slr' ).addClass( 'btn_set_pending' );
			// necesito caja_id y post_id. id_slider = slr_(caja_id)_(post_id)
			var aux = id_slider.split("_");
			var _caja_id = aux[1];
			var _post_id = aux[2];
			//coger_tiempo_slr_editpost[_post_id] = true;

			}
		});
  	}

