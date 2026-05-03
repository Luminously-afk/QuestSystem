<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Quest System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
        }
        .brand {
            font-weight: 700;
        }
        .card-shadow {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand brand" href="<?php echo BASE_URL; ?>/">IT Quest System</a>
        <div class="d-flex">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="navbar-text text-white me-3">
                    <?php echo htmlspecialchars($_SESSION['full_name']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)
                </span>
                <a class="btn btn-outline-light me-2" href="<?php echo BASE_URL; ?>/auth/changePassword">Change Password</a>
                <a class="btn btn-outline-light" href="<?php echo BASE_URL; ?>/auth/logout">Logout</a>
            <?php else: ?>
                <a class="btn btn-outline-light me-2" href="<?php echo BASE_URL; ?>/auth/login">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container py-4">
