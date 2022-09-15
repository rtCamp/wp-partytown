<?php
/**
 * Add `enable partytown` to the settings page.
 *
 * @package partytown
 * @subpackage Settings
 */

namespace Partytown;

/**
 * Add settings page.
 *
 * @since 1.0.0
 * @return void
 */
function partytown_settings_page() {
	add_options_page(
		'PartyTown',
		'PartyTown',
		'manage_options',
		'partytown',
		__NAMESPACE__ . '\partytown_render_settings_page'
	);
}

add_action( 'admin_menu', __NAMESPACE__ . '\partytown_settings_page' );

/**
 * Render settings page.
 *
 * @since 1.0.0
 * @return void
 */
function partytown_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
	<div class="wrap">
		<h1>PartyTown</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'partytown' ); ?>
			<?php do_settings_sections( 'partytown' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Register settings for PartyTown.
 *
 * @since 1.0.0
 * @return void
 */
function partytown_settings_init() {
	register_setting(
		'partytown',
		'partytown',
		__NAMESPACE__ . '\partytown_sanitize_settings'
	);

	add_settings_section(
		'partytown_settings_section',
		'',
		__NAMESPACE__ . '\partytown_settings_section_callback',
		'partytown'
	);

	add_settings_field(
		'partytown_enable',
		'Enable PartyTown',
		__NAMESPACE__ . '\partytown_enable_callback',
		'partytown',
		'partytown_settings_section'
	);
}

add_action( 'admin_init', __NAMESPACE__ . '\partytown_settings_init' );

/**
 * Render settings section.
 *
 * @since 1.0.0
 * @return void
 */
function partytown_settings_section_callback() {
	echo wp_kses_post(
		sprintf(
			'<p>%s</p>',
			esc_html__( 'Enable PartyTown to execute scripts in Worker threads.', 'partytown' )
		)
	);
}

/**
 * Render settings field.
 *
 * @since 1.0.0
 * @return void
 */
function partytown_enable_callback() {
	$options = get_option( 'partytown', 'on' );
	$checked = ( isset( $options ) && 'on' === $options ) ? 'checked' : '';
	?>
	<label>
		<input type="checkbox" name="partytown" <?php echo esc_attr( $checked ); ?> />
		<?php esc_html_e( 'Enable PartyTown', 'partytown' ); ?>
	</label>
	<?php
}
