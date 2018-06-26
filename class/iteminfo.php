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
    * @param string $itemobject To make it easier, the object will be passed so parameters wont be flooded.
    *
    * @return void
    */
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null, $itemobject = null)
    {
        $this->set_key($sApiKey);
        $this->set_game((int)$iGame);
        $this->set_steamid($sSteamid);
        $this->commodity = $itemobject->commodity;
        $this->date_created = $itemobject->date_created;
        $this->description = $itemobject->description;
        $this->display_type = $itemobject->display_type;
        $this->drop_interval = $itemobject->drop_interval;
        $this->drop_max_per_window = $itemobject->drop_max_per_window;
        $this->hash = $itemobject->hash;
        $this->icon_url = $itemobject->icon_url;
        $this->icon_url_hash = $itemobject->icon_url_hash;
        $this->icon_url_hash_large = $itemobject->icon_url_large_hash;
        $this->icon_url_large = $itemobject->icon_url_large;
        $this->item_quality = $itemobject->item_quality;
        $this->item_slot = $itemobject->item_slot;
        $this->itemdefid = $itemobject->itemdefid;
        $this->marketable = $itemobject->marketable;
        $this->modified = $itemobject->modified;
        $this->name = $itemobject->name;
        $this->name_color = $itemobject->name_color;
        $this->price = $itemobject->price;
        $this->promo = $itemobject->promo;
        $this->quantity = $itemobject->quantity;
        $this->store_hidden = $itemobject->store_hidden;
        $this->tags = explode(";", $itemobject->tags);
        $this->timestamp = $itemobject->Timestamp;
        $this->tradable = $itemobject->tradable;
        $this->type = $itemobject->type;
        $this->workshopid = $itemobject->workshopid;
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
