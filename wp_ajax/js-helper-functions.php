<script>
    //*********** */ ajax helper function. Paste these helpers on the header *******

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

 var ajax_shortcode = (shortcode, callback)=>{
    ajaxRequest( {
        action: "handle_ajax_shortcode",
        request: "shortcode", // this is the function request from wp_ajax.php
        shortcode: shortcode,
    }, callback);
 }

// ************ END helper functions**************
</script>