$(function () {
	
	Profile.init ();
	
});



var Profile = function () {
	
	return { init: init };
	
	function init () {
		
		enableBackToTop ();

	}

	function enableBackToTop () {
		var backToTop = $('<a>', { id: 'back-to-top', href: '#top' });
		var icon = $('<i>', { class: 'icon-chevron-up' });

		backToTop.appendTo ('body');
		icon.appendTo (backToTop);
		
	    backToTop.hide();

	    $(window).scroll(function () {
	        if ($(this).scrollTop() > 150) {
	            backToTop.fadeIn ();
	        } else {
	            backToTop.fadeOut ();
	        }
	    });

	    backToTop.click (function (e) {
	    	e.preventDefault ();

	        $('body, html').animate({
	            scrollTop: 0
	        }, 600);
	    });
	}
	
}();

// profile.php

$(document).ready( function(){
	$( ".edit-details" ).click( function(){
		var id_elem = $(this).id;
		details_init(id_elem);
	});
	$('#li_avatar').click( function(){
		// hide all content
		// show content info_avatar
		clickInfoAvatar();
	});
	$('#li_account').click( function(){
		clickInfoUsuario();
	});
	$('#li_projects').click( function(){
		clickInfoProjects();
	});
	$('#li_comments').click( function(){
		clickInfoComments();
	});
	$('#li_inbox').click( function(){
		clickInfoInbox();
	});
	
});
function clickInfoUsuario(){
	$('.info_usuario').removeClass('info_invisible').addClass('info_invisible');
	$('#user_account').removeClass('info_invisible').addClass('info_visible');
}
function details_init(e){
	var id_avatar = document.getElementById('avatar').id;
	var id_account = document.getElementById('settings').id;
	var id_projects = document.getElementById('projects').id;
	var id_comments = document.getElementById('comments').id;
	var id_inbox = document.getElementById('inbox').id;
	var detailsMenu = [id_avatar,id_account,id_projects,id_comments,id_inbox];
	console.log(detailsMenu);
	console.log(e);
	var target = document.getElementById(e).id;
	navmenus(target,detailsMenu);
}
function uploads_init(e){
	var avatar_div = document.getElementById('avatar_upload').id;
	var banner_div = document.getElementById('banner_upload').id;
	var detailsMenu = [avatar_div,banner_div];
	console.log(detailsMenu);
	console.log(e);
	var target = document.getElementById(e).id;
	navmenus(target,detailsMenu);
}
function navmenus(x, ma){
	for (var m in ma) {
		if(ma[m] != x){
			document.getElementById(ma[m]).style.display = "none";
		}
	}
	if(document.getElementById(x).style.display == 'block'){
		document.getElementById(x).style.display = "none";
	}
	else{
		document.getElementById(x).style.display = "block";
	}
}

function show_lightbox(){
	document.getElementById('light').style.display = 'block';
	document.getElementById('fade').style.display = 'block';
}
function hide_lightbox(){
	document.getElementById('light').style.display = 'none';
	document.getElementById('fade').style.display = 'none';
}