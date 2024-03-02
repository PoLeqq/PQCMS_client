<?php

@session_start();
if(empty($_SESSION["pqcms"]["panel"]["auth_key"]))
    die(json_encode(["suc" => 0, "desc" => "Najpierw się zaloguj!"]));

require_once(dirname(__DIR__,3)."/Communicator.inc.php");
require_once(dirname(__DIR__,3)."/website/classes/Text.php");
require_once(dirname(__DIR__,2)."/scripts/notifications/NotificationManager.inc.php");
$notificationManager = new NotificationManager("editor-editText","Edytor strony");
$notificationManagerError = new NotificationManager("editor-editTextError","Edytor strony");

// todo useless sprawdzanie permisji tu, niech będzie na serwie już
$perms = ["pqcms.site.specialchars"];
foreach($_POST as $key => $value)
    $perms[] = "pqcms.site.text.set.$key";

$hasPermission = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["perms" => $perms]);
if($hasPermission["suc"] == 0)
{
    $notificationManagerError->addNotification("e","Wystąpił błąd podczas komunikacji z komunikatorem. Błąd: ".$hasPermission["desc"]);
    header("location: ./");
    die("Nieprawidłowe przekierowanie.");
}
else if(!isset($hasPermission["perms"]))
{
    $notificationManagerError->addNotification("e","Wystąpił błąd podczas komunikacji z komunikatorem.".
        "Nie znaleziono pola \"perms\". Skontaktuj się z administratorem PQCMS!");
    header("location: ./");
    die("Nieprawidłowe przekierowanie.");
}

$invalidTexts = [];
$unpermittedTexts = [];
$toChangeTexts = [];

foreach($_POST as $key => $value)
{
    $valid = !is_null(Text::getTextByName($key));

//    $changes[$key]["suc"] = ($valid && $hasPermission);
    $changes[$key]["suc"] = $valid;

    if(!$valid)
        $invalidTexts[] = $key;
    else if(!$hasPermission["perms"]["pqcms.site.text.set.$key"])
        $unpermittedTexts[] = $key;
    else
    {
        if(!$hasPermission["perms"]["pqcms.site.specialchars"])
            $value = htmlspecialchars($value);
        $toChangeTexts[$key] = $value;
    }
}

if(count($invalidTexts) != 0)
{
    $fields = implode(", ", $invalidTexts);
    $fieldsText = (count($invalidTexts) > 1) ? "pól" : "pola";
    $notificationManagerError->addNotification("e","Nie odnaleziono ${fieldsText} (${fields}).".
        "Uważasz że to błąd? Skontaktuj się z administratorem PQCMS!");
}
else if(count($unpermittedTexts) != 0)
{
    $fields = implode(", ", $unpermittedTexts);
    if(count($unpermittedTexts) == 1)
        $notificationManagerError->addNotification("e","Pole (${fields}) nie zostało zmienione, ponieważ nie posiadasz odpowiednich uprawnień.");
    else
        $notificationManagerError->addNotification("e","Pola (${fields}) nie zostały zmienione, ponieważ nie posiadasz odpowiednich uprawnień.");
}

if(count($toChangeTexts) > 0)
{
    require_once(dirname(__DIR__,3)."/utils/database/Database.inc.php");
    $conn = Database::getConnection();

    if(is_null($conn))
    {
        $notificationManagerError->addNotification("e","Błąd połączenia z bazą danych! Nie wprowadzono zmian!");
        header("location: ./");
        die("Nieprawidłowe przekierowanie.");
    }

    $oldText = [];

    $stmtSelect = $conn->prepare("SELECT value FROM pqcms_site_text WHERE name = ?");
    foreach ($toChangeTexts as $textName => $textValue)
    {
        $stmtSelect->bind_param("s",$textName);
        $stmtSelect->execute();

        $result = $stmtSelect->get_result();
        $row = $result->fetch_row();
        if(!(is_null($row) || (is_bool($row) && !$row)))
            $oldText[$textName] = $row[0];
    }
    $stmtSelect->close();

    date_default_timezone_set("Europe/Warsaw");
    $date = date('Y-m-d H:i:s',time());
    $stmtInsert = $conn->prepare("INSERT INTO pqcms_logs VALUES (null, 0, '$date', ?)");

    foreach ($toChangeTexts as $textName => $textValue)
    {
        $logData["user"] = $_SESSION["pqcms"]["panel"]["username"];
        $logData["old_text"] = $oldText[$textName];
        $logData["new_text"] = $textValue;
        $logData = json_encode($logData,JSON_UNESCAPED_UNICODE);
        $stmtInsert->bind_param("s",$logData);
        $stmtInsert->execute();
    }
    $stmtInsert->close();


    $sql = "UPDATE pqcms_site_text SET value = CASE ";
    foreach ($toChangeTexts as $textName)
        $sql .= "WHEN name = ? THEN ? ";
    $sql .= "ELSE value END";
    $stmt = $conn->prepare($sql);

    $paramTypes = str_repeat('ss', count($toChangeTexts));
    $bindParams = [];

    foreach ($toChangeTexts as $textName => $textValue)
    {
        $bindParams[] = $textName;
        $bindParams[] = $textValue;
    }

    $stmt->bind_param($paramTypes, ...$bindParams);
    $result = $stmt->execute();

    if($result === true)
        $notificationManager->addNotification("s","Zmieniono tekst strony!");
    else
        $notificationManagerError->addNotification("e","Niepowodzenie podczas edycji strony!");
}

header("location: ./");
echo "Nieprawidłowe przekierowanie.";