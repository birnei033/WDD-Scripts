<?php
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
}