<?php namespace Habari; ?>
<header class="row">
	<h1><img src="https://habariproject.org/user/themes/hipo/images/habari_logo.png"></h1>
	<ul>
		<li><a class="dash" href="<?php Site::out_url('admin'); ?>/dash" title="Veiew the Dashboard"><i class="icon-dashboard"></i>Dashboard</a></li>
		<li><a class="content" href="<?php Site::out_url('admin'); ?>/content" title="View your content"><i class="icon-book"></i>Content</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/content/new" title="Create a new Post"><i class="icon-file-text-alt"></i>New Content</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/settings" title="Update your settings"><i class="icon-cog"></i>Settings</a></li>
	</ul>
	<div id="user_settings" class=""><img src="https://www.gravatar.com/avatar/<?php echo md5($user->email); ?>"><span>Chris J. Davis <i class="icon-angle-down"></i></span></div>
</header>