<?php 
/*
	version 		Fecha 			Descripcion
	1.1 			21-08-2017 		Cambios en $user->avatar y $url_imagen_aux por mensajes de error en log. "Trying to get property of non-object" y Undefined offset: 1 y 2"
	1.2 			22-11-2017 		Check si existe $user->avatar (#33)
*/
?>
<?php if( isset( $GLOBALS['comments'] ) && is_array( $comments ) ) { ?>
<?php foreach( $comments as $key => $comment ): ?>
<?php //$user = Commenters::getCommenter( $comment->userId ); ?>
<?php $user = Commenters::getMember( $comment->userId ); ?>
<?php require_once (MODELS_DIR . 'votes.php'); ?>

<?php //echo ('<script>console.log("debug comment: ' . $comment->debug . ', debug user: ' . $user->debug . '")</script>'); ?>	
<li class="comment-holder" id="_<?php echo($comment->comment_id); ?>" data-timein="<?php echo($comment->time_in); ?>" data-timeout="<?php echo($comment->time_out); ?>" 
data-posx="<?php echo($comment->pos_x); ?>" data-posy="<?php echo($comment->pos_y); ?>" data-width="<?php echo($comment->width); ?>" data-height="<?php echo($comment->height); ?>" 
style="display: none; " >
	<div class="user-img">
		<img class="user-img-pic" src="<?php 
											// ¿sin imagen?
											// la imagen puede estar en /members/(user)/, y también en ../../img (es decir, en root/img)
											// 1) está en members: --> $avatar_no_esta_en_members = false
													// img src = echo( 'members/' . $user->id . '/' . $user->avatar );
											// 2) no está en members: 
													// 2.1) está en /img/
															// $url_imagen_aux[0] = ""
															// $url_imagen_aux[1] = ""
															// $url_imagen_aux[2] = (filename de la imagen).
															
													// 2.2) no está definida
															// $user->avatar == ""
															// $url_imagen_aux[0] = "" -> tengo que comprobar que no se cumplen ambas a la vez.
															
													// 2.3) está definida pero no se encuentra el fichero
															
											// v1.2 	Era: $avatar_no_esta_en_members = strpos($user->avatar, "../");
											if (!isset($user->avatar)) {
												$avatar_no_esta_definida = true;
												$avatar_no_esta_en_members = true;
											}
											else {
												$avatar_no_esta_definida = false;
												$avatar_no_esta_en_members = strpos($user->avatar, "../");
											}
											if ($avatar_no_esta_definida) {
												// caso 2.2.
												//if( $user->avatar == "" ) {
												if( !isset($user->avatar) ) {	// true si es null, o si no esta definida. Daba error "Trying to get property of non-object"
													// 'pintar' imagen por defecto.
													echo( "img/default_avatar.png" );
												}
											}
											else {
												if ( ( $avatar_no_esta_en_members === true ) || ( $user->avatar == "" )  ) {	// === necesario. Con == no funcionaría en algunos casos, como 1º posición.
													//echo($user->profile_img); 
													$url_imagen_aux = explode( "../", $user->avatar );
												
													//if ( ( $url_imagen_aux[0] == "" ) && ( $url_imagen_aux[1] == "" ) && ( $url_imagen_aux[2] != "" ) )	{				// (caso 2.1) [daba error "Undefined offset: 1 y 2"]
													if ( 
															( 
																( !isset($url_imagen_aux[0]) ) || ($url_imagen_aux[0] == "") 
															) 
															&& 
															( 
																( !isset($url_imagen_aux[1]) ) || ($url_imagen_aux[1] == "") 
															) 
															&& 
															( 
																( isset($url_imagen_aux[2]) ) && ($url_imagen_aux[2] != "") 
															)	
														)
														{				// (caso 2.1)
														echo( $url_imagen_aux[2] );
													}
												// caso 1. Está en members.	
												} 
												else {
													echo( 'members/' . $user->id . '/' . $user->avatar);
												}
											}

											?>" />
	</div>
	<div class="comment-body">
		<h3 class="username-field">
			<?php //echo($user->userName); 
			if (!isset($user->username)) {
				echo "Guest";
			}
			else {
				echo($user->username);
			}

			?>
		</h3>
		<div id="comment-text_<?php echo $videoId; ?>_<?php echo $comment->box_id; ?>_<?php echo $comment->comment_id; ?>" class="comment-text">
			
			<?php 
				$breaks = array("<br />","<br>","<br/>");  
    			/*
    			$user_comment = str_ireplace($breaks, "\r\n", $comment->comment); 
				echo( stripcslashes( html_entity_decode($user_comment) ) ); 
				*/
    			$user_comment_conbreaks = stripcslashes( html_entity_decode($comment->comment) ); 
    			$user_comment = str_ireplace($breaks, "\r\n", html_entity_decode($user_comment_conbreaks)); 
				echo( $user_comment ); 
				/*
				echo( "<script>console.log('********** comment->comment : " . $comment->comment . "**********');</script>"); 
				echo( "<script>console.log('********** user_comment_conbreaks : " . $user_comment_conbreaks  . "**********');</script>"); 
				echo( "<script>console.log('********** user_comment : " . $user_comment  . "**********');</script>"); 
				*/
    		?>
		</div>
		<p class="debug temps">Ini: <?php echo($comment->time_in); ?> - End: <?php echo($comment->time_out); ?> </p>
		<div class="social-buttons-holder">
			<ul>		
				<li id="<?php echo $comment->comment_id; ?>" class="like-btn">
				<?php 
					//echo ('<script>console.log("getNumVotesDeCommentDeUser(' . $comment->comment_id . ', ' . $user->id . ')")</script>');
					$usuario_ha_votado_ya_este_comentario = Votes::getNumVotesDeCommentDeUser($comment->comment_id, $user->id);
					if ( !$usuario_ha_votado_ya_este_comentario ) {
						echo ('<i class="fa fa-thumbs-o-up"></i></li>');
						//echo ('<script>console.log("getNumVotesDeCommentDeUser(' . $comment->comment_id . ', ' . $user->id . '): ' . $usuario_ha_votado_ya_este_comentario . '")</script>');
					}
					else {
						echo ('<i class="fa fa-thumbs-up"></i></li>');
					}
				?>
				<li id="<?php echo $comment->comment_id; ?>" class="reply-btn"><i class="fa fa-comment-o"></i></li>
			</ul>
		</div>
	</div>
	<?php if( $log_user_id == $comment->userId ) { ?>
	<div class="comment-buttons-holder">
		<ul>
			<li id="<?php echo $comment->comment_id; ?>" class="delete-btn">X</li>			
			<li id="<?php echo $comment->comment_id; ?>" class="edit-btn"><i class="fa fa-pencil"></i></li>
		</ul>
	</div>
	<?php } ?>
</li>
<?php endforeach; ?>
<?php //} else { echo ('<script>console.log("debug comment: ' . $comment->debug . ', debug user: ' . $user->debug . '")</script>'); } ?>	
<?php } else { echo ('<script>console.log("comment-box debug no encuentra comentarios!! ")</script>'); } ?>	
<?php //endif; ?>