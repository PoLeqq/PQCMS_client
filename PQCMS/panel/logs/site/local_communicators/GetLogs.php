<?php

header("Content-Type: application/json");

$post = json_decode(file_get_contents("php://input"), true);
if($post === null)
    die(json_encode(["suc" => 0, "desc" => "Invalid POST data"]));

$posts = [];
if(empty($post["type"]))
    die(json_encode(["suc" => 0, "desc" => "You need to post \"type\"!"]));
else $posts["type"] = $post["type"];
if(isset($post["from"]))
    $posts["from"] = $post["from"];
if(isset($post["amount"]))
    $posts["amount"] = $post["amount"];

require_once(dirname(__DIR__,4)."/Communicator.inc.php");
die(json_encode((Communicator::communicate(CommunicateURL::GET_LOGS,$post)),JSON_UNESCAPED_UNICODE));