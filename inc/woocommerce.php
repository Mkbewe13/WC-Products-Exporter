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
 */
function wc_products_export_submenu_callback() {
    $csv_exporter = new \WCProductsExporter\CSV\CSVExport();
    $csv_exporter->getCSVFile();
}
