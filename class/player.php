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
    * @deprecated Use GetAvatar() instead
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
    * @deprecated Use GetAvatar() instead
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
    * Get the Avatar in an object (small, medium, full)
    *
    *
    *
    * @return object
    */
    public function GetAvatar(){
        $object = new \stdClass();
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            $object->small = $player->avatar;
            $object->medium = $player->avatarmedium;
            $object->full = $player->avatarfull;
            return $object;
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
    * @deprecated Use GetAvatar() instead
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
    * @param string $sSteamidreporter (Optional) The Steam ID of the user or game server who is reporting the cheating.
    * @param string $appdata (Optional) App specific data about the type of cheating set by developer. (ex 1 = Aimbot, 2 = Wallhack, 3 = Griefing)
    * @param bool $heuristic (Optional) Extra information about the source of the cheating - was it a heuristic.
    * @param bool $detection (Optional) Extra information about the source of the cheating - was it a detection.
    * @param bool $playerreport (Optional) Extra information about the source of the cheating - was it a player report.
    * @param bool $noreportid (Optional) Don't return reportid. This should only be passed if you don't intend to issue a ban based on this report.
    * @param int $iGamemode (Optional) Extra information about state of game - was it a specific type of game play or game mode. (0 = generic)
    * @param int $suspicionstarttime (Optional) Extra information indicating how far back the game thinks is interesting for this user. Unix epoch time (time since Jan 1st, 1970).
    * @param int $severity (Optional) Level of severity of bad action being reported. Scale set by developer.
    * 
    * 
    * @return anticheat
    */
    public function ReportPlayerCheating($sSteamidreporter = 0, $appdata = 0, $heuristic = false, $detection = false, $playerreport = false, $noreportid = false, $iGamemode = 0, $suspicionstarttime = 0, $severity = 0){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid, 'steamidreporter' => $sSteamidreporter, 'appdata' => $appdata, 'heuristic' => $heuristic, 'detection' => $detection, 'playerreport' => $playerreport, 'noreportid' => $noreportid, 'gamemode' => $iGamemode, 'suspicionstarttime' => $suspicionstarttime, 'severity' => $severity ))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ICheatReportingService/ReportPlayerCheating/v1/", false, $context);
        $response = json_decode($req_players);
       
        
        return new \justinback\steam\anticheat($response->response->reportid, $this->key, $this->game, $this->steamid);
    }
    
    
    /**
    * Allows publishers to report users who are behaving badly on their community hub.
    *
    * 
    * @param string $sSteamidreporter SteamID of user doing the reporting
    * @param int $abuseType Abuse type code (see EAbuseReportType enum)
    * @param int $contentType Content type code (see ECommunityContentType enum)
    * @param string $description Narrative from user
    * @param string gid (optional) GID of related record (depends on content type)
    * 
    * @return object
    */
    public function ReportAbuse($sSteamidreporter, $abuseType, $contentType, $description, $gid = null){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamidTarget' => $this->steamid, 'steamidActor' => $sSteamidreporter, 'abuseType' => $abuseType, 'contentType' => $contentType, 'description' => $description, 'gid' => $gid))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ISteamCommunity/ReportAbuse/v1/", false, $context);
        $response = json_decode($req_players);
        $obj = new \stdClass();
        if($response->result>success == 1){
            $obj->success = true;
            $obj->message = null;
            return $obj;
        }
        $obj->success = false;
        $obj->message = $response->result->message;
        return $obj;
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
    * List all files by user as array (Only IDs)
    *
    * 
    * 
    * @return ugc array
    */
    public function EnumerateUserPublishedFiles(){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, "steamid" => $this->steamid))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ISteamRemoteStorage/EnumerateUserPublishedFiles/v1/", false, $context);
        $response = json_decode($req_players);
       
        $list = array();
        
        foreach ($response->response->files as $file) {
            array_push($list, new \justinback\steam\ugc($file->publishedfileid, $this->key, $this->game, $this->steamid));
        }
        
        return $list;
    }
    
    
    /**
    * achievements object.
    *
    * @param string $sApiKey (optional) set a different apikey than the construct
    * @param string $iGame (optional) set a different appid than the construct
    * @param string $sSteamid (optional) set a different steamid than the construct
    * 
    * @return achievements
    */
    public function achievements($sApiKey = null, $iGame = null, $sSteamid = null)
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
        return new \justinback\steam\achievements($sApiKey,$iGame,$sSteamid);
    }
    
    
    /**
    * inventory object.
    *
    * @param string $sApiKey (optional) set a different apikey than the construct
    * @param string $iGame (optional) set a different appid than the construct
    * @param string $sSteamid (optional) set a different steamid than the construct
    * 
    * @return inventory
    */
    public function inventory($sApiKey = null, $iGame = null, $sSteamid = null)
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
        return new \justinback\steam\inventory($sApiKey,$iGame,$sSteamid);
    }
    
    
    /**
    * Transaction object.
    *
    * @param string $bTesting Sandbox or Production environment?
    * @param string $sApiKey (optional) set a different apikey than the construct
    * @param string $iGame (optional) set a different appid than the construct
    * @param string $sSteamid (optional) set a different steamid than the construct
    * 
    * @return transactions
    */
    public function transactions($bTesting = false, $sApiKey = null, $iGame = null, $sSteamid = null)
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
        return new \justinback\steam\transactions($bTesting, $sApiKey,$iGame,$sSteamid);
    }
    
}
