<?php
function convert_xml_prodinfo($products, $product_type, $compare_file = false, $attributes){
    $compare_file = (array)$compare_file;
    $attributes = (array)$attributes;

    $variables = [
        'color' => [],
        'size' => [],
        'printing_position' => [],

    ];
    if($product_type === 'variable'){
        foreach ($products as $product) {
            if(isset($product->COLOR_DESCRIPTION))
                $variables['color'][] = (string)$product->COLOR_DESCRIPTION;
            
            if(isset($product->SIZE)){
                if(strlen($product->SIZE)){
                    $variables['size'][] = (string)$product->SIZE;
                }
            }
        }
    } else {
    }
    $p = $products[0];
    $category_string = $p->CATEGORY_LEVEL_2;
    $category_string .= strlen($p->CATEGORY_LEVEL_3) > 0 ? ' > ' . $p->CATEGORY_LEVEL_3 : '';
    $category_string .= strlen($p->CATEGORY_LEVEL_4) > 0 ? ' > ' . $p->CATEGORY_LEVEL_4 : '';
    $images = $p->IMAGE_URL;
    if(isset($p->DIGITAL_ASSETS)){
        foreach($p->DIGITAL_ASSETS->DIGITAL_ASSET as $el){
            $images .= ',' . $el->URL;
        };
    }
    switch ($product_type) {
        case 'variable':
            $variable = [];
            $variable[] = [
                'variable', // Type
                (string)$p->PRODUCT_NUMBER, // SKU
                (string)$p->PRODUCT_NAME, // Name
                1, // Published
                '', // Parent
                '', // Is featured
                'visible', // Visibility in catalog
                (string)$p->SHORT_DESCRIPTION, // Short description
                (string)$p->LONG_DESCRIPTION, // Description
                1, // In stock
                $compare_file[(string)$p->PRODUCT_NUMBER][0]->QUANTITY, // Stock
                (string)$p->PACKAGING_CARTON->WEIGHT, // Weight
                (string)$p->PACKAGING_CARTON->LENGTH, // Length
                (string)$p->PACKAGING_CARTON->WIDTH, // Width
                (string)$p->PACKAGING_CARTON->HEIGHT, // Height
                100, // Sale price
                110, // Regular price
                (string)$category_string, // Categories
                (string)$images, // Images
                "Color", // Attr name
                implode(', ', $variables['color']), // Attr values
                1, // Attr visible
                1, // Attr global
                "Size", // Attr name
                implode(', ', $variables['size']), // Attr values
                1, // Attr visible
                1 // Attr global
            ];
            for($i = 1; $i < count($products); $i++){
                $parent_product = $products[0];
                $p = $products[$i];
                $variable[] = [
                    'variation', // Type
                    (string)$p->PRODUCT_NUMBER, // SKU
                    (string)$p->PRODUCT_NAME, // Name
                    1, // Published
                    (string)$parent_product->PRODUCT_NUMBER, // Parent
                    0, // Is featured
                    'visible', // Visibility in catalog
                    (string)$p->SHORT_DESCRIPTION, // Short description
                    (string)$p->LONG_DESCRIPTION, // Description
                    1, // In stock
                    $compare_file[(string)$p->PRODUCT_NUMBER][0]->QUANTITY, // Stock
                    (string)$p->PACKAGING_CARTON->WEIGHT, // Weight
                    (string)$p->PACKAGING_CARTON->LENGTH, // Length
                    (string)$p->PACKAGING_CARTON->WIDTH, // Width
                    (string)$p->PACKAGING_CARTON->HEIGHT, // Height
                    100, // Sale price
                    110, // Regular price
                    (string)$category_string, // Categories
                    (string)$p->IMAGE_URL, // Images
                    "Color", // Attr name
                    (string)$p->COLOR_DESCRIPTION, // Attr values
                    1, // Attr visible
                    1, // Attr global
                    "Size", // Attr name
                    (string)$p->SIZE, // Attr values
                    1, // Attr visible
                    1 // Attr global
                ];
            }
            return $variable;
            break;
        case 'simple':
            return [
                'simple', // Type
                (string)$p->PRODUCT_NUMBER, // SKU
                (string)$p->PRODUCT_NAME, // Name
                1, // Published
                '', // Parent
                0, // Is featured
                'visible', // Visibility in catalog
                (string)$p->SHORT_DESCRIPTION, // Short description
                (string)$p->LONG_DESCRIPTION, // Description
                1, // In stock
                $compare_file[(string)$p->PRODUCT_NUMBER][0]->QUANTITY, // Stock
                (string)$p->PACKAGING_CARTON->WEIGHT, // Weight
                (string)$p->PACKAGING_CARTON->LENGTH, // Length
                (string)$p->PACKAGING_CARTON->WIDTH, // Width
                (string)$p->PACKAGING_CARTON->HEIGHT, // Height
                100, // Sale price
                110, // Regular price
                (string)$category_string, // Categories
                (string)$images, // Images
                "", // Attr name
                "", // Attr values
                "", // Attr visible
                "", // Attr global
                "", // Attr name
                "", // Attr values
                "", // Attr visible
                "" // Attr global
            ];
            break;
        default:
            return;
    }
}

function convert_xml_usb($products, $product_type){
    $variables = [
        'color' => []
    ];
    if($product_type === 'variable'){
        foreach ($products as $product) {
            if(isset($product->colors))
                $variables['color'][] = (string)$product->colors;
        }
    }
    $p = $products[0];
    $category_string = $p->maincategory;
    $category_string .= strlen($p->subcategory) > 0 ? ' > ' . $p->subcategory : '';
    $images = $p->IMAGE_URL;
    $images .= $p->IMAGE_PRINT_POSITIONS ? ',' . $p->IMAGE_PRINT_POSITIONS : '';
    switch ($product_type) {
        case 'variable':
            $variable = [];
            $variable[] = [
                'variable', // Type
                (string)$p->code, // SKU
                (string)$p->article, // Name
                1, // Published
                '', // Parent
                '', // Is featured
                'visible', // Visibility in catalog
                (string)$p->DESCRIPTIONS->it_short, // Short description
                (string)$p->DESCRIPTIONS->it_long, // Description
                1, // In stock
                9999, // Stock
                '', // Weight
                '', // Length
                '', // Width
                '', // Height
                0, // Sale price
                0, // Regular price
                (string)$category_string, // Categories
                (string)$images, // Images
                "Color", // Attr name
                implode(', ', $variables['color']), // Attr values
                1, // Attr visible
                1, // Attr global
                "", // Attr name
                '', // Attr values
                '', // Attr visible
                '' // Attr global
            ];
            for($i = 1; $i < count($products); $i++){
                $parent_product = $products[0];
                $p = $products[$i];
                $variable[] = [
                    'variation', // Type
                    (string)$p->article, // SKU
                    (string)$p->article, // Name
                    1, // Published
                    (string)$parent_product->sku, // Parent
                    0, // Is featured
                    'visible', // Visibility in catalog
                    (string)$p->DESCRIPTIONS->it_short, // Short description
                    (string)$p->DESCRIPTIONS->it_long, // Description
                    1, // In stock
                    9999, // Stock
                    '', // Weight
                    '', // Length
                    '', // Width
                    '', // Height
                    0, // Sale price
                    0, // Regular price
                    (string)$category_string, // Categories
                    (string)$images, // Images
                    "Color", // Attr name
                    (string)$p->colors, // Attr values
                    1, // Attr visible
                    1, // Attr global
                    "", // Attr name
                    "", // Attr values
                    "", // Attr visible
                    "" // Attr global
                ];
            }
            return $variable;

            break;
        case 'simple':
            return [
                'simple', // Type
                (string)$p->code, // SKU
                (string)$p->article, // Name
                1, // Published
                '', // Parent
                0, // Is featured
                'visible', // Visibility in catalog
                (string)$p->DESCRIPTIONS->it_short, // Short description
                (string)$p->DESCRIPTIONS->it_long, // Description
                1, // In stock
                '9999', // Stock
                '', // Weight
                '', // Length
                '', // Width
                '', // Height
                0, // Sale price
                0, // Regular price
                (string)$category_string, // Categories
                (string)$images, // Images
                "", // Attr name
                "", // Attr values
                "", // Attr visible
                "", // Attr global
                "", // Attr name
                "", // Attr values
                "", // Attr visible
                "" // Attr global
            ];
            break;
        default:
            return;
    }
}