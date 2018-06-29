<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam User Generated Content (UGC / Workshop) managing
 *
 * @author Justin Back <jb@justinback.com>
 */
class ugc {
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
    public function __construct($sPublishedFileId = null, $sApiKey = null, $iGame = null, $sSteamid = null)
    {
        $this->set_key($sApiKey);
        $this->set_game((int)$iGame);
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
    private function set_key($sApiKey)
    {
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
    private function set_game($iGame)
    {
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
    private function set_steamid($sSteamid)
    {
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
    private function set_fileid($sPublishedFileId)
    {
        $this->fileid = $sPublishedFileId;
    }
    
   
    
    /**
    * Update Ban Status of a UGC
    *
    * @param bool $bBanned Is banned or not
    * @param string $sReason Reason why the item was banned. Only visible to admins.
    * @param string $sPublishedFileId (optional) numeric ID of the target file. 
    * 
    * 
    * @return bool
    */
    public function UpdateBanStatus($bBanned, $sReason, $sPublishedFileId = null){
        if($sPublishedFileId == null){
            $sPublishedFileId = $this->fileid;
        }
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileid' => $sPublishedFileId, 'banned' => $bBanned, 'reason' => $sReason))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcUpdateBanStatus = file_get_contents("https://partner.steam-api.com/IPublishedFileService/UpdateBanStatus/v1/", false, $cContext);
        //$oUpdateBanStatus = json_decode($fgcUpdateBanStatus);
       
        
        return true;
    }
    
    
    /**
    * Updates the incompatible status on the published file. "Incompatible" items are hidden from community hubs and user profiles, but can still be accessed by a direct link.
    *
    *
    * @param bool $bIncompatible Is banned or not
    * @param string $sPublishedFileId (optional) numeric ID of the target file. 
    *
    * 
    * @return bool
    */
    public function UpdateIncompatibleStatus($bIncompatible, $sPublishedFileId = null){
        if($sPublishedFileId == null){
            $sPublishedFileId = $this->fileid;
        }
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileid' => $sPublishedFileId, 'incompatible' => $bIncompatible))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcUpdateIncompatibleStatus = file_get_contents("https://partner.steam-api.com/IPublishedFileService/UpdateIncompatibleStatus/v1/", false, $cContext);
        //$oUpdateIncompatibleStatus = json_decode($fgcUpdateIncompatibleStatus);
       
        
        return true;
    }
    
    
    /**
    * Updates tags on the published file
    *
    *
    * 
    * @param array $aAddTags Tags array("Maps", "Another Tag")
    * @param array $aRemoveTags Tags array("Tag", "Another Tag")
    * @param string $sPublishedFileId (optional) numeric ID of the target file.  
    * 
    * @return bool
    */
    public function UpdateTags($aAddTags, $aRemoveTags, $sPublishedFileId = null){
        if($sPublishedFileId == null){
            $sPublishedFileId = $this->fileid;
        }
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileid' => $sPublishedFileId, 'add_tags' => $aAddTags, 'remove_tags' => $aRemoveTags))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcUpdateTags = file_get_contents("https://partner.steam-api.com/IPublishedFileService/UpdateTags/v1/", false, $cContext);
        //$oUpdateTags = json_decode($fgcUpdateTags);
       
        
        return true;
    }
    
    
    
    /**
    * Get File Info
    *
    *
    * @param string $sPublishedFileIds (optional) numeric ID of the target file. 
    * 
    * @return object
    */
    public function GetPublishedFileDetails($sPublishedFileIds = null){
        if($sPublishedFileIds == null){
            $sPublishedFileIds = $this->fileid;
        }
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileids[0]' => $sPublishedFileIds, "itemcount" => 1))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcGetPublishedFileDetails = file_get_contents("https://partner.steam-api.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/", false, $cContext);
        $oGetPublishedFileDetails = json_decode($fgcGetPublishedFileDetails);
       
        foreach($oGetPublishedFileDetails->response->publishedfiledetails as $oUgc){
         return $oUgc;   
        }
    }
    
    
    /**
    * Subscribe to the published file as the user
    *
    *
    * @param string $sPublishedFileId (optional) numeric ID of the target file.
    * @param string $sSteamid (optional) numeric ID of the user.  
    * 
    * @return bool
    */
    public function SubscribePublishedFile($sPublishedFileId = null, $sSteamid = null){
        if($sPublishedFileId == null){
            $sPublishedFileId = $this->fileid;
        }
        if($sSteamid == null){
            $sSteamid = $this->steamid;
        }
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileid' => $sPublishedFileId, "steamid" => $sSteamid))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcSubscribePublishedFile = file_get_contents("https://partner.steam-api.com/IPublishedFileService/SubscribePublishedFile/v1/", false, $cContext);
        //$oSubscribePublishedFile = json_decode($fgcSubscribePublishedFile);
       
        
        return true;
    }
    
    
    /**
    * Unsubscribe from the published file as the user
    *
    *
    * @param string $sPublishedFileId (optional) numeric ID of the target file.
    * @param string $sSteamid (optional) numeric ID of the user. 
    * 
    * @return bool
    */
    public function UnsubscribePublishedFile($sPublishedFileId = null, $sSteamid = null){
        if($sPublishedFileId == null){
            $sPublishedFileId = $this->fileid;
        }
        if($sSteamid == null){
            $sSteamid = $this->steamid;
        }
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileid' => $sPublishedFileId, "steamid" => $sSteamid))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcUnsubscribePublishedFile = file_get_contents("https://partner.steam-api.com/IPublishedFileService/UnsubscribePublishedFile/v1/", false, $cContext);
        //$oUnsubscribePublishedFile = json_decode($fgcUnsubscribePublishedFile);
       
        
        return true;
    }
    
    
    /**
    * Performs a search query for published files
    *
    * @todo Finish this method. Need response from valve
    * @deprecated
    * @return object
    */
    public function QueryFiles()
    {
        // I have no idea which parameters should be present... Leaving as is for now
        $fgcQueryFiles = file_get_contents("https://api.steampowered.com/IPublishedFileService/QueryFiles/v1?key=".$this->key."&steamid=".$this->steamid);
        $oQueryFiles = json_decode($fgcQueryFiles);
        
        return $oQueryFiles->response;
    }
    
    
    /**
    * Get Creator by UGC
    *
    *
    * @param string $sPublishedFileIds (optional) numeric ID of the target file. 
    * 
    * @return player
    */
    public function player($sPublishedFileIds = null){
        if($sPublishedFileIds == null){
            $sPublishedFileIds = $this->fileid;
        }
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileids[0]' => $sPublishedFileIds, "itemcount" => 1))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcPlayer = file_get_contents("https://partner.steam-api.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/", false, $cContext);
        $oPlayer = json_decode($fgcPlayer);
       
        foreach($oPlayer->response->publishedfiledetails as $oUgc){
         return new \justinback\steam\player($this->key, $this->game, $oUgc->creator);   
        }
    }
    
    
    
}
