<?php

/*
* Custom field keys to be used. Just use wordpress' default custom field function rather then ACF.
* on_post = post id on where to append the tooltip content. 
    0 value means to append the content in all tha pages, 
    only use 0 if the trigger/button is present on all pages like the header and footer elements.
* properties = properties of tippy.js library. 
    Just input js array value eg | key: "value", key: "value", and so on |
* trigger = selector of the trigger / button eg. | .class or #id |
*/

// style 
function modal_enqueue_styles() {
    wp_enqueue_style( 'modal-style', get_stylesheet_directory_uri() . '/modal/modal.css' );
}
add_action( 'wp_enqueue_scripts', 'modal_enqueue_styles' );

// Creating a custom post type.
// On the post, you can insert shortcodes.
function create_modal_posttype() {
 
    register_post_type( 'modal',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Modals' ),
                'singular_name' => __( 'Modal' )
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'tooltip'),
            'show_in_rest' => true,
            'supports'=>array( 'title', 'editor', 'custom-fields' ),
            'publicly_queryable'=>true,
 
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_modal_posttype' );

// Displaying the content and its javascript
function modal_to_footer(){
    $current_page_id = get_the_ID();
    // The Query
    $the_query = new WP_Query( array(
        'post_type'=>'modal'
    ) );
     
    // The Loop
    if ( $the_query->have_posts() ) {

        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            $trigger = get_post_meta(get_the_ID(),'trigger');
            $tippy_props = get_post_meta(get_the_ID(),'properties');
            $display_on_post_id = get_post_meta(get_the_ID(),'on_post');
            $css = get_post_meta(get_the_ID(),'css');
            if($trigger != '' ){
                if($display_on_post_id[0] == $current_page_id){ 
                    include 'modal_content.php';
                }elseif($display_on_post_id[0] == 0){
                    include 'modal_content.php';
            }//end if display_on_post
        
            } // end if trigger
    } // end while
    } else {
        // no posts found
    }

    /* Restore original Post Data */
    wp_reset_postdata();

}
// Insert the post/tooltip content after the main content of current page but displayed none;
add_action('wp_footer', 'modal_to_footer');