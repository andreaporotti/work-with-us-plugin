<?php
/**
 * This class contains the main plugin logic.
 */
class Wwu {

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
	 * The main plugins method.
	 */
	public function run() {

		// Enqueue styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		// Add the CTA to post content.
		add_filter( 'the_content', array( $this, 'add_cta' ) );

		// Init plugin options page.
		$plugin_options = new Wwu_Plugin_Options();
		add_action( 'admin_menu', array( $plugin_options, 'options_menu' ) );
		add_action( 'admin_init', array( $plugin_options, 'options_init' ) );

	}

	/**
	 * Load plugin styles.
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug, plugin_dir_url( __FILE__ ) . '../css/cta.css', array(), $this->plugin_version, 'all' );

	}

	/**
	 * Add the CTA to post content.
	 */
	public function add_cta( $content ) {

		// Get options.
		$option_post_tag = get_option( 'wwu_post_tag' );

		// Execute the CTA logic only if current post is a post with the requested tag.
		if ( is_singular( 'post' ) && has_tag( $option_post_tag ) ) {

			// Get options.
			$option_cta               = get_option( 'wwu_cta' );
			$option_paragraphs_number = get_option( 'wwu_paragraphs_number' );

			// Create an array containing all the HTML elements found in the post content.
			// NOTE: DOMDocument will create a full HTML structure since the content is not a complete HTML document.
			$dom        = new DOMDocument();
			$elements   = array();
			$paragraphs = 0;

			libxml_use_internal_errors( true );
			$dom->loadHTML( $content );
			libxml_use_internal_errors( false );

			foreach ( $dom->getElementsByTagName( 'body' )->item( 0 )->childNodes as $element ) {
				// Add the element skipping empty lines.
				if ( $element->nodeType === XML_ELEMENT_NODE ) {
					$elements[] = $dom->saveHTML( $element );

					// If current element is a paragraph, add it to the paragraphs conuter.
					if ( $element->nodeName === 'p' && $paragraphs < $option_paragraphs_number ) {
						$paragraphs++;

						// If we reached the number of paragraphs, add the CTA to the content.
						if ( $paragraphs == $option_paragraphs_number ) {
							$elements[] = $option_cta;
						}
					}
				}
			}

			// Create the new post content from the array of HTML elements.
			$new_content = implode( $elements );

			return $new_content;

		}

		return $content;

	}

}
