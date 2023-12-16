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

//$client = Communicator::communicate(CommunicateURL::GET_CLIENT_VERSION,[]);
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
$validAuthKey = Communicator::communicate(CommunicateURL::IS_VALID_AUTH_KEY,["auth_key" => "b1c457af33c9faa944d9666bddafd0da27d273b6f5ee645b652aa2b308f122dfd0e1b73a34ac1c70fdcd26926fcb623aa4a41396c1d7a13ea3e3476ed0f32e91"]);
echo "\$validAuthKey\n";
print_r($validAuthKey);
echo "\n\n";

$hasPermission = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["auth_key" => "12570c43b2b8281dfff0e51d376cabe1f7f93eaab57b41cc35806f0676afac3c54538a35c08ab5736fd9d8bd50cc82090913ee3dd3d89caa6143833a5ecdf367","perm" => "pqcms.test"]);
echo "\$hasPermission\n";
print_r($hasPermission);
echo "\n\n";
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
//$getUsers = Communicator::communicate(CommunicateURL::GET_USER,[]);
//echo "\$getUsers\n";
//print_r($getUsers);