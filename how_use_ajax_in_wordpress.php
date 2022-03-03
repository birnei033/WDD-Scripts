<?php
//1. Localized Script- Pasted this code to the functions.php

//localize your script
$Param = array(
  'doShortCode' => admin_url('admin-ajax.php?action=handle_ajax_shortcode'),
);
wp_localize_script('handle_ajax_shortcode', 'Param', $Param);
//executes for users that are not logged in.
add_action('wp_ajax_nopriv_handle_ajax_shortcode', 'handle_ajax_shortcode');
//executes for users that are logged in.
add_action('wp_ajax_handle_ajax_shortcode', 'handle_ajax_shortcode');

//2. Create the function "handle_ajax_shortcode" on functions.php
//refer / study the code below from HJP wines


function handle_ajax_shortcode()
{
  if (!empty($_POST['request'])) {
    function ajaxRespond($data)
    {
      $respond->request = $_POST['request'];
      $respond->data = $data;
      $myJSON = json_encode($respond);
      echo $myJSON; 
    }

    //get products request
    function get_ladder()
    {
      $data = NULL;
      $category = NULL;
      $products = "";
      if (!empty($_POST['category'])) {
        $category = $_POST['category'];
        $ladder = do_shortcode('[c_post post_type="rounds" layout_id=3784  class="owl-carousel rounds-item-wrapper" column_content_count=4]');
      } else {
        $products = do_shortcode('[c_post post_type="rounds" layout_id=3784  class="owl-carousel rounds-item-wrapper else-this" column_content_count=4]');
      }

      $data->category = $category;
      $data->products = $ladder;

      return $data;
    }
    //get cart request
    function get_cart()
    {
      $items = [];
      foreach ( WC()->cart->get_cart() as $cart_item ) {
        $item_id = $cart_item['data']->get_id();
        $title = $cart_item['data']->get_title();
        $quantity = $cart_item['quantity'];

        $new_item = ['title' => $title, 'quantity' => $quantity];
        $items[$item_id] = $new_item;
      }
      return $items;
    }

    function shortcode(){
      if (!empty($_POST['request'])) {
          $shortcode = stripslashes($_POST['shortcode']);
          return do_shortcode($shortcode);
      }
    return "Not found";
  }
	  
	  function test(){
		  return "test";
	  }

    switch ($_POST['request']) {
      case 'getladder':
        ajaxRespond(get_ladder());
        break;
      case "getcart":
        ajaxRespond(get_cart());
        break;
      case "shortcode":
        ajaxRespond(get_cart());
        break;
      default:
			echo test();
    }
  }

  //don't forget to stop execution afterward.
  wp_die();
}


// 3. ajax request
// refer / study the js code below
?>

<script>
// ajax helper function
var ajaxRequest = (data, callback) => {
        jQuery.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: data,
            success: callback,
            error: () => {
                console.log("error on ajaxRequest:\n" + data);
            }
        });
     };

		// sample 1 - returns json
    ajaxRequest( {
        action: "handle_ajax_shortcode",
        request: "all-products",
        category: 'decor',
        onsale: true,
    }, function(response){
        res = JSON.parse(response);
        $('div#art-gallery').append(res.data.products);
        // console.log(res);
    });

    // sample 2 - shortcode request - returns content of the shortcode
    ajaxRequest( {
        action: "handle_ajax_shortcode",
        request: "shortcode",
        shortcode: '[your-shortcode]',
        onsale: true,
    }, function(response){
        res = JSON.parse(response);
        $('div#art-gallery').append(response);
        // console.log(response);
    });

 </script>