<?php

/*
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam achievement managing
 *
 * @author Justin Back <jb@justinback.com>
 */
class achievements {
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
    
    /*public function achievements()
    {
        $req_stats = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2?key=".$this->key."&appid=".(int)$this->game);
        $availableGameStats = json_decode($req_stats);
        return $availableGameStats->game->availableGameStats->achievements;
    }*/
    
    public function GetPlayerAchievements()
    {
        $req_achievement = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v1?key=".$this->key."&steamid=".$this->steamid."&appid=".(int)$this->game);
        $playerstats = json_decode($req_achievement);
        
        
        if($this->game()->CheckAppOwnership()){
            $achievements = array_filter($playerstats->playerstats->achievements, function($item) {
                    return $item->achieved == 1;
            });
            return $achievements;
        }
        return false;
    }
    
    public function GetPlayerAchievementsLocked()
    {
        $req_achievement = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v1?key=".$this->key."&steamid=".$this->steamid."&appid=".(int)$this->game);
        $playerstats = json_decode($req_achievement);
        
        
        if($this->game()->ownership()){
            $achievements = array_filter($playerstats->playerstats->achievements, function($item) {
                    return $item->achieved == 0;
            });
            return $achievements;
        }
        return false;
    }
    
    public function GetAchievementDetails($apiname){
        $req_achievement = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2?key=".$this->key."&appid=".(int)$this->game);
        $availableGameStats = json_decode($req_achievement);
        foreach($availableGameStats->game->availableGameStats->achievements as $achievement){
            if($achievement->name == $apiname){
                return $achievement;
            }
        }
    }
    
    /*public function UnlockAchievement($apiname){
        $req_achievement = file_get_contents("https://api.steampowered.com/ISteamUserStats/SetUserStatsForGame/v1?key=".$this->key."&appid=".(int)$this->game);
        $availableGameStats = json_decode($req_achievement);
        foreach($availableGameStats->game->availableGameStats->achievements as $achievement){
            if($achievement->name == $apiname){
                return $achievement;
            }
        }
    }*/
    
    public function HasPlayerUnlockedAchievement($apiname){
            foreach($this->GetPlayerAchievements() as $userachievement){
                if($userachievement->apiname == $apiname){
                    return true;
                }
                return false;
            }
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
