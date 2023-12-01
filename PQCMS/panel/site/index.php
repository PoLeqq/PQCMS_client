<?php

session_start();
if(empty($_SESSION["pqcms-panel-username"]))
{
    header("location: ../");
    die("Najpierw musisz się zalogować! Błędne przekierowanie.");
}

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
                $site = json_decode(file_get_contents("site.json"),true);

                foreach($site["tabs"] as $tab)
                {
                    echo<<<END
                    <li title="${tab}">
                        ${tab}
                    </li>
                    END;
                }
            ?>
        </ul>
    </nav>

    <div id="mainFrame">
        <iframe></iframe>
    </div>

    <script>

        function sendMessage() {
            const message = document.querySelector("#message").value;
            const iframe = document.querySelector("iframe");
            iframe.contentWindow.postMessage(message, "*");
        }

        let tabs = document.querySelectorAll("nav ul li");
        tabs.forEach((e) => {
            e.addEventListener("click", sendMessage);
        })
    </script>
</body>
</html>