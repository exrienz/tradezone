<?php
require_once __DIR__ . '/../config/config.php';
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$db = new PDO('sqlite:' . $config['db_path']);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare('INSERT INTO zones (symbol, lower_bound, upper_bound, direction, remark) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $_POST['symbol'],
        $_POST['lower_bound'],
        $_POST['upper_bound'],
        $_POST['direction'],
        $_POST['remark']
    ]);
    header('Location: index.php');
    exit;
}
include 'header.php';
?>
<h2>Add Zone</h2>
<form method="post">
    <div class="mb-3">
        <label class="form-label">Symbol</label>
        <select name="symbol" class="form-select">
            <option value="XAU">XAU</option>
            <option value="XAG">XAG</option>
            <option value="BTC">BTC</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Lower Bound</label>
        <input type="number" step="0.01" name="lower_bound" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Upper Bound</label>
        <input type="number" step="0.01" name="upper_bound" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Direction</label>
        <select name="direction" class="form-select">
            <option value="Uptrend">Uptrend</option>
            <option value="Downtrend">Downtrend</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Remark</label>
        <input type="text" name="remark" class="form-control">
    </div>
    <button class="btn btn-primary" type="submit">Add</button>
</form>
<?php include 'footer.php'; ?>
