<?php namespace Habari; ?>
<?php Haunted::show('admin.header'); ?>
<header class="row">
	<h1><img src="https://habariproject.org/user/themes/hipo/images/habari_logo.png"></h1>
	<ul>
		<li><a class="dash selected" href="<?php Site::out_url('admin'); ?>/dash" title="Veiew the Dashboard">Dashboard</a></li>
		<li><a class="content" href="<?php Site::out_url('admin'); ?>/content" title="View your content">Content</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/themes" title="Manage your themes">Design</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/plugins" title="manage your plugins">Function</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/settings" title="Update your settings">Settings</a></li>
	</ul>
	<div id="user_settings" class=""><img src="https://www.gravatar.com/avatar/<?php echo md5($user->email); ?>"><span>Chris J. Davis <i class="icon-angle-down"></i></span></div>
</header>