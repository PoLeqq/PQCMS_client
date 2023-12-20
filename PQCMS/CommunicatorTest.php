<?php

header("Content-type: application/json");

require_once("Communicator.inc.php");

//$verify = Communicator::communicate(CommunicateURL::VERIFY_LICENSE);
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

//$loginUser = Communicator::communicate(CommunicateURL::LOGIN_USER,["username" => "-", "password" => "admin321"]);
//echo "\$loginUser\n";
//print_r($loginUser);
//echo "\n\n";
//$authKey = $loginUser["auth_key"];

$validAuthKey = Communicator::communicate(CommunicateURL::IS_VALID_AUTH_KEY,["auth_key" => "8eaa55706751a0f6642d55ac1a37372474729689f34318902769c796d353d4ec736e6f6644be6ee3e5d995af3603c37bb9702e55c2b783bc1c152d2a78e3287d"]);
echo "\$validAuthKey\n";
print_r($validAuthKey);
echo "\n\n";
//
$hasPermission = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["auth_key" => $_SESSION["pqcms-panel-auth_key"],"perms" => ["pqcms.test"]]);
echo "\$hasPermission\n";
print_r($hasPermission);
echo "\n\n";
//
//$validAuthKey = Communicator::communicate(CommunicateURL::IS_VALID_AUTH_KEY,["auth_key" => $authKey]);
//echo "\$validAuthKey\n";
//print_r($validAuthKey);
//echo "\n\n";

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

//$settings = Communicator::communicate(CommunicateURL::GET_SETTINGS);
//echo "\$settings\n";
//print_r($settings);
//echo "\n\n";


$updateSettings = Communicator::communicate(CommunicateURL::UPDATE_SETTINGS,["token_lifespan_reset" => 1, "login_session_time_reset" => 1, "login_count_reset" => 1, "auth_key" => "4bf72835bfefa5c5d5d3c6f2a1bf22094b8974152b9b3aec323f276df52de09d6f1b054300524df4891e03f5c215a8815646147d9b54fd999d6346c490636395"]);
echo "\$updateSettings\n";
print_r($updateSettings);
echo "\n\n";




// $addUser = Communicator::communicate(CommunicateURL::ADD_USER,["username" => "user1234567", "nickname" => "user12345", "password" => "admin123", "disabled" => false]);
// echo "\$addUser\n";
// print_r($addUser);

// $getUsers = Communicator::communicate(CommunicateURL::GET_USER,["username" => "user1234567", "nickname" => "user12345", "disabled" => false]);
//$getUsers = Communicator::communicate(CommunicateURL::GET_USER,[]);
//echo "\$getUsers\n";
//print_r($getUsers);


//var_dump(dont_use_outside_communicate("system/version/GetServerVersion.php",[]));

//function dont_use_outside_communicate(string $path, array $postData = []): mixed
//{
//    $postData["domain"] = $_SERVER["SERVER_NAME"];
////    $postData["secure_key"] = "4d98f521fac2f29acabcae8db8aa4eed955fcc70fba6041dd8eaffb88ddc30b7d5611f41307cc1e65978182da99cba16595a242ee32cd715115c17f8031c1f0a";
//
////    $key = Communicator::communicate(CommunicateURL::VERIFY_LICENSE,["generate_secure_key" => true]);
////    if($key["suc"] == 0)
////        return["suc" => 0, "desc" => "Błąd podczas generowania klucza zabezpieczającego: ".$key["desc"]];
////    $postData["secure_key"] = $key["secure_key"];
//
//    $options = array(
//        'http' => array(
//            'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
//                "Referer: https://${_SERVER["SERVER_NAME"]}\r\n",
//            'method'  => 'POST',
//            'content' => http_build_query($postData)
//        )
//    );
//
//    $targetUrl = 'https://poleq.pl/server/api/'.$path;
//
//    $context = stream_context_create($options);
//
//    $response = file_get_contents($targetUrl, false, $context);
//
////        var_dump($response);
//
//    if($response === false) return null;
//    else return json_decode($response,true);
//}