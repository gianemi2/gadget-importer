<?php
function get_csv_headings($payload){
    switch ($payload) {
        case 'prodinfo':
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
            break;
        
        default:
            # code...
            break;
    }

}