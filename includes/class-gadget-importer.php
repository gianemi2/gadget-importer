<?php 
require(GADGET_PATH . 'helpers/upload-image.php');
class Gadget_Importer{
    function __construct(){
        $this->FTP_HOST = 'transfer.midoceanbrands.com';
        $this->FTP_USER = 'gadgetfirenze';
        $this->FTP_PASS = '80841212';

        $this->FTP_CONNECTION = ftp_connect($this->FTP_HOST);
        ftp_login($this->FTP_CONNECTION, $this->FTP_USER, $this->FTP_PASS);

        $this->PRODUCTS_XML = 'prodinfo_it_v1.1.xml';
        $this->STOCK_XML = 'stock.xml';

        $this->ELABORATED_ARRAY = [];

        $this->TODOWNLOAD = [
            $this->PRODUCTS_XML,
            $this->STOCK_XML
        ];
    }

    function download_xml($xml) {
        try {
            $local_file = GADGET_PATH . $xml;
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
        $i = 0;
        while ($node = $streamer->getNode()) {
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
        $product;
        $serialized_variations;

        $this->ELABORATED_ARRAY = array_values($this->ELABORATED_ARRAY);
        foreach ($this->ELABORATED_ARRAY as $products) {
            $product = $products[0];
            if(count($products) === 1){
                $serialized_variations = [];
            } else {
                $color_variations = [];
                $serialized_variations = [
                    'color' => [
                        'name' => 'Color',
                        'value' => '',
                        'position' => 0,
                        'is_visible' => 1,
                        'is_variation' => 0,
                        'is_taxonomy' => 0
                    ]
                ];
                unset($products[0]);
                foreach($products as $variation){
                    $color_variations[] = $variation->COLOR_DESCRIPTION;
                }
                $color_variations = implode('|', $color_variations);
                $serialized_variations['color']['value'] = $color_variations;

            }
            $this->processProductToWordPress($product, $serialized_variations);
        }
    }

    function processProductToWordPress($product, $serialized_variations){
        $post_title = (string)$product->PRODUCT_NAME;
        $thumbnail_id = upload_image($product->THUMBNAIL_URL)['id'];
        $wp_product = [
            'post_title' => $post_title,
            'post_status' => 'publish',
            'post_type' => 'product',
            'post_content' => (string)$product->LONG_DESCRIPTION,
            'post_excerpt' => (string)$product->SHORT_DESCRIPTION,
            'meta_input' => [
                '_sku' => (string)$product->PRODUCT_ID,
                '_stock' => 9999,
                '_length' => (string)$product->PACKAGING_CARTON->LENGTH,
                '_width' => (string)$product->PACKAGING_CARTON->WIDTH,
                '_height' => (string)$product->PACKAGING_CARTON->HEIGHT,
                '_weight' => (string)$product->PACKAGING_CARTON->WEIGHT,
                '_price' => 80,
                '_regular_price' => 95,
                '_thumbnail_id' => $thumbnail_id,
                '_product_attributes' => $serialized_variations
            ]
        ];
        $post_id = wp_insert_post( $wp_product );
        if($post_id){
            $this->handleAllImages($product, $post_id);
            echo $post_id . ' caricato correttamente';
        }
    }

    function handleAllImages($product, $post_id){
        upload_image($product->IMAGE_URL, $post_id);

        foreach ($product->DIGITAL_ASSETS as $image) {
            upload_image($image->DIGITAL_ASSET->URL, $post_id);
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

        //$this->download_xml($this->PRODUCTS_XML);
        
        $this->read($this->PRODUCTS_XML);
    }
}