<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\api;

/**
 * Steam game managing.
 * HUB for interacting with other classes and supplying variables automatically.
 *
 * @author Justin Back <jback@pixelcatproductions.net>
 */
class SteamApp implements \justinback\steam\interfaces\ISteamApp {

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
        $this->key = $sApiKey;
        $this->game = (int) $iGame;
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
    public function CheckAppOwnership(): bool {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            //"appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMUSER,
                        "GetPublisherAppOwnership",
                        "v2", $CURLParameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        foreach ($CURLResponse->appownership->apps as $oApp) {
            if ($oApp->appid == $this->game) {
                return (bool) $oApp->ownsapp;
            }
        }
        throw new \justinback\steam\exceptions\SteamException("The App or Steam ID entered is invalid!");
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
    public function GetNumberOfCurrentPlayers(): int {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
                // This can vary from request to request, sometimes its steamid or steamids or even an array.
                //"steamid" => $this->steamid,
                // Custom Queries below here.
        ));

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        true,
                        \justinback\SteamPHP::PUBLIC_INTERFACE_STEAMUSERSTATS,
                        "GetNumberOfCurrentPlayers",
                        "v1", $CURLParameters));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The App ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
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
    public function CAchievements($sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\Achievements {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\api\Achievements($sApiKey, $iGame, $sSteamid);
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
    public function CLeaderboards($sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\SteamLeaderboards {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\api\SteamLeaderboards($sApiKey, $iGame, $sSteamid);
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
    public function CSteamUGC($sPublishedFileId, $sApiKey = null, $iGame = null, $sSteamid = null): \justinback\steam\api\SteamUGC {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\api\SteamUGC($sPublishedFileId, $sApiKey, $iGame, $sSteamid);
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
    public function CStorePage($iGame = null): \justinback\steam\api\StorePage {
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
            return new \justinback\steam\api\StorePage($iGame, $oStore->name, $oStore->type, $oStore->required_age, $oStore->is_free, $oStore->detailed_description, $oStore->about_the_game, $oStore->short_description, $oStore->developers, $oStore->publishers, $oStore->dlc);
        }
        throw new \justinback\steam\exceptions\SteamRequestParameterException("The App ID entered is invalid!");
    }

}
