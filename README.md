# Mini Test – Lead Capture (PHP + MySQL)

This is a lightweight PHP + MySQL project built as part of a coding mini-test.  
It demonstrates capturing Google Ads click IDs (`gclid`) and tracker IDs (`sub_id`) alongside a lead’s basic details.

---

## Features

- **Task A – Lead Form & Admin**

  - Simple form (`index.php`) to capture **name, email, gclid, sub_id**.
  - Hidden fields auto-filled from URL query (`?gclid=...&sub_id=...`).
  - Leads saved into a MySQL table (`schema.sql` provided).
  - Admin page (`admin.php`) lists the 50 most recent leads.

- **Task B – MySQL Index & EXPLAIN**

  - Adds an index on `created_at` with:
    ```sql
    CREATE INDEX idx_created_at ON leads (created_at);
    ```
  - **Before EXPLAIN:** full table scan (`type=ALL`, Using filesort).
  - **After EXPLAIN:** uses `idx_created_at` → faster `ORDER BY created_at DESC LIMIT 50`.
  - **Why faster:** The index allows MySQL to read rows in order without scanning/sorting the whole table.

- **Task C – Verification**
  - On lead save, the app can send a postback to a tracker URL (`$TRACKER_POSTBACK_URL` in `config.php`).
  - Local sink (`postback-sink.php`) simulates a tracker and always returns `200 OK`.
  - Logs are viewable at `logs.php`:
    - **Postback Client Log** – what the server attempted.
    - **Tracker Sink Log** – what the local sink received.
  - **Verification steps:**
    1. Tracker log shows `200 OK`.
    2. Admin page shows the lead row with non-empty `gclid` and `sub_id`.

---

## How to Run

1. Clone repo into your web root (e.g., `C:\xampp\htdocs\mini-test`).
2. Create database, e.g. `mini_test`.
3. Import schema:
   ```bash
   mysql -u root -p mini_test < schema.sql
   ```
