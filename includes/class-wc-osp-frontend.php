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
        //add_action( 'wp_enqueue_scripts', array ( $this , 'frontend_scripts' ) );
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
        if( ! $the_product ||  is_product()  ){
            return $title ;
        }
        
        // It s a product        
        $length_title = strlen( trim ( $title )) ;
        $short_title = $title;
        
        if( $length_title >= 40 ) {
            $short_title = substr( $short_title, 0 , 40 ) . ' ...';            
        }
        
        $parents_cats .= '<p class="product__categories">' . self::all_cat_classes ( $id ) . '</p>';
        
        return   $parents_cats. '<br>' .$short_title;
    }
    

    public static function get_parent_terms( $term ) {
        
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
    public static function all_cat_classes( $product_id = '') {
        
        $cats = ""; 
        $terms = get_the_terms( $product_id, "product_cat" );
        $key = 0;

        // foreach product_cat get main top product_cat
        foreach ( $terms as $cat ) {
            $cat   = self::get_parent_terms( $cat );
            $cats .=  ( strpos( $cats, $cat->slug ) === false ? $cat->slug." " : "" );
            $cats .= '<a href="'.esc_url( get_category_link( $cat ) ).'" title="'.  esc_attr( $cats  ).'" >' .esc_html($cat->name,'osp') .'</a>'; 
            $key++;
        }

        return $cats;
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

