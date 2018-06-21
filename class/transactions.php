<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Microtransaction managing
 *
 * @since pb1.0.0a
 * @todo Basically Everything
 * @author Justin Back <jb@justinback.com>
 */
class transactions {
    /**
    * Steamworks API Key
    *
    */
    private $key = null;
    
    /**
    * Steamworks App ID
    *
    */
    private $game = null;
    
    /**
    * SteamID of user
    *
    */
    private $steamid = null;
    
    /**
    * Construction of the variables steamid, key and game
    *
    * 
    * @param string $sApiKey Steamworks Developer API Key
    * @param string $iGame Your Appid
    * @param string $sSteamid The SteamID of the user 
    *
    * @return void
    */
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null)
    {
        $this->set_key($sApiKey);
        $this->set_game((int)$iGame);
        $this->set_steamid($sSteamid);
    }
    
    /**
    * Setting API Key from the construct
    *
    *
    * @param string $sApiKey Steamworks Developer API Key
    *
    * @return void
    */
    private function set_key($sApiKey)
    {
        $this->key = $sApiKey;
    }
    
    
    /**
    * Setting AppID from the construct
    *
    *
    * @param string $iGame Your AppID
    *
    * @return void
    */
    private function set_game($iGame)
    {
        $this->game = $iGame;
    }
    
    
    /**
    * Setting SteamID from the construct
    *
    *
    * @param string $sSteamid The Players SteamID
    *
    * @return void
    */
    private function set_steamid($sSteamid)
    {
        $this->steamid = $sSteamid;
    }
    
    
    
}
