<?php

/*
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam manager hub
 *
 * @author Justin Back <jb@justinback.com>
 */
class manager {
    private $key = null;
    private $game = null;
    private $steamid = null;
    
    public function __construct($apikey = null, $game = null, $steamid = null)
    {
        $this->set_key($apikey);
        $this->set_game((int)$game);
        $this->set_steamid($steamid);
        
    }
    
    private function set_key($apikey)
    {
        $this->key = $apikey;
    }
    
    public function set_game($game)
    {
        $this->game = $game;
    }
    
    public function set_steamid($steamid)
    {
        $this->steamid = $steamid;
    }

    
    
    public function player($apikey = null, $game = null, $steamid = null)
    {
        if($apikey === null){
            $apikey = $this->key;
        }
        if($game === null){
            $game = $this->game;
        }
        if($steamid === null){
            $steamid = $this->steamid;
        }
        return new \justinback\steam\player($apikey,$game,$steamid);
    }
    
    public function game($apikey = null, $game = null, $steamid = null)
    {
        if($apikey === null){
            $apikey = $this->key;
        }
        if($game === null){
            $game = $this->game;
        }
        if($steamid === null){
            $steamid = $this->steamid;
        }
        return new \justinback\steam\game($apikey,$game,$steamid);
    }
    
}
