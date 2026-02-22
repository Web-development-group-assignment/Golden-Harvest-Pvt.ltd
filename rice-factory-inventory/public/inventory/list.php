<?php require_once __DIR__ . '/../../middleware/auth_only.php'; ?>
<?php 
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php'; 
?>
<div class="card glass">
  <div class="card-head">
    <h4>Inventory</h4> 
    <?php if(is_role('admin')||is_role('manager')): ?>
      <a class="icon-btn" href="<?= u('inventory/create.php') ?>">
        <i class="ri-add-line"></i>
      </a>
      <?php endif; ?>
    </div>
  <div class="table-wrap">
    <table>
    <thead>
      <tr>
        <th>SKU</th>
        <th>Name</th>
        <th>Category</th>
        <th>Unit</th>
        <th>Qty</th>
        <th>Reorder</th>
        <th>Cost</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php $rows=$pdo->query("SELECT * FROM items WHERE is_active=1 ORDER BY name")->fetchAll(); foreach($rows as $r): ?>
      <tr class="<?= ($r['current_qty']<=$r['reorder_level'])?'low':'' ?>">
        <td><?= e($r['sku']) ?></td>
        <td><?= e($r['name']) ?></td>
        <td><?= e($r['category']) ?></td>
        <td><?= e($r['unit']) ?></td>
        <td><?= e($r['current_qty']) ?></td>
        <td><?= e($r['reorder_level']) ?></td>
        <td><?= e(number_format((float)$r['cost_price'],2)) ?></td>
        <td>
          <a href="<?= u('inventory/edit.php?id='.(int)$r['id']) ?>">Edit</a>
          <?php if(is_role('admin')): ?> | <a href="<?= u('inventory/delete.php?id='.(int)$r['id']) ?>" onclick="return confirm('Delete this item?')">Delete</a><?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
