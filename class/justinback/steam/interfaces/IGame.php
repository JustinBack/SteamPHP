<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface IGame {

    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null);

    public function CheckAppOwnership(): bool;

    public function GetNumberOfCurrentPlayers(): int;

    public function achievements($sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\achievements;

    public function leaderboards($sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\leaderboards;

    public function ugc($sPublishedFileId, $sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\ugc;

    public function store($iGame = null): \justinback\steam\api\store;
}
