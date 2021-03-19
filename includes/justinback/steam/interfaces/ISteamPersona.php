<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface ISteamPersona {

    public function CAchievements($sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\Achievements;

    public function CAntiCheat($sReportID = null, $sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\AntiCheat;

    public function CSteamMicrotransactions($bTesting = false, $sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\SteamMicrotransactions;

    public function CUserInventory($sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\UserInventory;

    public function GetAvatar(): object;

    public function GetFriendList(): array;

    public function GetPersonaName(): string;

    public function GetPlayerBans(): array;

    public function GetRealName(): string;

    public function GetSteamLevel(): int;

    public function GetUserGroupList(): array;

    public function ReportAbuse($sSteamidreporter, $iAbuseType, $iContentType, $sDescription, $sGid = null): bool;
}
