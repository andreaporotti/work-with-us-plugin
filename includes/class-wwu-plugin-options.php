<?php
/**
 * This class contains the logic to manage plugin options.
 */
class Wwu_Plugin_Options {

	/**
	 * The name of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The slug of this plugin.
	 */
	protected $plugin_slug;

	/**
	 * The current plugin version.
	 */
	protected $plugin_version;

	/**
	 * The slug of the options menu.
	 */
	private $options_slug;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {

		$this->plugin_name    = WWU_PLUGIN_NAME;
		$this->plugin_version = WWU_PLUGIN_VERSION;
		$this->plugin_slug    = WWU_PLUGIN_SLUG;
		$this->options_slug   = $this->plugin_slug . '_options';

	}

	/**
	 * Adds the plugin options page as sub-item in the Settings menu.
	 */
	public function options_menu() {

		add_options_page(
			sprintf(
				/* translators: %s is the plugin name */
				__( 'Impostazioni %s', 'wwu' ),
				$this->plugin_name
			),
			$this->plugin_name,
			'manage_options',
			$this->options_slug,
			array(
				$this,
				'options_page_cb',
			)
		);

	}

	/**
	 * Callback that shows the options page content.
	 */
	public function options_page_cb() {

		// Check user capabilities.
		if ( current_user_can( 'manage_options' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'options-page-html.php';
		}

	}

	/**
	 * Adds the options fields to the options page.
	 */
	public function options_init() {

		/**
		 * -------------
		 * CTA settings.
		 * -------------
		 */

		// Add a section.
		add_settings_section(
			'wwu_options_section_cta',
			esc_html__( 'Impostazioni CTA', 'wwu' ),
			array(
				$this,
				'options_section_cta_cb',
			),
			$this->options_slug
		);

		// Register a setting.
		register_setting(
			$this->options_slug,
			'wwu_cta',
			array(
				'type'              => 'string',
				'show_in_rest'      => false,
				'default'           => '',
				'sanitize_callback' => array(
					$this,
					'option_cta_sanitize_cb',
				),
			)
		);

		// Add setting field to the section.
		add_settings_field(
			'wwu_cta',
			esc_html__( 'Contenuto CTA', 'wwu' ),
			array(
				$this,
				'option_cta',
			),
			$this->options_slug,
			'wwu_options_section_cta',
			array(
				'name' => 'wwu_cta',
			)
		);

		// Register a setting.
		register_setting(
			$this->options_slug,
			'wwu_paragraphs_number',
			array(
				'type'              => 'integer',
				'show_in_rest'      => false,
				'default'           => WWU_PARAGRAPHS_NUMBER,
				'sanitize_callback' => array(
					$this,
					'option_paragraphs_number_sanitize_cb',
				),
			)
		);

		// Add setting field to the section.
		add_settings_field(
			'wwu_paragraphs_number',
			esc_html__( 'Numero di paragrafi', 'wwu' ),
			array(
				$this,
				'option_paragraphs_number',
			),
			$this->options_slug,
			'wwu_options_section_cta',
			array(
				'name' => 'wwu_paragraphs_number',
			)
		);

		// Register a setting.
		register_setting(
			$this->options_slug,
			'wwu_post_tag',
			array(
				'type'              => 'string',
				'show_in_rest'      => false,
				'default'           => WWU_POST_TAG,
				'sanitize_callback' => array(
					$this,
					'option_post_tag_sanitize_cb',
				),
			)
		);

		// Add setting field to the section.
		add_settings_field(
			'wwu_post_tag',
			esc_html__( 'Tag dell\'articolo', 'wwu' ),
			array(
				$this,
				'option_post_tag',
			),
			$this->options_slug,
			'wwu_options_section_cta',
			array(
				'name' => 'wwu_post_tag',
			)
		);

		/**
		 * -----------------
		 * General settings.
		 * -----------------
		 */

		// Add a section.
		add_settings_section(
			'wwu_options_section_general',
			esc_html__( 'Impostazioni generali', 'wwu' ),
			array(
				$this,
				'options_section_general_cb',
			),
			$this->options_slug
		);

		// Register a setting.
		register_setting(
			$this->options_slug,
			'wwu_delete_data_on_uninstall',
			array(
				'type'              => 'boolean',
				'show_in_rest'      => false,
				'default'           => 0,
				'sanitize_callback' => array(
					$this,
					'option_delete_data_on_uninstall_sanitize_cb',
				),
			)
		);

		// Add setting field to the section.
		add_settings_field(
			'wwu_delete_data_on_uninstall',
			esc_html__( 'Elimina i dati del plugin', 'wwu' ),
			array(
				$this,
				'option_delete_data_on_uninstall_cb',
			),
			$this->options_slug,
			'wwu_options_section_general',
			array(
				'label_for' => 'wwu_delete_data_on_uninstall',
			)
		);

	}

	/**
	 * ---------------------------
	 * General settings callbacks.
	 * ---------------------------
	 */

	/**
	 * Callback for the general options section output.
	 */
	public function options_section_general_cb( $args ) {

		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>">
			<?php echo esc_html__( 'Configura le impostazioni generali del plugin.', 'wwu' ); ?>
		</p>
		<?php

	}

	/**
	 * Callback for the delete_data_on_uninstall option value sanitization.
	 */
	public function option_delete_data_on_uninstall_sanitize_cb( $value ) {

		if ( '1' !== $value ) {
			return 0;
		}

		return $value;

	}

	/**
	 * Callback for the delete_data_on_uninstall option field output.
	 */
	public function option_delete_data_on_uninstall_cb( $args ) {

		// Get the option value.
		$option_delete_data_on_uninstall = get_option( $args['label_for'], 0 );

		?>
		<fieldset>
			<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" value="1" <?php checked( $option_delete_data_on_uninstall, 1 ); ?>>
			<label for="<?php echo esc_attr( $args['label_for'] ); ?>"><?php echo esc_html__( 'elimina', 'wwu' ); ?></label>
			<p class="description">
				<?php echo esc_html__( 'Attenzione: attivando questa opzione tutti i dati e le impostazioni del plugin saranno eliminati alla disinstallazione.', 'wwu' ); ?>
			</p>
		</fieldset>
		<?php

	}

	/**
	 * ---------------------------
	 * CTA settings callbacks.
	 * ---------------------------
	 */

	/**
	 * Callback for the cta options section output.
	 */
	public function options_section_cta_cb( $args ) {

		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>">
			<?php echo esc_html__( 'Configura le impostazioni della call to action.', 'wwu' ); ?>
		</p>
		<?php

	}

	/**
	 * Callback for the cta option sanitization.
	 */
	public function option_cta_sanitize_cb( $value ) {

		return $value;

	}

	/**
	 * Callback for the cta option field output.
	 */
	public function option_cta( $args ) {

		// Get the option value.
		$option_cta = get_option( $args['name'], '' );

		?>
		<fieldset>
			<textarea name="<?php echo esc_attr( $args['name'] ); ?>" class="regular-text" rows="8"><?php echo wp_kses_post( $option_cta ); ?></textarea>
			<p class="description">
				<?php echo esc_html__( 'Il codice HTML della call to action.', 'wwu' ); ?>
			</p>
		</fieldset>
		<?php

	}

	/**
	 * Callback for the paragraphs_number option sanitization.
	 */
	public function option_paragraphs_number_sanitize_cb( $value ) {

		// Set default value if option is empty.
		if ( empty( $value ) ) {
			$value = WWU_PARAGRAPHS_NUMBER;
		}

		return $value;

	}

	/**
	 * Callback for the paragraphs_number option field output.
	 */
	public function option_paragraphs_number( $args ) {

		// Get the option value.
		$option_paragraphs_number = get_option( $args['name'], '' );

		?>
		<fieldset>
			<input type="text" name="<?php echo esc_attr( $args['name'] ); ?>" class="regular-text" value="<?php echo esc_attr( $option_paragraphs_number ); ?>">
			<p class="description">
				<?php echo esc_html__( 'La call to action sarà visualizzata dopo il numero di paragrafi indicato.', 'wwu' ); ?>
			</p>
		</fieldset>
		<?php

	}

	/**
	 * Callback for the post_tag option sanitization.
	 */
	public function option_post_tag_sanitize_cb( $value ) {

		// Set default value if option is empty.
		if ( empty( $value ) ) {
			$value = WWU_POST_TAG;
		}

		return $value;

	}

	/**
	 * Callback for the post_tag option field output.
	 */
	public function option_post_tag( $args ) {

		// Get the option value.
		$option_post_tag = get_option( $args['name'], '' );

		?>
		<fieldset>
			<input type="text" name="<?php echo esc_attr( $args['name'] ); ?>" class="regular-text" value="<?php echo esc_attr( $option_post_tag ); ?>">
			<p class="description">
				<?php echo esc_html__( 'La call to action sarà visualizzata negli articoli che contengono il tag indicato.', 'wwu' ); ?>
			</p>
		</fieldset>
		<?php

	}

}
