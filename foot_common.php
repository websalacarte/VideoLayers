<?php 
echo( '

<script type="text/javascript">
	function DropDown(el) {
		this.dd = el;
		this.initEvents();
	}
	DropDown.prototype = {
		initEvents : function() {
			var obj = this;

			obj.dd.on("click", function(event){
				$(this).toggleClass( "active" );
				event.stopPropagation();
			});	
		}
	}

	$(function() {

		var dd = new DropDown( $( "#dd" ) );

		$(document).click(function() {
			// all dropdowns
			$( ".wrapper-dropdown-5" ).removeClass( "active" );
		});

	});
</script>


  <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(["_setAccount", "UA-35536785-1"]);
  _gaq.push(["_trackPageview"]);

  (function() {
    var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
    ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
');
?>
