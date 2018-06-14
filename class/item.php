<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Inventory managing
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
    * Construction of the variables
    *
    * 
    * @param string $apikey Steamworks Developer API Key
    * @param string $game Your Appid
    * @param string $steamid The SteamID of the user
    * @param string $itemid The Item ID
    * @param int $quantity Item Quantity
    * @param string $itemdefid Item Definition
    * @param string $acquired Timestamp of item creation date
    * @param string $state Item State
    * @param string $origin Item Origin, e.g external
    * @param string $state_changed_timestamp Timestamp since latest change
    *
    * @return void
    */
    public function __construct($apikey = null, $game = null, $steamid = null, $itemid = null, $quantity = null, $itemdefid = null, $acquired = null, $state = null, $origin = null, $state_changed_timestamp = null)
    {
        $this->set_key($apikey);
        $this->set_game((int)$game);
        $this->set_steamid($steamid);
        $this->set_itemid($itemid);
        $this->set_quantity($quantity);
        $this->set_itemdefid($itemdefid);
        $this->set_acquired($acquired);
        $this->set_state($state);
        $this->set_origin($origin);
        $this->set_state_changed_timestamp($state_changed_timestamp);
    }
    
    /**
    * Setting API Key from the construct
    *
    *
    * @param string $apikey Steamworks Developer API Key
    *
    * @return void
    */
    private function set_key($apikey)
    {
        $this->key = $apikey;
    }
    
    
    /**
    * Setting AppID from the construct
    *
    *
    * @param string $game Your AppID
    *
    * @return void
    */
    private function set_game($game)
    {
        $this->game = $game;
    }
    
    
    /**
    * Setting SteamID from the construct
    *
    *
    * @param string $steamid The Players SteamID
    *
    * @return void
    */
    private function set_steamid($steamid)
    {
        $this->steamid = $steamid;
    }
    
    
    /**
    * Setting ItemID from the construct
    *
    *
    * @param string $itemid The Item ID
    *
    * @return void
    */
    private function set_itemid($itemid)
    {
        $this->itemid = $itemid;
    }
    
    
    /**
    * Setting Quantity from the construct
    *
    *
    * @param string $quantity The Item Quantity
    *
    * @return void
    */
    private function set_quantity($quantity)
    {
        $this->quantity = $quantity;
    }
    
    
    /**
    * Setting ItemDefID from the construct
    *
    *
    * @param string $itemdefid The Item Defintion ID
    *
    * @return void
    */
    private function set_itemdefid($itemdefid)
    {
        $this->itemdefid = $itemdefid;
    }
    
    
    /**
    * Setting acquired from the construct
    *
    *
    * @param string $acquired The creation date of the item 
    *   
    * @return void
    */
    private function set_acquired($acquired)
    {
        $this->acquired = $acquired;
    }
    
    
    /**
    * Setting state from the construct
    *
    *
    * @param string $state The state of the item 
    *   
    * @return void
    */
    private function set_state($state)
    {
        $this->state = $state;
    }
    
    /**
    * Setting origin from the construct
    *
    *
    * @param string $origin The origin of the item 
    *   
    * @return void
    */
    private function set_origin($origin)
    {
        $this->origin = $origin;
    }
    
    
    /**
    * Setting state_changed_timestamp from the construct
    *
    *
    * @param string $state_changed_timestamp The change timestamp of the item 
    *   
    * @return void
    */
    private function set_state_changed_timestamp($state_changed_timestamp)
    {
        $this->state_changed_timestamp = $state_changed_timestamp;
    }
    
    
    /**
    * Marks an item as wholly or partially consumed. This action cannot be reversed.
    *
    * @param string $quantity Quantity of the item
    * @param string $requestid Optional, default 0. Clients may provide a unique identifier for a request to perform at most once execution. When a requestid is resubmitted, it will not cause the work to be performed again; the response message will be the current state of items affected by the original successful execution.
    * 
    * @return bool
    */
    public function ConsumeItem($quantity, $requestid = null){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid, 'itemid' => $this->itemid, 'quantity' => $quantity, 'requestid' => $requestid))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/IInventoryService/ConsumeItem/v1/", false, $context);
        $response = json_decode($req_players);
        return $response;
    }
    
    
    
}
