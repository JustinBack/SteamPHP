<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface ISteamApp {

    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null);

    public function CheckAppOwnership(): bool;

    public function GetNumberOfCurrentPlayers(): int;

    public function CAchievements($sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\achievements;

    public function CLeaderboards($sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\leaderboards;

    public function CSteamUGC($sPublishedFileId, $sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\ugc;

    public function CStorePage($iGame = null): \justinback\steam\api\store;
}
