<?php
class ProdExtraController extends ReaderController{
    function __construct($xml_name, $nodeParseDepth = 3){
        parent::__construct($xml_name);
        $this->CSV = str_replace('.xml', '.csv', $this->output);
        $this->CSV_STREAM = fopen($this->CSV, 'w');
        $this->PRODUCTS = [];
    }
    function run($sku_property = 'ID', $check_for_property = false){
        $current_node_number = 0;
        while ($node = $this->data->getNode()) {
            $xml_product = simplexml_load_string($node);
            if($check_for_property){
                if(!isset($xml_product->$check_for_property))
                    continue;
            }
            $product_setup = [];
            $product_setup['sku'] = (string)$xml_product->PRODUCT_BASE_NUMBER . '-' . explode(';',$xml_product->ITEM_COLOR_NUMBER)[0];
            foreach ($xml_product->PRINTING_POSITIONS->PRINTING_POSITION as $pos) {
                $print_position = [];
                $print_position['name'] = (string)$pos->ID;
                $print_position['max_height'] = (string)$pos->MAX_PRINT_SIZE_HEIGHT;
                $print_position['max_width'] = (string)$pos->MAX_PRINT_SIZE_WIDTH;
                $print_position['image'] = (string)$pos->PRINT_POSITION_URL;
                
                $techniques = [];
                foreach ($pos->PRINTING_TECHNIQUE as $techs) {
                    $techniques[] = [
                        'name' => convert_printing_id((string)$techs->ID),
                        'max_colors' => (string)$techs->MAX_COLORS
                    ];
                }
                $print_position['techniques'] = $techniques;
                $product_setup['print_positions'][] = $print_position;
            }
            $results = create_prodextras($product_setup, $current_node_number);
            $rows = $results[0];
            $current_node_number = $results[1];
            $rows = array_merge(...$rows);
            foreach ($rows as $row) {
                $this->PRODUCTS[] = $row;
            }
            $current_node_number = $current_node_number+1;
        }
        $this->create_csv();
        
    }

    function create_csv(){
        fputcsv($this->CSV_STREAM, get_csv_headings('prodextra'));
        foreach ($this->PRODUCTS as $line) {
            fputcsv($this->CSV_STREAM, $line);
        }
    }
}