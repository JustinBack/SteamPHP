<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam group managing.
 * Get group name, avatar and members!
 *
 * @author Justin Back <jb@justinback.com>
 */
class group {

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
     * GlobalID of the group
     *
     */
    public $gid = null;

    /**
     * Construction of the variables steamid, key and game
     *
     *
     * @param string $sApiKey Steamworks Developer API Key
     * @param string $iGame Your Appid
     * @param string $sSteamid The SteamID of the user 
     * @param string $sGid The GlobalID of the group
     *
     * @return void
     */
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null, $sGid = null) {
        $this->set_key($sApiKey);
        $this->set_game((int) $iGame);
        $this->set_steamid($sSteamid);
        $this->gid = $sGid;
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
     * Get the name of the group
     *
     *
     *
     * @return string
     */
    public function GetGroupName() {
        $fgcGetGroupName = file_get_contents("https://steamcommunity.com/gid/".$this->gid."/memberslistxml?xml=1");
        $oGetGroupName = simplexml_load_string($fgcGetGroupName, null, LIBXML_NOCDATA);

        return current($oGetGroupName->groupDetails->groupName);
    }

    /**
     * Get the Custom URL of the group
     *
     *
     *
     * @return string
     */
    public function GetGroupURL() {
        $fgcGetGroupName = file_get_contents("https://steamcommunity.com/gid/".$this->gid."/memberslistxml?xml=1");
        $oGetGroupName = simplexml_load_string($fgcGetGroupName, null, LIBXML_NOCDATA);

        return current($oGetGroupName->groupDetails->groupURL);
    }

    /**
     * Get the Headline of the group
     *
     *
     *
     * @return string
     */
    public function GetGroupHeadline() {
        $fgcGetGroupName = file_get_contents("https://steamcommunity.com/gid/".$this->gid."/memberslistxml?xml=1");
        $oGetGroupName = simplexml_load_string($fgcGetGroupName, null, LIBXML_NOCDATA);

        return current($oGetGroupName->groupDetails->headline);
    }

    /**
     * Get the summary of the group
     *
     *
     *
     * @return string
     */
    public function GetGroupSummary() {
        $fgcGetGroupName = file_get_contents("https://steamcommunity.com/gid/".$this->gid."/memberslistxml?xml=1");
        $oGetGroupName = simplexml_load_string($fgcGetGroupName, null, LIBXML_NOCDATA);

        return current($oGetGroupName->groupDetails->summary);
    }

    /**
     * Get the group id 64 of the group
     *
     *
     *
     * @return string
     */
    public function GetGroupID64() {
        $fgcGetGroupName = file_get_contents("https://steamcommunity.com/gid/".$this->gid."/memberslistxml?xml=1");
        $oGetGroupName = simplexml_load_string($fgcGetGroupName, null, LIBXML_NOCDATA);

        return current($oGetGroupName->groupID64);
    }

    /**
     * Get the avatars of the group
     *
     *
     *
     * @return object
     */
    public function GetGroupAvatar() {
        $fgcGetGroupName = file_get_contents("https://steamcommunity.com/gid/".$this->gid."/memberslistxml?xml=1");
        $oGetGroupName = simplexml_load_string($fgcGetGroupName, null, LIBXML_NOCDATA);

        $oAvatars = new \stdClass();
        
        $oAvatars->icon = current($oGetGroupName->groupDetails->avatarIcon);
        $oAvatars->medium = current($oGetGroupName->groupDetails->avatarMedium);
        $oAvatars->full = current($oGetGroupName->groupDetails->avatarFull);
        
        return $oAvatars;
    }

    /**
     * Get the members of the group
     *
     *
     * 
     * @return player array
     */
    public function GetGroupMembers() {
        $fgcGetGroupName = file_get_contents("https://steamcommunity.com/gid/".$this->gid."/memberslistxml?xml=1");
        $oGetGroupName = simplexml_load_string($fgcGetGroupName, null, LIBXML_NOCDATA);

        $aMembers = array();
        foreach($oGetGroupName->members->steamID64 as $oMember){
            array_push($aMembers, new \justinback\steam\player($this->key, $this->game, current($oMember)));
        }
        
        
        return $aMembers;
    }

    /**
     * Get the member statistics of the group
     *
     *
     *
     * 
     * @return object
     */
    public function GetGroupStats() {
        $fgcGetGroupName = file_get_contents("https://steamcommunity.com/gid/".$this->gid."/memberslistxml?xml=1");
        $oGetGroupName = simplexml_load_string($fgcGetGroupName, null, LIBXML_NOCDATA);

        $oMembers = new \stdClass();
        $aMembers = array();
        
        $oMembers->Total = current($oGetGroupName->groupDetails->memberCount);
        $oMembers->InChat = current($oGetGroupName->groupDetails->membersInChat);
        $oMembers->InGame = current($oGetGroupName->groupDetails->membersInGame);
        $oMembers->Online = current($oGetGroupName->groupDetails->membersOnline);
        
        return $oMembers;
    }
}
