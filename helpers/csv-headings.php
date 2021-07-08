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
        case 'usb':
            return [
                'offer_name',
                'rule_on',
                'product_id',
                'category_id',
                'check_on',
                'min',
                'max',
                'discount_type',
                'value',
                'max_discount',
                'allow_roles',
                'allow_membership_plans',
                'from_date',
                'to_date',
                'adjustment',
                'email_ids',
                'prev_order_count',
                'prev_order_total_amt',
                'repeat_rule'
            ];
            break;
        case 'prodextra': 
            return [
                'id', // Id
                'type', // Group or field
                'order', // Order
                'group_title', // Group title
                'group_description', // Description
                'group_layout', // Layout
                'group_id', // Group ID
                'field_label', //field label
                'field_type', // field type
                'field_price', // field price
                'field_options', // field options
                'first_field_empty', // first field options
                'field_minchecks', // field minchecks
                'field_maxchecks', // field maxchecks
                'child_products', // children
                'products_layout', // product layout
                'products_quantities', // products quantities
                'allow_none', // allow none
                'number_columns', // number columns
                'hide_labels', // hide labels
                'allow_multiple', // allow multiple
                'select_placeholder', // select placeholder
                'min_products', // min products
                'max_products', // max products
                'child_discount', // child discounts
                'discount_type', // discount type
                'field_required', // field required
                'field_flatrate', // field flatrate
                'field_percentage', // field percentage
                'field_minchars', // field minchars
                'field_maxchars', // field maxchars
                'per_character', // per character
                'field_freechars', // field freechars 
                'field_alphanumeric', // field alphanumeric
                'field_alphanumeric_charge', // field alphanumeric charge
                'field_minval', // field minval
                'field_maxval', // field maxval
                'multiply', // multiply
                'min_date_today', // min date today
                'field_mindate', // field mindate
                'field_maxdate', // field maxdate
                'field_color', // field color
                'field_width', // field width
                'field_show', // field show
                'field_palettes', // field palettes
                'field_default', // field default
                'field_default_hidden', // field default hidden
                'field_image', // field image
                'field_description', // field description
                'condition_action', // condition action
                'condition_match', // condition match
                'condition_field', // condition field
                'condition_rule', // condition rule
                'condition_value', // condition value
                'variation_field', // variation field
                'formula', // formula
                'formula_action', // formula action
                'formula_round', // formula round
                'decimal_places', // decimal places
                'field_rows', // field rows
                'multiple_uploads', // multiple uploads
                'max_files', // max files
                'multiply_price', // multiply price
                'hidden_calculation', // hidden calculation
                'price_visibility', // price visibility
                'option_price_visibility', // option price visibility
                'conditions' // conditions
            ];
            break;
        default:
            # code...
            break;
    }

}