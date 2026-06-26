# MilkWise Pro - Milk Distribution Management System

A simple PHP + MySQL app for managing daily milk orders between an admin
and approved customers.

## Folder structure
```
milk-system/
├── admin/          (login, dashboard, customer approval)
├── customer/        (login, register, dashboard, daily update)
├── includes/        (shared header, footer, functions)
├── config/          (db.php - connection + auto schema setup)
├── assets/css/      (style.css)
├── assets/js/       (script.js)
├── index.php        (landing page + QR code)
├── logout.php
└── db.sql           (manual schema reference, optional)
```

## Local setup (XAMPP)
1. Copy this whole `milk-system` folder into `C:\xampp\htdocs\` (or symlink it there).
2. Copy `config/secrets.example.php` to `config/secrets.php` and fill in your
   real database credentials and the admin email/password you want. This
   file is git-ignored on purpose - it holds real secrets and never gets
   pushed to GitHub, even from a public repo.
3. Start Apache and MySQL from the XAMPP control panel.
4. Visit `http://localhost/milk-system/` — the database and tables are created
   automatically on first load, including the admin account from secrets.php.
5. Admin login: `http://localhost/milk-system/admin/login.php`, using the
   email/password you put in `secrets.php`.

The admin row is only created once; editing `secrets.php` after the account
already exists won't change it (you'd update the `users` table directly, or
delete that row and reload to have it recreated with the new values).

## Making it reachable from another device or the internet

The QR code and any links on the page are generated from whatever domain is
currently serving the app — there's nothing hardcoded to `localhost` anymore.
So once you make the site reachable some other way, the QR code updates
itself automatically. A few ways to do that:

- **Same Wi-Fi network:** find your computer's local IP (`ipconfig` on
  Windows) and visit `http://<your-local-ip>/milk-system/` from a phone on
  the same network.
- **Tunnel to the internet (quick, temporary):** tools like ngrok or
  Cloudflare Tunnel expose your local Apache server with a public HTTPS URL.
  Run the tunnel pointed at port 80 (or whatever port Apache uses), then
  visit the generated URL — the app will pick it up automatically.
- **Real hosting (permanent):** deploy to any PHP+MySQL host and update the
  three variables at the top of `config/db.php` ($host, $username,
  $password) to match that host's database credentials.

## Putting this on GitHub

```bash
cd milk-system
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/<your-username>/<your-repo>.git
git push -u origin main
```

`.gitignore` already excludes `config/secrets.php`, so your real database
credentials and admin password never get committed - safe for a public
repo. Only `config/secrets.example.php` (placeholder values) gets pushed.
Anyone cloning the repo fresh needs to copy that template to `secrets.php`
and fill in their own values before the app will run.

## Features
- Cow/buffalo milk plans per customer
- Daily update window (closes 9PM, reopens 12AM) for skip/reduce/increase requests
- Every daily update request needs admin approval before it counts toward tomorrow's total -
  pending/rejected requests fall back to the customer's default quantity
- Admin approval queue for new customer signups
- Admin can edit any customer's name, email, and password from Manage Customers → Edit
- Auto-creates database and tables on first run, including a migration step for
  installs that predate the request-approval column

## Known limitations
- No CSRF tokens on forms (fine for local/single-admin use, not for public production)
- No rate-limiting on login attempts
- Single admin model — no multi-admin support
