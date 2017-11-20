<?php
/**
 * post_content
 * settings
 *
 */

include_once PLUGIN_DIR_PATH.'/trm_wp_functions/post_content/post_content_shortcode.php';
add_shortcode('post-content','trm_post_content_shortcode');

//add_action( 'wp_enqueue_scripts', 'trm_wp_functions_enqueue' );
function trm_functions_enqueue() {
	// trm_gallery styles
	//wp_enqueue_style( 'trm_gallery', PLUGIN_DIR_URL.'trm/trm_gallery/css/ajax-load-more.css', array(), '20160816' );
}
