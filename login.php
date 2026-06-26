<?php
require '../includes/functions.php';
require '../config/db.php';

$error = '';
if ($_POST) {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    if (login($pdo, $email, $pass)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials or not approved yet.';
    }
}

$inAdminOrCustomerFolder = true;
?>
<?php include '../includes/header.php'; ?>
<h2>Customer Login</h2>
<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-primary">Login</button>
    <a href="../index.php" class="btn btn-secondary">Back</a>
</form>
<?php include '../includes/footer.php'; ?>
