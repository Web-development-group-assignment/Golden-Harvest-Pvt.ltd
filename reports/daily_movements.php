<?php 
require_once __DIR__ . '/../../middleware/auth_only.php';
include __DIR__ . '/../partials/header.php'; 
include __DIR__ . '/../partials/nav.php';
$rows=$pdo->query(
    "SELECT sm.*, i.name item_name, u.full_name uname 
    FROM stock_movements sm 
    JOIN items i ON i.id=sm.item_id LEFT JOIN users u ON u.id=sm.created_by 
    WHERE DATE(sm.movement_date)=CURDATE() 
    ORDER BY sm.movement_date DESC"
)->fetchAll(); 
?>
<div class="card glass">
    <div class="card-head">
        <h4>Daily Movements (<?= date('Y-m-d') ?>)</h4>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>Remarks</th>
                    <th>By</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $r): ?>
                <tr class="<?= $r['type']==='IN'?'in':'out' ?>">
                    <td><?= e($r['movement_date']) ?></td>
                    <td><?= e($r['item_name']) ?></td>
                    <td><?= e($r['type']) ?></td>
                    <td><?= e($r['qty']) ?></td>
                    <td><?= e($r['remarks']) ?></td>
                    <td><?= e($r['uname']??$r['created_by']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
