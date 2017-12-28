$( document ).ready( function(){
	//Ahora tengo varias cajas, y varios videos. Detectar id's para informar DB.
	
	$('#comment-post-btn').click(function(){									// ya no lo usamos. Ahora (v.0.7) la clase que 'clickamos' es comment-post-btn_con_slr.
		comment_post_btn_click();
	});
	
	
	// varias cajas -> por clase, indicando caja
	$('.comment-post-btn').click(function(){									// ya no lo usamos. Ahora (v.0.7) la clase que 'clickamos' es comment-post-btn_con_slr.
		var _caja_tmp = $(this).attr('id').split("_");	
		var _video = _caja_tmp[1];
		var _caja = _caja_tmp[2];
		
		_timein = tiempo_actual_play;
		_timeout = tiempo_actual_play + 5;
		
		comment_video_box_post_btn_click(_video, _caja, _timein, _timeout);
	});
	
	// caso para Slider
	//tiempo_slr_cajas[ 'caja_1', 'min' ]
	$('.comment-post-btn_con_slr').click(function(){
		var _caja_tmp = $(this).attr('id').split("_");	
		var _video = _caja_tmp[1];
		var _caja = _caja_tmp[2];
				
		// el tiempo depende:
			// si no hemos cambiado via 'SET', debe ser _timein = tiempo_actual_play y _timeout = tiempo_actual_play+5
			// si hemos cambiado via 'SET', debe ser tiempo_slr_cajas 'min' y 'max'.
			// creada variable bool: coger_tiempo_slr[ caja_tmp[2] ]
		//comment_video_box_post_btn_click(_video, _caja, tiempo_slr_cajas[ 'caja_'+_caja, 'min' ], tiempo_slr_cajas[ 'caja_'+_caja, 'max' ]);
		if( coger_tiempo_slr[ _caja ] ) {
			comment_video_box_post_btn_click(_video, _caja, tiempo_slr_cajas[ 'caja_'+_caja, 'min' ], tiempo_slr_cajas[ 'caja_'+_caja, 'max' ]);
		}
		else {
			_timein = tiempo_actual_play;
			_timeout = tiempo_actual_play + 5;
			comment_video_box_post_btn_click(_video, _caja, _timein, _timeout);
		}
		
		
		// era toggle, pero debe ser 'close' sí o sí. (si venimos de 'SET' será close, y si hacemos post sin 'SET' permanecerá closed.
		//$("#clock_slr_"+_caja).toggle();
		$("#clock_slr_"+_caja).hide();
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
function comment_video_box_post_btn_click(_vid, _box, _timein, _timeout) {
	// no es que necesitemos el video_id per se, porque sólo tenemos 1 en la pàgina, pero lo necesitaré para posicionar respuesta en automático.
	//var _comment = $('#comentarios_'+vid+'_'+box+' .comment-post-text').val();
	var _comment = $('#comment-post-text_'+_vid+'_'+_box).val();
	console.log('comment_video_box_post_btn_click -> _comment val: '+_comment);
	var _userId = $('#userId').val();
	var _userName = $('#userName').val();
	
	if(_comment.length > 0 && _userId != null) {
		//proceed with ajax callback
		$('#comment-post-container').css('border', '1px solid #e1e1e1');
		
		$.post("ajax/comment_insert.php", 
			{
				task : "comment_insert",
				userId : _userId,
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
				//Task: Insert html into the ul/li
				comment_video_box_insert( jQuery.parseJSON( data ) );
				console.log("ResponseText: " + data);
				
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
	var t = '';
/*console.log('data: '+data);	
console.log('data.debug: '+data.debug);	
console.log('data.debug2: '+data.debug2);	
console.log('data.debug3: '+data.debug3);	
console.log('data.user: '+data.user);	
console.log('data.comment: '+data.comment);	
	t += '<div class="debug">debug: '+data.debug+'</div>';*/
	t += '<li class="comment-holder" id="_'+data.comment.comment_id+'" data-timein="'+data.comment.timein+'" data-timeout="'+data.comment.timeout+'" >';
	t += '<div class="user-img">';
	//t += '<img class="user-img-pic" src="'+data.user.profile_img+'" />';
	t += '<img class="user-img-pic" src="members/'+data.user.id+'/'+data.user.avatar+'" />';
	t += '</div>';
	t += '<div class="comment-body">';
	//t += '<h3 class="username-field">'+data.user.userName+'</h3>';
	t += '<h3 class="username-field">'+data.user.username+'</h3>';
	t += '<div id="comment-text_' + data.comment.vidId + '_' + data.comment.boxId + '_' + data.comment.comment_id + '" class="comment-text">' + aux_comm_sin_comillas + '</div>';
	t += '<p class="debug temps">Ini: '+data.comment.timein+'- End: '+data.comment.timeout+' </p>';
	t += '<div class="social-buttons-holder"><ul><li id="'+data.comment.comment_id+'" class="like-btn"><i class="fa fa-thumbs-o-up"></i>&nbsp;</li><li id="'+data.comment.comment_id+'" class="reply-btn"><i class="fa fa-comment-o"></i>&nbsp;</li></ul></div>';
	t += '</div>';
	t += '<div class="comment-buttons-holder">';
	t += '<ul>';
	t += '<li id="'+data.comment.comment_id+'" class="delete-btn">X</li>';
	t += '<li id="'+data.comment.comment_id+'" class="edit-btn"><i class="fa fa-pencil"></i>&nbsp;</li>';
	
	t += '</ul>';
	t += '</div>';
	t += '</li>';
	
	$('#comments-holder-ul_'+data.comment.vidId+'_'+data.comment.boxId).prepend(t);
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
		
		$.post("ajax/comment_insert.php", 
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
				console.log("ResponseText: " + data);
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

