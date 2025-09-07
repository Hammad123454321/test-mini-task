<?php
require_once __DIR__ . '/../config.php';

// Handle add index button
if (isset($_GET['add_index'])) {
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_created_at ON leads (created_at)");
    header("Location: admin.php?msg=Index+added");
    exit;
}

// Handle "Show AFTER plan"
$explainAfter = false;
if (isset($_GET['show_after'])) {
    $explainAfter = true;
}

// Insert quick test lead (for demo button)
if (isset($_GET['add_lead'])) {
    $stmt = $pdo->prepare("INSERT INTO leads (name,email,gclid,sub_id) VALUES (?,?,?,?)");
    $stmt->execute([
        'Test User ' . rand(100,999),
        'test'.rand(100,999).'@example.com',
        'G-DEMO-'.rand(100,999),
        'S-DEMO-'.rand(100,999),
    ]);
    header("Location: admin.php?msg=Lead+inserted");
    exit;
}

// Fetch leads
$rows = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC LIMIT 50")->fetchAll();

// Run EXPLAIN
$explainQuery = "EXPLAIN SELECT * FROM leads " . ($explainAfter ? "FORCE INDEX (idx_created_at)" : "") . " ORDER BY created_at DESC LIMIT 50";
$explainRows = $pdo->query($explainQuery)->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin</title>
  <style>
    body { font-family: Arial, sans-serif; margin:2rem; }
    button, .btn { padding:.4rem .8rem; margin:.2rem; border:none; border-radius:.3rem; cursor:pointer; }
    .btn { background:#111;color:#fff;text-decoration:none; }
    table { border-collapse: collapse; width:100%; margin-top:1rem; }
    th,td { border:1px solid #ccc; padding:.4rem; }
    th { background:#eee; }
    .msg { margin:.5rem 0; padding:.5rem; background:#e6ffe6; border:1px solid #b2d8b2; }
  </style>
</head>
<body>

<h2>Leads (newest 50)</h2>

<?php if (isset($_GET['msg'])): ?>
  <div class="msg"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<a class="btn" href="index.php?gclid=G-DEMO-123&sub_id=S-DEMO-456">+ Add New Lead</a>
<a class="btn" href="admin.php?add_index=1">Add idx_created_at</a>
<a class="btn" href="admin.php?show_after=1">Show AFTER plan (FORCE INDEX)</a>
<a class="btn" href="logs.php">Check Logs</a>

<table>
<tr>
  <th>ID</th><th>Name</th><th>Email</th><th>GCLID</th><th>SubID</th><th>Created</th>
</tr>
<?php foreach($rows as $r): ?>
<tr>
  <td><?= $r['id'] ?></td>
  <td><?= htmlspecialchars($r['name']) ?></td>
  <td><?= htmlspecialchars($r['email']) ?></td>
  <td><?= htmlspecialchars($r['gclid']) ?></td>
  <td><?= htmlspecialchars($r['sub_id']) ?></td>
  <td><?= $r['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<h3>EXPLAIN for listing query</h3>
<p>Use this as your before (no force). Then click “Show AFTER plan” for the after screenshot.</p>

<table>
<tr>
  <?php foreach(array_keys($explainRows[0]) as $col): ?>
    <th><?= htmlspecialchars($col) ?></th>
  <?php endforeach; ?>
</tr>
<?php foreach($explainRows as $row): ?>
<tr>
  <?php foreach($row as $val): ?>
    <td><?= htmlspecialchars((string)$val) ?></td>
  <?php endforeach; ?>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
