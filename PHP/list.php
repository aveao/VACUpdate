<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Palanquin:500" rel="stylesheet"> 
<title>VacUpdate List</title>
</head>
<body bgcolor="#0D0D0D">
<?php
function postTable()
{
        $dir = 'sqlite:/home/ardaoftp/samba/VACUpdate.sqlite';
        $dbh  = new PDO($dir) or die("cannot open the database");
        $query =  "SELECT * FROM trackedusers";
        echo('<table style="width:100%;text-align=left;font-family: \'Palanquin\', sans-serif;color: white;" border="1">');
        echo ("<tr><th>SteamID</th><th>Steam Name</th><th>Added By</th><th>Banned</th></tr>");
        foreach ($dbh->query($query) as $row)
        {
            echo ('<tr>');
            echo ("<td>" . $row[0] . "</td><td>" . $row[2] . "</td><td>" . $row[1] . "</td><td>" . emojify($row[3]) . "</td>");
            echo ('</tr>');
        }
        echo('</table>');
        $query = null;
        $dbh = null;
}
function emojify($input)
{
    return str_replace("0","✖️",str_replace("1","✔️",$input));
}
postTable();
?>
<br><br><a style="font-family: \'Palanquin\', sans-serif; color: white;" href=index.php>⬅️ Return to main page</a>
</body>
</html>
