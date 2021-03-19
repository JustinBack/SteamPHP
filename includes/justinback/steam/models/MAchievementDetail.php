<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\models;

class MAchievementDetail {

    public static string $name;
    public static int $defaultValue;
    public static string $displayName;
    public static bool $hidden;
    public static string $description;
    public static string $icon;
    public static string $iconGray;

    public function __construct(string $sName, int $iDefaultValue, string $sDisplayName, bool $bHidden, string $sDescription, string $sIcon, string $sIconGray) {
        self::$name = $sName;
        self::$defaultValue = $iDefaultValue;
        self::$displayName = $sDisplayName;
        self::$hidden = $bHidden;
        self::$description = $sDescription;
        self::$icon = $sIcon;
        self::$iconGray = $sIconGray;
    }

}
