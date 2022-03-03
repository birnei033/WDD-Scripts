<?php
include "quicktags.php";
include "shortcodes.php";

function display_post($atts)
{
    /* shortcode args [c_post arg="value"]
    # post_type : the post type 
    # cat : category id | use on 'post' post type only
    # post_per_page : number | how many post will display per page
    # layout_id : layout post id | ask bernz to know how it works
    # order_by : string (date, name)
    # order : string (ASC, DESC)
    # column : number | define the number of columns, default is 1
    # column_content_count : number | it wont work if the column is > 1, defines how many post will display in a column, I have used it in a carousel
    # class : string | add the class in every item
    # root_id : string | add the root id
    # on_tax : string | the slug of a taxonomy
    # on_tax_terms : number | the taxonomy term id
    # template : string | tabs for TAB template, default is for COLUMN template
    */
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    extract(shortcode_atts(array(
        'post_type' => 'post',
        'cat' => '',
        'category__in' => '',
        'posts_per_page' => '-1',
        'layout_id' => 0,
        'order_by' => 'date',
        'order' => 'DESC',
        'column' => 1,
        'column_content_count' => 0,
        'class' => '',
        'root_class'=>'',
        'p'=>'',
        'root_id' => '',
        'on_tax' => '',
        'on_tax_terms' => '',
        'on_tax_fields' => 'term_id',
        'template' => 'default',
    ), $atts));

    // default tax_query
    $on_tax_query = '';
    $on_tax_terms = explode(',', $on_tax_terms);

    // if on_tax is used
    if ($on_tax) {
        if (!$on_tax_terms) {
            $on_tax_terms = get_terms($on_tax, ['fields' => 'ids']);
        }
        $on_tax_query = array(
            array(
                'taxonomy' => $on_tax,
                'field' => $on_tax_fields,
                'terms' => $on_tax_terms,
            ),
        );
    }

    $query = new WP_Query(array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'orderby' => $order_by,
        'p'=>$p,
        'cat' => $cat,
        'category__in' => $category__in,
        'order' => $order,
        'tax_query' => $on_tax_query,
    ));

    // switch template between TAB template and COLUMN template
    // we'll add more template if necessary
    switch ($template) {
        case 'tabs':
            include "templates/tabs.php";
            wp_reset_query();
            if($post_count){
                return $list;
            }else{
                return "No post found";
            }
            break;
        case 'default': // columns
            include "templates/columns.php";
            include "templates/pagination.php";
            wp_reset_query();
            if($post_count){
                return $list;
            }else{
                return "No post found";
            }
            break;
        default: 
            
    } // end switch
    
}
add_shortcode('c_post', 'display_post');
 