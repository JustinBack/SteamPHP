<?php

/*
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam leaderboard managing
 *
 * @author Justin Back <jb@justinback.com>
 */
class leaderboards {
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
    
    
    public function DeleteLeaderboard($name){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => $this->game, 'name' => $name))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ISteamLeaderboards/DeleteLeaderboard/v1/", false, $context);
        $response = json_decode($req_players);
        
        if($response->result->result != 1){
         return false;   
        }
        return true;
    }
    
    public function GetLeaderboardsForGame(){
        $req_lb = file_get_contents("https://api.steampowered.com/ISteamLeaderboards/GetLeaderboardsForGame/v2?key=".$this->key."&appid=".(int)$this->game);
        $resp = json_decode($req_lb);
        return $resp->response->leaderboards;
    }
    
    
    public function game($apikey = null, $game = null, $steamid = null)
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
        return new \justinback\steam\game($apikey,$game,$steamid);
    }
    
}
