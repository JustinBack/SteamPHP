<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam game managing.
 * HUB for interacting with other classes and supplying variables automatically.
 *
 * @author Justin Back <jback@pixelcatproductions.net>
 */
class game {

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
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null) {
        $this->set_key($sApiKey);
        $this->set_game((int) $iGame);
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
     * Check wether the user owns your game or not
     *
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the steam id or app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @return bool TRUE if the user owns your game otherwise FALSE
     */
    public function CheckAppOwnership() {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            //"appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUser/GetPublisherAppOwnership/v2/?" . $CURLParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        foreach ($CURLResponse->appownership->apps as $oApp) {
            if ($oApp->appid == $this->game) {
                return (bool) $oApp->ownsapp;
            }
        }
        throw new exceptions\SteamException("The App or Steam ID entered is invalid!");
    }

    /**
     * Get the current number of players of your app
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @return int The current number of players using your app
     */
    public function GetNumberOfCurrentPlayers() {
        
        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1/?" . $CURLParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The App ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }
        
        
        return $CURLResponse->response->player_count;
    }

    /**
     * Interact with the game's achievements.
     *
     * @param string $sApiKey (optional) set a different apikey than the construct
     * @param string $iGame (optional) set a different appid than the construct
     * @param string $sSteamid (optional) set a different steamid than the construct
     * 
     * @return achievements
     */
    public function achievements($sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\achievements($sApiKey, $iGame, $sSteamid);
    }

    /**
     * Interact with the game's leaderboards.
     *
     * @param string $sApiKey (optional) set a different apikey than the construct
     * @param string $iGame (optional) set a different appid than the construct
     * @param string $sSteamid (optional) set a different steamid than the construct
     * 
     * @return leaderboards
     */
    public function leaderboards($sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\leaderboards($sApiKey, $iGame, $sSteamid);
    }

    /**
     * Interact with the game's User Generated Content (UGC).
     *
     * @param string $sPublishedFileId The ID of the File
     * @param string $sApiKey (optional) set a different apikey than the construct
     * @param string $iGame (optional) set a different appid than the construct
     * @param string $sSteamid (optional) set a different steamid than the construct
     * 
     * @return ugc
     */
    public function ugc($sPublishedFileId, $sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\ugc($sPublishedFileId, $sApiKey, $iGame, $sSteamid);
    }

    /**
     * Get current info about the Store Page of your app.
     *
     * @param string $iGame (optional) set a different appid than the construct
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @return store Store Object containing a lot of info through the big picture API
     */
    public function store($iGame = null) {
        if ($iGame === null) {
            $iGame = $this->game;
        }
        
        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            //"key" => $this->key,
            "appids" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://store.steampowered.com/api/appdetails?" . $CURLParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        
        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 404) {
                throw new exceptions\SteamRequestParameterException("The App ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }
        
        
        $oStore = $CURLResponse->$iGame->data;

        
        if ($CURLResponse->$iGame->success) {
            return new \justinback\steam\store($iGame, $oStore->name, $oStore->type, $oStore->required_age, $oStore->is_free, $oStore->detailed_description, $oStore->about_the_game, $oStore->short_description, $oStore->developers, $oStore->publishers, $oStore->dlc);
        }
        throw new exceptions\SteamRequestParameterException("The App ID entered is invalid!");
    }

}
