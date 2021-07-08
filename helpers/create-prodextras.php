<?php
function create_prodextras($prod, $index){
    $rows = [];
    foreach ($prod['print_positions'] as $position) {
        // Position row
        $group = [
            $index, // Id
            'group', // Group or field
            0, // Order
            $position['name'], // Group title
            '', // Description
            'table', // Layout
            serialize(['sku' => $prod['sku']]),
            '', //field label
            '', // field type
            '', // field price
            '', // field options
            '', // first field options
            '', // field minchecks
            '', // field maxchecks
            '', // children
            '', // product layout
            '', // products quantities
            '', // allow none
            '', // number columns
            '', // hide labels
            '', // allow multiple
            '', // select placeholder
            '', // min products
            '', // max products
            '', // child discounts
            '', // discount type
            '', // field required
            '', // field flatrate
            '', // field percentage
            '', // field minchars
            '', // field maxchars
            '', // per character
            '', // field freechars 
            '', // field alphanumeric
            '', // field alphanumeric charge
            '', // field minval
            '', // field maxval
            '', // multiply
            '', // min date today
            '', // field mindate
            '', // field maxdate
            '', // field color
            '', // field width
            '', // field show
            '', // field palettes
            '', // field default
            '', // field default hidden
            '', // field image
            '', // field description
            'hide', // condition action
            'all', // condition match
            '', // condition field
            '', // condition rule
            '', // condition value
            '', // variation field
            '', // formula
            '', // formula action
            '', // formula round
            '', // decimal places
            '', // field rows
            '', // multiple uploads
            '', // max files
            '', // multiply price
            '', // hidden calculation
            '', // price visibility
            '', // option price visibility
            '', // conditions
        ];
        $techniques_serialized = array_map(function($elem){
            return [
                'image' => '',
                'value' => $elem['name'],
                'price' => ''
            ];
        }, $position['techniques']);
        $techniques_serialized = serialize($techniques_serialized);
        $incremental_index = 0;
        $tech_index = $incremental_index;
        // Print techniques row
        $tech_row = [
            'pewc_group_' . $index . '_' . $tech_index, // Id
            'field', // Group or field
            $incremental_index, // Order
            '', // Group title
            '', // Description
            '', // Layout
            $index, // Group ID
            'Tipo di stampa', //field label
            'select', // field type
            '', // field price
            $techniques_serialized, // field options
            '', // first field options
            '', // field minchecks
            '', // field maxchecks
            '', // children
            'checkboxes', // product layout
            'independent', // products quantities
            '', // allow none
            '3', // number columns
            '', // hide labels
            '', // allow multiple
            '', // select placeholder
            '', // min products
            '', // max products
            '', // child discounts
            'fixed', // discount type
            '', // field required
            '', // field flatrate
            '', // field percentage
            '', // field minchars
            '', // field maxchars
            '', // per character
            '', // field freechars 
            '', // field alphanumeric
            '', // field alphanumeric charge
            '', // field minval
            '', // field maxval
            '', // multiply
            '', // min date today
            '', // field mindate
            '', // field maxdate
            '', // field color
            '', // field width
            '', // field show
            '', // field palettes
            '', // field default
            '', // field default hidden
            '', // field image
            '', // field description
            'hide', // condition action
            'all', // condition match
            '', // condition field
            '', // condition rule
            '', // condition value
            '', // variation field
            '', // formula
            'no-action', // formula action
            'no-rounding', // formula round
            '', // decimal places
            '', // field rows
            '', // multiple uploads
            '1', // max files
            '', // multiply price
            '', // hidden calculation
            'visible', // price visibility
            'visible', // option price visibility
            '', // conditions
        ];
        $incremental_index++;

        $height = [
            'pewc_group_' . $index . '_' . $incremental_index, // Id
            'field', // Group or field
            $incremental_index, // Order
            '', // Group title
            '', // Description
            '', // Layout
            $index, // Group ID
            'Altezza stampa', //field label
            'number', // field type
            '', // field price
            '', // field options
            '', // first field options
            '', // field minchecks
            '', // field maxchecks
            '', // children
            'checkboxes', // product layout
            'independent', // products quantities
            '', // allow none
            '3', // number columns
            '', // hide labels
            '', // allow multiple
            '', // select placeholder
            '', // min products
            '', // max products
            '', // child discounts
            'fixed', // discount type
            '', // field required
            '', // field flatrate
            '', // field percentage
            '', // field minchars
            '', // field maxchars
            '', // per character
            '', // field freechars 
            '', // field alphanumeric
            '', // field alphanumeric charge
            '', // field minval
            $position['max_height'], // field maxval
            '', // multiply
            '', // min date today
            '', // field mindate
            '', // field maxdate
            '', // field color
            '', // field width
            '', // field show
            '', // field palettes
            $position['max_height'], // field default
            $position['max_height'], // field default hidden
            '', // field image
            '', // field description
            'hide', // condition action
            'all', // condition match
            '', // condition field
            '', // condition rule
            '', // condition value
            '', // variation field
            '', // formula
            'no-action', // formula action
            'no-rounding', // formula round
            '', // decimal places
            '', // field rows
            '', // multiple uploads
            '1', // max files
            '', // multiply price
            '', // hidden calculation
            'visible', // price visibility
            'visible', // option price visibility
            '', // conditions
        ];
        $incremental_index++;
        $width = [
            'pewc_group_' . $index . '_' . $incremental_index, // Id
            'field', // Group or field
            $incremental_index, // Order
            '', // Group title
            '', // Description
            '', // Layout
            $index, // Group ID
            'Larghezza stampa', //field label
            'number', // field type
            '', // field price
            '', // field options
            '', // first field options
            '', // field minchecks
            '', // field maxchecks
            '', // children
            'checkboxes', // product layout
            'independent', // products quantities
            '', // allow none
            '3', // number columns
            '', // hide labels
            '', // allow multiple
            '', // select placeholder
            '', // min products
            '', // max products
            '', // child discounts
            'fixed', // discount type
            '', // field required
            '', // field flatrate
            '', // field percentage
            '', // field minchars
            '', // field maxchars
            '', // per character
            '', // field freechars 
            '', // field alphanumeric
            '', // field alphanumeric charge
            '', // field minval
            $position['max_width'], // field maxval
            '', // multiply
            '', // min date today
            '', // field mindate
            '', // field maxdate
            '', // field color
            '', // field width
            '', // field show
            '', // field palettes
            $position['max_width'], // field default
            $position['max_width'], // field default hidden
            '', // field image
            '', // field description
            'hide', // condition action
            'all', // condition match
            '', // condition field
            '', // condition rule
            '', // condition value
            '', // variation field
            '', // formula
            'no-action', // formula action
            'no-rounding', // formula round
            '', // decimal places
            '', // field rows
            '', // multiple uploads
            '1', // max files
            '', // multiply price
            '', // hidden calculation
            'visible', // price visibility
            'visible', // option price visibility
            '', // conditions
        ];
        $incremental_index++;
        
        $colors_row = [];
        for ($j=0; $j < count($position['techniques']); $j++) { 
            $elem = $position['techniques'][$j];
            $serialized_colors = [];
            for($k = 1; $k <= $elem['max_colors']; $k++){
                $serialized_colors[] = [
                    'image' => '',
                    'value' => $k,
                    'price' => ''
                ];
            };
            $serialized_colors = serialize($serialized_colors);
            $color = [
                'pewc_group_' . $index . '_' . $incremental_index, // Id
                'field', // Group or field
                $incremental_index, // Order
                '', // Group title
                '', // Description
                '', // Layout
                $index, // Group ID
                'Colori', //field label
                'select', // field type
                '', // field price
                $serialized_colors, // field options
                '', // first field options
                '', // field minchecks
                '', // field maxchecks
                '', // children
                'checkboxes', // product layout
                'independent', // products quantities
                '', // allow none
                '3', // number columns
                '', // hide labels
                '', // allow multiple
                '', // select placeholder
                '', // min products
                '', // max products
                '', // child discounts
                'fixed', // discount type
                '', // field required
                '', // field flatrate
                '', // field percentage
                '', // field minchars
                '', // field maxchars
                '', // per character
                '', // field freechars 
                '', // field alphanumeric
                '', // field alphanumeric charge
                '', // field minval
                '', // field maxval
                '', // multiply
                '', // min date today
                '', // field mindate
                '', // field maxdate
                '', // field color
                '', // field width
                '', // field show
                '', // field palettes
                '', // field default
                '', // field default hidden
                '', // field image
                '', // field description
                'show', // condition action
                'all', // condition match
                serialize(['pewc_group_' . $index . '_' . $tech_index]), // condition field
                serialize(['is']), // condition rule
                serialize([$elem['name']]), // condition value
                '', // variation field
                '', // formula
                'no-action', // formula action
                'no-rounding', // formula round
                '', // decimal places
                '', // field rows
                '', // multiple uploads
                '1', // max files
                '', // multiply price
                '', // hidden calculation
                'visible', // price visibility
                'visible', // option price visibility
                '', // conditions
            ];
            $incremental_index++;
            $colors_row[] = $color;
        }
        /* $colors_row = array_map(function($elem) use ($index, $incremental_index, $tech_index){
            // Colors row
            
            return $color;
        }, $position['techniques']); */

        $results = [$group, $tech_row, $height, $width];
        foreach ($colors_row as $color) {
            $results[] = $color;
        }
        $rows[] = $results;
        $index++;
    }
    return [$rows, $index];
}