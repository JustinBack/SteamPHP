<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam player managing.
 * Get Player name, avatar and report cheating!
 *
 * @author Justin Back <jback@pixelcatproductions.net>
 */
class player {

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
     * Get the Community name of the user
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @return string
     */
    public function GetPersonaName() {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            //"appids" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamids" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?" . $CURLParameters);
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
                throw new exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count((array) $CURLResponse->response->players) === 0) {
            throw new exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
        }


        foreach ($CURLResponse->response->players as $oPlayer) {
            return $oPlayer->personaname;
        }

        throw new exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
    }

    /**
     * Get the ban overview of the user
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws exceptions\SteamEmptyException if the request returns nothing and the result is empty.
     *
     * @return array Multidimensional array containing SteamId (string), CommunityBanned (bool), VACBanned (int), NumberOfVACBans (int), DaysSinceLastBan (int), NumberOfGameBans (int), bans (array), EconomyBan (string) fields.
     * <code>
     * array(1) {
     *   [0]=>
     *   object(stdClass)#5 (8) {
     *     ["SteamId"]=>
     *     string(17) "76561198147337306"
     *     ["CommunityBanned"]=>
     *     bool(false)
     *     ["VACBanned"]=>
     *     bool(true)
     *     ["NumberOfVACBans"]=>
     *     int(1)
     *     ["DaysSinceLastBan"]=>
     *     int(1303)
     *     ["NumberOfGameBans"]=>
     *     int(0)
     *     ["bans"]=>
     *     array(0) {
     *     }
     *     ["EconomyBan"]=>
     *     string(4) "none"
     *   }
     * }
     * </code>
     * 
     */
    public function GetPlayerBans() {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            //"appids" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamids" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?" . $CURLParameters);
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
                throw new exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count((array) $CURLResponse->players) === 0) {
            throw new exceptions\SteamEmptyException("The Steam ID entered has no bans!");
        }

        return $CURLResponse->players;
    }

    /**
     * Returns the Steam Level of a user
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @return int The Steam Level of the user
     */
    public function GetSteamLevel() {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            //"appids" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/IPlayerService/GetSteamLevel/v1/?" . $CURLParameters);
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
                throw new exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->response->player_level == null) {
            throw new exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
        }

        return $CURLResponse->response->player_level;
    }

    /**
     * Get the friendlist overview of the user
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws exceptions\SteamEmptyException if the request returns nothing and the result is empty.
     *
     * @return player An array containing a player object with information about the friends. Each friend has its own object.
     */
    public function GetFriendList() {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            //"appids" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUser/GetFriendList/v1/?" . $CURLParameters);
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
                throw new exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        $aUsers = array();

        foreach ($CURLResponse->friendslist->friends as $aFriends) {
            array_push($aUsers, new \justinback\steam\player($this->key, $this->game, $aFriends->steamid));
        }


        if (count($aUsers) === 0) {
            throw new exceptions\SteamEmptyException("This user has no friends!");
        }

        return $aUsers;
    }

    /**
     * Get the group overview of the user
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws exceptions\SteamEmptyException if the request returns nothing and the result is empty.
     *
     * @return group array
     */
    public function GetUserGroupList() {



        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            //"appids" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUser/GetUserGroupList/v1/?" . $CURLParameters);
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
                throw new exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        $aGroups = array();

        foreach ($CURLResponse->response->groups as $oGroup) {
            array_push($aGroups, new \justinback\steam\group($oGroup->gid, $this->key, $this->game));
        }

        if (count($aGroups) === 0) {
            throw new exceptions\SteamEmptyException("This user has no groups!");
        }

        return $aGroups;
    }

    /**
     * This function has been moved to the \justinback\steam\utils class!
     *
     * @throws exceptions\JBDeprecatedException This function is deprecated
     * @deprecated
     * @see utils
     * 
     */
    public function GetSteamIDs() {
        throw new exceptions\JBDeprecatedException("This function has been deprecated! Use \justinback\steam\utils\ConvertSteamID() instead!");
    }

    /**
     * Get the Avatar in an object (small, medium, full)
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @return object
     */
    public function GetAvatar() {
        $oObject = new \stdClass();


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            //"appids" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamids" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?" . $CURLParameters);
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
                throw new exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count((array) $CURLResponse->response->players) === 0) {
            throw new exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
        }

        foreach ($CURLResponse->response->players as $oPlayer) {
            $oObject->small = $oPlayer->avatar;
            $oObject->medium = $oPlayer->avatarmedium;
            $oObject->full = $oPlayer->avatarfull;
            return $oObject;
        }
        throw new exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
    }

    /**
     * Get the Realname from the user
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @return string
     */
    public function GetRealName() {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            //"appids" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamids" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?" . $CURLParameters);
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
                throw new exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count((array) $CURLResponse->response->players) === 0) {
            throw new exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
        }
        foreach ($CURLResponse->response->players as $oPlayer) {
            return $oPlayer->realname;
        }
        throw new exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
    }

    /**
     * ReportPlayerCheating is designed to gather community reports of cheating, where one player reports another player within the game.
     *
     * It is intended for unreliable data from peers in the game ( semi-trusted sources ). The back-end that reports the data should ensure that both parties are authenticated, but the data in itself is treated as hearsay. Optional parameters may be used to encode the type of cheating that is suspected or additional evidence ( an identifier pointing to the match/demo for further review )
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @param string $sSteamidreporter (Optional) The Steam ID of the user or game server who is reporting the cheating.
     * @param string $sAppData (Optional) App specific data about the type of cheating set by developer. (ex 1 = Aimbot, 2 = Wallhack, 3 = Griefing)
     * @param bool $bHeuristic (Optional) Extra information about the source of the cheating - was it a heuristic.
     * @param bool $bDetection (Optional) Extra information about the source of the cheating - was it a detection.
     * @param bool $bPlayerReport (Optional) Extra information about the source of the cheating - was it a player report.
     * @param bool $bNoReportId (Optional) Don't return reportid. This should only be passed if you don't intend to issue a ban based on this report.
     * @param int $iGamemode (Optional) Extra information about state of game - was it a specific type of game play or game mode. (0 = generic)
     * @param int $iSuspicionStartTime (Optional) Extra information indicating how far back the game thinks is interesting for this user. Unix epoch time (time since Jan 1st, 1970).
     * @param int $iSeverity (Optional) Level of severity of bad action being reported. Scale set by developer.
     * 
     * 
     * @return anticheat
     */
    public function ReportPlayerCheating($sSteamidreporter = 0, $sAppData = 0, $bHeuristic = false, $bDetection = false, $bPlayerReport = false, $bNoReportId = false, $iGamemode = 0, $iSuspicionStartTime = 0, $iSeverity = 0) {
        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'steamidreporter' => $sSteamidreporter,
            'appdata' => $sAppData,
            'heuristic' => $bHeuristic,
            'detection' => $bDetection,
            'playerreport' => $bPlayerReport,
            'noreportid' => $bNoReportId,
            'gamemode' => $iGamemode,
            'suspicionstarttime' => $iSuspicionStartTime,
            'severity' => $iSeverity,
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.steampowered.com/ICheatReportingService/ReportPlayerCheating/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);

        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The parameters are invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        return new \justinback\steam\anticheat($CURLResponse->response->reportid, $this->key, $this->game, $this->steamid);
    }

    /**
     * Allows publishers to report users who are behaving badly on their community hub.
     *
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws exceptions\SteamEmptyException if the request returns nothing and the result is empty.
     * 
     * @param string $sSteamidreporter SteamID of user doing the reporting
     * @param int $iAbuseType Abuse type code (see EAbuseReportType enum)
     * @param int $iContentType Content type code (see ECommunityContentType enum)
     * @param string $sDescription Narrative from user
     * @param string $sGid (optional) GID of related record (depends on content type)
     * 
     * @return bool
     */
    public function ReportAbuse($sSteamidreporter, $iAbuseType, $iContentType, $sDescription, $sGid = null) {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            'steamidTarget' => $this->steamid,
            'steamidActor' => $sSteamidreporter,
            'abuseType' => $iAbuseType,
            'contentType' => $iContentType,
            'description' => $sDescription,
            'gid' => $sGid,
        ));
        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/ISteamCommunity/ReportAbuse/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);

        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The parameters are invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        $oObj = new \stdClass();
        if ($CURLResponse->result > success == 1) {
            return true;
        }

        throw new exceptions\SteamRequestParameterException($CURLResponse->result->message);
    }

    /**
     * Remove a game ban on a player.
     *
     * This is used if a Game ban is determined to be a false positive.
     *
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws exceptions\SteamEmptyException if the request returns nothing and the result is empty.
     * 
     * @return bool|exceptions\SteamEmptyException TRUE on unban otherwise exceptions\SteamEmptyException
     */
    public function RemovePlayerGameBan() {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/ICheatReportingService/RemovePlayerGameBan/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);

        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The parameters are invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count($CURLResponse->response) == 0) {
            throw new exceptions\SteamEmptyException("This player is not banned!");
        }
        return true;
    }

    /**
     * List all files by user as array (Only IDs)
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws exceptions\SteamEmptyException if the request returns nothing and the result is empty.
     * 
     * @return ugc array
     */
    public function EnumerateUserPublishedFiles() {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));
        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/ISteamRemoteStorage/EnumerateUserPublishedFiles/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);

        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The parameters are invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        $aList = array();

        foreach ($CURLResponse->response->files as $oFile) {
            array_push($aList, new \justinback\steam\ugc($oFile->publishedfileid, $this->key, $this->game, $this->steamid));
        }

        if (count($aList) == 0) {
            throw new exceptions\SteamEmptyException("This player has no UGC!");
        }

        return $aList;
    }

    /**
     * achievements object.
     *
     * 
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
     * inventory object.
     *
     * @param string $sApiKey (optional) set a different apikey than the construct
     * @param string $iGame (optional) set a different appid than the construct
     * @param string $sSteamid (optional) set a different steamid than the construct
     * 
     * @return inventory
     */
    public function inventory($sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\inventory($sApiKey, $iGame, $sSteamid);
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

    /**
     * User Authentication object.
     *
     * @param string $sApiKey (optional) set a different apikey than the construct
     * @param string $iGame (optional) set a different appid than the construct
     * @param string $sSteamid (optional) set a different steamid than the construct
     * 
     * @return userauth
     */
    public function auth($sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\userauth($sApiKey, $iGame, $sSteamid);
    }

}
