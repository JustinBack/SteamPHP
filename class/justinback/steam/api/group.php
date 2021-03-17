<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\api;

/**
 * Steam group managing.
 * Get group name, avatar and members!
 *
 * @author Justin Back <jback@pixelcatproductions.net>
 */
class group implements \justinback\steam\interfaces\IGroup {

    /**
     * GlobalID of the group
     *
     */
    public $gid = null;

    /**
     * APIKey from steamworks
     *
     */
    private $key = null;

    /**
     * App ID of your app
     *
     */
    private $appid = null;

    /**
     * Construction of the variables
     *
     *
     * @param string $sGid The GlobalID of the group
     * @param string $sAPIKey [optional] The APIKey from steamworks
     * @param string $sAppID [optional] The AppID of your app
     *
     * @return void
     */
    public function __construct($sGid, $sAPIKey = null, $sAppID = null) {
        $this->gid = $sGid;
        $this->appid = $sAppID;
        $this->key = $sAPIKey;
    }

    /**
     * Get the name of the group
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the group global id is not valid as a parameter
     *
     * @return string the group name
     */
    public function GetGroupName(): string {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://steamcommunity.com/gid/" . $this->gid . "/memberslistxml?xml=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = curl_exec($ch);
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 404) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Group Global ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oGetGroupName = simplexml_load_string($CURLResponse, null, LIBXML_NOCDATA);

        return current($oGetGroupName->groupDetails->groupName);
    }

    /**
     * Get the Custom URL of the group
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the group global id is not valid as a parameter
     *
     * @return string
     */
    public function GetGroupURL(): string {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://steamcommunity.com/gid/" . $this->gid . "/memberslistxml?xml=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = curl_exec($ch);
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 404) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Group Global ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oGetGroupName = simplexml_load_string($CURLResponse, null, LIBXML_NOCDATA);

        return current($oGetGroupName->groupDetails->groupURL);
    }

    /**
     * Get the Headline of the group
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the group global id is not valid as a parameter
     *
     * @return string
     */
    public function GetGroupHeadline(): string {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://steamcommunity.com/gid/" . $this->gid . "/memberslistxml?xml=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = curl_exec($ch);
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 404) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Group Global ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oGetGroupName = simplexml_load_string($CURLResponse, null, LIBXML_NOCDATA);

        return current($oGetGroupName->groupDetails->headline);
    }

    /**
     * Get the summary of the group
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the group global id is not valid as a parameter
     *
     * @return string
     */
    public function GetGroupSummary(): string {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://steamcommunity.com/gid/" . $this->gid . "/memberslistxml?xml=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = curl_exec($ch);
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 404) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Group Global ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oGetGroupName = simplexml_load_string($CURLResponse, null, LIBXML_NOCDATA);

        return current($oGetGroupName->groupDetails->summary);
    }

    /**
     * Get the group id 64 of the group
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the group global id is not valid as a parameter
     *
     * @return string
     */
    public function GetGroupID64(): string {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://steamcommunity.com/gid/" . $this->gid . "/memberslistxml?xml=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = curl_exec($ch);
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 404) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Group Global ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oGetGroupName = simplexml_load_string($CURLResponse, null, LIBXML_NOCDATA);

        return current($oGetGroupName->groupID64);
    }

    /**
     * Get the avatars of the group
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the group global id is not valid as a parameter
     *
     * @return object
     * <code>
     * object(stdClass)#6 (3) {
     *   ["icon"]=>
     *   string(116) "https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/71/7184a353cd6a120017d14f72c30f9bffb4103f31.jpg"
     *   ["medium"]=>
     *   string(123) "https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/71/7184a353cd6a120017d14f72c30f9bffb4103f31_medium.jpg"
     *   ["full"]=>
     *   string(121) "https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/71/7184a353cd6a120017d14f72c30f9bffb4103f31_full.jpg"
     * }
     * </code>
     */
    public function GetGroupAvatars(): object {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://steamcommunity.com/gid/" . $this->gid . "/memberslistxml?xml=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = curl_exec($ch);
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 404) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Group Global ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oGetGroupName = simplexml_load_string($CURLResponse, null, LIBXML_NOCDATA);

        $oAvatars = new \stdClass();

        $oAvatars->icon = current($oGetGroupName->groupDetails->avatarIcon);
        $oAvatars->medium = current($oGetGroupName->groupDetails->avatarMedium);
        $oAvatars->full = current($oGetGroupName->groupDetails->avatarFull);

        return $oAvatars;
    }

    /**
     * Get the members of the group
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamEmptyException if the request returns nothing and the result is empty.
     * 
     * @return player array
     */
    public function GetGroupMembers(): array {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://steamcommunity.com/gid/" . $this->gid . "/memberslistxml?xml=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = curl_exec($ch);
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 404) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Group Global ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oGetGroupName = simplexml_load_string($CURLResponse, null, LIBXML_NOCDATA);

        $aMembers = array();
        foreach ($oGetGroupName->members->steamID64 as $oMember) {
            array_push($aMembers, new \justinback\steam\player($this->key, $this->appid, current($oMember)));
        }

        if (count($aMembers) === 1) {
            throw new \justinback\steam\exceptions\SteamEmptyException("This group has no members besides the creator!");
        }

        return $aMembers;
    }

    /**
     * Get the member statistics of the group
     *
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the group global id is not valid as a parameter
     * 
     * 
     * @return object
     * <code>
     * object(stdClass)#6 (4) {
     *   ["Total"]=>
     *   string(5) "23024"
     *   ["InChat"]=>
     *   string(4) "1020"
     *   ["InGame"]=>
     *   string(3) "314"
     *   ["Online"]=>
     *   string(4) "4856"
     * }
     * </code>
     */
    public function GetGroupStats(): object {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://steamcommunity.com/gid/" . $this->gid . "/memberslistxml?xml=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $CURLResponse = curl_exec($ch);
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 404) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("The Group Global ID entered is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oGetGroupName = simplexml_load_string($CURLResponse, null, LIBXML_NOCDATA);

        $oMembers = new \stdClass();
        $aMembers = array();

        $oMembers->Total = current($oGetGroupName->groupDetails->memberCount);
        $oMembers->InChat = current($oGetGroupName->groupDetails->membersInChat);
        $oMembers->InGame = current($oGetGroupName->groupDetails->membersInGame);
        $oMembers->Online = current($oGetGroupName->groupDetails->membersOnline);

        return $oMembers;
    }

}
