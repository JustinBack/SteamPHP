<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\models;

final class MGroupStats extends \stdClass {

    public int $Total;
    public int $InChat;
    public int $InGame;
    public int $Online;

    public function __construct(int $iTotal, int $iInChat, int $iInGame, int $iOnline) {
        $this->Total = $iTotal;
        $this->InChat = $iInChat;
        $this->InGame = $iInGame;
        $this->Online = $iOnline;
    }

}
