<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\models;

final class MGroupAvatar extends \stdClass {

    public string $small;
    public string $medium;
    public string $large;

    public function __construct(string $sSmall, string $sMedium, string $sLarge) {
        $this->small = $sSmall;
        $this->medium = $sMedium;
        $this->large = $sLarge;
    }

}
