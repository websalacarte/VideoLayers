// social-actions.js
// funciones LVTI para modulo 'Social'


function add_social_events() {
	// social like user
	$( ".user_social" ).click(function() {
		fav_this_user($(this));
	  return false;
	});
}

function fav_this_user(that){

	var _userId = $('#userId').val();
	var tag_social_friend = 'user_social ';
	var aux_friend = that.attr('class').substr(tag_social_friend.length);
	//console.log('DEBUG fav_this_user aux_friend: '+aux_friend);
	var aux_friend_classname = aux_friend.split('_');
	var _friendId = aux_friend_classname[2];
	//console.log('DEBUG fav_this_user aux_friend: '+aux_friend+', aux_friend_classname: '+aux_friend_classname+', _friendId: '+_friendId);


	var _class_author = 'user_social_'+_friendId;
	
	//var _friend_is_favorite = $(this).hasClass("fa-star") ? true : false;

	// ¿is_page_favorite??
	var _friend_is_favorite = that.find('.fav_profile').hasClass("fa-star") ? true : false;	

	if (_friend_is_favorite) {
		delete_fav_this_friend(_userId, _friendId);
		console.log('deleting friend favorite User: '+_userId+', Friend: '+_friendId);
		// propaga todos los posts en la pagina
		$('.'+_class_author+' i.fav_profile').removeClass("fa-star").addClass("fa-star-o");
	}
	else {
		insert_fav_this_friend(_userId, _friendId);	
		console.log('inserting friend favorite. User: '+_userId+', Friend: '+_friendId);
		// propaga todos los posts en la pagina
		$('.'+_class_author+' i.fav_profile').removeClass("fa-star-o").addClass("fa-star");
	}
		
}
//delete_fav_this_friend

function delete_fav_this_friend(_userId, _friendId) {

	$.post(path_scr+"ajax/social_insert.php", 
		{
			task : "friend_delete_fav",
			userId : _userId,
			friendId : _friendId
		}
	)
	.error(
		function()
		{
			console.log("Error removing favorite user: ");
		}
	)
	.success(
		function(data)
		{
			console.log("ResponseText removing favorite user: " + data);
  			//$("#fav_page i").removeClass("fa-star").addClass("fa-star-o");
		}
	);
}
//insert_fav_this_friend

function insert_fav_this_friend(_userId, _friendId) {

	$.post(path_scr+"ajax/social_insert.php", 
		{
			task : "friend_insert_fav",
			userId : _userId,
			friendId : _friendId
		}
	)
	.error(
		function()
		{
			console.log("Error adding favorite user: ");
		}
	)
	.success(
		function(data)
		{
			console.log("ResponseText adding favorite user: " + data);
			//$("#fav_page i").removeClass("fa-star-o").addClass("fa-star");
		}
	);
}


add_social_events();
