<?php
return "test";
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