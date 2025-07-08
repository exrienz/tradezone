<?php
require_once __DIR__ . '/../config/config.php';
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$db = new PDO('sqlite:' . $config['db_path']);
$id = (int)($_GET['id'] ?? 0);
$db->prepare('DELETE FROM zones WHERE id = ?')->execute([$id]);
header('Location: index.php');
