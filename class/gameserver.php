<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Game Server managing
 *
 * @since pb1.0.0a
 * @todo Everything, API currently has error. Waiting for fix
 * @author Justin Back <jb@justinback.com>
 */
class gameserver {
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
    * @param string $apikey Steamworks Developer API Key
    * @param string $game Your Appid
    * @param string $steamid The SteamID of the user 
    *
    * @return void
    */
    public function __construct($apikey = null, $game = null, $steamid = null)
    {
        $this->set_key($apikey);
        $this->set_game((int)$game);
        $this->set_steamid($steamid);
    }
    
    /**
    * Setting API Key from the construct
    *
    *
    * @param string $apikey Steamworks Developer API Key
    *
    * @return void
    */
    private function set_key($apikey)
    {
        $this->key = $apikey;
    }
    
    
    /**
    * Setting AppID from the construct
    *
    *
    * @param string $game Your AppID
    *
    * @return void
    */
    private function set_game($game)
    {
        $this->game = $game;
    }
    
    
    /**
    * Setting SteamID from the construct
    *
    *
    * @param string $steamid The Players SteamID
    *
    * @return void
    */
    private function set_steamid($steamid)
    {
        $this->steamid = $steamid;
    }
    
    
    /**
    * Gets a list of game server accounts with their logon tokens
    *
    *
    *
    * @return array
    */
    public function GetAccountList(){
        $req_players = file_get_contents("https://api.steampowered.com/IGameServersService/GetAccountList/v1?key=".$this->key. "&appid=". $this->game);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        return $GetNumberOfCurrentPlayers->response;
    }
    
    
}
