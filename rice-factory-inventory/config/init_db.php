<?php
require_once __DIR__.'/config.php';

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) UNIQUE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(120) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS suppliers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  contact_person VARCHAR(120),
  phone VARCHAR(40),
  email VARCHAR(120),
  address TEXT,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sku VARCHAR(50) UNIQUE NOT NULL,
  name VARCHAR(150) NOT NULL,
  category VARCHAR(100),
  unit VARCHAR(20) NOT NULL,
  reorder_level DECIMAL(12,3) DEFAULT 0,
  current_qty DECIMAL(14,3) DEFAULT 0,
  cost_price DECIMAL(12,2) DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS purchases (
  id INT AUTO_INCREMENT PRIMARY KEY,
  supplier_id INT NOT NULL,
  invoice_no VARCHAR(50),
  purchase_date DATE NOT NULL,
  notes TEXT,
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
  FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS purchase_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  purchase_id INT NOT NULL,
  item_id INT NOT NULL,
  qty DECIMAL(14,3) NOT NULL,
  unit_price DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (purchase_id) REFERENCES purchases(id),
  FOREIGN KEY (item_id) REFERENCES items(id)
);

CREATE TABLE IF NOT EXISTS stock_movements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  item_id INT NOT NULL,
  movement_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  type ENUM('IN','OUT') NOT NULL,
  qty DECIMAL(14,3) NOT NULL,
  reference_type VARCHAR(50),
  reference_id INT,
  remarks VARCHAR(255),
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (item_id) REFERENCES items(id),
  FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS audit_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  action VARCHAR(50),
  entity VARCHAR(50),
  entity_id INT,
  payload JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
SQL;

$pdo->exec($sql);

$roles=['admin','manager','clerk'];
foreach($roles as $r){
  $st=$pdo->prepare("SELECT id FROM roles WHERE name=?");
  $st->execute([$r]);
  if(!$st->fetch()){
    $ins=$pdo->prepare("INSERT INTO roles (name) VALUES (?)");
    $ins->execute([$r]);
  }
}

$email='admin@ricefactory.local';
$st=$pdo->prepare("SELECT id FROM users WHERE email=?");
$st->execute([$email]);
if(!$st->fetch()){
  $roleId=$pdo->query("SELECT id FROM roles WHERE name='admin' LIMIT 1")->fetch()['id'];
  $stmt=$pdo->prepare("INSERT INTO users (full_name,email,password_hash,role_id) VALUES (?,?,?,?)");
  $stmt->execute(['System Admin',$email,password_hash('Admin@123',PASSWORD_BCRYPT),$roleId]);
  echo "Admin created: $email / Admin@123";
}
else{ 
  echo "Admin already exists."; 
}
