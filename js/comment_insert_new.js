/*
	v1.1 	12-12-2017 		Habilito imagen en comment_video_box_insert.
	v1.2 	19-12-2017 		Defino funcion anyadir_evento_click_boton_post para version 'x,y' y la saco de doc.ready para poderla llamar en la pagina, en $("#new_comment_button").on('click...')
								Retoco el 'onclick' del boton post para obtener _caja_activa desde caja_actual y llamar php con valor adecuado.
*/
	function anyadir_evento_click_boton_post(boton_post) {
		boton_post.on('click touchstart', function(){
			var _caja_tmp = $(this).attr('id').split("_");	
			var _video = _caja_tmp[1];
			var _caja = _caja_tmp[2];

			if ( $('body').hasClass('is_overlaid') && $('body').hasClass('is_commsallover') ) {
				// _caja==5 y no representa la caja correcta. El valor correcto esta en caja_actual.
					// formas alternativas: $(#area_comentarios).find(.caja_comentarios):isvisible. get id de .caja_comentarios|visible, y obtener id caja visible.
				_caja_activa = ( (typeof caja_actual !== 'undefined') && (caja_actual != 0) ) ? caja_actual : _caja;
				console.log('_caja es '+_caja);
			}
					
			// el tiempo depende:
				// si no hemos cambiado via 'SET', debe ser _timein = tiempo_actual_play y _timeout = tiempo_actual_play+5
				// si hemos cambiado via 'SET', debe ser tiempo_slr_cajas 'min' y 'max'.
				// creada variable bool: coger_tiempo_slr[ caja_tmp[2] ]
			//comment_video_box_post_btn_click(_video, _caja, tiempo_slr_cajas[ 'caja_'+_caja, 'min' ], tiempo_slr_cajas[ 'caja_'+_caja, 'max' ]);
			//if( coger_tiempo_slr[ _caja ] ) {
				_timein = tiempo_slr_cajas[ 'caja_'+_caja, 'min' ];
				_timeout = tiempo_slr_cajas[ 'caja_'+_caja, 'max' ];
				console.log('clicked en comment-post-btn_con_slr, _timein: '+_timein+', _timeout: '+_timeout+' (con caja: '+_caja+')');
				//comment_video_box_post_btn_click(_video, _caja, tiempo_slr_cajas[ 'caja_'+_caja, 'min' ], tiempo_slr_cajas[ 'caja_'+_caja, 'max' ]);
				// v1.2
				comment_video_box_post_btn_click(_video, _caja_activa, tiempo_slr_cajas[ 'caja_'+_caja, 'min' ], tiempo_slr_cajas[ 'caja_'+_caja, 'max' ]);
			//}
			//else {
				//_timein = tiempo_actual_play;
				//_timeout = tiempo_actual_play + 5;
				//comment_video_box_post_btn_click(_video, _caja, _timein, _timeout);
			//}
			
			
			// era toggle, pero debe ser 'close' sí o sí. (si venimos de 'SET' será close, y si hacemos post sin 'SET' permanecerá closed.
			//$("#clock_slr_"+_caja).toggle();
			$("#clock_slr_"+_caja).hide();
		});
	}
$( document ).ready( function(){
	//Ahora tengo varias cajas, y varios videos. Detectar id's para informar DB.
	
	// caso para Slider
	//tiempo_slr_cajas[ 'caja_1', 'min' ]
	$('.comment-post-btn_con_slr').each(function( index ) {
		anyadir_evento_click_boton_post( $(this) );
	});

	$('.comment-edit-btn_con_slr').click(function(){			// no se usa porque va ligado a evento. ¿Seguro? Este evento lo usan los que cargamos con la página, y el evento añadido se 'attacha' a los posts que se van insertando durante la visita.
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
	//console.log( 'update comentario: _post_id: ' + _post_id + ', comm_id: ' + comm_id );
	
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
				//console.log("en comment_insert.js, #97, success -> ResponseText: " + data);
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

function comment_video_box_post_btn_click(_vid, _box, _timein, _timeout) {
	// no es que necesitemos el video_id per se, porque sólo tenemos 1 en la pàgina, pero lo necesitaré para posicionar respuesta en automático.
	//var _comment = $('#comentarios_'+vid+'_'+box+' .comment-post-text').val();
	var _comment = $('#comment-post-text_'+_vid+'_'+_box).val();
	var _comment_aux_text = $('#comment-post-text_'+_vid+'_'+_box).text();
	var _posx = $('#comment-post-text_'+_vid+'_'+_box).parents('.comment-insert').find('.new_comm_buttons_wrapper #pos_comm .posx').val();
	var _posy = $('#comment-post-text_'+_vid+'_'+_box).parents('.comment-insert').find('.new_comm_buttons_wrapper #pos_comm .posy').val();
	var _width = $('#comment-post-text_'+_vid+'_'+_box).parents('.comment-insert').find('.new_comm_buttons_wrapper #pos_comm .width').val();
	var _height = $('#comment-post-text_'+_vid+'_'+_box).parents('.comment-insert').find('.new_comm_buttons_wrapper #pos_comm .height').val();

	// modifico los datos a %. Antes de insertar en DB
		// Javascript no permite modificar variables (referencia) salvo cuando son objetos.
		// por eso ESTO NO VA
		//console.log("%c%s", "color: red; background: yellow; font-size: 24px;",'comment_video_box_post_btn_click ERA, _posx:'+_posx+', _posy:'+_posy+', _width:'+_width+', _height:'+_height);
		//calcula_posicion_px_a_percentual(_posx, _posy, _width, _height);	
		//console.log("%c%s", "color: green; background: yellow; font-size: 24px;",'comment_video_box_post_btn_click ES, _posx:'+_posx+', _posy:'+_posy+', _width:'+_width+', _height:'+_height);
	console.log("%c%s", "color: red; background: yellow; font-size: 24px;",'comment_video_box_post_btn_click ERA, _posx:'+_posx+', _posy:'+_posy+', _width:'+_width+', _height:'+_height);
	var obj_posicion = {
		posx: _posx,
		posy: _posy,
		w: _width,
		h: _height
	}
	calcula_posicion_px_a_percentual_objeto(obj_posicion);	
	_posx = obj_posicion.posx;
	_posy = obj_posicion.posy;
	_width = obj_posicion.w;
	_height = obj_posicion.h;
	console.log("%c%s", "color: green; background: yellow; font-size: 24px;",'comment_video_box_post_btn_click ES, _posx:'+_posx+', _posy:'+_posy+', _width:'+_width+', _height:'+_height);
	
	// CORRECCION <BR> y CR.
	// ver http://stackoverflow.com/a/5999805/1414176
	/*	
	// erronea:
	boxText = $(this).val().replace(/ /g, "<br/>");
  	$(this).replaceWith( '<div class="BoxText">' + $(this).val() + '</div>' );

  	// correcta:
  	$(this).val().replace(/\r\n|\r|\n/g,"<br />");	// CR -> <BR> "line breaks to HTML break tags"
  	boxText.replace(/<br\s?\/?>/g,"\n");		// <BR> -> CR  "bring it back to new lines"
	*/
  	_comment_con_BR = _comment.replace(/\r\n|\r|\n/g,"<br />");
  	// en GET: 
  	_comment_con_CR = _comment_con_BR.replace(/<br\s?\/?>/g,"\n");
  	//console.log('comment_video_box_post_btn_click, _comment="'+_comment+'", _comment_con_BR="'+_comment_con_BR+'", _comment_con_CR (get)="'+_comment_con_CR);


	//console.log('comment_video_box_post_btn_click -> _comment val: '+_comment+', _comment_aux_text: '+_comment_aux_text);
	var _userId = $('#userId').val();
	var _userName = $('#userName').val();
	
	if(_comment.length > 0 && _userId != null) {
		//proceed with ajax callback
		$('#comment-post-container').css('border', '1px solid #e1e1e1');
		
		$.post("ajax/comment_insert_new.php", 
			{
				task : "comment_insert",
				userId : _userId,
				comment : _comment,
				vidId : _vid,
				boxId : _box,
				timein : _timein,
				timeout : _timeout,
				posx : _posx,
				posy : _posy,
				width : _width,
				height : _height
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
				//Task: Insert html into the ul/li
				//comment_video_box_insert( jQuery.parseJSON( data ) );


				comment_video_box_insert( jQuery.parseJSON( decodeHtml( data ) ) );
		    	var new_comentario_area_lectura = $('#comentarios_'+video_id_actual+'_'+caja_actual+' .comment-wrapper .tab-content .tab_llegir');
			    // activo visibilidad nuevo comentario (ubicado en tab_crear)
		    	new_comentario_area_lectura.addClass('active').parent().find('.tab_crear').removeClass('active');
				posiciona_comentaris_a_pagina();	// los datos en DB son porcentuales. Es ok esta funcion.

	    		//destruye newcommentbox en DOM, ya esta publicado en #llegir.
	    		$('#area_comentarios .new_comms_wrapper').remove();

				//console.log("ResponseText: " + data);
				
				/*	// En console.log, aquí informa a DOM. Pero en comment_video_box_insert parece el lugar más adecuado, y funciona igual de bien.
				// restablecemos boton edit y añadimos evento
				ajx_comm = jQuery.parseJSON( data );
				console.log('llamo(2) a restablecer boton edit con parametros: vidId: ' + ajx_comm.comment.vidId + ', boxId: ' + ajx_comm.comment.boxId + ', comment_id: ' + ajx_comm.comment.comment_id + ', user: ' + ajx_comm.user.id);
				
				restablece_btn_edit( ajx_comm.comment.vidId, ajx_comm.comment.boxId, ajx_comm.comment.comment_id, ajx_comm.user.id );			// restablece_btn_edit( video_id, caja_id, post_id, user_id )
				console.log('supuestamente restablecido');
				*/
				/*
				
				ajx_comm = jQuery.parseJSON( data );
				console.log('llamo(2) a anyadeEventoVote con parametros: vidId: ' + ajx_comm.comment.vidId + ', boxId: ' + ajx_comm.comment.boxId + ', comment_id: ' + ajx_comm.comment.comment_id + ', user: ' + ajx_comm.user.id);	// pero no entra en la funcion...
				anyadeEventoVote(ajx_comm.comment.vidId, ajx_comm.comment.boxId, ajx_comm.comment.comment_id, ajx_comm.user.id );			
				//funcion_tonta( ajx_comm.comment.vidId, ajx_comm.comment.boxId, ajx_comm.comment.comment_id, ajx_comm.user.id );				// ya se puede borrar. Verificar.
				*/
			}
			);
		//console.log(_comment + "User name: " + _userName + ", User Id: " + _userId);
	}
	else {
		console.log("The text area was empty");
		$('#comment-post-container').css('border', '1px solid #f00');
	}
	// remove the text from the textarea ready for another post
	$('#comment-post-text_'+_vid+'_'+_box).val("");
}
function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}
	
function funcion_tonta( video_id, caja_id, post_id, user_id ) {
	console.log('estoy en funcion_tonta con parametros: vidId: ' + video_id + ', boxId: ' + caja_id + ', comment_id: ' + post_id + ', user: ' + user_id);
}

function funcion_tonta2( video_id, caja_id, post_id, user_id ) {
	console.log('estoy en funcion_tonta2 con parametros: vidId: ' + video_id + ', boxId: ' + caja_id + ', comment_id: ' + post_id + ', user: ' + user_id);
	$( '#area_comentarios' ).on('click', 'li#' + post_id + '.like-btn .fa', function() {		
		on_btn_like_click( video_id, caja_id, post_id, user_id );
		console.log('evento like añadido a nuevo post');
	});
}

function anyadeEventoVote( video_id, caja_id, post_id, user_id ) {
	//console.log('************dentro de anyadeEventoVote, con vidId: ' + video_id + ', caja: ' + caja_id + ', post: ' + post_id + ', user: ' + user_id );
	//$( 'li#' + post_id + '.like-btn .fa' ).css( 'color', '#f0c' );	// #6989CC
	$( '#area_comentarios' ).on('click', 'li#' + post_id + '.like-btn .fa', function() {		
		on_btn_like_click( video_id, caja_id, post_id, user_id );
		//console.log('evento like añadido a nuevo post');
	});
}

function comment_video_box_update(data) {
	//ya lo hemos hecho en funcions_utils (función "actualiza_post_editado_pagina")
	console.log("updated comentario en DB: comm_id: " + data.comment.comment_id + ", comentario: " + data.comment.comment + ", user: " + data.comment.userId);
}

function anyadeEventoDelete( video_id, caja_id, post_id, user_id ) {
	$( '#area_comentarios' ).on('click', 'li#' + post_id + '.delete-btn .fa', function() {		
		on_btn_delete_click( video_id, caja_id, post_id, user_id );
		//console.log('evento delete añadido a nuevo post');
	});
}

function on_btn_delete_click( video_id, caja_id, post_id, user_id ) {
	console.log('dentro de on_btn_delete_click, con vidId: ' + video_id + ', caja: ' + caja_id + ', post: ' + post_id + ', user: ' + user_id );
	var page_post_deleted = $( '#pageId' ).val();
	
	
	// else, delete delete en DB-votos
		console.log('unliked comment: '+post_id+', video: '+video_id+', page: '+page_post_deleted+', (box: '+caja_id+'), user: '+user_id);
		delete_post(post_id, video_id, user_id, caja_id, page_post_deleted);
	
	// actualiza página (número votos actual, tras like/unlike)
			// No hace falta. Se llaman al volver de insert_vote y delete_vote.
	vote_updated_page_update(post_id);
	// + ejecuta update DB 'likes'
			// Los votos sólo aparecen en BD votos.
}

function on_btn_like_click( video_id, caja_id, post_id, user_id ) {
	console.log('dentro de on_btn_like_click, con vidId: ' + video_id + ', caja: ' + caja_id + ', post: ' + post_id + ', user: ' + user_id );
	$( 'li#' + post_id + '.like-btn .fa' ).toggleClass( "fa-thumbs-o-up" ).toggleClass( "fa-thumbs-up" );
	
	var page_liked = $( '#pageId' ).val();
	
	// if +1 like, insert like en DB-votos.
	if ( $( 'li#' + post_id + '.like-btn .fa' ).hasClass('fa-thumbs-up') ) {	//votado
		console.log('liked comment: '+post_id+', video: '+video_id+', page: '+page_liked+', box: '+caja_id+', user: '+user_id);
		insert_vote(post_id, video_id, user_id, caja_id, page_liked);
	}
	
	// else, delete like en DB-votos
	else {
		console.log('unliked comment: '+post_id+', video: '+video_id+', page: '+page_liked+', (box: '+caja_id+'), user: '+user_id);
		delete_vote(post_id, video_id, user_id, caja_id, page_liked);
	}
	// actualiza página (número votos actual, tras like/unlike)
			// No hace falta. Se llaman al volver de insert_vote y delete_vote.
	vote_updated_page_update(post_id);
	// + ejecuta update DB 'likes'
			// Los votos sólo aparecen en BD votos.
}
		
function comment_video_box_insert(data) {
	console.log('comment_video_box_insert -> _comment ajax: '+data.comment.comment);
	// limpio las comillas con str.split(search).join(replacement)	(el problema no es en Ajax, porque se inserta perfecto en DB. La cuestión es que se muestre bien en la página recién post-eado (sin escapar comillas).
	var aux_comm_sin_comillas = data.comment.comment.split("\\'").join("'");
	aux_comm_sin_comillas = aux_comm_sin_comillas.split('\\"').join('"');
	aux_comm_sin_comillas = aux_comm_sin_comillas.split('\\').join('');
	console.log('comment_video_box_insert -> _comment ajax unescaped: '+aux_comm_sin_comillas);
	//var aux_comm_sin_comillas_con_CrLF = aux_comm_sin_comillas.replace(/<br\s?\/?>/g,"\n");
	//console.log('comment_video_box_insert -> aux_comm_sin_comillas_con_CrLF: '+aux_comm_sin_comillas_con_CrLF);
	var t = '';
	
	/*console.log('data: '+data);	
	console.log('data.debug: '+data.debug);	
	console.log('data.debug2: '+data.debug2);	
	console.log('data.debug3: '+data.debug3);	
	console.log('data.user: '+data.user);	
	console.log('data.comment: '+data.comment);	
	
	t += '<div class="debug">debug: '+data.debug+'</div>';*/
	var is_overlaid = $('body').hasClass('is_overlaid');
	var is_xy = $('body').hasClass('is_commsallover');
	if (is_overlaid && is_xy) {
		t += '<li class="comment-holder comms_wrapper" id="_'+data.comment.comment_id+'" data-timein="'+data.comment.timein+'" data-timeout="'+data.comment.timeout+'" data-posx="'+data.comment.posx+'" data-posy="'+data.comment.posy+'" data-width="'+data.comment.width+'" data-height="'+data.comment.height+'" >';
		t += '<div id="comm_handle_'+data.comment.vidId+'_' + data.comment.boxId + '_' + data.comment.comment_id + '" class="comm_handle" style="display: none;">';
		t += '	<a><i class="fa fa-user-o"></i></a>';
		t += '</div>';
		t += '<div class="user-img">';
			//t += '<img class="user-img-pic" src="'+data.user.profile_img+'" />';
		t += '<img class="user-img-pic" src="members/'+data.user.id+'/'+data.user.avatar+'" />';
		t += '</div>';
		t += '<div class="comment-body">';
		//t += '<h3 class="username-field">'+data.user.username+'</h3>';
		t += '<div id="comment-text_' + data.comment.vidId + '_' + data.comment.boxId + '_' + data.comment.comment_id + '" class="comment-text"><p>' + aux_comm_sin_comillas + '</p></div>';
		//t += '<p class="debug temps">Ini: '+data.comment.timein+'- End: '+data.comment.timeout+' </p>';
		//t += '<div class="social-buttons-holder"><ul><li id="'+data.comment.comment_id+'" class="like-btn"><i class="fa fa-thumbs-o-up"></i>&nbsp;</li><li id="'+data.comment.comment_id+'" class="reply-btn"><i class="fa fa-comment-o"></i>&nbsp;</li></ul></div>';
		t += '</div>';
		t += '<div class="comment-buttons-holder">';
		t += '<ul>';
		t += '<li id="'+data.comment.comment_id+'" class="delete-btn"><i class="fa fa-times" title="Delete this subtitle"></i></li>';
		t += '<li id="'+data.comment.comment_id+'" class="edit-btn"><i class="fa fa-pencil" title="Edit this subtitle"></i>&nbsp;</li>';
		
		t += '</ul>';
		t += '</div>';
		t += '</li>';
	}
	else {
		t += '<li class="comment-holder" id="_'+data.comment.comment_id+'" data-timein="'+data.comment.timein+'" data-timeout="'+data.comment.timeout+'" >';
		t += '<div class="user-img">';
			//t += '<img class="user-img-pic" src="'+data.user.profile_img+'" />';
		t += '<img class="user-img-pic" src="members/'+data.user.id+'/'+data.user.avatar+'" />';
		t += '</div>';
		t += '<div class="comment-body">';
		//t += '<h3 class="username-field">'+data.user.username+'</h3>';
		t += '<div id="comment-text_' + data.comment.vidId + '_' + data.comment.boxId + '_' + data.comment.comment_id + '" class="comment-text"><p>' + aux_comm_sin_comillas + '</p></div>';
		//t += '<p class="debug temps">Ini: '+data.comment.timein+'- End: '+data.comment.timeout+' </p>';
		//t += '<div class="social-buttons-holder"><ul><li id="'+data.comment.comment_id+'" class="like-btn"><i class="fa fa-thumbs-o-up"></i>&nbsp;</li><li id="'+data.comment.comment_id+'" class="reply-btn"><i class="fa fa-comment-o"></i>&nbsp;</li></ul></div>';
		t += '</div>';
		t += '<div class="comment-buttons-holder">';
		t += '<ul>';
		t += '<li id="'+data.comment.comment_id+'" class="delete-btn"><i class="fa fa-times" title="Delete this subtitle"></i></li>';
		t += '<li id="'+data.comment.comment_id+'" class="edit-btn"><i class="fa fa-pencil" title="Edit this subtitle"></i>&nbsp;</li>';
		
		t += '</ul>';
		t += '</div>';
		t += '</li>';
	}
	
	$('#comments-holder-ul_'+data.comment.vidId+'_'+data.comment.boxId).prepend(t);
	//var comm_con_BR_encoded = $('#comment-text_' + data.comment.vidId + '_' + data.comment.boxId + '_' + data.comment.comment_id).val().replace(/<br\s?\/?>/g,"\n");
	var comm_con_BR_encoded = $('#comment-text_' + data.comment.vidId + '_' + data.comment.boxId + '_' + data.comment.comment_id).text();
	console.log('comment_video_box_insert, comm_con_BR_encoded: '+comm_con_BR_encoded);
	/*var comm_con_BR_encoded_replaced = comm_con_BR_encoded.replace(/<br\s?\/?>/g,"\r\n");
	console.log('comment_video_box_insert, comm_con_BR_encoded_replaced: '+comm_con_BR_encoded_replaced);
	*/
           var comm_con_BR_decoded = $('#comment-text_' + data.comment.vidId + '_' + data.comment.boxId + '_' + data.comment.comment_id).html(comm_con_BR_encoded);
	console.log('comment_video_box_insert, comm_con_BR_decoded: '+comm_con_BR_decoded);
           //var comm_con_BR_decoded_output = $('#comment-text_' + data.comment.vidId + '_' + data.comment.boxId + '_' + data.comment.comment_id).text(comm_con_BR_decoded);
	//console.log('comment_video_box_insert, comm_con_BR_decoded_output: '+comm_con_BR_decoded_output);
	//var aux_comm_sin_comillas_con_CrLF = aux_comm_sin_comillas.replace(/<br\s?\/?>/g,"\n");

	add_delete_handlers();
	
	// restablecemos boton edit y añadimos eventos (edit, like)
	restablece_btn_edit( data.comment.vidId, data.comment.boxId, data.comment.comment_id, data.user.id );			// restablece_btn_edit( video_id, caja_id, post_id, user_id )
	anyadeEventoVote( data.comment.vidId, data.comment.boxId, data.comment.comment_id, data.user.id );				// no entra en la funcion....
	//funcion_tonta2( data.comment.vidId, data.comment.boxId, data.comment.comment_id, data.user.id );				// sí entra.
	console.log('supuestamente restablecido');
}

function comment_post_btn_click() {			// ja no es fa servir
	var _comment = $('#comment-post-text').val();
	var _userId = $('#userId').val();
	var _userName = $('#userName').val();
	
	if(_comment.length > 0 && _userId != null) {
		//proceed with ajax callback
		$('#comment-post-container').css('border', '1px solid #e1e1e1');
		
		$.post("ajax/comment_insert_new.php", 
			{
				task : "comment_insert",
				userId : _userId,
				comment : _comment
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
				//Task: Insert html into the ul/li
				comment_insert( jQuery.parseJSON( data ) );
				//console.log("ResponseText: " + data);
			}
			);
		//console.log(_comment + "User name: " + _userName + ", User Id: " + _userId);
	}
	else {
		console.log("The text area was empty");
		$('#comment-post-container').css('border', '1px solid #f00');
	}
	// remove the text from the textarea ready for another post
	$('#comment-post-text').val("");
};

function comment_insert(data) {		// ja no es fa servir
	var t = '';

	/*console.log('data: '+data);	
	console.log('data.debug: '+data.debug);	
	console.log('data.debug2: '+data.debug2);	
	console.log('data.debug3: '+data.debug3);	
	console.log('data.user: '+data.user);	
	console.log('data.comment: '+data.comment);	
	
	t += '<div class="debug">debug: '+data.debug+'</div>';*/
	
	t += '<li class="comment-holder" id="_'+data.comment.comment_id+'">';
	t += '<div class="user-img">';
	t += '<img class="user-img-pic" src="'+data.user.profile_img+'" />';
	t += '</div>';
	t += '<div class="comment-body">';
	t += '<h3 class="username-field">'+data.user.userName+'</h3>';
	t += '<div class="comment-text">'+data.comment.comment+'</div>';
	t += '</div>';
	t += '<div class="comment-buttons-holder">';
	t += '<ul>';
	t += '<li id="'+data.comment.comment_id+'" class="delete-btn">X</li>';
	t += '</ul>';
	t += '</div>';
	t += '</li>';
	
	$('.comments-holder-ul').prepend(t);
	add_delete_handlers();
}

