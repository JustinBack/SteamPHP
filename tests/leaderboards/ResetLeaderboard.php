<?php
/**
 * @package DoNotDocument
 */

require_once(__DIR__."/../steam.php");


$sApiKey = getenv("apikey"); // STEAMWORKS WEB API KEY
$iAppID = getenv("appid"); // YOUR APP ID
$sSteamid = getenv("steamid"); // STEAMID


$steam  = new justinback\steam\manager($sApiKey, $iAppID, $sSteamid);

if($steam->game()->leaderboards()->ResetLeaderboard("travisci_test")){
    echo "true";
    return;
}
echo "Leaderboard could not be reset!";