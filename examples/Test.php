<?php

/**
 * @package DoNotDocument
 */
require_once("../includes/justinback/SteamPHP.php");


$Steam = new justinback\steam\api\Achievements();


$ach = $Steam->GetAchievementDetails("123");