<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface IUtils {

    public static function ConstructApiUris(bool $bPublic, string $sApiInterface, string $sApiMethod, string $sApiMethodVersion, string $sGetParameters = null): string;

    public static function ConvertSteamIDs(string $sSteamID): object;

    public static function IsValidSteamID(string $sSteamID): bool;
}
