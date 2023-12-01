<?php

header("Content-type: application/json");

require_once("Communicator.inc.php");

//$verify = Communicator::communicate(CommunicateURL::VERIFY_LICENSE,[]);
//echo "\$verify\n";
//print_r($verify);
//echo "\n\n";

//$client = Communicator::communicate(CommunicateURL::GET_SERVER_VERSION,[]);
//echo "\$client\n";
//print_r($client);
//
//$client2 = Communicator::communicate(CommunicateURL::GET_SERVER_VERSION,["complex" => true]);
//echo "\$client2\n";
//print_r($client2);

//f2758a8022409234a799b62d16b29790893afce46a41372189706d147e7775005562d32dfde795440618cc36e93b244668ab0e14c7e0ded877061181f79c0a6f
//$client = Communicator::communicate(CommunicateURL::GET_CLIENT_VERSION,"fc3856cf0c53c9d3cd94f1bca15ce6054a482fea3e7dd14a102409221d0f94305589dcc54241931451f3ff44b3a5c70e758a4c002a9bbfd80c5450a51cdc49f2Array",[]);
//$client = Communicator::communicate(CommunicateURL::GET_CLIENT_VERSION,$key["secure_key"],[]);
//echo "\$client\n";
//print_r($client);

//$server = Communicator::communicate(CommunicateURL::GET_SERVER_VERSION,$key["secure_key"],[]);
//echo "\$server\n";
//print_r($server);

//$adminExists = Communicator::communicate(CommunicateURL::DOES_ADMIN_EXISTS,[]);
//echo "\$adminExsits\n";
//print_r($adminExists);

//$loginUser = Communicator::communicate(CommunicateURL::LOGIN_USER,["username" => "admin123", "password" => "admin123"]);
//echo "\$loginUser\n";
//print_r($loginUser);
//echo "\n\n";
//$authKey = $loginUser["auth_key"];
//
//$validAuthKey = Communicator::communicate(CommunicateURL::IS_VALID_AUTH_KEY,["auth_key" => $authKey]);
//echo "\$validAuthKey\n";
//print_r($validAuthKey);
//echo "\n\n";
//
//session_start();
//$logoutUser = Communicator::communicate(CommunicateURL::LOGOUT_USER,["auth_key" => $authKey]);
//$logoutUser = Communicator::communicate(CommunicateURL::LOGOUT_USER,["auth_key" => "946e14463744bf46843123bddbb09c3f7cc43926d11920c11d717aea92e4146db3ab5ad2d91dce9d88077dfb05aabd7b908667f29adfe24f51cbaaaadaa898ed"]);
//echo "\$logoutUser\n";
//print_r($logoutUser);
//echo "\n\n";
//
//$validAuthKey = Communicator::communicate(CommunicateURL::IS_VALID_AUTH_KEY,["auth_key" => "c7fca35652d32355f2b364d3f90ca09a0ad9c40f0dd035c6b36ee0ae3d96cb8d069aeae8b7977e5b1e1604297b59c0eb247c860566a8a19d0406b9eab128a088"]);
//echo "\$validAuthKey\n";
//print_r($validAuthKey);
//echo "\n\n";

//$settings = Communicator::communicate(CommunicateURL::GET_SETTINGS,[]);
//echo "\$settings\n";
//print_r($settings);

// $addUser = Communicator::communicate(CommunicateURL::ADD_USER,["username" => "user1234567", "nickname" => "user12345", "password" => "admin123", "disabled" => false]);
// echo "\$addUser\n";
// print_r($addUser);

// $getUsers = Communicator::communicate(CommunicateURL::GET_USER,["username" => "user1234567", "nickname" => "user12345", "disabled" => false]);
$getUsers = Communicator::communicate(CommunicateURL::GET_USER,[]);
echo "\$getUsers\n";
print_r($getUsers);