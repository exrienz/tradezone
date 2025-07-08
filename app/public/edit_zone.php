<?php
require_once __DIR__ . '/../config/config.php';
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$db = new PDO('sqlite:' . $config['db_path']);
$id = (int)($_GET['id'] ?? 0);
$zone = $db->prepare('SELECT * FROM zones WHERE id = ?');
$zone->execute([$id]);
$zone = $zone->fetch(PDO::FETCH_ASSOC);
if(!$zone) {
    echo 'Zone not found';
    exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare('UPDATE zones SET symbol=?, lower_bound=?, upper_bound=?, direction=?, remark=? WHERE id=?');
    $stmt->execute([
        $_POST['symbol'],
        $_POST['lower_bound'],
        $_POST['upper_bound'],
        $_POST['direction'],
        $_POST['remark'],
        $id
    ]);
    header('Location: index.php');
    exit;
}
include 'header.php';
?>
<h2>Edit Zone</h2>
<form method="post">
    <div class="mb-3">
        <label class="form-label">Symbol</label>
        <select name="symbol" class="form-select">
            <option value="XAU" <?= $zone['symbol']=='XAU'?'selected':'' ?>>XAU</option>
            <option value="XAG" <?= $zone['symbol']=='XAG'?'selected':'' ?>>XAG</option>
            <option value="BTC" <?= $zone['symbol']=='BTC'?'selected':'' ?>>BTC</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Lower Bound</label>
        <input type="number" step="0.01" name="lower_bound" class="form-control" value="<?= $zone['lower_bound'] ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Upper Bound</label>
        <input type="number" step="0.01" name="upper_bound" class="form-control" value="<?= $zone['upper_bound'] ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Direction</label>
        <select name="direction" class="form-select">
            <option value="Uptrend" <?= $zone['direction']=='Uptrend'?'selected':'' ?>>Uptrend</option>
            <option value="Downtrend" <?= $zone['direction']=='Downtrend'?'selected':'' ?>>Downtrend</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Remark</label>
        <input type="text" name="remark" class="form-control" value="<?= htmlspecialchars($zone['remark']) ?>">
    </div>
    <button class="btn btn-primary" type="submit">Save</button>
</form>
<?php include 'footer.php'; ?>
