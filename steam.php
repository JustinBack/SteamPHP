<?php

/**
 * Autoloading Stuff
 * 
 * Copyright (c) 2018, Justin Back <jback@pixelcatproductions.net>
 * All rights reserved.
 */

namespace justinback;


/**
 * Steam class, nothing else
 *
 * @author Justin Back <jback@pixelcatproductions.net>
 */
class steamphp {

    public const STEAM_PHP_VERSION = "2.0.0";
    
    /**
     * This is my autoloader. 
     * There are many like it, but this one is mine. 
     * My autoloader is my best friend. 
     * It is my life. 
     * I must master it as I must master my life. 
     * My autoloader, without me, is useless. 
     * Without my autoloader, I am useless. 
     * I must use my autoloader true. 
     * I must code better than my enemy who is trying to be better than me.
     * I must be better than him before he is. 
     * And I will be.
     *
     */
    public static function bootstrap() {
        spl_autoload_register(function ($class) {
            $file = __DIR__ . "/class/" . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';


            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }

}

steamphp::bootstrap();
