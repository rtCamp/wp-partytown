<?php
/**
 * Counter in Web Worker Example
 *
 * @package partytown\examples
 */

namespace partytown\examples;

/**
 * Add counter button in footer
 */
function js_counter() {
	echo '<p id="counter-partytown">1</p>';
	echo '<button id="partytown-button" class="partytown-button">PartyTown</button>';
}
add_action( 'wp_footer', __NAMESPACE__ . '\js_counter' );

/**
 * Enqueue Script
 */
function enqueue_non_critical_js() {
	wp_enqueue_script(
		'partytown-non-critical',
		plugin_dir_url( __FILE__ ) . 'non-critical.js',
		array( 'partytown' ),
		'1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_non_critical_js' );
