<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam leaderboard managing. 
 * Delete, Add, Reset and Set Scores!
 *
 * @author Justin Back <jb@justinback.com>
 */
class leaderboards {

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
     * Delete a leaderboard from your App
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @param string $sName name of the leaderboard to delete
     *
     * @return bool
     */
    public function DeleteLeaderboard($sName) {



        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            'name' => $sName,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/ISteamLeaderboards/DeleteLeaderboard/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("Leaderboard name is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->result->result != 1) {
            return false;
        }
        return true;
    }

    /**
     * Create a leaderboard for your App
     *
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @param string $sName name of the leaderboard to create
     * @param string $sSortMethod sort method to use for this leaderboard (defaults to Ascending)
     * @param string $sDisplayType display type for this leaderboard (defaults to Numeric)
     * @param bool $bCreateIfNotFound if this is true the leaderboard will be created if it doesn't exist. Defaults to true.
     * @param bool $bOnlyTrustedWrites if this is true the leaderboard scores cannot be set by clients, and can only be set by publisher via SetLeaderboardScore WebAPI. Defaults to false.
     * @param bool $bOnlyFriendsReads if this is true the leaderboard scores can only be read for friends by clients, scores can always be read by publisher. Defaults to false.
     *
     * @return object
     * <code>
     * object(stdClass)#4 (2) {
     *   ["result"]=>
     *   int(1)
     *   ["test"]=>
     *   object(stdClass)#6 (6) {
     *     ["leaderBoardID"]=>
     *     int(3337451)
     *     ["leaderBoardEntries"]=>
     *     int(0)
     *     ["leaderBoardSortMethod"]=>
     *     string(9) "Ascending"
     *     ["leaderBoardDisplayType"]=>
     *     string(7) "Numeric"
     *     ["onlytrustedwrites"]=>
     *     bool(false)
     *     ["onlyfriendsreads"]=>
     *     bool(false)
     *   }
     * }
     * </code>
     */
    public function FindOrCreateLeaderboard($sName, $sSortMethod = "Ascending", $sDisplayType = "Numeric", $bCreateIfNotFound = true, $bOnlyTrustedWrites = false, $bOnlyFriendsReads = false) {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            'name' => $sName,
            'sortmethod' => $sSortMethod,
            'displaytype' => $sDisplayType,
            'createifnotfound' => $bCreateIfNotFound,
            'onlytrustedwrites' => $bOnlyTrustedWrites,
            'onlyfriendsreads' => $bOnlyFriendsReads,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/ISteamLeaderboards/FindOrCreateLeaderboard/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("A parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        return $CURLResponse->result;
    }

    /**
     * Reset a leaderboard for your App
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @param string $sLeaderboardId numeric ID of the target leaderboard. Can be retrieved from GetLeaderboardsForGame
     * 
     * @return bool
     */
    public function ResetLeaderboard($sLeaderboardId) {



        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            'leaderboardid' => $sLeaderboardId
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/ISteamLeaderboards/ResetLeaderboard/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Leaderboard ID is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->result->result != 1) {
            return false;
        }
        return true;
    }

    /**
     * Set a score for your leaderboard for your App
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @param string $sLeaderboardId numeric ID of the target leaderboard. Can be retrieved from GetLeaderboardsForGame
     * @param string $sScore the score to set for this user
     * @param string $sScoreMethod update method to use. Can be "KeepBest" or "ForceUpdate"
     * @param rawbytes $rDetails (optional) game-specific details for how the score was earned. Up to 256 bytes.
     * 
     * @return object
     * <code>
     * object(stdClass)#4 (5) {
     *   ["result"]=>
     *   int(1)
     *   ["leaderboard_entry_count"]=>
     *   int(1)
     *   ["score_changed"]=>
     *   bool(true)
     *   ["global_rank_previous"]=>
     *   int(0)
     *   ["global_rank_new"]=>
     *   int(1)
     * }
     * </code>
     */
    public function SetLeaderboardScore($sLeaderboardId, $sScore, $sScoreMethod, $rDetails = null) {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'leaderboardid' => $sLeaderboardId,
            'score' => $sScore,
            'scoremethod' => $sScoreMethod,
            'details' => $rDetails
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/ISteamLeaderboards/SetLeaderboardScore/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Leaderboard ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        return $CURLResponse->result;
    }

    /**
     * Get all leaderboards from your App. Result is cached!
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @return object
     * <code>
     * array(1) {
     *   [0]=>
     *   object(stdClass)#6 (7) {
     *     ["id"]=>
     *     int(3337465)
     *     ["name"]=>
     *     string(4) "test"
     *     ["entries"]=>
     *     int(1)
     *     ["sortmethod"]=>
     *     string(9) "Ascending"
     *     ["displaytype"]=>
     *     string(7) "Numeric"
     *     ["onlytrustedwrites"]=>
     *     bool(false)
     *     ["onlyfriendsreads"]=>
     *     bool(false)
     *   }
     * }
     * </code>
     */
    public function GetLeaderboardsForGame() {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
                // This can vary from request to request, sometimes its steamid or steamids or even an array.
                //"steamid" => $this->steamid,
                // Custom Queries below here.
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/ISteamLeaderboards/GetLeaderboardsForGame/v2/?" . $CURLParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The App ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count($CURLResponse->response->leaderboards) === 0) {
            throw new exceptions\SteamException("Your app doesn't have any leaderboards");
        }

        return $CURLResponse->response->leaderboards;
    }

    /**
     * Get all leaderboard entries from your App
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @param int $sLeaderboardId ID of the leaderboard to view
     * @param string $sDataRequest type of request: RequestGlobal, RequestAroundUser, RequestFriends
     * @param int $iRangeStart range start or 0
     * @param int $iRangeEnd range end or max LB entries
     * @param bool $bSteamid Use SteamID to lookup or not
     * 
     * 
     * @return array
     */
    public function GetLeaderboardEntries($sLeaderboardId, $sDataRequest, $iRangeStart, $iRangeEnd, $bSteamid = true) {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            "leaderboardid" => $sLeaderboardId,
            "datarequest" => $sDataRequest,
            "rangestart" => $iRangeStart,
            "rangeend" => $iRangeEnd,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/ISteamLeaderboards/GetLeaderboardEntries/v1/?" . $CURLParameters);

        if ($bSteamid) {
            $CURLParameters = http_build_query(array(
                // Our default parameters!
                "key" => $this->key,
                "appid" => $this->game,
                // This can vary from request to request, sometimes its steamid or steamids or even an array.
                //"steamid" => $this->steamid,
                // Custom Queries below here.
                "leaderboardid" => $sLeaderboardId,
                "datarequest" => $sDataRequest,
                "rangestart" => $iRangeStart,
                "rangeend" => $iRangeEnd,
                "steamid" => $this->steamid,
            ));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Leaderboard ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count($CURLResponse->leaderboardEntryInformation->leaderboardEntries) === 0) {
            throw new exceptions\SteamException("Your app doesn't have any leaderboard entries");
        }


        return $CURLResponse->leaderboardEntryInformation->leaderboardEntries;
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

}
