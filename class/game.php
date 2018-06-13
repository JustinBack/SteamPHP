<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam game managing
 *
 * @author Justin Back <jb@justinback.com>
 */
class game {
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
    * Check wether the user owns your game or not
    *
    *
    *
    * @return bool
    */
    public function CheckAppOwnership()
    {
        $req_owner = file_get_contents("https://api.steampowered.com/ISteamUser/GetPublisherAppOwnership/v2?key=".$this->key."&steamid=".$this->steamid);
        $appownership = json_decode($req_owner);
        
        foreach ($appownership->appownership->apps as $app)
            {
                if($app->appid == $this->game){
                    return (bool)$app->ownsapp;
                }
            }
    }
    
    /**
    * Get the current number of players of your app
    *
    *
    *
    * @return int
    */
    public function GetNumberOfCurrentPlayers(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1?key=".$this->key."&appid=".(int)$this->game);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        return $GetNumberOfCurrentPlayers->response->player_count;
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
    
    
    /**
    * leaderboards object.
    *
    * @param string $apikey (optional) set a different apikey than the construct
    * @param string $game (optional) set a different appid than the construct
    * @param string $steamid (optional) set a different steamid than the construct
    * 
    * @return leaderboards
    */
    public function leaderboards($apikey = null, $game = null, $steamid = null)
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
        return new \justinback\steam\leaderboards($apikey,$game,$steamid);
    }
    
    
    /**
    * ugc object.
    *
    * @param string $publishedfileid (optional) set a different publishedfileid than the construct
    * @param string $apikey (optional) set a different apikey than the construct
    * @param string $game (optional) set a different appid than the construct
    * @param string $steamid (optional) set a different steamid than the construct
    * 
    * @return ugc
    */
    public function ugc($publishedfileid, $apikey = null, $game = null, $steamid = null)
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
        return new \justinback\steam\ugc($publishedfileid, $apikey,$game,$steamid);
    }
   
    
}