<?php
class ImportController extends ReaderController{
    function __construct($xml_name, $nodeParseDepth = 3, $compare_file = false, $product_type = 'prodinfo'){
        parent::__construct($xml_name);
        
        $this->CSV = str_replace('.xml', '.csv', $this->output);
        $this->CSV_STREAM = fopen($this->CSV, 'w');
        $this->PRODUCTS = [];
        $this->JSON = false; 
        $this->PRODUCT_TYPE = $product_type;
        if($compare_file){
            $json = file_get_contents($compare_file);
            if($json){
                $this->JSON = json_decode($json);
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

        echo '<pre>';
        print_r($this->JSON);
        print_r($this->PRODUCTS);
        echo '</pre>';

        foreach ($this->PRODUCTS as $product) {
            
        }
    }

    function createProdInfoCSV(){
        fputcsv($this->CSV_STREAM, get_csv_headings('prodinfo'));
        $this->PRODUCTS = array_values($this->PRODUCTS);

        foreach ($this->PRODUCTS as $products) {
            $product_type = count($products) > 1 ? 'variable' : 'simple';
            $csv_line = convert_xml_prodinfo($products, $product_type, $this->JSON);
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