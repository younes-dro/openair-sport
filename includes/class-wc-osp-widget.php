<?php

class OSP_WIDGET extends WP_Widget {
    
    public $cookie_country_name = 'country_user';
    
    function __construct() {

        $params = array(
            'description' => 'Display List of countries',
            'name' => 'Openair Sport : Countries'
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
            
            echo '<a href="javascript:void(0)" class="switcher-info">';
                echo '<span class="ship-to">' ; esc_html_e( 'Ship to ' , 'ops' ); 
                    echo '<i class="css_flag  flag-icon-squared osp-flag osp-flag-icon-background flag-icon-'.strtolower($default_country).'">';
                    echo '</i>';
                echo '</span>';
                echo '<i class="open-country ion-chevron-down"></i>';
            echo '</a>';

            echo '<div class=" switcher-sub osp-contries-wrapper">';
            echo '<div class="switcher-common">';
                echo '<span class="label">Ship to</span>';
                echo '<select id="osp-country-field" class="osp-country-field">';
                foreach ($countries as $code => $country){
                    $selected = ( $code === $default_country) ? 'selected="selected"' : '';
                    echo '<option '.$selected.'  value="'.$code.'">'.$country.'</option>';
                }
                echo '</select>';
            echo '</div>'; // .switcher-common
            echo '</div>'; //.osp-contrey-wrapper

            
            echo '</div>';//.osp-shipping
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
       
        if ( !is_admin()){
            if( WC_OSP_Helper::get_shipping_counry() ){
                $c = WC_OSP_Helper::get_shipping_counry();
            }
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
            // Update the customer shipping country 
            WC_OSP_Helper::set_shipping_counry( $c );            
        }
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

