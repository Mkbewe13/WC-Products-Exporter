<?php

class ProductDataService
{

    public static function get_prepare_products_data(){

        $args = array(
            'status' => 'publish',
            'limit' => -1
        );
        $products = wc_get_products( $args );
    }

}
