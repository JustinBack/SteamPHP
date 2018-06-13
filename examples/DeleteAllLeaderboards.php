<?php
/**
 * @package DoNotDocument
 */

require_once("../steam.php");


$apikey = "00000"; // STEAMWORKS WEB API KEY
$appid = 000000; // YOUR APP ID
$steamid = null; // NOT REQUIRED HERE


$steam  = new justinback\steam\manager($apikey, $appid, $steamid);


foreach($steam->game()->leaderboards()->GetLeaderboardsForGame() as $leaderboard){
    var_dump($steam->game()->leaderboards()->DeleteLeaderboard($leaderboard->name));
    echo "<br>";
}