<?php

// Sprawdzenie, czy user posiada permisje do strony
require_once(dirname(__DIR__)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("hr");

require_once(dirname(__DIR__,2)."/Communicator.inc.php");
$user = Communicator::communicate(CommunicateURL::GET_USER,["username" => $_SESSION["pqcms-panel-username"]])["resp"];
var_dump($user);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - Panel, Użytkownik</title>

    <!--    <link rel="stylesheet" href="panel.css">-->
    <link rel="stylesheet" href="user.css">
    <link rel="icon" href="../../images/PQCMS.svg">
</head>
<body>

<div id="mainFrame">
    <h1>Twoje dane</h1>

    <form method="post" action="ChangeUserData.php">
        <input name="username" value="<?php ?>">
    </form>
</div>

<script>
    const tabs = document.querySelectorAll("nav ul li");
    const iframeOverlay = document.querySelector("#mainIframeOverlay");
    const iframe = document.querySelector("#mainFrame iframe");

    function changeIframeSrc(element)
    {
        const newUrl = element.getAttribute("data-site");
        const currentURL = new URL(iframe.src);

        const baseUrl = currentURL.origin + currentURL.pathname;
        const pathURL = baseUrl + "?site=" + encodeURIComponent(newUrl);

        if(currentURL.href === pathURL) return;

        iframeOverlay.style.visibility = "visible";
        iframeOverlay.style.opacity = "1";

        iframe.src = pathURL;
    }

    tabs.forEach((e) => {
        e.addEventListener("click", () => changeIframeSrc(e));
    })

    iframe.addEventListener("load",() =>
    {
        iframeOverlay.style.opacity = "0";

        setTimeout(() => {
            iframeOverlay.style.visibility = "hidden";
        },500);
    })
</script>
</body>
</html>