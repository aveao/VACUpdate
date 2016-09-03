# VACUpdate
Make a list of Steam users and check their VAC Status. Basically [this](https://github.com/jung35/VacStatus) in PHP frontend and C# backend, and for use on personal servers.

I can add multi-user support if there is enough interest and bribe (BTC: 38ehnuPM9PUbAxgmV13X7ygdDBTwGY17BN).

## Currently not complete!

## Dependencies

php-sqlite3. On linux, install with `sudo apt-get install php-sqlite3` and then do `sudo phpenmod pdo_sqlite` and `sudo apachectl graceful`.

mono-complete (or if you are on windows, .net framework 4.5): http://www.mono-project.com/docs/getting-started/install/linux/

## How to install

 - Install dependencies.
 - Find a way to run the C# backend hourly. I prefer cronjobs on linux and wrapping the code in `while(true) - wait` on windows.
 - Put the php script to somewhere accessible from your preference of server (I use XAMPP on Windows and Apache2/httpd on linux, depending on distro. Quick note, I used php7 while developing this, while it is likely to work with older versions, I don't promise so). 
 - Set your steamid and the location of the sqlite database on the file `finish_auth.php`.
 - Run the C# backend and start the php server.
