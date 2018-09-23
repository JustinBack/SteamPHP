<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam manager hub
 *
 * @author Justin Back <jb@justinback.com>
 */
class manager {

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
        return new \justinback\steam\player($sApiKey, $iGame, $sSteamid);
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
        return new \justinback\steam\game($sApiKey, $iGame, $sSteamid);
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
        return new \justinback\steam\ugc($sPublishedFileId, $sApiKey, $iGame, $sSteamid);
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
        $oStoreInfo = $oStore->$iGame->data;

        if ($oStore->$iGame->success) {
            return new \justinback\steam\store($iGame, $oStoreInfo->name, $oStoreInfo->type, $oStoreInfo->required_age, $oStoreInfo->is_free, $oStoreInfo->detailed_description, $oStoreInfo->about_the_game, $oStoreInfo->short_description, $oStoreInfo->developers, $oStoreInfo->publishers, $oStoreInfo->dlc);
        }
        return false;
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
        return new \justinback\steam\transactions($bTesting, $sApiKey, $iGame, $sSteamid);
    }

}
