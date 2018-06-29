<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam leaderboard managing. 
 * Delete, Add, Reset and Set Scores!
 *
 * @author Justin Back <jb@justinback.com>
 */
class leaderboards 
{
    
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
    
    /**
    * Delete a leaderboard from your App
    *
    *
    * @param string $sName name of the leaderboard to delete
    *
    * @return bool
    */
    public function DeleteLeaderboard($sName){
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'name' => $sName))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcDeleteLeaderboard = file_get_contents("https://partner.steam-api.com/ISteamLeaderboards/DeleteLeaderboard/v1/", false, $cContext);
        $oDeleteLeaderboard = json_decode($fgcDeleteLeaderboard);
        
        if($oDeleteLeaderboard->result->result != 1){
         return false;   
        }
        return true;
    }
    
    
    /**
    * Create a leaderboard for your App
    *
    *
    * @param string $sName name of the leaderboard to create
    * @param string $sSortMethod sort method to use for this leaderboard (defaults to Ascending)
    * @param string $sDisplayType display type for this leaderboard (defaults to Numeric)
    * @param bool $bCreateIfNotFound if this is true the leaderboard will be created if it doesn't exist. Defaults to true.
    * @param bool $bOnlyTrustedWrites if this is true the leaderboard scores cannot be set by clients, and can only be set by publisher via SetLeaderboardScore WebAPI. Defaults to false.
    * @param bool $bOnlyFriendsReads if this is true the leaderboard scores can only be read for friends by clients, scores can always be read by publisher. Defaults to false.
    *
    * @return object
    */
    public function FindOrCreateLeaderboard($sName, $sSortMethod = "Ascending", $sDisplayType = "Numeric", $bCreateIfNotFound = true, $bOnlyTrustedWrites = false, $bOnlyFriendsReads = false){
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'name' => $sName, 'sortmethod' => $sSortMethod, 'displaytype' => $sDisplayType, 'createifnotfound' => $bCreateIfNotFound, 'onlytrustedwrites' => $bOnlyTrustedWrites, 'onlyfriendsreads' => $bOnlyFriendsReads))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcFindOrCreateLeaderboard = file_get_contents("https://partner.steam-api.com/ISteamLeaderboards/FindOrCreateLeaderboard/v2/", false, $cContext);
        $oFindOrCreateLeaderboard = json_decode($fgcFindOrCreateLeaderboard);
        
        return $oFindOrCreateLeaderboard->result;
    }
    
    
    /**
    * Reset a leaderboard for your App
    *
    *
    * @param string $sLeaderboardId numeric ID of the target leaderboard. Can be retrieved from GetLeaderboardsForGame
    * 
    * @return bool
    */
    public function ResetLeaderboard($sLeaderboardId){
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'leaderboardid' => $sLeaderboardId))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcResetLeaderboard = file_get_contents("https://partner.steam-api.com/ISteamLeaderboards/ResetLeaderboard/v1/", false, $cContext);
        $oResetLeaderboard = json_decode($fgcResetLeaderboard);
        
         if($oResetLeaderboard->result->result != 1){
         return false;   
        }
        return true;
    }
    
    /**
    * Set a score for your leaderboard for your App
    *
    *
    * @param string $sLeaderboardId numeric ID of the target leaderboard. Can be retrieved from GetLeaderboardsForGame
    * @param string $sScore the score to set for this user
    * @param string $sScoreMethod update method to use. Can be "KeepBest" or "ForceUpdate"
    * @param rawbytes $rDetails (optional) game-specific details for how the score was earned. Up to 256 bytes.
    * @param string $sSteamid (optional) steamID to set the score for 
    * 
    * @return object
    */
    public function SetLeaderboardScore($sLeaderboardId, $sScore, $sScoreMethod, $rDetails = null, $sSteamid = null){
        if($sSteamid == null){
            $sSteamid = $this->steamid;
        }
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'leaderboardid' => $sLeaderboardId, 'steamid' => $sSteamid, 'score' => $sScore, 'scoremethod' => $sScoreMethod, 'details' => $rDetails))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcSetLeaderboardScore = file_get_contents("https://partner.steam-api.com/ISteamLeaderboards/SetLeaderboardScore/v1/", false, $cContext);
        $oSetLeaderboardScore = json_decode($fgcSetLeaderboardScore);
       
        
        return $oSetLeaderboardScore->result;
    }
    
    /**
    * Get all leaderboards from your App
    *
    *
    * 
    * @return object
    */
    public function GetLeaderboardsForGame(){
        $fgcGetLeaderboardsForGame = file_get_contents("https://api.steampowered.com/ISteamLeaderboards/GetLeaderboardsForGame/v2?key=".$this->key."&appid=".(int)$this->game. "&". time());
        $oGetLeaderboardsForGame = json_decode($fgcGetLeaderboardsForGame);
        return $oGetLeaderboardsForGame->response->leaderboards;
    }
    
    
    
    /**
    * Get all leaderboard entries from your App
    *
    *
    * @param int $sLeaderboardId ID of the leaderboard to view
    * @param string $sDataRequest type of request: RequestGlobal, RequestAroundUser, RequestFriends
    * @param int $iRangeStart range start or 0
    * @param int $iRangeEnd range end or max LB entries
    * @param bool $sSteamid Use SteamID to lookup or not
    * 
    * 
    * @return array
    */
    public function GetLeaderboardEntries($sLeaderboardId, $sDataRequest, $iRangeStart, $iRangeEnd, $sSteamid = true){
        $fgcGetLeaderboardEntries = file_get_contents("https://api.steampowered.com/ISteamLeaderboards/GetLeaderboardEntries/v1?key=".$this->key."&appid=".(int)$this->game. "&". time(). "&leaderboardid=".$sLeaderboardId. "&datarequest=". $sDataRequest. "&rangestart=". $iRangeStart. "&rangeend=". $iRangeEnd);
        if($sSteamid){
        $fgcGetLeaderboardEntries = file_get_contents("https://api.steampowered.com/ISteamLeaderboards/GetLeaderboardEntries/v1?key=".$this->key."&appid=".(int)$this->game. "&". time(). "&leaderboardid=".$sLeaderboardId. "&datarequest=". $sDataRequest. "&rangestart=". $iRangeStart. "&rangeend=". $iRangeEnd. "&steamid=". $this->steamid);
        }
        $oGetLeaderboardEntries = json_decode($fgcGetLeaderboardEntries);
        return $oGetLeaderboardEntries->leaderboardEntryInformation->leaderboardEntries;
    }
    
    
    /**
    * Game object.
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
