$( document ).ready( function(){
	
	
	$('.comment-edit-btn_con_slr').click(function(){			// no se usa porque va ligado a evento.
		var _comm_id = $(this).parents( '.comment-holder' ).attr( 'id' );
		var _caja_tmp = $(this).attr('id').split("_");	
		var _video = _caja_tmp[1];
		var _caja = _caja_tmp[2];
		if( coger_tiempo_slr[ _caja ] ) {
			update_video_box_edit_btn_click(_video, _caja, tiempo_slr_cajas[ 'caja_'+_caja, 'min' ], tiempo_slr_cajas[ 'caja_'+_caja, 'max' ]);
		}
		else {
			_timein = tiempo_actual_play;
			_timeout = tiempo_actual_play + 5;
			update_video_box_edit_btn_click(_comm_id, _video, _caja, _timein, _timeout);
		}
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
		
		$.post("ajax/comment_update.php", 
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
	
	// 1)
		// boton 'edit' habilitado
		$( 'li#' + post_id + '.edit-btn' ).removeClass('deshabilitado');	// --> display: inline-block;
	
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
	$( '#' + caja_destino_id ).text( contenido_post );
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
	//edit_area += "<div class='comment-insert'><h3 class='who-says'><span>Dice:</span> " + $( '#userId' ).val() + "</h3>";
	edit_area += "<div id='comment-edit_" + caja_id + '' + post_id + "' class='comment-edit'>";
	edit_area += "<div class='edit_post' id='edit_post_" + caja_id + '' + post_id + "'>";
	edit_area += 	"<div id='btn_clock_" + caja_id + '' + post_id + "' class='btn_clock'><i class='fa fa-clock-o'></i></div>";
	edit_area += 	"<div id='clock_slr_" + caja_id + '' + post_id + "' class='clock_slr' style='display: none;'>";
	edit_area += 		"<p class='marge_temps'><label for='amount_" + caja_id + '' + post_id + "'>Margen tiempo:</label><input type='text' id='amount_" + caja_id + '' + post_id + "' class='amount' style='border:0; color:#f6931f; font-weight:bold;'></p>";
	edit_area += 		"<div id='slr_" + caja_id + '' + post_id + "' class='slr slider-range'></div>";
	edit_area += 		"<div class='comment-set-btn-wrapper'><div id='btn_slr_" + caja_id + '' + post_id + "' class='btn_slr '>Set</div></div><div class='clr'></div>";
	edit_area += "</div></div>";
	edit_area += "";
	edit_area += "<div id='comment-edit-container' class='comment-insert-container'>";
	edit_area += "<textarea id='edit-post-text_" + video_id + "' class='comment-post-text edit-post-text comment-insert-text'>" + post_contenido_antiguo + "</textarea>" ;
	edit_area += "<div id='comment-setedit-btn_" + video_id + "_" + caja_id + "' class='comment-post-btn_con_slr comment-edit-btn-wrapper'><i class='fa fa-check'></i></div>";				// 'SET edit'
	edit_area += "<div id='comment-canceledit-btn_" + video_id + "_" + caja_id + "' class='comment-post-btn_con_slr comment-noedit-btn-wrapper'><i class='fa fa-times'></i></div>";			// 'CANCEL edit'
	edit_area += "</div>";
	$( "#" + post_id + " .comment-text" ).after( edit_area );	// id: EDIT-post-text_(vidid)
	$( "#" + post_id + " .comment-text" ).css( 'display', 'none' );
	
	
	coger_tiempo_slr_post[ caja_id ][post_id] = false;
	coger_tiempo_slr_editpost[post_id] = false;
	//inicializa_slider( "slr_" + caja_id + '' + post_id );
	console.log('editando post');
		// boton 'edit' deshabilitado
		var aux_post_id = post_id.split("_");
		$( 'li#' + aux_post_id[1] + '.edit-btn' ).addClass('deshabilitado');	// display -> none
		console.log('comment-edit#175 -> deshabilitado edit ' + 'li#' + aux_post_id[1] + '.edit-btn' );
	
	// añado listener para btn_clock click (ya que lo hemos insertado en DOM después de $.ready)
	var btn_edit_clock = document.getElementById( 'btn_clock_' + caja_id + post_id );
	btn_edit_clock.addEventListener('click', function() {
		on_btn_clock_click( 'btn_clock_' + caja_id + post_id );
	}, false);
	
	// añado listener para SLIDER de edit (ya que lo hemos insertado en DOM después de $.ready)
	var btn_slider = document.getElementById( 'slr_' + caja_id + '' + post_id );
	btn_slider.addEventListener('click', function() {
		on_slider_click( video_id, caja_id, post_id );											//********* PENDIENTE **************//
	}, false);
	
	// añado listener para btn_Set_SLIDER de edit (ya que lo hemos insertado en DOM después de $.ready)
	var btn_slider_set = document.getElementById( 'btn_slr_' + caja_id + '' + post_id );
	btn_slider_set.addEventListener('click', function() {
		on_slider_set_click( video_id, caja_id, post_id );											//********* PENDIENTE **************//
	}, false);
	
	// añado listener para btn_edit_ok click (ya que lo hemos insertado en DOM después de $.ready)
	var btn_edit_ok = document.getElementById( 'comment-setedit-btn_' + video_id + '_' + caja_id );
	btn_edit_ok.addEventListener('click', function() {
		on_btn_edit_ok_click( video_id, caja_id, post_id );
	}, false);
	
	// añado listener para btn_edit_cancel click (ya que lo hemos insertado en DOM después de $.ready)
	var btn_edit_cancel = document.getElementById( 'comment-canceledit-btn_' + video_id + '_' + caja_id );
	btn_edit_cancel.addEventListener('click', function() {
		on_btn_edit_cancel_click( video_id, caja_id, post_id );											//********* PENDIENTE **************//
	}, false);
	
  }
  
  function on_slider_click( video_id, caja_id, post_id ) {			// recibe el evento "inicializa"
	console.log( 'on_slider_click (pendiente), con video_id: '+video_id+', caja_id: '+caja_id+', post_id: '+post_id );
/*	$( '#slr_' + caja_id + '' + post_id ).slider({
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

