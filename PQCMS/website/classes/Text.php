<?php

class Text
{
    private string $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getCode(): ?string
    {
        require_once(dirname(__DIR__,2)."/utils/database/Database.inc.php");
        $conn = Database::getConnection();
        if(is_null($conn))
            return null;
        $query = $conn->query("SELECT value FROM pqcms_site_text WHERE id = $this->id");

        if($query->num_rows == 0) $resp = "";
        else $resp = $query->fetch_row()[0];

        $query->close();
        $conn->close();
        return $resp;
    }

    /**
     * @param string $elementId id elementu (ustawiony dla textarea) (tylko, gdy editable = true)
     * @param bool $editable czy edytowalny
     * @return string wygenerowany kod HTML tekstu
     */
    public function generateHtml(string $elementId, bool $editable): string
    {
        if($editable) return "<textarea id='pqcms-editable-textarea-$elementId' name='$elementId' style='width: 100%' class='pqcms-editable-textarea'>".$this->getCode()."</textarea>";
        return $this->getCode();
    }

    public static function unsafe_getTextByName(string $name): ?Text
    {
        require_once(dirname(__DIR__,2)."/utils/database/Database.inc.php");
        $conn = Database::getConnection();
        if(is_null($conn))
            return null;
        $query = $conn->query("SELECT id FROM pqcms_site_text WHERE name = '$name'");

        if($query->num_rows == 0) $resp = null;
        else $resp = new Text($query->fetch_row()[0]);

        $query->close();
        $conn->close();
        return $resp;
    }
}