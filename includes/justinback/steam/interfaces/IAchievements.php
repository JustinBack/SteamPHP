<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface IAchievements {

    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null);

    public function GetPlayerAchievements(): \justinback\steam\models\MPlayerAchievementCollection;

    public function GetPlayerAchievementsLocked(): \justinback\steam\models\MPlayerAchievementCollection;

    public function GetAchievementDetails($sApiname): \justinback\steam\models\MAchievementDetail;

    public function LockAchievement($sApiname): bool;

    public function UnlockAchievement($sApiname): bool;

    public function HasPlayerUnlockedAchievement($sApiname): bool;

    public function CSteamApp($sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\SteamApp;
}
