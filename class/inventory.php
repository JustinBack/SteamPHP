<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Inventory managing. 
 * AddItems etc.
 *
 * @author Justin Back <jback@pixelcatproductions.net>
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
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null) {
        $this->set_key($sApiKey);
        $this->set_game((int) $iGame);
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
     * AddItem is used to add new items directly in a user's inventory. For each itemdef, an instance of that type is created and added to the target account.
     * Items of type 'bundle' or 'generator' are unpacked at the time they are added. The response message provides information about the item or items that were actually inserted in the inventory.
     * This call will fail if the itemdef is not defined or cannot be unpacked; or if the target player does not have permission for the given appid.
     * Player notification of a new item, if any, is best handled by the game client. Calls to AddItem that occur when the player is not in-game may optionally set notify=1 to notify the player via Steam. This may engage any of the player-notification mechanisms in Steam, including an overlay popup.
     * The optional requestid parameter allows a client to make an idempotent call. If the client is unsure whether a request completed successfully on the server, it can replay the request, reusing the original request ID.
     * If the request is replayed, the response will include current state for the items that were affected by the original request, without making new changes.
     * If the original request fails on the server, replaying the same request ID will re-attempt the work rather than reporting the prior error result.
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * 
     * @param string $sItemDefId Item definition ID to grant
     * @param string $sItemPropsJson No description provided. (Valve: Unused!)
     * @param bool $bNotify Optional, default 0. Set to 1 to indicate the user is not in-game and should see a Steam notification.
     * @param string $sRequestId Optional, default 0. Clients may provide a unique identifier for a request to perform at most once execution. When a requestid is resubmitted, it will not cause the work to be performed again; the response message will be the current state of items affected by the original successful execution.
     * 
     * @return item The item object containing info about the item
     */
    public function AddItem($sItemDefId, $sItemPropsJson = null, $bNotify = false, $sRequestId = null) {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'itemdefid[0]' => $sItemDefId,
            'itempropsjson' => $sItemPropsJson,
            'notify' => $bNotify,
            'requestid' => $sRequestId,
        ));
        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/IInventoryService/AddItem/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("One of the parameters supplied is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }
        $oAddItem = json_decode($CURLResponse->response->item_json);

        foreach ($oAddItem as $oResponse) {
            if (isset($oResponse->error)) {
                throw new exceptions\SteamRequestParameterException("A supplied itemdefid is invalid. " . $oResponse->error);
            }

            return new \justinback\steam\item($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp);
        }
        throw new exceptions\SteamRequestParameterException("The Itemdefid is invalid!");
    }

    /**
     * Adds a promo item to a user's inventory 
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @param string $sItemDefId List of the itemdefid's to grant
     * @param string $sItemPropsJson No description provided.
     * @param bool $bNotify Optional, default 0. Set to 1 to indicate the user is not in-game and should see a Steam notification.
     * @param string $sRequestId Optional, default 0. Clients may provide a unique identifier for a request to perform at most once execution. When a requestid is resubmitted, it will not cause the work to be performed again; the response message will be the current state of items affected by the original successful execution.
     * 
     * @return item
     */
    public function AddPromoItem($sItemDefId, $sItemPropsJson = null, $bNotify = false, $sRequestId = null) {
        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'itemdefid[0]' => $sItemDefId,
            'itempropsjson' => $sItemPropsJson,
            'notify' => $bNotify,
            'requestid' => $sRequestId,
        ));
        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/IInventoryService/AddPromoItem/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("One of the parameters supplied is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oAddItem = json_decode($CURLResponse->response->item_json);

        foreach ($oAddItem as $oResponse) {

            if (isset($oResponse->error)) {
                throw new exceptions\SteamRequestParameterException("A supplied itemdefid is invalid. " . $oResponse->error);
            }

            return new \justinback\steam\item($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp);
        }
        throw new exceptions\SteamRequestParameterException("The Itemdefid is invalid!");
    }

    /**
     * Consolidate items of the given type within an user's inventory.
     * Whenever items are combined into a stack, the resulting stack takes the market and trade restriction values of the most-restricted item. The Consolidate action ignores any item with an active market or trade restriction, unless 'force' is set to true. 
     *
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * 
     * @param array $aItemDefId No description provided
     * @param bool $bForce The Consolidate action ignores any item with an active market or trade restriction, unless 'force' is set to true.
     *
     * @return item An array of the stacked items
     */
    public function Consolidate($aItemDefId, $bForce = false) {
        $aArray = array();


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'itemdefid' => $aItemDefId,
            'force' => $bForce,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/IInventoryService/Consolidate/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("One of the parameters supplied is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        foreach (json_decode($CURLResponse->response->item_json) as $oResponse) {

            if (isset($oResponse->error)) {
                throw new exceptions\SteamRequestParameterException("A supplied itemdefid is invalid. " . $oResponse->error);
            }

            array_push($aArray, new \justinback\steam\item($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp));
        }

        if (count($aArray) === 0) {
            throw new exceptions\SteamException("A supplied itemdefid is invalid, the user does not have a non stacked item in the inventory!");
        }

        return $aArray;
    }

    /**
     * Modify the dynamic properties on items for the given user. This call is rate-limited per user and currently only 100 items can be modified in one call. 
     *
     * @param string $sInputJson No description provided
     * @param int $iTimestamp Unix timestamp of the request. An error will be returned if the items have been modified since this request time.
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * Example InputJson
     * <code>
     * array(
     * 		array(
     * 			'itemid' => "1955010841396664607",
     * 			'property_name' => "My_Property_Name",
     * 			'property_value_string' => "Hello test test!"
     * 		)
     * 	)
     * </code>
     * @return item Returns an array of modified items
     */
    public function ModifyItems($sInputJson, $iTimestamp = 0) {
        $aArray = array();


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'input_json' => json_encode(array(
                "steamid" => $this->steamid,
                "timestamp" => $iTimestamp,
                "updates" => $sInputJson
        ))));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/IInventoryService/ModifyItems/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("One of the parameters supplied is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        foreach (json_decode($CURLResponse->response->item_json) as $oResponse) {

            if (isset($oResponse->error)) {
                throw new exceptions\SteamRequestParameterException("A supplied itemid is invalid. " . $oResponse->error);
            }

            array_push($aArray, new \justinback\steam\item($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp, $oResponse->dynamic_props));
        }

        if (count($aArray) === 0) {
            throw new exceptions\SteamException("A supplied itemid is invalid, the user does not have the item in the inventory!");
        }

        return $aArray;
    }

    /**
     * ExchangeItem is used for crafting - converting items using a predefined recipe. A successful exchange destroys the set of items required by the crafting recipe, and adds a new instance of the target itemdef to the player's inventory.
     *
     * The target item definition must have one or more crafting recipes declared in the exchange attribute. Recipes declare the number and type of items required to create the target item. If the set of items provided in the ExchangeItems call does not satisfy any recipe, the call fails and no changes are made to the inventory.
     * 
     * See the Inventory Service Schema documentation for more detail on crafting recipes.
     *
     * The crafting operation will take trade and market restrictions into account; the created item will have the latest trade restriction of any item used to create it.
     *
     * If successful, this call returns an encoded JSON blob that lists the items that were changed by this call - the consumed items and the newly created one.
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @param array $aMaterialsItemId The unique ID an item in the player's inventory to be converted to the target item type
     * @param array $aMaterialsQuantity The quantity of the matching item that should be used in this recipe. This array must be the same length as $aMaterialsItemId.
     * @param string $sOutputItemDefId The ItemDef of the item to be created.
     * 
     * 
     * @return item array
     */
    public function ExchangeItem($aMaterialsItemId, $aMaterialsQuantity, $sOutputItemDefId) {
        $aArray = array();



        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'materialsitemid' => $aMaterialsItemId,
            "materialsquantity" => $aMaterialsQuantity,
            "outputitemdefid" => $sOutputItemDefId,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/IInventoryService/ExchangeItem/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("One of the parameters supplied is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        foreach (json_decode($CURLResponse->response->item_json) as $oResponse) {

            if (isset($oResponse->error)) {
                throw new exceptions\SteamRequestParameterException("A supplied itemid is invalid. " . $oResponse->error);
            }


            array_push($aArray, new \justinback\steam\item($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp, $oResponse->dynamic_props));
        }

        if (count($aArray) === 0) {
            throw new exceptions\SteamException("A supplied itemid is invalid, the user does not have the item in the inventory!");
        }
        return $aArray;
    }

    /**
     * GetInventory is used to retrieve a user's inventory 
     *
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws exceptions\SteamEmptyException if the request returns nothing and the result is empty.
     * 
     * @return item array
     */
    public function GetInventory() {
        $aArray = array();


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/IInventoryService/GetInventory/v1/?". $CURLParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamException("Steam ID is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        foreach (json_decode($CURLResponse->response->item_json) as $oResponse) {



            array_push($aArray, new \justinback\steam\item($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp, $oResponse->dynamic_props));
        }
        
        if (count($aArray) === 0) {
            throw new exceptions\SteamEmptyException("The inventory is empty!");
        }
        
        return $aArray;
    }

    /**
     * GetItemDefs is used to retrieve the itemdefs for a given application. 
     *
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the steam id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * @throws exceptions\SteamEmptyException if the request returns nothing and the result is empty.
     * 
     * @return iteminfo array
     */
    public function GetItemDefs() {
        $aArray = array();
        
        
        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/IInventoryService/GetItemDefs/v1/?". $CURLParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamException("Steam ID is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }
       


        foreach (json_decode($CURLResponse->response->itemdef_json) as $oResponse) {
            array_push($aArray, new \justinback\steam\iteminfo($this->key, $this->game, $this->steamid, $oResponse));
        }
        
        if (count($aArray) === 0) {
            throw new exceptions\SteamEmptyException("No item definitions defined!");
        }
        
        return $aArray;
    }

}
