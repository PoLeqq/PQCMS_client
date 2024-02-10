<?php

include_once(dirname(__DIR__,2)."/utils/database/Database.inc.php");
include_once(dirname(__DIR__,2)."/config/data/JSONForms.php");

class Form
{
    protected string $id;
    protected string $name;
    protected int $enabled;
    protected array $cols;

    /**
     * @param string $id
     * @param string $name
     * @param array $cols
     */
    public function __construct(string $id, string $name, int $enabled, array $cols)
    {
        $this->id = $id;
        $this->name = $name;
        $this->enabled = $enabled;
        $this->cols = $cols;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isEnabled(): bool
    {
        return $this->enabled !== 0;
    }

    public function getCols(): array
    {
        return $this->cols;
    }

    public function getRows(): ?array
    {
        $conn = Database::getConnection();
        if(is_null($conn))
            return null;

        $stmt = $conn->prepare("SELECT * FROM pqcms_forms WHERE form_id = ? ORDER BY date DESC");
        $stmt->bind_param("s",$this->id);
        $stmt->execute();

        $result = $stmt->get_result();

        $rows = [];
        while($row = $result->fetch_assoc())
            $rows[] = $row;

        $result->close();
        $stmt->close();
        $conn->close();
        return $rows;
    }

    public function addRow(array $data): bool
    {
        $conn = Database::getConnection();
        if(is_null($conn))
            return false;

        $data = json_encode($data,JSON_UNESCAPED_UNICODE);

        date_default_timezone_set("Europe/Warsaw");
        $date = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("INSERT INTO pqcms_forms VALUES (null,?,?,?)");
        $stmt->bind_param("sss",$this->id,$data,$date);
        $stmt->execute();

        $result = $stmt->errno;

        $stmt->close();
        $conn->close();
        return $result === 0;
    }

    public static function getForms(): array
    {
        $jsonForms = new JSONForms();
        return $jsonForms->getForms();
    }

    public static function getForm(string $name): ?Form
    {
        $jsonForms = new JSONForms();
        return $jsonForms->getForm($name);
    }
}