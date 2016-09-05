<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Palanquin:500" rel="stylesheet"> 
<title>VacUpdate Tracking Adder</title>
</head>
<body bgcolor="#0D0D0D">
<div style="font-family: \'Palanquin\', sans-serif; color: white;">

<?php
session_start();

$input = $_POST["input"];

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

if (verifylogin())
{

function startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function AddToDB($dbsteamid)
{

    $SteamProfileXML = file_get_contents("http://steamcommunity.com/profiles/".$dbsteamid."?xml=1");
    if (preg_match('/The specified profile could not be found./',$SteamProfileXML))
    {
         'err: not found';
    }
    else
    {
        $dir = 'sqlite:/home/ardaoftp/samba/VACUpdate.sqlite';
        $dbh = new PDO($dir) or die("cannot open the database");
        $xml=simplexml_load_string($SteamProfileXML) or die("err: Cannot create xml");
        $name = $xml->steamID;

        $stmt = $dbh->prepare('INSERT INTO trackedusers (steamid, steamname, addedby, banned) VALUES (?, ?, ?, 0)');
        $stmt->execute(array($dbsteamid, $_SESSION["vacupdatesteamid"], $name));

        $stmt = null;
        $dbh = null;

        echo("Added " . $dbsteamid . "<br>");
    }
}

function AddSteamIDList($text)
{
    preg_match_all("/STEAM_[0,1]:[0,1]:[0-9]{1,9}/", $text, $SteamIDs);
    
    foreach($SteamIDs[0] as $k=>$v) 
    {
        AddToDB(SteamIDToSteamID64($v));
    }
}

function AddSteamID64List($text)
{
    preg_match_all("/7656119[0-9]{10}/", $text, $SteamIDs);
    
    foreach($SteamIDs[0] as $k=>$v) 
    {
        AddToDB($v);
    }
}

function CustomURLToSteamID64($id)
{
    $SteamProfileXML = file_get_contents("http://steamcommunity.com/id/".$id."?xml=1");
    if (preg_match('/The specified profile could not be found./',$SteamProfileXML))
    {
        return 'err: not found';
    }
    else
    {
        $xml=simplexml_load_string($SteamProfileXML) or die("err: Cannot create xml");
        return $xml->steamID64;
    }
} 

function SteamIDToSteamID64($id)
{ // All credits go to Seather of AlliedMods: https://forums.alliedmods.net/showpost.php?p=565979&postcount=16
    $iServer = "0";
    $iAuthID = "0";
	
	$szAuthID = $id;
	
	$szTmp = strtok($szAuthID, ":");
	
	while(($szTmp = strtok(":")) !== false)
    {
        $szTmp2 = strtok(":");
        if($szTmp2 !== false)
        {
            $iServer = $szTmp;
            $iAuthID = $szTmp2;
        }
    }
    if($iAuthID == "0")
        return "0";

    $i64friendID = bcmul($iAuthID, "2");

    //Friend ID's with even numbers are the 0 auth server.
    //Friend ID's with odd numbers are the 1 auth server.
    $i64friendID = bcadd($i64friendID, bcadd("76561197960265728", $iServer)); 
	
	return $i64friendID;
}

if (startsWith($input, "STEAM_")) //SteamID - Example: STEAM_1:0:37016670
{
    AddSteamIDList($input);
}
else if (startsWith($input, "765")) //SteamID64 - Example: 76561198034299068
{
    AddSteamID64List($input);
}
else if (startsWith($input, "http://steamcommunity.com/")) //Link - Example: http://steamcommunity.com/profiles/76561198034299068
{
    $slashpos = strrpos($input, "/") + 1;
    $steamid = substr($input, $slashpos);
    if (startsWith($steamid, "765"))
    {
        AddSteamID64List($input);
    }
    else
    {
        AddToDB(CustomURLToSteamID64($steamid));
    }
}
else if (preg_match("/userid name uniqueid connected ping loss state rate/", $input)) //CS:GO status Command Output - Example: https://github.com/ardaozkal/VACUpdate/issues/3#issuecomment-244571684
{
    AddSteamIDList($input);
}
else //Assume CustomLink - Example: ardaozkal
{
    AddToDB(CustomURLToSteamID64($input));
}

}
else
{
    echo "not authorized";
}
?>
<br><br><a href=index.php>⬅️ Return to main page</a>
</div>
</body>
</html>
