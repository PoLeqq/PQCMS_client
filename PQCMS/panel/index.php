<?php
require_once("../utils/database/Database.inc.php");
$setupDatabase = (Database::setupDefaultDatabase());

require_once("scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("");

require_once(dirname(__DIR__)."/Communicator.inc.php");
$tabs = ["editor" => true,"hr" => true,"settings" => true, "user" => true];
$tabsViewPermissions = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["perms" => getTabsAsPerms($tabs)]);

function getTabsAsPerms(array $tabs): array
{
    $perms = [];
    foreach(array_keys($tabs) as $tab)
        $perms[] = "pqcms.tabs.view.$tab";
    return $perms;
}

if(is_null($tabsViewPermissions))
{
    require_once("scripts/notifications/NotificationManager.inc.php");
    NotificationManager::addNewNotification("pqcms-index-tabs-error","PQCMS","e",
        "Wystąpił błąd podczas komunikacji z serwerami PQCMS! Skontaktuj się z administratorem PQCMS!");
}
else if($tabsViewPermissions["suc"] == 0)
{
    require_once("scripts/notifications/NotificationManager.inc.php");
    NotificationManager::addNewNotification("pqcms-index-tabs-error","PQCMS","e",
        "Wystąpił błąd podczas komunikacji z serwerami PQCMS! Opis: ".$tabsViewPermissions["desc"]);
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

    <link rel="stylesheet" href="panel.css">
    <link rel="stylesheet" href="notifications.css">
    <link rel="icon" href="../images/PQCMS.svg">

    <script src="panel.js" defer></script>
    <script src="notifications.js" defer></script>
</head>
<body>
    <div id="main">
        <nav>
            <ul>
                <li class="internalLink" internalLink="https://poleq.pl/server/client/system/homepage.php" tabindex="1">
<!--                <li class="internalLink" internalLink="http://localhost/pqcms/server/client/system/homepage.php" tabindex="1">-->
                    PQCMS
                    <div class="nav-image">
                        <img src="../images/PQCMS.svg" alt="logo">
                    </div>
                </li>
                <?php
                if($tabs["pqcms.tabs.view.editor"])
                    echo<<<HTML
<li class="internalLink" internalLink="site/" tabindex="2">
    Strona
    <div class="nav-image">
        <img src="images/edit_site.svg" id="edit_site" alt="Strona">
    </div>
</li>
HTML;
                if($tabs["pqcms.tabs.view.hr"])
                    echo<<<HTML
<li class="internalLink" internalLink="hr/" tabindex="4">
    HR
    <div class="nav-image">
        <img src="images/hr.svg" alt="Osoby">
    </div>
</li>
HTML;
                if($tabs["pqcms.tabs.view.hr"])
                    echo<<<HTML
                <!--                <li class="internalLink" internalLink="logs/" tabindex="5">-->
<!--                    Logi-->
<!--                    <div class="nav-image">-->
<!--                        <img src="images/logs.svg" alt="Logi">-->
<!--                    </div>-->
<!--                </li>-->
HTML;
                if($tabs["pqcms.tabs.view.settings"])
                    echo<<<HTML
<li class="internalLink" internalLink="settings/" tabindex="6">
    Ustawienia
    <div class="nav-image">
        <img src="images/settings.svg" alt="Zębatka">
    </div>
</li>
HTML;
                if($tabs["pqcms.tabs.view.user"])
                    echo<<<HTML
<li class="internalLink" internalLink="user/" tabindex="7">
    Twoje dane
    <div class="nav-image">
        <img src="images/user.svg" alt="Użytkownik">
    </div>
</li>
HTML;
                ?>

            </ul>
            <div>
                <a href="logout/" id="logout">
                    Wyloguj się
                    <div class="nav-image">
                        <img src="images/logout.svg" alt="logout">
                    </div>
                </a>
                <div style="display:flex; justify-content: space-between">
                    <span id="pqcms-username">
                        <?php echo $_SESSION["pqcms-panel-username"] ?>
                    </span>
                    <span id="pqcms-session-timer">
                        (czas)
                    </span>
                </div>
            </div>
        </nav>
        <div id="mainIframe">
            <div id="mainIframeOverlay">
                <img src="images/preloader.gif" alt="preloader"/>
            </div>
            <iframe id="panelMain" src="home/"></iframe>
        </div>

        <div id="background"></div>
    </div>

    <div id="pqcms-notifications"></div>

    <footer>
        PQCMS &copy Wszelkie prawa zastrzeżone.
        <a class="d-block" href="http://localhost/pqcms/kontakt/">Kontakt z administratorem</a>
    </footer>
</body>
</html>