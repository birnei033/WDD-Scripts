

// sample 1 - returns json - you have to add the necesary function in wp_ajax.php
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

// sample 2 - shortcode request - returns the content of the shortcode
ajax_shortcode('shortcode-name attr=""', function(response){
    // do your thing here
    console.log(response);
});
