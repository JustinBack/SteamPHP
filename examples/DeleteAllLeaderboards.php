<?php
/**
 * @package DoNotDocument
 */

require_once("steam.php");


$sApiKey = "00000"; // STEAMWORKS WEB API KEY
$iAppID = 000000; // YOUR APP ID
$sSteamid = null; // NOT REQUIRED HERE


$steam  = new justinback\steam\manager($sApiKey, $iAppID, $sSteamid);


foreach($steam->game()->leaderboards()->GetLeaderboardsForGame() as $leaderboard){
    var_dump($steam->game()->leaderboards()->DeleteLeaderboard($leaderboard->name));
    echo "<br>";
}