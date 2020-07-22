<?php
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

// ##################### END #####################################
//======================================================================
// [showmodule id =""]
// Show module shortcode
//======================================================================
function showmodule_shortcode($moduleid)
{
    $args = shortcode_atts(array('id' => '*'), $moduleid);
    return do_shortcode('[et_pb_section global_module="' . $args['id'] . '"][/et_pb_section]');
}
add_shortcode('showmodule', 'showmodule_shortcode');
// ##################### END #####################################

//======================================================================
// Header top
// add section above header top
//======================================================================
function mp_custom_header_above( $main_header ) {
	if ( is_front_page()) {
			$custom_header = '<header id="custom-header-above">';
        	$custom_header .= do_shortcode('[showmodule id ="36"]');
 		 	$custom_header .= '</header> <!-- #custom-header-above -->';
			return $custom_header . $main_header;
	}
	elseif  ( is_page(12) ) {
			$custom_header = '<header id="custom-page-header">';
        	$custom_header .= do_shortcode('[showmodule id ="45"]');
 		 	$custom_header .= '</header> <!-- #custom-header-above -->';
			return $custom_header . $main_header;
	}
	elseif ( is_search() ) {
		$custom_header = '<header id="search-header">';
		$custom_header .= do_shortcode('[showmodule id ="69"]');
		$custom_header .= '</header> <!-- #search-header -->';
		return $custom_header . $main_header;;
	}
	else {
		   echo do_shortcode('[showmodule id ="36"]') . $main_header;
	}
}
add_filter( 'et_html_main_header', 'mp_custom_header_above' );
// ##################### END #####################################


//======================================================================
// Navigation top
// add Element on navigation end
//======================================================================
function et_header_top_hook_example() {
 	echo do_shortcode('[showmodule id ="61"]');
// 	if(is_active_sidebar("sidebar-3")){
// 		dynamic_sidebar("sidebar-3");
// 	}
}
add_action( 'et_header_top', 'et_header_top_hook_example' );

// 	if(is_active_sidebar("sidebar-3")){
// 		dynamic_sidebar("sidebar-3");
// 	}
// ##################### END #####################################

//======================================================================
// SEARCH HEADER
// Adds Divi Library Item To Search Results Pages
//======================================================================
// function mp_search_header( $content ) {
// 	if ( is_search() ) {
// 		$custom_header = '<header id="search-header">';
// 			$custom_header .= do_shortcode('[showmodule id ="69"]');
// 		$custom_header .= '</header> <!-- #search-header -->';
// 		return $content . $custom_header;
// 	}
// }
// add_filter( 'et_html_main_header', 'mp_search_header' );


//======================================================================
// SEARCH QUERY SHORTCODE
// Create a shortcode for search query to return on search results pages
//======================================================================
function s_add_search_query() {
	return apply_filters( 'get_search_query', get_query_var( 's' ) );
}
add_shortcode( 'add_search_query', 's_add_search_query' );

//======================================================================
// SINGLE PAGE | Fires right before the_content() is called.
//example is insert a section above the product page
//======================================================================

function add_section_before_content(){
	if(is_post_type_archive('product')):
	do_shortcode("[showmodule id=\"5070\"]");
	endif;
}add_shortcode('et_before_content', 'add_section_before_content');

// ##################### END #####################################

// google calendar link Shortcode

function calendar_link($atts){
	extract(shortcode_atts(array(
		'key' => '',
		'group'=>'google_calendar_event'
	), $atts));
	
	if ($key == '') {
		$key = 'attribute key is required.';
	}
	if($group){
		$group = get_field($group);
		$format = "Ymd\THi";
		$timeZone =  get_option('gmt_offset');
		$timeZone = strpos($timeZone, '-') !== false ? $timeZone : "+".$timeZone;
// 		start date time
		$starttime = $group['g_start_date'];
		$date = new DateTime($starttime." ".$timeZone);
		$date->setTimezone(new DateTimeZone('+0000'));
		$eventstart = 	$date->format($format);
		
// 		end date time
		$endtime = $group['g_end_date'];
		$enddate = new DateTime($endtime." ".$timeZone);
		$enddate->setTimezone(new DateTimeZone('+0000'));
		$eventend = $enddate->format($format);

		return str_replace("|","T","https://www.google.com/calendar/render?action=TEMPLATE&text=".$group['g_title']."&dates=".$eventstart."00Z/".$eventend."00Z&details=".$group['g_details']."&location=".get_field('place')."&sf=true&output=xml"); 
	}
}add_shortcode('g-event-link', 'calendar_link');
// ##################### END #####################################

//  REMOVE PRODUCT DESCRIPTION TAB FILTER
add_filter( 'woocommerce_product_tabs', 'rmeove_product_description', 20, 1 );
function rmeove_product_description( $tabs ) {

	// Remove the description tab
    if ( isset( $tabs['description'] ) ) unset( $tabs['description'] );      	    
    return $tabs;
}
// ##################### END #####################################

// ACF PHOTO GALLRY FIELD OUTPUT IMAGES SHORTCODE

function get_build_gallery($atts){
	global $post;
	extract(shortcode_atts(array(
        'id' => $post->ID,
    ), $atts));
	$html = '';
	$galleries =  acf_photo_gallery('our_build_gallery', $id);
			$size = 'medium';
			if(count($galleries)):
				foreach( $galleries as $gallery):
					$html .= wp_get_attachment_image( $gallery['id'], $size );
				endforeach;
			endif;
	return $html;
}	
add_shortcode('build-gallery', 'get_build_gallery');

// ##################### END #####################################