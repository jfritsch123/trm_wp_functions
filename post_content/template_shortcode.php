<?php
/**
 * kinderschutz.
 * joefritsch
 * 2019-05-11
 */

function trm_template_shortcode($atts) {
	$args = shortcode_atts( array(
		'template' => 'template.php',
		'template-dir' => 'shortcode-templates/'
	), $atts, 'template-content' );

	ob_start();
	include get_stylesheet_directory_uri().'/'.$atts['template-dir'].$atts['template'];
	$contents = ob_get_contents();
	ob_end_clean();
	return $contents;
}
