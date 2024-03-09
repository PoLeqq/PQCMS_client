<?php

class PQCMSStarSystemDatabase
{
    private mysqli $connection;

    public function __construct()
    {
        require_once(dirname(__DIR__,4)."/utils/database/Database.inc.php");
        $this->connection = Database::getConnection();
    }

    public function saveRate(string $ip, int $stars, string $email, ?string $desc): bool
    {
        if(!$this->connection->ping())
            return false;

        date_default_timezone_set("Europe/Warsaw");
        $date = date("Y-m-d H:i:s");

        $stmt = $this->connection->prepare("INSERT INTO pqcms_addon_pqcmsstarsystem_rates VALUES (null, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss",$ip, $stars, $email, $desc, $date);
        $stmt->execute();

        if($stmt->errno !== 0)
            return false;

        $stmt->close();

        return true;
    }

    public function getRates(): array|false
    {
        if(!$this->connection->ping())
            return false;

        $rows = [];

        $query = $this->connection->query("SELECT ip, stars, email, description, date FROM pqcms_addon_pqcmsstarsystem_rates");
        while($row = $query->fetch_assoc())
            $rows[] = $row;
        $query->close();

        return $rows;
    }

    public function close() {
        $this->connection->close();
    }
}