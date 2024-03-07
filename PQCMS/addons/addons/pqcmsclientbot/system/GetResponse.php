<?php

require_once(dirname(__DIR__)."/bot/ClientBot.php");
$bot = new ClientBot();

$post = json_decode(file_get_contents("php://input"), true);
if(empty($post) || !is_string($post))
    die(json_encode([
        "suc" => 0,
        "msg" => $bot->getErrorMessage()
    ],JSON_UNESCAPED_UNICODE));


require_once(dirname(__DIR__)."/bot/response/Response.inc.php");
$response = null;
/** @var Response $resp */
foreach($bot->getResponses() as $resp)
{
    if($resp->getId() === $post)
    {
        $response = $resp;
        break;
    }
}

if(is_null($response))
    die(json_encode([
        "suc" => 0,
        "msg" => $bot->getErrorMessage()
    ],JSON_UNESCAPED_UNICODE));

die(json_encode([
    "suc" => 1,
    "msg" => $response->getRandomBotResponse(),
    "resp" => $response->getUserRespData()
],JSON_UNESCAPED_UNICODE));