<?php
class ImportController{
    function __construct($xml_name){
        $this->XML = XML_PATH . $xml_name;
        $this->CSV = CSV_PATH . str_replace('.xml', '.csv', $xml_name);
        $this->CSV_STREAM = fopen($this->CSV, 'w');
        $this->PRODUCTS = [];
    }

    function readProdInfo($nodeParseDepth = 3){ 
        $streamer = Prewk\XmlStringStreamer::createStringWalkerParser($this->XML, ['captureDepth' => $nodeParseDepth]);
        while ($node = $streamer->getNode()) {
            $xml_product = simplexml_load_string($node);
            $this->PRODUCTS[(string)$xml_product->PRODUCT_BASE_NUMBER][] = $xml_product;
            $i++;
        }
        
        $this->importProdInfo();
    }

    function importProdInfo(){
        fputcsv($this->CSV_STREAM, get_csv_headings('prodinfo'));
        $this->PRODUCTS = array_values($this->PRODUCTS);

        foreach ($this->PRODUCTS as $products) {
            $product_type = count($products) > 1 ? 'variable' : 'simple';
            $csv_line = convert_xml_prodinfo($products, $product_type);
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