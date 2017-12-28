/* funcions útils per VideoLayers */
$( document ).ready( function(){
	$( ".btn_slr" ).click( function(){
		// botón SET 
		var aux = $( this ).parent().parent().find('.slr').slider( "option", "values");
		var caja_aux = $( this ).attr( 'id' ).split("_");
		var caja = caja_aux[ 2 ];	// this es btn_slr, y es <button id="btn_slr_1" class="btn_slr">
		console.log('caja: ' + caja);
		tiempo_slr_cajas[ 'caja_'+caja, 'min' ] = aux[0];
		tiempo_slr_cajas[ 'caja_'+caja, 'max' ] = aux[1];
		console.log(' min: ' + tiempo_slr_cajas[ 'caja_'+caja, 'min' ] + ', max: ' + tiempo_slr_cajas[ 'caja_'+caja, 'max' ] );
		$( this ).removeClass( 'btn_set_pending' );
	});

	function update_tiempo_slr_cajas(caja, fecha_formato_lectura) {
		// la llamo en clockSlider.noUiSlider.on('update'...) y dispongo de fecha_formato_lectura
		// formato tiempo (para DB) es 123456.999
		// leemos valores del tooltip y aplicamos reverse
		aux_min = reverseFormatDate(fecha_formato_lectura)
		tiempo_slr_cajas[ 'caja_'+caja, 'min' ] = aux[0];
		tiempo_slr_cajas[ 'caja_'+caja, 'max' ] = aux[1];
	}
		
	$( ".edit-btn" ).click(  function() {
		console.log('funcions_utils, -> .edit-btn clicked');		
		// reemplaza post por textarea con post + 'REPLY' btn.
		var post = $( this ).parents( '.comment-holder' );
		var edit_post_id = post.attr( 'id' );
		var edit_user = $( '#userId' ).val();
		console.log( 'post: ' + post + ' | edit_post_id: ' + edit_post_id + ' | edit_user: ' + edit_user );		
		haz_post_editable(edit_post_id, edit_user);
		// boton 'edit' deshabilitado
		var aux_post_id = edit_post_id.split("_");
		$( 'li#' + aux_post_id[1] + '.edit-btn' ).addClass('deshabilitado');	// display -> none
		console.log('utils#37 -> deshabilitado edit ' + 'li#' + aux_post_id[1] + '.edit-btn' );
		}
	);
	
	$( ".like-btn .fa" ).click(  function() {
		// función para página de video (page_id), cuando usuario clica en botón Like (voto a favor de comentario, o desactivar voto comentario).
		// parámetros: comentario que se vota (comentario_id), usuario que vota (usuario_id), video del comentario (video_liked).
		// No hacen falta: caja, page_id.
		$( this ).toggleClass( "fa-thumbs-o-up" ).toggleClass( "fa-thumbs-up" );
		var comentario_id = $( this ).parent().attr( 'id' );
		var video_liked_aux = $( this ).parents( '.comments-holder-ul' ).attr( 'id' ).split('_');
		var video_liked = video_liked_aux[1];
		var box_liked = video_liked_aux[2];
		var usuario_id = $( '#userId' ).val();
		var page_liked = $( '#pageId' ).val();
		// if +1 like, insert like en DB-votos.
		if ( $(this).hasClass('fa-thumbs-up') ) {	//votado
			console.log('liked comment: '+comentario_id+', video: '+video_liked+', page: '+page_liked+', box: '+box_liked+', user: '+usuario_id);
			insert_vote(comentario_id, video_liked, usuario_id, box_liked, page_liked);
		}
		// else, delete like en DB-votos
		else {
			console.log('unliked comment: '+comentario_id+', video: '+video_liked+', page: '+page_liked+', (box: '+box_liked+'), user: '+usuario_id);
			delete_vote(comentario_id, video_liked, usuario_id, page_liked);
		}
		// actualiza página (número votos actual, tras like/unlike)
				// No hace falta. Se llaman al volver de insert_vote y delete_vote.
				vote_updated_page_update(comentario_id);
		// + ejecuta update DB 'likes'
				// Los votos sólo aparecen en BD votos.
		}
	);
	
	$( ".reply-btn .fa" ).click(  function() {
		$( this ).toggleClass( "fa-comment" ).toggleClass( "fa-comment-o" );
		var comentario_id = $( this ).parent().attr( 'id' );
		var video_replied = $( this ).parents( '.comments-holder-ul' ).attr( 'id' );
		var usuario_id = $( '#userId' ).val();
		console.log('replying comment: '+comentario_id+', video: '+video_replied);
		
		// + insertar textarea y 'POST'.
		}
	);
});


function vote_updated_page_update( _comm_id ) {
	console.log( 'pendiente añadir nº votos para update, funcions-utils -> actualizando votos para comentario: ' + _comm_id );
}

function insert_vote( _comm_id, _vid_id, _usr_id, _box_id, _page_id ) {
	// llamada: insert_vote(comentario_id, video_liked, usuario_id, box_liked, page_liked);
	
	if( _usr_id != null ) {		// si el usuario no está logado, no puede votar.	?????
		
		$.post("ajax/vote_insert.php", 
			{
				task : "insert_vote",
				userId : _usr_id,
				comment_id : _comm_id,
				vidId : _vid_id,
				boxId : _box_id,
				pageId : _page_id
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
				console.log("en (funcions_utils) #124, success -> ResponseText: " + data);
				vote_updated_page_update( jQuery.parseJSON( data ) );	// para actualizar la pagina, aunque ya lo hacemos desde evento. Reportamos retorno del update en db.
			}
		);
	}
	else {
		// no puede votar?
	}
}
function delete_vote( _comm_id, _vid_id, _usr_id, _page_id ) {
	// llamada: insert_vote(comentario_id, video_liked, usuario_id, page_liked);
	
	if( _usr_id != null ) {		// si el usuario no está logado, no puede votar/desvotar.	?????
		
		$.post("ajax/vote_delete.php", 
			{
				task : "delete_vote",
				userId : _usr_id,
				comment_id : _comm_id,
				vidId : _vid_id,
				pageId : _page_id
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
				console.log("en (funcions_utils) #158, success -> ResponseText: " + data);
				vote_updated_page_update( jQuery.parseJSON( data ) );	// para actualizar la pagina, aunque ya lo hacemos desde evento. Reportamos retorno del update en db.
			}
		);
	}
	else {
		// no puede votar?
	}
}
  
  function actualiza_slider(t, box) {
	var sl_0 = t;
	var sl_1 = t + 5;
	var min_ini = Math.floor( sl_0 / 60 ) < 10 ? "0"+Math.floor( sl_0 / 60 ) : Math.floor( sl_0 / 60 );
	var seg_ini = Math.floor( sl_0 % 60 ) < 10 ? "0"+Math.floor( sl_0 % 60 ) : Math.floor( sl_0 % 60 );
	var min_fin = Math.floor( sl_1 / 60 ) < 10 ? "0"+Math.floor( sl_1 / 60 ) : Math.floor( sl_1 / 60 );
	var seg_fin = Math.floor( sl_1 % 60 ) < 10 ? "0"+Math.floor( sl_1 % 60 ) : Math.floor( sl_1 % 60 );
	$( "#amount_" + box ).val( "De: " + min_ini + ':' + seg_ini + " a: " + min_fin + ':' + seg_fin );
  }
  
  function margedetemps(id) {
	var caixa = $(this).attr(id);
    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 75, 300 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		console.log('margedetemps, #298');
      }
    });
    $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
      " - $" + $( "#slider-range" ).slider( "values", 1 ) );
  }


function toHHMMSS(numerodesegundos){
	var sec_num = parseInt(this, 10); // don't forget the second param
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    var time    = hours+':'+minutes+':'+seconds;
    return time;
}
// pendiente probar!!!!!!!!!
