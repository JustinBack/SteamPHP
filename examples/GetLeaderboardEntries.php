<?php
/**
 * @package DoNotDocument
 */

require_once("../steam.php");


$apikey = "00000"; // STEAMWORKS WEB API KEY
$appid = 000000; // YOUR APP ID
$steamid = null; // NOT REQUIRED HERE


$steam  = new justinback\steam\manager($apikey, $appid, $steamid);


$i = 1;
$a = 1;
foreach($steam->game()->leaderboards()->GetLeaderboardsForGame() as $leaderboard){
    echo "<dl><dt>$a. Leaderboard: $leaderboard->name</dt>";
    foreach($steam->game()->leaderboards()->GetLeaderboardEntries($leaderboard->id, "RequestGlobal", "0", "50", true) as $entry){
    echo "<dd>$i.</dd>";
    echo "<dd>- Score: $entry->score</dd>";
    echo "<dd>- Rank: $entry->rank</dd>";
    echo "<dd>- SteamID: $entry->steamID</dd>";
    echo "<dd>----------</dd>";
    $i++;
    }
    echo "</dl>";
    $a++;
}
