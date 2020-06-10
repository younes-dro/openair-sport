<?php

/**
 * Open Air Sport Store
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to younesdro@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Open Air Sport Store to newer
 * versions in the future. If you wish to customize Open Air Sport Store for your
 * needs please refer to https://github.com/younes-dro/ for more information.
 *
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check the compatibility of the environment.
 * 
 * @class WC_OSP_Dependencies
 * @author Younes DRO <younesdro@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class WC_OSP_Dependencies {

    /** minimum PHP version required by this plugin */
    const MINIMUM_PHP_VERSION = '5.3';

    /** minimum WordPress version required by this plugin */
    const MINIMUM_WP_VERSION = '5.3.2';

    /** minimum WooCommerce version required by this plugin */
    const MINIMUM_WC_VERSION = '3.7.0';

    public function __construct() {
        
    }

    /**
     * Checks the PHP version.
     * 
     * @since 1.0.0
     */
    public static function check_php_version() {

        return version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=' );
    }

    /**
     * Gets the message for display when the environment is incompatible with this plugin.
     * 
     * @return string
     */
    public static function get_php_notice() {

        return sprintf(
                esc_html__( 'The minimum PHP version required for this plugin is %1$s. You are running %2$s.', 'osp')
                , self::MINIMUM_PHP_VERSION, PHP_VERSION );
    }

    /**
     * Checks the WP version.
     * 
     * @since 1.0.0
     */
    public static function check_wp_version() {
        
        if ( ! self::MINIMUM_WP_VERSION ){
            return true;
        }

        return version_compare( get_bloginfo( 'version' ), self::MINIMUM_WP_VERSION, '>=' );
    }

    public static function get_wp_notice(){
        
        return sprintf(
                esc_html__( '%s is not active, as it requires WordPress version %s or higher. Please %supdate WordPress &raquo;%s', 'osp')
				,'<strong>' . WC_Custom_Variable_Products()->plugin_name . '</strong>',
				self::MINIMUM_WP_VERSION,
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">', '</a>'
			);
    }
    /**
     * Checks WC version.
     * 
     * @since 1.0.0
     */
    public static function check_wc_version() {
        
        if ( ! self::MINIMUM_WC_VERSION ){
            return true;
        }
        
        return defined( 'WC_VERSION' ) && version_compare( WC_VERSION, self::MINIMUM_WC_VERSION, '>=' );
    }
    
    public function get_wc_notice() {
        
        return sprintf(
                esc_html__( '%s is not active, as it requires WooCommerce version %s or higher. Please %supdate WooCommerce &raquo;%s', 'woocommerce-custom-variable-products')
				,'<strong>' . WC_Custom_Variable_Products()->plugin_name . '</strong>',
				self::MINIMUM_WC_VERSION,
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">', '</a>'
			);
    }

    /**
     * Determines if all the requirements are valid .
     * 
     * @since 1.0.0
     * 
     * @return bool
     */
    public function is_compatible( ) {
     
        return ( self::check_php_version() && self::check_wp_version() && self::check_wc_version() );
    }
}
