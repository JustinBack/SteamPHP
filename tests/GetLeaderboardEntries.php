<?php
/**
 * @package DoNotDocument
 */

require_once("steam.php");


$apikey = getenv("apikey"); // STEAMWORKS WEB API KEY
$appid = getenv("appid"); // YOUR APP ID
$steamid = getenv("steamid"); // STEAMID


$steam  = new justinback\steam\manager($apikey, $appid, $steamid);

var_dump($steam->game()->leaderboards()->GetLeaderboardsForGame());
