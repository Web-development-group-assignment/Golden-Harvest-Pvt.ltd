<?php 
require_once __DIR__ . '/../config/config.php';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();
  $email=trim($_POST['email']??''); 
  $password=$_POST['password']??'';
  if(login(
    $pdo,
    $email,
    $password
  )) redirect('dashboard.php');
  else $error='Invalid credentials or inactive user.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Golden Harvest IMS — Sign in</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">
  <link rel="stylesheet" href="<?= u('assets/dashboard-modern.css') ?>">
</head>
<body data-theme="<?= isset($_COOKIE['imsTheme']) ? e($_COOKIE['imsTheme']) : 'dark' ?>">
  <div class="login-hero"style="margin-left:570px">
    <div class="hero-right">
      <div class="brand"style="margin-right:150px">
        <div class="logo">
          <img src="<?= u('assets/logo.jpg') ?>" alt="logo">
        </div>
      </div>
      <form class="login-card" method="post" style="text-align: center;">
        <?php csrf_field(); ?>
        <h2>Welcome to Golden Harvest Pvt.Ltd</h2>
        <?php if($error): ?><div class="alert" style="background:#fecaca;color:#7f1d1d;border-radius:10px;padding:10px;margin-bottom:8px;"><?= e($error) ?></div><?php endif; ?>
        <label class="field"><i class="ri-mail-line"></i><input type="email" name="email" placeholder="Email address" required></label>
        <label class="field"><i class="ri-lock-2-line"></i><input type="password" name="password" placeholder="Password" required></label>
        <button class="btn-primary">Sign in</button>
        <div style="margin-top:10px;text-align:center"><button type="button" id="themeToggle" class="icon-btn"><i id="themeIcon" class="ri-sun-line"></i></button></div>

      </form>
    </div>
  </div>
  <script src="<?= u('assets/dashboard-modern.js') ?>"></script>
</body>
</html>
