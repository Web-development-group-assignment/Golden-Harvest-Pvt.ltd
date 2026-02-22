<?php require_login(); ?>
<aside class="sidebar">
  <div class="brand">
    <div class="logo">
      <img src="<?= u('assets/logo.jpg') ?>" alt="logo" style="width:34px;height:34px;border-radius:8px;object-fit:contain;">
    </div>
    <span>Golden Harvest IMS</span>
  </div>
  <nav class="menu">
    <a href="<?= u('dashboard.php') ?>" class="<?= basename($_SERVER['SCRIPT_NAME'])==='dashboard.php'?'active':'' ?>">
      <i class="ri-dashboard-2-line"></i> 
      Dashboard
    </a>
    <a href="<?= u('inventory/list.php') ?>" class="<?= strpos($_SERVER['SCRIPT_NAME'],'inventory')!==false?'active':'' ?>">
      <i class="ri-archive-2-line"></i> 
      Inventory
    </a>
    <a href="<?= u('suppliers/list.php') ?>" class="<?= strpos($_SERVER['SCRIPT_NAME'],'suppliers')!==false?'active':'' ?>">
      <i class="ri-truck-line"></i> 
      Suppliers
    </a>
    <a href="<?= u('stock/inbound.php') ?>" class="<?= strpos($_SERVER['SCRIPT_NAME'],'inbound')!==false?'active':'' ?>">
      <i class="ri-download-2-line"></i> 
      Inbound
    </a>
    <a href="<?= u('stock/outbound.php') ?>" class="<?= strpos($_SERVER['SCRIPT_NAME'],'outbound')!==false?'active':'' ?>">
      <i class="ri-upload-2-line"></i> 
      Outbound
    </a>
    <a href="<?= u('stock/ledger.php') ?>" class="<?= strpos($_SERVER['SCRIPT_NAME'],'ledger')!==false?'active':'' ?>">
      <i class="ri-file-list-3-line"></i> 
      Ledger
    </a>
    <a href="<?= u('reports/low_stock.php') ?>" class="<?= strpos($_SERVER['SCRIPT_NAME'],'reports')!==false?'active':'' ?>">
      <i class="ri-alert-line"></i> 
      Reports
    </a>
    <?php if (is_role('admin')): ?>
      <a href="<?= u('users/list.php') ?>" class="<?= strpos($_SERVER['SCRIPT_NAME'],'users')!==false?'active':'' ?>">
        <i class="ri-user-settings-line"></i> 
        Users
      </a>
    <?php endif; ?>
    <a href="<?= u('logout.php') ?>">
      <i class="ri-logout-circle-line"></i> 
    Logout
    </a>
  </nav>
</aside>
<main class="main">
  <header class="topbar glass">
    <div class="greeting">
      <h1><?= e($page_title ?? 'Inventory Management') ?></h1>
      <p>Welcome, <strong><?= e($_SESSION['user']['name'] ?? 'User') ?></strong>!</p>
    </div>
    <div class="actions" style="text-align: right;">
      <button id="themeToggle" title="Toggle theme" class="icon-btn">
        <i class="ri-sun-line" id="themeIcon"></i>
      </button><br>
      <div class="user-chip"><img src="<?= u('assets/logo.jpg') ?>" alt="brand"/>
      <span><?= e($_SESSION['user']['role'] ?? 'role') ?></span>
      </div>
    </div>
  </header>
  <section class="grid single">
    <div class="card glass" style="padding:0;background:transparent;border:none;box-shadow:none;">
