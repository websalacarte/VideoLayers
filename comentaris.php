<?php require_once('includes/defines.php'); ?>
<?php require_once ('mysql/models/comments.php'); ?>
<?php require_once('includes/ficheros.php'); ?>
<?php $userId = 2; //la nostra 'cookie' amb dades del login. ?>
<!DOCTYPE html>
<head>
	<title>Comments Box</title>
	<link href="css/comments-layout.css" rel="stylesheet">
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
  <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
  <script type="text/javascript" src="js/comment_insert.js?<?php echo time(); ?>"></script>
  <script type="text/javascript" src="js/comment_delete.js?<?php echo time(); ?>"></script>
</head>
<body>
	<div class="wrapper">
		<div class="page-data">
			Page data is in here
		</div>
		<div class="comment-wrapper">
			<h3 class="comment-title">User feedback...</h3>
			
			<div class="comment-insert">
				<h3 class="who-says"><span>Says:</span> Websalacarte</h3>
				<div id="comment-post-container" class="comment-insert-container">
					<textarea id="comment-post-text" class="comment-insert-text"></textarea>
				</div>
				<div id="comment-post-btn" class="comment-post-btn-wrapper">Post
				</div>
			</div>
			
			<div class="comments-list">
				<ul class="comments-holder-ul">
					<?php //$comments = array("a", "b", "c", "d", "e"); ?>
					<?php $comments = Comments::getComments(); ?>
					<?php require_once (INC . 'comment_box.php'); ?>
				</ul>
			</div>
		</div>
	</div>
	<input type="hidden" id="userId" value="<?php echo $userId; ?>" />
	<input type="hidden" id="userName" value="Websalacarte" />
</body>
</html>