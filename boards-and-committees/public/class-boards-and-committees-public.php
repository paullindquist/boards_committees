<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Boards_And_Committees
 * @subpackage Boards_And_Committees/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Boards_And_Committees
 * @subpackage Boards_And_Committees/public
 * @author     Paul Lindquist <paul.lindquist@gmail.com>
 */
class Boards_And_Committees_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $boards_and_committees    The ID of this plugin.
	 */
	private $boards_and_committees;

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
	 * @param      string    $boards_and_committees       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $boards_and_committees, $version ) {

		$this->boards_and_committees = $boards_and_committees;
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
		 * defined in Boards_And_Committees_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Boards_And_Committees_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->boards_and_committees, plugin_dir_url( __FILE__ ) . 'css/boards-and-committees-public.css', array(), $this->version, 'all' );

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
		 * defined in Boards_And_Committees_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Boards_And_Committees_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->boards_and_committees, plugin_dir_url( __FILE__ ) . 'js/boards-and-committees-public.js', array( 'jquery' ), $this->version, false );

	}

}
