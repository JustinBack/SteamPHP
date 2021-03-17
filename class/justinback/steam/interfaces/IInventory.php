<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface IInventory {

    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null);

    public function AddItem($sItemDefId, $sItemPropsJson = null, $bNotify = false, $sRequestId = null): \justinback\steam\api\item;

    public function AddPromoItem($sItemDefId, $sItemPropsJson = null, $bNotify = false, $sRequestId = null): \justinback\steam\api\item;

    public function Consolidate($aItemDefId, $bForce = false): array;

    public function ModifyItems($sInputJson, $iTimestamp = 0): array;

    public function ExchangeItem($aMaterialsItemId, $aMaterialsQuantity, $sOutputItemDefId): array;

    public function GetInventory(): array;

    public function GetItemDefs(): array;
}
