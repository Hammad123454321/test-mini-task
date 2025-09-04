<?php
require_once __DIR__ . '/../config.php';

// Add index if requested
if (isset($_GET['action']) && $_GET['action'] === 'add_index') {
  $checkSql = "SELECT COUNT(*) AS cnt FROM information_schema.STATISTICS 
               WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'leads' AND INDEX_NAME = 'idx_created_at'";
  $cnt = (int) $pdo->query($checkSql)->fetch()['cnt'];
  $msg = $cnt === 0 ? "Index idx_created_at created." : "Index idx_created_at already exists.";
  if ($cnt === 0) { $pdo->exec("CREATE INDEX idx_created_at ON leads (created_at)"); }
  header("Location: admin.php?msg=" . urlencode($msg)); exit;
}

// Optional: show AFTER-plan clearly with FORCE INDEX when ?force=1
$force = isset($_GET['force']) ? " FORCE INDEX (idx_created_at) " : " ";

$listSql = "SELECT id, name, email, gclid, sub_id, created_at
            FROM leads {$force}
            ORDER BY created_at DESC
            LIMIT 50";

$rows = $pdo->query($listSql)->fetchAll();
$explain = $pdo->query("EXPLAIN " . $listSql)->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Admin — Leads</title>
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 2rem; }
    table { border-collapse: collapse; width: 100%; margin-top:1rem; }
    th, td { border: 1px solid #ddd; padding: .5rem; font-size:.95rem; }
    th { background: #fafafa; text-align: left; }
    .actions a { margin-right: .5rem; }
    .banner { padding:.75rem 1rem; background:#f6f6f6; border:1px solid #ddd; border-radius:.5rem; }
    .muted { color:#666; font-size:.9rem; }
    .btn { display:inline-block; padding:.45rem .7rem; border-radius:.5rem; background:#111; color:#fff; text-decoration:none; }
  </style>
</head>
<body>
  <h2>Leads (newest 50)</h2>
  <?php if (isset($_GET['created'])): ?><div class="banner">Lead created.</div><?php endif; ?>
  <?php if (isset($_GET['msg'])): ?><div class="banner"><?php echo htmlspecialchars($_GET['msg']); ?></div><?php endif; ?>

  <div class="actions">
    <a class="btn" href="index.php?gclid=G-DEMO-123&sub_id=S-DEMO-456">+ Add New Lead</a>
    <a class="btn" href="admin.php?action=add_index">Add idx_created_at</a>
    <a class="btn" href="admin.php?force=1">Show AFTER plan (FORCE INDEX)</a>
  </div>

  <table>
    <thead><tr>
      <th>ID</th><th>Name</th><th>Email</th><th>gclid</th><th>sub_id</th><th>created_at</th>
    </tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['id'] ?></td>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><?= htmlspecialchars($r['email']) ?></td>
          <td><?= htmlspecialchars($r['gclid']) ?></td>
          <td><?= htmlspecialchars($r['sub_id']) ?></td>
          <td><?= htmlspecialchars($r['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="6" class="muted">No leads yet. Submit one via <a href="index.php">index.php</a>.</td></tr><?php endif; ?>
    </tbody>
  </table>

  <h3>EXPLAIN for listing query</h3>
  <p class="muted">Use this as your <strong>before</strong> (no force), then click “Show AFTER plan” for the <strong>after</strong> screenshot.</p>
  <table>
    <thead><tr>
      <?php if (!empty($explain)) foreach (array_keys($explain[0]) as $col) echo "<th>".htmlspecialchars($col)."</th>"; ?>
    </tr></thead>
    <tbody>
      <?php foreach ($explain as $row): ?>
        <tr><?php foreach ($row as $val): ?><td><?= htmlspecialchars((string)$val) ?></td><?php endforeach; ?></tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</body>
</html>
