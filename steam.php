<?php

/**
 * Autoloading Stuff
 * 
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;
/**
 * Steam class, nothing else
 *
 * @author Justin Back <jb@justinback.com>
 */
class steam
{
    
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
    public static function bootstrap()
    {
        spl_autoload_register(function ($class) {
            $file = __DIR__."/class/".substr($class, 17).".php";
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}
steam::bootstrap();