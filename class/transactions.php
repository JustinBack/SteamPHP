<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam Microtransaction managing
 *
 * @since pb1.0.0a
 * @todo Basically Everything
 * @author Justin Back <jb@justinback.com>
 */
class transactions {

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
     * AgreementID of transaction
     *
     */
    public $agreementid = null;

    /**
     * OrderID of transaction
     *
     */
    public $orderid = null;

    /**
     * Sandbox or Production environment
     *
     */
    private $interface = null;

    /**
     * Construction of the variables steamid, key and game
     *
     * @param string $bTesting Sandbox or Production environment? 
     * @param string $sApiKey Steamworks Developer API Key
     * @param string $iGame Your Appid
     * @param string $sSteamid The SteamID of the user 
     *
     * @return void
     */
    public function __construct($bTesting = false, $sApiKey = null, $iGame = null, $sSteamid = null) {
        $this->set_key($sApiKey);
        $this->set_game((int) $iGame);
        $this->set_steamid($sSteamid);
        if ($bTesting) {
            $this->interface = "ISteamMicroTxnSandbox";
        } else {
            $this->interface = "ISteamMicroTxn";
        }
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
     * Add time to the payment schedule of an agreement with billing type "steam".
     *
     * @param string $sNextProcessDate Date that next recurring payment should be initiated. Format is YYYYMMDD. Date can only be adjusted forward indicating you want to add time to the subscription. If the date exceeds the end date of the subscription, the end date will be extended.
     * 
     * @return object
     */
    public function AdjustAgreement($sNextProcessDate) {
        $aOptions = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int) $this->game, 'nextprocessdate' => $sNextProcessDate, "steamid" => $this->steamid, "agreementid" => $this->agreementid))
            )
        );
        $cContext = stream_context_create($aOptions);
        $fgcAdjustAgreement = file_get_contents("https://partner.steam-api.com/$this->interface/AdjustAgreement/v1/", false, $cContext);
        $oAdjustAgreement = json_decode($fgcAdjustAgreement);


        return $oAdjustAgreement;
    }

    /**
     * Cancels a recurring billing agreement (subscription).
     *
     *
     * 
     * @return object
     */
    public function CancelAgreement() {
        $aOptions = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int) $this->game, "steamid" => $this->steamid, "agreementid" => $this->agreementid))
            )
        );
        $cContext = stream_context_create($aOptions);
        $fgcCancelAgreement = file_get_contents("https://partner.steam-api.com/$this->interface/CancelAgreement/v1/", false, $cContext);
        $oCancelAgreement = json_decode($fgcCancelAgreement);


        return $oCancelAgreement;
    }

    /**
     * Creates a new purchase. Send the order information along with the Steam ID to seed the transaction on Steam.
     *
     * This command allows you to create a shopping cart of one or more items for a user. The cost and descriptions of these items will be displayed to the user for their approval. The purchase interface can be configured for either the Steam client or a web browser depending on if you are running a purchase in-game or from a web page.
     * Note that certain currencies must be charged in specific increments. For example, Ukrainian Hryvnia (UAH) must be charged in increments of 100. So if you attempt to price an item at 1050 UAH, InitTxn will fail with error result k_EMicroTxnResultInvalidParam
     * If you do not wish to price each transaction in the local user's currency, Steam can convert any purchase to the local user's wallet currency automatically based on current exchange rate. For example, if you pass <b>currency</b> as "USD" and <b>amount</b> as "999", a user in Russia will be charged in Rubles at the current exchange rate for $9.99, which would be about 614.90 pуб as of this writing.
     * 
     * 
     * 
     * 
     * @return object
     */
    public function InitTxn() {
        $aOptions = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int) $this->game, "steamid" => $this->steamid, "agreementid" => $this->agreementid))
            )
        );
        $cContext = stream_context_create($aOptions);
        $fgcInitTxn = file_get_contents("https://partner.steam-api.com/$this->interface/InitTxn/v3/", false, $cContext);
        $oInitTxn = json_decode($fgcInitTxn);


        return $oInitTxn;
    }

}
