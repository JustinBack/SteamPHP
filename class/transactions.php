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
     * Transaction ID
     *
     */
    public $transid = null;

    /**
     * OrderID of transaction
     *
     */
    public $orderid = null;

    /**
     * The Purchase URL
     */
    public $steamurl = null;

    /**
     * Sandbox or Production environment
     *
     */
    private $interface = null;

    /**
     * Sandbox or Production environment
     *
     */
    private $testing = null;

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
        $this->testing = $bTesting;
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
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @param string $sNextProcessDate Date that next recurring payment should be initiated. Format is YYYYMMDD. Date can only be adjusted forward indicating you want to add time to the subscription. If the date exceeds the end date of the subscription, the end date will be extended.
     * 
     * @return bool TRUE on success otherwise exceptions\SteamRequestException
     */
    public function AdjustAgreement($sNextProcessDate) {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            'nextprocessdate' => $sNextProcessDate,
            "agreementid" => $this->agreementid,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/" . $this->interface . "/AdjustAgreement/v1/");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Agreement ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->response->result == "OK") {
            return true;
        }

        throw new exceptions\SteamRequestException(json_encode($CURLResponse->response->error));
    }

    /**
     * Cancels a recurring billing agreement (subscription).
     *
     *
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @return boolean TRUE on success otherwise exceptions\SteamRequestException
     */
    public function CancelAgreement() {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            "agreementid" => $this->agreementid,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/" . $this->interface . "/CancelAgreement/v1/");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Agreement ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->response->result == "OK") {
            return true;
        }

        throw new exceptions\SteamRequestException(json_encode($CURLResponse->response->error));
    }

    /**
     * Tells Steam to refund a user for a purchase. Refunds can only be made for the full value of the original order.
     *
     *
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @return boolean TRUE on success otherwise exceptions/SteamRequestException
     */
    public function RefundTxn() {

        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            "orderid" => $this->orderid,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/" . $this->interface . "/RefundTxn/v2/");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Order ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->response->result == "OK") {
            return true;
        }

        throw new exceptions\SteamRequestException(json_encode($CURLResponse->response->error));
    }

    /**
     * Completes a purchase that was started by the InitTxn API.
     *
     * This command will capture funds for a transaction and should only be called after the user has authorized the transaction and you have received notification that the authorization was successful. Notification of authorization comes either through the Steam client (your game registers to receive notification) or through the user being redirected back to your web site (return URL specified when you redirect a user's web session to Steam). The usersession value specified in InitTxn determines the notification mechanism.
     * A successful response to this command means payment has been completed and you can safely grant items to the user. In the event of a timeout or some other communication error, use either the QueryTxn or GetReport APIs to get status on the transaction.
     *
     *
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * 
     * @return boolean
     */
    public function FinalizeTxn() {
        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            "orderid" => $this->orderid,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/" . $this->interface . "/FinalizeTxn/v2/");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Order ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->response->result == "OK") {
            return true;
        }

        throw new exceptions\SteamRequestException(json_encode($CURLResponse->response->error));
    }

    /**
     * Query the status of an order that was previously created with InitTxn.
     *
     *
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @return object
     */
    public function QueryTxn() {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            //"steamid" => $this->steamid,
            // Custom Queries below here.
            "transid" => $this->transid,
            "orderid" => $this->orderid,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/" . $this->interface . "/QueryTxn/v2/?" . $CURLParameters);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Order ID, Transaction ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        return $CURLResponse->response->params;
    }

    /**
     * Get detailed information of all recurring billing agreements (subscriptions) for a user.
     *
     *
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @return object
     */
    public function GetUserAgreementInfo() {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
                // Custom Queries below here.
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/" . $this->interface . "/GetUserAgreementInfo/v1/?" . $CURLParameters);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("The Steam ID or another parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        return $CURLResponse->response->params->agreements;
    }

    /**
     * Creates a new purchase. Send the order information along with the Steam ID to seed the transaction on Steam.
     *
     * This command allows you to create a shopping cart of one or more items for a user. The cost and descriptions of these items will be displayed to the user for their approval. The purchase interface can be configured for either the Steam client or a web browser depending on if you are running a purchase in-game or from a web page.
     * Note that certain currencies must be charged in specific increments. For example, Ukrainian Hryvnia (UAH) must be charged in increments of 100. So if you attempt to price an item at 1050 UAH, InitTxn will fail with error result k_EMicroTxnResultInvalidParam
     * If you do not wish to price each transaction in the local user's currency, Steam can convert any purchase to the local user's wallet currency automatically based on current exchange rate. For example, if you pass <b>currency</b> as "USD" and <b>amount</b> as "999", a user in Russia will be charged in Rubles at the current exchange rate for $9.99, which would be about 614.90 pуб as of this writing.
     * 
     * Note: If the parameter $sUserSession is set to "web" append the parameter "returnurl=URL_HERE" to the authorization url!
     * 
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @param integer $iItemCount Number of items in cart.
     * @param string $sLanguage ISO 639-1 language code of the item descriptions.
     * @param string $sCurrency ISO 4217 currency code. See <a href="https://partner.steamgames.com/doc/store/pricing/currencies">Supported Currencies</a> for proper format of each currency.
     * @param integer $iItemID 3rd party ID for item.
     * @param integer $iQuantity Quantity of this item.
     * @param integer $iAmount Total cost (in cents) of item(s). See <a href="https://partner.steamgames.com/doc/store/pricing/currencies">Supported Currencies</a> for proper format of each amount. Note that the amount you pass needs to be in the format that matches the "currency" code you pass.
     * @param string $sDescription Description of item
     * @param string $sUserSession (Optional) Session where user will authorize the transaction. Valid options are "client" or "web". If this parameter is not supplied, the interface will be assumed to be through a currently logged in Steam client session.
     * @param string $sIpAddress (Optional) IP address of user in string format (xxx.xxx.xxx.xxx). Only required if $sUserSession is set to web.
     * @param string $sBillingType (Optional) The recurring billing type. "steam", "game" etc.
     * @param string $sStartDate (Optional) The Non UNIX Timestamp when the recurring payment starts
     * @param string $sEndDate (Optional) The Non UNIX Time Stamp when the recurring payment ends
     * @param string $sPeriod (Optional) The period for recurring billing, e.g "month", "year", "week" and "day"
     * @param integer $iRecurringAmount (Optional) The recurring billing amount in cents see $iAmount
     * @param integer $iFrequency (Optional) The frequency for recurring billing in specified $sPeriod
     * 
     * 
     * @return transactions TRUE on success otherwise exceptions\SteamRequestException
     */
    public function InitTxn($iItemCount, $sLanguage, $sCurrency, $iItemID, $iQuantity, $iAmount, $sDescription, $sUserSession = "client", $sIpAddress = null, $sBillingType = null, $sStartDate = null, $sEndDate = null, $sPeriod = null, $iRecurringAmount = null, $iFrequency = null) {
        $iOrderID = sprintf("%016d", mt_rand(1, str_pad("", 16, "9")));


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            "orderid" => $iOrderID,
            "itemcount" => $iItemCount,
            "language" => $sLanguage,
            "currency" => $sCurrency,
            "itemid[0]" => $iItemID,
            "qty[0]" => $iQuantity,
            "amount[0]" => $iAmount,
            "description[0]" => $sDescription,
            "usersession" => $sUserSession,
            "ipaddress" => $sIpAddress,
            "startdate[0]" => $sStartDate,
            "enddate[0]" => $sEndDate,
            "period[0]" => $sPeriod,
            "frequency[0]" => $iFrequency,
            "recurringamt[0]" => $iRecurringAmount,
            "billingtype[0]" => $sBillingType,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/" . $this->interface . "/InitTxn/v3/");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("A parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }

        if ($CURLResponse->response->result == "OK") {
            $this->transid = $CURLResponse->response->params->transid;
            $this->orderid = $CURLResponse->response->params->orderid;
            $this->steamurl = $CURLResponse->response->params->steamurl;
            return $this;
        }

        throw new exceptions\SteamRequestException(json_encode($CURLResponse->response->error));
    }

    /**
     * Initiate a recurring payment (subscription) for the user.
     * A successful response means that Steam will initiate a billing cycle for the user. It does not mean that the actual billing cycle was completed successfully. Use the GetReport or GetUserAgreementInfo APIs to check actual billing status.
     * 
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     * 
     * @param integer $iAmount Total cost (in cents). This value corresponds to an initial one-time amount to be immediately charged to a user.
     * @param string $sCurrency ISO 4217 currency code. See <a href="https://partner.steamgames.com/doc/store/pricing/currencies">Supported Currencies</a> for proper format of each currency.
     * @return boolean TRUE on success otherwise exceptions\SteamRequestException
     */
    public function ProcessAgreement($iAmount, $sCurrency) {
        $iOrderID = sprintf("%016d", mt_rand(1, str_pad("", 16, "9")));


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            "orderid" => $iOrderID,
            "currency" => $sCurrency,
            "amount" => $iAmount,
            "agreementid" => $this->agreementid,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/" . $this->interface . "/ProcessAgreement/v1/");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("A parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        if ($CURLResponse->response->result == "OK") {
            return true;
        }

        throw new exceptions\SteamRequestException(json_encode($CURLResponse->response->error));
    }

    /**
     * Steam offers transaction reports that can be downloaded for reconciliation purposes. These reports show detailed information about each transaction that affects the settlement of funds into your accounts.
     *
     * 
     * @throws exceptions\SteamRequestException if the servers are down, or the web request failed
     * @throws exceptions\SteamRequestParameterException if the app id is not valid as a parameter
     * @throws exceptions\SteamException if the app id or api key is not valid as a parameter
     *
     * @param string $sTime Report type (One of: "GAMESALES", "STEAMSTORESALES", "SETTLEMENT")
     * @param integer $iMaxResults Maximum number of results to return in report. (Up to 1000)
     * @param string $sType Start time of the report. (RFC 3339 UTC formatted like: 2010-01-01T00:00:00Z)
     * @param boolean $bRawOutput Return new classes with transaction, player and item management or a raw object
     * @return object|transactions|player
     */
    public function GetReport($sTime, $iMaxResults = 1000, $sType = "GAMESALES", $bRawOutput = false) {


        $ch = curl_init();

        $CURLParameters = http_build_query(array(
            // Our default parameters!
            "key" => $this->key,
            "appid" => $this->game,
            // This can vary from request to request, sometimes its steamid or steamids or even an array.
            "steamid" => $this->steamid,
            // Custom Queries below here.
            "time" => $sTime,
            "maxresults" => $iMaxResults,
            "type" => $sType,
        ));

        curl_setopt($ch, CURLOPT_URL, "https://partner.steam-api.com/" . $this->interface . "/GetReport/v4/?" . $CURLParameters);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $CURLParameters);
        $CURLResponse = json_decode(curl_exec($ch));
        $CURLResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        // Error handling improved!

        if ($CURLResponseCode != 200) {
            if ($CURLResponseCode == 400) {
                throw new exceptions\SteamRequestParameterException("A parameter is invalid!");
            }
            if ($CURLResponseCode == 401) {
                throw new exceptions\SteamException("App ID or API Key is invalid.");
            }
            throw new exceptions\SteamRequestException("$CURLResponseCode Request Error.");
        }


        if ($bRawOutput) {
            return $CURLResponse->response->params;
        }
        $Array = array();
        foreach ($CURLResponse->response->params->orders as $Key => $Order) {

            $Object = new \stdClass();

            $Transaction = new \justinback\steam\transactions($this->testing, $this->key, $this->game, $Order->steamid);
            $Transaction->agreementid = $Order->agreementid;
            $Transaction->orderid = $Order->orderid;
            $Transaction->transid = $Order->transid;


            $Object->Player = new \justinback\steam\player($this->key, $this->game, $Order->steamid);
            $Object->Transaction = $Transaction;
            $Object->Items = $Order->items;



            $Array[$Key] = $Object;
        }
        return $Array;
    }

}
