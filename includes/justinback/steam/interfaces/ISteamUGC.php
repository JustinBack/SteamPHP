<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface ISteamUGC {

    public function EnumerateUserPublishedFiles(): array;

    public function GetPublishedFileDetails($sPublishedFileIds = null): object;

    public function GetSteamPersona($sPublishedFileIds = null): \justinback\steam\api\SteamPersona;
}
