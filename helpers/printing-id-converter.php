<?php
function convert_printing_id($id){
    $url = OUTPUT_PATH . 'printing-id.json';
    $results = file_get_contents($url);
    $results = json_decode($results);
    $printing_attr = array_filter($results, function($elem) use ($id){
        return $elem->id == $id ? true : false;
    });
    if(count($printing_attr) > 0){
        $printing_attr = array_values($printing_attr);
        $printing_attr = $printing_attr[0];
        $text = array_filter($printing_attr->NAME, function($elem){
            return $elem->language == 'IT' ? true : false;
        });
        if(count($text) > 0){
            $text = array_values($text);
            return $text[0]->text;
        }
    }
}