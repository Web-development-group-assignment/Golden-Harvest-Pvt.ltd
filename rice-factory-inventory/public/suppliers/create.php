<?php require_once __DIR__ . '/../../middleware/manager_or_admin.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();
  $name=trim($_POST['name']); 
  $person=trim($_POST['contact_person']); 
  $phone=trim($_POST['phone']); 
  $email=trim($_POST['email']); 
  $addr=trim($_POST['address']);
  $st=$pdo->prepare(
    "INSERT INTO suppliers (name,contact_person,phone,email,address) 
    VALUES (?,?,?,?,?)"
  );
  $st->execute([$name,$person,$phone,$email,$addr]);
  audit(
    $pdo,
    $_SESSION['user']['id'],
    'CREATE',
    'suppliers',
    $pdo->lastInsertId(),
    ['name'=>$name]
  );
  $msg='Supplier added.';
}
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; 
?>
<div class="card glass">
  <div class="card-head">
    <h4>Add Supplier</h4>
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
        <th><label>Name</label></th>
        <td><input name="name" required></td>
      </tr>
      <tr>
        <th><label>Contact Person</label></th>
        <td><input name="contact_person"></td>
      </tr>
      <tr>
        <th><label>Phone</label></th>
        <td><input name="phone"></td>
      </tr>
      <tr>
        <th><label>Email</label></th>
        <td><input type="email" name="email"></td>
      </tr>
      <tr>
        <th><label>Address</label></th>
        <td><input name="address"></td>
      </tr>
      <tr>
        <td>
          <button class="btn-primary" style="margin-top:10px;">Save</button>
        </td>
      </tr>
      <tr>
        <td>
          <a class="icon-btn" href="<?= u('suppliers/list.php') ?>" style="margin-left:8px;">
            <i class="ri-arrow-go-back-line"></i>
          </a>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
