<?php
/**
 * WP PartyTown
 *
 * @package partytown
 * @subpackage Performance
 * @license GPL v2 or later
 *
 * @wordpress-plugin
 * Plugin Name:       WP PartyTown
 * Plugin URI:        https://github.com/rtCamp/wp-partytown
 * Description:       Add partytown support to your WordPress site.
 * Version:           1.0.0
 * Requires at least: 5.1
 * Requires PHP:      5.2
 * Author:            rtCamp, thelovekesh
 * Author URI:        https://rtcamp.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://github.com/rtCamp/wp-partytown
 * Text Domain:       partytown
 * Domain Path:       /languages
 */

namespace Partytown;

/**
 * PartyTown Configuration
 *
 * @since 1.0.0
 * @see https://partytown.builder.io/configuration
 * @return void
 */
function partytown_configuration() {
	// If partytown is not enabled, return early.
	if ( ! get_option( 'partytown' ) ) {
		return;
	}

	$config = array(
		'lib' => str_replace( site_url(), '', plugin_dir_url( __FILE__ ) ) . 'includes/js/partytown/',
	);

	/**
	 * Add configuration for PartyTown.
	 *
	 * @since 1.0.0
	 * @param array $config Configuration for PartyTown.
	 * @return array
	 */
	$config = apply_filters( 'partytown_configuration', $config );

	?>
	<script>
		window.partytown = <?php echo wp_json_encode( $config ); ?>;
	</script>
	<?php
}

add_action( 'wp_head', __NAMESPACE__ . '\partytown_configuration', 1 );

/**
 * Initialize PartyTown
 *
 * @since 1.0.0
 * @return void
 */
function partytown_init() {
	// If user has enabled PartyTown, only then load the script.
	if ( get_option( 'partytown' ) ) {
		wp_enqueue_script(
			'partytown',
			plugin_dir_url( __FILE__ ) . 'includes/js/partytown/partytown.js',
			array(),
			'1.0.0',
			false
		);
	}
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\partytown_init', 1 );

/**
 * Get all scripts tags which has `partytown` dependency.
 *
 * @since 1.0.0
 * @return void
 */
function partytown_worker_scripts() {
	global $wp_scripts;

	$partytown_handles = array();

	// Get all scripts which has `partytown` dependency.
	foreach ( $wp_scripts->registered as $handle => $script ) {
		if ( ! empty( $script->deps ) && in_array( 'partytown', $script->deps ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$partytown_handles[] = $handle;
		}
	}

	foreach ( $partytown_handles as $partytown_handle ) {
		add_filter(
			'script_loader_tag',
			/**
			 * Add type="text/partytown" to script tag.
			 *
			 * @since 1.0.0
			 * @param string $tag Script tag.
			 * @param string $handle Script handle.
			 * @param string $src Script source.
			 * @param string $partytown_handle Script handle which have `partytown` dependency.
			 *
			 * @return string $tag Script tag with type="text/partytown".
			 */
			function( $tag, $handle, $src ) use ( $partytown_handle ) {
				if ( $handle === $partytown_handle ) {
					if ( strpos( $tag, 'type="text/javascript"' ) !== false ) {
						$tag = str_replace( 'type="text/javascript"', 'type="text/partytown"', $tag );
					} else {
						$tag = str_replace( '<script', '<script type="text/partytown"', $tag );
					}
				}
				return $tag;
			},
			10,
			3
		);
	}
}

add_action( 'wp_print_scripts', __NAMESPACE__ . '\partytown_worker_scripts' );

require_once plugin_dir_path( __FILE__ ) . 'includes/php/options-partytown.php';

// require examples.
// require_once plugin_dir_path( __FILE__ ) . 'examples/counter.php';
// require_once plugin_dir_path( __FILE__ ) . 'examples/get-request.php';
// require_once plugin_dir_path( __FILE__ ) . 'examples/post-request.php';
