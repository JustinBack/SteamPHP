<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Item managing. 
 * Cannot be called manually.
 * See \justinback\steam\inventory
 * 
 * @author Justin Back <jb@justinback.com>
 */
class item {
    /**
    * Steamworks API Key
    *
    */
    private $key = null;
    
    /**
    * Steamworks App ID
    *
    */
    public $game = null;
    
    /**
    * SteamID of user
    *
    */
    private $steamid = null;
    
    
    /**
    * ItemID of the acquired item
    *
    */
    public $itemid = null;
    
    
    /**
    * Quantity of the acquired item
    *
    */
    public $quantity = null;
    
    
    /**
    * itemdefid of the acquired item
    *
    */
    public $itemdefid = null;
    
    
    /**
    * date of the acquired item
    *
    */
    public $acquired = null;
    
    
    /**
    * state of the acquired item
    *
    */
    public $state = null;
    
    
    /**
    * origin of the acquired item
    *
    */
    public $origin = null;
    
    
    /**
    * timestamp of latest change of the acquired item
    *
    */
    public $state_changed_timestamp = null;
    
    /**
     * The dynamic properties of the item
     * 
     * 
     */
    public $dynamic_props = null;
    
    
    
    /**
    * Construction of the variables
    *
    * 
    * @param string $sApiKey Steamworks Developer API Key
    * @param string $iGame Your Appid
    * @param string $sSteamid The SteamID of the user
    * @param string $sItemId The Item ID
    * @param int $iQuantity Item Quantity
    * @param string $sItemDefId Item Definition
    * @param string $sAcquired Timestamp of item creation date
    * @param string $sState Item State
    * @param string $sOrigin Item Origin, e.g external
    * @param string $sStateChangedTimestamp Timestamp since latest change
    *
    * @return void
    */
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null, $sItemId = null, $iQuantity = null, $sItemDefId = null, $sAcquired = null, $sState = null, $sOrigin = null, $sStateChangedTimestamp = null, $aDynamicProps = array())
    {
        $this->set_key($sApiKey);
        $this->set_game((int)$iGame);
        $this->set_steamid($sSteamid);
        $this->set_itemid($sItemId);
        $this->set_quantity($iQuantity);
        $this->set_itemdefid($sItemDefId);
        $this->set_acquired($sAcquired);
        $this->set_state($sState);
        $this->set_origin($sOrigin);
        $this->set_state_changed_timestamp($sStateChangedTimestamp);
        $this->set_dynamic_props($aDynamicProps);
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
    * Setting Dynamic Props from the construct
    *
    *
    * @param string $aDynamicProps The Dynamic props as an array
    *
    * @return void
    */
    private function set_dynamic_props($aDynamicProps)
    {
        $this->dynamic_props = $aDynamicProps;
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
    * Setting ItemID from the construct
    *
    *
    * @param string $sItemId The Item ID
    *
    * @return void
    */
    private function set_itemid($sItemId)
    {
        $this->itemid = $sItemId;
    }
    
    
    /**
    * Setting Quantity from the construct
    *
    *
    * @param string $iQuantity The Item Quantity
    *
    * @return void
    */
    private function set_quantity($iQuantity)
    {
        $this->quantity = $iQuantity;
    }
    
    
    /**
    * Setting ItemDefID from the construct
    *
    *
    * @param string $sItemDefId The Item Defintion ID
    *
    * @return void
    */
    private function set_itemdefid($sItemDefId)
    {
        $this->itemdefid = $sItemDefId;
    }
    
    
    /**
    * Setting acquired from the construct
    *
    *
    * @param string $sAcquired The creation date of the item 
    *   
    * @return void
    */
    private function set_acquired($sAcquired)
    {
        $this->acquired = $sAcquired;
    }
    
    
    /**
    * Setting state from the construct
    *
    *
    * @param string $sState The state of the item 
    *   
    * @return void
    */
    private function set_state($sState)
    {
        $this->state = $sState;
    }
    
    /**
    * Setting origin from the construct
    *
    *
    * @param string $sOrigin The origin of the item 
    *   
    * @return void
    */
    private function set_origin($sOrigin)
    {
        $this->origin = $sOrigin;
    }
    
    
    /**
    * Setting state_changed_timestamp from the construct
    *
    *
    * @param string $sStateChangedTimestamp The change timestamp of the item 
    *   
    * @return void
    */
    private function set_state_changed_timestamp($sStateChangedTimestamp)
    {
        $this->state_changed_timestamp = $sStateChangedTimestamp;
    }
    
    
    /**
    * Marks an item as wholly or partially consumed. This action cannot be reversed.
    *
    * @param string $iQuantity Quantity of the item
    * @param string $sRequestId Optional, default 0. Clients may provide a unique identifier for a request to perform at most once execution. When a requestid is resubmitted, it will not cause the work to be performed again; the response message will be the current state of items affected by the original successful execution.
    * 
    * @return item
    */
    public function ConsumeItem($iQuantity, $sRequestId = null){
        $oOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid, 'itemid' => $this->itemid, 'quantity' => $iQuantity, 'requestid' => $sRequestId))
            )
        );
        $cContext  = stream_context_create($oOptions);
        $fgcConsumeItem = file_get_contents("https://partner.steam-api.com/IInventoryService/ConsumeItem/v1/", false, $cContext);
        $oConsumeItem = json_decode(json_decode($fgcConsumeItem)->response->item_json);
        foreach($oConsumeItem as $oResponse){
            return new \justinback\steam\item($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp);
        }
    }
    
    
    /**
    * Iteminfo object. 
    *
    * 
    * @return iteminfo
    */
    public function iteminfo(){
        $array = array();
        $req_players = file_get_contents("https://partner.steam-api.com/IInventoryService/GetItemDefs/v1?key=".$this->key."&steamid=".$this->steamid. "&appid=". $this->game);
        $GetInventory = json_decode($req_players);
        

        foreach (json_decode($GetInventory->response->itemdef_json) as $response){
            array_push($array, new \justinback\steam\iteminfo($this->key, $this->game, $this->steamid, $response));
        }
        $iteminfo = array_filter($array, function($oItem) {
                    return $oItem->itemdefid == $this->itemdefid;
            });
        return $iteminfo;
    }
    
    
}
