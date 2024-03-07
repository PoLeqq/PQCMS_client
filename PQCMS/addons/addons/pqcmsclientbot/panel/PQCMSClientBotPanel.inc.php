<?php

require_once(dirname(__DIR__,3)."/classes/AddonPanel.inc.php");

class PQCMSClientBotPanel extends AddonPanel
{
    protected function getWebsiteHTML(): string
    {
        $path = $this->addon->getRelativePathFromPanelToAddon();

        try {
            $config = $this->addon->getConfig();
        } catch (Exception $e) {
            return $e;
        }

        $selectedNewPageYes = $config["redirect-new-page"] ? "selected" : "";
        $selectedNewPageNo = $config["redirect-new-page"] ? "" : "selected";

        return<<<HTML
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>PQCMS - Addon Panel</title>
    
    <link rel="stylesheet" href="../../bs5/css/bootstrap.min.css">
</head>
<body>
    <div class="p-4">
        <h1>PQ Chatbot Panel</h1>
        <!--<form method="post" action="$path/scripts/UpdateConfig.php" class="col-3 d-flex flex-column gap-3">
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
        </form>-->
    </div>
</body>
</html>
HTML;

    }
}