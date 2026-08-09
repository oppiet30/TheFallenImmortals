# PLEASE DO NOT HOST THIS AS IT IS!!!! FOR RESEARCH PURPOSES ONLY

# I HOSTED THIS AS A REFERENCE TO WORK I HAVE DONE IN THE PAST, AND FOR ANYONE INTERESTED IN HOW THIS GAME WORKED. THIS IS PROCEEDURAL PHP, AND I WOULDN'T WISH THAT ON MY WORST ENEMY! IF YOU PLAN ON DOING ANYTHING WITH THIS, YOU WILL NEED TO MAKE HEAVY MODIFICATION. SEE MY COMMENT IN [ISSUE#2](https://github.com/AlexJezior/TheFallenImmortals/issues/2).

# The Fallen Immortals 
You can see this site today at: http://fallen.jez-your.com/
This originally targeted PHP 5.6, but the codebase has since been migrated off the removed `mysql_` extension to `mysqli_`, so it now runs on PHP 7+.

## History
In December 2009, I had an idea to develop a text-based MMORPG game for the browser, so that any user, coming from any 
computer environment would have an opportunity to connect. February 2010 was the first time the project came to life on 
the internet, and lasted until the summer of 2014. For years, I have held onto this project with the hope, that one day 
soon, I would redevelop it with better, and up to date, coding conventions. As my development ideas have grown, so has 
my want to develop a variety of practical applications for the internet. With that being said, I am letting my stepping 
stone grow some wings and tossing it out to the open world!

######

Feel free to use, and develop upon this project per the License attached to this repository. Keeping in mind, this 
project uses dated and deprecated PHP and MySQL coding techniques that were acceptable in 2009. It originally required 
**PHP 5.6** and would not run on PHP 7, but all `mysql_*` calls have since been migrated to `mysqli_*`, so it now runs 
on **PHP 7+**.

## Installation
So, you want to install this beast? In this section, I will help with the general setup of this project on your server. 
It's very straight forward, as long as you understand your way around the server. There's a lot of information on the 
internet, so it should not be a problem. ;)

######

You'll want to zip this project and extract it to your sites root path.

######

After the project files are on your server, let's setup the the database. Grab the .sql file from the "installation" 
folder and import the SQL file, via phpMyAdmin panel, to the database you created for this project.

######

With the database tables populated to your table, we will want to setup the association to your database in the code. 
Copy `db-conn.example.php` to `db-conn.php`, then edit `db-conn.php` with your database connection string 
(`$dbhost`/`$database`/`$dbuser`/`$dbpass`). `db-conn.php` is gitignored, so your credentials stay local. Once you have 
done that, you should be ready to register your first character! 

######

**REMEMBER TO DELETE THE INSTALLATION FOLDER AFTER INSTALLING THIS PROJECT ON YOUR SERVER. IT'S ONLY REQUIRED FOR 
INSTALLING THE SITE, NOTHING MORE. DO I NEED TO STRESS THE IMPORTANCE OF REMOVING THE SITE'S .ZIP FOLDER THAT YOU 
BROUGHT IT IN WITH?**

## Cron Jobs
A few scripts need to run on a schedule outside of a browser session — add these to your crontab (`crontab -e`), 
using the full path to your PHP CLI binary and to wherever you extracted this project:

```
# Daily reset (temple flag, mana, blessings, login flags) - once a day
@daily /usr/bin/php /path/to/TheFallenImmortals/0000r0000e0000s0000e0000t.php >> /path/to/TheFallenImmortals/cron.log 2>&1

# Monthly temple donation pot draw - once a month
@monthly /usr/bin/php /path/to/TheFallenImmortals/0000m0000o0000n0000t0000h0000l0000y.php >> /path/to/TheFallenImmortals/cron.log 2>&1

# Passive health regen (1% of max endurance every 5 minutes)
*/5 * * * * /usr/bin/php /path/to/TheFallenImmortals/0000h0000e0000a0000l0000t0000h.php >> /path/to/TheFallenImmortals/cron.log 2>&1
```

These filenames are intentionally obfuscated (e.g. `0000monthly.php` spells "monthly.php" with zeros interspersed 
between each letter) since they're cron-only scripts, not meant to be reachable or guessable as public URLs.

## Security Note
This repo's `images/` folder previously contained an `images/.htaccess.LCK` file with the contents `Alex||Alex.Jezior@gmail.com` 
— the original developer's name and email. Its naming and content match the "already compromised" bookkeeping markers left 
by mass PHP-infection toolkits, and it sat alongside an actual remote-code-execution backdoor (`images/68564.php`, since 
removed) that had been wired into the site's error handling. Both were present in this repo's very first commit, meaning 
the live site was already compromised before it was ever published here. The `.LCK` file itself was inert (Apache never 
reads a file that isn't literally named `.htaccess`) and has since been deleted; its contents are preserved here for the record.

## About the Developer
My name is Alexander Jezior, and I enjoy developing in PHP! Over the years, I have evolved my code base into something 
that this project does not accurately depict. This project merely shows an example of where I started, and gives a sense 
of my experience as a developer. Now-a-days, you can find me spending my time trying to build my own frame work, or CMS,
 and at times, dabbling with other developers ideas, such as, Laravel, or OctoberCMS. Feel free to connect with me, 
 I'm always happy to talk about programming!
