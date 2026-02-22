<?php require_once __DIR__ . '/../../middleware/auth_only.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; ?>
<div class="card glass">
  <div class="card-head">
    <h4>Suppliers</h4>
    <?php if(is_role('admin')||is_role('manager')): ?>
      <a class="icon-btn" href="<?= u('suppliers/create.php') ?>">
        <i class="ri-add-line"></i>
      </a>
    <?php endif; ?>
  </div>
  <div class="table-wrap">
    <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Contact</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $rows=$pdo->query(
        "SELECT * 
        FROM suppliers 
        ORDER BY created_at DESC"
      )->fetchAll(); 
      foreach($rows as $r): ?>
        <tr>
          <td><?= e($r['name']) ?></td>
          <td><?= e($r['contact_person']) ?></td>
          <td><?= e($r['phone']) ?></td>
          <td><?= e($r['email']) ?></td>
          <td><?= $r['is_active']?'Active':'Inactive' ?></td>
          <td>
            <a href="<?= u('suppliers/edit.php?id='.(int)$r['id']) ?>">Edit</a>
            <?php if(is_role('admin')): ?> | <a href="<?= u('suppliers/delete.php?id='.(int)$r['id']) ?>" onclick="return confirm('Deactivate this supplier?')">Delete</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
