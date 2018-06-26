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
    * @param string $itemdefid List of the itemdefid's to grant
    * @param string $itempropsjson No description provided.
    * @param bool $notify Optional, default 0. Set to 1 to indicate the user is not in-game and should see a Steam notification.
    * @param string $requestid Optional, default 0. Clients may provide a unique identifier for a request to perform at most once execution. When a requestid is resubmitted, it will not cause the work to be performed again; the response message will be the current state of items affected by the original successful execution.
    * 
    * @return item
    */
    public function AddItem($itemdefid, $itempropsjson = null, $notify = false, $requestid = null){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid, 'itemdefid[0]' => $itemdefid, 'itempropsjson' => $itempropsjson, 'notify' => $notify, 'requestid' => $requestid))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/IInventoryService/AddItem/v1/", false, $context);
        $response1 = json_decode(json_decode($req_players)->response->item_json);

            foreach($response1 as $response){
            return new \justinback\steam\item($this->key, $this->game, $this->steamid, $response->itemid, $response->quantity, $response->itemdefid, $response->acquired, $response->state, $response->origin, $response->state_changed_timestamp);
            }
    }
    
    
    /**
    * Adds a promo item to a user's inventory 
    *
    * @todo NOT TESTED YET
    * @param string $itemdefid List of the itemdefid's to grant
    * @param string $itempropsjson No description provided.
    * @param bool $notify Optional, default 0. Set to 1 to indicate the user is not in-game and should see a Steam notification.
    * @param string $requestid Optional, default 0. Clients may provide a unique identifier for a request to perform at most once execution. When a requestid is resubmitted, it will not cause the work to be performed again; the response message will be the current state of items affected by the original successful execution.
    * 
    * @return item
    */
    public function AddPromoItem($itemdefid, $itempropsjson = null, $notify = false, $requestid = null){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'steamid' => $this->steamid, 'itemdefid[0]' => $itemdefid, 'itempropsjson' => $itempropsjson, 'notify' => $notify, 'requestid' => $requestid))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/IInventoryService/AddPromoItem/v1/", false, $context);
        $response1 = json_decode(json_decode($req_players)->response->item_json);

            foreach($response1 as $response){
            return new \justinback\steam\item($this->key, $this->game, $this->steamid, $response->itemid, $response->quantity, $response->itemdefid, $response->acquired, $response->state, $response->origin, $response->state_changed_timestamp);
            }
    }
    
    
    
    /**
    * GetInventory is used to retrieve a user's inventory 
    *
    * 
    * @return item array
    */
    public function GetInventory(){
        $array = array();
        $req_players = file_get_contents("https://partner.steam-api.com/IInventoryService/GetInventory/v1?key=".$this->key."&steamid=".$this->steamid. "&appid=". $this->game);
        $GetInventory = json_decode($req_players);

        foreach (json_decode($GetInventory->response->item_json) as $response){
            array_push($array, new \justinback\steam\item($this->key, $this->game, $this->steamid, $response->itemid, $response->quantity, $response->itemdefid, $response->acquired, $response->state, $response->origin, $response->state_changed_timestamp));
        }
        return $array;
    }
    
    
    /**
    * GetItemDefs is used to retrieve the itemdefs for a given application. 
    *
    * 
    * @return iteminfo array
    */
    public function GetItemDefs(){
        $array = array();
        $req_players = file_get_contents("https://partner.steam-api.com/IInventoryService/GetItemDefs/v1?key=".$this->key."&steamid=".$this->steamid. "&appid=". $this->game);
        $GetInventory = json_decode($req_players);
        

        foreach (json_decode($GetInventory->response->itemdef_json) as $response){
            array_push($array, new \justinback\steam\iteminfo($this->key, $this->game, $this->steamid, $response));
        }
        return $array;
    }
    
    
}
