<?php

/**
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\api;

/**
 * Steam Item managing. 
 * Cannot be called manually.
 * See \justinback\steam\inventory
 * 
 * @author Justin Back <jback@pixelcatproductions.net>
 */
class SteamInventoryItem {

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
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null, $sItemId = null, $iQuantity = null, $sItemDefId = null, $sAcquired = null, $sState = null, $sOrigin = null, $sStateChangedTimestamp = null, $aDynamicProps = array()) {
        $this->key = $sApiKey;
        $this->game = (int) $iGame;
        $this->steamid = $sSteamid;
        $this->itemid = $sItemId;
        $this->quantity = $iQuantity;
        $this->itemdefid = $sItemDefId;
        $this->acquired = $sAcquired;
        $this->state = $sState;
        $this->origin = $sOrigin;
        $this->state_changed_timestamp = $sStateChangedTimestamp;
        $this->dynamic_props = $aDynamicProps;
    }

    /**
     * Marks an item as wholly or partially consumed. This action cannot be reversed.
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @param string $iQuantity Quantity of the item
     * @param string $sRequestId Optional, default 0. Clients may provide a unique identifier for a request to perform at most once execution. When a requestid is resubmitted, it will not cause the work to be performed again; the response message will be the current state of items affected by the original successful execution.
     * 
     * @return item
     */
    public function ConsumeItem($iQuantity, $sRequestId = null) {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'itemid' => $this->itemid,
            'quantity' => $iQuantity,
            'requestid' => $sRequestId,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/IInventoryService/ConsumeItem/v1/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("One of the parameters supplied is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        $oConsumeItem = json_decode($CURLResponse->response->item_json);
        foreach ($oConsumeItem as $oResponse) {
            return new \justinback\steam\api\SteamInventoryItem($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp);
        }
        throw new \justinback\steam\exceptions\SteamRequestParameterException("The item or Steam ID supplied is invalid!");
    }

    /**
     * Modify the dynamic properties on items for the given user. This call is rate-limited per user and currently only 100 items can be modified in one call. 
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @param string $sPropertyName Name of the dynamic property
     * @param string $sPropertyValue The value of the property
     * @param bool $bRemoveProperty Remove the property? Default false
     * @param string $sPropertyValueType Type of the property, see Steamworks docs. Default property_value_string
     * @param int $iTimestamp Unix timestamp of the request. An error will be returned if the items have been modified since this request time.
     *
     * 
     * 
     * @return item array
     */
    public function ModifyItem($sPropertyName, $sPropertyValue, $bRemoveProperty = false, $sPropertyValueType = "property_value_string", $iTimestamp = 0) {
        if ($iTimestamp == 0) {
            $iTimestamp = time();
        }
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
                "updates" => array(
                    array(
                        'itemid' => $this->itemid,
                        'property_name' => $sPropertyName,
                        $sPropertyValueType => $sPropertyValue,
                        "remove_property" => $bRemoveProperty
            ))))
        ));

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
                throw new \justinback\steam\exceptions\SteamRequestParameterException("One of the parameters supplied is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new \justinback\steam\exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new \justinback\steam\exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        foreach (json_decode($CURLResponse->response->item_json) as $oResponse) {

            if (isset($oResponse->error)) {
                throw new \justinback\steam\exceptions\SteamRequestParameterException("A supplied itemid is invalid. " . $oResponse->error);
            }
            array_push($aArray, new \justinback\steam\api\SteamInventoryItem($this->key, $this->game, $this->steamid, $oResponse->itemid, $oResponse->quantity, $oResponse->itemdefid, $oResponse->acquired, $oResponse->state, $oResponse->origin, $oResponse->state_changed_timestamp, $oResponse->dynamic_props));
        }

        if (count($aArray) === 0) {
            throw new \justinback\steam\exceptions\SteamException("A supplied itemid is invalid, the user does not have the item in the inventory!");
        }

        return $aArray;
    }

    /**
     * Iteminfo object. 
     *
     * @throws \justinback\steam\exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws \justinback\steam\exceptions\SteamRequestParameterException if a parameter is not valid
     * @throws \justinback\steam\exceptions\SteamException if the app id or api key is not valid as a parameter
     *  
     * @return iteminfo
     */
    public function iteminfo() {
        $Inventory = new inventory($this->key, $this->game, $this->steamid);


        $array = $Inventory->GetItemDefs();
        $iteminfo = array_filter($array, function($oItem) {
            return $oItem->itemdefid == $this->itemdefid;
        });
        return current($iteminfo);
    }

}
