# TheFallenImmortals — Project State & Conventions

Legacy procedural PHP text-based MMORPG being modernized to run on PHP 8.5.

## Work in Progress / Pending (next session)

- None currently open. (Image copy task completed: the 5 tutorial-only images —
  `Hand.png`, `lognavArea.png`, `spacerX.png`, `spacerY.png`, `charinfoBKG.png` — were
  copied to root `images/`; all CSS image references verified resolve.)

## Completed / Architecture

### DB layer (OOP mysqli + prepared statements)
- `src/Database.php`: OOP `Database` class wrapping mysqli; `query()` returns `bool(false)` on failure.
- `src/helpers.php`: procedural wrappers (`db_query`, `db_fetch_assoc`, `db_fetch_array`,
  `db_fetch_row`, `db_num_rows`, `db_insert_id`, `db_error`, `db_escape`, `db_connect`).
  `db_fetch_assoc/array/row` normalize null→false (avoid mysqli end-of-rows TypeError).
- `db-conn.php` (gitignored) → `require_once __DIR__.'/src/helpers.php'` then
  `db_connect('localhost','fallen','19KiNg73','fallendb')`. Templates in `db-conn.example.php`.
- `characters` table is empty/fresh; 33 tables; `enemies`=194, `classes`=22, `map`=10000.
- Two malicious webshells `images/68564.php` and `tfiTutorial/images/68564.php`: user chose to
  KEEP (do not delete/modify); their `.htaccess` `ErrorDocument 404` refs already removed (inert).

### Asset organization
- JS: 8 root files → `js/` (`ajax.js`, `chatroomfunctions.js`, `dom.js`, `functions.js`,
  `gamefunctions.js`, `homefunctions.js`, `jquery.js`, `jqueryPopup.js`);
  tutorial files → `tfiTutorial/js/` (`ajax.js`, `dom.js`, `gamefunctions.js`).
  All via `git mv` (history preserved). All references updated & verified.
- CSS: moved `main.css`, `gameindex.css`, `fbstyle.css` → `css/`; extracted inline `<style>`
  blocks from `index.php`→`css/index.css`, `index2.html`→`css/index2.css`,
  `game.php`→`css/game.css`, `game2.php`→`css/game2.css`; all replaced with
  `<link href="css/X.css" ...>`. Tutorial: `tfiTutorial/main.css` → `tfiTutorial/css/main.css`.
  All image paths rewritten `images/...` → `../images/...`. References in
  `gameindex.php`, `fbindex.php`, `activate.php`, `chatfunctions.html`, `tfiTutorial/index.php`
  updated. All links verified resolve; all CSS image references verified resolve
  (5 images copied from `tfiTutorial/images/` to `images/` complete the set).
  PHPStan clean, `php -l` clean.
- `tfiTutorial/gamefunctions.js` is unique (103 bytes, minimal `viewFighting()`); keep tutorial
  JS/CSS self-contained in `tfiTutorial/`.
- `massMail.php`, `sendemail.php`, `tfiTutorial/test.php` still have inline `<style>` (email/test
  templates) — left alone, out of scope.

### Password system (bcrypt, `murder()` removed)
- `register.php`: stores `password_hash($p, PASSWORD_BCRYPT)`; relaxed email regex;
  trims inputs; mismatch check uses `trim()`.
- `login.php`: fetch by username, `password_verify()` vs `$char['password']`, promotes temp
  password (bcrypt) into real password on login.
- `editaccount.php`: verify old password `password_verify($opass, $char['password'])`,
  compare plaintext new-passwords, store bcrypt hash in `activatenewpassword.newpassword`.
- `forgot.php`: temp password bcrypt in `temppass`. `activatepass.php`: unchanged, commits hash.
- bcrypt = 60 chars (fits `characters.password` varchar 255, `temppass` varchar 60,
  `activatenewpassword.newpassword` varchar 100).

## Reference / Commands
- PHPStan: `vendor/bin/phpstan analyse --no-progress --memory-limit=1G` — configured **level 1**, clean
  (use `--memory-limit=1G`; default CLI 128M can crash PHPStan).
  (`phpstan.neon`: broad `ignoreErrors` regex for inherited-global `variable.undefined` noise,
  loose numeric-string/binary-op patterns, `excludePaths` for `ChromePhp.php`, `vendor`, images).
  `bootstrapFiles: src/helpers.php`.
- `src/phpstan-bootstrap.php`: declares inherited game globals.
- No root-level `.css` files remain (all in `css/`); no root-level `.js` files remain (all in `js/`).
- Test artifacts cleaned: table counts back to 0 rows.

## Conventions
- Do NOT add code comments unless asked.
- Only commit when explicitly requested by the user.
- Keep tutorial assets self-contained under `tfiTutorial/`.
- OOP DB layer is the chosen approach; procedural game files delegate via `db_*` helpers.
- When registering a test user, use `oppie@localhost.localdomain` as the email address.
