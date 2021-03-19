<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface ISteamInventoryItem {

    public function ConsumeItem($iQuantity, $sRequestId = null): \justinback\steam\api\SteamInventoryItem;

    public function ModifyItem($sPropertyName, $sPropertyValue, $bRemoveProperty = false, $sPropertyValueType = "property_value_string", $iTimestamp = 0): \justinback\steam\api\SteamInventoryItem;

    public function GetItemInfo(): \justinback\steam\api\InventoryItemInfo;
}
