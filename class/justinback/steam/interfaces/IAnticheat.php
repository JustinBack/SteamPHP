<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface IAnticheat {

    public function __construct($reportid = null, $sApiKey = null, $iGame = null, $sSteamid = null);

    public function set_reportid($reportid);

    public function RequestPlayerGameBan($sCheatDescription, $iDuration, $bDelayBan = false);
}
