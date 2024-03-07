<?php

require_once("scripts/server/TabUtils.inc.php");
TabUtils::verifyUser();

require_once("../utils/database/Database.inc.php");
$conn = Database::getConnection();
if(!is_null($conn))
{
    $setupDatabase = (Database::setupDefaultDatabase($conn));
    if(is_null($setupDatabase))
    {
        require_once(dirname(__DIR__)."/panel/scripts/notifications/NotificationManager.inc.php");
        NotificationManager::addNewNotification("pqcms-databaseSetupError","Baza danych","e",
            "Niepowodzenie! Nie połączono z bazą danych, przez co nie można jej skonfigurować!");
    }
}
else
{
    require_once(dirname(__DIR__)."/panel/scripts/notifications/NotificationManager.inc.php");
    NotificationManager::addNewNotification("pqcms-databaseSetupError","Baza danych","e",
        "Niepowodzenie! Nie połączono z bazą danych, przez co nie można jej skonfigurować!");
}

require_once(dirname(__DIR__)."/Communicator.inc.php");
$tabs = ["editor" => false, "hr" => false, "settings" => false, "user" => false, "logs" => false, "forms" => false];
$tabsViewPermissions = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["perms" => getTabsAsPerms($tabs)]);

function getTabsAsPerms(array $tabs): array
{
    $perms = [];
    foreach(array_keys($tabs) as $tab)
        $perms[] = "pqcms.tabs.view.$tab";
    return $perms;
}

if($tabsViewPermissions["suc"] == 0)
{
    require_once("scripts/notifications/NotificationManager.inc.php");
    NotificationManager::addNewNotification("pqcms-index-tabs-error","PQCMS","e",
        "Wystąpił błąd podczas komunikacji z serwerami PQCMS! Opis: ".$tabsViewPermissions["desc"]);
    foreach(getTabsAsPerms($tabs) as $tab)
        $tabs[$tab] = false;
}
else
{
    foreach($tabsViewPermissions["perms"] as $tab => $tabPermValue)
        $tabs[$tab] = $tabPermValue;
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - Panel</title>

    <link rel="stylesheet" href="notifications.css">
    <link rel="stylesheet" href="panel.css">
    <link rel="icon" href="../images/PQCMS.svg">

    <script src="panel.js" type="module" defer></script>
    <script src="notifications.js" defer></script>
</head>
<body>
    <input type="hidden" id="pqcms-token" value="<?php echo $_SESSION["pqcms"]["panel"]["pqcms_token"] ?>"/>
    <div id="main">
        <nav>
            <ul>
                <li class="nav-link internalLink" internalLink="home/" tabindex="1">
                    PQCMS
                    <div class="nav-image">
                        <div id="nav-pqcms-logo">
                            <img src="../images/PQCMS.svg" alt="logo">
                        </div>
                    </div>
                </li>
                <?php
                if($tabs["pqcms.tabs.view.editor"])
                    echo <<<HTML
<li class="nav-link internalLink" internalLink="site/" tabindex="2">
    Strona
    <div class="nav-image">
        <img src="images/nav/edit_site.svg" id="edit_site" alt="Strona">
    </div>
</li>
HTML;
                if($tabs["pqcms.tabs.view.forms"])
                    echo <<<HTML
<li class="nav-link internalLink" internalLink="forms/" tabindex="3">
    Formularze
    <div class="nav-image">
        <img src="images/nav/forms.svg" alt="Tabela">
    </div>
</li>
HTML;
                if($tabs["pqcms.tabs.view.logs"])
                    echo<<<HTML
<li class="nav-link internalLink" internalLink="logs/" tabindex="4">
    Logi
    <div class="nav-image">
        <img src="images/nav/logs.svg" alt="Logi">
    </div>
</li>
HTML;
                if($tabs["pqcms.tabs.view.hr"])
                    echo <<<HTML
<li class="nav-link internalLink" internalLink="hr/" tabindex="5">
    HR
    <div class="nav-image">
        <img src="images/nav/hr.svg" alt="Osoby">
    </div>
</li>
HTML;
                if($tabs["pqcms.tabs.view.user"])
                    echo <<<HTML
<li class="nav-link internalLink" internalLink="user/" tabindex="6">
    Twoje dane
    <div class="nav-image">
        <img src="images/nav/user.svg" alt="Użytkownik">
    </div>
</li>
HTML;
                if($tabs["pqcms.tabs.view.settings"])
                    echo <<<HTML
<li class="nav-link internalLink" internalLink="settings/" tabindex="7">
    Ustawienia
    <div class="nav-image">
        <img src="images/nav/settings.svg" alt="Zębatka">
    </div>
</li>
HTML;
                if($tabs["pqcms.tabs.view.settings"])
                    echo <<<HTML
<li class="nav-link internalLink" internalLink="addons/" tabindex="8">
    Dodatki
    <div class="nav-image" style="position: relative; left: 2px">
        <img src="images/nav/addons.svg" alt="Dodatki">
    </div>
</li>
HTML;

                ?>

            </ul>
            <div>
                <a href="logout/" id="logout">
                    Wyloguj się
                    <div class="nav-image">
                        <img src="images/nav/logout.svg" alt="logout">
                    </div>
                </a>
                <div style="display:flex; justify-content: space-between; margin-top: 10px; padding-right: 10px;">
                    <span id="pqcms-username">
                        <?php
                        if(empty($_SESSION["pqcms"]["panel"]["username"]))
                            echo '<span style="color: #ab0000">{Błąd!}</span>';
                        else
                            echo $_SESSION["pqcms"]["panel"]["username"];
                        ?>
                    </span>
                    <span id="pqcms-session-timer">
                        00:00
                    </span>
                </div>
            </div>
        </nav>
        <div id="mainIframe">
            <div id="mainIframeOverlay">
                <img src="images/preloader.gif" alt="preloader"/>
            </div>
            <iframe id="panelMain"></iframe>
        </div>

        <div id="background"></div>
    </div>

    <div id="pqcms-notifications"></div>

    <footer>
        <span style="margin-right: 10px;">
            PQCMS - licencjowany system
        </span>
    </footer>

    <?php
//    date_default_timezone_set("Europe/Warsaw");
//
//    $time = time() + 30 * 60; // Dodanie 30 minut
//    $serverDate = date("Y-m-d H:i:s", $time);
    ?>

    <?php
    date_default_timezone_set("Europe/Warsaw");
    $sessionExpiryDate = date("Y-m-d H:i:s", $_SESSION["pqcms"]["panel"]["auth_key"]["expiry_time"]);

    echo <<<JS
<script type="module">
    const timer = document.querySelector("#pqcms-session-timer"); 
    
    function updateCountdown() {
        const now = convertTimeZone(new Date(),"Europe/Warsaw");
        const targetTime = new Date("$sessionExpiryDate");

        const timeDifference = targetTime - now;
        let minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
        if(minutes < 0) minutes = 0;
        let seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);
        if(seconds < 0) seconds = 0;

        const formattedTime = `\${minutes.toString().padStart(2, '0')}:\${seconds.toString().padStart(2, '0')}`;
        
        timer.textContent = formattedTime;
    }
    
    function convertTimeZone(date, tzString) {
        return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));   
    }

    // Uruchomienie funkcji updateCountdown co sekundę
    setInterval(updateCountdown, 1000);

    // Inicjalne uruchomienie funkcji po załadowaniu strony
    updateCountdown();
    
    import { validateSession } from "./panel.js";
    validateSession();
</script>
JS;
    ?>
</body>
</html>