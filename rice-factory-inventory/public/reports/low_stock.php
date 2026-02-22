<?php 
require_once __DIR__ . '/../../middleware/auth_only.php';
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php';
$rows=$pdo->query(
    "SELECT * 
    FROM items 
    WHERE is_active=1 AND current_qty<=reorder_level 
    ORDER BY name"
)->fetchAll(); ?>
<div class="card glass">
    <div class="card-head">
        <h4>Low Stock Report</h4>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Reorder</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $r): ?>
                <tr class="low">
                    <td><?= e($r['sku']) ?></td>
                    <td><?= e($r['name']) ?></td>
                    <td><?= e($r['current_qty']) ?></td>
                    <td><?= e($r['reorder_level']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
