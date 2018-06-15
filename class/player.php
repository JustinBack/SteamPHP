<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam player managing.
 * Get Player name, avatar and report cheating!
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
    * ReportPlayerCheating is designed to gather community reports of cheating, where one player reports another player within the game.
    *
    * It is intended for unreliable data from peers in the game ( semi-trusted sources ). The back-end that reports the data should ensure that both parties are authenticated, but the data in itself is treated as hearsay. Optional parameters may be used to encode the type of cheating that is suspected or additional evidence ( an identifier pointing to the match/demo for further review )
    *
    * @param string $steamidreporter (Optional) The Steam ID of the user or game server who is reporting the cheating.
    * @param string $appdata (Optional) App specific data about the type of cheating set by developer. (ex 1 = Aimbot, 2 = Wallhack, 3 = Griefing)
    * @param bool $heuristic (Optional) Extra information about the source of the cheating - was it a heuristic.
    * @param bool $detection (Optional) Extra information about the source of the cheating - was it a detection.
    * @param bool $playerreport (Optional) Extra information about the source of the cheating - was it a player report.
    * @param bool $noreportid (Optional) Don't return reportid. This should only be passed if you don't intend to issue a ban based on this report.
    * @param int $gamemode (Optional) Extra information about state of game - was it a specific type of game play or game mode. (0 = generic)
    * @param int $suspicionstarttime (Optional) Extra information indicating how far back the game thinks is interesting for this user. Unix epoch time (time since Jan 1st, 1970).
    * @param int $severity (Optional) Level of severity of bad action being reported. Scale set by developer.
    * 
    * 
    * @return anticheat
    */
    public function ReportPlayerCheating($steamidreporter = 0, $appdata = 0, $heuristic = false, $detection = false, $playerreport = false, $noreportid = false, $gamemode = 0, $suspicionstarttime = 0, $severity = 0){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid, 'steamidreporter' => $steamidreporter, 'appdata' => $appdata, 'heuristic' => $heuristic, 'detection' => $detection, 'playerreport' => $playerreport, 'noreportid' => $noreportid, 'gamemode' => $gamemode, 'suspicionstarttime' => $suspicionstarttime, 'severity' => $severity ))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ICheatReportingService/ReportPlayerCheating/v1/", false, $context);
        $response = json_decode($req_players);
       
        
        return new \justinback\steam\anticheat($response->response->reportid, $this->key, $this->game, $this->steamid);
    }
    
    
    
    /**
    * Remove a game ban on a player.
    *
    * This is used if a Game ban is determined to be a false positive.
    *
    * 
    * 
    * @return bool
    */
    public function RemovePlayerGameBan(){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ICheatReportingService/RemovePlayerGameBan/v1/", false, $context);
        $response = json_decode($req_players);
        
        if(count($response->response) == 0){
            return false;
        }
        return true;
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
    * inventory object.
    *
    * @param string $apikey (optional) set a different apikey than the construct
    * @param string $game (optional) set a different appid than the construct
    * @param string $steamid (optional) set a different steamid than the construct
    * 
    * @return inventory
    */
    public function inventory($apikey = null, $game = null, $steamid = null)
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
        return new \justinback\steam\inventory($apikey,$game,$steamid);
    }
    
    
    
    
}
