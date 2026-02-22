<?php require_once __DIR__ . '/../../middleware/manager_or_admin.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();
  $item_id=(int)$_POST['item_id']; 
  $qty=(float)$_POST['qty']; 
  $unit_price=(float)$_POST['unit_price']; 
  $remarks=trim($_POST['remarks']);
  if($qty<=0 || $unit_price<0){ 
    $msg='Invalid quantity or price.'; 
  }
  else{
    $pdo->beginTransaction();
    try{
      $pdo->prepare(
        "INSERT INTO stock_movements (item_id,type,qty,reference_type,remarks,created_by) 
        VALUES ( ?, 'IN', ?, 'purchase', ?, ?)"
      )->execute([$item_id,$qty,$remarks,$_SESSION['user']['id']]);
      $pdo->prepare(
        "UPDATE items 
        SET current_qty=current_qty+?, cost_price=? 
        WHERE id=?"
      )->execute([$qty,$unit_price,$item_id]);
      audit(
        $pdo,
        $_SESSION['user']['id'],
        'CREATE',
        'stock_movements',
        $pdo->lastInsertId(),
        ['type'=>'IN','qty'=>$qty]
      );
      $pdo->commit(); $msg='Inbound recorded.';
    }
    catch(Exception $e){ 
      $pdo->rollBack(); 
      $msg='Error: '.$e->getMessage(); 
    }
  }
}
$items=$pdo->query(
  "SELECT id, sku, name 
  FROM items 
  WHERE is_active=1 
  ORDER BY name"
)->fetchAll();
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; 
?>
<div class="card glass">
  <div class="card-head">
    <h4>Inbound (Purchase/GRN)</h4>
  </div>
  <?php if($msg): ?>
    <div class="info" style="background:#e0f2fe;color:#075985;padding:8px;border-radius:8px;margin-bottom:8px;">
      <?= e($msg) ?>
    </div>
  <?php endif; ?>
  <form method="post">
    <?php csrf_field(); ?>
    <table>
      <tr>
        <th><label>Item</label></th>
        <td>
          <select name="item_id" required>
          <option value="">-- Select --</option>
          <?php foreach($items as $it): ?>
            <option value="<?= (int)$it['id'] ?>">
              <?= e($it['sku'].' - '.$it['name']) ?>
            </option>
          <?php endforeach; ?>
          </select>
        </td>
      </tr>
      <tr>
        <th><label>Quantity</label></th>
        <td><input type="number" step="0.001" name="qty" required></td>
      </tr>
      <tr>
        <th><label>Unit Price</label></th>
        <td><input type="number" step="0.01" name="unit_price" required></td>
      </tr>
      <tr>
        <th><label>Remarks</label></th>
        <td><input name="remarks" placeholder="Invoice / Lot info"></td>
      </tr>
      <tr>
        <td colspan="2">
          <button class="btn-primary" style="margin-top:10px;">Record Inbound</button>
        </td>
      </tr>
      <tr>
        <td>
          <a class="icon-btn" href="<?= u('./inventory/list.php') ?>" style="margin-left:8px;">
            <i class="ri-arrow-go-back-line"></i>
          </a>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
