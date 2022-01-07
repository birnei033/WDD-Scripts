<?php
 
class Tooltip{

    function __construct(){
        add_action('et_head_meta','insert_on_head_meta');
        // Hooking up our function to theme setup
        add_action( 'init', 'create_tooltip_posttype' );
    }

    private function insert_on_head_meta(){
        ob_start(); ?> 
            <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
            <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
            <link  rel="stylesheet"  href="https://unpkg.com/tippy.js@6/animations/scale.css"/>
            <link  rel="stylesheet"  href="https://unpkg.com/tippy.js@6/themes/light.css"/>
        <?php echo ob_get_clean();
    }

    // Creating a custom post type for the mega menu, dropdown and tooltip content.
    // On the post, you can insert shortcodes.
    private function create_tooltip_posttype() {
    
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
}