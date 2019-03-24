<?php

/**
 * @package DoNotDocument
 */
require_once("steam.php");


$sApiKey = "00000"; // STEAMWORKS WEB API KEY
$iAppID = 000000; // YOUR APP ID
$sSteamid = null; // NOT REQUIRED HERE


$steam = new justinback\steam\manager($sApiKey, $iAppID, $sSteamid);

try {
foreach ($steam->game()->leaderboards()->GetLeaderboardsForGame() as $leaderboard) { 
    print("<pre>" . print_r($steam->game()->leaderboards()->DeleteLeaderboard($leaderboard->name), true) . "</pre>");
    echo "<br>";
}

} catch (\justinback\steam\exceptions\SteamRequestException $ex) {
    // Something is funky here! Must be something on valves end
    print("<pre>" . print_r($ex, true) . "</pre>");
} catch (\justinback\steam\exceptions\SteamRequestParameterException $ex) {
    // A parameter supplied is invalid, could be the Leaderboard Name
    print("<pre>" . print_r($ex, true) . "</pre>");
} catch (\justinback\steam\exceptions\SteamEmptyException $ex) {
    // Your game doesn't have any leaderboards.
    print("<pre>" . print_r($ex, true) . "</pre>");
} catch (\justinback\steam\exceptions\SteamException $ex) {
    // App ID or the API Key is invalid.
    print("<pre>" . print_r($ex, true) . "</pre>");
}