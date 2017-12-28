$(document).ready( function(){

	add_delete_handlers();

});

function add_delete_handlers() {
	$('.delete-btn').each( function(){
		var btn = this;
		$( btn ).click( function(){
			//console.log("Hem clicat el botó: " + btn.id );
			comment_delete( btn.id );
			// añado Confirm

			var r = confirm("Press a button!");
			if (r == true) {
			    comment_delete( btn.id );
			} else {
			    // Do nothing!
			}
		});
	});
}

function comment_delete(_comment_id) {
	
	$.post( "ajax/comment_delete.php" ,
		{
			task : "comment_delete",
			comment_id : _comment_id
		}
	)
	.error( function( data ){
		//console.log( "Success on the comment_ delete para el btn: " + _comment_id );
		$( "#_" + _comment_id ).css('background', '1px solid #f00;');
	})
	.success( function( data ){
		//console.log( "Success on the comment_ delete para el btn: " + _comment_id );
		$( "#_" + _comment_id ).detach();
	});
			
}


function comments_refresh(tiempo) {
	var num_dettached = 0;
	var num_appended = 0;
	$('.comment-holder').each( function(){
		var tin = $(this).attr("data-timein");
		var tout = $(this).attr("data-timeout");
		var comm_id = $(this).attr("id");

		if( tin > tiempo || tout < tiempo ) {
			$(this).fadeOut(1500);
			num_dettached = num_dettached + 1;
		}
		else {
			if ( tin < tiempo && tout > tiempo ) {
				$(this).css("display", "block");
				$(this).fadeIn(1500);
			}
			num_appended = num_appended + 1;
		}
	});	
	last_refresh_time = tiempo;
//	console.log('refreshed. Detached: '+num_dettached+', appended: '+num_appended);
}

function comments_refresh_____________old(tiempo) {
	var num_dettached = 0;
	var num_appended = 0;
	$('.comment-holder').each( function(){
		var tin = $(this).attr("data-timein");
		var tout = $(this).attr("data-timeout");
		var comm_id = $(this).attr("id");

		if( tin > tiempo || tout < tiempo ) {
			$(this).detach();
			//console.log('id: '+comm_id+' to detach');
			num_dettached = num_dettached + 1;
		}
		else {
			$(this).appendTo(".comments-list");
			//console.log('id: '+comm_id+' to append');
			num_appended = num_appended + 1;
		}
	});	
	last_refresh_time = tiempo;
	console.log('refreshed. Detached: '+num_dettached+', appended: '+num_appended);
}
