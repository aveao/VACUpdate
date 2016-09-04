<?php
require_once "common.php";
global $pape_policy_uris;
session_start();
?>
<html>
  <head><title>VacUpdate Main Menu</title><link href="https://fonts.googleapis.com/css?family=Palanquin:500" rel="stylesheet"></head>
  <style type="text/css">
      * {
        font-family: verdana,sans-serif;
      }
      body {
        width: 50em;
        margin: 1em;
      }
      div {
        padding: .5em;
      }
      table {
        margin: none;
        padding: none;
      }
      .alert {
        border: 1px solid #e7dc2b;
        background: #fff888;
      }
      .success {
        border: 1px solid #669966;
        background: #88ff88;
      }
      .error {
        border: 1px solid #ff0000;
        background: #ffaaaa;
      }
  </style>
  <body bgcolor="#0D0D0D">
    <h1 style="font-family: \'Palanquin\', sans-serif; color: white;">VacUpdate</h1>
    
    <?php if (isset($msg)) { print "<div class=\"alert\">$msg</div>"; } ?>
    <?php if (isset($error)) { print "<div class=\"error\">$error</div>"; } ?>
    <?php if (isset($success)) { print "<div class=\"success\">$success</div>"; } ?>

    <div id="verify-form">
      <?php 
      
        function verifylogin()
        {
            if (isset($_SESSION["vacupdatesessionid"]))
            {
            $dir = 'sqlite:/home/ardaoftp/samba/VACUpdate.sqlite';
            $dbh = new PDO($dir) or die("cannot open the database");
            $query=$dbh->prepare("SELECT * FROM users WHERE steamid = ? AND sessionid = ?");
            $query->execute(array($_SESSION["vacupdatesteamid"], $_SESSION["vacupdatesessionid"]));
            $verified = false;

            foreach ($query as $row)
            {
              $verified = true;
            }

            $stmt = null;
            $dbh = null;

            return $verified;
            }
            else
            {
              return false;
            }
        }

      echo '<a style="font-family: \'Palanquin\', sans-serif; color: white;" href=list.php>➡️ View List</a><br><br>';

      if (verifylogin())
      {
        echo '<form method="get" style="font-family: \'Palanquin\', sans-serif; color: white;" action="logout.php"><input type="submit" value="Logoff" /></form>';

        echo '<form style="font-family: \'Palanquin\', sans-serif; color: white;" action="addtrack.php" method="post"> SteamID, SteamID64, CSGO status or Custom Link: <input type="text" name="input"><br><input type="submit"></form>';
      }
      else
      {
        echo '<form method="get" style="font-family: \'Palanquin\', sans-serif; color: white;" action="try_auth.php"><input type="submit" value="Login" /></form>';
      }
      
    ?>
    </div>
  </body>
</html>