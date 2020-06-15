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

    function __construct() {
        
//        add_action('init',  array( $this , 'shorter_title' ));
        add_filter( 'woocommerce_cart_item_name', array ( $this ,  'osp_shorter_title_cart' ), 10, 3 );
        add_filter( 'the_title', array ( $this ,  'osp_shorter_title_home' ), 10, 3 );
        add_filter('woocommerce_sale_flash',  array( $this, 'osp_hide_sale_flash' ) );
        add_action( 'wp_enqueue_scripts', array ( $this , 'frontend_scripts' ) );
        add_filter( 'widget_categories_args' ,array ( $this, 'exclude_widget_categories' ) );
        add_filter( 'woocommerce_product_categories_widget_args', array ( $this , 'custom_woocommerce_product_categories_widget_args' ) );
        
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
     * Hide the WordPress default category .
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
    
    
    /*-----------------------------------------------------------------------------------*/
    /*  Scripts and Styles                                                               */
    /*-----------------------------------------------------------------------------------*/

    /**
     * Load scripts only in Single Product Variable Type.
     */ 
    public function frontend_scripts(){
        
        $the_product = wc_get_product( get_the_ID() );
        
        // If  we don't have the context Product we stop here .
        if( ! $the_product ){
//            return;
        }
        // Make sure we are viewing a single product variable.
//        if( $the_product->is_type( 'variable' ) && is_product() ) {
//        }
        
        wp_register_style( 'wc-osp-frontend', WC_OSP()->plugin_url() . '/assets/css/osp-frontend.css', array(), WC_OSP()->version );
        wp_enqueue_style( 'wc-osp-frontend' );

        wp_register_style( 'wc-osp-flagicon', WC_OSP()->plugin_url() . '/assets/flag-icon/css/flag-icon.min.css', array(), WC_OSP()->version );
        wp_enqueue_style( 'wc-osp-flagicon' );        
            
        wp_register_script( 'wc-osp-js', WC_OSP()->plugin_url() . '/assets/js/osp-frontend.js', array( 'jquery' ), WC_OSP()->version, true );
        wp_enqueue_script( 'wc-osp-js');        

    }
}

