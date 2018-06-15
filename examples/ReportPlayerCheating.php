<?php
/**
 * @package DoNotDocument
 */

require_once("../steam.php");


$apikey = "00000"; // STEAMWORKS WEB API KEY
$appid = 000000; // YOUR APP ID
$steamid = null; // STEAMID


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