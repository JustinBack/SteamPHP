<?php

/**
 * @package DoNotDocument
 * 
 * This is an experimental example, here we can check if the user is a valve employee, community moderator or steamworks developer. Note: This isnt reliable at all since its not required to join the respective groups.
 * 
 */
require_once("steam.php");


$sApiKey = "00000"; // STEAMWORKS WEB API KEY
$iAppID = 000000; // YOUR APP ID
$sSteamid = null; // STEAMID



$steam = new justinback\steam\manager($sApiKey, $iAppID, $sSteamid);

/*
 * 4145017 is the GID of the Steamworks Development Group | https://steamcommunity.com/gid/4145017
 */
$IsDeveloper = in_array(4145017, array_column($steam->player()->GetUserGroupList(), 'gid'));

/*
 * 4 is the GID of the Valve Software Group | https://steamcommunity.com/gid/4
 */
$IsValveEmployee = in_array(4, array_column($steam->player()->GetUserGroupList(), 'gid'));

/*
 * 28361337 is the GID of the Community Moderator Group, NOT GLOBAL MODERATORS | https://steamcommunity.com/gid/28361337
 */
$IsCommunityModerator = in_array(28361337, array_column($steam->player()->GetUserGroupList(), 'gid'));

echo "User: "; var_dump($steam->player()->GetPersonaName());
echo "Is Steamworks Developer: "; var_dump($IsDeveloper);
echo "Is Valve Employee: "; var_dump($IsValveEmployee);
echo "Is Community Moderator: "; var_dump($IsCommunityModerator);