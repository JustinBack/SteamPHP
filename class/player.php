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
        $fgcGetPersonaName = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $oGetPersonaName = json_decode($fgcGetPersonaName);
        
        foreach($oGetPersonaName->response->players as $oPlayer){
            return $oPlayer->personaname;
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
        $oObject = new \stdClass();
        $fgcGetAvatar = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $oGetAvatar = json_decode($fgcGetAvatar);
        
        foreach($oGetAvatar->response->players as $oPlayer){
            $oObject->small = $oPlayer->avatar;
            $oObject->medium = $oPlayer->avatarmedium;
            $oObject->full = $oPlayer->avatarfull;
            return $oObject;
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
        $fgcGetRealName = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".$this->key."&steamids=".$this->steamid);
        $oGetRealName = json_decode($fgcGetRealName);
        
        foreach($oGetRealName->response->players as $oPlayer){
            return $oPlayer->realname;
        }
    }
    
    
    /**
    * ReportPlayerCheating is designed to gather community reports of cheating, where one player reports another player within the game.
    *
    * It is intended for unreliable data from peers in the game ( semi-trusted sources ). The back-end that reports the data should ensure that both parties are authenticated, but the data in itself is treated as hearsay. Optional parameters may be used to encode the type of cheating that is suspected or additional evidence ( an identifier pointing to the match/demo for further review )
    *
    * @param string $sSteamidreporter (Optional) The Steam ID of the user or game server who is reporting the cheating.
    * @param string $sAppData (Optional) App specific data about the type of cheating set by developer. (ex 1 = Aimbot, 2 = Wallhack, 3 = Griefing)
    * @param bool $bHeuristic (Optional) Extra information about the source of the cheating - was it a heuristic.
    * @param bool $bDetection (Optional) Extra information about the source of the cheating - was it a detection.
    * @param bool $bPlayerReport (Optional) Extra information about the source of the cheating - was it a player report.
    * @param bool $bNoReportId (Optional) Don't return reportid. This should only be passed if you don't intend to issue a ban based on this report.
    * @param int $iGamemode (Optional) Extra information about state of game - was it a specific type of game play or game mode. (0 = generic)
    * @param int $iSuspicionStartTime (Optional) Extra information indicating how far back the game thinks is interesting for this user. Unix epoch time (time since Jan 1st, 1970).
    * @param int $iSeverity (Optional) Level of severity of bad action being reported. Scale set by developer.
    * 
    * 
    * @return anticheat
    */
    public function ReportPlayerCheating($sSteamidreporter = 0, $sAppData = 0, $bHeuristic = false, $bDetection = false, $bPlayerReport = false, $bNoReportId = false, $iGamemode = 0, $iSuspicionStartTime = 0, $iSeverity = 0){
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid, 'steamidreporter' => $sSteamidreporter, 'appdata' => $sAppData, 'heuristic' => $bHeuristic, 'detection' => $bDetection, 'playerreport' => $bPlayerReport, 'noreportid' => $bNoReportId, 'gamemode' => $iGamemode, 'suspicionstarttime' => $iSuspicionStartTime, 'severity' => $iSeverity ))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcReportPlayerCheating = file_get_contents("https://partner.steam-api.com/ICheatReportingService/ReportPlayerCheating/v1/", false, $cContext);
        $oReportPlayerCheating = json_decode($fgcReportPlayerCheating);
       
        
        return new \justinback\steam\anticheat($oReportPlayerCheating->response->reportid, $this->key, $this->game, $this->steamid);
    }
    
    
    /**
    * Allows publishers to report users who are behaving badly on their community hub.
    *
    * 
    * @param string $sSteamidreporter SteamID of user doing the reporting
    * @param int $iAbuseType Abuse type code (see EAbuseReportType enum)
    * @param int $iContentType Content type code (see ECommunityContentType enum)
    * @param string $sDescription Narrative from user
    * @param string $sGid (optional) GID of related record (depends on content type)
    * 
    * @return object
    */
    public function ReportAbuse($sSteamidreporter, $iAbuseType, $iContentType, $sDescription, $sGid = null){
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamidTarget' => $this->steamid, 'steamidActor' => $sSteamidreporter, 'abuseType' => $iAbuseType, 'contentType' => $iContentType, 'description' => $sDescription, 'gid' => $sGid))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcReportAbuse = file_get_contents("https://partner.steam-api.com/ISteamCommunity/ReportAbuse/v1/", false, $cContext);
        $oReportAbuse = json_decode($fgcReportAbuse);
        $oObj = new \stdClass();
        if($oReportAbuse->result>success == 1){
            $oObj->success = true;
            $oObj->message = null;
            return $oObj;
        }
        $oObj->success = false;
        $oObj->message = $oReportAbuse->result->message;
        return $oObj;
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
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcRemovePlayerGameBan = file_get_contents("https://partner.steam-api.com/ICheatReportingService/RemovePlayerGameBan/v1/", false, $cContext);
        $oRemovePlayerGameBan = json_decode($fgcRemovePlayerGameBan);
        
        if(count($oRemovePlayerGameBan->response) == 0){
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
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, "steamid" => $this->steamid))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcEnumerateUserPublishedFiles = file_get_contents("https://partner.steam-api.com/ISteamRemoteStorage/EnumerateUserPublishedFiles/v1/", false, $cContext);
        $oEnumerateUserPublishedFiles = json_decode($fgcEnumerateUserPublishedFiles);
       
        $aList = array();
        
        foreach ($oEnumerateUserPublishedFiles->response->files as $oFile) {
            array_push($aList, new \justinback\steam\ugc($oFile->publishedfileid, $this->key, $this->game, $this->steamid));
        }
        
        return $aList;
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
