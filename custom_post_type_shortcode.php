<?php
function appthemes_add_quicktags()
{
    if (wp_script_is('quicktags')) {
        ?>
      <script type="text/javascript">
      QTags.addButton( 'wdd_show_module', 'showmodule', '[showmodule id=""]');
      QTags.addButton( 'wdd_show_content', 'post-content', '[post-content]');
      QTags.addButton( 'wdd_show_excerpt', 'post-excerpt', '[post-excerpt length="-1"]');
      QTags.addButton( 'wdd_show_thumbnail', 'post-thumbnail', '[post-thumbnail size="full"]');
      QTags.addButton( 'wdd_show_permalink', 'post-link', '[post-link]');
      QTags.addButton( 'wdd_show_title', 'title', '[title]');
      // QTags.addButton( 'divi_button_shortcode', 'divi_button', '[divi_button alignment="left" href="#"]','[/divi_button]');
      QTags.addButton( 'wdd_get_acf_field', 'get-field', '[get-field key=""]');
      QTags.addButton( 'wdd_get_category', 'post-category', '[post-category]');
      QTags.addButton( 'wdd_get_date', 'post-date', '[post-date]');
      QTags.addButton( 'wdd_get_author', 'post-author', '[post-author]');
	  QTags.addButton( 'wdd_get_slug', 'slug', '[slug]');
      QTags.addButton( 'wdd_get_comment_count', 'post-comment-count', '[post-comment-count]');
      QTags.addButton( 'wdd_show_image', 'acf_image', '[acf_image size="full", key=""]');
      QTags.addButton( 'wdd_show_image_from_grou', 'acf_image_on_group', '[acf_image_on_group size="full" key="" group=""]');
       QTags.addButton( 'wdd_show_get_group_field', 'get_group_field', '[get_group_field group="" key=""]');
    </script>
    <?php
}
}
add_action('admin_print_footer_scripts', 'appthemes_add_quicktags');

function get_group_field($atts){
    extract(shortcode_atts(
    array(
        'group'=>'',
        'key' => ''
    ),
    $atts));
    $field = get_field($group);
    if($field){
        return $field[$key];
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



function wdd_show_title($atts)
{
    return get_the_title();
}
add_shortcode('title', 'wdd_show_title');

function wdd_show_slug($atts)
{
    return get_post_field('post_name');
}
add_shortcode('slug', 'wdd_show_slug');

function wdd_show_permalink($atts)
{
    return get_permalink();
}
add_shortcode('post-link', 'wdd_show_permalink');

function wdd_show_thumbnail($atts)
{
    extract(shortcode_atts(
        array(
            'size' => 'full',
        ),
        $atts));
    return get_the_post_thumbnail($post->ID, $size);
}
add_shortcode('post-thumbnail', 'wdd_show_thumbnail');

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

function wdd_show_content($atts)
{
    return get_the_content();
}
add_shortcode('post-content', 'wdd_show_content');

/*
function wdd_getField($atts)
{
extract(shortcode_atts(array(
'key' => '',
), $atts));
if ($key == '') {
$key = 'attribute key is required.';
}
return get_field($key);
}add_shortcode('get-field', 'wdd_getField');
 */

function wdd_getField($atts)
{
    extract(shortcode_atts(array(
        'key' => '',
		'sub_field' => '',
        'span_after_space' => 0,
        'type' => '',
    ), $atts));
    if ($key == '') {
        $key = 'attribute key is required.';
    }

    $return = '';

    if ($key == 'title') {
        $return = get_the_title();
    } else {
        $return = get_field($key);
    }
	
	if ($sub_field == 'title') {
        $return = get_the_title();
    } else {
        $return = the_sub_field($sub_field);
    }

    if (is_numeric($span_after_space) && $span_after_space > 0) {
        $str_arr = explode(" ", $return);
        $return = '';
        for ($i = 0; $i < count($str_arr); ++$i) {

            if ($i == $span_after_space) {
                $return .= '<span>';
            }
            $return .= $str_arr[$i];
            if ($i < count($str_arr) - 1) {
                $return .= ' ';
            } else {
                $return .= '</span>';
            }
        }
    }

    if ($type) {
        $if_hidden = "";
        if (!$return) {
            $if_hidden = " no-content";
        }
        switch ($type) {
            case "phone":
                $return = '<a class="phone' . $if_hidden . '" href="tel:' . '+61' . substr($return, 1) . '">' . $return . '</a>';
                break;
            case 'email':
                $return = '<a class="email' . $if_hidden . '" href="mailto:' . $return . '">' . $return . '</a>';
                break;
            case 'url':
				$text = '';
				switch($key) {
					case "linkedin":
						$text = "linkedin.com/" . get_post_field('post_name');
						break;
					default:
						$text = $return;
				}
                $return = '<a class="url ' . $key . $if_hidden . '" href="' . $return . '" target="_blank">' . $text . '</a>';
                break;
            default:
        }
    }

    return $return;

}add_shortcode('get-field', 'wdd_getField');

function wdd_show_category($atts)
{
    $cats = get_the_category();
    $in = (count($cats) > 1) ? "<ul>" : "";
    // $in .= (count($cats > 1)) ? "<li>".count($cats)."</li>" : '';
    foreach ($cats as $cat) {
         $in .= (count($cats) > 1) ? "<li><a class='wdd-category' style='color:inherit;' href='/category/$cat->slug'>$cat->name </a></li>" :"<a style='color:inherit;' class='wdd-category' href='/category/ $cat->slug'>$cat->name </a>";
    }
    $in .= (count($cats) > 1) ? "</ul>" : "";
    return $in;
}
add_shortcode('post-category', 'wdd_show_category');

function wdd_show_date($atts)
{
    return get_the_date();
}
add_shortcode('post-date', 'wdd_show_date');

function wdd_show_author($atts)
{
    extract(shortcode_atts(array('field' => 'nickname'), $atts));
    return get_the_author_meta($field);
}
add_shortcode('post-author', 'wdd_show_author');

function wdd_show_comment_count($atts)
{
    return get_comments_number();
}
add_shortcode('post-comment-count', 'wdd_show_comment_count');

//Shortcode to show the module
// function showmodule_shortcode($moduleid)
// {
//     $args = shortcode_atts(array('id' => '*'), $moduleid);
//     return do_shortcode('[et_pb_section global_module="' . $args['id'] . '"][/et_pb_section]');
// }
// add_shortcode('showmodule', 'showmodule_shortcode');
// ------------------------------------------------------------------------------------

add_filter('redirect_canonical','pif_disable_redirect_canonical');


function display_post($atts)
{
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
    // $paged = $_GET['pag'];
    extract(shortcode_atts(array(
        'post_type' => 'post',
        'cat' => '',
        'category__in' => '',
        'posts_per_page' => '-1',
        'layout_id' => 0,
        'order_by' => 'date',
        'order' => 'DESC',
        'column' => 2,
        'column_content_count' => 0,
        'class' => '',
        'root_id' => '',
        'on_tax' => '',
        'on_tax_terms' => '',
        'on_tax_fields' => 'term_id',
        'template' => 'default',
		'project_category' => '',
		'parent' => '',
		's'=>'',
		'terms_data_out'=>false,
        'region'=>false
    ), $atts));
    $on_tax_query = '';


    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'orderby' => $order_by,
        'cat' => $cat,
        'category__in' => $category__in,
        'order' => $order,
        // 'tax_query' => $on_tax_query,
		'post_parent' => $parent
    );
   
    if($s != ''){
        $args['s'] = $s;
    }
    $args_for_terms_count = $args;
    $post_count = count(get_posts($args_for_terms_count));
    $terms = array();
    if($terms_data_out){
        $taxterms = get_terms(array('taxonomy'=>$on_tax, 'hide_empty'=>true));
        $post_count = count(get_posts($args_for_terms_count));
        foreach($taxterms as $term){
            $args_for_terms_count['tax_query'] = array(array('taxonomy'=>$on_tax, 'field'=>'slug', 'terms'=>$term->slug));
            $terms[$term->slug]['count'] = count(get_posts($args_for_terms_count));
            $terms[$term->slug]['name'] = $term->name;
            $terms[$term->slug]['active'] = ($term->slug == $on_tax_terms) ? 'active' : '';
            $terms[$term->slug]['slug'] = $term->slug;
            $terms["all"]['name'] = "All Results";
            $terms["all"]['count'] = $post_count;
            $terms["all"]['active'] = ($on_tax_terms == '') ? "active" : '';
            $terms["all"]['post_type'] = $post_type;
            $terms["all"]['slug'] = '';
           
       }
    }
     if ($on_tax_terms) {
        if (!$on_tax_terms) {
           $on_tax_terms = get_terms($on_tax, ['fields' => 'ids']);
//              $on_tax_terms[$id] = get_term_by('id', $id, 'category');
        }
        $on_tax_query = array(
            array(
                'taxonomy' => $on_tax,
                'field' => $on_tax_fields,
                'terms' => $on_tax_terms,
            ),
        );

        if($region){
            array_push($on_tax_query, array(
                'taxonomy' => 'region_taxamony',
                'field'    => 'slug',
                'terms'    => $region,
            ));
            $on_tax_query['relation'] = 'AND';
        }
        $args['tax_query'] = $on_tax_query;
    }
    
    
    $query = new WP_Query($args);

    switch ($template) {
        case 'tabs':

            $list = '<div id="' . $root_id . '" class="et_pb_module et_pb_tabs ' . $class . '">';
            $list_menu = '<ul class="et_pb_tabs_controls clearfix">';
            $list_content = '<div class="et_pb_all_tabs">';

            $index = 0;

            while ($query->have_posts()): $query->the_post();

                $list_menu .= '<li class="et_pb_tab_' . $index;
                if ($index == 0) {
                    $list_menu .= ' et_pb_tab_active';
                }
                $list_menu .= '" id="' . get_post_field('post_name') . '"><a href="#">' . get_the_title() . '</a></li>';

                $list_content .= '<div class="et_pb_tab et_pb_tab_' . $index . ' clearfix';
                if ($index == 0) {
                    $list_content .= ' et_pb_active_content et-pb-active-slide';
                }
                $list_content .= '"><div class="et_pb_tab_content">' . do_shortcode(get_the_content()) . '</div><!-- .et_pb_tab_content" --></div> <!-- .et_pb_tab -->';
                ++$index;

            endwhile;

            $list_menu .= '</ul>';
            $list_content .= '</div> <!-- .et_pb_all_tabs -->';
            $list .= $list_menu . $list_content . '</div> <!-- .et_pb_all_tabs -->';

            wp_reset_query();
            return $list;
            break;

        case 'default':
        default:
            $column_open = '';
            $column_closing = '';
            $col_open_last = '';
            $col_closing_last = '';
            $style = 'style="margin-bottom: 49px;"';
            switch ($column) {
                case 2:
                    $col_open = '  <div class="one_half">';
                    $column_closing = '</div>';
                    $col_open_last = '<div class="one_half et_column_last">';
                    $col_closing_last = '</div><div class="clear" ' . $style . ' ></div>';
                    break;
                case 3:
                    $col_open = '  <div class="one_third">';
                    $column_closing = '</div>';
                    $col_open_last = '<div class="one_third et_column_last">';
                    $col_closing_last = '</div><div class="clear" ' . $style . '></div>';
                    break;
                case 4:
                    $col_open = '  <div class="one_fourth">';
                    $column_closing = '</div>';
                    $col_open_last = '<div class="one_fourth et_column_last">';
                    $col_closing_last = '</div><div class="clear" ' . $style . '></div>';
                    break;
                default:
                    $col_open = '  <div class="">';
                    $column_closing = '</div>';
                    $col_open_last = '<div class="">';
                    $col_closing_last = '</div>';
                    break;
            }

            $rows = 1;
            $closing = '</div>';
            $list = '<div id = "' . $root_id . '" class="wdd-wrapper ' . $class . '">';
            $closer = "";
            $group = 1;
            $post_count = $query->post_count;
            while ($query->have_posts()): $query->the_post();

                if ($rows < $column) {
                    $list .= $col_open;
                    $rows++;
                    $closing = $column_closing;
                } else {
                    if ($column_content_count != 0 && $column == 1) {
                        $rows = ($rows > $column_content_count) ? 1 : $rows;
                        $list .= $rows == 1 ? "<div class='group group-$group'>" : "";
                        $list .= $col_open_last."<!-- $post_count -->";
                        $closing = $column_closing;
                        if (($rows == $column_content_count) || ($post_count == 1)) {
                            $closer = "</div>";
                        }else{
                            $closer = "";
                        }
                        $rows++;
                        $post_count--;
                        $group = ($rows > $column_content_count) ? ($group + 1) : $group;
                    } else {
                        $list .= $col_open_last;
                        $rows = 1;
                        $closing = $col_closing_last;
                    }
                }
                $list .= do_shortcode(get_post($layout_id)->post_content);
                $list .= $closing;
                $list .= $closer;

            endwhile;
            $list .= '</div>';

            if ($posts_per_page != -1) {
                $list .= '<div class="custom-pagination pagination">';
                $list .= paginate_links(array(
                    'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                    'total' => $query->max_num_pages,
                    'current' => max(1, $paged),
                    'format' => '?paged=%#%',
                    'show_all' => false,
                    'type' => 'plain',
                    'end_size' => 2,
                    'mid_size' => 1,
                    'prev_next' => true,
                    'prev_text' => sprintf('<i></i> %1$s', __('Previous', 'text-domain')),
                    'next_text' => sprintf('%1$s <i></i>', __('Next', 'text-domain')),
                    'add_args' => false,
                    'add_fragment' => '',
                ));
                $list .= '</div>';
            } //end if
            wp_reset_query();
            $list .= "<div style='display: none;' class='taxonomies'>".json_encode($terms)."</div>";
            if(!$query->found_posts){
                return "No result found."; 
            }
            return $list;
    }
}
add_shortcode('c_post', 'display_post');

