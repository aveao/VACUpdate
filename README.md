# VACUpdate
Make a list of Steam users and check their VAC Status. Basically [this](https://github.com/jung35/VacStatus) in PHP frontend and C# backend, and for use on personal servers. It has multi-user support, however you can whitelist people. My own copy is whitelisted for me only.

## Currently not complete!

## Dependencies

php-sqlite3 and php-bcmath. On linux, install with `sudo apt-get install php-sqlite3 php-bcmath` and then do `sudo phpenmod pdo_sqlite` and `sudo phpenmod bcmath`, and then finally `sudo apachectl graceful`.

mono-complete (or if you are on windows, .net framework 4.5): http://www.mono-project.com/docs/getting-started/install/linux/

## How to install

 - Install dependencies.
 - Find a way to run the C# backend hourly. I prefer cronjobs on linux and wrapping the code in `while(true) - wait` on windows.
 - Put the php script to somewhere accessible from your preference of server (I use XAMPP on Windows and Apache2/httpd on linux, depending on distro. Quick note, I used php7 while developing this, while it is likely to work with older versions, I don't promise so). 
 - Set the location of the sqlite database on the files `index.php`, `finish_auth.php` and `list.php`.
 - If you are going to enable the whitelist, put a file called whitelist.txt on the same folder as the php files. Put a steamid64 on each line.
 - Run the C# backend and start the php server.
