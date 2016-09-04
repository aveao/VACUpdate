# VACUpdate
Make a list of Steam users and check their VAC Status. Basically [this](https://github.com/jung35/VacStatus) in PHP frontend and C# backend, and for use on personal servers. It has multi-user support, however you can whitelist people. My own copy is whitelisted for me only.

## Complete!

Feels incomplete? Bribe me. BTC: 38ehnuPM9PUbAxgmV13X7ygdDBTwGY17BN

## Dependencies

php-sqlite3 and php-bcmath. On linux, install with `sudo apt-get install php-sqlite3 php-bcmath` and then do `sudo phpenmod pdo_sqlite` and `sudo phpenmod bcmath`, and then finally `sudo apachectl graceful`.

if you are going to use C#, and are on linux, mono-complete (note: I wasn't able to get this working): http://www.mono-project.com/docs/getting-started/install/linux/

## How to install

 - Decide if you'll use the C# or python version. Python version runs better on linux.
 - Install dependencies.
 - if C#: Find a way to run the C# backend hourly. I prefer cronjobs on linux and wrapping the code in `while(true) - wait` on windows.
 - if Python: Find a way to run the python script hourly. On linux, use cronjobs. On windows, make a batch script with 1 hour etc wait.
 - Put the php script to somewhere accessible from your preference of server (I use XAMPP on Windows and Apache2/httpd on linux, depending on distro. Quick note, I used php7 while developing this, while it is likely to work with older versions, I don't promise so). 
 - Set the location of the sqlite database on the files `index.php`, `finish_auth.php` and `list.php`.
 - If you are going to enable the whitelist, put a file called whitelist.txt on the same folder as the php files. Put a steamid64 on each line.
 - Make sure the db file has enough permissions. (777 master race)
 - Run the C#/python backend and start the php server.
