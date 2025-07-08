<?php
require_once __DIR__ . '/../config/config.php';
include 'header.php';

$db = new PDO('sqlite:' . $config['db_path']);
$exists = $db->query('SELECT COUNT(*) FROM users')->fetchColumn();
if($exists && !isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    $stmt->execute([
        $_POST['username'],
        password_hash($_POST['password'], PASSWORD_DEFAULT)
    ]);
    header('Location: login.php');
    exit;
}
?>
<h2>Register Admin</h2>
<form method="post">
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-primary" type="submit">Register</button>
</form>
<?php include 'footer.php'; ?>
