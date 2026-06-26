<?php
// Some free hosts (e.g. InfinityFree) don't allow writing to the default
// session save path, which makes login silently fail. Point sessions at a
// folder inside our own app instead, which we know is writable.
$sessionPath = __DIR__ . '/../tmp_sessions';
if (!is_dir($sessionPath)) {
    @mkdir($sessionPath, 0755, true);
}
if (is_dir($sessionPath) && is_writable($sessionPath)) {
    session_save_path($sessionPath);
}
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../index.php');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ../customer/dashboard.php');
        exit;
    }
}

function requireCustomer() {
    requireLogin();
    if ($_SESSION['role'] !== 'customer') {
        header('Location: ../admin/dashboard.php');
        exit;
    }
}

function getUser($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Only 'approved' accounts may log in. Pending/rejected accounts are blocked here,
// not just hidden from the UI - this was a real gap in the original code.
function login($pdo, $email, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }

    if ($user['role'] === 'customer' && $user['status'] !== 'approved') {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    return true;
}

function registerCustomer($pdo, $name, $email, $password) {
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        return false;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'customer', 'pending')");
    $stmt->execute([$name, $email, $hash]);
    $userId = $pdo->lastInsertId();
    $pdo->prepare("INSERT INTO milk_plans (user_id, default_quantity) VALUES (?, 1.00)")->execute([$userId]);
    return true;
}

// Only updates the admin has approved count toward tomorrow's total.
// Pending or rejected requests fall back to the customer's default quantity,
// since they haven't taken effect yet.
function getTotalMilk($pdo) {
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(CASE
            WHEN du.quantity IS NOT NULL AND du.request_status = 'approved' THEN du.quantity
            ELSE mp.default_quantity
        END), 0) as total
        FROM users u
        LEFT JOIN milk_plans mp ON u.id = mp.user_id
        LEFT JOIN daily_updates du ON u.id = du.user_id AND du.update_date = ?
        WHERE u.status = 'approved' AND u.role = 'customer'
    ");
    $stmt->execute([$tomorrow]);
    return $stmt->fetchColumn();
}

// Admin edits a customer's name/email/password. Pass null/empty password to leave it unchanged.
// Returns true on success, false if the new email is already taken by someone else.
function editCustomer($pdo, $userId, $name, $email, $password = null) {
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check->execute([$email, $userId]);
    if ($check->fetch()) {
        return false;
    }

    if ($password !== null && $password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ? AND role = 'customer'")
            ->execute([$name, $email, $hash, $userId]);
    } else {
        $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ? AND role = 'customer'")
            ->execute([$name, $email, $userId]);
    }
    return true;
}
