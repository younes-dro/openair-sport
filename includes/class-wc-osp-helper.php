<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WC_OSP_Helper {

    public static function get_shipping_counry() {
       
       if( WC()->customer ) 
       return WC()->customer->get_shipping_country();
    }
    public static function set_shipping_counry ( $country = '') {
        
        if (  WC()->customer )
        
        WC()->customer->set_shipping_country( $country );        
    
        
    }
    public static function getUserGEO() {

        // Get an instance of the WC_Geolocation object class
        $geolocation_instance = new WC_Geolocation();
        // Get user IP
        $user_ip_address = $geolocation_instance->get_ip_address();
        if ($user_ip_address === '127.0.0.1') {
            return 'MA';
        }
        // Get geolocated user IP country code.
        $user_geolocation = $geolocation_instance->geolocate_ip( $user_ip_address );

        return $user_geolocation['country'];
    }

}
