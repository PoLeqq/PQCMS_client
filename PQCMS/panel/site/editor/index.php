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
    <link rel="stylesheet" href="editor.css">
    <link rel="icon" href="../../../images/PQCMS.svg">
</head>
<body>
    editor

    <script>
        window.addEventListener('message', function(event) {
            console.log("Message received from the parent: " + event.data); // Message received from parent
        });
    </script>
</body>
</html>