<?php

//localize your script
$Param = array(
  'doShortCode' => admin_url('admin-ajax.php?action=handle_ajax_shortcode'),
);
wp_localize_script('handle_ajax_shortcode', 'Param', $Param);
//executes for users that are not logged in.
add_action('wp_ajax_nopriv_handle_ajax_shortcode', 'handle_ajax_shortcode');
//executes for users that are logged in.
add_action('wp_ajax_handle_ajax_shortcode', 'handle_ajax_shortcode');

// include custom jQuery
function shapeSpace_include_custom_jquery() {
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null);
	wp_enqueue_script('helpers', get_stylesheet_directory_uri() . '/wp_ajax/helper-functions.js', array(), null);
}
add_action('wp_enqueue_scripts', 'shapeSpace_include_custom_jquery');

// Create the function "handle_ajax_shortcode"
function handle_ajax_shortcode()
{

    if (!empty($_POST['request']))
    {
        function ajaxRespond($data)
        {
            $respond->request = $_POST['request'];
            $respond->data = $data;
            $myJSON = json_encode($respond);
            echo $myJSON; 
        }

        //******** */ Funtions - add some function to use. **********
        //get cart request. This is use for woocommerce - uncomment this if using woocommerce
        // function get_cart()
        // {
        //     $items = [];
        //     foreach ( WC()->cart->get_cart() as $cart_item ) {
        //     $item_id = $cart_item['data']->get_id();
        //     $title = $cart_item['data']->get_title();
        //     $quantity = $cart_item['quantity'];

        //     $new_item = ['title' => $title, 'quantity' => $quantity];
        //     $items[$item_id] = $new_item;
        //     }
        //     return $items;
        // }
        // return the shortcode output
        function shortcode()
        {
            if (!empty($_POST['request'])) {
                $shortcode = stripslashes($_POST['shortcode']);
                return do_shortcode("[".$shortcode."]");
            }
            return "Not request found";
        }
        
        function test()
        {
            return "Request not found!";
        }

        //************** */ End functions *********************

        switch ($_POST['request']) 
        {
        case "shortcode":
            // echo the shortcode output. Not json.
            echo shortcode();
            break;
        // case "get-cart":
        //     echo ajaxRespond(get_cart());
        //     break
        default:
            echo test();
        }
    }

  //don't forget to stop execution afterward.
  wp_die();
}
