<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WC_OSP_Currency {

    function __construct() {
        
        add_filter('woocommerce_product_get_price', array($this, 'custom_price'), 99, 2);
        add_filter('woocommerce_product_get_regular_price', array($this, 'custom_price'), 99, 2);
        // Variations 
        add_filter('woocommerce_product_variation_get_price', array($this, 'custom_price'), 99, 2);
        add_filter('woocommerce_product_variation_get_regular_price', array($this, 'custom_price'), 99, 2);
        

        // Variable (price range)
        add_filter('woocommerce_variation_prices_price', array($this, 'custom_variable_price'), 99, 3);
//        add_filter('woocommerce_variation_prices_regular_price', array($this, 'custom_variable_price'), 99, 3);
        
        // Sale price 
        add_filter( 'woocommerce_product_get_sale_price', array ($this, 'custom_price'), 99, 2 );
        // Sale price  Variable products (min-max)
//        add_filter( 'woocommerce_variation_prices_sale_price', array ($this, 'custom_variable_price'), 99, 3 );
        // Products variations
//        add_filter( 'woocommerce_product_variation_get_sale_price', array ($this, 'custom_variable_price'), 99, 3 );        
        
        // Handling price caching
        add_filter('woocommerce_get_variation_prices_hash', array($this, 'add_price_multiplier_to_variation_prices_hash'), 99, 1);
        
        // currency symbols 
        add_filter('woocommerce_currency_symbol', array ( $this , 'change_existing_currency_symbol' ) , PHP_INT_MIN, 2);
        
        // Cart 
        add_action( 'woocommerce_before_calculate_totals', array  ( $this , 'set_cart_item_sale_price' ), 20, 1 );
    }

    // Utility function to change the prices with a multiplier (number)
    public static function get_price_multiplier() {
        $rate = 1; 
        if ( isset( $_COOKIE['currency_user'])){
            if( $_COOKIE['currency_user'] === 'EUR'){
                $rate = 0.89;
            }else if($_COOKIE['currency_user'] === 'GBP'){
             $rate = 0.80;   
            }
        }
//        var_dump( get_woocommerce_currency() );
        return $rate; 
    }

    public function custom_price($price, $product) {
        if (is_numeric($price))
        return (float) round ( $price *  self:: get_price_multiplier(), 2 ) ;
    }

    public function custom_variable_price($price, $variation, $product) {
        if (is_numeric($price))
        return  (float) round( $price * self::get_price_multiplier(), 2 ) ;
    }

    public function add_price_multiplier_to_variation_prices_hash($hash) {
        $hash[] = self::get_price_multiplier();
        return $hash;
    }
    function change_existing_currency_symbol( $currency_symbol, $currency ) {
        
        if ( isset( $_COOKIE['currency_user'])){
            if( $_COOKIE['currency_user'] === 'EUR'){
                $currency_symbol = '&euro;';
            }else if($_COOKIE['currency_user'] === 'GBP'){
                $currency_symbol = "&#163;";
            }
        }

         return $currency_symbol;
    }
    function set_cart_item_sale_price( $cart ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) )
            return;

        if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
            return;

        // Iterate through each cart item
        foreach( $cart->get_cart() as $cart_item ) {
            $price = $cart_item['data']->get_sale_price(); // get sale price
            $cart_item['data']->set_price( $price ); // Set the sale price

        }
    }    
}

new WC_OSP_Currency();