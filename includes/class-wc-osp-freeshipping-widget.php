<?php

class OSP_FREESHIPPING_WIDGET extends WP_Widget {
    
    
    function __construct() {

        $params = array(
            'description' => 'Add Custom Freeshiping message ',
            'name' => 'Openair Sport : Free Shipping Area'
        );
        parent::__construct('OSP_FREESHIPPING_WIDGET', '', $params);
    }

    function form($instance) {

        extract($instance);
    }

    function widget($args, $instance) {
        
        if( is_product() ){
            $current_product = wc_get_product(get_the_ID() );            
            
            $ship_to = WC()->customer->get_shipping_country();
            $ext = new A2W_Woocommerce();
            $ext_id = $ext->get_product_external_id( get_the_ID() );
            $shipping_loader = new A2W_ShippingLoader();
            $shipping_data = $shipping_loader->load(new A2W_ShippingMeta( get_the_ID(), $ext_id, $ship_to, '', '', '' ) );
            $shipping_methods = $shipping_data['data']['ways'];
            $isFreeShipping = false; 
            foreach ($shipping_methods as $key => $value) {

                if ( $value['price'] == 0 ){
                    $isFreeShipping = true; 
                }
            }
            
            
            echo '<div class="product-widget">';
            echo '<div class="textwidget">';
            echo '<p><strong>Buy now and enjoy:</strong></p>';
            echo '<ul>';
            if ( $isFreeShipping ){
                echo '<li><strong>Free shipping</strong> on this item</li>';
            }
            echo '<li>30 days hassle-free returns</li>';
            
            if( $this->displayed_sale_price_html( $current_product ) ){
               echo '<li> UPTO <strong>' . $this->displayed_sale_price_html( $current_product ) . '% </strong>OFF</li>';                
            }
            
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }
      
    }
    
    /**
     * Display the percentage 
     * 
     * @param object $product
     * @return strinf
     */
    public function displayed_sale_price_html ( $product ){
        $percentages = array();
        // Get all variation prices.
        $prices = $product->get_variation_prices();

        // Loop through variation prices.
        foreach ( $prices['price'] as $key => $price ) {
	    // Only on sale variations.
                if ( $prices['regular_price'][ $key ] !== $price ) {
	            // Calculate and set in the array the percentage for each variation on sale.
		        $percentages[] = round( 100 - ( $prices['sale_price'][ $key ] / $prices['regular_price'][ $key ] * 100 ) );
		}
	}
        // Keep the highest value.
	return $percentage = max( $percentages ) ;        
    }
}
add_action('widgets_init', 'register_osp_freeshipping__widget');

function register_osp_freeshipping__widget() {
    register_widget('OSP_FREESHIPPING_WIDGET');
}

