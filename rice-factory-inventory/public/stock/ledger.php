<?php require_once __DIR__ . '/../../middleware/auth_only.php';
$item_id=(int)($_GET['item_id']??0);
$items=$pdo->query(
  "SELECT id,name 
  FROM items 
  WHERE is_active=1 
  ORDER BY name"
)->fetchAll();
$movs=[]; 
if($item_id){ 
  $st=$pdo->prepare(
    "SELECT sm.*, u.full_name uname 
    FROM stock_movements sm LEFT JOIN users u ON u.id=sm.created_by 
    WHERE sm.item_id=? 
    ORDER BY movement_date DESC, id DESC"
  ); 
  $st->execute([$item_id]); 
  $movs=$st->fetchAll(); 
}
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; 
?>
<div class="card glass">
  <div class="card-head">
    <h4>Stock Ledger</h4>
  </div>
  <form method="get" class="form">
    <label>Item</label>
    <select name="item_id" onchange="this.form.submit()">
      <option value="">-- Choose Item --</option>
      <?php foreach($items as $it): ?>
      <option value="<?= (int)$it['id'] ?>" 
      <?= $item_id==$it['id']?'selected':'' ?>>
      <?= e($it['name']) ?>
    </option>
    <?php endforeach; ?>
    </select>
  </form>
  <?php if($item_id): ?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Type</th>
          <th>Qty</th>
          <th>Ref</th>
          <th>Remarks</th>
          <th>By</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($movs as $m): ?>
        <tr class="<?= $m['type']==='IN'?'in':'out' ?>">
          <td><?= e($m['movement_date']) ?></td>
          <td><?= e($m['type']) ?></td>
          <td><?= e($m['qty']) ?></td>
          <td><?= e(($m['reference_type']??'').'#'.($m['reference_id']??'')) ?></td>
          <td><?= e($m['remarks']) ?></td>
          <td><?= e($m['uname']??$m['created_by']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
