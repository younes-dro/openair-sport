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
        add_filter( 'woocommerce_cart_item_name', array ( $this ,  'osp_shorter_title' ), 10, 3 );
        add_filter('woocommerce_sale_flash',  array( $this, 'osp_hide_sale_flash' ) );
        //add_action( 'wp_enqueue_scripts', array ( $this , 'frontend_scripts' ) );
    }
    
    /**
     * Display the product titles shorter in Cart and Checkout pages.
     * 
     * @param type $item_name
     * @param type $cart_item
     * @param type $cart_item_key
     * @return type String
     */
    public function osp_shorter_title( $item_name,  $cart_item,  $cart_item_key ) {
        
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
            return;
        }
        // Make sure we are viewing a single product variable.
        if( $the_product->is_type( 'variable' ) && is_product() ) {
        }

    }
}

