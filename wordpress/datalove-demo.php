<?php
/**
 * Plugin Name: Data Love AI Demo Embed
 * Plugin URI:  https://github.com/data-love-co/product-demo
 * Description: Embeds the Data Love AI guided product demo via the [datalove_demo] shortcode. Usage: [datalove_demo vertical="housing" tour="auto"]
 * Version:     1.0.0
 * Author:      Data Love Co
 * Author URI:  https://dataloveco.com
 * License:     GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * [datalove_demo] shortcode.
 *
 * Attributes:
 *   vertical   - hunger | housing | economic | care | veterans (default: hunger)
 *   tour       - "auto" to auto-start the guided tour (default: off)
 *   min_height - minimum iframe height in px (default: 640)
 *
 * Examples:
 *   [datalove_demo]
 *   [datalove_demo vertical="housing" tour="auto"]
 *   [datalove_demo vertical="veterans" min_height="720"]
 */
function datalove_demo_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'vertical'   => 'hunger',
			'tour'       => '',
			'min_height' => '640',
		),
		$atts,
		'datalove_demo'
	);

	$allowed_verticals = array( 'hunger', 'housing', 'economic', 'care', 'veterans' );
	$vertical          = strtolower( trim( $atts['vertical'] ) );
	if ( ! in_array( $vertical, $allowed_verticals, true ) ) {
		$vertical = 'hunger';
	}

	$args = array(
		'vertical' => $vertical,
		'embed'    => '1',
	);
	if ( 'auto' === strtolower( trim( $atts['tour'] ) ) ) {
		$args['tour'] = 'auto';
	}

	$src        = add_query_arg( $args, 'https://data-love-co.github.io/product-demo/' );
	$min_height = max( 320, (int) $atts['min_height'] );

	return sprintf(
		'<div class="datalove-demo-embed" style="position:relative;width:100%%;aspect-ratio:16/9;min-height:%1$dpx;border-radius:12px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.12);">'
		. '<iframe src="%2$s" title="Data Love AI — guided product demo" loading="lazy" allowfullscreen style="position:absolute;inset:0;width:100%%;height:100%%;border:0;"></iframe>'
		. '</div>'
		. '<p style="margin-top:8px;font-size:14px;"><a href="%2$s" target="_blank" rel="noopener">Open the full demo &#8599;</a></p>',
		$min_height,
		esc_url( $src )
	);
}
add_shortcode( 'datalove_demo', 'datalove_demo_shortcode' );
