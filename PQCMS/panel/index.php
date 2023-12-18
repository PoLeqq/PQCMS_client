<?php
require_once("../utils/database/Database.inc.php");
$setupDatabase = (Database::setupDefaultDatabase());
session_start();
if(empty($_SESSION["pqcms-panel-auth_key"]))
{
    header("location: ../");
    die("Najpierw musisz się zalogować!");
}
unset($_SESSION["pqcms-panel-login-error"]);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - Panel</title>

    <link rel="stylesheet" href="panel.css">
    <link rel="icon" href="../images/PQCMS.svg">

    <script src="panel.js" defer></script>
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
                <li class="internalLink" internalLink="site/" tabindex="2">
                    Strona
                    <div class="nav-image">
                        <img src="images/edit_site.svg" id="edit_site" alt="Strona">
                    </div>
                </li>
                <li class="internalLink" internalLink="hr/" tabindex="4">
                    HR
                    <div class="nav-image">
                        <img src="images/hr.svg" alt="Osoby">
                    </div>
                </li>
                <li class="internalLink" internalLink="logs/" tabindex="5">
                    Logi
                    <div class="nav-image">
                        <img src="images/logs.svg" alt="Logi">
                    </div>
                </li>
                <li class="internalLink" internalLink="settings/" tabindex="6">
                    Ustawienia
                    <div class="nav-image">
                        <img src="images/settings.svg" alt="Zębatka">
                    </div>
                </li>
                <li class="internalLink" internalLink="user/" tabindex="7">
                    Twoje dane
                    <div class="nav-image">
                        <img src="images/user.svg" alt="Użytkownik">
                    </div>
                </li>
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
    <footer>
        PQCMS &copy Wszelkie prawa zastrzeżone.
        <a class="d-block" href="http://localhost/pqcms/kontakt/">Kontakt z administratorem</a>
    </footer>
</body>
</html>