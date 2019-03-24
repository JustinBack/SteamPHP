# SteamPHP

<p align="center">
  <img width="256" src="https://cdn.pixelcatproductions.net/f/p/d56e6fb34b1f49a683fbfb25f93f43d0bb297eee95875d524b25bf05ee99c6df43f7432b6a06387ad130071359d64deb938e/JustinReneBack2Black.png" alt="Justin Back Logo"/>
</p>


###### NEW

SteamPHP now supports fully compatible exceptions! Error handling is better than ever. This includes a major rewrite of this library so don't update if you don't handle errors now properly else your application will break!

###### Version

[![VERSION](https://img.shields.io/badge/Version-1.2.1-green.svg)](VERSION.md)

###### Compability

![PHP5.4](https://php-eye.com/badge/justinback/steam-php/php54.svg)
![PHP5.5](https://php-eye.com/badge/justinback/steam-php/php55.svg)
![PHP5.6](https://php-eye.com/badge/justinback/steam-php/php56.svg)
![PHP7.0](https://php-eye.com/badge/justinback/steam-php/php70.svg)


###### Support

You can message me on steam or contact me via email: justinback@support.justinback.com (A ticket will be created and a confirmation mail will be sent)

###### Info


[![GitHub issues](https://img.shields.io/github/issues/JustinBack/SteamPHP.svg)](https://github.com/JustinBack/SteamPHP/issues)

[![GitHub license](https://img.shields.io/github/license/JustinBack/SteamPHP.svg)](https://github.com/JustinBack/SteamPHP/blob/master/LICENSE)

##### FAQ

**Why?**

*Most developers struggle with interacting with the WebAPI, this (yet) unfinished library makes everything easier!*

**How?**

Making web requests especially multiple can be frustrating such as code quality. This library makes it easy with providing one liners. Examples below*

## Installation

### Composer

1. `composer require justinback/steam-php`
2. include `vendor/autoload.php`
3. See Usage

### By Source

1. Download from GitHub
2. include `steam.php`
3. See Usage


## Usage

```php

$steam  = new justinback\steam\manager($sApiKey, $iAppID, $sSteamid);

```

## Examples


#### Deleting Leaderboards

```php
$steam->game()->leaderboards()->DeleteLeaderboard("lbname");

```




#### Getting Steam Community Name

```php

$steam->player()->GetPersonaName();

```




## Documentation


[Docs](https://steamphp.docs.justinback.com)


### Generating

Get apigen

```
./path_to_executeable "generate" "--source" "path_to_source" "--destination" "path_to_source/docs" "--title" "SteamPHP" "--charset" "UTF-8" "--exclude" "index.php" "--access-levels" "public" "--access-levels" "protected" "--php" "--tree" "--deprecated" "--todo" "--template-theme bootstrap"
```

## Feature Requests / Bug Reports


Feel free to make a [Pull Request](https://github.com/JustinBack/SteamPHP/compare) or [Open an Issue](https://github.com/JustinBack/SteamPHP/issues/new/choose)!