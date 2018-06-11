<?php

/*
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
    
    
    public function GetPersonaName(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            return $player->personaname;
        }
    }
    
    public function GetAvatarSmall(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            return $player->avatar;
        }
    }
    
    public function GetAvatarMedium(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            return $player->avatarmedium;
        }
    }
    
    public function GetRealName(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            return $player->realname;
        }
    }
    
    public function GetAvatarFull(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            return $player->avatarfull;
        }
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
    
    
    
    
}
