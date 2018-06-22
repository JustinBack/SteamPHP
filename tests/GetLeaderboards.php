<?php
/**
 * @package DoNotDocument
 */

require_once("../steam.php");


$sApiKey = getenv("apikey"); // STEAMWORKS WEB API KEY
$iAppID = getenv("appid"); // YOUR APP ID
$sSteamid = getenv("steamid"); // STEAMID


$steam  = new justinback\steam\manager($sApiKey, $iAppID, $sSteamid);

if($steam->game()->leaderboards()->GetLeaderboardsForGame() === NULL){
    echo "false";
    return;
}
echo "true";
return;