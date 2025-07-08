<?php
require_once __DIR__ . '/../config/config.php';
include 'header.php';

if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new PDO('sqlite:' . $config['db_path']);
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<h2>Login</h2>
<?php if(!empty($error)) echo '<p class="text-danger">'.$error.'</p>'; ?>
<form method="post">
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-primary" type="submit">Login</button>
</form>
<p><a href="register.php">Register</a></p>
<?php include 'footer.php'; ?>
