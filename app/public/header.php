<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trade Zone Validator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
<nav class="mb-3">
    <?php if(isset($_SESSION['user_id'])): ?>
    <a href="index.php">Dashboard</a> |
    <a href="add_zone.php">Add Zone</a> |
    <a href="logout.php">Logout</a>
    <?php endif; ?>
</nav>
