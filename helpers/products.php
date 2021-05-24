<?php

function get_first_line(){
    return [
        'Type',
        'SKU',
        'Name',
        'Published',
        'Parent',
        'Is featured?',
        'Visibility in catalog',
        'Short description',
        'Description',
        'In stock?',
        'Stock',
        'Weight',
        'Length',
        'Width',
        'Height',
        'Sale price',
        'Regular price',
        'Categories',
        'Images',
        'Attribute 1 name',
        'Attribute 1 value (s)',
        'Attribute 1 visible',
        'Attribute 1 global',
        'Attribute 2 name',
        'Attribute 2 value (s)',
        'Attribute 2 visible',
        'Attribute 2 global'
    ];
}

function convert_xml_to_woocommerce($products, $product_type){
    $variables = [
        'color' => [],
        'size' => []
    ];
    if($is_variable){
        foreach ($p as $product) {
            if(isset($product->COLOR_DESCRIPTION))
                $variables['color'][] = (string)$product->COLOR_DESCRIPTION;
            
            if(isset($product->SIZE)){
                if(strlen($product->SIZE)){
                    $variables['size'][] = (string)$product->SIZE;
                }
            }
        }
    }
    $p = $products[0];
    $category_string = $p->CATEGORY_LEVEL_1;
    $category_string .= $p->CATEGORY_LEVEL_2 ? ' > ' . $p->CATEGORY_LEVEL_2 : '';
    $category_string .= $p->CATEGORY_LEVEL_3 ? ' > ' . $p->CATEGORY_LEVEL_3 : '';
    $category_string .= $p->CATEGORY_LEVEL_4 ? ' > ' . $p->CATEGORY_LEVEL_4 : '';
    $images = $p->IMAGE_URL . ',' . $p->THUMBNAIL_URL;
    foreach($p->DIGITAL_ASSETS->DIGITAL_ASSET as $el){
        $images .= ',' . $el->URL;
    };
    switch ($product_type) {
        case 'variable':
            $variable = [];
            $variable[] = [
                'variable',
                (string)$p->PRODUCT_BASE_NUMBER,
                (string)$p->PRODUCT_NAME,
                1,
                0,
                '',
                'visible',
                (string)$p->SHORT_DESCRIPTION,
                (string)$p->LONG_DESCRIPTION,
                1,
                9999,
                (string)$p->PACKAGING_CARTON->WEIGHT,
                (string)$p->PACKAGING_CARTON->LENGTH,
                (string)$p->PACKAGING_CARTON->WIDTH,
                (string)$p->PACKAGING_CARTON->HEIGHT,
                100,
                110,
                (string)$category_string,
                (string)$images,
                "Color",
                implode(', ', $variables['color']),
                1,
                1,
                "Size",
                implode(', ', $variables['size']),
                1,
                1
            ];
            for($i = 1; $i < count($products); $i++){
                $main_product = $products[0];
                $p = $products[$i];
                $variable[] = [
                    'variation',
                    (string)$p->PRODUCT_NUMBER,
                    (string)$p->PRODUCT_NAME,
                    1,
                    0,
                    (string)$main_product->PRODUCT_BASE_NUMBER,
                    'visible',
                    (string)$p->SHORT_DESCRIPTION,
                    (string)$p->LONG_DESCRIPTION,
                    1,
                    9999,
                    (string)$p->PACKAGING_CARTON->WEIGHT,
                    (string)$p->PACKAGING_CARTON->LENGTH,
                    (string)$p->PACKAGING_CARTON->WIDTH,
                    (string)$p->PACKAGING_CARTON->HEIGHT,
                    100,
                    110,
                    (string)$category_string,
                    (string)$images,
                    "Color",
                    (string)$p->COLOR_DESCRIPTION,
                    1,
                    1,
                    "Size",
                    (string)$p->SIZE,
                    1,
                    1
                ];
            }
            return $variable;
            break;
        case 'simple':
            return [
                'simple',
                (string)$p->PRODUCT_BASE_NUMBER,
                (string)$p->PRODUCT_NAME,
                1,
                0,
                '',
                'visible',
                (string)$p->SHORT_DESCRIPTION,
                (string)$p->LONG_DESCRIPTION,
                1,
                9999,
                (string)$p->PACKAGING_CARTON->WEIGHT,
                (string)$p->PACKAGING_CARTON->LENGTH,
                (string)$p->PACKAGING_CARTON->WIDTH,
                (string)$p->PACKAGING_CARTON->HEIGHT,
                100,
                110,
                (string)$category_string,
                (string)$images,
                "",
                "",
                "",
                "",
                "",
                ""
            ];
            break;
        default:
            return;
    }
}

function link_attributes_to_product($variable_product_id, $attributes){
    foreach ($attributes['value'] as $value) {
        $variation = new WC_Product_Variation();
        $variation->set_regular_price(100);
        $variation->set_parent_id($variable_product_id);

        $variation->set_attributes(array(
            $attributes['label'] => $value
        ));
        $variation->save();
    }
}

function does_product_exists($sku){
    global $wpdb;

    $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

    if ( $product_id ) return $product_id;

    return false;
}