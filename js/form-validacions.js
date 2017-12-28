/* Form-validacions.js 	*/
/* 	valida el formulari de addpage */
$(document).ready(function() {
	

    $('.formulari_validat').bootstrapValidator({
        message: 'msg_val(00): This value is not valid',
        // Only disabled elements are excluded
        // The invisible elements belonging to inactive tabs must be validated
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			new_platform_id: {
                message: 'msg_val(28): You must select one of the video platforms',
                validators: {
                    notEmpty: {
                        message: 'msg_val(29): The video platform is required'
                    }
                }
            },
			new_video_id: {
                message: 'msg_val(01): The video id is not valid',
                validators: {
                    notEmpty: {
                        message: 'msg_val(02): The video id is required and cannot be empty'
                    },
                    stringLength: {
                        min: 6,
                        max: 22,
                        message: 'msg_val(03): The video id must be more than 6 and less than 22 characters long'
                    }
					// eliminada validación "integer" porque YT no lo es. No importa. En Vimeo ya validamos con API si existe el video.
//                    integer: {
//                        message: 'msg_val(07): The value is not an integer'
//                    },

/*					Eliminada validación video en Vimeo. Pendiente hidden + validación por separado.
                    remote: {
                        message: 'msg_val(25): This video does not exist in Vimeo',
                        url: 'ajax/check_vimeo_id.php'
                    }
*/					
                }
            },
			video_title: {
                message: 'msg_val(08): The video title is not valid',
                validators: {
                    notEmpty: {
                        message: 'msg_val(09): The video title is required and cannot be empty'
                    }
                }
            },
			cat_1: {
                message: 'msg_val(10): The category is not valid',
                validators: {
                    notEmpty: {
                        message: 'msg_val(11): The category is required and cannot be empty'
                    }
                }
            },
			cat_2: {
                message: 'msg_val(12): The category is not valid',
                validators: {
                    notEmpty: {
                        message: 'msg_val(13): The category is required and cannot be empty'
                    }
                }
            },
			cat_3: {
                message: 'msg_val(14): The category is not valid',
                validators: {
                    notEmpty: {
                        message: 'msg_val(15): The category is required and cannot be empty'
                    }
                }
            },
			cat_4: {
                message: 'msg_val(16): The category is not valid',
                validators: {
                    notEmpty: {
                        message: 'msg_val(17): The category is required and cannot be empty'
                    }
                }
            },
            /* v1.1 21-08-2017 multiples categorias. Si empty, 1 cat con name default 'comments'
			'categories[]': {
                message: 'msg_val(18): The category is not valid',
                validators: {
                    notEmpty: {
                        message: 'msg_val(19): At least 1 category is required and cannot be empty'
                    }
                }
            },
            */
			new_video_pg: {
                message: 'msg_val(20): The video page filename is not valid',
                validators: {
                    notEmpty: {
                        message: 'msg_val(21): The video page filename is required and cannot be empty'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9\-\_]+$/,
                        message: 'msg_val(22): The video page filename can only consist of alphabetical, number, dash ( - ) and underscore ( _ )'
                    },
					/*
					callback: {
                        callback: function(value, validator) {
                            // Determine the numbers which are generated in captchaOperation
							var file_pagina = $(' #new_video_pg ').val();
							console.log('llamando a callback, file_pagina: ' + file_pagina);
                            uri_pagina(file_pagina);
							console.log('ha vuelto de uri_pagina ');
//                            return value == encuentra_video_en_vimeo;
                        },
                        message: 'msg_val(26): This page already exists. Choose another page filename'
                    },
					*/
					remote: {
                        url: 'ajax/check_pagina_disponible.php',
                        message: 'msg_val(27): This page already exists. Choose another page filename'
                    }
                }
            },
			page_title: {
                message: 'msg_val(23): The video page title is not valid',
                validators: {
                    notEmpty: {
                        message: 'msg_val(24): The video page title is required and cannot be empty'
                    }
                }
            },
            username: {
                message: 'msg_val(01): The username is not valid',
                validators: {
                    notEmpty: {
                        message: 'msg_val(02): The username is required and cannot be empty'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'msg_val(03): The username must be more than 6 and less than 30 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_]+$/,
                        message: 'msg_val(04): The username can only consist of alphabetical, number and underscore'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'msg_val(05): The email is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'msg_val(06): The input is not a valid email address'
                    }
                }
            }
        }
    });
});


/* Resposta de la API de VIMEO */
		function uri_pagina(file_pagina) {
			console.log('en uri_pagina. PENDIENTE');
		}
		function uri_vimeo(_vid_id) {
			// construye URL
			console.log('en uri_vimeo');
			var cb = 'http://vimeo.com/api/v2/video/' + _vid_id + '.json?callback=showThumbs';
			console.log('json url: ' + cb);
			// haz callback
			
			$.getJSON(cb, function(result){
				//response data are now in the result variable
				console.log('en json retornado, llamando a showThumbs');
				var retorno = showThumbs(result, _vid_id);
				console.log('en json, después de showThumbs');
				return retorno;
			});
			
			$.ajax({
			  dataType: "jsonp",
			  url: cb,
			  data: data,
			  success: success
			});
			
		};
		
		
		// This function goes through the clips and puts them on the page
		function showThumbs(videos, id_video) {
			console.log('en showThumbs, con id_video: ' + id_video );

			if (videos.length == 0) {
				// no ha encontrado el video_id
				console.log('video id no encontrado');
				return false;
			}
			else {
				// video_id es correcto
				var _id = videos[0].id;
				var _url = videos[0].url;
				var _title = videos[0].title;
				var _thumbsmall = videos[0].thumbnail_small;
				var _thumbmed = videos[0].thumbnail_medium;
				var _thumblarge = videos[0].thumbnail_large;
				var _duration = videos[0].duration;
				var _w = videos[0].width;
				var _h = videos[0].height;
				
				console.log('ENCONTRADO VIDEO EN VIMEO\n id: '+_id+', \n _url: '+_url+', \n _title: '+_title+', \n _thumbsmall: '+_thumbsmall+', \n _thumbmed: '+_thumbmed+', \n _thumblarge: '+_thumblarge+', \n _duration: '+_duration+', \n _w: '+_w+', \n _h: '+_h+', \n _id: '+_id);
				if (_id == id_video) {
					console.log('ids SÍIIII iguales');
					return true;
				}
				else {
					// no coinciden -> ¿error?
					console.log('ids no iguales, error');
					return false;
				}
			}
			
			for (var i = 0; i < videos.length; i++) {
				var thumb = document.createElement('img');
				thumb.setAttribute('src', videos[i].thumbnail_medium);
				thumb.setAttribute('alt', videos[i].title);
				thumb.setAttribute('title', videos[i].title);

				var a = document.createElement('a');
				a.setAttribute('href', videos[i].url);
				a.appendChild(thumb);

				var li = document.createElement('li');
				li.appendChild(a);
				ul.appendChild(li);
			}
		}

/*
	$('form').validate({
        rules: {
            new_video_id: {
                minlength: 3,
                maxlength: 15,
				type: int,
                required: true
            },
            video_title: {
                minlength: 3,
                maxlength: 15,
                required: true
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
*/