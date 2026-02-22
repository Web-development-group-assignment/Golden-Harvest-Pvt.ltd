<?php
session_start();
$ENV=[
  'DB_HOST'=>'127.0.0.1',
  'DB_NAME'=>'rice_inventory',
  'DB_USER'=>'root',
  'DB_PASS'=>'',
  'APP_URL'=>'http://localhost/rice-factory-inventory/public'
];
try{
  $pdo=new PDO(
    "mysql:host={$ENV['DB_HOST']};dbname={$ENV['DB_NAME']};charset=utf8mb4", $ENV['DB_USER'],$ENV['DB_PASS'],[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
    ]
  );
}
catch(Exception $e){ 
  die('DB connection failed: '.$e->getMessage()); 
}
require_once __DIR__.'/../lib/helpers.php';
require_once __DIR__.'/../lib/csrf.php';
require_once __DIR__.'/../lib/auth.php';
