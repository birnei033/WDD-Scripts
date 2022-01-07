<?php
if (!defined('ABSPATH')) die();

// this function add a quicktag buttons to the wordpress editor
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
      QTags.addButton( 'wdd_get_acf_field', 'acf field', '[acf field="field_name" post_id=""]');
      QTags.addButton( 'wdd_get_category', 'post-category', '[post-category]');
      QTags.addButton( 'wdd_get_date', 'post-date', '[post-date]');
      QTags.addButton( 'wdd_get_author', 'post-author', '[post-author]');
	  QTags.addButton( 'wdd_get_slug', 'slug', '[slug]');
      QTags.addButton( 'wdd_get_comment_count', 'post-comment-count', '[post-comment-count]');
      QTags.addButton( 'wdd_get_comment_count', 'post-comment-count', '[post-comment-count]');
    </script>
    <?php
}
}
add_action('admin_print_footer_scripts', 'appthemes_add_quicktags');

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
function showmodule_shortcode($moduleid)
{
    extract(shortcode_atts(array('id' => '*'), $moduleid));
    return do_shortcode('[et_pb_section global_module="' . $id . '"][/et_pb_section]');
}
add_shortcode('showmodule', 'showmodule_shortcode');

//Shortcode to show the module 2
function showmodule_shortcoded($moduleid)
{
    extract(shortcode_atts(array('id' => '*'), $moduleid));
    return do_shortcode(get_post($id)->post_content);
}
add_shortcode('showmodule2', 'showmodule_shortcoded');

// ------------------------------------------------------------------------------------

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

            $list = '<div id="' . $root_id . '" class="et_pb_module et_pb_tabs ' . $root_class . '">';
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
        // don't mind it here. LOL!
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
                case "table":
                    $col_open = '  <tr>';
                    $column_closing = '</tr>';
                    $col_open_last = '<tr>';
                    $col_closing_last = '<tr>';
                    break;
                default:
                // if the column is 1 or unset or greater than 4
                    $col_open = '  <div class="one-column">';
                    $column_closing = '</div>';
                    $col_open_last = '<div class="'.$class.'">';
                    $col_closing_last = '</div>';
                    break;
            }

            $rows = 1;
            $closing = '</div>';
            $list = '<div id = "' . $root_id . '" class="wdd-wrapper ' . $class . '">'; // the root element
            $closer = "";
            $group = 1; // used for grouping 
            $post_count = $query->post_count; //counts the number of posts available
            while ($query->have_posts()): $query->the_post();

                if ($rows < $column) {
                    $list .= $col_open;
                    $rows++;
                    $closing = $column_closing;
                } else {
                    // this one is for grouping if the column_content_count is used and column is 1
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
                $list .= do_shortcode(get_post($layout_id)->post_content); // displays the content of the layout post type.
                $list .= $closing;
                $list .= $closer;

            endwhile;
            $list .= '</div>';
            // pagination appears if the post_per_page is not equal to -1
            if ($posts_per_page != -1) {
                $list .= '<div class="pagination">';
                $list .= paginate_links(array(
                    'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                    'total' => $query->max_num_pages,
                    'current' => max(1, get_query_var('paged')),
                    'format' => '?paged=%#%',
                    'show_all' => false,
                    'type' => 'plain',
                    'end_size' => 2,
                    'mid_size' => 1,
                    'prev_next' => true,
                    'prev_text' => sprintf('<i></i> %1$s', __('Older Posts', 'text-domain')),
                    'next_text' => sprintf('%1$s <i></i>', __('Newer Posts', 'text-domain')),
                    'add_args' => false,
                    'add_fragment' => '',
                ));
                $list .= '</div>';
            } //end if
            wp_reset_query();
            return $list;
    } // end switch
}
add_shortcode('c_post', 'display_post');
 