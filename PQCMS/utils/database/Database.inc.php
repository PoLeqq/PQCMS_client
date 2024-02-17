<?php

class Database
{
    /**
     * Tworzy połączenie z bazą danych, zwraca obiekt w formie mysqli (lub null, gdy połączenie nie powiodło się)
     */
    public static function getConnection(): bool|mysqli|null
    {
        require_once(dirname(__DIR__,2) . "/config/data/JSONDatabase.php");
        $db = new JSONDatabase();

        if($db->getHost() === "" && $db->getName() === "" && $db->getUser() === "" && $db->getPassword() === "")
        {
            require_once(dirname(__DIR__,2)."/panel/scripts/notifications/NotificationManager.inc.php");
            NotificationManager::addNewNotification("pqcms-databaseConnectionError","Baza danych","e",
                "Nie można połączyć z bazą danych (dane są puste)! Zmień je w ustawieniach!");
            return null;
        }

        try{
            @$connect = mysqli_connect($db->getHost(), $db->getUser(), $db->getPassword());
            if($connect instanceof mysqli)
                $connect->set_charset("utf8mb4");
        } catch(Exception) {
            require_once(dirname(__DIR__,2)."/panel/scripts/notifications/NotificationManager.inc.php");
            NotificationManager::addNewNotification("pqcms-databaseConnectionError","Baza danych","e",
                "Brak połączenia z bazą danych! Sprawdź poprawność danych w ustawieniach!");
            return null;
        }

        if(!($connect instanceof mysqli) || (mysqli_errno($connect) !== 0))
        {
            require_once(dirname(__DIR__,2)."/panel/scripts/notifications/NotificationManager.inc.php");
            NotificationManager::addNewNotification("pqcms-databaseConnectionError","Baza danych","e",
                "Brak połączenia z bazą danych! Sprawdź poprawność danych w ustawieniach!");
            return null;
        }
        else
        {
            // Stworzenie bazy danych, jeżeli nie istnieje
            $query = 'CREATE DATABASE IF NOT EXISTS ' . $db->getName();
            mysqli_query($connect, $query);

            // Ustawienie bazy danych na poprawną
            mysqli_select_db($connect, $db->getName());
        }

        return $connect;
    }

    /**
     * Funkcja pobiera wszystkie pliki .sql dołączone do utils/database/tables i je wykonuje.
     * @return ?array poprawność wykonania operacji z plików sql
     */
    public static function setupDefaultDatabase($conn): ?array
    {
        if(!($conn instanceof mysqli))
            return null;
        $resp = [];

        $path = __DIR__."/tables/";
        $files = scandir($path);
        foreach ($files as $file) {
            if(str_ends_with($file, '.sql')) {
                $query = $conn->query("SHOW TABLES");
                if($conn->errno !== 0)
                    return null;
                while($row = $query->fetch_row())
                    if($row[0].".sql" == $file) {
                        $resp[$file] = 0;
                        continue 2;
                    }

                $sqlFile = file_get_contents($path . $file);
                if (!$conn->multi_query($sqlFile))
                {
                    $resp[$file] = -1;
                    continue;
                }

                do if($result = $conn->store_result()) $result->free_result();
                while ($conn->next_result());

                $resp[$file] = 1;
            }
        }

        $conn->close();
        return $resp;
    }
}