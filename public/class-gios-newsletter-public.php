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

/**
 * Use our LightnCandy namespace to avoid repetitive typing
 */

use LightnCandy\LightnCandy;
use TemplateManager\TemplateManager;


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
	 * For holding an instance of our TemplateManager class
	 *
	 * @since 	1.0.0
	 * @access 	private
	 * @var 	object 		$templateManager 	An instance of the TemplateManager class
	 */
	 private $templateManager;

	 /**
	  * The path to our templates. This is passed in to the TemplateManager
	  */
	 private $templatePath;

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

		// create an instance of our TemplateManager class, giving it the path to our templates
		$this->templatePath = plugin_dir_path( __FILE__ ) . 'partials/short-codes/';
		$this->templateManager = new TemplateManager( $this->templatePath );
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
	 */
	public function featured_post_shortcode( $atts ) {

		$data = shortcode_atts( array(
			'title' => '(default title)',
			'subtitle' => '(default subtitle)'
			), $atts );


		$template = $this->templateManager->getTemplate( 'featured-post' );
		$compiled = LightnCandy::compile( $template );
		$renderer = LightnCandy::prepare( $compiled );
		return $renderer( $data );
	}

	public function section_header_shortcode( $atts ) {
		$data = shortcode_atts( array(
			'title' => '(default title)'
			), $atts );


		$template = $this->templateManager->getTemplate( 'section-header' );
		$compiled = LightnCandy::compile( $template );
		$renderer = LightnCandy::prepare( $compiled );
		return $renderer( $data );
	}

	public function category_section_shortcode( $atts ) {

		// first, normalize our shortcode attributes, providing defaults for anything missing
		$a = shortcode_atts( array(
			'category_name' => '',
			'order_by' => 'title'
			),
			$atts
		);

		// create an array of query parameters
		$args = array( 'category_name' => $a['category_name'] );

		// sort posts based on the 'order_by' attribute. The 'shortcode_atts()' method above will sort by title
		// if no 'order_by' attribute is present, so we can rely on there being something there
		switch ( $a['order_by'] ) {
			case 'date':
			$args['orderby'] = 'date';
			$args['order'] = 'ASC';
			break;
		case 'title':
			// sorting by title is the default. Falling through.
		default:
			$args['orderby'] = 'title';
			$args['order'] = 'ASC';
			break;
		}

		// create a new query to get posts in our category, sorted the way we want
		$category_posts = new WP_Query( $args );

		// the famous WordPress Loop!

		if( $category_posts->have_posts() ) {

			/**
			* Before we actually begin looping, we'll grab our template, compile it, and the prep it for
			* using our data. That way we don't incur this overhead on every post in a category
			*
			* Note: to avoid having LightnCandy translate HTML tags into entities, we are passing the
			* NOESCAPE flag when we compile the template.
			*/
			$template = $this->templateManager->getTemplate( 'one-post' );
			$compiled = LightnCandy::compile( $template, array(
				'flags' => LightnCandy::FLAG_NOESCAPE
				));
			$renderer = LightnCandy::prepare( $compiled );

			// we want to capture the output for returning
			ob_start();

			while( $category_posts->have_posts() ) {
				/**
				* Now we're actually looping through posts. For each post, make a data array from the
				* items we want to display.
				*
				* Note: in order to supress actual output, we are using some alternate 'get_' methods
				* here. The 'get_the_content()' method may be troublesome, as it DOES NOT apply any
				* WordPress filters to the content. If you're not seeing expected behavior in post
				* content, this may be why.
				*/
				$category_posts->the_post();

				$data = array(
				'title' => the_title( '', '', false ),
				'content' => get_the_content(),
				'date' => get_the_date(),
				'time' => get_the_time()
				);

				// echo our completed post output to the buffer
				echo $renderer( $data );
			}

			// return our final output
			return ob_get_clean();

		}else{

			return '<p>There are no posts for this category.</p>';

		}
	}
	
	public function sidebar_menu_shortcode() {
		ob_start();
		include( 'partials/short-codes/sidebar-menu.php' );
		return ob_get_clean();
	}
}