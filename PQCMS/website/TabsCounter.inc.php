<?php

class TabsCounter
{
    private mysqli $conn;

    public function __construct()
    {
        require_once(dirname(__DIR__)."/utils/database/Database.inc.php");
        $this->conn = Database::getConnection();
    }

    public function saveVisit(string $tabName, string $remoteAddr): void
    {
        $stmt = $this->conn->prepare("INSERT INTO pqcms_tabs_counter (id, tab_name, ip, date) VALUES (null, ?, ?, ?)");

        date_default_timezone_set("Europe/Warsaw");
        $date = date('Y-m-d H:i:s',time());

        $stmt->bind_param("sss",$tabName,$remoteAddr,$date);
        $stmt->execute();
        $stmt->close();
    }

    public function close(): void {
        $this->conn->close();
    }
}