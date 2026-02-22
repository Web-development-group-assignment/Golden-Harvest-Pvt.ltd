<?php require_once __DIR__ . '/../../middleware/admin_only.php';
$id=(int)($_GET['id']??0);
$st=$pdo->prepare(
    "UPDATE suppliers 
    SET is_active=0 
    WHERE id=?"
); 
$st->execute([$id]);
audit(
    $pdo,
    $_SESSION['user']['id'],
    'DELETE',
    'suppliers',
    $id,
    null
);
redirect('list.php');
