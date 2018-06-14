

# SteamPHP


**Why?**

`Most developers struggle with interacting with the WebAPI, this (yet) unfinished library makes everything easier!`

**How?**

`Making web requests especially multiple can be frustrating such as code quality. This library makes it easy with providing one liners. Examples below`

## Examples


**Deleting Leaderboards**

`Long`
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

`Short`
~~~

$steam->game()->leaderboards()->DeleteLeaderboard("lbname");

~~~




**Getting Steam Community Name**

`Long`
~~~

        $req_players = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=abcd&steamids=0000");
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        
        foreach($GetNumberOfCurrentPlayers->response->players as $player){
            echo $player->personaname;
        }

~~~


`Short`
~~~

$steam->player()->GetPersonaName();

~~~




## Source / Documentation


[Docs](https://steamphp.docs.justinback.com)

[Source](https://github.com/JustinBack/SteamPHP)


## Feature Requests / Bug Reports


Feel free to make a [Pull Request](https://github.com/JustinBack/SteamPHP/compare) or [Open an Issue](https://github.com/JustinBack/SteamPHP/issues/new/choose)!