<?php

namespace justinback\steam\exceptions;

/**
 * This exception occurs when nothing is returned, e.g empty inventory, friends list, groups etc.
 */

class SteamEmptyException extends \Exception {}