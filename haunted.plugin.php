<?php
namespace Habari;

class Haunted extends Plugin {

	private function add_templates() {
		$this->add_template( 'dash', dirname(__FILE__) . '/templates/dash.php' );
		$this->add_template( 'edit', dirname(__FILE__) . '/templates/edit.php' );		
		$this->add_template( 'content', dirname(__FILE__) . '/templates/content.php' );
		$this->add_template( 'design', dirname(__FILE__) . '/templates/design.php' );
	}

	public function action_init() {
		$this->add_templates();
	}

	public function filter_admin_access_tokens( array $require_any, $page ) {
		switch ( $page ) {
			case 'content':
			case 'dash':
			case 'edit':
			case 'design':
			case 'function':
				$require_any = array( 'manage_posts' => true );
			break;
		}
		
		return $require_any;
	}

	public function action_admin_theme_get_dash($handler, $theme) {  
		$theme->title = 'Dash';
		$theme->display('dash');
		exit;
	}

	public function action_admin_theme_get_content($handler, $theme) {
		$theme->content = Posts::get( array('nolimit' => true, 'orderby' => 'pubdate DESC') );
		$theme->types = Post::list_active_post_types();
		$theme->title = 'Content';
		$theme->display('content');
		exit;
	}

	public function action_admin_theme_get_design($handler, $theme) {
		$theme->title = 'Design';
		$theme->designs = $this->get_themes();
		$theme->display('design');		
		exit;
	}

	public function action_admin_theme_get_edit($handler, $theme) {
		$theme->post = Post::get( array('id' => $_GET['id']) );
		$theme->title = 'new';
		$theme->display('edit');
		exit;
	}

	private function get_themes() {
		$ret = new \stdClass();
		
		$all_themes = Themes::get_all_data();
		$theme_names = Utils::array_map_field($all_themes, 'name');

		$ret->available_updates = Options::get( 'updates_available', array() );

		foreach ( $all_themes as $name => $theme ) {

			// only themes with a guid can be checked for updates
			if ( isset( $theme['info']->guid ) ) {
				if ( isset( $available_updates[ (string)$theme['info']->guid ] ) ) {
					// @todo this doesn't use the URL and is therefore worthless
					$all_themes[ $name ]['info']->update = $available_updates[ (string)$theme['info']->guid ]['latest_version'];
				}
			}

			// If this theme requires a parent to be present and it's not, send an error
			if(isset($theme['info']->parent) && !in_array((string)$theme['info']->parent, $theme_names)) {
				$all_themes[$name]['req_parent'] = $theme['info']->parent;
			}
		}

		$ret->all_themes = $all_themes;

		$ret->active_theme = Themes::get_active_data( true );
		$ret->active_theme_dir = $ret->active_theme['path'];

		// If the active theme is configurable, allow it to configure
		$ret->active_theme_name = $ret->active_theme['info']->name;
		$ret->configurable = Plugins::filter( 'theme_config', false, $ret->active_theme );
/* 		$theme->assign( 'configure', Controller::get_var( 'configure' ) ); */

/* 		$ret->areas = $this->get_areas(0); */
		$ret->previewed = Themes::get_theme_dir( false );

		$ret->help = isset($this->theme->active_theme['info']->help) ? $ret->active_theme['info']->help : false;
		$ret->help_active = Controller::get_var('help') == $ret->active_theme['dir'];
/*
		$this->prepare_block_list();

		$blocks_areas_t = DB::get_results( 'SELECT b.*, ba.scope_id, ba.area, ba.display_order FROM {blocks} b INNER JOIN {blocks_areas} ba ON ba.block_id = b.id ORDER BY ba.scope_id ASC, ba.area ASC, ba.display_order ASC', array() );
		$blocks_areas = array();
		
		foreach ( $blocks_areas_t as $block ) {
			if ( !isset( $blocks_areas[$block->scope_id] ) ) {
				$blocks_areas[$block->scope_id] = array();
			}
			
			$blocks_areas[$block->scope_id][$block->area][$block->display_order] = $block;
		}
		
		$theme->blocks_areas = $blocks_areas;
*/

/*
		$scopes = DB::get_results( 'SELECT * FROM {scopes} ORDER BY name ASC;' );
		$scopes = Plugins::filter( 'get_scopes', $scopes );
		$theme->scopes = $scopes;
		$theme->scopeid = 0;
*/
		return $ret;
	}

	public static function show($file) {
		if( !isset($user) ) {
			$user = User::identify();
		}
		
		$base = dirname( __FILE__ );
		include_once( $base . '/templates/' . $file . '.php' );
	}

	public static function image($path) {
		$base = dirname( __FILE__ );
		echo Site::get_url('user') . '/plugins/haunted/templates/images/' . $path;
	}

	public static function js($file) {
		echo '<script type="text/javascript" language="javascript" src="' . Site::get_url('user') . '/plugins/haunted/templates/js/' .  $file . '.js"></script>' . "\n";
	}

	public static  function css($file) {
		echo '<link rel="stylesheet" type="text/css" media="screen" href="' . Site::get_url('user') . '/plugins/haunted/templates/' .  $file . '.css">' . "\n";
	}

	public function action_before_act_admin( $that ) {
		if( !isset($that->handler_vars['page']) && $that->handler_vars['entire_match'] == 'admin' ) {
			Utils::redirect( Site::get_url('admin') . '/dash/' );
		}
	}
	
	public function action_auth_ajax_wsse_update() {
		$ar = new AjaxResponse(200, null, Utils::WSSE());
		$ar->out();
	}	

	private function make_safe( $file ) {
		// check that this is an image, and not a file.
		$safe_file = $file['file']['name'];
		$safe_file = str_replace( "#", "No.", $safe_file );
		$safe_file = str_replace( "$", "Dollar", $safe_file );
		$safe_file = str_replace( "%", "Percent", $safe_file );
		$safe_file = str_replace( "^", "", $safe_file );
		$safe_file = str_replace( "&", "and", $safe_file );
		$safe_file = str_replace( "*", "", $safe_file );
		$safe_file = str_replace( "?", "", $safe_file );
		return $safe_file;
	}
	
	private function create_dir($path) {
		if ( !is_dir( $path ) ){
			mkdir( $path, 0777 );
		}
	}
	
	private function upload_image($file, $upload_dir) {
		$return = new \stdClass();
		if( $file != '' ) {
			$cleaned = $this->make_safe( $file );
			$this->create_dir( $upload_dir );
			$path = $upload_dir . $cleaned;
			if( copy($file['file']['tmp_name'], $path) ) {
				$file_name = $file['file']['name'];
				$file_size = $file['file']['size'];
				if( $file_size > 999999 ) {
					$div = $file_size / 1000000;
					$file_size = round( $div, 1 ) . ' MB';
				} else {
					$div = $file_size / 1000;
					$file_size = round( $div, 1 ) . ' KB';
				}
				
				$return->document = $path;
			}
		}
		
		return $return;
	}

	public function action_auth_ajax_filter_contents($data) {
		$vars = $_GET;
		$tags = array();
		$args = array();
		$voc = array();

		$args['limit'] = 50;
		$args['public'] = 1;
		$args['orderby'] = 'id DESC';
		
		if( !empty($vars['tags']) ) {
			$tags = array_merge( $tags, $vars['tags'] );
		}

		if( !empty($vars['content_type']) ) {
			$args['content_type'] = $vars['content_type'];
		}
		
		if( isset($vars['search']) && strlen($vars['search']) >= 3 ) {
			$args['criteria'] = $vars['search'];
		}
		
		if( !empty($tags) ) {
			$voc['tags:term'] = $tags;
		}

		$args['vocabulary'] = $voc;

		$content = Posts::get( $args );

		foreach( $content as $post ) {
			$post->postDate = $post->pubdate->friendly(1);
			$post->avatar = 'https://www.gravatar.com/avatar/' . md5($post->author->email); 
			$post->author = $post->author;
			$post->color = self::status_color( $post );
			$post->details = URL::get( 'auth_ajax', Utils::wsse(array('context' => 'get_details', 'id' => $post->id)) );
		}

		$response = new AjaxResponse(200, null);
		ob_end_clean();

		$result['rows'] = json_decode( $content->to_json() );
		$result['query'] = $content->get_query();
		$result['params'] = $get_params;
		$count = $content->count_all();
		$result['count'] = $count;
		$result['pages'] = ceil($count / Options::get('pagination', 5));
		$result['page'] = $page;

		$response->data = $result;
		$response->out();
	}

	public function action_auth_ajax_get_details($data) {
		$vars = $_GET;

		$return = Post::get( array('id' => $vars['id']) );

		if( $return ) {
			$result['post'] = $return->to_json();
			$result['type'] = Post::type_name( $return->content_type );
			$result['status'] = Post::status_name( $return->status );
			$result['edit_link'] = URL::get('admin', array('page' => 'edit', 'id' => $return->id));
			$status = 200;
			$message = '';
		} else {
			$result = '';
			$status = 401;
			$message = 'You don\'t have access to that content';
		}
		
		$ar = new AjaxResponse( $status, $message, $result );
		$ar->out();	
	}

	public function action_auth_ajax_save_content($data) {
		$content = Post::get( array('id' => $_GET['id']) );

		$vars = $_POST;
				
		$postdata= array(
				'title' 		=>	$vars['title'],
				'slug'			=>	Utils::slugify( $vars['title'] ),
				'content'		=>	$vars['editor_area'] ? $vars['editor_area'] : '',
				'updated'		=>	DateTime::date_create( date(DATE_RFC822) ),
				'tags'			=>	$vars['tagsinput'],
		);
		
		if( isset($_GET['state']) && $_GET['state'] == 'publish' ) {
			$postdata['status'] = Post::status('published');
			$postdata['pubdate'] = DateTime::date_create( date(DATE_RFC822) );
		}
			
		foreach( $postdata as $key => $value ) {
			$content->$key = $value;
		}

		if( $content ) {
			$content->update();
			$status = 200;
			$message = $content->title . ' was updated ';
		} else {
			$status = 401;
			$message = $content->title . ' wasn\'t updated.';
		}
		
		$ar = new AjaxResponse( $status, $message, null );
		$ar->out();
		exit();
	}

	private function upload($file, $dir) {
		try {
			$image = $this->upload_image( $file, $dir );
			return $image;
		} catch( Exception $e ) {
			return false;
		}
    }

	public function action_auth_ajax_upload_files($data) {
		$r = array();
		$user = User::identify();
		$folder = $user->id;
				
		$upload_dir = Site::get_path('user') . '/files/uploads/' . $folder . '/';
						
		$file = $this->upload( $_FILES, $upload_dir );
		
		$passed = $file;
		
		if( $passed != false ) {
			$r['error'] = false;		
			$r['status_code'] = 200;
			$r['response'] = $file->document;
		} else {
			$r['error'] = true;		
			$r['status_code'] = 400;
		}
		
		$ar = new AjaxResponse( $r['status_code'], null, $r['response'] );
		$ar->out();
    }

	public static function format_tags( $terms, $between = ', ', $between_last = null, $sort_alphabetical = false ) {
		$array = array();
		if ( !$terms instanceof Terms ) {
			$terms = new Terms( $terms );
		}

		foreach ( $terms as $term ) {
			$array[$term->term] = $term->term_display;
		}

		if ( $sort_alphabetical ) {
			ksort( $array );
		}

		if ( $between_last === null ) {
			$between_last = _t( ' and ' );
		}

		$fn = function($a, $b) {
			return $a;
		};
		
		$array = array_map( $fn, $array, array_keys( $array ) );
		$last = array_pop( $array );
		$out = implode( $between, $array );
		$out .= ( $out == '' ) ? $last : $between_last . $last;
		
		return $out;
	}

	public static function status_color($post) {
		switch( $post->status ) {
			case Post::status('draft') :
				$color = 'yellow';
			break;
			case Post::status('scheduled') :
				$color = 'blue';
			break;
			case Post::status('published') :
				$color = 'green';
			break;			
		}
		
		if( $color == 'blue' ) {
			$schedule = ' (' . $post->pubdate->friendly( 1 ) . ')';
		} else {
			$schedule = '';
		}
		
		return '<span class="' . $color . '">' . ucfirst( Post::status_name( $post->status ) ) . $schedule . '</span>';
	}
}
?>