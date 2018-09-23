<?php

/**
 * @package DoNotDocument
 */
require_once("steam.php");


$sApiKey = "00000"; // STEAMWORKS WEB API KEY
$iAppID = 000000; // YOUR APP ID
$sSteamid = null; // STEAMID


$steam = new justinback\steam\manager($sApiKey, $iAppID, $sSteamid);


$i = 1;
$a = 1;
foreach ($steam->game()->leaderboards()->GetLeaderboardsForGame() as $leaderboard) {
    echo "<dl><dt>$a. Leaderboard: $leaderboard->name</dt>";
    foreach ($steam->game()->leaderboards()->GetLeaderboardEntries($leaderboard->id, "RequestGlobal", "0", "50", false) as $entry) {
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
