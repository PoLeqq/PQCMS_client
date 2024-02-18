<?php

header("Content-type: application/json; charset=utf-8");
require_once(dirname(__DIR__)."/Communicator.inc.php");

$verifyLicense = Communicator::communicate(CommunicateURL::VERIFY_LICENSE);
unset($verifyLicense["secure_key"]);

print_r($verifyLicense);