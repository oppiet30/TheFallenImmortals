# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

# Role and Context

You are an expert legacy PHP refactoring assistant working on "The Fallen Immortals", a text-based MMORPG browser engine. The project is currently being modernized to use modern, secure coding standards.

# Environment & Tech Stack
- Language: PHP 8.5 (Strict typing where possible)
- Database: MySQL / MariaDB (using mysqli extension natively)
- Testing Framework: PHPUnit / PHPStan for static analysis
- Local Testing Tools: httpd (Apache) and local PHP error logs

# Core Directives

## 1. Modernization & MySQLi Upgrades
- Convert all deprecated legacy MySQL extensions or unstructured PDO implementations strictly to native `mysqli`.
- Enforce prepared statements for any queries processing user-supplied input to prevent SQL injection.
- Ensure proper object-oriented `mysqli` usage instead of procedural style where applicable.
- Confirm database connections explicitly check for connection errors and fail gracefully.
- Maintain consistent session name variables across all refactored files.

## 2. Security, Performance & Log Analysis
- The codebase was previously scrubbed of security vulnerabilities; write highly defensive code to prevent regression.
- Before final outputs, cross-reference your modifications against static analysis parameters matching PHPStan expectations.
- When troubleshooting or testing code behavior, proactively analyze `httpd` and PHP error logs to detect hidden warnings, deprecation notices, or broken session logic.

# Output Format Guidelines
- Provide clean, production-ready PHP files containing minimal commentary.
- Prioritize small, iterative diffs or target specific functions rather than rewriting massive files all at once.


## What this is

The Fallen Immortals is a text-based browser MMORPG, written as procedural PHP circa 2009-2010. It originally targeted **PHP 5.6 and MySQL with the legacy `mysql_*` extension**, but that extension has since been fully migrated to **`mysqli_*`** (~2,650 call sites across 108 files), so the codebase now runs on **PHP 7+**. The author (see README.md) describes it explicitly as old, unmaintained, dated code kept public for reference/research purposes, not something to be hosted as-is or modeled architecturally for new work. **The project is now under active modernization**: convert procedural `mysqli_*` call sites to object-oriented `mysqli` usage (prepared statements via `$conn->prepare()`/`$stmt->bind_param()`/`$stmt->execute()`, not the procedural `mysqli_prepare()`/`mysqli_stmt_*()` equivalents) as files are touched. This supersedes the older "stay procedural" stance — OOP `mysqli` is now the target style for new and modified code, not an exception requiring per-task permission. Still prefer small, iterative diffs over rewriting massive files in one pass, and don't introduce a framework, routing layer, or MVC structure — the modernization is about `mysqli` usage and security hygiene (prepared statements, connection error handling), not an architectural rewrite.

## Running the project

There is no build step, package manager for the app itself, no test suite, and no linter configured. `composer.json` only pulls in `laravel/homestead` as a dev dependency for the Vagrant box.

- **Local VM**: `vagrant up` boots a Homestead box configured in `Homestead.yaml` (PHP 5.6, site `fallenimmortals.old`, VM IP `192.168.10.11`, DB `homestead`). Paths in `Homestead.yaml`/`Vagrantfile` reference a specific developer's Windows machine (`C:\Users\ajezi\...`) and will need updating for any other environment. `vendor/laravel/homestead` (from `composer install`) is also required before `vagrant up`/`vagrant status` will even parse the `Vagrantfile` — it is not installed by default.
- **Database**: import `installation/fallendb.sql` into MySQL to create the schema (characters, guilds, inventory, chatroom, enemies, forge, temple, shop, trade, etc. — ~30 tables total).
- **DB credentials**: `db.php`, `indexdb.php`, and `index.php` all `include('db-conn.php')`, the single source of truth for `$dbhost`/`$database`/`$dbuser`/`$dbpass` and the `$conn` mysqli connection handle used everywhere. `db-conn.php` is gitignored (local-only); copy `db-conn.example.php` to `db-conn.php` and edit it to get a working connection.
- **Verifying changes**: no test suite — run `php -l <file>` after editing an endpoint. To see it rendered, note Apache's `DocumentRoot` is unrelated to this project; it's served via `mod_userdir` at `http://localhost/~<user>/<repo-dir>/...`.
- **PHP error log**: runtime fatals/warnings/notices land in `/var/log/php-fpm/www-error.log`, shared across other sites on the same box — `grep -i TheFallenImmortals` to filter to this project.
- **Query the DB directly**: use `/usr/bin/mariadb` (not the deprecated `mysql` alias) with the credentials from `db-conn.php`, e.g. `mariadb -u fallen -p'<pass>' fallendb -e "..."`.
- **Silent DB failures**: most `mysqli_query()` calls in this codebase have no `or die(mysqli_error($conn))` check (unlike a few, e.g. the chatroom insert in `register.php`). An endpoint can print a JS success message while its INSERT/UPDATE actually failed — when testing a DB-writing endpoint, verify the row directly in the DB, don't trust the response text alone.
- **Strict SQL mode vs. the original schema**: this server's `sql_mode` includes `STRICT_TRANS_TABLES` (the modern MySQL/MariaDB default), but `installation/fallendb.sql` has several `NOT NULL` columns with no `DEFAULT` (fixed for `characters` — see git history). Any INSERT across the schema that omits such a column will reject the whole row under strict mode; if a write silently fails elsewhere, check for this pattern before assuming app logic is wrong.
- **PHP7/8 removed functions**: this codebase was written for PHP5.6-era procedural PHP — `eregi()`/`ereg()` and other removed functions can still surface as `Call to undefined function` fatals in untouched files; replace with the `preg_match()` equivalent (add `/i` for case-insensitivity) rather than reintroducing the old function.

## Architecture

This is a **flat, multi-page PHP application** with no framework, router, or MVC layering. Every top-level `.php` file in the repo root is both a URL endpoint and a self-contained script.

**Request flow for an authenticated game action:**
1. Browser JS (in `js/*.js`, referenced from `game.php`/`index.php`) calls `evalAJAXHtml()` / `evalpostAJAXHtml()` (`js/ajax.js`), a raw `XMLHttpRequest` to a target PHP file.
2. The PHP endpoint starts with a near-identical boilerplate: `session_name("icsession"); session_start(); include('db.php'); include('varset.php');`, then loads the character row via `$_SESSION['userid']`.
3. `varset.php` is the de-facto "current player" context loader — it queries the `characters` table for the session's user, flattens ~25 fields into globals (`$charname`, `$charlvl`, `$chargold`, `$charstr`, etc.), computes item-bonus-modified stats from `inventory`, and loads the player's `guilds` row if in a guild. Nearly every endpoint depends on these globals existing.
4. The endpoint performs its logic (usually more raw `mysql_query()` calls, much of it string-concatenated SQL) and **responds with executable JavaScript, not HTML/JSON** — e.g. `print("fillDiv('displayArea','...');");` or `print("alert('...');")`. The browser's `eval()`s the AJAX response (see `evalAJAXHtml` in `js/ajax.js` and `fillDiv`/`$` in `js/dom.js`). This eval-response pattern is the core "protocol" of the whole app — expect it in any endpoint you touch.
5. `db.php` additionally runs global side effects on every include: it checks the requesting IP against the `banned` table and sweeps the `muted` table to auto-unmute expired mutes, posting a system message into `chatroom`.

**Naming/organization convention:** endpoint files are named for the action they perform (`fightenemy.php`, `castspell.php`, `equip.php`, `raisestat.php`, `buytype.php`, `mineore.php`, `forgecraft.php`, `travelmove.php`, ...) and generally correspond 1:1 with a JS function in `js/gamefunctions.js` / `js/homefunctions.js` that triggers the AJAX call. When adding a new game action, follow this existing pattern (new endpoint file + matching JS trigger) rather than introducing routing.

**Front-end**: no build tooling — plain `<script src="js/...">` tags pull in `jquery.js`, `ajax.js`, `dom.js`, `functions.js`, `homefunctions.js`, `gamefunctions.js`, `chatroomfunctions.js` from `js/` (CSS lives in `css/` the same way — both were flat in the repo root originally). `game.php` is the main authenticated game shell; `index.php` is the public landing/login/register page.

**`tfiTutorial/`** is a separate, older/parallel copy of parts of the app (its own `index.php`, `ajax.js`, `dom.js`, images) — treat it as a distinct, largely dead-end area unless a task specifically references it.

**Security note**: this codebase predates prepared statements in its own idioms — most SQL is still built via direct string concatenation of `$_POST`/`$_GET`/`$_SESSION` values. Migrating these to prepared statements (OOP `mysqli`, per the modernization directive above) is an active, ongoing goal, not something that requires being asked file-by-file — but given the scale (~780 write queries across 78 files, no test suite), convert in small batches (a handful of files at a time), verify each batch live against the real DB before moving on, and commit incrementally rather than attempting it in one pass. Passwords were previously hashed with `md5`/`sha1` plus a hardcoded salt (see `murder()` in `login.php`) — already migrated to `password_hash()`/`password_verify()` (bcrypt) with a migrate-on-login path for existing accounts; `murder()` remains only as a legacy-verification fallback, never for creating new hashes.
