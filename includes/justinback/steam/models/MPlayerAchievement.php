<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\models;

final class MPlayerAchievement extends \stdClass {

    public string $apiname;
    public bool $achieved;
    public int $unlocktime;

    public function __construct(string $sApiName, bool $bAchieved, int $iUnlockTime) {
        $this->apiname = $sApiName;
        $this->achieved = $bAchieved;
        $this->unlocktime = $iUnlockTime;
    }

}
