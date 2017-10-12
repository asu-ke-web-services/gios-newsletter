<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.example.com/walt
 * @since      1.0.0
 *
 * @package    Gios_Newsletter
 * @subpackage Gios_Newsletter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Gios_Newsletter
 * @subpackage Gios_Newsletter/public
 * @author     Walter McConnell <wmcconne@asu.edu>
 */
class Gios_Newsletter_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gios_Newsletter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gios_Newsletter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/gios-newsletter-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gios_Newsletter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gios_Newsletter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gios-newsletter-public.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Register Shortcodes
	 *
	 * @since 	1.0.0
	 *
	 * Place all calls to 'add_shortcode' here, and create their callback functions inside this
	 * same file, in the section below.
	 */
	public function register_shortcodes() {
		add_shortcode( 'featured_post', array( $this, 'featured_post_shortcode' ) );
		add_shortcode( 'section_header', array( $this, 'section_header_shortcode' ) );
		add_shortcode( 'category_section', array( $this, 'category_section_shortcode' ) );
		add_shortcode( 'sidebar_menu', array( $this, 'sidebar_menu_shortcode' ) );
	}

	/**
	 *
	 * Shortcode callback functions.
	 *
	 * More complicated callback functions could make this file very long and hard to manage.
	 * To avoid that, I've decided to use the following pattern:
	 *
	 * 1. Move the working code and HTML to a partial, where we can mix PHP and HTML in a cleaner
	 * fashion than we could inside these functions.
	 *
	 * 2. In these callback functions, start output buffering and include our partial, which
	 * can then just output what it needs to
	 *
	 * 3. return the resulting HTML from the output buffer (shortcode methods must RETURN, not ECHO
	 * their results)
	 *
	 * Feel free to ignore this pattern for shorter/simpler functions :)
	 */
	public function featured_post_shortcode() {
		ob_start();
		include( 'partials/short-codes/featured-post.php' );
		return ob_get_clean();
	}

	public function section_header_shortcode( $atts ) {
		ob_start();
		include( 'partials/short-codes/section-header.php' );
		return ob_get_clean();
	}

	public function category_section_shortcode( $atts ) {
		ob_start();
		include( 'partials/short-codes/category-section.php' );
		return ob_get_clean();
	}

	public function sidebar_menu_shortcode() {
		ob_start();
		include( 'partials/short-codes/sidebar-menu.php' );
		return ob_get_clean();
	}
}
