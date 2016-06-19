<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Boards_And_Committees
 * @subpackage Boards_And_Committees/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Boards_And_Committees
 * @subpackage Boards_And_Committees/admin
 * @author     Paul Lindquist <paul.lindquist@gmail.com>
 */
class Boards_And_Committees_Admin {

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
     * @param      string    $boards_and_committees       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $boards_and_committees, $version ) {

        $this->boards_and_committees = $boards_and_committees;
        $this->version = $version;

    }
    /**
     *	Displays admin page
     *
     * @since    1.0.1
     */
    public function output_content() {
        echo '<h2>here\'s some output<h2>';
    }


    /**
     * Adds the admin menu item
     *
     * @since    1.0.1
     */
    public function add_menu_page() {
        add_menu_page( 'Boards and Committees', 'Boards and Committees', 'edit_posts', 'boards_and_committees', array($this, 'output_content'), 'dashicons-id', 7  );
    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style( $this->boards_and_committees, plugin_dir_url( __FILE__ ) . 'css/boards-and-committees-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_script( $this->boards_and_committees, plugin_dir_url( __FILE__ ) . 'js/boards-and-committees-admin.js', array( 'jquery' ), $this->version, false );

    }

}
