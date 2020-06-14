<?php

/**
 * Plugin Name: Open Air Sport
 * Plugin URI: https://github.com/younes-dro
 * Description: Open air sport store 
 * Version: 1.0.0
 * Author: Younes DRO
 * Author URI: https://github.com/younes-dro
 * Text Domain: osp
 * Domain Path: /languages
 * 
 * WC requires at least: 3.7.0
 * WC tested up to: 3.7.0
 *
 * @author Younes DRO <younesdro@gmail.com>
 * @category Core
 * @package OSP
 *
 * Copyright: © 2019 Younes DRO
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
 

/*  Copyright 2020 Younes DRO (email : younesdro@gmail.com)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  custom
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once plugin_dir_path( __FILE__) . '/includes/class-wc-osp-dependencies.php';
require_once plugin_dir_path( __FILE__) . '/includes/class-wc-osp-frontend.php';

/**
 * WC_Custom_Variable_Products class.
 * 
 * The main instance of the plugin.
 * 
 * @since 1.0.0
 */
class WC_OSP{
    
    /** 
     * The Single instance of the class.
     * 
     * @var obj WC_OSP object
     */
    protected static $instance;
   
    /** 
     * Plugin Version.
     * 
     * @var String 
     */
    public $version = '1.0.0';
    
    /**
    * Plugin Name
    *
    * @var String 
    */
    public $plugin_name = 'Open Air Sport';
    /** 
     * Instance of the WC_OSP_Dependencies class.
     * 
     * Verify the requirements 
     * 
     * @var obj WC_OSP_Dependencies object  
     */
    protected static $dependencies;
    
    /** @var array the admin notices to add */
    protected $notices = array();
    
    /**
     * 
     * @param WC_OSP_Dependencies $dependencies
     */
    public function __construct( WC_OSP_Dependencies $dependencies) {
        
        self::$dependencies = $dependencies;
        
        register_activation_hook( __FILE__ , array( $this , 'activation_check' ) );
        
        add_action( 'admin_init', array( $this , 'check_environment' ));
        
        add_action( 'admin_init', array( $this, 'add_plugin_notices'));
        
        add_action('admin_notices', array( $this, 'admin_notices' ), 15 );
        
        add_action('plugins_loaded', array ( $this , 'init_plugin') );
               
          
    }    
    
    /**
     * Gets the main WC_OSP instance.
     * 
     * Ensures only one instance of WC_OSP is loaded or can be loaded.
     * 
     * @since 1.0.0
     * @return WC_OSP instance
     */
    public static function start( WC_OSP_Dependencies $dependencies ){
        if ( NULL === self::$instance){
            self::$instance = new self( $dependencies );
        }
        
        return self::$instance;
    }
    
    /**
     * Cloning is forbidden due to singleton pattern.
     * 
     * @since 1.0.0
     */
    public function __clone() {
        $cloning_message = sprintf( 
                esc_html__( 'You cannot clone instances of %s.', 'osp' ) ,
                get_class( $this )  
                );
        _doing_it_wrong( __FUNCTION__, $cloning_message, $this->version );
    }
    
    /**
     * Unserializing instances is forbidden due to singleton pattern.
     * 
     * @since 1.0.0
     */
    public function __wakeup() {
        $unserializing_message = sprintf( 
                esc_html__( 'You cannot clone instances of %s.', 'osp' ) ,
                get_class( $this )  
                );
                _doing_it_wrong( __FUNCTION__, $unserializing_message, $this->version );
    }
    
    /**
     * Checks the server environment and deactivates plugins as necessary.
     * 
     * @since 1.0.0
     */
    public  function activation_check() {

        if ( ! self::$dependencies->check_php_version() ){
            
            $this->deactivate_plugin();
            
            wp_die( $this->plugin_name . esc_html__(' could not be activated. ', 'osp' ) . self::$dependencies->get_php_notice() );
            
        }
    }
    
    /**
     * Checks the environment on loading WordPress, just in case the environment changes after activation.
     * 
     * @since 1.0.0
     */
    public function check_environment(){
        
        if ( ! self::$dependencies->check_php_version() && is_plugin_active( plugin_basename( __FILE__ ) ) ){
            
            $this->deactivate_plugin();
            $this->add_admin_notice( 
                    'bad_environment',
                    'error', 
                    $this->plugin_name . esc_html__( ' has been deactivated. ', 'osp' ) . self::$dependencies->get_php_notice() 
                    );
        }
    }
    
    /**
     * Deactivate the plugin
     * 
     * @since 1.0.0
     */
    protected function deactivate_plugin(){
        
        deactivate_plugins( plugin_basename( __FILE__ ));
        
        if ( isset( $_GET['activate'] ) ){
            unset( $_GET['activate'] );
        }
    }
    
	/**
	 * Adds an admin notice to be displayed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug message slug
	 * @param string $class CSS classes
	 * @param string $message notice message
	 */
	public function add_admin_notice( $slug, $class, $message ) {

		$this->notices[ $slug ] = array(
			'class'   => $class,
			'message' => $message
		);
	} 
        
        public function add_plugin_notices() {
            
            if ( ! self::$dependencies->check_wp_version() ){
                
                $this->add_admin_notice( 'update_wordpress', 'error', self::$dependencies->get_wp_notice() );
            }
            
            if( ! self::$dependencies->check_wc_version() ){
                
                 $this->add_admin_notice( 'update_woocommerce', 'error', self::$dependencies->get_wc_notice() );
            }
        }
        
	/**
	 * Displays any admin notices added with \WC_Custom_Variable_Products::add_admin_notice()
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {

		foreach ( (array) $this->notices as $notice_key => $notice ) {

			echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
			echo wp_kses( $notice['message'], array( 'a' => array( 'href' => array() ) ) );
			echo "</p></div>";
		}
	}        
    
        /**
         * Initializes the plugin.
         * 
         * @since 1.0.0
         */
        public function init_plugin() {
            
            if ( ! self::$dependencies->is_compatible() ){
                return;
            }
            // Load the front end template
//            add_action( 'after_setup_theme', array( $this , 'frontend_includes') );
            
            if( ! is_admin()  ){    
                new WC_OSP_Frontend();                
            }            
        }
        
	/**
	 * Include template functions and hooks.
	 */
        public function frontend_includes(){
//            require_once ('includes/wc-cvp-template-functions.php');
//            require_once ('includes/wc-cvp-template-hooks.php');
        }
        /*-----------------------------------------------------------------------------------*/
	/*  Helper Functions                                                                 */
	/*-----------------------------------------------------------------------------------*/
        
        /**
         * Get the plugin url.
         * 
         * @since 1.0.0
         * 
         * @return string
         */
        public function plugin_url(){
            return untrailingslashit( plugins_url( '/', __FILE__ ) );
        }
        
        /**
         * Get the plugin path.
         * 
         * @since 1.0.0
         * 
         * @return string
         */
        public function plugin_path(){
            return untrailingslashit( plugin_dir_path( __FILE__ ) );
        }
        
        /**
         * Get the plugin base path name.
         * 
         * @since 1.0.0
         * 
         * @return string
         */
        public function plugin_basename(){
            return plugin_basename( __FILE__ );
        }
        
}

/**
 * Returns the main instance of WC_OSP.
 */
function WC_OSP(){
    return WC_OSP::start( new WC_OSP_Dependencies() );
}

WC_OSP();

remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle', 10 );
function abChangeProductsTitle() {
    
    echo all_cat_classes(get_the_ID());
    
}
function get_parent_terms( $term ) {
        
    if ($term->parent > 0) {
        $term = get_term_by( "id", $term->parent, "product_cat" );
            if ( $term->parent > 0 ) {
               get_parent_terms( $term );
            } else return $term;
        }
    else return $term;
}
    
/**
     * Will return all categories of a product, including parent categories
     * 
     * @param object $product_id
     * @return array $cats
     */
function all_cat_classes( $product_id = '') {
        
        $cats = ""; 
        $terms = get_the_terms( $product_id, "product_cat" );
        $key = 0;

        // foreach product_cat get main top product_cat
        foreach ( $terms as $cat ) {
            $cat   = get_parent_terms( $cat );
            if (is_object($cat)){
                $cats .= '<a href="'.esc_url( get_category_link( $cat->term_id ) ).'" title="'.  esc_attr( $cat->name  ).'" >' .esc_html( $cat->name ) .'</a>'; 
            }
            $key++;
        }

        return $cats;
    } 
    
    
    require_once plugin_dir_path( __FILE__) . '/includes/class-wc-osp-widget.php';