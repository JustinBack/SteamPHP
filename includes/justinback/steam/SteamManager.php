<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam manager hub
 *
 * @author Justin Back <jback@pixelcatproductions.net>
 */
class SteamManager {

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
     * Our Utils class in a handy variable!
     * @var utils
     */
    private $Utils = null;

    /**
     * Construction of the variables steamid, key and game
     *
     *
     * @param string $sApiKey Steamworks Developer API Key
     * @param string $iGame Your Appid
     * @param string $sSteamid The SteamID of the user 
     *
     * @throws exceptions\SteamIDInvalidException if steam ID is invalid
     * 
     * @return void
     */
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null) {

        $this->Utils = new utils();

        if ($sSteamid !== null) {
            if (!$this->Utils->IsValidSteamID($sSteamid)) {
                throw new \justinback\steam\exceptions\SteamIDInvalidException("The SteamID provided is invalid, it must be a Steam64 ID!");
            }
        }

        $this->set_key($sApiKey);
        $this->set_game((int) $iGame);
        if ($sSteamid !== null) {
            $this->set_steamid($sSteamid);
        }
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
     * player object.
     *
     * @param string $sApiKey (optional) set a different apikey than the construct
     * @param string $iGame (optional) set a different appid than the construct
     * @param string $sSteamid (optional) set a different steamid than the construct
     * 
     * @return player
     */
    public function player($sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\api\player($sApiKey, $iGame, $sSteamid);
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
    public function game($sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\api\game($sApiKey, $iGame, $sSteamid);
    }

    /**
     * UGC object.
     *
     * @param string $sPublishedFileId ID of the File
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
        return new \justinback\steam\api\ugc($sPublishedFileId, $sApiKey, $iGame, $sSteamid);
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
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The App ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oStore = $CURLResponse->$iGame->data;


        if ($CURLResponse->$iGame->success) {
            return new \justinback\steam\api\store($iGame, $oStore->name, $oStore->type, $oStore->required_age, $oStore->is_free, $oStore->detailed_description, $oStore->about_the_game, $oStore->short_description, $oStore->developers, $oStore->publishers, $oStore->dlc);
        }
        throw new \justinback\steam\exceptions\SteamRequestParameterException("The App ID entered is invalid!");
    }

    /**
     * Transaction object.
     *
     * @param bool $bTesting Sandbox or Production environment?
     * @param string $sApiKey (optional) set a different apikey than the construct
     * @param string $iGame (optional) set a different appid than the construct
     * @param string $sSteamid (optional) set a different steamid than the construct
     * 
     * @return transactions
     */
    public function transactions($bTesting = false, $sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\api\transactions($bTesting, $sApiKey, $iGame, $sSteamid);
    }

    /**
     * Group object.
     *
     * @param string $sGID Steam Community global id (gid). typically in https://steamcommunity.com/gid/<GID>
     * @param string $sAPIKey [optional] Your API Key
     * @param string $sGame [optional] Your App ID
     * 
     * @return group
     */
    public function group($sGID, $sAPIKey, $sGame) {
        return new \justinback\steam\api\group($sGID, $sAPIKey, $sGame);
    }

}
