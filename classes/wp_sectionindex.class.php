<?php
/**
* class WP_SectionIndex
* Handles all functionality within the WP SectionIndex plugin.
*/

class WP_SectionIndex {
	
	/**
	* Setup Variables.
	*/
	
	var $plugin_path;
	var $plugin_url;
	var $plugin_prefix;
	var $plugin_file;
	
	var $prefix;
	
	var $tag;
	var $use_pages;
	var $use_posts;
	var $clean_data;
	var $shortcode_title;
	var $use_backtotop;
	var $backtotop_id;
	var $display_all_anchors;
	
	var $missing_options;
	
	var $backtotop_inserted;
	var $backtotop_counter;
	
	/**
	* WP_SectionIndex()
	* Constructor function.
	*/

	function WP_SectionIndex ( $plugin_path, $plugin_url, $plugin_prefix, $plugin_file ) {
		
		// Setup default options.
		
		$this->plugin_path = $plugin_path;
		$this->plugin_url = $plugin_url;
		$this->prefix = $plugin_prefix;
		$this->plugin_prefix = $plugin_prefix;
		$this->plugin_file = $plugin_file;
		
		$this->options = array(
							'tag' => 'h3', 
							'use_pages' => 1, 
							'use_posts' => 1, 
							// 'clean_data' => 1, 
							'use_backtotop' => 1, 
							'backtotop_id' => '', 
							'display_all_anchors' => 1
						);
		
		$this->tag 					= 'h3';
		$this->use_pages 			= 1;
		$this->use_posts 			= 1;
		$this->clean_data 			= 1;
		$this->shortcode_title 		= 'Section Index';
		$this->use_backtotop 		= 1;
		$this->backtotop_id 		= 'header';
		$this->display_all_anchors  = 1;
		
		$this->missing_options 		= array();
		
		$this->backtotop_inserted 	= false;
		$this->backtotop_counter 	= 0;
		
	} // End WP_SectionIndex()
	
	/**
	* create_content_anchors()
	* Searches the content area, finds the heading tags and adds anchor tags above them.
	*/
		
	function get_section_headings ($matches) {
	
		global $wpdb;
				
		// Generate a random number to avoid multiple anchors with the same name.
		$guid = '';
		$guid = substr(rand(), 0, 3);
		
		// Setup the text to be used as the anchor.
		$anchor_text = '';
		$anchor_text = strtolower( $matches[1] );
		
		// Limit anchor text to only 4 words.
		$anchor_text_bits = explode(' ', $anchor_text);
		if ( count( $anchor_text_bits ) > 4 ) { $limited_anchor_text_bits = 4; } else { $limited_anchor_text_bits = count( $anchor_text_bits ); }
		$anchor_text = '';
		for ( $i = 0; $i < $limited_anchor_text_bits; $i++ ) {
			$anchor_text .= $anchor_text_bits[$i];
				
				if ( $i == $limited_anchor_text_bits - 1 ) {} else {	$anchor_text .= ' '; }

		} // End FOR Loop
		
		// Setup and clean up anchor text.
		$anchor_text = str_replace( ' ', '_', $anchor_text );
		$anchor_text = str_replace( '.', '', $anchor_text );
		$anchor_text = rawurlencode( $anchor_text );
		$anchor = '';
		$anchor = '<a name="' . $anchor_text . '" id="' . $anchor_text . '" class="sectionindex">&nbsp;</a>';
		
		// Construct the filtered heading tag with the newly created anchor.
		$filtered_heading = '';
		$filtered_heading = "\n" . $anchor . "\n" . $matches[0];
		
		$filtered_heading = trim( $filtered_heading );
		
		return $filtered_heading;
		
	
	} // End get_section_headings()
	
	/**
	* create_content_anchors()
	* A callback function used to add the anchors above the matched heading tags.
	* Used in: $this->get_section_headings() (callback function).
	*/
	
	function create_content_anchors ($content) {
		
		global $post;
		
		$disable_on_post = get_post_meta ( $post->ID, 'wpsi_disable_index', true);
		
		// $pattern = '/<' . $this->tag . '>([^<]+)<\/' . $this->tag . '>/i';
		// $pattern = '/<' . $this->tag . '>([^<]+)<\/' . $this->tag . '>/i';
		$pattern = '/<' . $this->tag . '[^>]*>(.*?)<\/' . $this->tag . '>/i';	
		
		if ( $disable_on_post == false ) {
			
			if ( (is_page() && $this->use_pages == 1) || (is_single() && $this->use_posts == 1) ) {
			
				return preg_replace_callback($pattern, array(&$this, 'get_section_headings'), $content);
			
			} else {
				
				return $content;
				
			} // End IF Statement
			
		} else {
		
			return $content;
			
		} // End IF Statement
	
	} // End create_content_anchors()
	
	/**
	* detect_backtotop_locations()
	* Searches the content area, finds the heading tags and adds "back to top" anchor tags above them.
	*/
		
	function detect_backtotop_locations ($matches) {
	
		global $wpdb;

		$backtotop_anchor = '';
		
		if ( $this->backtotop_counter == 0 ) {} else {
			
			// Setup the text to be used as the anchor.
			$backtotop_anchor = '<a href="#' . $this->backtotop_id . '" class="back_to_top">' . apply_filters( 'wpsi_backtotop_text', 'Back to top' ) . '</a>';
		
		} // End IF Statement		
		
		$this->backtotop_counter++;
		
		// Construct the filtered heading tag with the newly created anchor.
		$hyperlink = '';
		$hyperlink = "\n" . $backtotop_anchor . "\n" . $matches[0];
		
		$hyperlink = trim( $hyperlink );
		
		return $hyperlink;
		
	
	} // End detect_backtotop_locations()
	
	/**
	* create_backtotop_anchors()
	* A callback function used to add the anchors above the matched heading tags.
	* Used in: $this->get_section_headings() (callback function).
	*/
	
	function create_backtotop_anchors ($content) {
		
		global $post;
		
		$disable_on_post = get_post_meta ( $post->ID, 'wpsi_disable_index', true);
		
		// $pattern = '/<' . $this->tag . '>([^<]+)<\/' . $this->tag . '>/i';
		$pattern = '/<' . $this->tag . '[^>]*>(.*?)<\/' . $this->tag . '>/i';	
		
		if ( $disable_on_post == false && $this->use_backtotop == 1 ) {
			
			if ( (is_page() && $this->use_pages == 1) || (is_single() && $this->use_posts == 1) ) {
				
				if ( $this->use_backtotop == 1 ) {
				
					return preg_replace_callback($pattern, array(&$this, 'detect_backtotop_locations'), $content) . "\n" . '<a href="#' . $this->backtotop_id . '" class="back_to_top">' . apply_filters( 'wpsi_backtotop_text', 'Back to top' ) . '</a>' . "\n";
				
				} else {
					
					return preg_replace_callback($pattern, array(&$this, 'detect_backtotop_locations'), $content);
					
				} // End IF Statement
			
			} else {
				
				return $content;
			
			} // End IF Statement
			
		} else {
		
			return $content;
			
		} // End IF Statement
	
	} // End create_backtotop_anchors()
	
	/**
	* return_the_content()
	* Return the filtered post content.
	* Based on the `the_content` function.
	*
	* @param string $more_link_text Optional. Content for when there is more text.
	* @param string $stripteaser Optional. Teaser content before the more text.
	*/
	
	function return_the_content($more_link_text = null, $stripteaser = 0, $page_id = null) {
		
		global $post, $page;

		if($page_id == null) {
			$content = $post->post_content;
		} else {
			$data = get_page($page_id);
			$content = $data->post_content;
		}
		
		if ( $this->display_all_anchors == 0 ) {
		
			// Separate the content into it's various pages.
			$content_pages = explode( '<!--nextpage-->', $content );
			
			if ( count( $content_pages ) > 1 ) {
			
				if ( $page ) {
				
					$pagenumber = $page - 1;
				
					$content = $content_pages[$pagenumber];
					
				} else {
					
					$content = $content_pages[0];
					
				} // End IF Statement
				
			} // End IF Statement
		
		} // End IF Statement
		
		// $content = get_the_content($more_link_text, $stripteaser);

		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		
		return $content; 
		
	} // End return_the_content()
	
	/**
	* get_anchors()
	* Returns anchor tags for the current page or post.
	* Used in: $this->widget_frontend, $this->shortcode, $this->create_anchor_list.
	*/
	
	function get_anchors ($page = null) {

		global $post;

		// $pattern = '/<a name=\"([^\"]+)" id=\"([^\"]+)" class="sectionindex">&nbsp;<\/a>\n<' . $this->tag . '>([^<]+)<\/' . $this->tag . '>/i';
		$pattern = '/<a name=\"([^\"]+)" id=\"([^\"]+)" class="sectionindex">&nbsp;<\/a>\n<' . $this->tag . '[^>]*>(.*?)<\/' . $this->tag . '>/i';
		
		$content = $this->return_the_content($more_link_text = null, $stripteaser = 0, $page);
		
		$anchors = array();
		
		$page_permalink = '';
		
		if( $page != null ) {
		
			$page_permalink = get_permalink($page);
		
		} // End IF Statement
		
		if ( $this->display_all_anchors == 0 ) {
		
			preg_match_all( $pattern, $content, $matches );
					
			for ( $i = 0; $i < count($matches[0]); $i++ ) {			
				
				// Split the sectionindex anchor from the heading tag.
				$explode = explode('class="sectionindex">', $matches[0][$i]);
				
				// Store the raw text from the heading.
				$raw_text = strip_tags( $explode[1] );
				
				// Detect the attributes on the heading.
				preg_match( '|name="(.*)$|mi', $explode[0], $attributes );
				
				$_atts = explode( ' ', $attributes[0] );
				
				// Setup the href, text and front of anchor variables.
				$href = str_replace( 'id="', 'href="#', $_atts[1] );
				
				$text = strip_tags( $explode[1] );
				
				// Remove new lines, carriage returns and unnecessary spaces from the text.
				$text = str_replace( "\n", '', $text );
				$text = str_replace( "\r", '', $text );
				$text = str_replace( "&nbsp;", '', $text );
				$text = trim( $text );
				
				$front_of_anchor = '<a ' . $href . ' rel="noindex, nofollow">';
				
				// Create a new variable for the anchor.
				$anchor = '';
				
				// Assign to this variable, the front of the anchor, the text and the end of the anchor tag.
				
				$anchor .= $front_of_anchor . $text . '</a>';
				
				$anchors[] = $anchor;
				
			} // End FOR Loop
		
		} else {
			
			$current_url = get_permalink( $post->ID );
			
			$is_rewrites = get_option( 'permalink_structure' );
			
			// Separate the content into it's various pages.
			$content_pages = explode( '<!--nextpage-->', $content );
			
			if ( $page ) {
			
				$pagenumber = $page - 1;
				
			} else {
				
				$page = 0;
				
			} // End IF Statement
			
			foreach ( $content_pages as $k => $v ) {
				
				// Determine the page number.
				$pagenum = '';
				
				if ( $k > 0 ) {
				
					$page_keyword = '';
				
					if ( $is_rewrites == '' ) {
						
						$pagenum = '&page=' . ( $k+1 );
						
					} else {
					
						$pagenum = $page_keyword . ( $k+1 ) . '/';
						
					} // End IF Statement
					
				} // End IF Statement
				
				preg_match_all( $pattern, $v, $matches );
				
				for ( $i = 0; $i < count($matches[0]); $i++ ) {			
					
					// Split the sectionindex anchor from the heading tag.
					$explode = explode('class="sectionindex">', $matches[0][$i]);
					
					// Store the raw text from the heading.
					$raw_text = strip_tags( $explode[1] );
					
					// Detect the attributes on the heading.
					preg_match( '|name="(.*)$|mi', $explode[0], $attributes );
					
					$_atts = explode( ' ', $attributes[0] );
					
					// Setup the href, text and front of anchor variables.
					$href = str_replace( 'id="', 'href="' . $current_url . $pagenum . '#', $_atts[1] );
					
					$text = strip_tags( $explode[1] );
					
					// Remove new lines, carriage returns and unnecessary spaces from the text.
					$text = str_replace( "\n", '', $text );
					$text = str_replace( "\r", '', $text );
					$text = str_replace( "&nbsp;", '', $text );
					$text = trim( $text );
					
					$front_of_anchor = '<a ' . $href . ' rel="noindex, nofollow">';
					
					// Create a new variable for the anchor.
					$anchor = '';
					
					// Assign to this variable, the front of the anchor, the text and the end of the anchor tag.
					
					$anchor .= $front_of_anchor . $text . '</a>';
					
					$anchors[] = $anchor;
					
				} // End FOR Loop
				
			} // End FOREACH Loop
					
		} // End IF Statement
		
		return $anchors;
		
	} // End get_anchors()
	
	/**
	* create_anchor_list()
	* Creates a list of anchor tags within the content area.
	* Used in: $this->widget_frontend, $this->shortcode.
	* Dependancies: $this->get_anchors.
	* Params: $echo.
	*/
	
	function create_anchor_list ($echo = true, $page_id = null) {
		
		$anchors = $this->get_anchors($page_id);
			
		$html = '';
		
		if ( count($anchors) > 0 ) {
			
			$html .= '<ul class="sectionindex-anchor-list">' . "\n";
			
			foreach ( $anchors as $a ) {
				
				$html .= '<li>' . $a . '</li>' . "\n";
				
			} // End FOREACH Loop
			
			$html .= '</ul>' . "\n";
			
		} // End IF Statement
		
		if ( empty($html) ) { 
			
			return;
			
		} else {
			
			if ( $echo == true ) {
				
				echo $html;
				 
			} else {
				
				return $html;

			} // End IF Statement
		
		} // End IF Statement
		
	} // End create_anchor_list()
	
	/**
	* settings_screen()
	* The WordPress options screen.
	*/
	
	function settings_screen () {
		
		global $wpsi_plugin_path;
		
		// Separate the admin page XHTML to keep things neat and in the appropriate location.
		require_once($wpsi_plugin_path . '/settings/screen.php');
		
	} // End settings_screen()
	
	/**
	* settings_register()
	* Register administration menu in the global WordPress admin_menu.
	*/
	
	function settings_register () {
	
		if (function_exists('add_submenu_page')) {
			
			add_submenu_page( 'options-general.php', 'Section Index', 'Section Index', 'manage_options', 'wp-section-index', array( &$this, 'settings_screen' ) );
			
		} // End IF Statement
		
	} // End settings_register()
	
	/**
	* settings_process()
	* Process the WordPress options screen.
	*/
	
	function settings_process () {} // End settings_process()
	
	/**
	* admin_notice()
	* Notify the user if there are settings missing.
	*/
	
	function admin_notice () {
	
		// $missing_options = array();
		
		foreach ( $this->options as $key => $value ) {

			if ( get_option( $this->prefix . $key ) == '' && $value != '' ) {
				$this->missing_options[] = $key;
			} // End IF Statement
		} // End FOREACH
		
		if ( count($this->missing_options) >= 1 || $this->widget_is_registered() == false ) {
		
			$notice = '';
			$notice .= '<div id="wpsi-notice" class="updated fade">' . "\n";
			$notice .= '<p><strong>' . __( 'WP Section Index is almost ready.', 'wp-section-index' ) . '</strong> ' . "\n";
			
			if ( count($this->missing_options) >= 1 ) {
			
				$notice .= sprintf(__( 'Please <a href="%1$s">run the setup</a> for it to work.'), "options-general.php?page=wp-section-index", 'wp-section-index' );
				$notice .= "\n" . '</p>' . "\n";
			
			} // End IF Statement
			
			if ( $this->widget_is_registered() == false ) {
			
				$notice .= "\n" . '<p>' . "\n";
				$notice .= sprintf(__( 'We noticed that you haven\'t added the Section Index widget anywhere on your website. Please <a href="%1$s">visit the widgets screen</a> to add it.', 'wp-section-index' ), "widgets.php");
				$notice .= "\n" . '</p>' . "\n";
				
			} // End IF Statement
			
			$notice .= '</div>' . "\n";
			
			echo $notice;
		
		} // End IF Statement
		
	} // End admin_notice()
	
	/**
	* widget_is_registered()
	* Checks if the widget has been registered in any widgetized areas.
	*/
	
	function widget_is_registered () {
	
		$widget_is_registered = false;
	
		if ( function_exists('wp_get_sidebars_widgets') ) {
		
			$areas = wp_get_sidebars_widgets();
			
			foreach ( $areas as $key => $value ) {
			
				if ( $key != 'wp_inactive_widgets' ) {
					
					if ( count($value) > 0 ) {
						
						foreach ( $value as $v ) {

							if ( substr($v, 0, 12) == 'sectionindex' ) {
								
								$widget_is_registered = true;
									
							} // End IF Statement
							
						} // End FOREACH
							
					} // End IF Statement
					
				} // End IF Statement
				
			} // End FOREACH
			
		} // End IF Statement
		
		return $widget_is_registered;
		
	} // End widget_is_registered()
	
	/**
	* register_widget()
	* Register the widget.
	*/
	
	function register_widget () {
	
		register_widget ( 'SectionIndex_Widget' );
		
	} // End register_widget()
	
	/**
	* shortcode()
	* A shortcode for listing the anchor links within the content area.
	*/
	
	function shortcode () {
		
		$tag = 'h4';
		
		$title = '<' . $tag . '>' . $this->shortcode_title . '</' . $tag . '>' . "\n";
		
		$shortcode = $title;
		
		return $shortcode;
		
	} // End shortcode()
	
	/**
	* create_meta_box()
	* Creates a meta box on the Pages and Posts screens for toggling the section index.
	*/
	
	function create_meta_box () {
	
		if ( function_exists('add_meta_box') ) { 
		
		$post_types = array( 'post', 'page' );
		
		$post_types = apply_filters( 'wpsi_supported_posttypes', $post_types );
		
		if ( count( $post_types ) ) {
			
			foreach ( $post_types as $p ) {
			
				// Sanitise.
				$p = strtolower( trim( strip_tags( $p ) ) );
			
				add_meta_box( 'wpsi_metabox', 'Section Index Settings', array(&$this, 'meta_box_content'), $p, 'side', 'low');
				
			} // End FOREACH Loop
			
		} // End IF Statement
		
		/*			
		// Code from the previous version.
		add_meta_box( 'wpsi_metabox', 'Section Index Settings', array(&$this, 'meta_box_content'), 'page', 'side', 'low');
		add_meta_box( 'wpsi_metabox', 'Section Index Settings', array(&$this, 'meta_box_content'), 'post', 'side', 'low');
		*/
		
		} else {
			
			add_action('dbx_page_advanced', array(&$this, 'meta_box_content'));
			add_action('dbx_post_advanced', array(&$this, 'meta_box_content'));
			
		} // End IF Statement
		
	} // End create_meta_box()
	
	/**
	* meta_box_content()
	* The content of the meta box.
	*/
	
	function meta_box_content () {
	
		global $post;
	
		$box_content = '';
		
		$box_content .=	'<div class="wpsi_metabox_content">';
			
			// Get the attachment information for this box
			$wpsi_disable_index_value = get_post_meta ( $post->ID, 'wpsi_disable_index', true );
			
			$box_content .= '<input type="hidden" name="wpsi_noncename" id="wpsi_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
			$box_content .= '<input type="checkbox" name="wpsi_disable_index" id="wpsi_disable_index" value="1"';
			
				if ( $wpsi_disable_index_value == true ) {
					$box_content .= ' checked="checked"';
				}
			
			$box_content .= '/> ' . __( 'Disable Section Indexing', 'wp-section-index' );
			$box_content .= '<p><em>(' . __( 'If this box is checked, the section index will not be run on this', 'wp-section-index' ) . $post->post_type . '.)</em></p>';
		
		$box_content .= '</div><!--/.wpsi_metabox_content-->';
		
		echo $box_content;
		
	} // End meta_box_content()
	
	/**
	* save_meta_box_data()
	* Saves the content of the meta box.
	*/
	
	function save_meta_box_data ( $post_id ) {
	
		global $post;
	
		// Verify
		if ( !wp_verify_nonce( $_POST['wpsi_noncename'], plugin_basename(__FILE__) ) ) {  
			return $post_id;  
		}
		
		// Check capabilities		
		if ( 'page' == $_POST['post_type'] ) {  
			if ( !current_user_can( 'edit_page', $post_id )) { 
				return $post_id;
			}
		} else {  
			if ( !current_user_can( 'edit_post', $post_id )) { 
				return $post_id;
			}
		}
		
		// Run the necessary process
		$data = '';
		
		if ( isset( $_POST['wpsi_disable_index'] ) ) {
			
			$data = $_POST['wpsi_disable_index'];
			
		} // End IF Statement
		
		if ( get_post_meta ( $post_id, 'wpsi_disable_index' ) == "" ) { 
			add_post_meta ( $post_id, 'wpsi_disable_index', $data, true ); 
		}
		elseif ( $data != get_post_meta ( $post_id, 'wpsi_disable_index', true ) ) { 
			update_post_meta ( $post_id, 'wpsi_disable_index', $data );
		}
		elseif ( $data == "" ) { 
			delete_post_meta ( $post_id, 'wpsi_disable_index', get_post_meta ( $post_id, 'wpsi_disable_index', true ) );
		}
		
	} // End save_meta_box_data()
	
	/*----------------------------------------
 	  add_contextual_help()
 	  ----------------------------------------
 	  
 	  * Add contextual help text on the
 	  * settings screen.
 	----------------------------------------*/
	
	function add_contextual_help ( $contextual_help, $screen_id, $screen ) { 
	  
	  global $title;
	  
	  // $contextual_help .= var_dump($screen); // use this to help determine $screen->id
	  
	  if ( 'settings_page_wp-section-index' == $screen->id ) {
	  
	    $contextual_help =
		'<h5>' . sprintf ( __( '%s Documentation', 'wp-section-index' ), esc_html( $title ) ) . '</h5>' . 
		'<p><strong>' . __('So, how does this plugin work anyway?', 'wp-section-index') . '</strong></p>' .
		'<p>' . sprintf ( __( 'The %s plugin is used in conjunction with a widget, displaying the section index for the page currently viewed by the user. The plugin looks through your content and creates easy-to-navigate-to sections within the content. This is ideal for long Pages or blog Posts.', 'wp-section-index' ), esc_html( $title ) ) . '</p>' .
		'<p><strong>' . __('The section index isn\'t displaying. What gives?', 'wp-section-index') . '</strong></p>' .
		'<p>' . __( 'If the widget is not visible on the Page or Post, the section index will not display. By the same token, if the widget is enabled and there are no sections for the Page or Post in question (or the section index has been disabled for that Page or Post) the widget displaying the section index will not display.', 'wp-section-index' ) . '</p>' .
		'<p><strong>' . __('What\'s this about a "Back to top" element ID?', 'wp-section-index') . '</strong></p>' .
		'<p>' . __( 'This is the ID of an element (a `div` tag, for example) in your theme to which you would like the user to jump when a "Back to top" link is clicked. It is recommended that this element be somewhere near the top of the screen, usually a `header` or `top` element. The ID can be found by viewing the source of the page and looking for a tag with the `id=""` attribute. The value inside the quotation marks is what is inputted in the `"Back to top" element ID` field.', 'wp-section-index' ) . '</p>' .
	      '<h5>' . __('For more information:') . '</h5>' .
	      '<p>' . __('<a href="http://matty.co.za/plugins/wp-section-index/" target="_blank">' . esc_html( $title ) . ' Website and Documentation</a>') . '</p>' .
	      '<p>' . __('<a href="http://wordpress.org/tags/wp-section-index" target="_blank">' . esc_html( $title ) . ' Support Forums on WordPress.org</a>') . '</p>';
	  
	  } // End IF Statement
	  
	  return $contextual_help;
	  
	} // End add_contextual_help()
	
	/**
	* load_translations()
	* Makes this plugin available for translation and loads the appropriate language file.
	*/
	
	function load_translations () {
		
		$languages_dir = basename( $this->plugin_path ) . '/languages';
		
		load_plugin_textdomain( 'wp-section-index', false, $languages_dir );
		
	} // End load_translations()
	
	/**
	* activation()
	* Sets up default data when the plugin is activated, if no data currently exists.
	*/
	
	function activation () {
		
		// Setup the version setter, for use with future upgrades, etc.
		$_data = get_plugin_data( $this->plugin_file );
		
		if ( array_key_exists( 'Version', $_data ) ) {
			
			update_option( $this->prefix . 'version', $_data['Version'] );
			
		} // End IF Statement
	
	} // End activation()
	
	/**
	* deactivation()
	* Removes all entries to the database `options` table, if this setting is set to 1.
	*/
	
	function deactivation () {} // End deactivation()
	
} // End class WP_SectionIndex
?>