<?php

namespace WCProductsExporter\CSV;

use League\Csv\ByteSequence;
use League\Csv\Reader;
use League\Csv\Writer;
use PHPMailer\PHPMailer\Exception;
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

        try{
            $this->setupWriter();
        }catch (Exception|\League\Csv\Exception $e){
            wp_safe_redirect(admin_url('?csv_failed=1'));
        }
    }

    /**
     * Trigger csv file download
     *
     * @return void
     */
    public function getCSVFile(){
        ob_clean();
        $this->writer->output(self::FILENAME);
        die();
    }

    /**
     * Set content for writer from ProductData object
     *
     * @param array $products_data
     * @return void
     */
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

    /**
     * Returns single data row for file content.
     *
     * @param ProductData $product_data
     * @return array
     */
    private function setDataRow(ProductData $product_data): array
    {
        return array(
            $product_data->name,
            $product_data->categories,
            $product_data->sku,
            $product_data->discount_price,
            $product_data->full_price);

    }

    /**
     * Sets up writer and fill csv file with prepared data.
     *
     * @return void
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    private function setupWriter(){
        $this->writer = Writer::createFromString();
        $this->writer->insertOne($this->header);
        $this->writer->insertAll($this->content);
        $this->writer->setDelimiter("\t");
        $this->writer->setNewline("\r\n");
        $this->writer->setOutputBOM(ByteSequence::BOM_UTF8);
    }


}
