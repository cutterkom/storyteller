<?php 

/*****************
	Storyteller Theme Functions
*****************/


/*****************
	Thumbnails + Size
	*****************/
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'small', 768 );
	add_image_size( 'medium', 1024 );
	add_image_size( 'large', 1400 );

	
/*****************
	Enqueue scripts and styles
	*****************/
	add_action( 'wp_enqueue_scripts', 'storyteller_scripts' );
	function storyteller_scripts() {

		wp_enqueue_style( 'storyteller-style', get_stylesheet_uri() );
		wp_enqueue_script( 'jquery');

		if( !is_admin()){ 
			wp_enqueue_script( 'fitvids', get_bloginfo( 'stylesheet_directory' ) . '/js/jquery.fitvids.js', array( 'jquery' ), '1.0.0' );
			wp_enqueue_script( 'fitvids-set', get_bloginfo( 'stylesheet_directory' ) . '/js/fitvids_settings.js', array( 'jquery', 'fitvids' ), '1.0.0' );
			wp_enqueue_script( 'keys', get_bloginfo( 'stylesheet_directory' ) . '/js/keyboard.js', array( 'jquery' ), '1.0.0' );
			wp_enqueue_script( 'backstretch', get_bloginfo( 'stylesheet_directory' ) . '/js/backstretch.js', array( 'jquery' ), '1.0.0' );
			wp_enqueue_script( 'backstretch-set', get_bloginfo('stylesheet_directory') . '/js/backstretch_settings.js', array( 'jquery', 'backstretch' ), '1.0.0' );

			$backstretch_img = array(
				'small' => wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'small' ),
				'medium' => wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'medium' ),
				'large' => wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'large' )
				);

			wp_localize_script( 'backstretch-set', 'BackStretchImages', $backstretch_img );
		}



	}


/*****************
	Custom CSS Widget on slide	
	*****************/
	add_action('admin_menu', 'custom_css_hooks');
	add_action('save_post', 'save_custom_css');
	add_action('wp_head','insert_custom_css');
	function custom_css_hooks() {
		add_meta_box('custom_css', 'Custom CSS', 'custom_css_input', 'post', 'normal', 'high');
		add_meta_box('custom_css', 'Custom CSS', 'custom_css_input', 'page', 'normal', 'high');
	}
	function custom_css_input() {
		global $post;
		echo '<input type="hidden" name="custom_css_noncename" id="custom_css_noncename" value="'.wp_create_nonce('custom-css').'" />';
		echo '<textarea name="custom_css" id="custom_css" rows="5" cols="30" style="width:100%;">'.get_post_meta($post->ID,'_custom_css',true).'</textarea>';
	}
	function save_custom_css($post_id) {
		if (!wp_verify_nonce($_POST['custom_css_noncename'], 'custom-css')) return $post_id;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
		$custom_css = $_POST['custom_css'];
		update_post_meta($post_id, '_custom_css', $custom_css);
	}
	function insert_custom_css() {
		if (is_page() || is_single()) {
			if (have_posts()) : while (have_posts()) : the_post();
			echo '<style type="text/css">'.get_post_meta(get_the_ID(), '_custom_css', true).'</style>';
			endwhile; endif;
			rewind_posts();
		}
	}


/*****************
	Add style in editor
	*****************/
	add_editor_style();


/*****************
	Change labels in admin menu
	*****************/
	function storyteller_change_post_label() {
		global $menu;
		global $submenu;
		$menu[5][0] = 'Tell Your Story';
		$submenu['edit.php'][5][0] = 'Slides';
		$submenu['edit.php'][10][0] = 'Add Slide';
		$submenu['edit.php'][15][0] = 'Stories';
		remove_menu_page( 'edit.php?post_type=page' ); 
		remove_menu_page( 'edit-comments.php' ); 
		remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' );

		echo '';
	}
	add_action( 'admin_menu', 'storyteller_change_post_label' );


/*****************
	Change "post" to "slide"
	*****************/
	function storyteller_change_post_object() {
		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$labels->name = 'Slides';
		$labels->singular_name = 'Slide';
		$labels->add_new = 'Add Slide';
		$labels->add_new_item = 'Add Slide';
		$labels->edit_item = 'Edit Slide';
		$labels->new_item = 'Slide';
		$labels->view_item = 'View Slide';
		$labels->search_items = 'Search Slide';
		$labels->not_found = 'No Slide found';
		$labels->not_found_in_trash = 'No Slide found in Trash';
		$labels->all_items = 'All Slides';
		$labels->menu_name = 'Slides';
		$labels->name_admin_bar = 'Slides';
	}
	add_action( 'admin_menu', 'storyteller_change_post_object' );


/*****************
	Change "category" to "stories"
	*****************/
	function storyteller_change_tax_object_label() {
		global $wp_taxonomies;
		$labels = &$wp_taxonomies['category']->labels;
		$labels->name = __('Stories', 'storyteller');
		$labels->singular_name = __('Story', 'storyteller');
		$labels->search_items = __('Search Your Stories', 'storyteller');
		$labels->all_items = __('All Your Stories', 'storyteller');
		$labels->parent_item = __('Your Parent Story', 'storyteller');
		$labels->parent_item_colon = __('Your Parent Story', 'storyteller');
		$labels->edit_item = __('Edit Your Story', 'storyteller');
		$labels->view_item = __('View Your Story', 'storyteller');
		$labels->update_item = __('Update Your Story', 'storyteller');
		$labels->add_new_item = __('Add Your Story', 'storyteller');
		$labels->new_item_name = __('Your New Story', 'storyteller');
	}
	add_action( 'init', 'storyteller_change_tax_object_label' );


/*****************
	Remove Widgets on Dashboard	
	*****************/
	function remove_dashboard_widgets() {
		global $wp_meta_boxes;
		remove_action('welcome_panel', 'wp_welcome_panel');
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

	}
	add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );


/*****************
	Add Customized Dashboard Widget	
	*****************/
	function example_add_dashboard_widgets() {

		wp_add_dashboard_widget(
                 'example_dashboard_widget',         // Widget slug.
                 'How Stryteller works',         // Title.
                 'example_dashboard_widget_function' // Display function.
                 );	
	}
	add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );


/*****************
	Add Content to Customized Dashboard Widget	
	*****************/
	function example_dashboard_widget_function() {

	// Display whatever it is you want to show.
		echo "<p>With Storyteller you can combine big images and videos with text and embeddable content to create visually attractive stories.</p>";
		echo "<a href='/wp-admin/post-new.php'><button class='dashboard-btn'>Create a story</button></a>";
		echo '<a href="http://storyteller.katharinabrunner.de/demo" style="margin-left:30px;">See the demo</a> ';
		echo "<h2>How to start?</h2>";
	
		echo 'Every story is built up of individual slides. Click "Add Slide" to create a slide. Enter a title, your text, add a featured image and you are good to go. By combining individual slides you can create a story.';
		echo '<h3>You can find more help at the right upper corner on every <a href="/wp-admin/post-new.php">Add Slide</a> page</h3> ';
	}      

/*****************
	Disable Adminbar
	*****************/
	add_filter('show_admin_bar', '__return_false');


/*****************
	Remove links/menus from the admin bar	
	*****************/
	function storyteller_admin_bar_render() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('comments');
		$wp_admin_bar->remove_menu('new-content');
	}
	add_action( 'wp_before_admin_bar_render', 'storyteller_admin_bar_render' );


/*****************
	Remove Post Metaboxes 
	*****************/
	function storyteller_remove_post_metaboxes() {
remove_meta_box( 'authordiv','post','normal' ); // Author Metabox
remove_meta_box( 'commentstatusdiv','post','normal' ); // Comments Status Metabox
remove_meta_box( 'commentsdiv','post','normal' ); // Comments Metabox
remove_meta_box( 'postcustom','post','normal' ); // Custom Fields Metabox
remove_meta_box( 'postexcerpt','post','normal' ); // Excerpt Metabox
remove_meta_box( 'revisionsdiv','post','normal' ); // Revisions Metabox
remove_meta_box( 'trackbacksdiv','post','normal' ); // Trackback Metabox
remove_meta_box( 'tagsdiv-post_tag','post','normal' ); // Tags Metabox
}
add_action('admin_menu','storyteller_remove_post_metaboxes');


/*****************
	Add Help Metabox
	*****************/
	function storyteller_metabox_top_right() {
		add_meta_box( 'after-title-help', 'Need some help?', 'storyteller_top_right_help_metabox_content', 'post', 'side', 'high' );
	}


/*****************
	Help Metabox Content
	*****************/
	function storyteller_top_right_help_metabox_content() { ?>
	<h4>Where to put title and text?</h4>
	<p>Enter your title in the title text field and your text in the text editor below. It is important that you <strong>choose the position by selecting paragraphs and asigning them to "right", "middle" or "left" via Quicktags</strong>.</p>
	<h4>How to add a full screen background image?</h4>
	<p>Add an image as featured image. It is automatically set as a full screen background image.</p>
	<h4>Want to use a video?</h4>
	<p>Just copy a url from youtube or vimeo in the text editor. The video will be automatically inserted full screen.</p>
	<h4>How to assign a slide to a story?</h4>
	<p>Choose an existing story under "Stories" or create a new one.</p>
	<h3><a href="">Need more help? </a></h3>
	<?php }
	add_action( 'add_meta_boxes', 'storyteller_metabox_top_right' );


/*****************
	Pre_get_posts for category loop
	*****************/
	function storyteller_change_category( $query ) {
		if ( !is_admin() && $query->is_category() && $query->is_main_query() ) {
			$query->set( 'posts_per_page', '1' );
			$query->set( 'order','ASC' );

			
		}
		
	};
	add_action( 'pre_get_posts', 'storyteller_change_category' );

/*****************
	Options Page
	*****************/


	function storyteller_register_options_page(){
		add_menu_page( 'Options', 'Options', 'manage_options', 'storytelleroptions', 'storyteller_options_page', '', 6 ); 
	}
	add_action( 'admin_menu', 'storyteller_register_options_page' );

	function storyteller_options_page() {
		?>
		<div class="wrap">
			<div><br></div>
			<h2>Storyteller Options</h2>

			<form method="post" action="options.php">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( 'storyteller-options' ); ?>
				<?php do_settings_sections( 'storyteller-options' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

/*****************
	Change Fonts in Backend
	*****************/
	function storyteller_register_admin_settings() {
		register_setting( 'storyteller-options', 'storyteller-options' );

    // Settings fields and sections
		add_settings_section( 'section_typography', 'Choose your font', 'storyteller_section_typography', 'storyteller-options' );
		add_settings_field( 'primary-font', 'Font', 'storyteller_field_primary_font', 'storyteller-options', 'section_typography' );
	}
	add_action( 'admin_init', 'storyteller_register_admin_settings' );

	function storyteller_section_typography() {
		echo '';
	}

	function storyteller_field_primary_font() {
		$options = (array) get_option( 'storyteller-options' );
		$fonts = storyteller_get_available_fonts();
		$current_font = 'Open Sans';

		if ( isset( $options['primary-font'] ) )
			$current_font = $options['primary-font'];

		?>
		<select name="storyteller-options[primary-font]">
			<?php foreach( $fonts as $font_key => $font ): ?>
			<option <?php selected( $font_key == $current_font ); ?> value="<?php echo $font_key; ?>"><?php echo $font['name']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php
}

function storyteller_get_available_fonts() {
	$fonts = array(
		'open-sans' => array(
			'name' => 'Open Sans',
			'import' => '@import url(http://fonts.googleapis.com/css?family=Open+Sans);',
			'css' => "font-family: 'Open Sans', sans-serif;"
			),
		'georgia' => array(
			'name' => 'Georgia',
			'import' => '',
			'css' => "font-family: 'Georgia', sans-serif;"
			),
		'lato' => array(
			'name' => 'Lato',
			'import' => '@import url(http://fonts.googleapis.com/css?family=Lato);',
			'css' => "font-family: 'Lato', sans-serif;"
			),
		'arial' => array(
			'name' => 'Arial',
			'import' => '',
			'css' => "font-family: Arial, sans-serif;"
			
			)
		);

	return apply_filters( 'storyteller_available_fonts', $fonts );
}


function storyteller_wp_head_fonts() {
	$options = (array) get_option( 'storyteller-options' );
	$fonts = storyteller_get_available_fonts();
	$current_font_key = 'arial';

	if ( isset( $options['primary-font'] ) )
		$current_font_key = $options['primary-font'];

	if ( isset( $fonts[ $current_font_key ] ) ) {
		$current_font = $fonts[ $current_font_key ];

		echo '<style>';
		echo $current_font['import'];
		echo 'body * { ' . $current_font['css'] . ' } ';
		echo '</style>';
	}
}
add_action( 'wp_head', 'storyteller_wp_head_fonts' );

?>