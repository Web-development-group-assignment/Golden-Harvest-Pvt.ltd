<?php
require_once __DIR__ . '/../../middleware/auth_only.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();
  $item_id=(int)$_POST['item_id']; 
  $qty=(float)$_POST['qty']; 
  $remarks=trim($_POST['remarks']);
  $curSt=$pdo->prepare(
    "SELECT current_qty 
    FROM items 
    WHERE id=?"
  ); 
  $curSt->execute([$item_id]); 
  $cur=(float)($curSt->fetch()['current_qty']??0);
  if($qty<=0 || $qty>$cur){ 
    $msg='Invalid quantity or insufficient stock.'; 
  }
  else{
    $pdo->beginTransaction();
    try{
      $pdo->prepare(
        "INSERT INTO stock_movements (item_id,type,qty,reference_type,remarks,created_by) 
        VALUES (?, 'OUT', ?, 'production', ?, ?)"
      )->execute([$item_id,$qty,$remarks,$_SESSION['user']['id']]);
      $pdo->prepare(
        "UPDATE items 
        SET current_qty=current_qty-? 
        WHERE id=?"
      ) ->execute([$qty,$item_id]);
      audit(
        $pdo,
        $_SESSION['user']['id'],
        'CREATE',
        'stock_movements',
        $pdo->lastInsertId(),
        ['type'=>'OUT','qty'=>$qty]
      );
      $pdo->commit(); $msg='Outbound recorded.';
    }
    catch(Exception $e){ 
      $pdo->rollBack(); 
      $msg='Error: '.$e->getMessage(); 
    }
  }
}
$items=$pdo->query(
  "SELECT id, sku, name, current_qty
  FROM items 
  WHERE is_active=1 
  ORDER BY name"
)->fetchAll();
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; 
?>
<div class="card glass">
  <div class="card-head">
    <h4>Outbound (Production/Dispatch)</h4>
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
                <?= e($it['sku'].' - '.$it['name'].' ('.$it['current_qty'].')') ?>
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
        <th><label>Remarks</label></th>
        <td><input name="remarks" placeholder="Batch / Order ref"></td>
      </tr>
      <tr>
        <td colspan="2">
          <button class="btn-primary" style="margin-top:10px;">Record Outbound</button>
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
