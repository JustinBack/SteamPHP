<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Anti Cheat (VAC & Game ban) managing. 
 * Ban, unban and report players.
 *
 * @author Justin Back <jback@pixelcatproductions.net>
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
     * Setting ReportID
     *
     *
     * @param string $reportid The Report ID
     *
     * @return void
     */
    public function set_reportid($reportid) {
        $this->reportid = $reportid;
    }

    /**
     * Requests a game ban on a specific player.
     *
     * This is designed to be used after the incidents from ReportPlayerCheating have been reviewed and cheating has been confirmed.
     *
     * 
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the steam id, report id, or duration is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * 
     * @param string $sCheatDescription Text describing cheating infraction.
     * @param int $iDuration Ban duration requested in seconds. (duration 0 will issue infinite - less than a year is a suspension and not visible on profile)
     * @param bool $bDelayBan (Optional) Delay the ban according to default ban delay rules.
     * 
     * @link https://partner.steamgames.com/doc/webapi/ICheatReportingService#RequestPlayerGameBan
     * @return bool TRUE if the player has successfully been banned otherwise FALSE
     */
    public function RequestPlayerGameBan($sCheatDescription, $iDuration, $bDelayBan = false) {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            "reportid" => $this->reportid,
            "cheatdescription" => $sCheatDescription,
            "delayban" => $bDelayBan,
            "duration" => $iDuration,
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ICheatReportingService/RequestPlayerGameBan/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);

        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Duration or Report ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }
        

        if (count((array)$CURLResponse->response) == 0) {
            return false;
        }
        return true;
    }

}
