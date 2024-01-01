<?php

class Group
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function doesExists(): ?bool
    {
        require_once(dirname(__DIR__,2)."/utils/database/Database.inc.php");
        $conn = Database::getConnection();
        if(is_null($conn))
            return null;
        $query = $conn->query("SELECT id FROM pqcms_site_text_group WHERE id = $this->id");

        if($query->num_rows == 0) $resp = false;
        else $resp = true;

        $query->close();
        $conn->close();
        return $resp;
    }

    public function getName(): ?string
    {
        require_once(dirname(__DIR__,2)."/utils/database/Database.inc.php");
        $conn = Database::getConnection();
        if(is_null($conn))
            return null;
        $query = $conn->query("SELECT name FROM pqcms_site_text_group WHERE id = $this->id");

        if($query->num_rows == 0) $resp = "null";
        else $resp = $query->fetch_row()[0];

        $query->close();
        $conn->close();
        return $resp;
    }

//    /**
//     * @param string $name nazwa tekstu. wymagania: tylko cyfry oraz litery a-z (małe)
//     * @return Text|null
//     */
//    public static function getTextByName(string $name): ?Group
//    {
//        if(!preg_match_all('/[a-z0-9]/', $name))
//            return null;
//
//        require_once(dirname(__DIR__,2)."/utils/database/Database.inc.php");
//        $conn = Database::getConnection();
//        if(is_null($conn))
//            return null;
//        $query = $conn->query("SELECT id FROM pqcms_site_text WHERE name = '$name'");
//
//        if($query->num_rows == 0) $resp = null;
//        else $resp = new Group($query->fetch_row()[0]);
//
//        $query->close();
//        $conn->close();
//        return $resp;
//    }
}