<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Anti Cheat (VAC & Game ban) managing. 
 * Ban, unban and report players.
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
    public $reportid = null;

    /**
     * Construction of the variables steamid, key and game
     *
     * 
     * @param string $reportid ReportPlayerCheating Report ID
     * @param string $sApiKey Steamworks Developer API Key
     * @param string $iGame Your Appid
     * @param string $sSteamid The SteamID of the user 
     *
     * @return void
     */
    public function __construct($reportid = null, $sApiKey = null, $iGame = null, $sSteamid = null) {
        $this->set_key($sApiKey);
        $this->set_game((int) $iGame);
        $this->set_steamid($sSteamid);
        $this->set_reportid($reportid);
    }

    /**
     * Setting API Key from the construct
     *
     *
     * @param string $sApiKey Steamworks Developer API Key
     *
     * @return void
     */
    private function set_key($sApiKey) {
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
    private function set_game($iGame) {
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
    private function set_steamid($sSteamid) {
        $this->steamid = $sSteamid;
    }

    /**
     * Setting ReportID from the construct
     *
     *
     * @param string $reportid The Report ID
     *
     * @return void
     */
    private function set_reportid($reportid) {
        $this->reportid = $reportid;
    }

    /**
     * Requests a game ban on a specific player.
     *
     * This is designed to be used after the incidents from ReportPlayerCheating have been reviewed and cheating has been confirmed.
     *
     * @param string $sCheatDescription Text describing cheating infraction.
     * @param int $iDuration Ban duration requested in seconds. (duration 0 will issue infinite - less than a year is a suspension and not visible on profile)
     * @param bool $bDelayBan (Optional) Delay the ban according to default ban delay rules.
     * 
     * 
     * @return bool
     */
    public function RequestPlayerGameBan($sCheatDescription, $iDuration, $bDelayBan = false) {
        $aOptions = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int) $this->game, 'steamid' => $this->steamid, 'reportid' => $this->reportid, 'cheatdescription' => $sCheatDescription, 'duration' => $iDuration, 'delayban' => $bDelayBan))
            )
        );
        $cContext = stream_context_create($aOptions);
        $fgcRequestPlayerGameBan = file_get_contents("https://partner.steam-api.com/ICheatReportingService/RequestPlayerGameBan/v1/", false, $cContext);
        $oRequestPlayerGameBan = json_decode($fgcRequestPlayerGameBan);


        if (count($oRequestPlayerGameBan->response) == 0) {
            return false;
        }
        return true;
    }

}
