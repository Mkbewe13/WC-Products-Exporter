<?php
/**
 * Plugin Name:     WC Products Exporter
 * Description:     Exports woocommerce products in csv file
 * Author:          Mkbewe13
 * Text Domain:     WPE
 * Version:         0.1
 */

if (!defined('ABSPATH')) {
    exit;
}

define('APP_DIR', dirname(__FILE__));

require_once 'vendor/autoload.php';

class WC_Products_Exporter
{
    const MIMIMUM_WORDPRESS_VERSION = '6.1';
    const MIMIMUM_WOOCOMMERCE_VERSION = '7.1';
    const MIMIMUM_PHP_VERSION = '7.2.0';

    public function init()
    {
        register_activation_hook(__FILE__, [$this, 'activation']);
    }


    public function activation()
    {
        $this->validatePhpVersion();
        $this->validateWordpressVersion();
        $this->validateWoocommerceVersion();

    }

    private function validateWoocommerceVersion(): void
    {

        if (!class_exists('WooCommerce')) {
            die('WC Products Exporter requires an active Woocommerce plugin.');
        }

        if ( version_compare( WC_VERSION, self::MIMIMUM_WOOCOMMERCE_VERSION, '<' ) ) {
            die('WC Products Exporter requires at least Woocommerce 7.1');
        }
    }

    private function validatePhpVersion(): void
    {
        if(version_compare(phpversion(),self::MIMIMUM_PHP_VERSION,'<')){
            die('WC Products Exporter requires at least PHP 7.2.0');
        }
    }

    private function validateWordpressVersion(): void{
        if(version_compare(get_bloginfo('version'),self::MIMIMUM_WORDPRESS_VERSION, '<') ){
            die('WC Products Exporter requires at least Wordpress 6.1');
        }
    }
}

$wcProductsExporter = new WC_Products_Exporter();
$wcProductsExporter->init();
