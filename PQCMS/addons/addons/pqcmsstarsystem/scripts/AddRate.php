<?php

$post = json_decode(file_get_contents("php://input"), true);
if($post === null)
    die(json_encode(["suc" => 0, "desc" => "Invalid POST data"]));


if(!isset($post["rate"]) || !isset($post["email"]) || !isset($post["description"]))
    die(json_encode(["suc" => 0, "desc" => "Invalid POST data (required \"rate\", \"email\" and \"description\").", "post" => $post]));

require_once(dirname(__DIR__)."/database/PQCMSStarSystemDatabase.inc.php");
$dbSystem = new PQCMSStarSystemDatabase();
if(!$dbSystem->saveRate($_SERVER["REMOTE_ADDR"],$post["rate"],$post["email"],$post["description"]))
    die(json_encode(["suc" => 0, "desc" => "Internal error."]));
die(json_encode(["suc" => 1, "desc" => "Rate saved!"]));