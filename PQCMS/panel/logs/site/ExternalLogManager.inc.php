<?php

class ExternalLogManager
{
    private string $serverName;
    private string $displayName;
    private array $columns;
    private bool $generated = false;
    private int $addAmount;

    public function __construct(string $serverName, string $displayName, array $columns, int $addAmount)
    {
        $this->serverName = $serverName;
        $this->displayName = $displayName;
        $this->columns = $columns;
        $this->addAmount = $addAmount;
    }

    private function getTableHead(): string
    {
        $tds = "";
        foreach ($this->columns as $visualName)
            $tds .= <<<HTML
    <th>$visualName</th>

HTML;
        return <<<HTML
<tr>
    $tds
</tr>
HTML;
    }

    public function generateHtml(): string
    {
        if($this->generated)
            trigger_error("Logs table should be generated only once!",E_USER_WARNING);
        $this->generated = true;

        $id = $this->serverName;
        $displayName = $this->displayName;
        $head = $this->getTableHead();

        return <<<HTML
<h3>$displayName</h3>

<div class="table-wrapper custom-scroll">
    <table class="col-12 data-table" id="pqcms-log-table-$id">
        <thead>
            $head
        </thead>
        <tbody>
        </tbody>
    </table>
    <span id="pqcms-log-table-loadinfo-$id">
        Poczekaj, dane się ładują...
    </span>
</div>
HTML;

    }


    public function generateJS(): string
    {
        $id = $this->serverName;
        $cols = json_encode($this->columns,JSON_UNESCAPED_UNICODE);
        $htmlId = "#pqcms-log-table-loadinfo-$id";
        $addAmount = $this->addAmount;
        $tableCounterVar = "table_td_counter_$id";

        return<<<JS
const table = document.querySelector("#pqcms-log-table-$id");
let $tableCounterVar = 0;

fetch("local_communicators/GetLogs.php", {
  method: "POST",
  body: JSON.stringify({
    type: "$id"
  }),
  headers: {
    "Content-type": "application/json; charset=UTF-8"
  }
}).then((resp) => {
    const infoElement = document.querySelector("$htmlId");
    resp.json().then((json) => {
        if(json["suc"] !== 1 || (!("logs" in json)))
        {
            infoElement.innerText = json["desc"];
            infoElement.style.color = "red";
            console.error(json["desc"]);
            return;
        }
        
        const tbody = table.querySelector("tbody");
        infoElement.remove();
        json["logs"].forEach(e => {
            const tr = document.createElement("tr");
            const data = JSON.parse(e)["data"];
            
            getTds(tr, data).forEach(e => {
                tr.appendChild(e);
            })
            
            tbody.appendChild(tr); 
        })
        tbody.appendChild(getLoadMoreButton(tbody));
    }).catch((err) => {
        infoElement.innerText = err.message;
        infoElement.style.color = "red";
        console.error(err);
    });
});

const columns = $cols;

function getTds(tr, data) {
    const tds = [];

    const action = data["action"];
    if(action === "login")
    {
        if(data["logged"])
            tr.style.background = "rgba(0,255,0,.8)";
        else
            tr.style.background = "rgba(255,80,80,.8)";
    }
    else if(action === "logout")
    {
        if(data["admin_logout"])
            tr.style.background = "rgba(255,0,0,.8)";
        else
            tr.style.background = "rgba(248, 157, 31,.8)";
    }
    
    for(var key in columns)
    {
        const td = document.createElement("td");
        if(key in data)
        {
            let text = data[key];
            if(typeof(text) === "boolean")
                if(text)
                    text = "Tak";
                else
                    text = "Nie";
                
            td.innerText = text;            
        }
            
        else
            td.innerText = "-";
        tds.push(td);
    }
    
    return tds;
}

function getLoadMoreButton(tbody) 
{
    const tr = document.createElement("tr");
    
    const td = document.createElement("td");
    td.colSpan = Object.keys(columns).length;
    td.classList.add("load-more-button");
    td.setAttribute("data-load-destination","$htmlId");
    td.innerText = "Pokaż więcej...";
    
    tr.addEventListener("click",() => {
        tr.remove();
        getData($tableCounterVar,$addAmount);
    })
    
    tr.appendChild(td);
    return tr;
}

function getData(from, amount)
{
    fetch("local_communicators/GetLogs.php", {
      method: "POST",
      body: JSON.stringify({
        type: "$id",
        from: from,
        amont: amount
      }),
      headers: {
        "Content-type": "application/json; charset=UTF-8"
      }
    }).then((resp) => {
        resp.json().then((json) => {
            if(json["suc"] !== 1 || (!("logs" in json)))
            {
                console.error(json["desc"]);
                return;
            }
            
            const tbody = table.querySelector("tbody");
            console.log("foreach:");
            json["logs"].forEach(e => {
                console.log(e);
                const tr = document.createElement("tr");
                const data = JSON.parse(e)["data"];
                
                getTds(tr, data).forEach(e => {
                    tr.appendChild(e);
                });
                
                $tableCounterVar++;
                console.log($tableCounterVar);
                tbody.appendChild(tr); 
            })
            tbody.appendChild(getLoadMoreButton(tbody));
        }).catch((err) => {
            console.error(err);
        });
    });
}
JS;
    }
}