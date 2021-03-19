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
class SteamPHP {
    /* Library constants */

    public const STEAM_PHP_VERSION = "2.0.0";
    public const CURL_USER_AGENT = "SteamPHP {{ version }} Library";

    /* Public API constants */
    public const PUBLIC_API_SECURE = true;
    public const PUBLIC_API_ROOT = (self::PUBLIC_API_SECURE ? "https://" : "http://" ) . "api.steampowered.com";

    /* Partner API Constants */
    public const PARTNER_API_SECURE = true;
    public const PARTNER_API_ROOT = (self::PUBLIC_API_SECURE ? "https://" : "http://" ) . "partner.steam-api.com";

    /* Partner API Interfaces */
    public const PARTNER_INTERFACE_BROADCASTSERVICE = "IBroadcastService";
    public const PARTNER_INTERFACE_CHEATREPORTINGSERVICE = "ICheatReportingService";
    public const PARTNER_INTERFACE_ECONMARKETSERVICE = "IEconMarketService";
    public const PARTNER_INTERFACE_GAMEINVENTORY = "IGameInventory";
    public const PARTNER_INTERFACE_GAMESERVERSSERVICE = "IGameServersService";
    public const PARTNER_INTERFACE_INVENTORYSERVICE = "IInventoryService";
    public const PARTNER_INTERFACE_LOBBYMATCHMAKINGSERVICE = "ILobbyMatchmakingService";
    public const PARTNER_INTERFACE_STEAMAPPS = "ISteamApps";
    public const PARTNER_INTERFACE_STEAMCOMMUNITY = "ISteamCommunity";
    public const PARTNER_INTERFACE_STEAMECONOMY = "ISteamEconomy";
    public const PARTNER_INTERFACE_STEAMGAMESERVERSTATSs = "ISteamGameServerStats";
    public const PARTNER_INTERFACE_STEAMLEADERBOARDS = "ISteamLeaderboards";
    public const PARTNER_INTERFACE_STEAMMICROTXN = "ISteamMicroTxn";
    public const PARTNER_INTERFACE_STEAMMICROTXNSANDBOX = "ISteamMicroTxnSandbox";
    public const PARTNER_INTERFACE_STEAMPUBLISHEDITEMSEARCH = "ISteamPublishedItemSearch";
    public const PARTNER_INTERFACE_STEAMPUBLISHEDITEMVOTING = "ISteamPublishedItemVoting";
    public const PARTNER_INTERFACE_STEAMREMOTESTORAGE = "ISteamRemoteStorage";
    public const PARTNER_INTERFACE_STEAMUSERAUTH = "ISteamUserAuth";
    public const PARTNER_INTERFACE_STEAMUSER = "ISteamUser";
    public const PARTNER_INTERFACE_WORKSHOPSERVICE = "IWorkshopService";

    /* Public API Interfaces */
    public const PUBLIC_INTERFACE_CLOUDSERVICE = "ICloudService";
    public const PUBLIC_INTERFACE_ECONSERVICE = "IEconService";
    public const PUBLIC_INTERFACE_GAMENOTIFICATIONSSERVICE = "IGameNotificationsService";
    public const PUBLIC_INTERFACE_PLAYERSERVICE = "IPlayerService";
    public const PUBLIC_INTERFACE_PUBLISHEDFILESERVICE = "IPublishedFileService";
    public const PUBLIC_INTERFACE_SITELICENSESERVICE = "ISiteLicenseService";
    public const PUBLIC_INTERFACE_STEAMNEWS = "ISteamNews";
    public const PUBLIC_INTERFACE_STEAMUSERSTATS = "ISteamUserStats";
    public const PUBLIC_INTERFACE_STEAMWEBAPIUTIL = "ISteamWebAPIUtil";

    public static function bootstrap() {
        spl_autoload_register(function ($class) {
            $file = __DIR__ . "/../includes/" . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';


            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }

}

SteamPHP::bootstrap();
