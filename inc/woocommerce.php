<?php

use WCProductsExporter\Service\ProductDataService;

/**
 * Creates submenu with callback that trigger products export.
 *
 * @return void
 *
 * @since x.x.x
 */
function create_products_submenu(){
    add_submenu_page(
        'edit.php?post_type=product',
        'WC Product CSV Exporter',
        'WC Product CSV Exporter',
        'manage_options',
        'wc-csv-export',
        'wc_products_export_submenu_callback',
        99
    );
}
add_action('admin_menu', 'create_products_submenu');
/**
 * Callback for the products csv import submenu page.
 *
 * @since x.x.x
 */
function wc_products_export_submenu_callback()
{
    $csv_exporter = new \WCProductsExporter\CSV\CSVExport();
    try {
        $csv_exporter->getCSVFile();
    } catch (Exception|\League\Csv\Exception $e) {
        wp_safe_redirect(admin_url('?csv_failed=1'));
    }

}

/**
 * Display erron notice in admin panel after redirecting if something went wrong with csv export
 *
 * @return void
 *
 * @since x.x.x
 */
function product_export_failed_error_display()
{
    if(!empty($_GET['csv_failed']) && $_GET['csv_failed'] == 1){
        echo '<div class="notice notice-error is-dismissible">
             <p>Something went wrong with generating the product export csv file. Try again.</p>
         </div>';
    }

}
add_action('admin_notices', 'product_export_failed_error_display');
