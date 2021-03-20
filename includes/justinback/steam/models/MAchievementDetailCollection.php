<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\models;

final class MAchievementDetailCollection extends ArrayObject {

    public function __construct(MAchievementDetail ...$array) {
        parent::__construct($array);
    }

    public function append($value) {
        if ($value instanceof MAchievementDetail) {
            parent::append($value);
        } else {
            throw new Exception("Cannot append non MAchievementDetail to a " . __CLASS__);
        }
    }

    public function offsetSet($index, $newval) {
        if ($newval instanceof MAchievementDetail) {
            parent::offsetSet($index, $newval);
        } else {
            throw new Exception("Cannot add a non MAchievementDetail value to a " . __CLASS__);
        }
    }

}
