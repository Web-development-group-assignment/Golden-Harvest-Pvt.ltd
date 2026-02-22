<?php 
require_once __DIR__ . '/../../middleware/manager_or_admin.php';
$id=(int)($_GET['id']??0);
$s=$pdo->prepare(
  "SELECT * 
  FROM suppliers 
  WHERE id=?"
); 
$s->execute([$id]); 
$sp=$s->fetch(); 
if(!$sp) exit('Supplier not found');
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();
  $name=trim($_POST['name']); 
  $person=trim($_POST['contact_person']); 
  $phone=trim($_POST['phone']); 
  $email=trim($_POST['email']); 
  $addr=trim($_POST['address']); 
  $active=isset($_POST['is_active'])?1:0;
  $st=$pdo->prepare(
    "UPDATE suppliers 
    SET name=?,contact_person=?,phone=?,email=?,address=?,is_active=? 
    WHERE id=?"
  );
  $st->execute([$name,$person,$phone,$email,$addr,$active,$id]);
  audit(
    $pdo,
    $_SESSION['user']['id'],
    'UPDATE',
    'suppliers',
    $id,
    ['name'=>$name]);
  $msg='Supplier updated.';
}
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; 
?>
<div class="card glass">
  <div class="card-head">
    <h4>Edit Supplier</h4>
  </div>
<?php if($msg): ?>
  <div class="info" style="background:#dcfce7;color:#166534;padding:8px;border-radius:8px;margin-bottom:8px;">
    <?= e($msg) ?></div>
    <?php endif; ?>
  <form method="post">
    <?php csrf_field(); ?>
    <table>
      <tr>
        <th><label>Name</label></th>
        <td><input name="name" value="<?= e($sp['name']) ?>" required></td>
      </tr>
      <tr>
        <th><label>Contact Person</label></th>
        <td><input name="contact_person" value="<?= e($sp['contact_person']) ?>"></td>
      </tr>
      <tr>
        <th><label>Phone</label></th>
        <td><input name="phone" value="<?= e($sp['phone']) ?>"></td>
      </tr>
      <tr>
        <th><label>Email</label></th>
        <td><input type="email" name="email" value="<?= e($sp['email']) ?>"></td>
      </tr>
      <tr>
        <th><label>Address</label></th>
        <td><input name="address" value="<?= e($sp['address']) ?>"></td>
      </tr>
      <tr>
        <th>
          <label>
            <input type="checkbox" name="is_active" <?= $sp['is_active']?'checked':'' ?>> 
            Active
          </label>
        </th>
      </tr>
      <tr>
        <td colspan="2">
          <button class="btn-primary" style="margin-top:10px;">Update</button>
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
