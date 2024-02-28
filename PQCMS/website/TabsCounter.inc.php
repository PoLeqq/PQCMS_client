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
        $continent = null;
        $country = null;
        $city = null;

        $user_ip = getenv('REMOTE_ADDR');

        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
        if($geo !== false)
        {
            $continent = $geo["geoplugin_continentCode"];
            $country = $geo["geoplugin_countryCode"];
            $city = $geo["geoplugin_city"];
        }

        $stmt = $this->conn->prepare("INSERT INTO pqcms_tabs_counter VALUES (null, ?, ?, ?, ?, ?, ?)");
        echo $this->conn->error;

        date_default_timezone_set("Europe/Warsaw");
        $date = date('Y-m-d H:i:s',time());

        $stmt->bind_param("ssssss",$tabName,$remoteAddr,$date,$continent,$country,$city);
        $stmt->execute();
        $stmt->close();
    }

    public function close(): void {
        $this->conn->close();
    }
}