

# SteamPHP

[![VERSION](https://img.shields.io/badge/Version-pb1.0.0b-yellow.svg)](VERSION.md)

![PHP5.4](https://php-eye.com/badge/justinback/steam-php/php54.svg)
![PHP5.5](https://php-eye.com/badge/justinback/steam-php/php55.svg)
![PHP5.6](https://php-eye.com/badge/justinback/steam-php/php56.svg)
![PHP7.0](https://php-eye.com/badge/justinback/steam-php/php70.svg)
![PHP7.1](https://php-eye.com/badge/justinback/steam-php/php71.svg)

[![GitHub issues](https://img.shields.io/github/issues/JustinBack/SteamPHP.svg)](https://github.com/JustinBack/SteamPHP/issues)

[![GitHub license](https://img.shields.io/github/license/JustinBack/SteamPHP.svg)](https://github.com/JustinBack/SteamPHP/blob/master/LICENSE)



**Why?**

`Most developers struggle with interacting with the WebAPI, this (yet) unfinished library makes everything easier!`

**How?**

`Making web requests especially multiple can be frustrating such as code quality. This library makes it easy with providing one liners. Examples below`

## Installation

### Composer

`composer require justinback/steam-php`


### By Source

1. Download from GitHub
2. include `steam.php`
3. See Usage

## Examples


#### Deleting Leaderboards

##### Long

~~~

$options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => 'your key', 'appid' => 'appid', 'name' => 'lbname'))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ISteamLeaderboards/DeleteLeaderboard/v1/", false, $context);
        $response = json_decode($req_players);
        
        if($response->result->result != 1){
         echo "failure";
         return;
        }
        echo"success";

~~~

#### Short
~~~

$steam->game()->leaderboards()->DeleteLeaderboard("lbname");

~~~




#### Getting Steam Community Name

##### Long
~~~

        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=abcd&steamids=0000");
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            echo $player->personaname;
        }

~~~


##### Short
~~~

$steam->player()->GetPersonaName();

~~~




## Documentation


[Docs](https://steamphp.docs.justinback.com)


## Feature Requests / Bug Reports


Feel free to make a [Pull Request](https://github.com/JustinBack/SteamPHP/compare) or [Open an Issue](https://github.com/JustinBack/SteamPHP/issues/new/choose)!