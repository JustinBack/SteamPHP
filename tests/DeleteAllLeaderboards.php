<?php
/**
 * @package DoNotDocument
 */

require_once("steam.php");


$apikey = getenv("apikey"); // STEAMWORKS WEB API KEY
$appid = getenv("appid"); // YOUR APP ID
$steamid = getenv("steamid"); // STEAMID


$steam  = new justinback\steam\manager($apikey, $appid, $steamid);


foreach($steam->game()->leaderboards()->GetLeaderboardsForGame() as $leaderboard){
    var_dump($steam->game()->leaderboards()->DeleteLeaderboard($leaderboard->name));
    echo "\n";
}