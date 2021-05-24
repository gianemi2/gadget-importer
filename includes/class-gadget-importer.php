<?php 
class Gadget_Importer{
    function __construct(){
        $this->IMPORT_FILE = fopen(GADGET_PATH . 'file.csv', 'w');
        fputcsv($this->IMPORT_FILE, get_first_line());

        $this->FTP_HOST = 'transfer.midoceanbrands.com';
        $this->FTP_USER = 'gadgetfirenze';
        $this->FTP_PASS = '80841212';
        $this->FTP_CONNECTION = ftp_connect($this->FTP_HOST);
        ftp_login($this->FTP_CONNECTION, $this->FTP_USER, $this->FTP_PASS);

        $this->PRODUCTS_XML = 'prodinfo_it_v1.1.xml';
        $this->STOCK_XML = 'stock.xml';

        $this->TODOWNLOAD = [
            $this->PRODUCTS_XML,
            $this->STOCK_XML
        ];
    }

    function download_xml($xml) {
        try {
            $local_file = GADGET_PATH . $xml;
            if($this->isFileUpdated($xml)){
                return;
            }
            $server_file = $xml;
            if (ftp_get($this->FTP_CONNECTION, $local_file, $server_file, FTP_BINARY)) {
                echo "<br>Successfully downloaded ";
            } else {
                echo "<br>Error while downloading from ";
            }

        } catch (\Throwable $th) {
            echo $th;
        }

    }

    function read($xml, $node = 3){ 
        $streamer = Prewk\XmlStringStreamer::createStringWalkerParser(GADGET_PATH . $xml, ['captureDepth' => $node]);
        while ($node = $streamer->getNode()) {
            $xml_product = simplexml_load_string($node);
            $this->ELABORATED_ARRAY[(string)$xml_product->PRODUCT_BASE_NUMBER][] = $xml_product;
        }
        
        $this->import();
    }

    function import(){
        $this->ELABORATED_ARRAY = array_values($this->ELABORATED_ARRAY);
        foreach ($this->ELABORATED_ARRAY as $products) {
            $product_type = count($products) > 1 ? 'variable' : 'simple';
            //$products = $is_variation ? convert_variable_products($products) : $products;
            $csv_line = convert_xml_to_woocommerce($products, $product_type);
            if(is_array($csv_line[0])){
                foreach ($csv_line as $line) {
                    fputcsv($this->IMPORT_FILE, $line);
                }
            } else {
                fputcsv($this->IMPORT_FILE, $csv_line);
            }
        }
    }

    function processProductToWordPress($product, $variations){
        $post_title = (string)$product->PRODUCT_NAME;
        $thumbnail_id = upload_image($product->THUMBNAIL_URL)['id'];
        if(in_array($product->PRODUCT_ID, $this->IMPORTED_SKU)) return;

        $product = [
            'name' => $post_title,
            'description' => (string)$product->LONG_DESCRIPTION,
            //'sku' => (string)$product->PRODUCT_ID,
            'sku' => (string)$product->PRODUCT_NUMBER,
            'stock' => 9999
        ];

        if($variations){
            $variations = array_values($variations);
            foreach ($variations as $variation) {
                $attributes = create_attributes($variation);
                $variable_product_id = create_variable_product($product, $attributes);
                link_attributes_to_product($variable_product_id, $variation);   
            }
        } else {
            $product_id = create_simple_product($product);
        }
    }

    function handleAllImages($product, $post_id){
        upload_image($product->IMAGE_URL);

        foreach ($product->DIGITAL_ASSETS as $image) {
            upload_image($image->DIGITAL_ASSET->URL);
        }
    }

    function isFileUpdated($xml){
        if(file_exists(GADGET_PATH . $xml)){
            $last_edit = filemtime(GADGET_PATH . $xml);
            $fiveDaysAgo = strtotime("-5 days");

            if($last_edit > $fiveDaysAgo){
                return true;   
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function run(){

        $this->download_xml($this->PRODUCTS_XML);
        $this->read($this->PRODUCTS_XML);
    }
}