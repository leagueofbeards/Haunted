<?php namespace Habari; ?>
<?php Haunted::show('admin.header'); ?>
<header class="row">
	<h1><img src="https://habariproject.org/user/themes/hipo/images/habari_logo.png"></h1>
	<ul>
		<li><a class="dash" href="<?php Site::out_url('admin'); ?>/dash" title="Veiew the Dashboard">Dashboard</a></li>
		<li><a class="content" href="<?php Site::out_url('admin'); ?>/content" title="View your content">Content</a></li>
		<li><a class="design selected" href="<?php Site::out_url('admin'); ?>/themes" title="Manage your themes">Design</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/plugins" title="manage your plugins">Function</a></li>
		<li><a href="<?php Site::out_url('admin'); ?>/settings" title="Update your settings">Settings</a></li>
	</ul>
	<div id="user_settings" class=""><img src="https://www.gravatar.com/avatar/<?php echo md5($user->email); ?>"></span></div>
</header>
<section class="row">
	<div class="c12">
		<div id="inbox" class="c6">
			<div class="items">
				<h4>
				<form id="filter_contents" action="<?php URL::out('auth_ajax', Utils::wsse(array('context' => 'filter_contents')) ); ?>" method="get">
					<?php echo Utils::setup_wsse(); ?>
					<select name="content_type" id="content_type">
						<option value="0">Inactive Themes</option>
						<option value="1">Active Themes</option>
					</select>
					<span class="search"><input id="search" name="search" type="text"> <i class="icon-search"></i></span>
				</form>
				</h4>
				<ul id="contents">
					<li class="selected" data-url="">
						<div class="c9 postinfo">
							<span class="author"><img class="screenshot" src="<?php echo $designs->active_theme['screenshot']; ?>"></span>
							<span class="title"><?php echo $designs->active_theme['name']; ?> <?php echo $designs->active_theme['version']; ?> by
							
							<?php
								$authors = array();
								foreach ( $designs->active_theme['info']->author as $author ) {
									$authors[] = isset( $author['url'] ) ? '<a href="' . $author['url'] . '">' . $author . '</a>' : $author;
								}
								echo Format::and_list( $authors,  _t( ' and ' ));
							?>
							 
							</span>
							<p class="tags"><?php echo $designs->active_theme['description']; ?></p>
							<?php if ( $designs->active_theme['info']->license != '' ): ?>
								<p class="tags license"><?php printf( _t('%1$s is licensed under the %2$s'), $designs->active_theme['info']->name, '<a href="' . $designs->active_theme['info']->license['url'] . '">' . $designs->active_theme['info']->license . '</a>' ); ?></p>
							<?php endif; ?>
						</div>
						<div class="c3 end meta">
						</div>
					</li>
					
					<?php foreach( $designs->all_themes as $thm ) { ?>
						<?php if ( $thm['path'] != $designs->active_theme_dir ) : ?>
					<li data-url="">
						<div class="c10 postinfo">
							<div class="avatar">
								<span class="author"><img class="screenshot" src="<?php echo $thm['screenshot']; ?>"></span>
							</div>
							<div class="info">
							<span class="title"><?php echo $thm['name']; ?> <?php echo $thm['version']; ?> by 
							<?php
								$authors = array();
								foreach ( $thm['info']->author as $author ) {
									$authors[] = isset( $author['url'] ) ? '<a href="' . $author['url'] . '">' . $author . '</a>' : $author;
								}
								echo Format::and_list( $authors,  _t( ' and ' ));
							?>
							</span>
							<p class="tags"><?php echo $thm['description']; ?></p>
							<?php if ( $thm['info']->license != '' ): ?>
								<p class="tags license"><?php printf( _t('%1$s is licensed under the %2$s'), $thm['info']->name, '<a href="' . $thm['info']->license['url'] . '">' . $thm['info']->license . '</a>' ); ?></p>
							<?php endif; ?>
							</div>
						</div>
						<div class="c2 end meta">
							<div class="content_controls">
							<?php				
								$dbtn = FormControlDropbutton::create('actions');
								$preview = FormControlDropbutton::create('actions');
								
								$dbtn->append(FormControlSubmit::create('activate')->add_class('publish')->set_url(URL::get( 'activate_theme', 'theme_dir=' . $thm['dir'] . '&theme_name=' . $thm['info']->name ))->set_caption(_t('Activate')));

								if ($designs->previewed == $thm['dir']) {
									$preview->append(FormControlSubmit::create('end_preview')->add_class('delete')->set_url(URL::get( 'preview_theme', 'theme_dir=' . $thm['dir'] . '&theme_name=' . $thm['info']->name ))->set_caption(_t('End Preview')));
								} else {
									$preview->append(FormControlSubmit::create('preview')->add_class('draft')->set_url(URL::get( 'preview_theme', 'theme_dir=' . $thm['dir'] . '&theme_name=' . $thm['info']->name ))->set_caption(_t('Preview')));
								}
								
								echo $dbtn->pre_out();
								echo $dbtn->get( $theme );
								
								echo $preview->pre_out();
								echo $preview->get( $theme );
								
								?>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div id="content" class="c6 end">
			<div class="post">
				<h4>
					<span class="status">Features &amp; Information for</span> <span class="type"><?php echo $designs->active_theme['name']; ?></span>
				</h4>
				<div class="post_content">
					<h3 class="feature_heading">Theme Features</h3>
					<table id="features">
						<thead>
							<tr>
								<td width="5%"></td>							
								<td>Feature</td>
								<td width="10%" style="text-align:right;">Support?</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="info"><i class="icon-question"></i></td>							
								<td>Configurable</td>
								<?php if( $designs->configurable ) { ?>
								<td style="text-align:center;"><i class="icon-ok"></i></td>
								<?php } else { ?>
								<td style="text-align:center;"><i class="icon-remove"></i></td>
								<?php } ?>
							</tr>						
							<tr>
								<td class="info"><i class="icon-question"></i></td>							
								<td>Blocks and Areas</td>
								<?php if( $designs->active_theme['info']->areas ) { ?>
								<td style="text-align:center;"><i class="icon-ok"></i></td>
								<?php } else { ?>
								<td style="text-align:center;"><i class="icon-remove"></i></td>
								<?php } ?>
							</tr>
							<tr>
								<td class="info"><i class="icon-question"></i></td>							
								<td>Child Theme</td>
								<?php if( $designs->active_theme['info']->parent ) { ?>
								<td style="text-align:center;"><i class="icon-ok"></i></td>
								<?php } else { ?>
								<td style="text-align:center;"><i class="icon-remove"></i></td>
								<?php } ?>
							</tr>
						</tbody>
					</table>
					<?php if ( isset( $active_theme['info']->help ) ): ?>
						<h3>Help</h3>
						<?php echo Pluggable::get_xml_text($designs->active_theme['info']['filename'], $designs->active_theme['info']->help); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
<script>
ADMIN.offset = 80;
</script>
<?php Haunted::show('admin.footer'); ?>