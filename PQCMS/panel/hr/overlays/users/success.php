<?php

session_start();
if(!isset($_SESSION["pqcms-panel-hr-add_user-result"])) {
    header("location: ../../");
    die("Niepoprawne przekierowanie.");
}
unset($_SESSION["pqcms-initializer-success"])

?>

<!doctype html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Sukces | ElectroCMS</title>
    <meta name="description" content="Panel logowania do systemu ElectroCMS">
    <meta name="author" content='Wiktor "PoLeq" Soliński, Jan "Kancjusz" Tokarz'>
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="../../../../images/PQCMS.svg">

    <link rel="stylesheet" href="../../../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../default.css">
    <link rel="stylesheet" href="../success.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #00ff00, #31a231, #57ff57, #00ff00);
            background-size: 400% 400%;
            animation: block-background 20s ease-in-out infinite;
        }
    </style>
</head>
<body>

<div id="site-container" class="d-flex justify-content-center align-items-center text-center">
    <div class="result">
        <header class="mb-4">
            <a id="main-link" class="navbar-brand px-3 text-white" style="font-size: 40px!important;" href="http://localhost/pqcms/">
                PQCMS
                <img src="../../../../images/PQCMS.svg" alt="logo">
            </a>
        </header>

        Sukcess
        <noscript>
            <a href="http://localhost/pqcms/server/client/">Wykryto wyłączony JavaScript! Kliknij tutaj, aby przekierować.</a>
        </noscript>
    </div>
</div>
</body>
</html>