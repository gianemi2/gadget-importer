<?php
class ConvertController extends ReaderController{
    function __construct($xml_name, $nodeParseDepth = 3){
        parent::__construct($xml_name);
        $this->json_path = str_replace('.xml', '.json', $this->output);
        $this->JSON = [];
    }
    function run($sku_property = 'ID'){
        while ($node = $this->data->getNode()) {
            $xml_product = simplexml_load_string($node);
            $el = $this->JSON[(string)$xml_product->sku_property];
            if(isset($el)){
                $el[] = $xml_product;
            } else {
                $this->JSON[(string)$xml_product->sku_property][] = $xml_product;
            }
        }
        echo '<pre>';
        print_r($this->JSON);
        echo '</pre>';
        if(file_put_contents( $this->json_path, json_encode($this->JSON) )) 
            echo "Stock JSON created!";
        else 
            echo "Something goes wrong.";
    }
}