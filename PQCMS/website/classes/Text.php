<?php

class Text
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getGroup(): ?Group {
        require_once(dirname(__DIR__,2)."/utils/database/Database.inc.php");
        $conn = Database::getConnection();
        if(is_null($conn))
            return null;
        $query = $conn->query("SELECT group_id FROM pqcms_site_text WHERE id = $this->id");

        if($query->num_rows == 0) $resp = null;
        else $resp = (int) $query->fetch_row()[0];

        $query->close();
        $conn->close();

        require_once "Group.php";
        if(!is_null($resp))
            return new Group($resp);
        return null;
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
    public function generateHtml(string $elementId, bool $editable, bool $disabled = false): string
    {
        $disabled = $disabled ? "disabled" : "";
        $code = $this->getCode();
        if($editable) return<<<HTML
<div class="pqcms-editable-div" style="">
    <div style="border: 1px solid black; display:flex; justify-content:center; align-items:center; background-color: rgba(0,0,0,.2); font-weight: bold; border-radius: 3px">
        $elementId
    </div>
    <textarea ${disabled} id='pqcms-editable-textarea-$elementId' data-name='$elementId' style='box-sizing: border-box; width: 100%; margin-bottom: -7px' class='pqcms-editable-textarea'>$code</textarea>
</div>
HTML;

        if(is_null($code))
            return "";
        require_once("PQCode.php");
        $pqCode = new PQCode($code);
        return $pqCode->getHTML();
    }

    /**
     * @param string $name nazwa tekstu. wymagania: tylko cyfry oraz litery a-z (małe)
     * @return Text|null
     */
    public static function getTextByName(string $name): ?Text
    {
        if(!preg_match_all('/[a-z0-9]/', $name))
            return null;

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