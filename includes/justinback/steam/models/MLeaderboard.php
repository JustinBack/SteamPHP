<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\models;

final class MLeaderboard extends \stdClass {

    public int $ID;
    public string $Name;
    public MSorting $Sorting;
    public MLeaderboardDisplayType $displayType;
    public int $Entries;
    public bool $onlyTrustedWrites;
    public bool $onlyFriendsReads;

    public function __construct(int $ID, string $Name, MSorting $Sorting, MLeaderboardDisplayType $displayType, bool $oTW, bool $oFR, int $Entries) {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Entries = $Entries;
        $this->Sorting = $Sorting;
        $this->displayType = $displayType;
        $this->onlyTrustedWrites = $oTW;
        $this->onlyFriendsReads = $oFR;
    }

}
