<?php
require_once __DIR__ . '/../config.php';
$qs = $_SERVER['QUERY_STRING'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Lead Capture — Mini Test</title>
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 2rem; }
    .card { max-width: 520px; padding: 1.25rem; border: 1px solid #ddd; border-radius: .75rem; }
    label { display:block; margin:.5rem 0 .25rem; }
    input[type=text], input[type=email] { width:100%; padding:.6rem; border:1px solid #ccc; border-radius:.5rem; }
    button { margin-top:1rem; padding:.6rem 1rem; border:0; border-radius:.5rem; background:#111; color:#fff; cursor:pointer; }
    small, .muted { color:#666; } .muted { font-size:.9rem; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Lead Form</h2>

    <form method="post" action="save.php<?php echo $qs ? '?'.htmlspecialchars($qs, ENT_QUOTES) : ''; ?>">
      <label for="name">Name</label>
      <input id="name" name="name" type="text" placeholder="Jane Doe" required />

      <label for="email">Email</label>
      <input id="email" name="email" type="email" placeholder="jane@example.com" required />

      <input id="gclid" name="gclid" type="hidden" />
      <input id="sub_id" name="sub_id" type="hidden" />

      <button type="submit">Submit Lead</button>
    </form>

    <p class="muted">Admin: <a href="admin.php">/admin.php</a></p>
  </div>

  <script>
    // Single clean script: URL -> cookies (90d) -> hidden inputs
    const qs = new URLSearchParams(location.search);

    const setCookie = (k, v, days) => {
      const t = new Date(); t.setTime(t.getTime() + days*864e5);
      document.cookie = `${k}=${encodeURIComponent(v)}; expires=${t.toUTCString()}; path=/`;
    };
    const getCookie = (k) =>
      document.cookie.split('; ').find(r => r.startsWith(k + '='))?.split('=')[1];

    const gclid  = qs.get('gclid')  || (getCookie('gclid')  && decodeURIComponent(getCookie('gclid')))  || '';
    const sub_id = qs.get('sub_id') || (getCookie('sub_id') && decodeURIComponent(getCookie('sub_id'))) || '';

    if (gclid)  setCookie('gclid', gclid, 90);
    if (sub_id) setCookie('sub_id', sub_id, 90);

    document.getElementById('gclid').value  = gclid;
    document.getElementById('sub_id').value = sub_id;
  </script>
</body>
</html>
