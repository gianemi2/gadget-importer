<?php
class ImportController extends ReaderController{
    function __construct($xml_name, $nodeParseDepth = 3, $compare_file = false, $product_type = 'prodinfo', $attributes = false){
        parent::__construct($xml_name);
        
        if($compare_file){
            $this->compare_file = OUTPUT_PATH . $compare_file;
        }
        $this->CSV = str_replace('.xml', '.csv', $this->output);
        $this->CSV_STREAM = fopen($this->CSV, 'w');
        $this->PRODUCTS = [];
        $this->JSON = false; 
        $this->PRODUCT_TYPE = $product_type;
        if($this->compare_file){
            $json = file_get_contents($this->compare_file);
            if($json){
                echo 'OK COMPARE FILE FOUND!';
                $this->JSON = json_decode($json);
            } else {
                echo 'compare file not found :(';
            }
        }
        if($this->attributes){
            $attributes = file_get_contents($this->attributes);
            if($attributes){
                echo 'OK ATTRIBUTES FILE FOUND!';
                $this->ATTRIBUTES = json_decode($attributes);
            } else {
                echo 'attributes file not found :(';
            }
        }
    }

    function run($sku_property = 'PRODUCT_BASE_NUMBER'){ 
        while ($node = $this->data->getNode()) {
            $xml_product = simplexml_load_string($node, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->PRODUCTS[(string)$xml_product->$sku_property][] = $xml_product;
        }
        switch ($this->PRODUCT_TYPE) {
            case 'prodinfo':
                $this->createProdInfoCSV();
                break;
            case 'usb': 
                $this->createUSBCSV();
                break;
            default:
                # code...
                break;
        }
    }

    function createUSBCSV(){
        fputcsv($this->CSV_STREAM, get_csv_headings('prodinfo'));
        $this->PRODUCTS = array_values($this->PRODUCTS);

        foreach ($this->PRODUCTS as $products) {
            $product_type = count($products) > 1 ? 'variable' : 'simple';
            $csv_line = convert_xml_usb($products, $product_type);
            if(is_array($csv_line[0])){
                foreach ($csv_line as $line) {
                    fputcsv($this->CSV_STREAM, $line);
                }
            } else {
                fputcsv($this->CSV_STREAM, $csv_line);
            }
        }
    }

    function createProdInfoCSV(){
        fputcsv($this->CSV_STREAM, get_csv_headings('prodinfo'));
        $this->PRODUCTS = array_values($this->PRODUCTS);

        foreach ($this->PRODUCTS as $products) {
            $product_type = count($products) > 1 ? 'variable' : 'simple';
            $csv_line = convert_xml_prodinfo($products, $product_type, $this->JSON, $this->ATTRIBUTES);
            if(is_array($csv_line[0])){
                foreach ($csv_line as $line) {
                    fputcsv($this->CSV_STREAM, $line);
                }
            } else {
                fputcsv($this->CSV_STREAM, $csv_line);
            }
        }
    }
}