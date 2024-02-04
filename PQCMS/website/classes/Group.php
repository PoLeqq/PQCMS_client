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
        $stmt = $conn->prepare("SELECT id FROM pqcms_site_text_group WHERE id = ?");
        $stmt->bind_param("i",$this->id);
        $stmt->execute();

        if($stmt->get_result()->num_rows == 0) $resp = false;
        else $resp = true;

        $stmt->close();
        $conn->close();
        return $resp;
    }

    public function getName(): ?string
    {
        require_once(dirname(__DIR__,2)."/utils/database/Database.inc.php");
        $conn = Database::getConnection();
        if(is_null($conn))
            return null;
        $stmt = $conn->prepare("SELECT name FROM pqcms_site_text_group WHERE id = ?");
        $stmt->bind_param("i",$this->id);
        $stmt->execute();

        $result = $stmt->get_result();
        if($result->num_rows == 0) $resp = null;
        else $resp = $result->fetch_row()[0];

        $stmt->close();
        $conn->close();
        return $resp;
    }
}