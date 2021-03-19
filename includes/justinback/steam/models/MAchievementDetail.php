<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\models;

class MAchievementDetail {

    public string $name;
    public int $defaultValue;
    public string $displayName;
    public bool $hidden;
    public string $description;
    public string $icon;
    public string $iconGray;

    public function __construct(string $sName, int $iDefaultValue, string $sDisplayName, bool $bHidden, string $sDescription, string $sIcon, string $sIconGray) {
        $this->name = $sName;
        $this->defaultValue = $iDefaultValue;
        $this->displayName = $sDisplayName;
        $this->hidden = $bHidden;
        $this->description = $sDescription;
        $this->icon = $sIcon;
        $this->iconGray = $sIconGray;
    }

}
