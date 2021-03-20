<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\models;

final class MPlayerAchievementCollection extends ArrayObject {

    public function __construct(MPlayerAchievement ...$array) {
        parent::__construct($array);
    }

    public function append($value) {
        if ($value instanceof MPlayerAchievement) {
            parent::append($value);
        } else {
            throw new Exception("Cannot append non MPlayerAchievement to a " . __CLASS__);
        }
    }

    public function offsetSet($index, $newval) {
        if ($newval instanceof MPlayerAchievement) {
            parent::offsetSet($index, $newval);
        } else {
            throw new Exception("Cannot add a non MPlayerAchievement value to a " . __CLASS__);
        }
    }

}
