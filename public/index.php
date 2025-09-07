<?php require_once __DIR__ . '/../config.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Lead Form</title>
  <style>
    body { font-family: Arial, sans-serif; margin:2rem; }
    .card { max-width:500px;padding:1rem;border:1px solid #ccc;border-radius:.5rem; }
    input { width:100%;padding:.5rem;margin:.3rem 0; }
    button { padding:.5rem 1rem; background:#111; color:#fff; border:none; border-radius:.3rem; cursor:pointer; }
    .muted { font-size:.9rem;color:#666; }
  </style>
</head>
<body>
<div class="card">
  <h2>Lead Capture</h2>
  <form method="post" action="save.php">
    <label>Name</label>
    <input type="text" name="name" required>
    <label>Email</label>
    <input type="email" name="email" required>

    <input type="hidden" id="gclid" name="gclid">
    <input type="hidden" id="sub_id" name="sub_id">

    <button type="submit">Submit Lead</button>
  </form>
  <p class="muted">Open with params: <code>?gclid=G-DEMO-123&sub_id=S-DEMO-456</code></p>
  <p class="muted"><a href="admin.php">Admin</a> | <a href="logs.php">Logs</a></p>
</div>
<script>
  const params = new URLSearchParams(window.location.search);
  document.getElementById('gclid').value = params.get('gclid') || '';
  document.getElementById('sub_id').value = params.get('sub_id') || '';
</script>
</body>
</html>
