<?php

/**
 * Copyright (c) 2019, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Some useful utils for validation. E.g SteamID Check!
 *
 * @author Justin René Back
 */
class utils {

    /**
     * Check if a Steam64ID is valid
     * @param string $sSteamID Steam64ID to check
     * @return boolean TRUE if the SteamID is correct otherwise FALSE
     */
    public function IsValidSteamID(string $sSteamID) {
        if (strlen($sSteamID) == 17) {
            return true;
        }
        return false;
    }

    /**
     * Get the SteamID's in an object (steamID, steamID64, steamID32, steamAccountID)
     *
     * @link https://secure.php.net/manual/en/bc.setup.php Requires BCMath!
     * @see <a href="https://developer.valvesoftware.com/wiki/SteamID">SteamID Valve Wiki</a>
     * 
     * @param string $sSteamID Steam64ID to convert.
     * @return object Returns steamID, steamID64, steamAccountID and steamID32
     */
    public function ConvertSteamIDs($sSteamID) {

        $iUniverse = ($sSteamID >> 56) & 0xFF;
        $iBAccountID = 0;
        $iHAccountID = 0;

        $oSteamIDs = new \stdClass();


        if (((substr($sSteamID, 7) - 7960265728) % 2) == 0) {
            $iHAccountID = ((substr($sSteamID, 7) - 7960265728) / 2);
        } else {
            $iBAccountID = 1;
            $iHAccountID = (((substr($sSteamID, 7) - 7960265728) - 1) / 2);
        }
        $oSteamIDs->steamID = "STEAM_$iUniverse:$iBAccountID:$iHAccountID";
        $oSteamIDs->steamID64 = $sSteamID;
        $oSteamIDs->steamAccountID = (substr($oSteamIDs->steamID64, 7) - 7960265728);
        $oSteamIDs->steamID32 = "[U:1:$oSteamIDs->steamAccountID]";


        return $oSteamIDs;
    }

}
