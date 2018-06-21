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
    * @param string $name name of the leaderboard to delete
    *
    * @return bool
    */
    public function DeleteLeaderboard($name){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'name' => $name))
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
    
    
    /**
    * Create a leaderboard for your App
    *
    *
    * @param string $name name of the leaderboard to create
    * @param string $sortmethod sort method to use for this leaderboard (defaults to Ascending)
    * @param string $displaytype display type for this leaderboard (defaults to Numeric)
    * @param bool $createifnotfound if this is true the leaderboard will be created if it doesn't exist. Defaults to true.
    * @param bool $onlytrustedwrites if this is true the leaderboard scores cannot be set by clients, and can only be set by publisher via SetLeaderboardScore WebAPI. Defaults to false.
    * @param bool $onlyfriendsreads if this is true the leaderboard scores can only be read for friends by clients, scores can always be read by publisher. Defaults to false.
    *
    * @return object
    */
    public function FindOrCreateLeaderboard($name, $sortmethod = "Ascending", $displaytype = "Numeric", $createifnotfound = true, $onlytrustedwrites = false, $onlyfriendsreads = false){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'name' => $name, 'sortmethod' => $sortmethod, 'displaytype' => $displaytype, 'createifnotfound' => $createifnotfound, 'onlytrustedwrites' => $onlytrustedwrites, 'onlyfriendsreads' => $onlyfriendsreads))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ISteamLeaderboards/FindOrCreateLeaderboard/v2/", false, $context);
        $response = json_decode($req_players);
        
        return $response->result;
    }
    
    
    /**
    * Reset a leaderboard for your App
    *
    *
    * @param string $leaderboardid numeric ID of the target leaderboard. Can be retrieved from GetLeaderboardsForGame
    * 
    * @return bool
    */
    public function ResetLeaderboard($leaderboardid){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'leaderboardid' => $leaderboardid))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ISteamLeaderboards/ResetLeaderboard/v1/", false, $context);
        $response = json_decode($req_players);
        
         if($response->result->result != 1){
         return false;   
        }
        return true;
    }
    
    /**
    * Set a score for your leaderboard for your App
    *
    *
    * @param string $leaderboardid numeric ID of the target leaderboard. Can be retrieved from GetLeaderboardsForGame
    * @param string $score the score to set for this user
    * @param string $scoremethod update method to use. Can be "KeepBest" or "ForceUpdate"
    * @param rawbytes $details (optional) game-specific details for how the score was earned. Up to 256 bytes.
    * @param string $sSteamid (optional) steamID to set the score for 
    * 
    * @return object
    */
    public function SetLeaderboardScore($leaderboardid, $score, $scoremethod, $details = null, $sSteamid = null){
        if($sSteamid == null){
            $sSteamid = $this->steamid;
        }
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'leaderboardid' => $leaderboardid, 'steamid' => $sSteamid, 'score' => $score, 'scoremethod' => $scoremethod, 'details' => $details))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ISteamLeaderboards/SetLeaderboardScore/v1/", false, $context);
        $response = json_decode($req_players);
       
        
        return $response->result;
    }
    
    /**
    * Get all leaderboards from your App
    *
    *
    * 
    * @return object
    */
    public function GetLeaderboardsForGame(){
        $req_lb = file_get_contents("https://api.steampowered.com/ISteamLeaderboards/GetLeaderboardsForGame/v2?key=".$this->key."&appid=".(int)$this->game. "&". time());
        $resp = json_decode($req_lb);
        return $resp->response->leaderboards;
    }
    
    
    
    /**
    * Get all leaderboard entries from your App
    *
    *
    * @param int $leaderboardid ID of the leaderboard to view
    * @param string $datarequest type of request: RequestGlobal, RequestAroundUser, RequestFriends
    * @param int $rangestart range start or 0
    * @param int $rangend range end or max LB entries
    * @param bool $sSteamid Use SteamID to lookup or not
    * 
    * 
    * @return array
    */
    public function GetLeaderboardEntries($leaderboardid, $datarequest, $rangestart, $rangend, $sSteamid = true){
        $req_lb = file_get_contents("https://api.steampowered.com/ISteamLeaderboards/GetLeaderboardEntries/v1?key=".$this->key."&appid=".(int)$this->game. "&". time(). "&leaderboardid=".$leaderboardid. "&datarequest=". $datarequest. "&rangestart=". $rangestart. "&rangeend=". $rangend);
        if($sSteamid){
        $req_lb = file_get_contents("https://api.steampowered.com/ISteamLeaderboards/GetLeaderboardEntries/v1?key=".$this->key."&appid=".(int)$this->game. "&". time(). "&leaderboardid=".$leaderboardid. "&datarequest=". $datarequest. "&rangestart=". $rangestart. "&rangeend=". $rangend. "&steamid=". $this->steamid);
        }
        $resp = json_decode($req_lb);
        return $resp->leaderboardEntryInformation->leaderboardEntries;
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
