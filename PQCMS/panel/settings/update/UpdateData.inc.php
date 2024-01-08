<?php

/**
 * Funkcja aktualizująca dane o bazie danych. Jeśli podane argumenty to null, nie zostaną zmienione.
 * @param string|null $host host
 * @param string|null $user użytkownik
 * @param string|null $password hasło
 * @param string|null $name nazwa
 * @return array
 */
function updateDatabase(?string $host, ?string $user, ?string $password, ?string $name): array
{
    if(is_null($host) && is_null($user) && is_null($password) && is_null($name))
        return ["suc" => 0, "desc" => "Nic nie zmieniono. Czy masz odpowiednie uprawnienia?"];

    require_once(dirname(__DIR__, 3) . "/config/data/JSONDatabase.php");
    $database = new JSONDatabase();

//    if($database->getHost() === $host
//        && $database->getUser() === $user
//        && $database->getPassword() === $password
//        && $database->getName() === $name)
//        return ["suc" => 0, "desc" => "Podano takie same dane!"];

    if(!is_null($host))
        $database->setHost($host);
    if(!is_null($user))
        $database->setUser($user);
    if(!is_null($password))
        $database->setPassword($password);
    if(!is_null($name))
        $database->setName($name);
    $database->saveData();

    $error = false;
    try {
        mysqli_connect($database->getHost(),$database->getUser(),$database->getPassword(),$database->getName());
    } catch(Exception) {
        $error = true;
    }

    return ["suc" => 1, "desc" => "Zmieniono dane do bazy danych!", "conn_err" => $error];
}

/**
 * Funkcja aktualizująca dane systemowe (PQCMS). Jeśli podane argumenty to null, nie zostaną zmienione.
 * @param string|null $login użytkownik
 * @param string|null $licenseKey klucz licencyjny
 * @return array
 */
function updatePQCMS(?string $login, ?string $licenseKey): array
{
    if(is_null($login) && is_null($licenseKey))
        return ["suc" => 0, "desc" => "Nic nie zmieniono. Czy masz odpowiednie uprawnienia?"];

    require_once(dirname(__DIR__, 3) . "/config/data/JSONPQCMS.php");
    $pqcms = new JSONPQCMS();

    if($pqcms->getLogin() === $login && $pqcms->getLicenseKey() === $licenseKey)
        return ["suc" => 0, "desc" => "Podano takie same dane!"];

    $warning = "";
    if(!is_null($licenseKey))
        $warning = !$pqcms->setLicenseKey($licenseKey) ? "Błędny format klucza licencyjnego!" : "";
    if(!is_null($login))
        $pqcms->setLogin($login);

    $pqcms->saveData();
    $resp = ["suc" => 1, "desc" => "Zmieniono dane systemowe!"];
    if($warning !== "")
        $resp["warning"] = $warning;

    return $resp;
}

function updateSettings(?int $loginCount, ?bool $resetLoginCount, ?int $loginSessionTime, ?bool $resetLoginSessionTime, ?int $tokenLifespan, ?bool $resetTokenLifespan): array
{
    $posts = [];
    if(!is_null($loginCount) && !is_null($resetLoginCount))
        if($resetLoginCount) $posts["login_count_reset"] = true;
        else $posts["login_count"] = $loginCount;

    if(!is_null($loginSessionTime) && !is_null($resetLoginSessionTime))
        if($resetLoginSessionTime) $posts["login_session_time_reset"] = true;
        else $posts["login_session_time"] = $loginSessionTime;

    if(!is_null($tokenLifespan) && !is_null($resetTokenLifespan))
        if($resetTokenLifespan) $posts["token_lifespan_reset"] = true;
        else $posts["token_lifespan"] = $tokenLifespan;

    require_once(dirname(__DIR__, 3) . "/Communicator.inc.php");
    return Communicator::communicate(CommunicateURL::UPDATE_SETTINGS,$posts);
}