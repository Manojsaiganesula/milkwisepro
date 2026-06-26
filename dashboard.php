<?php
require '../includes/functions.php';
requireCustomer();
require '../config/db.php';

$stmt = $pdo->prepare("SELECT default_quantity FROM milk_plans WHERE user_id = ? LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$defaultQty = $stmt->fetchColumn();
if ($defaultQty === false) {
    $defaultQty = 1.00;
}

$tomorrow = date('Y-m-d', strtotime('+1 day'));
$stmt = $pdo->prepare("SELECT * FROM daily_updates WHERE user_id = ? AND update_date = ?");
$stmt->execute([$_SESSION['user_id'], $tomorrow]);
$update = $stmt->fetch();

$inAdminOrCustomerFolder = true;
?>
<?php include '../includes/header.php'; ?>
<h2>Customer Dashboard</h2>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <i class="fas fa-droplet fa-5x text-primary milk-icon"></i>
                    </div>
                    <div class="col-md-8">
                        <h2 class="mb-2">Your Plan</h2>
                        <h1 class="display-4 fw-bold text-primary"><?= htmlspecialchars($defaultQty) ?> L Daily</h1>
                        <?php if ($update): ?>
                            <div class="alert alert-info mt-3">
                                <strong>Tomorrow:</strong> <?= htmlspecialchars(ucfirst($update['status'])) ?>
                                <?php if ($update['quantity']): ?> (<?= htmlspecialchars($update['quantity']) ?> L)<?php endif; ?>
                                <?php
                                $statusLabel = ['pending' => ['Awaiting admin approval', 'text-warning'], 'approved' => ['Approved', 'text-success'], 'rejected' => ['Rejected - please resubmit', 'text-danger']][$update['request_status']] ?? ['', ''];
                                ?>
                                <br><span class="<?= $statusLabel[1] ?> fw-semibold"><?= htmlspecialchars($statusLabel[0]) ?></span>
                                <?php if ($update['notes']): ?><br><small><?= htmlspecialchars($update['notes']) ?></small><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-secondary mt-3">
                                <strong>Tomorrow:</strong> Default <?= htmlspecialchars($defaultQty) ?> L
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <a href="update.php" class="btn btn-milk btn-lg w-100 h-100 d-flex align-items-center justify-content-center fs-4">
            <i class="fas fa-edit me-3"></i>
            Update Milk Order
        </a>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
