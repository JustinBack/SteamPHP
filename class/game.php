<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam game managing.
 * HUB for interacting with other classes and supplying variables automaticly.
 *
 * @author Justin Back <jb@justinback.com>
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
     *
     * @return bool
     */
    public function CheckAppOwnership() {
        $fgcCheckAppOwnership = file_get_contents("https://api.steampowered.com/ISteamUser/GetPublisherAppOwnership/v2?key=" . $this->key . "&steamid=" . $this->steamid);
        $oCheckAppOwnership = json_decode($fgcCheckAppOwnership);

        foreach ($oCheckAppOwnership->appownership->apps as $oApp) {
            if ($oApp->appid == $this->game) {
                return (bool) $oApp->ownsapp;
            }
        }
    }

    /**
     * Get the current number of players of your app
     *
     *
     *
     * @return int
     */
    public function GetNumberOfCurrentPlayers() {
        $fgcGetNumberOfCurrentPlayers = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1?key=" . $this->key . "&appid=" . (int) $this->game);
        $oGetNumberOfCurrentPlayers = json_decode($fgcGetNumberOfCurrentPlayers);
        return $oGetNumberOfCurrentPlayers->response->player_count;
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
     * leaderboards object.
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
     * ugc object.
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
     * gameserver object.
     *
     * @param string $sApiKey (optional) set a different apikey than the construct
     * @param string $iGame (optional) set a different appid than the construct
     * @param string $sSteamid (optional) set a different steamid than the construct
     * 
     * @return gameserver
     */
    public function gameserver($sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\gameserver($sApiKey, $iGame, $sSteamid);
    }

    /**
     * store object.
     *
     * @param string $iGame (optional) set a different appid than the construct
     * 
     * @return store
     */
    public function store($iGame = null) {
        if ($iGame === null) {
            $iGame = $this->game;
        }

        $fgcStore = file_get_contents("https://store.steampowered.com/api/appdetails?appids=" . (int) $iGame);
        $oStore = json_decode($fgcStore);
        $oStore = $oStore->$iGame->data;

        if ($oStore->$iGame->success) {
            return new \justinback\steam\store($iGame, $oStore->name, $oStore->type, $oStore->required_age, $oStore->is_free, $oStore->detailed_description, $oStore->about_the_game, $oStore->short_description, $oStore->developers, $oStore->publishers, $oStore->dlc);
        }
        return false;
    }

}
