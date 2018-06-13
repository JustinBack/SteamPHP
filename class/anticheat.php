<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Anti Cheat (VAC & Game ban) managing
 *
 * @author Justin Back <jb@justinback.com>
 */
class anticheat {
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
    * ID of report
    *
    */
    private $reportid = null;
    
    /**
    * Construction of the variables steamid, key and game
    *
    * 
    * @param string $reportid ReportPlayerCheating Report ID
    * @param string $apikey Steamworks Developer API Key
    * @param string $game Your Appid
    * @param string $steamid The SteamID of the user 
    *
    * @return void
    */
    public function __construct($reportid = null, $apikey = null, $game = null, $steamid = null)
    {
        $this->set_key($apikey);
        $this->set_game((int)$game);
        $this->set_steamid($steamid);
        $this->set_reportid($reportid);
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
    * Setting ReportID from the construct
    *
    *
    * @param string $reportid The Report ID
    *
    * @return void
    */
    private function set_reportid($reportid)
    {
        $this->reportid = $reportid;
    }
    
    
    /**
    * Requests a game ban on a specific player.
    *
    * This is designed to be used after the incidents from ReportPlayerCheating have been reviewed and cheating has been confirmed.
    *
    * @param string $cheatdescription Text describing cheating infraction.
    * @param int $duration Ban duration requested in seconds. (duration 0 will issue infinite - less than a year is a suspension and not visible on profile)
    * @param bool $delayban Delay the ban according to default ban delay rules.
    * 
    * 
    * @return bool
    */
    public function RequestPlayerGameBan($cheatdescription, $duration, $delayban = false){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid, 'reportid' => $this->reportid, 'cheatdescription' => $cheatdescription, 'duration' => $duration, 'delayban' => $delayban))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ICheatReportingService/RequestPlayerGameBan/v1/", false, $context);
        $response = json_decode($req_players);
        
        
        if(count($response->response) == 0){
            return false;
        }
        return true;
    }
    
    
    
}
