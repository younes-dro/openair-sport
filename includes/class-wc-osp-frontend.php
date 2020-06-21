<?php

/**
 * Front-End 
 * 
 * @class WC_OSP_Frontend
 * @author Younes DRO <younesdro@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WC_OSP_Frontend
 *
 * OSP front-end functions .
 */
class WC_OSP_Frontend {
    
    public static $desable_form_product  = false;

    function __construct() {
        
        add_action('wp_head' , array ( $this , 'osp_google_analytics') );
        add_filter( 'woocommerce_cart_item_name', array ( $this ,  'osp_shorter_title_cart' ), 10, 3 );
        add_filter( 'the_title', array ( $this ,  'osp_shorter_title_home' ), 10, 3 );
        add_filter('woocommerce_sale_flash',  array( $this, 'osp_hide_sale_flash' ) );
        add_action( 'wp_enqueue_scripts', array ( $this , 'frontend_scripts' ) );
        add_filter( 'widget_categories_args' ,array ( $this, 'exclude_widget_categories' ) );
        add_filter( 'woocommerce_product_categories_widget_args', array ( $this , 'custom_woocommerce_product_categories_widget_args' ) );
        
    }
    
    /**
     * Display google analytics .
     */
    public function osp_google_analytics (){
        
        $key = " <!-- Global site tag (gtag.js) - Google Analytics --><script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-169940218-1\"> </script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'UA-169940218-1'); </script>";
        echo $key;
    }
    /**
     * Display the product titles shorter in all store except the single product page.
     * 
     * @param type $title
     * @param type $id
     * @return string
     */
    function osp_shorter_title_home($title , $id ){
        
        $the_product  = wc_get_product( $id );
        $parents_cats = '';
        
        // If  we don't have the context Product , or we  we stop here .
        if( ! $the_product ||  is_product() || is_cart() || is_checkout() || is_checkout_pay_page()  ){
            return $title ;
        }
        
        // It s a product        
        $length_title = strlen( trim ( $title )) ;
        $short_title = $title;
        
        if( $length_title >= 40 ) {
            $short_title = substr( $short_title, 0 , 40 ) . ' ...';            
        }
        
        
        return   $short_title;
    }
       
    /**
     * Display the product titles shorter in Cart and Checkout pages.
     * 
     * @param type $item_name
     * @param type $cart_item
     * @param type $cart_item_key
     * @return type String
     */
    public function osp_shorter_title_cart( $item_name,  $cart_item,  $cart_item_key ) {
        
        $length_title = strlen( trim( get_the_title ( $cart_item['product_id'] ) ) );
        $short_title = get_the_title ( $cart_item['product_id'] );
        if( $length_title >= 40 ) {
            $short_title = substr(get_the_title ( $cart_item['product_id']), 0 , 40 ) . ' ...';            
        }
        
        $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            
        return sprintf( '<a href="%s">%s </a>', $_product->get_permalink( $cart_item ), $short_title );
    }

    /**
     * Hide the sale badge.
     * 
     * @return boolean
     */
    public function osp_hide_sale_flash() {
        
        return false;
        
    }
    
    /**
     * Hide the WordPress default category from widget.
     * @param array $args
     * @return array 
     */
    public function exclude_widget_categories( $args ){
        
        $exclude = "1,15";
        $args["exclude"] = $exclude;
        
        return $args;
    }
    
    /**
     * Hide the WooCommerce default product category.
     * 
     * @param array $args
     * @return array
     */
    public function custom_woocommerce_product_categories_widget_args( $args ) {
        
      $args['exclude'] = get_option( 'default_product_cat' );
      
      return $args;
    }    
    
    public function change_default_checkout_country() {
      
        if ( isset( $_COOKIE['country_user'])) {
            
            return $_COOKIE['country_user'];
        }
        
        return WC_OSP_Helper::getUserGEO();
    }    
    
    
    /*-----------------------------------------------------------------------------------*/
    /*  Scripts and Styles                                                               */
    /*-----------------------------------------------------------------------------------*/

    /**
     * Load scripts.
     */ 
    public function frontend_scripts(){
                
        wp_register_style( 'wc-osp-frontend', WC_OSP()->plugin_url() . '/assets/css/osp-frontend.css', array(), WC_OSP()->version );
        wp_enqueue_style( 'wc-osp-frontend' );
        
        wp_register_style( 'wc-osp-selec2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css', array( 'wc-osp-frontend' ), WC_OSP()->version );
        wp_enqueue_style( 'wc-osp-selec2' );        

        wp_register_style( 'wc-osp-flagicon', WC_OSP()->plugin_url() . '/assets/flag-icon/css/flag-icon.min.css', array(), WC_OSP()->version );
        wp_enqueue_style( 'wc-osp-flagicon' );
        
        wp_register_style( 'osp-widget-couontries', WC_OSP()->plugin_url() . '/assets/css/osp-widget-couontries.css', array(), WC_OSP()->version );
        wp_enqueue_style( 'osp-widget-couontries' );
        
        wp_enqueue_style( 'ionicons', 'http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css' ,array(), WC_OSP()->version );        
            
        wp_register_script( 'wc-osp-js', WC_OSP()->plugin_url() . '/assets/js/osp-frontend.js', array( 'jquery' ), WC_OSP()->version, true );
        wp_enqueue_script( 'wc-osp-js' );
        
        wp_register_script( 'wc-osp-select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js', array( 'jquery' ), WC_OSP()->version, true );
        wp_enqueue_script( 'wc-osp-select2-js' );
        
        if ( self::$desable_form_product ){
            wp_localize_script( 'wc-osp-js', 'ops_scripts_vars', array( 'desable_form' => true ) );
        }
        
        if(is_page( 'contact-us' ) || is_page( 'customer-help' )){
            wp_register_script( 'wc-osp-accordion', WC_OSP()->plugin_url() . '/assets/js/osp-accordion.js', array( 'jquery' ), WC_OSP()->version, true );
            wp_enqueue_script( 'wc-osp-accordion' );             
        }

    }
}

