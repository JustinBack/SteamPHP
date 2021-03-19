<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface ISteamLeaderboards {

    public function CSteamApp($sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\SteamApp;

    public function DeleteLeaderboard(string $sName): bool;

    public function FindOrCreateLeaderboard($sName, $sSortMethod = "Ascending", $sDisplayType = "Numeric", $bCreateIfNotFound = true, $bOnlyTrustedWrites = false, $bOnlyFriendsReads = false): object;

    public function GetLeaderboardEntries($sLeaderboardId, $sDataRequest, $iRangeStart, $iRangeEnd, $bSteamid = true): array;

    public function GetLeaderboardsForGame(): object;

    public function ResetLeaderboard($sLeaderboardId): bool;

    public function SetLeaderboardScore($sLeaderboardId, $sScore, $sScoreMethod, $rDetails = null): object;
}
