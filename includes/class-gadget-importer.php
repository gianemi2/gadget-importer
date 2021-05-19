<?php 
require(GADGET_PATH . 'helpers/upload-image.php');
class Gadget_Importer{
    function __construct(){
        $this->FTP_HOST = 'transfer.midoceanbrands.com';
        $this->FTP_USER = 'gadgetfirenze';
        $this->FTP_PASS = '80841212';

        $this->FTP_CONNECTION = ftp_connect($this->FTP_HOST);
        ftp_login($this->FTP_CONNECTION, $this->FTP_USER, $this->FTP_PASS);

        $this->STOCK_READY = true;
        $this->PRODUCTS_XML = 'prodinfo_it_v1.1.xml';
        $this->STOCK_XML = 'stock.xml';

        $this->ELABORATED_ARRAY = [];

        $this->TODOWNLOAD = [
            $this->PRODUCTS_XML,
            $this->STOCK_XML
        ];
        $this->IMPORTED_SKU = [];
    }

    function download_xml($xml) {
        try {
            $local_file = GADGET_PATH . $xml;
            if(!$this->isFileUpdated($xml)){
                $this->STOCK_READY = false;
            } else {
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
        // Debug: remove the comment.
        //if($this->STOCK_READY) return false;
        $streamer = Prewk\XmlStringStreamer::createStringWalkerParser(GADGET_PATH . $xml, ['captureDepth' => $node]);
        $i = 0;
        while ($node = $streamer->getNode()) {
            // Debug: Remove count
            if($i < 10){
                $xml_product = simplexml_load_string($node);
                $this->elaborateArray($xml_product);
            }
            $i++;
        }
        $this->import();
    }

    function elaborateArray($xml_product){
        $this->ELABORATED_ARRAY[(string)$xml_product->PRODUCT_BASE_NUMBER][] = $xml_product;
    }

    function import(){
        $product = [];
        $serialized_variations = false;

        $this->ELABORATED_ARRAY = array_values($this->ELABORATED_ARRAY);
        foreach ($this->ELABORATED_ARRAY as $products) {
            $product = $products[0];
            if(count($products) > 1){
                $color_variations = [];
                $serialized_variations = [
                    'color' => [
                        'label' => 'pa_color',
                        'value' => []
                    ]
                ];

                foreach($products as $variation){
                    $color_variations[] = (string)$variation->COLOR_DESCRIPTION;
                }
                $serialized_variations['color']['value'] = $color_variations;
            }
            $this->processProductToWordPress($product, $serialized_variations);
        }
    }

    function processProductToWordPress($product, $variations){
        $post_title = (string)$product->PRODUCT_NAME;
        $thumbnail_id = upload_image($product->THUMBNAIL_URL)['id'];
        if(in_array($product->PRODUCT_ID, $this->IMPORTED_SKU)) return;

        $product = [
            'name' => $post_title,
            'description' => (string)$product->LONG_DESCRIPTION,
            'sku' => (string)$product->PRODUCT_ID,
            'stock' => 9999
        ];

        if($variations){
            $attributes = create_attributes($variations['color']);
            $variable_product_id = create_variable_product($product, $attributes);
            link_attributes_to_product($variable_product_id, $variations['color']);
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