<?php namespace Habari; ?>
<?php $title = 'Content'; ?>
<?php Haunted::show('admin.header'); ?>
<header class="row">
	<h1><img src="https://habariproject.org/user/themes/hipo/images/habari_logo.png"></h1>
	<ul>
		<li><a class="dash" href="<?php Site::out_url('admin'); ?>/dash" title="Veiew the Dashboard">Dashboard</a></li>
		<li><a class="content selected" href="<?php Site::out_url('admin'); ?>/content" title="View your content">Content</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/themes" title="Manage your themes">Design</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/function" title="manage your plugins">Function</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/settings" title="Update your settings">Settings</a></li>
	</ul>
	<div id="user_settings" class=""><img src="https://www.gravatar.com/avatar/<?php echo md5($user->email); ?>"></div>
</header>
<section class="row">
	<div class="c12">
		<div id="inbox" class="c6">
			<div class="items">
				<h4>
				<form id="filter_contents" action="<?php URL::out('auth_ajax', Utils::wsse(array('context' => 'filter_contents')) ); ?>" method="get">
					<?php echo Utils::setup_wsse(); ?>
					<select name="content_type" id="content_type">
						<?php foreach( $types as $type => $id ) { ?>
							<option value="<?php echo $id; ?>"><?php echo ucfirst($type); ?></option>
						<?php } ?>
					</select>
					<span class="search"><input id="search" name="search" type="text"> <i class="icon-search"></i></span>
				</form>
				</h4>
				<ul id="contents">
					<script type="text/html" id="contentrow">
					<li id="post-{%=o.id%}" data-url="{%=o.details%}">
						<div class="c9 postinfo">
							<span class="author"><img class="gravatar" src="{%=o.avatar%}"></span>
							<span class="title">{%#o.title%}</span>
							<p class="tags">{%#o.color%}</p>
						</div>
						<div class="c3 end meta">
							<span class="date">{%=o.postDate%}</span>
							<span class="views">{%=o.views%}</span>
						</div>
					</li>
					</script>
				</ul>
				<script>
					$(function(){
						var getContent = getData('#contents', '<?php echo URL::get('auth_ajax', array('context' => 'filter_contents')); ?>', 'contentrow', '#filter_contents', 'There isn\'t any content matching the current criteria.', '#paginator');
						getContent();
					});
				</script>
			</div>
		</div>
		<div id="content" class="c6 end">
			<div class="post">				
				<h4>
					<span class="status">Published</span> <i class="icon-angle-right"></i> <span class="type"><?php echo Post::type_name($content[0]->content_type); ?></span>
					<span class="controls">
						<a href=""><i class="icon-trash"></i></a>
						<a href="<?php echo $content[0]->permalink; ?>" target="_blank"><i class="icon-desktop"></i></a>
						<a class="edit_link" href="<?php URL::out('admin', array('page' => 'edit', 'id' => $content[0]->id)); ?>"><i class="icon-pencil"></i></a>
					</span>
				</h4>
				<div class="post_content">
					<?php echo $content[0]->content; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
ADMIN.offset = 80;
</script>
</div>
<?php Haunted::show('admin.footer'); ?>