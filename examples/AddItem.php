<?php

/**
 * @package DoNotDocument
 */
require_once("../steam.php");


$sApiKey = "00000"; // STEAMWORKS WEB API KEY
$iAppID = 000000; // YOUR APP ID
$sSteamid = null; // STEAMID



$steam = new \justinback\steam\manager($sApiKey, $iAppID, $sSteamid);
try {
    $item = $steam->player()->inventory()->AddItem("10", null, true);

    echo "<dl><dt>Item</dt>";
    echo "<dd>Acquired: $item->acquired</dd>";
    echo "<dd>Game: $item->game</dd>";
    echo "<dd>Itemdefid: $item->itemdefid</dd>";
    echo "<dd>ItemID: $item->itemid</dd>";
    echo "<dd>Origin: $item->origin</dd>";
    echo "<dd>Quantity: $item->quantity</dd>";
    echo "<dd>State: $item->state</dd>";
    echo "<dd>State changed timestamp: $item->state_changed_timestamp</dd>";
    echo "</dl>";
} catch (\justinback\steam\exceptions\SteamRequestException $ex) {
    // Something is funky here! Must be something on valves end
    print("<pre>" . print_r($ex, true) . "</pre>");
} catch (\justinback\steam\exceptions\SteamRequestParameterException $ex) {
    // A parameter supplied is invalid, could be the ItemDefID or the SteamID
    print("<pre>" . print_r($ex, true) . "</pre>");
} catch (\justinback\steam\exceptions\SteamException $ex) {
    // App ID, Steam ID or the API Key is invalid.
    print("<pre>" . print_r($ex, true) . "</pre>");
}