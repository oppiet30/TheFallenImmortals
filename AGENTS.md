# TheFallenImmortals — Project State & Conventions

Legacy procedural PHP text-based MMORPG being modernized to run on PHP 8.5.

## Work in Progress / Pending (next session)

- PayPal integration code complete; awaiting real credentials to finish setup:
  - Put client keys + webhook id into `paypal-config.php` (copy of
    `paypal-config.example.php`, mode `live` or `sandbox`).
  - Register `completeIPN.php` in the PayPal Developer Dashboard as an HTTPS webhook
    for the PAYMENT.CAPTURE.COMPLETED event.
- Image copy task completed: the 5 tutorial-only images (`Hand.png`, `lognavArea.png`,
  `spacerX.png`, `spacerY.png`, `charinfoBKG.png`) were copied to root `images/`; all CSS
  image references verified resolve.

## Completed / Architecture

### DB layer (OOP mysqli + prepared statements)
- `src/Database.php`: OOP `Database` class wrapping mysqli; `query()` returns `bool(false)` on failure.
- `src/helpers.php`: procedural wrappers (`db_query`, `db_fetch_assoc`, `db_fetch_array`,
  `db_fetch_row`, `db_num_rows`, `db_insert_id`, `db_error`, `db_escape`, `db_connect`).
  `db_fetch_assoc/array/row` normalize null→false (avoid mysqli end-of-rows TypeError).
- `db-conn.php` (gitignored) → `require_once __DIR__.'/src/helpers.php'` then
  `db_connect('localhost','fallen','19KiNg73','fallendb')`. Templates in `db-conn.example.php`.
- `characters` table is empty/fresh; 33 tables; `enemies`=194, `classes`=22, `map`=10000.
- Test characters (ids 1384/1385): `Fighter01` (Fighter; str/dex/end/int/con 60/35/50/20/20,
  2nd class Mage) and `Mage01` (Mage; 20/20/50/60/35, 2nd class Fighter). Password
  `testpass123` (bcrypt), `activated='Yes'`, `refferal='Oppie'`, email
  `oppie@localhost.localdomain`. Both have `secondclass` + `warnings` rows.
- `installation/fallendb.sql` matches live DB except `characters` data (0 rows in dump).
  Data tables verified: affinity=27, bonus=1, cashpot=1, classes=22, enemies=194, map=10000,
  shop=535, temple=1. Seed tables keep `max(id)+1` AUTO_INCREMENT on ENGINE lines
  (affinity=28, bonus=2, cashpot=2, classes=26, enemies=197, map=10001, shop=608); empty
  tables reset to `AUTO_INCREMENT=1`. Column-level `id ... AUTO_INCREMENT,` lines untouched.
  Live DB image-path DEFAULTS aligned with dump (all relative, no `/images/` refs).
- Two malicious webshells `images/68564.php` and `tfiTutorial/images/68564.php`: user chose to
  KEEP (do not delete/modify); their `.htaccess` `ErrorDocument 404` refs already removed (inert).

### PayPal checkout (Orders v2 + webhooks, no SDK)
- `src/PayPal.php`: final cURL REST client — `createOrder()` (CAPTURE intent, tier in
  `custom_id`), `captureOrder()` (null if already captured), `verifyWebhook()` (server-side
  signature check via `/v1/notifications/verify-webhook-signature`), cached OAuth2 token.
- `paypal-config.php` (gitignored) / `paypal-config.example.php`: `mode` = `live`|`sandbox`
  + per-mode `client_id`, `client_secret`, `webhook_id`.
- `createPaypalOrder.php`: POST `tier` → JSON `{orderId, approveUrl}` (login required).
- `capturePaypalOrder.php`: redirect target (token/PayerID/tier); captures + credits if the
  webhook hasn't already (idempotent via `log` message LIKE txn id).
- `completeIPN.php`: webhook listener — verifies signature, accepts only
  PAYMENT.CAPTURE.COMPLETED, validates tier + USD amount, credits cash + networth, posts
  chatroom announcement, records in `log` (`name`, `message` = `PayPal txn <id>`).
- Tier rates (consistent across all three): FIVE_CASH 5/`5.25`, TEN_CASH 11/`10.50`,
  TWENTY_CASH 23/`21.00`, FIFTY_CASH 58/`52.50`, ONEHUNDRED_CASH 120/`105.00`.
- `purchase.php` triggers checkout via `js/functions.js` `paypalCheckout(tier)` (opens the
  approval URL in a new tab); no classic `_xclick` forms remain.

### opencode tooling
- `opencode.json` (project root): LSP config for PHP Intelephense
  (`command: ["/home/oppie/.npm-global/bin/intelephense","--stdio"]`, `extensions: [".php"]`).
  Schema requires a `command` array for object-keyed LSP entries.

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

### reCAPTCHA (v2, all players)
- `captcha-config.php` (gitignored) / `captcha-config.example.php`: `site_key` +
  `secret_key` for Google reCAPTCHA v2. Placeholder keys = captcha auto-passes (no
  suspension risk until real keys are plugged in).
- `fightenemy.php`: random chance triggers `captcha='Active'` (1/45 auto, 1/1500 manual)
  for ALL players (Ajezior-only restriction removed).
- `updatestats.php` / `updatestatstemp.php`: when `captcha=='Active'`, blocks UI with
  90s countdown + reCAPTCHA widget.
- `captchaverify.php`: unconfigured keys → auto-pass (awards gold, clears captcha);
  configured keys → POST to `google.com/recaptcha/api/siteverify`, timeout = 12hr
  suspension, wrong = alert + re-render widget.
- `attackenemy.php:8`: resets all players' captcha to `Inactive` on every attack.
- `recaptchalib.php` deleted (dead v1 library). `recaptcha_ajax.js` replaced with
  `recaptcha/api.js?render=explicit` in game.php, game2.php, gameindex.php.
- `js/functions.js`: `verifyCaptcha()` sends `g-recaptcha-response` token;
  `showRecaptcha()` uses `grecaptcha.render()` with dark theme.

## Reference / Commands
- PHPStan: `vendor/bin/phpstan analyse --no-progress --memory-limit=1G` — configured **level 1**, clean
  (use `--memory-limit=1G`; default CLI 128M can crash PHPStan).
  (`phpstan.neon`: broad `ignoreErrors` regex for inherited-global `variable.undefined` noise,
  loose numeric-string/binary-op patterns, `excludePaths` for `ChromePhp.php`, `vendor`, images).
  `bootstrapFiles: src/helpers.php`.
- `src/phpstan-bootstrap.php`: declares inherited game globals.
- No root-level `.css` files remain (all in `css/`); no root-level `.js` files remain (all in `js/`).
- Test artifacts cleaned: table counts back to 0 rows.
- LSP: `opencode debug config` validates `opencode.json`; Intelephense requires a
  `command` array (`["/home/oppie/.npm-global/bin/intelephense","--stdio"]`).
- `capturePaypalOrder.php`, `completeIPN.php` use `log` columns `name`+`message` (NOT
  `username/action/note/time`) — `log.name varchar(50)`, `log.message longtext`.

## Conventions
- Do NOT add code comments unless asked.
- Only commit when explicitly requested by the user.
- Keep tutorial assets self-contained under `tfiTutorial/`.
- OOP DB layer is the chosen approach; procedural game files delegate via `db_*` helpers.
- When registering a test user, use `oppie@localhost.localdomain` as the email address.
