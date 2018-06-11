<?php

/*
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
    private $key = null;
    private $game = null;
    private $steamid = null;
    
    public function __construct($apikey = null, $game = null, $steamid = null)
    {
        $this->set_key($apikey);
        $this->set_game((int)$game);
        $this->set_steamid($steamid);
        
    }
    
    private function set_key($apikey)
    {
        $this->key = $apikey;
    }
    
    public function set_game($game)
    {
        $this->game = $game;
    }
    
    public function set_steamid($steamid)
    {
        $this->steamid = $steamid;
    }
    
    
    
    
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
    
    
    public function GetNumberOfCurrentPlayers(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1?key=".$this->key."&appid=".(int)$this->game);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        return $GetNumberOfCurrentPlayers->response->player_count;
    }
    
    
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
   
    
}
