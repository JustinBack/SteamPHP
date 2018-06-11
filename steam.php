<?php

namespace justinback\steam;
/**
 * Autoloading Stuff
 *
 * @author Justin Back <jb@justinback.com>
 */

class steam
{
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