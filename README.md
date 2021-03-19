# SteamPHP

## Notice!

Do not pick the master branch, its unstable and broken as its WIP.

Pick a release instead, those are stable!

###### Compability
![PHP5.x](https://img.shields.io/badge/PHP5.x-Incompatible-red)
![PHP7.0](https://img.shields.io/badge/PHP7.0-Compatible-green)
![PHP7.1](https://img.shields.io/badge/PHP7.1-Compatible-green)
![PHP7.2](https://img.shields.io/badge/PHP7.2-Compatible-green)
![PHP7.3](https://img.shields.io/badge/PHP7.3-Compatible-green)
![PHP7.4](https://img.shields.io/badge/PHP7.4-Compatible-green)
![PHP8.0](https://img.shields.io/badge/PHP8.0-Not%20Tested-yellow)

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

$steam  = new justinback\steam\SteamManager($sApiKey, $iAppID, $sSteamid);

```

## Examples


#### Examples are in the `examples/` directory.




## Documentation


[Docs](https://steamphp.docs.justinback.com)


### Generating

Get apigen

```
./path_to_executeable "generate" "--source" "path_to_source" "--destination" "path_to_source/docs" "--title" "SteamPHP" "--charset" "UTF-8" "--exclude" "index.php" "--access-levels" "public" "--access-levels" "protected" "--php" "--tree" "--deprecated" "--todo" "--template-theme bootstrap"
```

## Feature Requests / Bug Reports


Feel free to make a [Pull Request](https://github.com/JustinBack/SteamPHP/compare) or [Open an Issue](https://github.com/JustinBack/SteamPHP/issues/new/choose)!