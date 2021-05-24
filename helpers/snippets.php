<?php 
function filter_empty_array_entries($var){
    return ($var !== NULL && $var !== FALSE && $var !== "");
}