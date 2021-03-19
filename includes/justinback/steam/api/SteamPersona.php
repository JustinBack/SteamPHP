<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\api;

use justinback\Hook;

/**
 * Steam player managing.
 * Get Player name, avatar and report cheating!
 *
 * @author Justin Back <jback@pixelcatproductions.net>
 */
class SteamPersona {

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
     * Get the Community name of the user
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
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

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMUSER,
                        "GetPlayerSummaries",
                        "v2",
                        $CURLParameters));

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
                throw new \justinback\steam\exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count((array) $CURLResponse->response->players) === 0) {
            throw new \justinback\steam\exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
        }


        foreach ($CURLResponse->response->players as $oPlayer) {
            return $oPlayer->personaname;
        }

        throw new \justinback\steam\exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
    }

    /**
     * Get the ban overview of the user
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamEmptyException if the request returns nothing and the result is empty.
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

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMUSER,
                        "GetPlayerBans",
                        "v1",
                        $CURLParameters));
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
                throw new \justinback\steam\exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count((array) $CURLResponse->players) === 0) {
            throw new \justinback\steam\exceptions\SteamEmptyException("The Steam ID entered has no bans!");
        }

        return $CURLResponse->players;
    }

    /**
     * Returns the Steam Level of a user
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
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
        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMUSER,
                        "GetSteamLevel",
                        "v1",
                        $CURLParameters));
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
                throw new \justinback\steam\exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->response->player_level == null) {
            throw new \justinback\steam\exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
        }

        return $CURLResponse->response->player_level;
    }

    /**
     * Get the friendlist overview of the user
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamEmptyException if the request returns nothing and the result is empty.
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

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMUSER,
                        "GetFriendList",
                        "v1",
                        $CURLParameters));

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
                throw new \justinback\steam\exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        $aUsers = array();

        foreach ($CURLResponse->friendslist->friends as $aFriends) {
            array_push($aUsers, new \justinback\steam\api\player($this->key, $this->game, $aFriends->steamid));
        }


        if (count($aUsers) === 0) {
            throw new \justinback\steam\exceptions\SteamEmptyException("This user has no friends!");
        }

        return $aUsers;
    }

    /**
     * Get the group overview of the user
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamEmptyException if the request returns nothing and the result is empty.
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
        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMUSER,
                        "GetUserGroupList",
                        "v1",
                        $CURLParameters));

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
                throw new \justinback\steam\exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        $aGroups = array();

        foreach ($CURLResponse->response->groups as $oGroup) {
            array_push($aGroups, new \justinback\steam\api\group($oGroup->gid, $this->key, $this->game));
        }

        if (count($aGroups) === 0) {
            throw new \justinback\steam\exceptions\SteamEmptyException("This user has no groups!");
        }

        return $aGroups;
    }

    /**
     * Get the Avatar in an object (small, medium, full)
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
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

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMUSER,
                        "GetPlayerSummaries",
                        "v2",
                        $CURLParameters));
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
                throw new \justinback\steam\exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count((array) $CURLResponse->response->players) === 0) {
            throw new \justinback\steam\exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
        }

        foreach ($CURLResponse->response->players as $oPlayer) {
            $oObject->small = $oPlayer->avatar;
            $oObject->medium = $oPlayer->avatarmedium;
            $oObject->full = $oPlayer->avatarfull;
            return $oObject;
        }
        throw new \justinback\steam\exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
    }

    /**
     * Get the Realname from the user
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
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
        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMUSER,
                        "GetPlayerSummaries",
                        "v2",
                        $CURLParameters));
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
                throw new \justinback\steam\exceptions\SteamException("Steam ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if (count((array) $CURLResponse->response->players) === 0) {
            throw new \justinback\steam\exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
        }
        foreach ($CURLResponse->response->players as $oPlayer) {
            return $oPlayer->realname;
        }
        throw new \justinback\steam\exceptions\SteamRequestParameterException("The Steam ID entered is invalid!");
    }

    /**
     * Allows publishers to report users who are behaving badly on their community hub.
     *
     * 
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamEmptyException if the request returns nothing and the result is empty.
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

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMCOMMUNITY,
                        "ReportAbuse",
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
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The parameters are invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->result->success == 1) {
            return true;
        }

        throw new \justinback\steam\exceptions\SteamRequestParameterException($CURLResponse->result->message);
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
    public function CAchievements($sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\Achievements($sApiKey, $iGame, $sSteamid);
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
    public function CUserInventory($sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\api\UserInventory($sApiKey, $iGame, $sSteamid);
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
    public function CSteamMicrotransactions($bTesting = false, $sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\api\SteamMicrotransactions($bTesting, $sApiKey, $iGame, $sSteamid);
    }

    /**
     * AntiCheat object.
     *
     * @param string $sReportID (optional) The Report ID
     * @param string $sApiKey (optional) set a different apikey than the construct
     * @param string $iGame (optional) set a different appid than the construct
     * @param string $sSteamid (optional) set a different steamid than the construct
     * 
     * @return transactions
     */
    public function CAntiCheat($sReportID = null, $sApiKey = null, $iGame = null, $sSteamid = null) {
        if ($sApiKey === null) {
            $sApiKey = $this->key;
        }
        if ($iGame === null) {
            $iGame = $this->game;
        }
        if ($sSteamid === null) {
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\api\AntiCheat($sReportID, $sApiKey, $iGame, $sSteamid);
    }

}
