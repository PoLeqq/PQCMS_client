<?php

require_once(dirname(__DIR__,3)."/classes/AddonPanel.inc.php");

class PQCMSClientBotPanel extends AddonPanel
{
    protected function getWebsiteHTML(): string
    {
        $dir = $this->addon->getRelativePathFromPanelToAddon();

        try {
            $config = $this->addon->getConfig();
        } catch (Exception $e) {
            return $e;
        }

        require_once("messages/PQCMSClientBotStartMessageManager.inc.php");
        $startMessages = new PQCMSClientBotStartMessageManager($config["start_messages"]);
        $startMsg = $startMessages->getEditableTable();

        return<<<HTML
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>PQCMS - Addon Panel</title>
    
    <link rel="stylesheet" href="../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="$dir/panel/style.css">
    <script src="$dir/panel/editableTable.js" type="module" defer></script>
</head>
<body>
    <div class="p-4 d-flex flex-column">
        <h1>PQ Chatbot Panel</h1>
        <div class="col-12 col-lg-6">
            <form action="$dir/scripts/UpdateConfig.php" method="post" class="d-flex flex-column gap-3">
                <h2>Zmienne</h2>
                <div>
                    Nazwa bota:<br/>
                    <input name="botname" value="${config["bot_name"]}"/>                
                </div>
                <div>
                    Opis błędu:<br/>
                    <input name="errormessage" value="${config["error_message"]}"/>             
                </div>
                <div>
                    Wiadomości startowe:<br/>
                    $startMsg            
                </div>
                <input type="submit" value="Aktualizuj dane">
            </form>        
        </div>
    </div>
</body>
</html>
HTML;

    }
}