<?php

// Get field from group acf field
function get_group_field($atts){
    extract(shortcode_atts(
    array(
        'group'=>'',
        'key' => ''
    ),
    $atts));
    
    if($group){
        return $group[$key];
    }
    
}add_shortcode('get_group_field', 'get_group_field');

// Shortcode to show acf image using id
function wdd_show_image($atts){
    extract(shortcode_atts(
    array(
        'size' => 'full',
        'key' => ''
    ),
    $atts));
    $image = get_field($key);
    $image_size = $size; // (thumbnail, medium, large, full or custom size)
    // if( $image ) {
        return wp_get_attachment_image( $image, $image_size );
    // }
}add_shortcode('acf_image', 'wdd_show_image');


// Shortcode to show acf image using id
function acf_image_on_group($atts){
    extract(shortcode_atts(
    array(
        'size' => 'full',
        'key' => '',
        'group'=>''
    ),
    $atts));
    $image = get_field($group);
    $imar_size = $size; // (thumbnail, medium, large, full or custom size)
    if( $image ) {
        return wp_get_attachment_image( $image[$key], $imar_size );
    }
}add_shortcode('acf_image_on_group', 'acf_image_on_group');

//Shortcode to show the post title
function wdd_show_title($atts)
{
    return get_the_title();
}
add_shortcode('title', 'wdd_show_title');

//Shortcode to show the slug of the post
function wdd_show_slug($atts)
{
    return get_post_field('post_name');
}
add_shortcode('slug', 'wdd_show_slug');

//Shortcode to show the permalink of the post
function wdd_show_permalink($atts)
{
    return get_permalink();
}
add_shortcode('post-link', 'wdd_show_permalink');


//Shortcode to show the post thumbnail if present
function wdd_show_thumbnail($atts)
{
    global $post;
    extract(shortcode_atts(
        array(
            'size' => 'full',
        ),
        $atts));
    return get_the_post_thumbnail($post->ID, $size);
}
add_shortcode('post-thumbnail', 'wdd_show_thumbnail');


//Shortcode to show post excerpt
function wdd_show_excerpt($atts)
{
    extract(shortcode_atts(
        array(
            'length' => '-1',
        ),
        $atts));
    if ($length != -1) {
        return substr(get_the_excerpt(), 0, $length) . ". . .";
    }
    return get_the_excerpt();
}
add_shortcode('post-excerpt', 'wdd_show_excerpt');

//Shortcode to show the post content
function wdd_show_content($atts)
{
    return get_the_content();
}
add_shortcode('post-content', 'wdd_show_content');


//Shortcode to show the category/ies of a post
function wdd_show_category($atts)
{
    $cats = get_the_category();
    $in = (count($cats) > 1) ? "<ul>" : "";
    foreach ($cats as $cat) {
         $in .= (count($cats) > 1) ? "<li><a class='wdd-category' style='color:inherit;' href='/category/$cat->slug'>$cat->name </a></li>" :"<a style='color:inherit;' class='wdd-category' href='/category/ $cat->slug'>$cat->name </a>";
    }
    $in .= (count($cats) > 1) ? "</ul>" : "";
    return $in;
}
add_shortcode('post-category', 'wdd_show_category');

//Shortcode to show the post date
function wdd_show_date($atts)
{
    return get_the_date();
}
add_shortcode('post-date', 'wdd_show_date');

//Shortcode to show author of a post
function wdd_show_author($atts)
{
    extract(shortcode_atts(array('field' => 'nickname'), $atts));
    return get_the_author_meta($field);
}
add_shortcode('post-author', 'wdd_show_author');

//Shortcode to comment counts of a post
function wdd_show_comment_count($atts)
{
    return get_comments_number();
}
add_shortcode('post-comment-count', 'wdd_show_comment_count');

//Shortcode to show the module
if(!function_exists('showmodule_shortcoded')){
    function showmodule_shortcode($moduleid)
    {
        extract(shortcode_atts(array('id' => '*'), $moduleid));
        return do_shortcode('[et_pb_section global_module="' . $id . '"][/et_pb_section]');
    }
    add_shortcode('showmodule', 'showmodule_shortcode');
}

//Shortcode to show the module 2
if(!function_exists('showmodule_shortcoded')){
    function showmodule_shortcoded($moduleid)
    {
        extract(shortcode_atts(array('id' => '*'), $moduleid));
        return do_shortcode(get_post($id)->post_content);
    }
    add_shortcode('showmodule2', 'showmodule_shortcoded');
}

// ------------------------------------------------------------------------------------