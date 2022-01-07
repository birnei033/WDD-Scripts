<?php

/*
* This script was inspired by Divi Mega pro. This can be use to create mega menus, dropdown and tooltips using the tippy.js library.
* This app is inteded only for Divi theme and for WDD developers. Feel free to customize this according to your needs.
* App name: MyTooltip
* Dev: BernZ
* Version: 1.0
*Tippy url: https://atomiks.github.io/tippyjs/v6/getting-started/
*/

/*
* Custom field keys to be used. Just use wordpress' default custom field function rather then ACF.
* on_post = post id on where to append the tooltip content. 
    0 value means to append the content in all tha pages, 
    only use 0 if the trigger/button is present on all pages like the header and footer elements.
* properties = properties of tippy.js library. 
    Just input js array value eg | key: "value", key: "value", and so on |
* trigger = selector of the trigger / button eg. | .class or #id |
*/

// adding tippy.js library in the header. Feel free to update the library if outdated or add some library.
function insert_on_head_meta(){
    ob_start(); ?> 
        <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
        <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
        <link  rel="stylesheet"  href="https://unpkg.com/tippy.js@6/animations/scale.css"/>
        <link  rel="stylesheet"  href="https://unpkg.com/tippy.js@6/themes/light.css"/>
    <?php echo ob_get_clean();
}add_action('et_head_meta','insert_on_head_meta');

// Creating a custom post type for the mega menu, dropdown and tooltip content.
// On the post, you can insert shortcodes.
function create_tooltip_posttype() {
 
    register_post_type( 'my-tooltip',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Tool tip' ),
                'singular_name' => __( 'Tool tips' )
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
add_action( 'init', 'create_tooltip_posttype' );

// Displaying the content and its javascript
function to_footer(){
    $current_page_id = get_the_ID();
    // The Query
    $the_query = new WP_Query( array(
        'post_type'=>'my-tooltip'
    ) );
     
    // The Loop
    if ( $the_query->have_posts() ) {

        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            $trigger = get_post_meta(get_the_ID(),'trigger');
            $tippy_props = get_post_meta(get_the_ID(),'properties');
            $display_on_post_id = get_post_meta(get_the_ID(),'on_post');
            if($trigger != '' ){
                if($display_on_post_id[0] == $current_page_id){ ?>

              <div style="display: none;"><div id="my-tooltip-<?=get_the_ID()?>"><?=the_content();?></div></div>

             <script>

        		var the_content = document.querySelector('#my-tooltip-<?=get_the_ID()?>');
        
        		// tippy.
        		var the_trigger = document.querySelector('<?=$trigger[0]?>');
        		tippy(the_trigger, {
                        content: the_content,
                        allowHTML: true,
                        interactive: true,
                    // generating the properties from properties custom field if the custom field is defined.
                    <?php if($tippy_props == '' || $tippy_props == false) { ?>
                        trigger: "click",
                        placement: "bottom",
                        animation: "scale",
                     <?php }else{
                         echo $tippy_props[0];
                     } ?>
        		});
        	</script>
        <?php }elseif($display_on_post_id[0] == 0){
                ?>

              <div style="display: none;"><div id="my-tooltip-<?=get_the_ID()?>"><?=the_content();?></div></div>

             <script>

        		var the_content = document.querySelector('#my-tooltip-<?=get_the_ID()?>');
        
        		// tippy. Feel free to add/edit its properties as you need.
        		var the_trigger = document.querySelector('<?=$trigger[0]?>');
        		tippy(the_trigger, {
                        content: the_content,
                        allowHTML: true,
                        interactive: true,
                    <?php if($tippy_props == '' || $tippy_props == false) { ?>
                        trigger: "click",
                        placement: "bottom",
                        animation: "scale",
                     <?php }else{
                         echo $tippy_props[0];
                     } ?>
        		});
        	</script>
        <?php
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
add_action('et_after_main_content', 'to_footer');


