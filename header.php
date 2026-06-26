<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MilkWise - Smart Milk Distribution Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= isset($inAdminOrCustomerFolder) ? '../assets/css/style.css' : 'assets/css/style.css' ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?= isset($inAdminOrCustomerFolder) ? '../index.php' : 'index.php' ?>">
                <i class="fas fa-droplet me-2"></i>
                MilkWise Pro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="nav-link">👋 <?= htmlspecialchars($_SESSION['name']) ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light ms-2" href="<?= isset($inAdminOrCustomerFolder) ? '../logout.php' : 'logout.php' ?>">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid mt-0 pb-5">
        <div class="hero mb-5">
            <div class="hero-content text-center">
                <h1 class="display-3 fw-bold mb-4 animate__animated animate__fadeInDown">
                    Smart Milk Distribution
                </h1>
                <p class="lead mb-4 animate__animated animate__fadeInUp">No wastage. Perfect delivery. Daily demand tracking.</p>
            </div>
        </div>
        <div class="container mt-n5">
