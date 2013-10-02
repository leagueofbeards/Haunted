<?php namespace Habari; ?>
	<script>
		$(document).ready(sizeContent);
		$(window).resize(sizeContent);
		
		function sizeContent() {
		    var newHeight = $("html").height() - ADMIN.offset + "px";
		    $("#inbox, #content, #content .post, #inbox #contents, #editor_area").css("height", newHeight);
		}

		if (window.fullScreenApi.supportsFullScreen) {
			var embiggen = document.getElementById('fullscreen');
			var container = document.getElementById('site-container');
			
			embiggen.addEventListener('click', function() {
				window.fullScreenApi.requestFullScreen(container);
			}, true);
		}
		
	</script>
	<?php Haunted::js('tmpl'); ?>
	<?php Haunted::js('application'); ?>
</body>
</html>