<?php 
$list = '<div id="' . $root_id . '" class="et_pb_module et_pb_tabs ' . $root_class . '">';
$list_menu = '<ul class="et_pb_tabs_controls clearfix">';
$list_content = '<div class="et_pb_all_tabs">';

$index = 0;
$post_count = $query->post_count; 
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



