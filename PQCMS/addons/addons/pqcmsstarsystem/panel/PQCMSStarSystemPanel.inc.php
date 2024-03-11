<?php

require_once(dirname(__DIR__,3)."/classes/AddonPanel.inc.php");

class PQCMSStarSystemPanel extends AddonPanel
{
    /**
     * @param Addon $addon dodatek
     */
    public function __construct(Addon $addon)
    {
        parent::__construct($addon);
    }

    private function getDataTable(): string
    {
        require_once(dirname(__DIR__)."/database/PQCMSStarSystemDatabase.inc.php");
        $systemDatabase = new PQCMSStarSystemDatabase();
        $rates = $systemDatabase->getRates();

        $rows = "";
        foreach ($rates as $rate) {
            $rows .= <<<HTML
<tr>
    <td>${rate["ip"]}</td>
    <td>${rate["stars"]}</td>
    <td>${rate["email"]}</td>
    <td>${rate["description"]}</td>
    <td>${rate["date"]}</td>
</tr>

HTML;

        }

        $amount = count($rates);

        return<<<HTML
<div class="table-parent mb-2" id="rates-table-div">
    <table class="col-12">
        <thead>
            <tr>
                <th>IP</th>        
                <th>Ocena</th>        
                <th>E-mail</th>        
                <th>Opis</th>        
                <th>Data</th>        
            </tr>    
        </thead>
        <tbody>
            $rows
        </tbody>
    </table>
</div>
Ilość ocen: $amount
HTML;

    }


    protected function getWebsiteHTML(): string
    {
        $dir = $this->addon->getRelativePathFromPanelToAddon();

        try {
            $config = $this->addon->getConfig();
        } catch (Exception $e) {
            return<<<HTML
<!--Internal addon error (while trying to get config file)-->
HTML;
        }

        $selectedNewPageYes = $config["redirect-new-page"] ? "selected" : "";
        $selectedNewPageNo = $config["redirect-new-page"] ? "" : "selected";

        $table = $this->getDataTable();

        return<<<HTML
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>PQCMS - Addon Panel</title>
    
    <link rel="stylesheet" href="../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="$dir/panel/style.css">
</head>
<body>
    <div class="p-4 d-flex flex-wrap" style="height: 100vh">
        <div class="col-12 col-lg-6 p-4">
            <h1>PQ Star System Panel</h1>
            <form method="post" action="$dir/scripts/UpdateConfig.php" class="col-12 d-flex flex-column gap-3">
                <div class="d-flex flex-column">
                    Od ilu gwiazdek przekierowywać:<br/>
                    <input type="number" name="star-redirect-rate" value="${config["star-redirect-rate"]}"/>            
                </div>
                <div class="d-flex flex-column">
                    Link przekierowania (np. do opini Google):
                    <input name="redirect-url" value="${config["redirect-url"]}"/>            
                </div>
                <div>
                    Czy przekierowanie otwiera nowe okno:
                    <select name="redirect-new-page" class="col-12">
                        <option value="true" $selectedNewPageYes>Tak</option>
                        <option value="false" $selectedNewPageNo>Nie</option>
                    </select>
                </div>
                <input type="submit" value="Aktualizuj wartości"/>
            </form>
        </div>
        </form>
        <div class="col-12 col-lg-6 p-4" style="height: 95%">
            $table
        </div>
    </div>
    
</body>
</html>
HTML;

    }
}