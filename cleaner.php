Skip to content
Why GitHub? 
Enterprise
Explore 
Marketplace
Pricing 
Search

Sign in
Sign up
2 0 0 jcmrs/mk-blue
 Code  Issues 0  Pull requests 0  Projects 0  Insights
Join GitHub today
GitHub is home to over 31 million developers working together to host and review code, manage projects, and build software together.

mk-blue/assets/helpers/cleaner.php
@jcmrs jcmrs Added shared.php helper for structures across pages.
8059746 on Jun 6, 2016
97 lines (84 sloc)  3.42 KB
    
<?php
// Clean up wp_head
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
// remove_action(‘wp_head’, ‘rel_canonical’, 10, 0 );
// Yoast JSON-LD
add_filter('disable_wpseo_json_ld_search', '__return_true'); 
// Remove editor filters
remove_filter('term_description','wpautop');
remove_filter('the_content', 'wptexturize');
remove_filter('the_excerpt', 'wptexturize');
remove_filter('comment_text', 'wptexturize');
remove_filter('the_title', 'wptexturize');
// Unregister unnecessary widgets
// Source code credit: http://ottopress.com/
add_action('widgets_init', 'mk_unregister_default_widgets', 11);
function mk_unregister_default_widgets() {
  unregister_widget('WP_Widget_Pages');
  unregister_widget('WP_Widget_Calendar');
  unregister_widget('WP_Widget_Archives');
  unregister_widget('WP_Widget_Links');
  unregister_widget('WP_Widget_Meta');
  unregister_widget('WP_Widget_Recent_Comments');
  unregister_widget('WP_Widget_RSS');
  unregister_widget('WP_Widget_Tag_Cloud');
  // unregister_widget('Akismet_Widget');
}
// Enable svg support
add_filter('upload_mimes', 'mk_svg_mime_type');
function mk_svg_mime_type($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
// Remove emojis
add_action( 'init', 'mk_disable_wp_emojicons' );
function mk_disable_wp_emojicons() {
  // All actions related to emojis
  // Source code credit: http://ottopress.com/
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  
  // Filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
// remove editor filters
remove_filter ('the_content', 'wpautop');
remove_filter( 'the_excerpt', 'wpautop' );
remove_filter('term_description','wpautop');
remove_filter('the_content', 'wptexturize');
remove_filter('the_excerpt', 'wptexturize');
remove_filter('comment_text', 'wptexturize');
remove_filter('the_title', 'wptexturize');
// Disable emoji drama in the editor
function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}
// Remove type attribute from scripts tag
add_filter( 'script_loader_tag', 'mk_remove_script_type_attribute' );
function mk_remove_script_type_attribute( $script ) {
  return str_replace( "type='text/javascript' ", '', $script );
}
// Remove type attribute from style tag
add_filter( 'style_loader_tag', 'mk_remove_style_type_attribute' );
function mk_remove_style_type_attribute( $style ) {
  $style = preg_replace( "# id='.*?' #", '', $style );
  return str_replace( " type='text/css'", '', $style );
}
