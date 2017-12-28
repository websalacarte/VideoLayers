$( document ).ready( function(){
	
	get_user_list();
	$( '#call_back_btn' ).click( function(){
		save_user();
	});
	$( '#lista_usuarios' ).change( function(){
		get_user();
	});
	$( '#logado_fb' ).change( function(){
		update_user_facebook();
	});
	
});

function update_user_facebook() {
	var usuario_facebook = $( '#logado_fb' ).val();
	if (opcion != 0) {
			$.post( "includes/login-ajax.php", 
				{
					id		: $( '#lista_usuarios' ).val(),
					task	: "get_user"
				}, 
				function( data ) {
					var persona = jQuery.parseJSON( data );
					$( '#nombre_persona' ).val(persona.userName);
					$( '#foto_persona' ).val(persona.profile_img);
					$( '#responseText' ).val(data);
					if (persona.profile_img) {
						var opt = "<img src='"+persona.profile_img+"' width='50' height='50' />";		// `userId`, `userName`, `profile_img`
						$( '#imatge_perfil' ).attr('src', persona.profile_img).css('display', 'block');
					}
					// update los "userId's" de la página
					$( 'h3.who-says' ).empty().append('<span>Dice:</span> '+persona.userName);
					$( 'input#userId' ).attr( 'value' , persona.userId );
					$( 'input#userName' ).attr( 'value' , persona.userName );
					
				}
			);
	}
	else {
		// crear usuario
		
	}
}
function get_user() {
	var opcion = $( '#lista_usuarios' ).val();
	if (opcion != 0) {
			$.post( "includes/login-ajax.php", 
				{
					id		: $( '#lista_usuarios' ).val(),
					task	: "get_user"
				}, 
				function( data ) {
					var persona = jQuery.parseJSON( data );
console.log('opcion: '+opcion+', \n persona.userId: '+persona.userId+', \n persona.userName: '+persona.userName);
					$( '#nombre_persona' ).val(persona.userName);
					$( '#foto_persona' ).val(persona.profile_img);
					$( '#responseText' ).val(data);
					if (persona.profile_img) {
						var opt = "<img src='"+persona.profile_img+"' width='50' height='50' />";		// `userId`, `userName`, `profile_img`
						$( '#imatge_perfil' ).attr('src', persona.profile_img).css('display', 'block');
					}
					// update los "userId's" de la página
					$( 'h3.who-says' ).empty().append('<span>Dice:</span> '+persona.userName);
					$( 'input#userId' ).attr( 'value' , persona.userId );
					$( 'input#userName' ).attr( 'value' , persona.userName );
					
				}
			);
	}
	else {
		// crear usuario
		
	}
}
function get_user_list() {
	
			$.post( "includes/login-ajax.php", 
				{
					task	: "get_user_list"
				}, 
				function( data ) {
					$( '#responseText' ).val(data);
					
					var people = jQuery.parseJSON( data );
					for( var i in people ) {
						var opt = "<option value='"+people[i].userId+"'>" + people[i].userName + "</option>";		// `userId`, `userName`, `profile_img`
						
						$( '#lista_usuarios' ).append(opt);
					}
				}
			);	
}

function save_user() {
	
			$.post( "includes/login-ajax.php", 
				{
					nombre_persona	: $( '#nombre_persona' ).val(),
					foto			: $( '#foto_persona' ).val(),
					id 				: $( '#lista_usuarios' ).val(),
					task			: "save_user"
				}, 
				function( data ) {
					$( '#responseText' ).val(data);
					var nuevo_usuario = jQuery.parseJSON( data );
console.log('nombre_persona: '+$( '#nombre_persona' ).val() + ', \n nuevo_usuario.userId: '+nuevo_usuario.userId+', \n nuevo_usuario.userName: '+nuevo_usuario.userName);
					$( '#lista_usuarios' ).attr('selected', 'none');
					var opt = "<option value='"+nuevo_usuario.userId+"' selected>" + nuevo_usuario.userName + "</option>";		// `userId`, `userName`, `profile_img`
					$( '#lista_usuarios' ).append(opt);
					// update los "userId's" de la página
					$( 'h3.who-says' ).empty().append('<span>Dice:</span> '+nuevo_usuario.userName);
					$( 'input#userId' ).attr( 'value' , nuevo_usuario.userId );
					$( 'input#userName' ).attr( 'value' , nuevo_usuario.userName );
				}
			);
	
}