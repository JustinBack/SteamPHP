<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam store page info. 
 * Cannot be called manually.
 * See \justinback\steam\game, \justinback\steam\manager
 * 
 * @author Justin Back <jb@justinback.com>
 */
class store {

    /**
     * Steamworks App ID
     *
     */
    public $game = null;

    /**
     * Type of the App: software, game, video, dlc
     *
     */
    public $type = null;

    /**
     * Store name
     *
     */
    public $name = null;

    /**
     * The Age Rating
     *
     */
    public $required_age = null;

    /**
     * Is it free or not
     *
     */
    public $is_free = null;

    /**
     * Detailed Store Description
     *
     */
    public $detailed_description = null;

    /**
     * About the Game
     *
     */
    public $about_the_game = null;

    /**
     * Short store description
     *
     */
    public $short_description = null;

    /**
     * Developer Array
     *
     */
    public $developers = null;

    /**
     * Publisher Array
     *
     */
    public $publishers = null;

    /**
     * DLC Array
     *
     */
    public $dlc = null;

    /**
     * Construction of the variables
     *
     * 
     * @param int $iGame Your Appid
     * @param string $sName Name of the App
     * @param string $sType Type of the App: software, game, video, dlc
     * @param int $iRequiredAge The Age Rating
     * @param bool $bIsFree Free or not?
     * @param string $sDetailedDescription Detailed Store Description
     * @param string $sAboutTheGame About the game
     * @param string $sShortDescription Short Store Description
     * @param array $aDevelopers Array of developers
     * @param array $aPublishers Array of publishers
     * @param array $aDlc Array of dlc appids
     *
     * @return void
     */
    public function __construct($iGame = null, $sName = null, $sType = null, $iRequiredAge = null, $bIsFree = null, $sDetailedDescription = null, $sAboutTheGame = null, $sShortDescription = null, $aDevelopers = null, $aPublishers = null, $aDlc = null) {
        $this->game = $iGame;
        $this->required_age = $iRequiredAge;
        $this->type = $sType;
        $this->is_free = $bIsFree;
        $this->detailed_description = strip_tags(str_replace("<br>", "\n", $sDetailedDescription));
        $this->about_the_game = strip_tags(str_replace("<br>", "\n", $sAboutTheGame));
        $this->short_description = strip_tags(str_replace("<br>", "\n", $sShortDescription));
        $this->developers = $aDevelopers;
        $this->publishers = $aPublishers;
        $this->name = $sName;
        $this->dlc = $aDlc;
    }

}
