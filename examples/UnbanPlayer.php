<?php
/**
 * @package DoNotDocument
 */

require_once("../steam.php");


$apikey = "00000"; // STEAMWORKS WEB API KEY
$appid = 000000; // YOUR APP ID
$steamid = null; // STEAMID



$steam  = new justinback\steam\manager($apikey, $appid, $steamid);

$unbanned = $steam->player()->RemovePlayerGameBan();

if($unbanned){
    echo $steam->player()->GetPersonaName(). " has been unbanned!";
    return;
}
echo $steam->player()->GetPersonaName(). " has <b>NOT</b> been unbanned! The user doesn't own the game or he doesn't even exist?!?";