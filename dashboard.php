<?php 
$page_title='Dashboard'; 
include __DIR__ . '/partials/header.php'; 
include __DIR__ . '/partials/nav.php'; 
?>
<section class="grid kpis">
  <?php
    $totalItems=(int)$pdo->query(
      "SELECT COUNT(*) c 
      FROM items WHERE is_active=1"
    )->fetch()['c'];
    $lowStock=(int)$pdo->query(
      "SELECT COUNT(*) c 
      FROM items 
      WHERE is_active=1 AND current_qty<=reorder_level"
    )->fetch()['c'];
    $inToday=(float)$pdo->query(
      "SELECT COALESCE(SUM(qty),0) q 
      FROM stock_movements 
      WHERE type='IN' AND DATE(movement_date)=CURDATE()"
    )->fetch()['q'];
    $outToday=(float)$pdo->query(
      "SELECT COALESCE(SUM(qty),0) q 
      FROM stock_movements 
      WHERE type='OUT' AND DATE(movement_date)=CURDATE()"
    )->fetch()['q'];
  ?>
  <div class="card kpi glass">
    <div class="kpi-icon">
      <i class="ri-database-2-line"></i>
    </div>
    <div class="kpi-info">
      <p class="label">Total Items</p>
      <h3><?= $totalItems ?></h3>
    </div>
    <span class="kpi-chip up">Live</span>
  </div>
  <div class="card kpi glass">
    <div class="kpi-icon">
      <i class="ri-alert-line"></i>
    </div>
    <div class="kpi-info">
      <p class="label">Low Stock</p>
      <h3><?= $lowStock ?></h3>
    </div>
    <span class="kpi-chip warn">Check</span>
  </div>
  <div class="card kpi glass">
    <div class="kpi-icon">
      <i class="ri-arrow-down-circle-line"></i>
    </div>
    <div class="kpi-info">
      <p class="label">Inbound Today (KG)</p>
      <h3><?= (int)$inToday ?></h3>
    </div>
    <span class="kpi-chip neutral">KG</span>
  </div>
  <div class="card kpi glass">
    <div class="kpi-icon">
      <i class="ri-arrow-up-circle-line"></i>
    </div>
    <div class="kpi-info">
      <p class="label">Outbound Today (KG)</p>
      <h3><?= (int)$outToday ?></h3>
    </div>
    <span class="kpi-chip neutral">KG</span>
  </div>
</section>
<br>
<section class="grid single">
  <div class="card glass">
    <div class="card-head">
      <h4>Recent Stock Movements</h4>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Item</th>
            <th>Type</th>
            <th>Qty</th>
            <th>Remarks</th>
          </tr>
        </thead>
        <tbody>
          <?php $rows=$pdo->query(
            "SELECT sm.movement_date,i.name item_name,sm.type,sm.qty,sm.remarks 
            FROM stock_movements sm JOIN items i ON i.id=sm.item_id 
            ORDER BY sm.movement_date DESC LIMIT 8"
          )->fetchAll(); 
          foreach($rows as $r): ?>
          <tr class="<?= $r['type']==='IN'?'row-in':'row-out' ?>">
            <td><?= e($r['movement_date']) ?></td>
            <td><?= e($r['item_name']) ?></td>
            <td><?= e($r['type']) ?></td>
            <td><?= e($r['qty']) ?></td>
            <td><?= e($r['remarks']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
