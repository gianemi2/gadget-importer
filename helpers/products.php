<?php

function create_simple_product($data){
    $product = new WC_Product();
    $product->set_name($data['name']);
    $product->set_description($data['description']);
    $product->set_sku($data['sku']);
    $product->set_stock($data['stock']);

    $product->save();
}

function create_variable_product($data, $attributes){
    $product = new WC_Product_Variable();
    $product->set_name($data['name']);
    $product->set_description($data['description']);
    $product->set_sku($data['sku']);
    $product->set_stock($data['stock']);

    $product->set_attributes(array($attributes));
    $id = $product->save();

    return $id;
}

function create_attributes($attributes){
    $attribute = new WC_Product_Attribute();
    $attribute->set_id(10);
    $attribute->set_name($attributes['label']);
    $attribute->set_options($attributes['value']);
    $attribute->set_position( 0 );
    $attribute->set_visible( 1 );
    $attribute->set_variation( 1 );

    return $attribute;
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