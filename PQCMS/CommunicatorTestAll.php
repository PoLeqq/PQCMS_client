<?php

session_start();

require_once("Communicator.inc.php");

?>

<!doctype html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">

    <title>~Świat API~</title>

    <style>
        body {
            margin: 0;
        }

        .communicator-response {
            background-color: #d3d3d3;
            margin: 50px 0;
            padding: 20px 50px;
        }

        .communicator-response > pre {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Witaj w świecie API!</h1> <i>jakkolwiek to brzmi...</i>
<?php

printCommunicate("VERIFY_LICENSE",null,CommunicateURL::VERIFY_LICENSE);
//    printCommunicate("GET_SERVER_VERSION",null,Communicator::communicate(CommunicateURL::GET_SERVER_VERSION));

printCommunicate("LOGIN_USER",["username" => "userr123", "password" => "userr123"],CommunicateURL::LOGIN_USER);

printCommunicate("GET_SERVER_VERSION",["complex" => true],CommunicateURL::GET_SERVER_VERSION);

printCommunicate("GET_CLIENT_VERSION",null,CommunicateURL::GET_CLIENT_VERSION);

printCommunicate("DOES_ADMIN_EXISTS",null,CommunicateURL::DOES_ADMIN_EXISTS);

printCommunicate("IS_VALID_AUTH_KEY",
    ["auth_key" => "8eaa55706751a0f6642d55ac1a37372474729689f34318902769c796d353d4ec736e6f6644be6ee3e5d995af3603c37bb9702e55c2b783bc1c152d2a78e3287d"],
    CommunicateURL::IS_VALID_AUTH_KEY);

printCommunicate("HAS_PERMISSION",
    ["auth_key" => $_SESSION["pqcms-panel-auth_key"], "perms" => ["pqcms.test"]],
    CommunicateURL::HAS_PERMISSION);

printCommunicate("GET_SETTINGS",null,CommunicateURL::GET_SETTINGS);

printCommunicate("LOGOUT_USER",null,CommunicateURL::LOGOUT_USER);



?>
</body>
</html>
<?php

function getCommunicatorValidatorResponse($communicateURL,&$posts): array
{
    if($communicateURL === CommunicateURL::VERIFY_LICENSE)
    {
        require_once("config/data/JSONPQCMS.php");
        $pqcms = new JSONPQCMS();
        $posts = array(
            'domain' => $pqcms->getDomain(),
            'login' => $pqcms->getLogin(),
            'license_key' => $pqcms->getLicenseKey(),
            'generate_secure_key' => $postData["generate_secure_key"] ?? null
        );
    }
    else
    {
        if(!in_array($communicateURL,CommunicateURL::getUnrequiredLoginSession()))
        {
            @session_start();
            if(empty($_SESSION["pqcms-panel-auth_key"]))
                return ["suc" => 0, "desc" => "Akcja niemożliwa do spełnienia. Nie posiadasz aktywnej sesji!"];
            else $posts["auth_key"] = $_SESSION["pqcms-panel-auth_key"];
        }

        $posts["domain"] = $_SERVER["SERVER_NAME"];

        $key = Communicator::communicate(CommunicateURL::VERIFY_LICENSE,["generate_secure_key" => true]);
        if(is_null($key))
            return ["suc" => 0, "desc" => "Nieznany błąd podczas komunikacji z serwerem PQCMS."];
        else if($key["suc"] == 0)
            return["suc" => 0, "desc" => "Błąd podczas generowania klucza zabezpieczającego: ".$key["desc"]];
        $posts["secure_key"] = $key["secure_key"];
    }
    return ["suc" => 1];
}

function printCommunicate(string $name, ?array $posts, string $communicateURL)
{
    $validResp = getCommunicatorValidatorResponse($communicateURL,$posts);

    if($validResp["suc"] == 1)
    {
        $communicatorResp = Communicator::communicate($communicateURL,$posts);
        $communicatorResponse = json_encode($communicatorResp,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
    else
    {
        $communicatorResp = ["auth_key" => "Chyba nigdy się nie wydarzy"];
        $communicatorResponse = json_encode($validResp,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

    if($communicateURL === CommunicateURL::LOGIN_USER)
    {
        if(isset($communicatorResp["auth_key"]))
            $_SESSION["pqcms-panel-auth_key"] = $communicatorResp["auth_key"];
    }


    $postsHTML = "";
    if(!is_null($posts))
    {
        $posts = json_encode($posts, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $postsHTML = "<pre>${posts}</pre>";
    }

    echo<<<HTML
<div class="communicator-response">
    <h2>${name}</h2>
    <h4>Post</h4>
    ${postsHTML}
    <h4>Return</h4>
    <pre>${communicatorResponse}</pre>
</div>
HTML;

}