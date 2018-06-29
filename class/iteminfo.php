<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Item info. 
 * Cannot be called manually.
 * See \justinback\steam\item
 * 
 * @author Justin Back <jb@justinback.com>
 */
class iteminfo {
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
    * Item Definition ID of the item
    *
    */
    public $itemdefid = null;
    
    
    /**
    * Readable Timestamp updated/created?
    *
    */
    public $timestamp = null;
    
    
    /**
    * Last modified time
    *
    */
    public $modified = null;
    
    
    /**
    * creation date of the item
    *
    */
    public $date_created = null;
    
    
    /**
    * Type of the item
    *
    */
    public $type = null;
    
    
    /**
    * Display type of the item
    *
    */
    public $display_type = null;
    
    
    /**
    * The name of the item
    *
    */
    public $name = null;
    
    
    /**
    * The price of the item
    *
    */
    public $price = null;
    
    
    /**
    * The quantity of the item
    *
    */
    public $quantity = null;
    
    
    /**
    * The description of the item
    *
    */
    public $description = null;
    
    
    /**
    * The icon url (small) of the item
    *
    */
    public $icon_url = null;
    
    
    /**
    * The icon url (large) of the item
    *
    */
    public $icon_url_large = null;
    
    
    /**
    * The name color of the item
    *
    */
    public $name_color = null;
    
    
    /**
    * The promotion type of the item
    *
    */
    public $promo = null;
    
    
    /**
    * The tag array of the item
    *
    */
    public $tags = null;
    
    
    /**
    * Is the item tradable?
    *
    */
    public $tradable = null;
    
    
    /**
    * Is the item marketable?
    *
    */
    public $marketable = null;
    
    
    /**
    * Not entirely sure about this one.  Will come back to this later.
    *
    */
    public $commodity = null;
    
    
    /**
    * Is it hidden in the item store?
    *
    */
    public $store_hidden = null;
    
    
    /**
    * The drop rate of the item
    *
    */
    public $drop_interval = null;
    
    
    /**
    * The Maximum drop per session of the item
    *
    */
    public $drop_max_per_window = null;
    
    
    /**
    * Not sure here
    *
    */
    public $workshopid = null;
    
    
    /**
    * The hash of the item
    *
    */
    public $hash = null;
    
    
    /**
    * The quality of the item
    *
    */
    public $item_quality = null;
    
    
    /**
    * The slot of the item
    *
    */
    public $item_slot = null;
    
    
    /**
    * The hash of the item icon (small)
    *
    */
    public $icon_url_hash = null;
    
    
    /**
    * The hash of the item icon (large)
    *
    */
    public $icon_url_hash_large = null;
    
    
    
    /**
    * Construction of the variables
    *
    * 
    * @param string $sApiKey Steamworks Developer API Key
    * @param string $iGame Your Appid
    * @param string $sSteamid The SteamID of the user
    * @param string $oItemobject To make it easier, the object will be passed so parameters wont be flooded.
    *
    * @return void
    */
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null, $oItemobject = null)
    {
        $this->set_key($sApiKey);
        $this->set_game((int)$iGame);
        $this->set_steamid($sSteamid);
        $this->commodity = $oItemobject->commodity;
        $this->date_created = $oItemobject->date_created;
        $this->description = $oItemobject->description;
        $this->display_type = $oItemobject->display_type;
        $this->drop_interval = $oItemobject->drop_interval;
        $this->drop_max_per_window = $oItemobject->drop_max_per_window;
        $this->hash = $oItemobject->hash;
        $this->icon_url = $oItemobject->icon_url;
        $this->icon_url_hash = $oItemobject->icon_url_hash;
        $this->icon_url_hash_large = $oItemobject->icon_url_large_hash;
        $this->icon_url_large = $oItemobject->icon_url_large;
        $this->item_quality = $oItemobject->item_quality;
        $this->item_slot = $oItemobject->item_slot;
        $this->itemdefid = $oItemobject->itemdefid;
        $this->marketable = $oItemobject->marketable;
        $this->modified = $oItemobject->modified;
        $this->name = $oItemobject->name;
        $this->name_color = $oItemobject->name_color;
        $this->price = $oItemobject->price;
        $this->promo = $oItemobject->promo;
        $this->quantity = $oItemobject->quantity;
        $this->store_hidden = $oItemobject->store_hidden;
        $this->tags = explode(";", $oItemobject->tags);
        $this->timestamp = $oItemobject->Timestamp;
        $this->tradable = $oItemobject->tradable;
        $this->type = $oItemobject->type;
        $this->workshopid = $oItemobject->workshopid;
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
    
    
    
}
