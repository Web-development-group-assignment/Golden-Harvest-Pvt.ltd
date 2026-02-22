<?php 
require_once __DIR__ . '/../../middleware/admin_only.php';
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
  $pass=$_POST['password'];
  $st=$pdo->prepare(
    "INSERT INTO users (full_name,email,password_hash,role_id) 
    VALUES (?,?,?,?)"
  ); 
  $st->execute([$name,$email,password_hash($pass,PASSWORD_BCRYPT),$role_id]);
  audit($pdo,$_SESSION['user']['id'],'CREATE','users',$pdo->lastInsertId(),['email'=>$email]); $msg='User created.';
}
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; 
?>
<div class="card glass">
  <div class="card-head">
    <h4>Add User</h4>
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
        <td><input name="full_name" required></td>
      </tr>
      <tr>
        <th><label>Email</label></th>
        <td><input type="email" name="email" required></td>
      </tr>
      <tr>
        <th><label>Role</label></th>
        <td>
          <select name="role_id" required>
            <?php foreach($roles as $r): ?>
            <option value="<?= (int)$r['id'] ?>">
              <?= e(ucfirst($r['name'])) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </td>
      </tr>
      <tr>
        <th><label>Password</label></th>
        <td><input type="password" name="password" minlength="6" required></td>
      </tr>
      <tr>
        <td colspan="2">
          <button class="btn-primary" style="margin-top:10px;">Create</button>
        </td>
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

