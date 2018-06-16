<?php
/**
 * @package DoNotDocument
 */

require_once("steam.php");


$apikey = getenv("apikey"); // STEAMWORKS WEB API KEY
$appid = getenv("appid"); // YOUR APP ID
$steamid = getenv("steamid"); // STEAMID



$steam  = new justinback\steam\manager($apikey, $appid, $steamid);

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