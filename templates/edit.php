<?php namespace Habari; ?>
<?php Haunted::show('admin.header'); ?>
<header class="row">
	<h1><img src="https://habariproject.org/user/themes/hipo/images/habari_logo.png"></h1>
	<ul>
		<li><a class="dash" href="<?php Site::out_url('admin'); ?>/dash" title="Veiew the Dashboard">Dashboard</a></li>
		<li><a class="content selected" href="<?php Site::out_url('admin'); ?>/content" title="View your content">Content</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/themes" title="Manage your themes">Design</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/plugins" title="manage your plugins">Function</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/settings" title="Update your settings">Settings</a></li>
	</ul>
	<div id="user_settings" class=""><img src="https://www.gravatar.com/avatar/<?php echo md5($user->email); ?>"></div>
</header>
	<form id="edit_form" method="post" action="<?php URL::out( 'auth_ajax', array('context' => 'save_content', 'id' => $post->id) ); ?>">
		<section id="editarea" class="row">
			<div class="c12">
				<div id="content_title" class="c12">
					<input type="text" name="title" value="<?php echo $post->title; ?>" placeholder="Title">
				</div>
				<div id="inbox" class="c6">
					<div class="items" style="padding-bottom: 140px;">
						<div><h4>Markup</h4></div>
						<div id="notes-button-bar" style="display:none;"></div>
						<div id="editor">
							<textarea id="editor_area" name="editor_area"><?php echo $post->content; ?></textarea>
						</div>
					</div>
				</div>
				<div id="content" class="c6 end">
					<div class="post">
						<h4>Preview</h4>
						<div id="preview-div" class="post_content"></div>
					</div>
				</div>
			</div>
		</section>
		<div id="footerbar">
			<i class="icon-tags"></i>
			<div class="tag_container">
				<input name="tagsinput" id="tagsinput" style="display:none;" type="text" class="tagsinput" value="<?php echo Haunted::format_tags( $post->tags, ' ', ', ' ); ?>">
			</div>
			<div class="content_controls">
				<div class="results"><span>Last updated <?php echo $post->updated->fuzzy(); ?>.</span></div>
				<?php if( $post->status != Post::status('published') ) { ?>
					<input id="draft" class="draft" type="submit" value="Save Draft">
					<input id="schedule" class="schedule" type="submit" value="Schedule">
					<input id="publish" class="publish" type="submit" value="Publish">
				<?php } else { ?>
					<input id="delete" class="delete" type="submit" value="Delete">
					<input id="save" class="publish" type="submit" value="Save">
				<?php } ?>
			</div>
		</div>
	</form>
	
	<?php Haunted::js('wmd.combined.min'); ?>
	<?php Haunted::js('showdown'); ?>

	<script type="text/javascript">
		var wmd = new WMDEditor({
			input: "editor_area",
			preview: "preview-div",
			button_bar: "notes-button-bar",
			modifierKeys: false,
			autoFormatting: false,
			output: "Markdown"
		});
		
		ADMIN.offset = 140;
	 	
		$(document).ready(function() {
			$('.placeholder_drop').each( function() {
			 	var dpz = $(this).dropzone({ 
			 		url: ADMIN.url + '/auth_ajax/upload_files', 
			 		previewTemplate: '<div id="dndPreview" class="dz-preview dz-file-preview" style="display:none;"><div class="dz-details"><div class="dz-filename"><span data-dz-name></span></div><div class="dz-size" data-dz-size></div><img data-dz-thumbnail /></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div></div>',
			 		success: showMe
			 		});
			 	
			 	function showMe(file, r) {
			 		var which = dpz.data('id');
			 		var str = '!dnd[' + which + ']';
			 		var new_str = $('#editor_area').html().replace(str, '![](' + ADMIN.url + '/' + r.data + ')');
			 		$('#editor_area').html( new_str );
			 	}
			});
	 	});
	</script>
<?php Haunted::show('admin.footer'); ?>