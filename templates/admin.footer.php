<?php namespace Habari; ?>
	<script>
		$(document).ready(sizeContent);
		$(window).resize(sizeContent);
		
		function sizeContent() {
		    var newHeight = $("html").height() - ADMIN.offset + "px";
		    $("#inbox, #content, #content .post, #inbox #contents, #editor_area").css("height", newHeight);
		}
	</script>
	<?php Haunted::js('tmpl'); ?>
	<?php Haunted::js('bootstrap.min'); ?>
	<?php Haunted::js('application'); ?>
</body>
</html>