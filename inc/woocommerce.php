<?php
add_action('admin_menu', 'create_products_submenu');
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

/**
 * Callback for the products csv import submenu page.
 */
function wc_products_export_submenu_callback() {
    //ProductDataService::get_prepare_products_data();
}
