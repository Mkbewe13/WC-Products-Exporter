<?php

namespace WCProductsExporter\CSV;

use League\Csv\ByteSequence;
use League\Csv\Reader;
use League\Csv\Writer;
use SplTempFileObject;
use WCProductsExporter\Other\ProductData;
use WCProductsExporter\Service\ProductDataService;

/**
 * A class that supports generating and downloading a csv file with products data
 *
 * @since x.x.x
 */
class CSVExport
{
    private const FILENAME = "Products_Export_";
    private $all_products_data;
    private $writer;
    private $header = ["Product name","Categories","SKU","Discount price","Price"];
    private $content;


    public function __construct()
    {
        $this->all_products_data = ProductDataService::get_prepared_products_data();

        $this->setContent($this->all_products_data);

        $this->writer = Writer::createFromString();
        $this->writer->insertOne($this->header);
        $this->writer->insertAll($this->content);
        $this->writer->setDelimiter("\t");
        $this->writer->setNewline("\r\n");
        $this->writer->setOutputBOM(ByteSequence::BOM_UTF8);



    }


    public function getCSVFile(){
        ob_clean();
        $this->writer->output(self::FILENAME);
        die();
    }

    private function setContent(array $products_data)
    {
        foreach ($products_data as $product_data) {

            if (is_a($product_data, ProductData::class)) {
                $this->content[] = $this->setDataRow($product_data);
            } elseif (is_array($product_data)) {
                foreach ($product_data as $variation_data) {
                    if (!is_a($variation_data, ProductData::class)) {
                        continue;
                    }
                    $this->content[] = $this->setDataRow($variation_data);

                }
            }
        }
    }

    private function setDataRow(ProductData $product_data): array
    {
        return array(
            $product_data->name,
            $product_data->categories,
            $product_data->sku,
            $product_data->discount_price,
            $product_data->full_price);

    }



}
