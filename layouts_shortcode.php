<?php

if (!defined('ABSPATH')) die();

// this function add a quicktag buttons to the wordpress editor
function appthemes_add_quicktags()
{
    if (wp_script_is('quicktags')) {
        ?>
      <script type="text/javascript">
      QTags.addButton( 'layouts', 'layout', '[layouts pagination_var=""]<div id="args">#query args in json format</div>[/layout]');
    </script>
    <?php
}
}
add_action('admin_print_footer_scripts', 'appthemes_add_quicktags');

add_shortcode('layout', function($atts, $content = null){
    extract(shortcode_atts(array(
        'pagination_var'=>null
    ), $atts));
    $doc=new domDocument();$doc->loadHTML($content);$argNode=$doc->getElementById('args');
    $json = $argNode->textContent;$argNode->parentNode->removeChild($argNode);$content = $doc->saveHTML();
    $_finalContent = '';$json = str_replace(array('“','”'), '"',  $json); $args = json_decode($json, 1); 
    $posts_per_page = $args['posts_per_page'] ? $args['posts_per_page'] : -1;
    if(!$pagination_var){
        $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
        global $paged, $wp_query, $wp;
        $argss = wp_parse_args($wp->matched_query);
        if ( !empty ( $argss['paged'] ) && 0 == $paged ) {
            $paged = $argss['paged'];
        }
    }else{$paged = ($_GET[$pagination_var]) ? $_GET[$pagination_var] : 1;}
    $args['paged'] = $paged;$postslist = new WP_Query( $args );ob_start();
    if($postslist){
        while ($postslist->have_posts()): $postslist->the_post(); global $post; $_tmp = $content; $post_arr = (array)$post;
            foreach ($post_arr as $key => $value) :
                $_tmp = str_replace("%$key%", $post_arr[$key], $_tmp);
            endforeach; $_finalContent .= do_shortcode($_tmp);
        endwhile;
        // pagination
        global $wp;
        
        if ($posts_per_page != -1) {
            $_finalContent .= '<div class="pagination">';
            $_finalContent .= paginate_links(array(
                'base' => ($pagination_var) ? str_replace(999999999, '%#%', esc_url(home_url( $wp->request )."?$pagination_var=999999999")) : str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'total' => $postslist->max_num_pages,
                'current' => max(1, $paged),
                'format' => '?page=%#%',
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
            $_finalContent .= '</div>';
        } //end if
        echo $_finalContent;
        // use this if you prefer previous and next navigation only
        // next_posts_link( 'Older Entries', $postslist->max_num_pages );
        // previous_posts_link( 'Next Entries &raquo;' ); 
        wp_reset_postdata();
        return ob_get_clean();
    }else{
        return "Not Found";
    }
});