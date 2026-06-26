<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'];
    if ($role === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: customer/dashboard.php');
    }
    exit;
}

// Build the QR target from whatever domain/path is actually serving this page,
// instead of a hardcoded localhost URL that breaks the moment you deploy or
// tunnel this somewhere else.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = rtrim(dirname($_SERVER['PHP_SELF']), '/');
$siteUrl = "$scheme://$host$basePath/";
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($siteUrl);
?>
<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 stat-card">
                    <div class="card-body">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3 milk-icon"></i>
                        <h3 class="card-title">Customer Register</h3>
                        <p class="card-text">Join and manage cow/buffalo milk addons</p>
                        <a href="customer/register.php" class="btn btn-milk btn-lg w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Register
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 stat-card">
                    <div class="card-body">
                        <i class="fas fa-sign-in-alt fa-3x text-success mb-3 milk-icon"></i>
                        <h5 class="card-title">Customer Login</h5>
                        <p class="small">Submit your daily addon demand</p>
                        <a href="customer/login.php" class="btn btn-success btn-lg w-100">
                            Login <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="qr-section mx-auto text-center mt-5">
    <h3>Scan QR to Access</h3>
    <div class="qr-container">
        <img src="<?= htmlspecialchars($qrUrl) ?>" alt="QR Code" class="img-fluid mb-3 shadow">
        <p class="lead">Share with customers for quick login & addon demands</p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
