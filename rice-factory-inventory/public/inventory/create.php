<?php require_once __DIR__ . '/../../middleware/manager_or_admin.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();
  $sku=trim($_POST['sku']); 
  $name=trim($_POST['name']); 
  $category=trim($_POST['category']); 
  $unit=trim($_POST['unit']);
  $reorder=(float)($_POST['reorder_level']??0); 
  $cost=(float)($_POST['cost_price']??0);
  $st=$pdo->prepare("INSERT INTO items (sku,name,category,unit,reorder_level,cost_price) VALUES (?,?,?,?,?,?)");
  $st->execute([$sku,$name,$category,$unit,$reorder,$cost]);
  audit($pdo,$_SESSION['user']['id'],'CREATE','items',$pdo->lastInsertId(),['sku'=>$sku,'name'=>$name]);
  $msg='Item created.';
}
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; 
?>
<div class="card glass">
  <div class="card-head">
    <h4>Add Item</h4>
  </div>
<?php if($msg): ?>
  <div class="info" style="background:#dcfce7;color:#166534;padding:8px;border-radius:8px;margin-bottom:8px;">
    <?= e($msg) ?>
  </div>
  <?php endif; ?>
<form method="post">
  <?php csrf_field(); ?>
  <table>
    <tr>
      <th><label>SKU</label></th>
      <td><input name="sku" required></td>
    </tr>
    <tr>
      <th><label>Name</label></th>
      <td><input name="name" required></td>
    </tr>
    <tr>
      <th><label>Category</label></th>
      <td><input name="category"></td>
    </tr>
    <tr>
      <th><label>Unit</label></th>
      <td><input name="unit" placeholder="kg, bag, ton" required></td>
    </tr>
    <tr>
      <th><label>Reorder Level</label></th>
      <td><input type="number" step="0.001" name="reorder_level" value="0"></td>
    </tr>
    <tr>
      <th><label>Cost Price</label></th>
      <td><input type="number" step="0.01" name="cost_price" value="0"></td>
    </tr>
    <tr>
      <td colspan="2">
        <button class="btn-primary" style="margin-top:10px;">Save</button>
      </td>
    </tr>
    <tr>
      <td>
        <a class="icon-btn" href="<?= u('inventory/list.php') ?>" style="margin-left:8px;">
        <i class="ri-arrow-go-back-line"></i>
        </a>
      </td>
    </tr>
  </table>
</form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
