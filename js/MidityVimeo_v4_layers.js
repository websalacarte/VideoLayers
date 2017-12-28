/*
 * MidityVimeoLib for embedding Vimeo clips and adding your subtitles
 * Copyright (C) 2010  Ing. J. Zandbergen, www.midity.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * 2013 08 10 EMM & Co
 *      Version 1.0: 2010 03 12 John Zandbergen. With the ability to have several movies on 1 page
 *		Version 3.0: 2013 08 10. Canviem show[clip_id] per show[tipus], perquè:
 *			+ només farem servir 1 video per pàgina (no té sentit més d'1)
 *			+ el "array" el necessitem per les capes de contingut.
 * 		Version 4.0: Creo onPlaying_sub, para que return no elimine el bucle.
 */
 var subsShown = false;
 var tipos = Array('ES02','CAT', 'BA','EN','DE','FR','RU','IT');
 
var MidityVimeoLib = {
    /* add an event to an object */
    addEvent: function() {
        if (window.addEventListener) {
            return function(el, type, fn) {
                el.addEventListener(type, fn, false);
            };
        } else if (window.attachEvent) {
            return function(el, type, fn) {
                var f = function() {
                    fn.call(el, window.event);
                };
                el.attachEvent('on' + type, f);
            };
        }
    }(),

    /* Initialize the library, which searches the page to make all moogaloop objects scriptable */
    initialize: function(){
        /* Get all embedded objects, we will scan through them to find the vimeo moogaloop.swf's */
        var objecten = document.getElementsByTagName("embed");
        var swfObjects = new Array();
        for(var i=0; i<objecten.length; i++)
        {
            if ( objecten[i].src.substr(0, "http://vimeo.com/moogaloop.swf".length) == "http://vimeo.com/moogaloop.swf")
            {
                // We found a moogaloop swf object
                swfObjects[swfObjects.length] = objecten[i];
            }
        }

        /* now we know the objects, let's replace them with loaded items */
        for(i=0; i<swfObjects.length; i++)
        {
            MidityVimeoLib.replace(swfObjects[i]);
        }
    },

    /* Replace an embedded moogaloop with a scriptable moogaloop */
    replace: function(embedObject){
        /* let's replace the embedded movie with a dynamically loaded one */
        /* first parse the data of the embedded swf */
        var parsedURI = MidityVimeoLib.parseURI(embedObject.src);

        /* first create a new div in which we are placing the new swf */
        var theNewDiv = document.createElement('div');
        theNewDiv.setAttribute("id", "MidityVimeo_" + parsedURI["options"]["clip_id"]);
        var width = embedObject.getAttribute("width");
        var height = embedObject.getAttribute("height");

        /*
         * everything is prepared. But due to the difference of objects in IE and others, we need
         * different approaches to replace the object.
         */
        if (embedObject.parentNode.tagName.toUpperCase() == "OBJECT")
        {
            /* We need to replace the parent node of the embed */
            embedObject.parentNode.parentNode.replaceChild(theNewDiv, embedObject.parentNode);
        }else{
            /* we need to replace the object itself */
            embedObject.parentNode.replaceChild(theNewDiv, embedObject);
        }

        var flashvars = {
            clip_id: parsedURI["options"]["clip_id"],
            show_portrait: parsedURI["options"]["show_portrait"],
            show_byline: parsedURI["options"]["show_byline"],
            show_title: parsedURI["options"]["show_title"],
            color: "715d56",
            js_api: 1, 
            js_onLoad: 'MidityVimeoLib.loaded',
            js_swf_id: parsedURI["options"]["clip_id"]
        };
        var fullScreenAllowed = (parsedURI["options"]["fullscreen"]==1?'true':'false');
        var params = {
            allowscriptaccess: 'always',
            allowfullscreen: fullScreenAllowed
        };

        var attributes = {};

        swfobject.embedSWF(parsedURI["page"], "MidityVimeo_" + parsedURI["options"]["clip_id"], width, height, "9.0.0","expressInstall.swf", flashvars, params, attributes);
    },

    /* parse the url to a structured object */
    parseURI: function(URI){
        URI = decodeURI(URI);
        var r = URI.split("?");
        var keyvalues = r[1].split("&");
        var parsed = {
            "page":r[0],
            "options":{}
        };
        for(var i=0; i<keyvalues.length; i++)
        {
            var pair = keyvalues[i].split("=");
            parsed["options"][pair[0]] = pair[1];
        }
        return parsed;
    },

    /* this function is called after the newly embedded moogaloop is ready */
    loaded: function(swf_id){
        theSWF = document.getElementById("MidityVimeo_" + swf_id);
        theSWF.api_addEventListener('onProgress', 'MidityVimeoLib.onPlaying');
    },

    /* these are functions and variables to support subtitles */
    /* the subtitles are stored in here */
    subtitles: {},

    /* Call this function to attach subtitles to a movie */					//MidityVimeoLib.showSubtitles(24819045, eval(id), 'subtitles_24819045');	eval(id): id=CAT, eval(id) = valor de variable CAT, ie, subtitulos enteros.
    showSubtitles: function(clip_id, newSubtitles, div_id, tipo_id){
    		subsShown = true;
console.log('lanzado showsubtitles para id: '+tipo_id);			
console.log('midity, showSubtitles, clip_id: '+clip_id+' | div_id: '+div_id+' | tipo_id: '+tipo_id);

        /*
		MidityVimeoLib.subtitles[clip_id] = {
            "theDiv": div_id,
            "theSubtitles": newSubtitles
        };*/
		MidityVimeoLib.subtitles[tipo_id] = {
            "theDiv": div_id,
            "theSubtitles": newSubtitles
        };

		/*
        MidityVimeoLib.subtitles[clip_id] = {
			key: tipo_id,
			data: {
				"theDiv": div_id,
				"theSubtitles": newSubtitles
			}
        };
		*/
		/*
		
// worked pretty fine, but...		
        MidityVimeoLib.subtitles[clip_id] = [
			{
				key: tipo_id,
				value: {
					"theDiv": div_id,
					"theSubtitles": newSubtitles
				}
			}];
	},*/
	/*
        MidityVimeoLib.subtitles[clip_id] = 
			{
			key: tipo_id,										// sin quotes. Cogerá el valor, cada vez. --> MVL.subtitles[clip_id].RU.theDiv y también MVL.subtitles[clip_id]['RU'].theDiv
			"theDiv": div_id,
			"theSubtitles": newSubtitles
			};
	*/
		/*
		MidityVimeoLib.subtitles[clip_id] = {};
		for (var indice_tipo = 0; indice_tipo < tipos.length; indice_tipo++) {
console.log('indice: '+indice_tipo+'|tipos[indice_tipo]:'+tipos[indice_tipo]+'|tipo_id: '+tipo_id);			
			if (tipos[indice_tipo] == tipo_id) {
				var obj = {
					key: tipo_id,
					"theDiv": div_id,
					"theSubtitles": newSubtitles
				};
console.log('obj: '+obj.key);				
				MidityVimeoLib.subtitles[clip_id].push(obj);					// ¿puedo hacer push ahora que es objeto? Supongo que sí (un array es un objeto).
			}
		}*/
	},

    hideSubtitles: function(clip_id, tipo_id) {
    	if(subsShown == true) {
        	var theDiv = MidityVimeoLib.subtitles[tipo_id].theDiv;
        	document.getElementById(theDiv).innerHTML = "";
        	delete MidityVimeoLib.subtitles[tipo_id];				//  tipo_id debe ser string, no numeric.
        }
    },

    onPlaying_sub: function(time, clip_id, tipo){
				var subtitles = MidityVimeoLib.subtitles[tipo].theSubtitles;
				var theDiv = MidityVimeoLib.subtitles[tipo].theDiv;
console.log('tipo: '+tipo+' | div: '+theDiv);			
				for(var i=0; i<subtitles.length; i++)
				{
					if ((subtitles[i][0] < time ) && (subtitles[i][1] > time))
					{
						document.getElementById(theDiv).innerHTML = '<p>'+subtitles[i][2]+'</p>';
						return;	// sólo retorna a onPlaying
					} else if (subtitles[i][0] > time ){
						document.getElementById(theDiv).innerHTML = "";
					}
				}
	},
	
	/* This function is called during play. It shows the subtitle */
    onPlaying: function(time, clip_id){
        /* TODO: review code */
console.log('Entro en onPlaying, con time: '+time);		
		
		tipos = Array('ES02','CAT','BA','EN','DE','FR','RU','IT');
		for (t_id = 0; t_id < tipos.length; ++t_id) { 
			var tipo = tipos[t_id];
			if (MidityVimeoLib.subtitles[tipo]){
				MidityVimeoLib.onPlaying_sub(time, clip_id, tipo);
/*				var subtitles = MidityVimeoLib.subtitles[tipo].theSubtitles;
				var theDiv = MidityVimeoLib.subtitles[tipo].theDiv;
console.log('tipo: '+tipo+' | div: '+theDiv);			
				for(var i=0; i<subtitles.length; i++)
				{
					if ((subtitles[i][0] < time ) && (subtitles[i][1] > time))
					{
						document.getElementById(theDiv).innerHTML = '<p>'+subtitles[i][2]+'</p>';
						//return;
					} else if (subtitles[i][0] > time ){
						document.getElementById(theDiv).innerHTML = "";
					}
				}
*/				
			}			
		}	// end for
console.log('onPlaying end for');		
    }
}

MidityVimeoLib.addEvent(window, "load", MidityVimeoLib.initialize);
