<?php

/**
 * Copyright (c) 2021, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam\interfaces;

interface ISteamMicrotransactions {

    public function AdjustAgreement($sNextProcessDate): bool;

    public function CancelAgreement(): bool;

    public function FinalizeTxn(): bool;

    public function GetReport($sTime, $iMaxResults = 1000, $sType = "GAMESALES", $bRawOutput = false): object;

    public function GetUserAgreementInfo(): object;

    public function InitTxn($iItemCount, $sLanguage, $sCurrency, $iItemID, $iQuantity, $iAmount, $sDescription, $sUserSession = "client", $sIpAddress = null, $sBillingType = null, $sStartDate = null, $sEndDate = null, $sPeriod = null, $iRecurringAmount = null, $iFrequency = null, $iOrderID = null): \justinback\steam\api\SteamMicrotransactions;

    public function ProcessAgreement($iAmount, $sCurrency): bool;

    public function QueryTxn(): object;

    public function RefundTxn(): bool;
}
