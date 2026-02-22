<?php 
require_once __DIR__ . '/../config/config.php';
if(!empty($_SESSION['user'])){ 
    audit(
        $pdo,
        $_SESSION['user']['id'],
        'LOGOUT',
        'users',
        $_SESSION['user']['id'],
        null); }
logout();
redirect('index.php');
