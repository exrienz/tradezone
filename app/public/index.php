<?php
require_once __DIR__ . '/../config/config.php';
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$db = new PDO('sqlite:' . $config['db_path']);
$zones = $db->query('SELECT * FROM zones')->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>
<h2>Zones</h2>
<table class="table">
<tr><th>ID</th><th>Symbol</th><th>Lower</th><th>Upper</th><th>Direction</th><th>Remark</th><th></th></tr>
<?php foreach($zones as $z): ?>
<tr>
    <td><?= htmlspecialchars($z['id']) ?></td>
    <td><?= htmlspecialchars($z['symbol']) ?></td>
    <td><?= htmlspecialchars($z['lower_bound']) ?></td>
    <td><?= htmlspecialchars($z['upper_bound']) ?></td>
    <td><?= htmlspecialchars($z['direction']) ?></td>
    <td><?= htmlspecialchars($z['remark']) ?></td>
    <td>
        <a href="edit_zone.php?id=<?= $z['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
        <a href="delete_zone.php?id=<?= $z['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>
