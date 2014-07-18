<?php
/*
Plugin Name: Totally Tabular
Plugin URI: http://dabzo.com/totally-tabular
Description: Responsive Tabbed Widgets - Aiming to Increase Usability through responsive tabbed-regions & drop-down-menus.
Version: 1.00
Author: Adam McFadyen
Author URI: http://dabzo.com 
License: GPL2

Copyright 2013 Adam McFadyen  (email : adam@dabzo.com)

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

ReadMe: Use [tabular] shortcode to include widgets added to the Tabbed-Sidebar in your WordPress theme. 
Tabs will cycle automatically & are clickable.
*/

require_once('admin-page.php');

function ttabular_sidebar(){
  register_sidebar( array(
    'name' => __( 'Tabular Sidebar', 'dabzo' ),
    'id' => 'ttabular-sidebar',
    'description' => __( 'Sidebar to display widgets as tabs via [tabular] shortcode.', 'dabzo' ),
    'before_widget' => '<div class="ttab-widget">',
    'after_widget' => '</div></div>',
    'before_title' => '<h5 class="ttab-title">',
    'after_title' => '</h5><div class="ttab-content">',
  ) );
}
add_action('init', 'ttabular_sidebar');

function ttabular_get_options(){
	$options = get_option('ttabular_settings');
	return $options;
} 
/*
function tabular_code(){
	if ( is_active_sidebar( 'ttabular-sidebar' ) ) :
		$options = ttabular_get_options();
		($options['rotator_speed'] ? $itemInterval = $options['rotator_speed'] : $itemInterval = "5000");

		echo'<script>';
		echo'var itemInterval = ' . $itemInterval . ';';
		echo'</script>';
	endif;
}
add_action('wp_footer', 'tabular_code'); 
*/
function ttabular_scripts_method() {
	$options = ttabular_get_options();
	($options['rotator_speed'] ? $itemInterval = $options['rotator_speed'] : $itemInterval = "5000");

	wp_register_script( 'ttabular-main', plugins_url('/js/main.js' , __FILE__ ), array( 'jquery' ), null );
	$optionsData = array(
		'itemInterval' => $itemInterval,
		'layoutOption' => $layoutOption
	);
	wp_localize_script( 'ttabular-main', 'optionsData', $optionsData );
	wp_enqueue_script( 'ttabular-main' );	

	if($options['layout_type']=='vertical'):
		wp_enqueue_style( 'ttabular-style', plugins_url('/css/vertical-style.css' , __FILE__ ) );
	else:
		wp_enqueue_style( 'ttabular-style', plugins_url('/css/style.css' , __FILE__ ) );
	endif;
}
add_action( 'wp_enqueue_scripts', 'ttabular_scripts_method' );

function ttabular_format_widgets( $title ) {	
	$title = "<span>" . $title . "</span>";
	return $title;
}

function ttabular_shortcode_output(){
	if ( is_active_sidebar( 'ttabular-sidebar' ) ) :
	ob_start();
		echo'<div id="ttab-container" class="widget-area rotator-tab-section">';
		add_filter ( 'widget_title', 'ttabular_format_widgets' );
		dynamic_sidebar( 'ttabular-sidebar' );
		remove_filter ( 'widget_title', 'ttabular_format_widgets' );
		echo'</div>';
	return ob_get_clean();		
	endif;
}

//[tabular]
function show_tabs( $atts ){
	return ttabular_shortcode_output();
}
add_shortcode( 'tabular', 'show_tabs' );

