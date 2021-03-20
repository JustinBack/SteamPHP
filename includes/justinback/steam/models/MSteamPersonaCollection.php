<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\models;

final class MSteamPersonaCollection extends ArrayObject {

    public function __construct(\justinback\steam\api\SteamPersona ...$array) {
        parent::__construct($array);
    }

    public function append($value) {
        if ($value instanceof \justinback\steam\api\SteamPersona) {
            parent::append($value);
        } else {
            throw new Exception("Cannot append non \justinback\steam\api\SteamPersona to a " . __CLASS__);
        }
    }

    public function offsetSet($index, $newval) {
        if ($newval instanceof \justinback\steam\api\SteamPersona) {
            parent::offsetSet($index, $newval);
        } else {
            throw new Exception("Cannot add a non \justinback\steam\api\SteamPersona value to a " . __CLASS__);
        }
    }

}
