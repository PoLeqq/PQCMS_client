<?php

require_once(dirname(__DIR__)."/Chart.inc.php");
require_once(dirname(__DIR__,5)."/utils/database/Database.inc.php");

abstract class TrafficLocationChart extends Chart
{
    protected string $visibleName;
    protected string $columnName;
    protected bool $uniqueIds;
    protected ?array $replacements;

    public function __construct(string $visibleName, string $columnName, bool $uniqueIds, array $replacements = null)
    {
        $id = "traffic-location-$columnName-chart";
        if($uniqueIds)
            $id .= "-unique";
        parent::__construct($id);

        $this->visibleName = $visibleName;
        $this->columnName = $columnName;
        $this->uniqueIds = $uniqueIds;
        $this->replacements = $replacements;
    }

    protected function getHTML(): ?string
    {
        $chartId = $this->chartId;
        $name = $this->visibleName;

        $info = "";
        if($this->uniqueIds)
            $info = "Tylko unikalne wejścia IP (liczone na 1 dzień)";

        return <<<HTML
<div class="chartCard col-12 col-md-6 col-lg-4" style="display: inline-flex;">
    <h3>Odwiedziny strony - $name</h3>
    <span style="text-align: center">
        Z ostatnich 30 dni<br/>
        $info    
    </span>
    
    <div class="chartBox">
        <div style="width: 100%;">
            <div id="containerBody-$chartId" style="height: 500px;">
                <canvas id="chart-$chartId"></canvas>
            </div>
        </div>
    </div>
</div>
HTML;
    }

    protected function getJS(): string
    {
        $data = $this->getData();
        if(empty($data))
            return "";

        if(!is_null($this->replacements))
        {
            $labels = array_keys($data);
            for($i=0; $i<sizeof($labels); $i++)
                if(isset($this->replacements[$labels[$i]]))
                    $labels[$i] = $this->replacements[$labels[$i]];
            $labels = json_encode($labels);
        }
        else
            $labels = json_encode(array_keys($data));

        $chartId = $this->chartId;
        $allColors = Chart::getUniqueColors();

        $i=0;
        $values = [];
        $colors = [];
        foreach($data as $label => $value)
        {
            $values[] = $value;
            if($i >= sizeof($allColors))
                $i -= sizeof($allColors);
            $colors[] = $allColors[$i];

            $i++;
        }

        $values = json_encode($values);
        $colors = json_encode($colors);

        return <<<JS
<script>
{
    
    
    // setup
    const data = {
        labels: $labels,
        datasets: [{
            label: 'Wszystkie odwiedziny',
            data: $values,
            backgroundColor: $colors
        }]
    };

    // config
    const config = {
        // type: 'line',
        type: 'doughnut',
        data,
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                }
            },
        },
    };

    // render init block
    const myChart = new Chart(
        document.getElementById('chart-$chartId'),
        config
    );

    const containerBody = document.querySelector("#containerBody-$chartId");
}
</script>
JS;
    }

    protected function getData(): array
    {
        $conn = Database::getConnection();
        $data = $this->getGuestsData($conn);

        $conn->close();

        return $data;
    }

    public function getGuestsData(mysqli $conn): array
    {
        $column = $this->columnName;
        $startDate = date('Y-m-d', strtotime('today - 29 days'));
        $endDate = date('Y-m-d', strtotime('today + 1 day'));

        $count = "ip";
        if($this->uniqueIds)
            $count = "DISTINCT ip";
        $query = $conn->query("SELECT $column, COUNT($count) AS amount FROM pqcms_tabs_counter WHERE 
                                                       date BETWEEN '$startDate' 
                                                           AND '$endDate' 
                                                   GROUP BY $column
                                                   ORDER BY amount DESC
                                                   LIMIT 10");
        $traffic = [];
        while($row = $query->fetch_assoc())
        {
            if(is_null($row[$column]))
                $row[$column] = "(brak danych)";
            else if($row[$column] == "")
                $row[$column] = "(nieznane)";

            $traffic[$row[$column]] = $row["amount"];
        }

        $query->close();

        return $traffic;
    }
}