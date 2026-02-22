<?php 
require_once __DIR__ . '/../../middleware/admin_only.php';
$rows=$pdo->query(
    "SELECT u.id,u.full_name,u.email,u.is_active,r.name role 
    FROM users u JOIN roles r ON u.role_id=r.id 
    ORDER BY u.created_at DESC"
)->fetchAll();
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; 
?>
<div class="card glass">
    <div class="card-head">
        <h4>Users</h4> 
        <a class="icon-btn" href="<?= u('users/create.php') ?>">
            <i class="ri-add-line"></i>
        </a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th
                    ><th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $r): ?>
                <tr>
                    <td><?= e($r['full_name']) ?></td>
                    <td><?= e($r['email']) ?></td>
                    <td><?= e(ucfirst($r['role'])) ?></td>
                    <td><?= $r['is_active']?'Active':'Inactive' ?></td>
                    <td><a href="<?= u('users/edit.php?id='.(int)$r['id']) ?>">Edit</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
