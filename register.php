<?php
require '../includes/functions.php';
require '../config/db.php';

$success = $error = '';
if ($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($name === '' || $email === '' || strlen($password) < 6) {
        $error = 'Please fill all fields - password must be at least 6 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (registerCustomer($pdo, $name, $email, $password)) {
        $success = 'Registered successfully! Wait for admin approval.';
    } else {
        $error = 'Registration failed - email may already exist.';
    }
}

$inAdminOrCustomerFolder = true;
?>
<?php include '../includes/header.php'; ?>
<h2>Customer Registration</h2>
<?php if ($success): ?>
<div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required minlength="6">
    </div>
    <button class="btn btn-success">Register</button>
    <a href="../index.php" class="btn btn-secondary">Back</a>
</form>
<?php include '../includes/footer.php'; ?>
