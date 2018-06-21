<?php
/**
 * @package DoNotDocument
 */

require_once("steam.php");


$sApiKey = getenv("apikey"); // STEAMWORKS WEB API KEY
$iAppID = getenv("appid"); // YOUR APP ID
$sSteamid = getenv("steamid"); // STEAMID



$steam  = new justinback\steam\manager($sApiKey, $iAppID, $sSteamid);

$unbanned = $steam->player()->RemovePlayerGameBan();

if($unbanned){
    echo $steam->player()->GetPersonaName(). " has been unbanned!";
    return;
}
echo $steam->player()->GetPersonaName(). " has <b>NOT</b> been unbanned! The user doesn't own the game or he doesn't even exist?!?";