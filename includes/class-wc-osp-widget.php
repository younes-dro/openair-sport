<?php

class OSP_WIDGET extends WP_Widget {

    function __construct() {

        $params = array(
            'description' => ' Test Country / Currency ',
            'name' => 'Openair Sport'
        );
        parent::__construct('osp_widget', '', $params);
    }

    function form($instance) {

        extract($instance);
    }

    function widget($args, $instance) {
        echo '<div class="top-bar-right osp-countries">';
        echo '<i id="osp-country-field-icon" class="ops-show-hide-counrtries ri ri-earth"></i>';
    global $woocommerce;
        $countries_obj   = new WC_Countries();
        $countries   = $countries_obj->__get('countries');
        

        woocommerce_form_field('osp_country_field', array(
        'type'       => 'select',
        'class'      => array( 'osp-country-field' ),
        'label'      => '',
        'placeholder'    => __('Enter your country'),
        'autocomplete' => true,
        'default' => 'MA',
        'options'    => $countries
        )
        );
//        echo '</div>';
      
    }
    public function getUserGEO() {
        // Get an instance of the WC_Geolocation object class
        $geolocation_instance = new WC_Geolocation();
        // Get user IP
        $user_ip_address = $geolocation_instance->get_ip_address();
        // Get geolocated user IP country code.
        $user_geolocation = $geolocation_instance->geolocate_ip( $user_ip_address );        
        return $user_geolocation;
    }

}

add_action('widgets_init', 'register_osp_widget');

function register_osp_widget() {
    register_widget('OSP_WIDGET');
}

