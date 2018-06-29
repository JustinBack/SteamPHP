<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Manage Steam Achievements.
 * 
 * @todo UnlockAchievement();
 * @todo LockAchievement();
 *
 * @author Justin Back <jb@justinback.com>
 */
class achievements {
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
    * @example
    * <code>
    * $achievements = $steam->game()->achievements();
    * </code>
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
    
    
    /**
    * Retrieve unlocked achievements by the player
    *
    *
    * 
    * 
    * @example
    * <code>
    * $achievements = $steam->game()->achievements();
    * $array = $achievements->GetPlayerAchievements();
    * </code> 
    *
    * @return array
    */
    public function GetPlayerAchievements()
    {
        $fgcGetPlayerAchievements = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v1?key=".$this->key."&steamid=".$this->steamid."&appid=".(int)$this->game);
        $oGetPlayerAchievements = json_decode($fgcGetPlayerAchievements);
        
        
        if($this->game()->CheckAppOwnership()){
            $aAchievements = array_filter($oGetPlayerAchievements->playerstats->achievements, function($oItem) {
                    return $oItem->achieved == 1;
            });
            return $aAchievements;
        }
        return false;
    }
    
    
    /**
    * Return only locked achievements by the player
    *
    * @example
    * <code>
    * $achievements = $steam->game()->achievements();
    * $array = $achievements->GetPlayerAchievementsLocked();
    * </code> 
    *
    * @return array
    */
    public function GetPlayerAchievementsLocked()
    {
        $fgcGetPlayerAchievementsLocked = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v1?key=".$this->key."&steamid=".$this->steamid."&appid=".(int)$this->game);
        $oGetPlayerAchievementsLocked = json_decode($fgcGetPlayerAchievementsLocked);
        
        
        if($this->game()->CheckAppOwnership()){
            $aAchievements = array_filter($oGetPlayerAchievementsLocked->playerstats->achievements, function($oItem) {
                    return $oItem->achieved == 0;
            });
            return $aAchievements;
        }
        return false;
    }
    
    
    /**
    * Setting SteamID from the construct
    *
    *
    * @param string $sApiname APIName of the achievement (not visible name)
    *
    * 
    * @example
    * <code>
    * $achievements = $steam->game()->achievements();
    * $object = $achievements->GetAchievementDetails();
    * </code> 
    * 
    * @return object
    */
    public function GetAchievementDetails($sApiname){
        $fgcGetAchievementDetails = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2?key=".$this->key."&appid=".(int)$this->game);
        $oAvailableGameStats = json_decode($fgcGetAchievementDetails);
        foreach($aAvailableGameStats->game->availableGameStats->achievements as $oAchievement){
            if($oAchievement->name == $sApiname){
                return $oAchievement;
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
    
    /**
    * Check if player has unlocked the specified achievement
    *
    *
    * @param string $apiname APIName of the achievement (not visible name)
    *
    * @example
    * <code>
    * $achievements = $steam->game()->achievements();
    * $bool = $achievements->HasPlayerUnlockedAchievement();
    * </code> 
    * 
    * 
    * @return bool
    */
    public function HasPlayerUnlockedAchievement($sApiname){
            foreach($this->GetPlayerAchievements() as $oUserAchievement){
                if($oUserAchievement->apiname == $sApiname){
                    return true;
                }
                return false;
            }
    }
    
    
    
    /**
    * game object.
    *
    * @param string $sApiKey (optional) set a different apikey than the construct
    * @param string $iGame (optional) set a different appid than the construct
    * @param string $sSteamid (optional) set a different steamid than the construct
    * 
    * @return game
    */
    public function game($sApiKey = null, $iGame = null, $sSteamid = null)
    {
        if($sApiKey === null){
            $sApiKey = $this->key;
        }
        if($iGame === null){
            $iGame = $this->game;
        }
        if($sSteamid === null){
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\game($sApiKey,$iGame,$sSteamid);
    }
}
