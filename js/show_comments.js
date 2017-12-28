/* show comments */


$( '.subs' ).click( function(){
	var is_overlaid = $('body').hasClass('is_overlaid');
	if (is_overlaid) hide_todos_comments();
	var id_caja = $(this).attr('id');
	show_comments(id_caja);
});

function show_comments(id) {
	var is_overlaid = $('body').hasClass('is_overlaid');
	if (is_overlaid) hide_todos_comments();
	var id_caja = $(this).attr('VALUE');
	var aux_id_caja = id.split("_");
	$( '#comentarios_'+video_id_actual+'_'+aux_id_caja[1] ).toggle('display');
	caja_actual = aux_id_caja[1];
	cambia_colores_overlaid(aux_id_caja[1]);
}

function hide_todos_comments() {
	for (j=1; j<=num_cajas; ++j) {
		$( '#comentarios_'+video_id_actual+'_'+j ).hide().parent('label').removeClass('active');
	}	
}

function cambia_colores_overlaid(caja) {
	// Quito clases de colores antiguos en el boton.
	var clasesAntiguasList = document.getElementById('view_topics_button').className.split(/\s+/);
	for (var i = 0; i < clasesAntiguasList.length; i++) {
		var aux_clase_nombre = clasesAntiguasList[i].split("color_");
	    if (aux_clase_nombre.length > 1) {
	        //esta clase tiene "color_"
	        aux_clase_color = aux_clase_nombre[1];
	        $("#view_topics_button").removeClass(clasesAntiguasList[i]);
	    }
	}
	// y pongo la nueva clase de color en el boton
	if(caja!=0) {
		var clasesCajaComentarios = document.getElementById( 'comentarios_'+video_id_actual+'_'+caja ).className.split(" ");
		var claseColorComentarios = clasesCajaComentarios[0].split("borde_"); 
		var claseColorComentarios_color = claseColorComentarios[1]; 
	}
	else {
		var claseColorComentarios_color = 'blanco';
	}
	$( '#my_video_edit #view_topics_button.boton_overlaid').addClass('color_'+claseColorComentarios_color);
}


