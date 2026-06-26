# Setup Checklist

## Local (XAMPP)
- [ ] Extract this `milk-system` folder into `C:\xampp\htdocs\milk-system`
- [ ] Start MySQL and Apache from the XAMPP control panel
- [ ] Visit `http://localhost/milk-system/` (tables + admin account auto-created)
- [ ] Log in at `http://localhost/milk-system/admin/login.php` with the
      credentials in README.md

## Going live / sharing the QR code
- [ ] Pick a way to make the site reachable (same-WiFi IP, tunnel, or real hosting)
- [ ] Reload `index.php` once on that new address — the QR code regenerates
      itself based on the current domain, no manual editing needed

## GitHub (optional)
- [ ] `git init && git add . && git commit -m "Initial commit"`
- [ ] Create a repo on GitHub, then `git push`

## Nice-to-haves if you keep building this
- [ ] Add CSRF tokens to the approve/update forms
- [ ] Add "forgot password" flow for customers
- [ ] Add per-milk-type (cow/buffalo) selection on the customer side -
      the `milk_type` column already exists in the schema but the UI
      doesn't use it yet
