<?php
/*
Plugin Name: WP Section Index
Plugin URI: http://matty.co.za/plugins/wp-section-index/
Description: Create a table of contents in a widget for the current page or blog post, using headings from the content.
Version: 1.1.1
Author: Matty
Author URI: http://matty.co.za/
*/
?>
<?php
/*  Copyright 2010  Matty  (email : nothanks@idontwantspam.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
	// Setup paths for this plugin and the `classes` folder.
	global $wpsi_plugin_path, $wpsi_classes_path;
	
	$wpsi_plugin_path = dirname(__FILE__);
	$wpsi_plugin_dir = basename(dirname(__FILE__));
	$wpsi_classes_path = $wpsi_plugin_path . '/classes/';
	
	// Require and instantiate the main plugin classes.
	require ( $wpsi_classes_path . 'wp_sectionindex.class.php' );
	require ( $wpsi_classes_path . 'widget.class.php' );
	$wpsi = new WP_SectionIndex( dirname( __FILE__ ), trailingslashit( WP_PLUGIN_URL ) . plugin_basename( dirname( __FILE__ ) ), 'wpsi_', __FILE__ );
	
	$wpsi->tag 					= get_option ( 'wpsi_tag' );
	$wpsi->use_pages 			= get_option ( 'wpsi_use_pages' );
	$wpsi->use_posts 			= get_option ( 'wpsi_use_posts' );
	$wpsi->use_backtotop		= get_option ( 'wpsi_use_backtotop' );
	$wpsi->backtotop_id 		= get_option ( 'wpsi_backtotop_id' );
	$wpsi->display_all_anchors 	= get_option( 'wpsi_display_all_anchors' );
	
	// Setup actions, filters and hook
	
	// Filters on the_content. This is what makes the plugin work.
	// If this is altered, the sky will fall on your head.	
	add_filter ( 'the_content', array(&$wpsi, 'create_backtotop_anchors'), 99, 2 );
	add_filter ( 'get_the_content', array(&$wpsi, 'create_backtotop_anchors'), 99, 2 );
	add_filter ( 'the_content', array(&$wpsi, 'create_content_anchors'), 99, 2 );
	add_filter ( 'get_the_content', array(&$wpsi, 'create_content_anchors'), 99, 2 );
	
	// Utility actions for the widget, admin options screen, post and page meta boxes, etc.	
	add_action ( 'widgets_init', array(&$wpsi, 'register_widget') );
	add_action ( 'admin_menu', array(&$wpsi, 'settings_register') );
	add_action ( 'admin_notices', array(&$wpsi, 'admin_notice') );
	add_action ( 'admin_menu', array(&$wpsi, 'create_meta_box') );
	add_action ( 'save_post', array(&$wpsi, 'save_meta_box_data') );
	add_action ( 'contextual_help', array( &$wpsi, 'add_contextual_help' ), 10, 3 );
	add_action ( 'init', array( &$wpsi, 'load_translations' ) );
	
	// Run this on activation of the plugin.
	register_activation_hook( __FILE__, array( &$wpsi, 'activation' ) );
?>