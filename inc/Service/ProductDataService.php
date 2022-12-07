<?php

namespace WCProductsExporter\Service;

use WCProductsExporter\Other\ProductData;

/**
 * A class that handles the preparation of product data for export
 *
 * @since x.x.x
 */
class ProductDataService
{

    /**
     * Returs array of ProductData objects that represent data of all products
     *
     * @return array
     */
    public static function get_prepared_products_data(): array
    {

        $products_data = array();

        $args = array(
            'status' => 'publish',
            'limit' => -1
        );

        $products = wc_get_products($args);

        foreach ($products as $product) {

            if (!is_a($product, 'WC_Product_Variable') && !is_a($product, 'WC_Product_Simple')) {
                continue;
            }

            if ($product->is_type('variable')) {
                $products_data[] = self::get_all_variations_data($product->get_id());
            } else {
                $products_data[] = ProductData::fromProductSimple($product);

            }
        }

        return $products_data;

    }

    /**
     * Returns array of ProductData objects that represent all products variations
     *
     * @param int $get_id
     * @return array
     */
    private static function get_all_variations_data(int $get_id): array
    {
        $product = wc_get_product($get_id);
        $variations_ids = $product->get_children();

        if (empty($variations_ids)) {
            return [];
        }

        $product_variations_data = [];

        foreach ($variations_ids as $variation_id) {
            $variation = new \WC_Product_Variation($variation_id);

            $product_variations_data[] = ProductData::fromProductVariation($variation);
        }

        return $product_variations_data;
    }

}
