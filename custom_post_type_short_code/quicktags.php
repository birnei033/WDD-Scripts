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
}add_action('admin_print_footer_scripts', 'appthemes_add_quicktags');