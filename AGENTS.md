# AGENTS.md

SIBIBIT — Sistem Informasi Pengajuan Bibit (Dinas Perkebunan Kolaka). Procedural PHP + jQuery/Bootstrap 5 admin app. No framework, no Composer for the app, no build/lint/test tooling.

## Run & verify
- Served by XAMPP (Apache + MySQL) from `htdocs/sibibit`. DB config: `controller/config/database.php` (`sibibit`, user `root`, empty password). Full schema: `datebase/sibibit(2).sql` — the `datebase/` spelling is legacy, not a bug to "fix".
- No test suite, linter, or bundler. Syntax-check PHP with `php -l <file>`; verify JS by exercising the page in a browser.
- Frontend deps are vendored in `assets/` and `libs/`; do not add npm/Composer packages.

## Architecture
- Roles/entrypoints: `index.php` (login) → `menu-admin.php` (level 1), `menu-kadis.php` (2), `menu-petani.php` (3). Role is a raw `$_SESSION['level']` check at the top of each menu file (levels 0/4/5 also appear in `controller/process/helper.php` and `views/users.php`).
- The admin shell is a client-side mini-SPA: `router/admin.js` holds the `pages` map (`pageKey -> { view, title, scripts }`). Views are fetched by AJAX into `#content`; per-page scripts are appended dynamically. **Register every new page in that `pages` object.** Navigation uses `data-page`; router handlers only fire for `.menu a`, `.dropdown-item`, `.card-dash`/`.activity-item`/`.view-all`/`.link-excel`/`.link`, and `.detail`.
- Backend endpoints are plain PHP in `controller/process/aksi*.php`, usually `?action=` + `switch`, returning JSON through mysqli prepared statements. Excel imports live in `controller/excel/` and use `libs/spreadsheet-reader-master` (`.xls`/`.xlsx` only); uploads go to `controller/excel/uploads/`.
- JS modules in `modul/` load per page. Shared helpers: `modul/options.js` (`loadOptions4`, `editOptions`), `modul/core.js`, `modul/ui-helper.js`; `Popup`, `showLoader`/`hideLoader`, `formatTanggalIndo` come from `libs/js`. Modals are in `modal/`, shared markup in `partials/`.

## Gotchas
- Controller includes are relative to the controller file: process endpoints use `include '../config/database.php'`, excel endpoints use `require '../../libs/...'`. Put new endpoints in the matching folder to keep paths working.
- Session is injected into JS as `const sessionData = <?php echo json_encode($_SESSION); ?>;` in each `menu-*.php`.
- CSRF helpers exist (`controller/config/csrf.php`, token in `<meta name="csrf-token">`) but most `aksi*.php` endpoints do not verify it. Follow existing code; do not assume enforcement.
- `tbl_petani.id_petani` is NOT auto-increment: it reuses the freshly inserted `tbl_users.id_user`. Create the user first, then the petani row (see `aksiPetani.php` create). Petani accounts get auto username `Petani###` (`controller/process/getid.php`) and default password `SIBIBIT`.
- Import is two-step: `preview-*.php` parses the upload and returns `{status, valid, invalid}`, then `create-*.php` persists the encoded rows. Keep that split when adding an import (see `controller/excel/*-petani.php` + `modul/modulImportPetani.js`).
