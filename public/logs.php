<?php
$files = [
  '../logs/postback.log'     => 'Postback Client Log',
  '../logs/tracker-sink.log' => 'Local Tracker Sink Log',
];
?><!doctype html>
<html><head><meta charset="utf-8"><title>Logs</title>
<style>body{font-family:system-ui,Segoe UI,Arial;margin:2rem;} pre{background:#f7f7f7;border:1px solid #ddd;padding:1rem;border-radius:.5rem;white-space:pre-wrap}</style>
</head><body>
<h2>Logs</h2>
<?php foreach ($files as $path => $label): ?>
  <h3><?= htmlspecialchars($label) ?></h3>
  <pre><?php
    $p = __DIR__ . '/' . $path;
    echo is_file($p) ? htmlspecialchars(file_get_contents($p)) : '(no log yet)';
  ?></pre>
<?php endforeach; ?>
</body></html>
