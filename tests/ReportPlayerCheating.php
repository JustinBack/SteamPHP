<?php
/**
 * @package DoNotDocument
 */

require_once("steam.php");


$apikey = getenv("apikey"); // STEAMWORKS WEB API KEY
$appid = getenv("appid"); // YOUR APP ID
$steamid = getenv("steamid"); // STEAMID


$steam  = new justinback\steam\manager($apikey, $appid, $steamid);

$report = $steam->player()->ReportPlayerCheating(); // Returns \justinback\steam\anticheat

echo $report->reportid; // Report ID


// We can ban this player too now!

$banned = $report->RequestPlayerGameBan("Aimbot and Wallhacks", "0", false);

echo "<br>";

if($banned){
    echo $steam->player()->GetPersonaName(). " has been banned!";
    return;
}
echo $steam->player()->GetPersonaName(). " has <b>NOT</b> been banned! The user doesn't own the game or he doesn't even exist?!?";