<?php

namespace WCProductsExporter\Other;

use WC_Product;

/**
 * A class for convenient handling of product fields that will be exported
 *
 * @since x.x.x
 */
class ProductData
{
    public $name;
    public $categories;
    public $sku;
    public $discount_price;
    public $full_price;

    public function __construct()
    {}

    /**
     * Returns instance of ProductData class based on simple product type.
     *
     * @param WC_Product $product
     * @return ProductData
     */
    public static function fromProductSimple(WC_Product $product): ProductData
    {
        $product_data = new self();

        $product_data->name = $product->get_name();
        $product_data->categories = self::getProductCategoriesString($product->get_id());
        $product_data->sku = $product->get_sku();
        $product_data->discount_price = $product->get_sale_price();
        $product_data->full_price = $product->get_regular_price();

        return $product_data;
    }

    /**
     *  Returns instance of ProductData class based on variable product type.
     *
     * @param \WC_Product_Variation $product
     * @return ProductData
     */
    public static function fromProductVariation(\WC_Product_Variation $product): ProductData
    {
        $product_data = new self();

        $product_data->name = $product->get_name();
        $product_data->categories = self::getProductCategoriesString($product->get_parent_id());
        $product_data->sku = $product->get_sku();
        $product_data->discount_price = $product->get_sale_price();
        $product_data->full_price = $product->get_regular_price();

        return $product_data;

    }

    /**
     * Returns string with all product categories.
     *
     * @param int $product_id
     * @return string
     */
    private static function getProductCategoriesString(int $product_id): string
    {
        $categories = array();
        $terms = get_the_terms($product_id, 'product_cat');
        foreach ($terms as $term) {
            $categories[] = $term->name;
        }
        return implode(', ', $categories);
    }


}
