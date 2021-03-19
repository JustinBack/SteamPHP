<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\api;

/**
 * Steam leaderboard managing. 
 * Delete, Add, Reset and Set Scores!
 *
 * @author Justin Back <jback@pixelcatproductions.net>
 */
class SteamLeaderboards {

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
     * Delete a leaderboard from your App
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @param string $sName name of the leaderboard to delete
     *
     * @return bool
     */
    public function DeleteLeaderboard(string $sName): bool {



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

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMLEADERBOARDS,
                        "DeleteLeaderboard",
                        "v1"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("Leaderboard name is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
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
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
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

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMLEADERBOARDS,
                        "FindOrCreateLeaderboard",
                        "v1"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("A parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        return $CURLResponse->result;
    }

    /**
     * Reset a leaderboard for your App
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
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
        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMLEADERBOARDS,
                        "ResetLeaderboard",
                        "v1"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Leaderboard ID is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->result->result != 1) {
            return false;
        }
        return true;
    }

    /**
     * Set a score for your leaderboard for your App
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
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
        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMLEADERBOARDS,
                        "SetLeaderboardScore",
                        "v1"));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Leaderboard ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        return $CURLResponse->result;
    }

    /**
     * Get all leaderboards from your App. Result is cached!
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamEmptyException if the request returns nothing and the result is empty.
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
        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMLEADERBOARDS,
                        "GetLeaderboardsForGame",
                        "v2",
                        $CURLParameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The App ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count($CURLResponse->response->leaderboards) === 0) {
            throw new \justinback\steam\exceptions\SteamEmptyException("Your app doesn't have any leaderboards");
        }

        return $CURLResponse->response->leaderboards;
    }

    /**
     * Get all leaderboard entries from your App
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamEmptyException if the request returns nothing and the result is empty.
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
        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMLEADERBOARDS,
                        "GetLeaderboardEntries",
                        "v1", $CURLParameters));

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
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Leaderboard ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count($CURLResponse->leaderboardEntryInformation->leaderboardEntries) === 0) {
            throw new \justinback\steam\exceptions\SteamEmptyException("Your app doesn't have any leaderboard entries");
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
    public function CSteamApp($sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\api\SteamApp($sApiKey, $iGame, $sSteamid);
    }

}
