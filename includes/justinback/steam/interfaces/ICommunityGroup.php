<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface ICommunityGroup {

    public function __construct($sGid, $sAPIKey = null, $sAppID = null);

    public function GetGroupName(): string;

    public function GetGroupURL(): string;

    public function GetGroupHeadline(): string;

    public function GetGroupSummary(): string;

    public function GetGroupID64(): string;

    public function GetGroupAvatars(): \justinback\steam\models\MGroupAvatar;

    public function GetGroupMembers(): \justinback\steam\models\MSteamPersonaCollection;

    public function GetGroupStats(): \justinback\steam\models\MGroupStats;
}
