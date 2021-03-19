<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface IAchievements {

    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null);

    public function GetPlayerAchievements();

    public function GetPlayerAchievementsLocked();

    public function GetAchievementDetails($sApiname);

    public function LockAchievement($sApiname);

    public function UnlockAchievement($sApiname);

    public function HasPlayerUnlockedAchievement($sApiname);

    public function game($sApiKey = null, $iGame = null, $sSteamid = null);
}
