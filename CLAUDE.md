# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

The Fallen Immortals is a text-based browser MMORPG, written as procedural PHP circa 2009-2010. It targets **PHP 5.6 and MySQL with the legacy `mysql_*` extension** — this code does **not** run on PHP 7+. The author (see README.md) describes it explicitly as old, unmaintained, dated code kept public for reference/research purposes, not something to be hosted as-is or modeled architecturally for new work. When making changes, preserve the existing procedural style and PHP 5.6/`mysql_*` API constraints rather than modernizing incidentally — any migration to `mysqli`/PDO or PHP 7+ is a large, deliberate undertaking, not a drive-by fix.

## Running the project

There is no build step, package manager for the app itself, no test suite, and no linter configured. `composer.json` only pulls in `laravel/homestead` as a dev dependency for the Vagrant box.

- **Local VM**: `vagrant up` boots a Homestead box configured in `Homestead.yaml` (PHP 5.6, site `fallenimmortals.old`, VM IP `192.168.10.11`, DB `homestead`). Paths in `Homestead.yaml`/`Vagrantfile` reference a specific developer's Windows machine (`C:\Users\ajezi\...`) and will need updating for any other environment.
- **Database**: import `installation/fallendb.sql` into MySQL to create the schema (characters, guilds, inventory, chatroom, enemies, forge, temple, shop, trade, etc. — ~30 tables total).
- **DB credentials**: hardcoded in `db.php`, `indexdb.php`, and inline in `index.php` (`$dbhost`/`$database`/`$dbuser`/`$dbpass`, default `localhost`/`homestead`/`homestead`/`secret`). All three must be kept in sync if credentials change — there is no shared config file.

## Architecture

This is a **flat, multi-page PHP application** with no framework, router, or MVC layering. Every top-level `.php` file in the repo root is both a URL endpoint and a self-contained script.

**Request flow for an authenticated game action:**
1. Browser JS (in `js/*.js`, referenced from `game.php`/`index.php`) calls `evalAJAXHtml()` / `evalpostAJAXHtml()` (`js/ajax.js`), a raw `XMLHttpRequest` to a target PHP file.
2. The PHP endpoint starts with a near-identical boilerplate: `session_name("icsession"); session_start(); include('db.php'); include('varset.php');`, then loads the character row via `$_SESSION['userid']`.
3. `varset.php` is the de-facto "current player" context loader — it queries the `characters` table for the session's user, flattens ~25 fields into globals (`$charname`, `$charlvl`, `$chargold`, `$charstr`, etc.), computes item-bonus-modified stats from `inventory`, and loads the player's `guilds` row if in a guild. Nearly every endpoint depends on these globals existing.
4. The endpoint performs its logic (usually more raw `mysql_query()` calls, much of it string-concatenated SQL) and **responds with executable JavaScript, not HTML/JSON** — e.g. `print("fillDiv('displayArea','...');");` or `print("alert('...');")`. The browser's `eval()`s the AJAX response (see `evalAJAXHtml` in `js/ajax.js` and `fillDiv`/`$` in `js/dom.js`). This eval-response pattern is the core "protocol" of the whole app — expect it in any endpoint you touch.
5. `db.php` additionally runs global side effects on every include: it checks the requesting IP against the `banned` table and sweeps the `muted` table to auto-unmute expired mutes, posting a system message into `chatroom`.

**Naming/organization convention:** endpoint files are named for the action they perform (`fightenemy.php`, `castspell.php`, `equip.php`, `raisestat.php`, `buytype.php`, `mineore.php`, `forgecraft.php`, `travelmove.php`, ...) and generally correspond 1:1 with a JS function in `js/gamefunctions.js` / `js/homefunctions.js` that triggers the AJAX call. When adding a new game action, follow this existing pattern (new endpoint file + matching JS trigger) rather than introducing routing.

**Front-end**: no build tooling — plain `<script src="...">` tags pull in `jquery.js`, `ajax.js`, `dom.js`, `functions.js`, `homefunctions.js`, `gamefunctions.js`, `chatroomfunctions.js`. `game.php` is the main authenticated game shell; `index.php` is the public landing/login/register page (and itself embeds a second, duplicate set of DB credentials for the online-player-count widget).

**`tfiTutorial/`** is a separate, older/parallel copy of parts of the app (its own `index.php`, `ajax.js`, `dom.js`, images) — treat it as a distinct, largely dead-end area unless a task specifically references it.

**Security note (context, not a to-do list)**: this codebase predates prepared statements in its own idioms — nearly all SQL is built via direct string concatenation of `$_POST`/`$_GET`/`$_SESSION` values, and passwords are hashed with `md5`/`sha1` plus a hardcoded salt (see `murder()` in `login.php`). This is inherent to the project's stated purpose (a historical reference implementation) — do not attempt a blanket security rewrite unless specifically asked; if asked to touch a specific endpoint, note this class of issue but scope fixes to what's requested.
