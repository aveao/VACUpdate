<?php
require_once "common.php";
session_start();
function escape($thing) {
    return htmlentities($thing);
}
function run() {
    $consumer = getConsumer();
    $return_to = getReturnTo();
    $response = $consumer->complete($return_to);
    if ($response->status == Auth_OpenID_CANCEL) {
        $msg = 'Verification cancelled.';
    } else if ($response->status == Auth_OpenID_FAILURE) {
        $msg = "OpenID authentication failed: " . $response->message;
    } else if ($response->status == Auth_OpenID_SUCCESS) {
        $openid = $response->getDisplayIdentifier();
        $esc_identity = escape($openid);
        $slashpos = strrpos($esc_identity, "/") + 1;
        $steamid = substr($esc_identity, $slashpos);
        $loginok = false;
        $sessionid = guidv4();

        if (file_exists("whitelist.txt"))
        {
            if (preg_match('/'.$steamid.'/', file_get_contents("whitelist.txt"))) // pro tip: make it utf8
            {
                $loginok = true;
            }
        }
        else
        {
            $loginok = true;
        }
        
        if ($loginok)
        {
            $_SESSION["vacupdatesessionid"] = $sessionid;
            $_SESSION["vacupdatesteamid"] = $steamid;

            $dirasd = 'sqlite:/home/ardaoftp/samba/VACUpdate.sqlite';
            $dbhasd = new PDO($dirasd) or die("cannot open the database");
            
            $stmtfg = $dbhasd->prepare('DELETE FROM users WHERE steamid = ?');
            $stmtfg->execute(array($steamid));

            $stmtasd = $dbhasd->prepare('INSERT INTO users (steamid, sessionid) VALUES (?, ?)');
            $stmtasd->execute(array($steamid, $sessionid));

            $stmtasd = null;
            $dbhasd = null;

            $success = "Logged in as " . $steamid . " - sessionid : " . $sessionid;
        }
        else
        {
            $success = "Couldn't login as " . $steamid . ". The server might have a whitelist (and you might not be whitelisted).";
        }
    }
    include 'index.php';
}

function guidv4()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

run();
?>