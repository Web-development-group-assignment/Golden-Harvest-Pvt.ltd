<?php
function required($v){ 
    return isset($v) && trim($v) !== '';
}
function is_email($v){ 
    return filter_var($v, FILTER_VALIDATE_EMAIL); 
}
function positive_number($v){ 
    return is_numeric($v) && $v >= 0; 
}
