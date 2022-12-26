<?php
/**
 * This class contains the logic for the plugin hooks.
 */
class Wwu_Plugin_Hooks {

	/**
	 * Plugin activation.
	 */
	public static function activate() {

		/**
		 * --------------------------
		 * Initialize plugin options.
		 * --------------------------
		 */

		// The call to action content.
		if ( false === get_option( 'wwu_cta' ) ) {
			add_option( 'wwu_cta', WWU_CTA, '', 'no' );
		}

		// The number of paragraph after which the CTA must be added.
		if ( false === get_option( 'wwu_paragraphs_number' ) ) {
			add_option( 'wwu_paragraphs_number', WWU_PARAGRAPHS_NUMBER, '', 'no' );
		}

		// The tag that the post must have.
		if ( false === get_option( 'wwu_post_tag' ) ) {
			add_option( 'wwu_post_tag', WWU_POST_TAG, '', 'no' );
		}

		// If the plugin data must be removed on plugin uninstall.
		if ( false === get_option( 'wwu_delete_data_on_uninstall' ) ) {
			add_option( 'wwu_delete_data_on_uninstall', 0, '', 'no' );
		}

	}

	/**
	 * Plugin deactivation.
	 */
	public static function deactivate() {

	}

}
