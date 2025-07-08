<?php
$config = include __DIR__ . '/../config/config.php';
$db = new PDO('sqlite:' . $config['db_path']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT,
    telegram_token TEXT,
    telegram_chat_id TEXT
)");

$db->exec("CREATE TABLE IF NOT EXISTS zones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    symbol TEXT,
    lower_bound REAL,
    upper_bound REAL,
    direction TEXT,
    remark TEXT,
    entered_at INTEGER
)");
?>
