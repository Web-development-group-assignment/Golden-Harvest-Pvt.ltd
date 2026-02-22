<?php
function u($path){ 
    global $ENV; 
    $base=rtrim($ENV['APP_URL'],'/'); 
    $p='/'.ltrim($path,'/'); 
    return $base.$p; 
}
function redirect($path){ 
    header('Location: '.(preg_match('/^https?:/',$path)?$path:u($path))); 
    exit;
}
function e($s){ 
    return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');
}
function json_ok($d=[]){ 
    header('Content-Type:application/json'); 
    echo json_encode(['ok'=>true,'data'=>$d]); 
    exit;
}
function json_error($m,$c=400){ 
    http_response_code($c); 
    header('Content-Type:application/json'); 
    echo json_encode(['ok'=>false,'error'=>$m]); 
    exit; 
}
