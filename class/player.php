<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam player managing
 *
 * @author Justin Back <jb@justinback.com>
 */
class player {
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
    public function set_game($game)
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
    public function set_steamid($steamid)
    {
        $this->steamid = $steamid;
    }
    
    /**
    * Get the Community name of the user
    *
    *
    *
    * @return string
    */
    public function GetPersonaName(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            return $player->personaname;
        }
    }
    
    /**
    * Get the Avatar in small
    *
    *
    *
    * @return string
    */
    public function GetAvatarSmall(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            return $player->avatar;
        }
    }
    
    /**
    * Get the Avatar in medium
    *
    *
    *
    * @return string
    */
    public function GetAvatarMedium(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            return $player->avatarmedium;
        }
    }
    
    /**
    * Get the Realname from the user
    *
    *
    *
    * @return string
    */
    public function GetRealName(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            return $player->realname;
        }
    }
    
    
    /**
    * Get the Avatar in full
    *
    *
    *
    * @return string
    */
    public function GetAvatarFull(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            return $player->avatarfull;
        }
    }
    
    /**
    * achievements object.
    *
    * @param string $apikey (optional) set a different apikey than the construct
    * @param string $game (optional) set a different appid than the construct
    * @param string $steamid (optional) set a different steamid than the construct
    * 
    * @return achievements
    */
    public function achievements($apikey = null, $game = null, $steamid = null)
    {
        if($apikey === null){
            $apikey = $this->key;
        }
        if($game === null){
            $game = $this->game;
        }
        if($steamid === null){
            $steamid = $this->steamid;
        }
        return new \justinback\steam\achievements($apikey,$game,$steamid);
    }
    
    
    
    
}
