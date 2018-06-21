<?php
/**
 * @package DoNotDocument
 */

require_once("steam.php");


$sApiKey = getenv("apikey"); // STEAMWORKS WEB API KEY
$iAppID = getenv("appid"); // YOUR APP ID
$sSteamid = getenv("steamid"); // STEAMID


$steam  = new justinback\steam\manager($sApiKey, $iAppID, $sSteamid);


foreach($steam->game()->leaderboards()->GetLeaderboardsForGame() as $leaderboard){
    var_dump($steam->game()->leaderboards()->DeleteLeaderboard($leaderboard->name));
    echo "\n";
}