<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Manage Steam Achievements.
 * 
 *
 * @author Justin Back <jb@justinback.com>
 */
class achievements {

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
     * @example
     * <code>
     * $achievements = $steam->game()->achievements();
     * </code>
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
     * Retrieve unlocked achievements by the player
     *
     *
     * 
     * 
     * @example
     * <code>
     * $achievements = $steam->game()->achievements();
     * $array = $achievements->GetPlayerAchievements();
     * </code> 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * @return \array Multidimensional array with objects containing apiname (string), achieved (int), unlocktime (int)
     * @link https://partner.steamgames.com/doc/webapi/ISteamUserStats#GetPlayerAchievements
     * <code>
     * array(2) {
     *   [3]=>
     *   object(stdClass)#9 (3) {
     *     ["apiname"]=>
     *     string(20) "ACT_1_BOOK_COLLECTOR"
     *     ["achieved"]=>
     *     int(0)
     *     ["unlocktime"]=>
     *     int(0)
     *   }
     *   [4]=>
     *   object(stdClass)#10 (3) {
     *     ["apiname"]=>
     *     string(19) "ACT_1_TOY_COLLECTOR"
     *     ["achieved"]=>
     *     int(0)
     *     ["unlocktime"]=>
     *     int(0)
     *   }
     * }
     * </code>
     */
    public function GetPlayerAchievements() {

        // Here is some curl stuff

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v1/?" . $CURLParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The SteamID is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        // Now the juicy stuff!

        if ($this->game()->CheckAppOwnership()) {
            $aAchievements = array_filter($CURLResponse->playerstats->achievements, function($oItem) {
                return $oItem->achieved == 1;
            });
            return $aAchievements;
        }
        throw new exceptions\SteamRequestException("An error has ocurred, must be something on valves end!");
    }

    /**
     * Return only locked achievements by the player
     *
     * @example
     * <code>
     * $achievements = $steam->game()->achievements();
     * $array = $achievements->GetPlayerAchievementsLocked();
     * </code> 
     *
     * @return array Same Multidimensional array as GetPlayerAchievements().
     * 
     * <code>
     * array(2) {
     *   [3]=>
     *   object(stdClass)#9 (3) {
     *     ["apiname"]=>
     *     string(20) "ACT_1_BOOK_COLLECTOR"
     *     ["achieved"]=>
     *     int(0)
     *     ["unlocktime"]=>
     *     int(0)
     *   }
     *   [4]=>
     *   object(stdClass)#10 (3) {
     *     ["apiname"]=>
     *     string(19) "ACT_1_TOY_COLLECTOR"
     *     ["achieved"]=>
     *     int(0)
     *     ["unlocktime"]=>
     *     int(0)
     *   }
     * }
     * </code>
     */
    public function GetPlayerAchievementsLocked() {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v1/?" . $CURLParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The SteamID is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($this->game()->CheckAppOwnership()) {
            $aAchievements = array_filter($CURLResponse->playerstats->achievements, function($oItem) {
                return $oItem->achieved == 0;
            });
            return $aAchievements;
        }
        return false;
    }

    /**
     * Get infos about a certain achievement
     *
     *
     * @param string $sApiname APIName of the achievement (not visible name)
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the api name is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @example
     * <code>
     * $achievements = $steam->game()->achievements();
     * $object = $achievements->GetAchievementDetails("ACT_1_LOCKED_OUT");
     * </code> 
     * 
     * @return object The details of an achievement in an object.
     * <code>
     * object(stdClass)#6 (7) {
     *   ["name"]=>
     *   string(16) "ACT_1_LOCKED_OUT"
     *   ["defaultvalue"]=>
     *   int(0)
     *   ["displayName"]=>
     *   string(10) "Locked Out"
     *   ["hidden"]=>
     *   int(0)
     *   ["description"]=>
     *   string(18) "That's not good..."
     *   ["icon"]=>
     *   string(117) "https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/650580/1d52a3c57e72e3f2d06ca987225ab1d990a441d3.jpg"
     *   ["icongray"]=>
     *   string(117) "https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/650580/c54a539174e1e8c57f61810acb0934f7c7a8b8ec.jpg"
     * }
     * </code>
     */
    public function GetAchievementDetails(string $sApiname) {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
                // This can vary from request to request, sometimes its steamid or steamids or even an array.
                //"steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2/?" . $CURLParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The API Name entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        foreach ($CURLResponse->game->availableGameStats->achievements as $oAchievement) {
            if ($oAchievement->name == $sApiname) {
                return $oAchievement;
            }
        }
        throw new exceptions\SteamRequestParameterException("The API Name entered is invalid");
    }

    /**
     * Lock an achievement (Remove) for the specified SteamID. The Achievement must be set to "Official GS" for it to work.
     * 
     * @param string $sApiname APIName of the achievement (not visible name)
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @example
     * <code>
     * $achievements = $steam->game()->achievements();
     * $locked = $achievements->LockAchievement("WALK_100_STEPS");
     * </code> 
     * 
     * @return boolean TRUE if the achievement has been locked otherwise FALSE
     */
    public function LockAchievement($sApiname) {
        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            "name[0]" => $sApiname,
            "value[0]" => 0,
            "count" => 1,
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUserStats/SetUserStatsForGame/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);

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


        if ($CURLResponse->response->result === 1) {
            return true;
        }
        
        if(isset($CURLResponse->response->error)){
            throw new exceptions\SteamRequestParameterException($CURLResponse->response->error);
        }
        return false;
    }

    /**
     * Unlock an achievement for the specified SteamID. The Achievement must be set to "Official GS" for it to work.
     * 
     * @param string $sApiname APIName of the achievement (not visible name)
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @example
     * <code>
     * $achievements = $steam->game()->achievements();
     * $locked = $achievements->UnlockAchievement("WALK_100_STEPS");
     * </code> 
     * 
     * @return boolean TRUE if the achievement has been unlocked otherwise FALSE
     */
    public function UnlockAchievement($sApiname) {
        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            "name[0]" => $sApiname,
            "value[0]" => 1,
            "count" => 1,
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUserStats/SetUserStatsForGame/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);

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


        if ($CURLResponse->response->result === 1) {
            return true;
        }
        
        if(isset($CURLResponse->response->error)){
            throw new exceptions\SteamRequestParameterException($CURLResponse->response->error);
        }
        return false;
    }

    /**
     * Check if player has unlocked the specified achievement
     *
     *
     * @param string $apiname APIName of the achievement (not visible name)
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * 
     * @example
     * <code>
     * $achievements = $steam->game()->achievements();
     * $bool = $achievements->HasPlayerUnlockedAchievement("ACT_1_LOCKED_OUT");
     * </code> 
     * 
     * 
     * @return boolean TRUE if the player has unlocked the achievement otherwise FALSE
     */
    public function HasPlayerUnlockedAchievement($sApiname) {
        foreach ($this->GetPlayerAchievements() as $oUserAchievement) {
            if ($oUserAchievement->apiname == $sApiname) {
                return true;
            }
            return false;
        }
    }

    /**
     * The game class for more functions regarding the game itself.
     *
     * @param string $sApiKey (optional) set a different apikey than the construct
     * @param string $iGame (optional) set a different appid than the construct
     * @param string $sSteamid (optional) set a different steamid than the construct
     * 
     * @return game The game class for more functions regarding the game itself
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

}
