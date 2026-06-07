<?php
/**
 * Plugin Name: Data Love AI Demo Embed
 * Plugin URI:  https://github.com/data-love-co/product-demo
 * Description: Embeds the Data Love AI guided product demo via the [datalove_demo] shortcode. Default shows the focus-area picker; use vertical="housing" to boot straight into one scenario.
 * Version:     1.1.0
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
 *   vertical   - hunger | housing | economic | care | veterans
 *                Omit (default) to show the focus-area picker so visitors
 *                choose their own scenario; set to boot straight into one.
 *   tour       - "auto" to auto-start the guided tour (only applies when
 *                vertical is set — the tour starts on the dashboard, so it
 *                would skip the picker)
 *   min_height - minimum iframe height in px (default: 700 for the picker,
 *                640 when booting into a scenario; floor 320)
 *
 * Examples:
 *   [datalove_demo]                               ← focus-area picker (default)
 *   [datalove_demo vertical="housing" tour="auto"]
 *   [datalove_demo vertical="veterans" min_height="720"]
 */
function datalove_demo_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'vertical'   => '',
			'tour'       => '',
			'min_height' => '',
		),
		$atts,
		'datalove_demo'
	);

	$allowed_verticals = array( 'hunger', 'housing', 'economic', 'care', 'veterans' );
	$vertical          = strtolower( trim( $atts['vertical'] ) );
	$direct            = in_array( $vertical, $allowed_verticals, true );

	$args = array();
	if ( $direct ) {
		// Boot straight into the chosen scenario's dashboard.
		$args['vertical'] = $vertical;
		$args['embed']    = '1';
		if ( 'auto' === strtolower( trim( $atts['tour'] ) ) ) {
			$args['tour'] = 'auto';
		}
	}
	// No args at all → the demo shows its focus-area picker landing page.

	$src = add_query_arg( $args, 'https://data-love-co.github.io/product-demo/' );

	$default_height = $direct ? 640 : 700; // picker card needs a little more room
	$min_height     = '' === trim( $atts['min_height'] ) ? $default_height : (int) $atts['min_height'];
	$min_height     = max( 320, $min_height );

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
