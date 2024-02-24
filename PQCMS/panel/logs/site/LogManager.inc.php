<?php

abstract class LogManager
{
    protected string $serverName;
    protected string $displayName;
    protected array $columns;
    protected int $startRows;
    protected int $addRows;

    public function __construct(string $serverName, string $displayName, array $columns, int $startRows, int $addRows)
    {
        $this->serverName = $serverName;
        $this->displayName = $displayName;
        $this->columns = $columns;
        $this->startRows = $startRows;
        $this->addRows = $addRows;
    }

    private function getTableHead(): string
    {
        $tds = "";
        foreach ($this->columns as $dataName => $visualName)
            $tds .= <<<HTML
    <th>$visualName</th>

HTML;

        return <<<HTML
<tr>
    $tds
</tr>
HTML;
    }

    public abstract function loadStartData(): void;

    private function getTableBody(): ?string
    {
        $id = array_search($this->serverName,self::getTypes());
        if($id === false)
            return '<span style="color: red">Nie odnaleziono typu!</span>';

        $rows = [];
        $query = $this->conn->query("SELECT date, data FROM pqcms_logs WHERE type = $id ORDER BY date DESC LIMIT 10");
        if($query->num_rows === 0)
            return null;
        while($row = $query->fetch_assoc())
        {
            $rowData = ["date" => $row["date"]];
            foreach(json_decode($row["data"]) as $index => $value)
                $rowData[$index] = $value;

            $rows[] = $rowData;
        }

        $trs = "";
        foreach ($rows as $row)
        {
            $tr = "<tr>";
            foreach ($this->columns as $colIndex => $colValue)
            {
                if(isset($row[$colIndex]))
                {
                    if($colIndex == "old_text" || $colIndex == "new_text")
                        $row[$colIndex] = "<div class='custom-scroll td-div'><pre>".htmlentities($row[$colIndex])."</pre></div>";

                    $tr .= <<<HTML
    <td>
        ${row[$colIndex]}
    </td>
HTML;
                }
                else
                    $tr .= <<<HTML
    <td></td>

HTML;

            }
            $tr .= "</tr>";

            $trs .= <<<HTML
$tr

HTML;
        }

        return $trs;
    }

    public function generateHtml(): string
    {


        $displayName = $this->displayName;
        $head = $this->getTableHead();
        $body = $this->getTableBody();

        if(is_null($body))
            return <<<HTML
<h3>$displayName</h3>
Brak historii do wyświetlenia.
HTML;


        return <<<HTML
<h3>$displayName</h3>

<div class="table-wrapper">
    <table class="col-12 data-table">
        <thead>
            $head
        </thead>
        <tbody>
            $body
        </tbody>
    </table>
</div>
HTML;

    }

    public static function getTypes(): array
    {
        return [
            "site-text",
            "session",
            "hr",
            "settings"
        ];
    }
}