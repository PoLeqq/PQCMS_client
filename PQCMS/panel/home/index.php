<?php

require_once("../scripts/server/TabUtils.inc.php");
TabUtils::verifyUser();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../default.css">
    <link rel="stylesheet" href="index.css">

    <title>PQCMS - Home Panel</title>

    <style>
        body {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <iframe src="https://poleq.pl/server/client/system/?token=<?php echo $_SESSION["pqcms"]["panel"]["pqcms_token"] ?>"></iframe>
</body>
</html>