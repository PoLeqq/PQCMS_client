<?php

@session_start();
if(empty($_SESSION["pqcms-panel-auth_key"]))
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
    <link rel="stylesheet" href="editor.css">
    <link rel="icon" href="../../../images/PQCMS.svg">
</head>
<body>
    <?php
    require_once(dirname(__DIR__,3)."/website/classes/Website.php");
    $website = new Website();

    if(empty($_GET["site"])) $site = "_root_";
    else $site = $_GET["site"];

    $tab = $website->getTab($site);

    if(is_null($tab)) echo "Nie znaleziono podanej strony.";
    else echo $tab->generateHtml(true);

    ?>

    <script>
        function adjustTextareaHeight(textarea)
        {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight + 2) + 'px';
        }

        document.querySelectorAll("textarea").forEach((e) =>
        {
            adjustTextareaHeight(e);

            e.addEventListener("input",() => {
                adjustTextareaHeight(e);
            });

            e.addEventListener("keydown", (event) => {

               let ctrlPressed = event.ctrlKey || event.metaKey;

                const shortcuts = {
                    "b": "b",
                    "i": "i",
                    "u": "u",
                    "s": "s",
                    "q": "quote",
                    "l": "link=https://poleq.pl",
                    // "br": "br",
                    "p": "p"
                };

                if(ctrlPressed && (event.key in shortcuts))
                    shortcutPQCode(event,e,shortcuts[event.key]);
            });
        })

        function shortcutPQCode(event,textarea,tag)
        {
            event.preventDefault();

            let selectedText = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
            let newText = `[${tag}]${selectedText}[/${tag}]`;

            textarea.setRangeText(newText, textarea.selectionStart, textarea.selectionEnd, 'end');

            textarea.dispatchEvent(new Event("input"));
        }
    </script>
</body>
</html>