<?php

header("Content-type: application/json; charset=utf-8");

@session_start();
if(empty($_SESSION["pqcms-panel-auth_key"]))
    die(json_encode(["suc" => 0, "desc" => "Najpierw się zaloguj!"],JSON_UNESCAPED_UNICODE));

require_once(dirname(__DIR__,2)."/Communicator.inc.php");
die(json_encode(Communicator::communicate(CommunicateURL::IS_VALID_AUTH_KEY),JSON_UNESCAPED_UNICODE));