<?php

require_once(dirname(__DIR__)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("editor");

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - Panel, Strona</title>

<!--    <link rel="stylesheet" href="panel.css">-->
    <link rel="stylesheet" href="site.css">
    <link rel="icon" href="../../images/PQCMS.svg">
</head>
<body>
    <nav>
        <ul>
            <?php
            require_once(dirname(__DIR__,2)."/config/data/JSONTabs.php");
            $sites = new JSONTabs();

            foreach($sites->getTabs() as $path => $name)
            {
                echo<<<END
                <li title="${name} - ${path}/" data-site="${path}">
                    ${name}
                </li>
                END;
            }
            ?>
        </ul>
    </nav>

    <div id="mainFrame">
        <div id="mainIframeOverlay">
            <img src="../images/preloader.gif" alt="preloader"/>
        </div>
        <iframe src="editor?site=Root"></iframe>
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