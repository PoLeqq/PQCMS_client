<?php

require_once("Chart.inc.php");
require_once(dirname(__DIR__,4)."/utils/database/Database.inc.php");

class TrafficTabsChart extends Chart
{
    public function __construct()
    {
        parent::__construct("traffictabschart");
    }

    protected function getHTML(): ?string
    {
        $chartId = $this->chartId;
        return <<<HTML
<div class="chartCard">
    <h1>Odwiedziny podstron</h1>
    <div class="chartBox">
        <span style="margin-right: 10px;">O - Odwiedziny</span>
        <span>U - Unikalne</span>
        <div style="width: 100%; overflow-x: scroll;">
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

        $labels = json_encode(array_keys(reset($data)));

        $chartId = $this->chartId;

        $colors = self::getUniqueColors();

        $datasets = "";
        $i=0;
        foreach($data as $label => $values)
        {
            $values = json_encode($values);
            $datasets .= <<<JS
{
    label: '$label',
    data: $values,
    backgroundColor: '$colors[$i]',
    borderColor: '$colors[$i]',
    pointBackgroundColor: '$colors[$i]'
},
JS;
            $i++;
        }

        return <<<JS
<script>
{
    
    
    // setup
    const data = {
        labels: $labels,
        datasets: [
            $datasets
        ]
    };

    // config
    const config = {
        type: 'line',
        data,
        options: {
            maintainAspectRatio: false,
            tension: .3,
            scales: {
                x: {
                    beginAtZero: true,
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                }
            },
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

    const labels = myChart.data.labels.length;
    if(labels > 10) {
        containerBody.style.width = `\${700 + ((labels - 7) * 100)}px`;
    }
}
</script>
JS;
    }

    protected function getData(): array
    {
        $conn = Database::getConnection();

        require_once(dirname(__DIR__,4)."/config/data/JSONSites.php");
        $sites = new JSONSites();

        $data = [];
        foreach($sites->getSites() as $site => $alias)
        {
            $data["$alias - (O)"] = $this->getTabData($conn,$site);
            $data["$alias - (U)"] = $this->getUniqueTabData($conn,$site);
        }

        $conn->close();

        return $data;
    }


    private function getTabData(mysqli $conn, string $tabName): array
    {
        $stmt = $conn->prepare("SELECT COUNT(id) FROM pqcms_tabs_counter WHERE date LIKE ? AND tab_name = ?");

        $traffic = [];
        $date = date('Y-m-d', strtotime('today - 29 days'));
        for($i = 0; $i < 30; $i++)
        {
            $formattedDate = date("d.m", strtotime($date));

            $sqlDate = $date."%";
            $stmt->bind_param("ss", $sqlDate,$tabName);
            $stmt->execute();

            $result = $stmt->get_result();

            $traffic[$formattedDate] = $result->fetch_row()[0];

            $result->close();

            $date = date('Y-m-d', strtotime($date.' +1 day'));
        }
        $stmt->close();

        return $traffic;
    }

    private function getUniqueTabData(mysqli $conn, string $tabName): array
    {
        $stmt = $conn->prepare("SELECT COUNT(DISTINCT(ip)) FROM pqcms_tabs_counter WHERE date LIKE ? AND tab_name = ?");

        $traffic = [];
        $date = date('Y-m-d', strtotime('today - 29 days'));
        for($i = 0; $i < 30; $i++)
        {
            $formattedDate = date("d.m", strtotime($date));

            $sqlDate = $date."%";
            $stmt->bind_param("ss", $sqlDate,$tabName);
            $stmt->execute();

            $result = $stmt->get_result();

            $traffic[$formattedDate] = $result->fetch_row()[0];

            $result->close();

            $date = date('Y-m-d', strtotime($date.' +1 day'));
        }
        $stmt->close();

        return $traffic;
    }
}