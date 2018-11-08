<?php

session_start();

/**
 * @package DoNotDocument
 * 
 * Here we test the recurring microtransaction system using sessions!
 * 
 */
require_once("steam.php");


$sApiKey = "00000"; // STEAMWORKS WEB API KEY
$iAppID = 000000; // YOUR APP ID
$sSteamid = null; // STEAMID



$steam = new justinback\steam\manager($sApiKey, $iAppID, $sSteamid);

if (isset($_GET["kill"])) {
    unset($_SESSION["SteamPHP_TransactionID_TXNtest"]);
    unset($_SESSION["SteamPHP_OrderID_TXNtest"]);
    unset($_SESSION["SteamPHP_AgreementID_TXNtest"]);
    exit;
}



$Transaction = $steam->transactions(true);

/*
 * Check if the user has a pending purchase here!
 */
if (isset($_SESSION["SteamPHP_TransactionID_TXNtest"])) {


    /*
     * We set the order id and the agreement id / transaction id here from the session!
     */
    $Transaction->transid = $_SESSION["SteamPHP_TransactionID_TXNtest"];
    $Transaction->orderid = $_SESSION["SteamPHP_OrderID_TXNtest"];
    $Transaction->agreementid = $_SESSION["SteamPHP_AgreementID_TXNtest"];


    /*
     * We will check if the Transaction has been approved or not!
     */
    $Order = $Transaction->QueryTxn();
    
    $Transaction->agreementid = $Order->agreementid;
    echo "Agreement ID: " . $Transaction->agreementid . "<br>";

    

    if ($Order->status == "Failed") {
        unset($_SESSION["SteamPHP_TransactionID_TXNtest"]);
        unset($_SESSION["SteamPHP_OrderID_TXNtest"]);
        unset($_SESSION["SteamPHP_AgreementID_TXNtest"]);
        die("Sorry! The user has has aborted the transaction!<br>The Status of the Order is: " . $Order->status);
    }
    if ($Order->status == "Succeeded") {
        unset($_SESSION["SteamPHP_TransactionID_TXNtest"]);
        unset($_SESSION["SteamPHP_OrderID_TXNtest"]);
        unset($_SESSION["SteamPHP_AgreementID_TXNtest"]);
        die("Yay! " . $Order->status);
    }



    if ($Order->status != "Approved") {

        die("Sorry! The user has not authorized the transaction yet!<br>The Status of the Order is: " . $Order->status);
    }

    /*
     *  As all the checks went through, we can now finally process the transaction and let the user pay for it!
     */
    $Order = $Transaction->FinalizeTxn();


    /*
     *  Here we will check if the item has been purchased or not.
     */
    if ($Order) {
        unset($_SESSION["SteamPHP_TransactionID_TXNtest"]);
        unset($_SESSION["SteamPHP_OrderID_TXNtest"]);
        unset($_SESSION["SteamPHP_AgreementID_TXNtest"]);
        echo "Wohoo! Thanks for purchasing this item! You'll find it in the game soon!";


        // Here we will list all additional agreements you've made with this user, stylish!
        print("<pre>".print_r($Transaction->GetUserAgreementInfo(), true)."</pre>");
        
        
        /*
         *  Your own code implementation could be here, however as this is only a test, theres nothing here now.
         */

        exit;
    } else {

        /*
         * An error occurred on valves end, nothing we can do about it!
         */

        echo "Oh no! An error has occurred trying to process your payment!";
    }

    echo "Nothing to do!";
    return;
}


$iItemCount = 1; // We only sell 1 item
$sLanguage = "EN"; // The language is english
$sCurrency = "USD"; // We sell it in US Dollars
$iItemID = 100; // A custom item id, decided by us!
$iQuantity = 3;  // We sell the item 3 times
$iAmount = 500; // We sell the item for $5 USD
$sDescription = "Awesome test item, 3 times!"; // This is the item description, appearing in the client
$sUserSession = "web"; // We want to authorize it using the website.

$sBillingType = "game"; // The billing type
$sStartDate = "20181108";  // The Start Date in format YYYYMMDD
$sEndDate = "20181208"; // The end date in format YYYYMMDD
$sPeriod = "week"; // Weekly purchase
$iRecurringAmount = 500; // for 500 USD
$iFrequency = 2; // Every 2 weeks

/*
 * Here we initialize the transaction!
 */
$Txn = $Transaction->InitTxn($iItemCount, $sLanguage, $sCurrency, $iItemID, $iQuantity, $iAmount, $sDescription, $sUserSession, $_SERVER["REMOTE_ADDR"],$sBillingType, $sStartDate, $sEndDate, $sPeriod, $iRecurringAmount, $iFrequency);
//$Txn = $Transaction->ProcessAgreement("USD", "500");

/*
 * If no error occurred, proceed.
 */
if (!isset($Txn->errorcode)) {

    // Authorizazion URL for web sessions look like this: https://store.steampowered.com/checkout/approvetxn/1663411099664123274/?returnurl=

    /*
     * Set the session for verification
     */
    $_SESSION["SteamPHP_TransactionID_TXNtest"] = $Txn->transid;
    $_SESSION["SteamPHP_OrderID_TXNtest"] = $Txn->orderid;

    echo "Ok great! Authorize the Transaction in the client and refresh the page to see the result of the microtransaction! Everything is saved in a session now. <br>";


    //var_dump("https://store.steampowered.com/checkout/approvetxn/$Transaction->agreementid/?returnurl=" . urlencode("https://google.com/"));
} else {
    /*
     * Something else happened, print the error messages.
     */
    var_dump($Txn);
}