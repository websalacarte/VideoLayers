<?php 
	if( isset( $GLOBALS['pages'] ) && is_array( $pages ) ) { 
// $paginas = array de paginas con : (`page_id`, `page_uri`, `page_title`, `page_header1`, `video_id`, `platform_id`, `num_boxes`, `creator_id`, `owner_id`, `creation_date`, `is_public`)	
	
?>
	<?php require_once (MODELS_DIR . 'comments.php'); // incluye Commenters ?>
	<?php require_once ('getStats.php'); ?>
	<?php require_once ('getVotes.php'); ?>

<?php foreach( $pages as $key => $page ): ?>

	<?php 
				// page creators
					$id_pagina = $page[ 'page_id' ];
					$quien_es_el_creador = $page['creator_id'];
					$quien_es_el_owner = $page['owner_id'];
					$who_did_it = Commenters::getMember( $quien_es_el_creador );
					$who_did_it_name = $who_did_it->username;
					$who_did_it_pic = $who_did_it->avatar;
					$who_owns_it = Commenters::getMember( $quien_es_el_owner );
					$who_owns_it_name = $who_owns_it->username;
					$who_owns_it_pic = $who_owns_it->avatar;
					
					
				// page: title (browser), header, pic_uri, page_uri, video, 
					$page_title = $page[ 'page_title' ];		// page title
					$page_header = $page[ 'page_header1' ];	// page header
					$page_url = $page[ 'page_uri' ] . '.php'; 		// page_uri
					$page_pic = $page[ 'page_avatar' ];		// page_avatar	=?= video_avatar		
					
					
				// video stats: num_boxes (page_stat), 
					$num_boxes = $page[ 'num_boxes' ];			
					$num_comms = get_video_comments( $page[ 'video_id'] );			// num comentarios del video (aunque debería ser página. (funcion está en getStats. Usa Comments::getTotalNumCommentsVideo y retorna count.)
					$num_votes = get_pagina_votes( $page[ 'page_id' ] );			// retorna count (función está en getVotes.php. También podria usar Votes::getNumVotesDeVideo($page->video_id) directamente)
	?>

	<?php //echo ('<script>console.log("debug page: ' . $page->debug . '")</script>'); ?>	
				
				<?php echo('<div class="item" style="background-image:url(\'img/projects/' . $page_pic . '\'); background-position: 50% 50%;">') ?>
				<?php echo('	<h3> ' . $page_header . '</h3>') ?>
				<?php echo('	<a href="' . $page_url . '" class="call">View this project >></a>') ?>
				<?php 
					
				?>
				<?php echo('	<p>Project created by: ' . $who_did_it_name . '</p>') ?>
				<?php echo('	<p class="stats_video">') ?>
				<?php echo('														<span class="dades"><i class="fa fa-tags"></i>: ' . $num_boxes . '</span>') ?>
				<?php echo('														<span class="dades"><i class="fa fa-comments-o"></i>: ' . $num_comms . '</span>') ?>
				<?php echo('														<span class="dades"><i class="fa fa-thumbs-up"></i>: ' . $num_votes . '</span></p>') ?>
				<?php echo('</div>') ?>

<?php endforeach; ?>
<?php 
	} else { 
		echo ('<script>console.log("page-box debug no encuentra pages!! ")</script>'); 
		if( isset( $GLOBALS['pages'] ) ) {
			echo ('<script>console.log("page-box debug. pages es global pero no array ")</script>'); 
		} else {
			echo ('<script>console.log("page-box debug. pages no es global")</script>'); 
		}
	} 
?>	
<?php //endif; ?>