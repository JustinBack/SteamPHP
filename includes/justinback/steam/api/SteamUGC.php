<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\api;

/**
 * Steam User Generated Content (UGC / Workshop) managing
 *
 * @author Justin Back <jback@pixelcatproductions.net>
 */
class SteamUGC {

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
     * ID of Published File
     *
     */
    private $fileid = null;

    /**
     * Construction of the variables steamid, key and game
     *
     *
     * @param string $sPublishedFileId UGC File ID
     * @param string $sApiKey Steamworks Developer API Key
     * @param string $iGame Your Appid
     * @param string $sSteamid The SteamID of the user 
     *
     * @return void
     */
    public function __construct($sPublishedFileId = null, $sApiKey = null, $iGame = null, $sSteamid = null) {
        $this->set_key($sApiKey);
        $this->set_game((int) $iGame);
        $this->set_steamid($sSteamid);
        $this->set_fileid($sPublishedFileId);
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
     * Setting File ID from the construct
     *
     *
     * @param string $sPublishedFileId The File's ID
     *
     * @return void
     */
    private function set_fileid($sPublishedFileId) {
        $this->fileid = $sPublishedFileId;
    }

    /**
     * List all files by user as array (Only IDs)
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamEmptyException if the request returns nothing and the result is empty.
     * 
     * @return ugc array
     */
    public function EnumerateUserPublishedFiles(): array {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMREMOTESTORAGE,
                        "EnumerateUserPublishedFiles",
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

        $aList = array();

        foreach ($CURLResponse->response->files as $oFile) {
            array_push($aList, new \justinback\steam\api\ugc($oFile->publishedfileid, $this->key, $this->game, $this->steamid));
        }

        if (count($aList) == 0) {
            throw new \justinback\steam\exceptions\SteamEmptyException("This player has no UGC!");
        }

        return $aList;
    }

    /**
     * Update Ban Status of a UGC
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @param bool $bBanned Is banned or not
     * @param string $sReason Reason why the item was banned. Only visible to admins.
     * @param string $sPublishedFileId (optional) numeric ID of the target file. 
     * @todo Return an error, right now it only returns true and doesnt listen to the API.
     * 
     * @return bool
     */
    public function UpdateBanStatus($bBanned, $sReason, $sPublishedFileId = null) {
        if ($sPublishedFileId == null) {
            $sPublishedFileId = $this->fileid;
        }


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            'publishedfileid' => $sPublishedFileId,
            'banned' => $bBanned,
            'reason' => $sReason
        ));

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        true,
                        \justinback\SteamPHP::PUBLIC_INTERFACE_PUBLISHEDFILESERVICE,
                        "UpdateBanStatus",
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


        return true;
    }

    /**
     * Updates the incompatible status on the published file. "Incompatible" items are hidden from community hubs and user profiles, but can still be accessed by a direct link.
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @param bool $bIncompatible Is banned or not
     * @param string $sPublishedFileId (optional) numeric ID of the target file. 
     * @todo Return an error, right now it only returns true and doesnt listen to the API.
     *
     * 
     * @return bool
     */
    public function UpdateIncompatibleStatus($bIncompatible, $sPublishedFileId = null) {
        if ($sPublishedFileId == null) {
            $sPublishedFileId = $this->fileid;
        }
        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            'publishedfileid' => $sPublishedFileId,
            'incompatible' => $bIncompatible,
        ));

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        true,
                        \justinback\SteamPHP::PUBLIC_INTERFACE_PUBLISHEDFILESERVICE,
                        "UpdateIncompatibleStatus",
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


        return true;
    }

    /**
     * Updates tags on the published file
     *
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @param array $aAddTags Tags array("Maps", "Another Tag")
     * @param array $aRemoveTags Tags array("Tag", "Another Tag")
     * @param string $sPublishedFileId (optional) numeric ID of the target file.  
     * @todo Return an error, right now it only returns true and doesnt listen to the API.
     * 
     * @return bool
     */
    public function UpdateTags($aAddTags, $aRemoveTags, $sPublishedFileId = null) {
        if ($sPublishedFileId == null) {
            $sPublishedFileId = $this->fileid;
        }


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            'publishedfileid' => $sPublishedFileId,
            'add_tags' => $aAddTags,
            'remove_tags' => $aRemoveTags
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/IPublishedFileService/UpdateTags/v1/");

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


        return true;
    }

    /**
     * Get File Info
     *
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamEmptyException if the request returns no results
     * 
     * 
     * @param string $sPublishedFileIds (optional) numeric ID of the target file. 
     * 
     * @return object
     */
    public function GetPublishedFileDetails($sPublishedFileIds = null): object {
        if ($sPublishedFileIds == null) {
            $sPublishedFileIds = $this->fileid;
        }



        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            'publishedfileids[0]' => $sPublishedFileIds,
            "itemcount" => 1
        ));



        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMREMOTESTORAGE,
                        "GetPublishedFileDetails",
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

        foreach ($CURLResponse->response->publishedfiledetails as $oUgc) {
            return current($oUgc);
        }

        throw new \justinback\steam\exceptions\SteamEmptyException("This UGC doesn't exist!");
    }

    /**
     * Subscribe to the published file as the user
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @param string $sPublishedFileId (optional) numeric ID of the target file.
     * @param string $sSteamid (optional) numeric ID of the user.  
     * @todo Return an error, right now it only returns true and doesnt listen to the API.
     * 
     * @return bool
     */
    public function SubscribePublishedFile($sPublishedFileId = null, $sSteamid = null) {
        if ($sPublishedFileId == null) {
            $sPublishedFileId = $this->fileid;
        }
        if ($sSteamid == null) {
            $sSteamid = $this->steamid;
        }


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'publishedfileid' => $sPublishedFileId,
        ));

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        true,
                        \justinback\SteamPHP::PUBLIC_INTERFACE_PUBLISHEDFILESERVICE,
                        "SubscribePublishedFile",
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



        return true;
    }

    /**
     * Unsubscribe from the published file as the user
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @param string $sPublishedFileId (optional) numeric ID of the target file.
     * @param string $sSteamid (optional) numeric ID of the user.  
     * @todo Return an error, right now it only returns true and doesnt listen to the API.
     * 
     * @return bool
     */
    public function UnsubscribePublishedFile($sPublishedFileId = null, $sSteamid = null) {
        if ($sPublishedFileId == null) {
            $sPublishedFileId = $this->fileid;
        }
        if ($sSteamid == null) {
            $sSteamid = $this->steamid;
        }


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'publishedfileid' => $sPublishedFileId,
        ));

        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        true,
                        \justinback\SteamPHP::PUBLIC_INTERFACE_PUBLISHEDFILESERVICE,
                        "UnsubscribePublishedFile",
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



        return true;
    }

    /**
     * Get Creator by UGC
     *
     *
     * @param string $sPublishedFileIds (optional) numeric ID of the target file. 
     * 
     * @return player
     */
    public function GetSteamPersona($sPublishedFileIds = null): SteamPersona {
        if ($sPublishedFileIds == null) {
            $sPublishedFileIds = $this->fileid;
        }


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            'publishedfileids[0]' => $sPublishedFileIds,
            "itemcount" => 1
        ));


        curl_setopt($ch, CURLOPT_URL, \justinback\steam\Utils::ConstructApiUris(
                        false,
                        \justinback\SteamPHP::PARTNER_INTERFACE_STEAMREMOTESTORAGE,
                        "GetPublishedFileDetails",
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

        foreach ($CURLResponse->response->publishedfiledetails as $oUgc) {
            return new \justinback\steam\api\SteamPersona($this->key, $this->game, $oUgc->creator);
        }
        throw new \justinback\steam\exceptions\SteamEmptyException("I haven't found this file or this player!");
    }

}
