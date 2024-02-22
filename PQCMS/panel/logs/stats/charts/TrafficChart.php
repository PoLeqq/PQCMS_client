<?php

require_once("Chart.inc.php");
require_once(dirname(__DIR__,4)."/utils/database/Database.inc.php");

class TrafficChart extends Chart
{
    public function __construct()
    {
        parent::__construct("trafficchart");
    }

    protected function getHTML(): ?string
    {
        $chartId = $this->chartId;
        return <<<HTML
<div class="chartCard">
    <h1>Odwiedziny strony</h1>
    <div class="chartBox">
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

        $labels = json_encode(array_keys($data[0]));

        $allGuests = json_encode(array_values($data[0]));
        $uniqueGuests = json_encode(array_values($data[1]));
//        $newGuests = json_encode(array_values($data[2]));

        $chartId = $this->chartId;

        return <<<JS
<script>
{
    // setup
    const data = {
        labels: $labels,
        datasets: [
//            {
//                label: 'Nowi goście',
//                data: $/newGuests,
//                backgroundColor: 'rgba(0,255,0,0.5)',
//                borderColor: 'rgb(0,255,0)',
//                pointBackgroundColor: "rgba(0,255,0,1)"
//            },
            {
                label: 'Unikalnych gości',
                data: $uniqueGuests,
                backgroundColor: 'rgba(255,0,0,0.5)',
                borderColor: 'rgb(255,0,0)',
                pointBackgroundColor: "rgba(255,0,0,1)"
            },
            {
                label: 'Wszystkie odwiedziny',
                data: $allGuests,
                backgroundColor: 'rgba(0,0,255,0.5)',
                borderColor: 'rgb(0,0,255)',
                pointBackgroundColor: "rgba(0,0,255,1)"
            }
        ]
    };

    // config
    const config = {
        type: 'line',
        data,
        options: {
            maintainAspectRatio: false,
            tension: .3,
            fill: true,
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
    if(labels > 7) {
        containerBody.style.width = `\${700 + ((labels - 7) * 100)}px`;
    }
}
</script>
JS;
    }

    protected function getData(): array
    {
        $conn = Database::getConnection();

        $data = [
            $this->getGuestsData($conn),
            $this->getUniqueGuestsData($conn)
//            $this->getNewGuestsData($conn)
        ];
        $conn->close();

        return $data;
    }


    public function getGuestsData(mysqli $conn): array
    {
        $stmt = $conn->prepare("SELECT COUNT(id) FROM pqcms_tabs_counter WHERE date LIKE ?");

        $traffic = [];
        $date = date('Y-m-d', strtotime('today - 29 days'));
        for($i = 0; $i < 30; $i++)
        {
            $formattedDate = date("d.m", strtotime($date));

            $sqlDate = $date."%";
            $stmt->bind_param("s", $sqlDate);
            $stmt->execute();

            $result = $stmt->get_result();

            $traffic[$formattedDate] = $result->fetch_row()[0];

            $result->close();

            $date = date('Y-m-d', strtotime($date.' +1 day'));
        }
        $stmt->close();

        return $traffic;
    }

    public function getNewGuestsData(mysqli $conn): array
    {
        $startDate = date('Y-m-d', strtotime('today - 29 days'));
        $endDate = date('Y-m-d', strtotime('today +1 day'));
//        $stmt = $conn->prepare("WITH RankedIPs AS (
//    SELECT ip, substr(date, 1, 10) as date_day,
//           ROW_NUMBER() OVER (PARTITION BY ip ORDER BY date) as row_num
//    FROM pqcms_tabs_counter
//    WHERE date >= ? AND date <= ?
//)
//SELECT COUNT(DISTINCT ip) as unique_ips, date_day
//FROM RankedIPs
//WHERE row_num = 1
//GROUP BY date_day;");
        $stmt = $conn->prepare("SELECT * FROM (
    SELECT
        SUBSTR(date, 1, 10) AS date_day,
        COUNT(DISTINCT ip) AS unique_ips
    FROM
        pqcms_tabs_counter t1
    WHERE
        NOT EXISTS (
            SELECT 1
            FROM pqcms_tabs_counter t2
            WHERE SUBSTR(t2.date, 1, 10) < SUBSTR(t1.date, 1, 10)
              AND t2.ip = t1.ip
        )
    GROUP BY
        date_day
) AS subquery
WHERE
    subquery.date_day BETWEEN ? AND ?;");

        $stmt->bind_param("ss",$startDate,$endDate);
        $stmt->execute();

        $result = $stmt->get_result();
        $traffic = [];

        for ($i = 0; $i < 30; $i++) {
            $date = date('Y-m-d', strtotime("$startDate +$i days"));
            $traffic[$date] = 0;
        }

        while($row = $result->fetch_assoc())
            $traffic[$row["date_day"]] = $row["unique_ips"];

        $result->close();
        $stmt->close();


        return $traffic;
    }

    private function getUniqueGuestsData(mysqli $conn): array
    {
        $stmt = $conn->prepare("SELECT COUNT(DISTINCT(ip)) FROM pqcms_tabs_counter WHERE date LIKE ?");

        $traffic = [];
        $date = date('Y-m-d', strtotime('today - 29 days'));
        for($i = 0; $i < 30; $i++)
        {
            $formattedDate = date("d.m", strtotime($date));

            $sqlDate = $date."%";
            $stmt->bind_param("s", $sqlDate);
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