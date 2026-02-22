<?php 
require_once __DIR__ . '/../../middleware/admin_only.php';
$id=(int)($_GET['id']??0);
$u=$pdo->prepare(
  "SELECT * 
  FROM users 
  WHERE id=?"
); 
$u->execute([$id]); 
$user=$u->fetch(); 
if(!$user) exit('User not found');
$msg=''; 
$roles=$pdo->query(
  "SELECT id,name 
  FROM roles 
  ORDER BY name"
)->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();
  $name=trim($_POST['full_name']); 
  $email=trim($_POST['email']); 
  $role_id=(int)$_POST['role_id']; 
  $active=isset($_POST['is_active'])?1:0;
  $pdo->prepare(
    "UPDATE users 
    SET full_name=?,email=?,role_id=?,is_active=? 
    WHERE id=?")->execute([$name,$email,$role_id,$active,$id]);
  if(!empty($_POST['password'])){ 
    $pdo->prepare(
      "UPDATE users 
      SET password_hash=? 
      WHERE id=?"
    )->execute([password_hash($_POST['password'],PASSWORD_BCRYPT),$id]); 
  }
  audit(
    $pdo,
    $_SESSION['user']['id'],
    'UPDATE',
    'users',
    $id,
    ['email'=>$email]
  ); 
  $msg='User updated.';
}
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; 
?>
<div class="card glass">
  <div class="card-head">
    <h4>Edit User</h4>
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
        <th><label>Full Name</label></th>
        <td><input name="full_name" value="<?= e($user['full_name']) ?>" required></td>
      </tr>
      <tr>
        <th><label>Email</label></th>
        <td><input type="email" name="email" value="<?= e($user['email']) ?>" required></td>
      </tr>
      <tr>
        <th><label>Role</label></th>
        <td>
          <select name="role_id">
            <?php foreach($roles as $r): ?>
              <option value="<?= (int)$r['id'] ?>" <?= $r['id']==$user['role_id']?'selected':'' ?>>
                <?= e(ucfirst($r['name'])) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </td>
      </tr>
      <tr>
        <th><label>Password (leave blank to keep)</label></th>
        <td><input type="password" name="password" minlength="6"></td>
      </tr>
      <tr>
        <th>
          <label><input type="checkbox" name="is_active" <?= $user['is_active']?'checked':'' ?>> Active</label>
        </th>
      </tr>
      <tr>
        <td><button class="btn-primary" style="margin-top:10px;">Update</button></td>
      </tr>
      <tr>
        <td>
          <a class="icon-btn" href="<?= u('users/list.php') ?>" style="margin-left:8px;">
            <i class="ri-arrow-go-back-line"></i>
          </a>
        </td>
      </tr>
    </table>  
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
