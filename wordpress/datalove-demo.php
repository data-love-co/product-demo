<?php
/**
 * Plugin Name: Data Love AI Demo Embed
 * Plugin URI:  https://github.com/data-love-co/product-demo
 * Description: Embeds the Data Love AI guided product demo via the [datalove_demo] shortcode. Fits below your site header and adds an Expand button for a full-window view. Default shows the focus-area picker; use vertical="housing" to boot straight into one scenario.
 * Version:     1.3.0
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
 *   height      - CSS height for the embed (default: 100dvh — fills the
 *                 viewport so the page itself doesn't scroll). Accepts a
 *                 length with unit, e.g. 100dvh, 90vh, 800px.
 *   offset      - px to subtract from height for your theme's header, so the
 *                 embed fits below it exactly with no page scroll (default: 0).
 *   min_height  - minimum iframe height in px, a floor for very short windows
 *                 (default: 420; hard floor 320)
 *   expand      - "on" (default) shows an ⛶ Expand button that opens the demo
 *                 full-window; "off" hides it.
 *   contact_url - where the in-overlay "Contact us" button links
 *                 (default: https://dataloveco.com/contact)
 *
 * Examples:
 *   [datalove_demo offset="90"]                   ← fit below a 90px site header
 *   [datalove_demo vertical="housing" tour="auto"]
 *   [datalove_demo expand="off"]                  ← inline only, no expand button
 *   [datalove_demo contact_url="https://dataloveco.com/book"]
 */
function datalove_demo_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'vertical'    => '',
			'tour'        => '',
			'height'      => '',
			'offset'      => '',
			'min_height'  => '',
			'expand'      => 'on',
			'contact_url' => 'https://dataloveco.com/contact',
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

	// Height: fill the viewport by default so the page itself doesn't scroll.
	// Sanitise to a CSS length + known unit; fall back to the default if odd.
	$height = trim( $atts['height'] );
	if ( '' === $height || ! preg_match( '/^[0-9]+(\.[0-9]+)?(px|vh|dvh|svh|lvh|%|rem|em)$/', $height ) ) {
		$height = '100dvh';
	}

	// Offset: px to carve out for the site header so the fit is exact.
	$offset = '' === trim( $atts['offset'] ) ? 0 : max( 0, (int) $atts['offset'] );
	$css_height = $offset > 0 ? sprintf( 'calc(%s - %dpx)', $height, $offset ) : $height;

	// Floor for very short windows. Kept low so a short window shrinks the
	// embed instead of forcing the page to scroll.
	$min_height = '' === trim( $atts['min_height'] ) ? 420 : (int) $atts['min_height'];
	$min_height = max( 320, $min_height );

	$show_expand = 'off' !== strtolower( trim( $atts['expand'] ) );
	$contact_url = esc_url( $atts['contact_url'] );

	// Print the CSS + JS once per page, no matter how many shortcodes appear.
	static $assets_done = false;
	$assets = '';
	if ( ! $assets_done ) {
		$assets_done = true;
		$assets = datalove_demo_assets();
	}

	static $instance = 0;
	$instance++;

	$expand_btn = $show_expand
		? '<button type="button" class="dlc-expand" aria-label="Expand demo to full screen">&#9974; Expand</button>'
		: '';

	$overlay_bar = $show_expand
		? sprintf(
			'<div class="dlc-bar">'
			. '<button type="button" class="dlc-exit" aria-label="Exit full screen">&#10005; Exit demo</button>'
			. '<a class="dlc-contact" href="%s">Contact us &#8594;</a>'
			. '</div>',
			$contact_url
		)
		: '';

	return $assets . sprintf(
		'<div class="dlc-demo" id="dlc-demo-%1$d" style="--dlc-h:%2$s;--dlc-min:%3$dpx;">'
		. '%4$s'
		. '<div class="dlc-frame"><iframe src="%5$s" title="Data Love AI &mdash; guided product demo" loading="lazy" allowfullscreen></iframe></div>'
		. '%6$s'
		. '</div>'
		. '<p class="dlc-fallback" style="margin-top:8px;font-size:14px;"><a href="%5$s" target="_blank" rel="noopener">Open the full demo &#8599;</a></p>',
		$instance,
		esc_attr( $css_height ),
		$min_height,
		$overlay_bar,
		esc_url( $src ),
		$expand_btn
	);
}

/**
 * The CSS + JS that power the fit-to-viewport sizing and the expand overlay.
 * Printed once per page (see the static guard in the shortcode).
 */
function datalove_demo_assets() {
	$css = '
.dlc-demo{position:relative;width:100%;height:var(--dlc-h,100dvh);min-height:var(--dlc-min,420px);border-radius:12px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.12);background:#F6F3FB;}
.dlc-demo .dlc-frame{position:absolute;inset:0;}
.dlc-demo .dlc-frame iframe{width:100%;height:100%;border:0;display:block;}
.dlc-demo .dlc-expand{position:absolute;bottom:14px;right:14px;z-index:5;display:inline-flex;align-items:center;gap:6px;background:rgba(61,29,114,0.92);color:#fff;border:0;border-radius:18px;padding:8px 14px;font-family:inherit;font-size:13px;font-weight:600;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.2);}
.dlc-demo .dlc-expand:hover{background:#3D1D72;}
.dlc-demo .dlc-bar{position:absolute;top:0;left:0;right:0;height:48px;z-index:6;display:none;align-items:center;justify-content:space-between;padding:0 14px;background:#3D1D72;}
.dlc-demo .dlc-bar .dlc-exit,.dlc-demo .dlc-bar .dlc-contact{font-family:inherit;font-size:14px;font-weight:600;line-height:1;cursor:pointer;border-radius:18px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;}
.dlc-demo .dlc-bar .dlc-exit{background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.5);padding:7px 14px;}
.dlc-demo .dlc-bar .dlc-exit:hover{background:rgba(255,255,255,0.14);}
.dlc-demo .dlc-bar .dlc-contact{background:#3BBFAD;color:#fff;border:0;padding:8px 16px;}
.dlc-demo .dlc-bar .dlc-contact:hover{background:#2DA396;}
.dlc-demo.is-expanded{position:fixed;inset:0;width:100vw;height:100dvh;min-height:0;border-radius:0;z-index:2147483000;}
.dlc-demo.is-expanded .dlc-bar{display:flex;}
.dlc-demo.is-expanded .dlc-frame{top:48px;}
.dlc-demo.is-expanded .dlc-expand{display:none;}
html.dlc-demo-lock,html.dlc-demo-lock body{overflow:hidden!important;}
';

	$js = '
(function(){
  function init(el){
    if(el.getAttribute("data-dlc-ready"))return;
    el.setAttribute("data-dlc-ready","1");
    var expand=el.querySelector(".dlc-expand"),exit=el.querySelector(".dlc-exit");
    function open(){el.classList.add("is-expanded");document.documentElement.classList.add("dlc-demo-lock");if(exit)exit.focus();}
    function close(){el.classList.remove("is-expanded");document.documentElement.classList.remove("dlc-demo-lock");if(expand)expand.focus();}
    if(expand)expand.addEventListener("click",open);
    if(exit)exit.addEventListener("click",close);
    document.addEventListener("keydown",function(e){if(e.key==="Escape"&&el.classList.contains("is-expanded"))close();});
  }
  function boot(){var l=document.querySelectorAll(".dlc-demo");for(var i=0;i<l.length;i++)init(l[i]);}
  if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",boot);}else{boot();}
})();
';

	return '<style>' . $css . '</style>' . "\n" . '<script>' . $js . '</script>' . "\n";
}
add_shortcode( 'datalove_demo', 'datalove_demo_shortcode' );
