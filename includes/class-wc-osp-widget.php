<?php

class OSP_WIDGET extends WP_Widget {
    
    public $cookie_country_name = 'country_user';
    
    function __construct() {

        $params = array(
            'description' => ' Test Country / Currency ',
            'name' => 'Openair Sport'
        );
        add_action('init', array( $this , 'set_ops_cookies') );
        parent::__construct('osp_widget', '', $params);
    }

    function form($instance) {

        extract($instance);
    }

    function widget($args, $instance) {
        
        global $woocommerce;
        $countries_obj   = new WC_Countries();
        $countries   = $countries_obj->__get('countries');

        if ( isset( $_COOKIE['country_user'] ) ){
            $default_country = $_COOKIE['country_user'];
        }else if(isset ( $_GET['country'] ) ){
            $default_country = $_GET['country'];
        } else{
            $default_country = $this->getUserGEO();
        }

        echo '<div class="top-bar-right osp-countries">';
        
        echo '<div class="osp-shipping">';
        
        echo '<span>' ; esc_html_e( 'Shipping to:' , 'ops' ); echo '</span>';
        echo '<div id="osp-flag-wrapper" class="osp-flag-wrapper">';
            echo '<div class="osp-img-thumbnail flag-icon-squared osp-flag osp-flag-icon-background flag-icon-'.strtolower($default_country).'"></div>';
        echo '</div>'; 
        
        echo '</div>';
        
        woocommerce_form_field('osp_country_field', array(
        'type'       => 'select',
        'class'      => array( 'osp-country-field' ),
        'label'      => '',
        'placeholder'    => __('Enter your country'),
        'autocomplete' => true,
        'default' => $default_country,
        'options'    => $countries
        )
        );
        
        
        echo '</div>'; //.top-bar-right
      
    }
    public function getUserGEO() {
        
        // Get an instance of the WC_Geolocation object class
        $geolocation_instance = new WC_Geolocation();
        // Get user IP
        $user_ip_address = $geolocation_instance->get_ip_address();
        if( $user_ip_address === '127.0.0.1'){
            return 'MA';
        }
        // Get geolocated user IP country code.
        $user_geolocation = $geolocation_instance->geolocate_ip( $user_ip_address );
        
        return $user_geolocation['country'];
    }
    
    public function set_ops_cookies(  ){
       
       if ( isset( $_GET['country'] ) ){
           $c = $_GET['country'] ;
       }
       else if( isset( $_COOKIE['country_user'] ) ){
           $c = $_COOKIE['country_user'];
       }else{
           $c = $this->getUserGEO();
       }
       setcookie( 'country_user', $c ,  time()+60*60*24*30 , '/' ); 
       $_COOKIE['country_user'] = $c;
    }
    
    public function get_ops_cookies( ) {
        if ( isset( $_COOKIE['country_user'] ) )
            
        return $_COOKIE['country_user'];
    }

}

add_action('widgets_init', 'register_osp_widget');

function register_osp_widget() {
    register_widget('OSP_WIDGET');
}

