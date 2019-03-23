<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Authenticate Users and Tickets! SteamPHP handles encryption and randomness of data
 *
 * @author Justin Back <jb@justinback.com>
 */
class userauth {

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
     * Used to access information about users
     *
     * @param string $sEncryptedLoginKey Should be the users hashed loginkey, AES encrypted with the sessionkey.
     * @param string $anySessionKey (optional) Should be a 32 byte random blob of data, which is then encrypted with RSA using the Steam system's public key. Randomness is important here for security.
     * @todo This method is not done yet.
     * 
     * @return bool
     */
    public function AuthenticateUser($sEncryptedLoginKey, $anySessionKey = null) {
        
        throw new \Exception("Method unfinished!");
        exit;
        
        $bCustom = true;
        
        
        if ($anySessionKey == null) {
            $bCustom = false;
        }
        
        // Check for libsodium
        if($bCustom && !extension_loaded("libsodium")){
            throw new \Exception("PHP Extension libsodium is required for a generation of your keys by SteamPHP");
            exit;
        }
        
        $aOptions = array(
            'http' => array(
                "ignore_errors" => true,
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int) $this->game, 'steamid' => $this->steamid, 'sessionkey' => $anySessionKey, 'encrypted_loginkey' => $anyEncryptedLoginKey))
            )
        );
        $cContext = stream_context_create($aOptions);
        $fgcAuthenticateUser = file_get_contents("https://api.steampowered.com/ISteamUserAuth/AuthenticateUser/v1/", false, $cContext);
        $oAuthenticateUser = json_decode($fgcAuthenticateUser);
        
        if(count((array)$oAuthenticateUser) === 0 ){
            throw new \Exception('Invalid parameters, either $sEncryptedLoginKey or $anySessionKey');
            exit;
        }
        
        return true;
        
    }

    /**
     * Used to access information about users
     *
     * @param string $sTicket Convert the ticket from GetAuthSessionTicket from binary to hex into an appropriately sized byte character array and pass the result in as this ticket parameter.
     * 
     * @todo Decode JSON, I have no Idea what the Steam API returns for now. Please decode it manually!
     * @return bool
     */
    public function AuthenticateUserTicket($sTicket) {
        
        
        $bCustom = true;
        
        
        if ($anySessionKey == null) {
            $bCustom = false;
        }
        
        $aOptions = array(
            'http' => array(
                "ignore_errors" => true,
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                //'method' => 'POST',
                //'content' => http_build_query(array('key' => $this->key, 'appid' => (int) $this->game, 'steamid' => $this->steamid, 'sessionkey' => $anySessionKey, 'encrypted_loginkey' => $anyEncryptedLoginKey))
            )
        );
        $cContext = stream_context_create($aOptions);
        $fgcAuthenticateUser = file_get_contents("https://api.steampowered.com/ISteamUserAuth/AuthenticateUserTicket/v1/?key=". $this->key. "&appid=". $this->game. "&ticket=". $sTicket, false, $cContext);
        $oAuthenticateUser = json_decode($fgcAuthenticateUser);
        
        return $fgcAuthenticateUser;
    }


}
