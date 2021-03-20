<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\models;

final class MLeaderboardCollection extends ArrayObject {

    public function __construct(MLeaderboard ...$array) {
        parent::__construct($array);
    }

    public function append($value) {
        if ($value instanceof MLeaderboard) {
            parent::append($value);
        } else {
            throw new Exception("Cannot append non MAchievementDetail to a " . __CLASS__);
        }
    }

    public function offsetSet($index, $newval) {
        if ($newval instanceof MLeaderboard) {
            parent::offsetSet($index, $newval);
        } else {
            throw new Exception("Cannot add a non MAchievementDetail value to a " . __CLASS__);
        }
    }

}
