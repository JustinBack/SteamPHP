<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Inventory managing. 
 * AddItems etc.
 *
 * @author Justin Back <jb@justinback.com>
 */
class inventory {
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
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null)
    {
        $this->set_key($sApiKey);
        $this->set_game((int)$iGame);
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
    * AddItem is used to add new items directly in a user's inventory. For each itemdef, an instance of that type is created and added to the target account.
    * Items of type 'bundle' or 'generator' are unpacked at the time they are added. The response message provides information about the item or items that were actually inserted in the inventory.
    * This call will fail if the itemdef is not defined or cannot be unpacked; or if the target player does not have permission for the given appid.
    * Player notification of a new item, if any, is best handled by the game client. Calls to AddItem that occur when the player is not in-game may optionally set notify=1 to notify the player via Steam. This may engage any of the player-notification mechanisms in Steam, including an overlay popup.
    * The optional requestid parameter allows a client to make an idempotent call. If the client is unsure whether a request completed successfully on the server, it can replay the request, reusing the original request ID.
    * If the request is replayed, the response will include current state for the items that were affected by the original request, without making new changes.
    * If the original request fails on the server, replaying the same request ID will re-attempt the work rather than reporting the prior error result.
    *
    * @param string $sItemDefId List of the itemdefid's to grant
    * @param string $sItemPropsJson No description provided.
    * @param bool $bNotify Optional, default 0. Set to 1 to indicate the user is not in-game and should see a Steam notification.
    * @param string $sRequestId Optional, default 0. Clients may provide a unique identifier for a request to perform at most once execution. When a requestid is resubmitted, it will not cause the work to be performed again; the response message will be the current state of items affected by the original successful execution.
    * 
    * @return item
    */
    public function AddItem($sItemDefId, $sItemPropsJson = null, $bNotify = false, $sRequestId = null){
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid, 'itemdefid[0]' => $sItemDefId, 'itempropsjson' => $sItemPropsJson, 'notify' => $bNotify, 'requestid' => $sRequestId))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcAddItem = file_get_contents("https://partner.steam-api.com/IInventoryService/AddItem/v1/", false, $cContext);
        $oAddItem = json_decode(json_decode($fgcAddItem)->response->item_json);

            foreach($oAddItem as $oResponse){
            return new \justinback\steam\item($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp);
            }
    }
    
    
    /**
    * Adds a promo item to a user's inventory 
    *
    * @todo NOT TESTED YET
    * @param string $sItemDefId List of the itemdefid's to grant
    * @param string $sItemPropsJson No description provided.
    * @param bool $bNotify Optional, default 0. Set to 1 to indicate the user is not in-game and should see a Steam notification.
    * @param string $sRequestId Optional, default 0. Clients may provide a unique identifier for a request to perform at most once execution. When a requestid is resubmitted, it will not cause the work to be performed again; the response message will be the current state of items affected by the original successful execution.
    * 
    * @return item
    */
    public function AddPromoItem($sItemDefId, $sItemPropsJson = null, $bNotify = false, $sRequestId = null){
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid, 'itemdefid[0]' => $sItemDefId, 'itempropsjson' => $sItemPropsJson, 'notify' => $bNotify, 'requestid' => $sRequestId))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcAddPromoItem = file_get_contents("https://partner.steam-api.com/IInventoryService/AddPromoItem/v1/", false, $cContext);
        $oAddPromoItem = json_decode(json_decode($fgcAddPromoItem)->response->item_json);

            foreach($oAddPromoItem as $oResponse){
            return new \justinback\steam\item($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp);
            }
    }
    
    
    
    /**
    * Modify the dynamic properties on items for the given user. This call is rate-limited per user and currently only 100 items can be modified in one call. 
    *
    * @todo This Method is broken as of 7/13/2018
    * @param string $sInputJson No description provided
    * @param int $iTimestamp Unix timestamp of the request. An error will be returned if the items have been modified since this request time. Must be specified in the input_json parameter.
    * @param message $mUpdates The list of items and properties being modified. Must be specified in the input_json parameter.
    *
    * @return nothing
    */
    public function ModifyItems($sInputJson, $iTimestamp, $mUpdates){
        $aOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid))
            )
        );
        $cContext  = stream_context_create($aOptions);
        $fgcModifyItems = file_get_contents("https://partner.steam-api.com/IInventoryService/ModifyItems/v1/", false, $cContext);
        $oModifyItems = json_decode(json_decode($fgcModifyItems)->response->item_json);
    }
    
    
    /**
    * GetInventory is used to retrieve a user's inventory 
    *
    * 
    * @return item array
    */
    public function GetInventory(){
        $aArray = array();
        $fgcGetInventory = file_get_contents("https://partner.steam-api.com/IInventoryService/GetInventory/v1?key=".$this->key."&steamid=".$this->steamid. "&appid=". $this->game);
        $oGetInventory = json_decode($fgcGetInventory);

        foreach (json_decode($oGetInventory->response->item_json) as $oResponse){
            array_push($aArray, new \justinback\steam\item($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp));
        }
        return $aArray;
    }
    
    
    /**
    * GetItemDefs is used to retrieve the itemdefs for a given application. 
    *
    * 
    * @return iteminfo array
    */
    public function GetItemDefs(){
        $aArray = array();
        $fgcGetItemDefs = file_get_contents("https://partner.steam-api.com/IInventoryService/GetItemDefs/v1?key=".$this->key."&steamid=".$this->steamid. "&appid=". $this->game);
        $oGetInventory = json_decode($fgcGetItemDefs);
        

        foreach (json_decode($oGetInventory->response->itemdef_json) as $oResponse){
            array_push($aArray, new \justinback\steam\iteminfo($this->key, $this->game, $this->steamid, $oResponse));
        }
        return $aArray;
    }
    
    
}
