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
// Unregister unnecessary widgets
add_action('widgets_init', 'mk_unregister_default_widgets', 11);
function mk_unregister_default_widgets() {
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

// remove wp version param from any enqueued scripts
function vc_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
// END remove wp version param from any enqueued scripts

// remove wp version
function my_remove_version_info() {
     return '';
}
add_filter('the_generator', 'my_remove_version_info');
// END remove wp version

// disable self pingback
function wpsites_disable_self_pingbacks( &$links ) {
  foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, get_option( 'home' ) ) )
            unset($links[$l]);
}

add_action( 'pre_ping', 'wpsites_disable_self_pingbacks' );
// END disable self pingback


// Remove EMOJIS
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );     
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link'); //removes EditURI/RSD (Really Simple Discovery) link.
remove_action('wp_head', 'wlwmanifest_link'); //removes wlwmanifest (Windows Live Writer) link.
remove_action('wp_head', 'wp_generator'); //removes meta name generator.
remove_action('wp_head', 'wp_shortlink_wp_head'); //removes shortlink.
remove_action( 'wp_head', 'feed_links', 2 ); //removes feed links.
remove_action('wp_head', 'feed_links_extra', 3 );  //removes comments feed. 
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
} add_action( 'init', 'disable_emojis' );


function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

// // Remove emoji CDN hostname from DNS prefetching hints.

function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
		// Strip out any URLs referencing the WordPress.org emoji location
		$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
		foreach ( $urls as $key => $url ) {
			if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
				unset( $urls[$key] );
			}
		}
	}
	return $urls;
}

// Remove WP ShortLinks
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
// END Remove WP ShortLinks


// Disable XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

// Disable X-Pingback to header
add_filter( 'wp_headers', 'disable_x_pingback' );
function disable_x_pingback( $headers ) {
    unset( $headers['X-Pingback'] );
return $headers;
}

// Disable all xml-rpc endpoints
add_filter('xmlrpc_methods', function () {
    return [];
}, PHP_INT_MAX);

// Disable WP-Embeds
function disable_wp_embeds() {
	
	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	
	// Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );
	
	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	
	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	
	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
	
	// Remove all embeds rewrite rules.
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	
	// Remove filter of the oEmbed result before any HTTP requests are made.
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
	// filter to remove TinyMCE emojis
  add_filter( 'emoji_svg_url', '__return_false' );

  // filter to remove the DNS prefetch
  add_filter( 'tiny_mce_plugins', 'disable_embeds' );
	
}
add_action( 'init', 'disable_wp_embeds', 9999 );
	
function disable_embeds_tiny_mce_plugin($plugins) {
	return array_diff($plugins, array('wpembed'));
}
	
function disable_embeds_rewrites($rules) {
	foreach($rules as $rule => $rewrite) {
		if(false !== strpos($rewrite, 'embed=true')) {
			unset($rules[$rule]);
		}
	}
	return $rules;
}
// END Disable WP-Embeds
