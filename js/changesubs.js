/* Change subs */

		$(function() {
//			$("#subtitles_24819045").draggable({ axis: 'y', containment: 'parent' });
		});

		var i, j, id, id_check, chequeado;
		var id_i = new Array();
		var fichero = new Array();
		
		function changeSubs (id_check) {
			//var chequeado = $(id_check).is(':checked');
			chequeado = $('#'+id_check).attr('checked');
			if (chequeado === "checked") {
				$('#'+id_check).removeAttr('checked');
				
				$('#subtitles_24819045_'+id_check.substring(5)).css("display", "none");	//subtitles_24819045_ES02
console.log('display de #subtitles_24819045_'+id_check.substring(5)+' desactivado');
			}
			else {
				var antiguo_chk = $(id_check).attr('checked');
				$('#'+id_check).attr('checked','checked');
				$('#subtitles_24819045_'+id_check.substring(5)).css("display", "inline");	//subtitles_24819045_ES02
console.log('activado display de #subtitles_24819045_'+id_check.substring(5));
				
				for (j = 0; j < document.subselect.subs.length; ++j) { 
					//console.log('j='+j);
					var aux = $('#'+id_check).attr('value');
					
					if (document.subselect.subs[j].value == $('#'+id_check).attr('value')) {
						i = j;
					}
					if ((document.subselect.subs[j].checked == true) || (document.subselect.subs[j].checked == "checked")) {
						// ninguna caja seleccionada -> hideSubtitles for all
						if(document.subselect.subs[j].value == 'none') {
							document.getElementById("author").innerHTML = "";
							//borramos todos los subtitulos
							id_i = Array('ES02','CAT', 'BA','EN','DE','FR','RU','IT');
							for (k = 0; k < id_i.length; ++k) { 
								if (MidityVimeoLib.subtitles[k]) {
									MidityVimeoLib.hideSubtitles(k);
									console.log('borrado subtitulos tipo:'+k);
								}
							}
							$("#subtog").click();
							
						// si alguna caja seleccionada, cargamos subtitles en clase
						} else {
							
							id = document.subselect.subs[j].value;
							id_i = Array('ES02','CAT', 'BA','EN','DE','FR','RU','IT');
							
							//tipo
							if ((id == 'RU') || (id == 'SRB')) {
								$('.subtitles').css("font-family", 'Neucha').css("font-style", "cursive").css("font-size", 48);
							}
							else {
								$('.subtitles').css("font-family", 'Loved by the King').css("font-style", "cursive").css("font-size", 36);
							}
							
							// En lugar de llamar a fichero (para subtitulos habituales, ficheros .srt), debo coger el objeto AJAX $std.	($comments de servidor, pasado a cliente en "data")						
							var nuevo_fichero = "http://www.websalacarte.com/alqv/js/subtitols/websalacarte_ALQV_24819045_" +id +".js";
							fichero.push( nuevo_fichero);
							$.getScript(nuevo_fichero)
							.done(function(){
									MidityVimeoLib.showSubtitles(24819045, eval(id), 'subtitles_24819045_'+id, id);				
									document.getElementById("author").innerHTML = '<a target="_blank" href="' +eval(id)[0][1] +'">by ' +eval(id)[0][0] +'<\/a>';	//eval(id) = eval (CAT, p.e.). El fichero contiene "CAT = ...", con lo que eval(CAT) 'ejecuta' y eval(CAT) = todos los subtitulos.
								})
								.fail(function(jqxhr, settings, exception) {
									console.log( 'failed: '+exception );
								});
											
						}
					 }	// if CHECKED True.
				}	//ex-for
				
			}
		}
		