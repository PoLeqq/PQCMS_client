<?php

@session_start();
if(isset($_SESSION["pqcms"]["panel"]["hr"]["edit-user-result"]))
{
    $result = $_SESSION["pqcms"]["panel"]["hr"]["edit-user-result"];
    unset($_SESSION["pqcms"]["panel"]["hr"]["edit-user-result"]);
}
else if(isset($_SESSION["pqcms"]["panel"]["hr"]["reset_password-user-result"]))
{
    $result = $_SESSION["pqcms"]["panel"]["hr"]["reset_password-user-result"];
    unset($_SESSION["pqcms"]["panel"]["hr"]["reset_password-user-result"]);
}
else
    die("Jeśli jesteś na tej stronie, najprawdopodobniej nastąpiło niepoprawne przekierowanie.");

?>

<!doctype html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Rezultat | PQCMS</title>
    <meta name="author" content='Wiktor "PoLeq" Soliński'>
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="../../../../../images/PQCMS.svg">

    <link rel="stylesheet" href="../../../../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../default.css">
    <link rel="stylesheet" href="../../success.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">

    <style>
        body {
            <?php
            if($result["suc"])
                echo "background: linear-gradient(135deg, #00ff00, #31a231, #57ff57, #00ff00);";
            else
                echo "background: linear-gradient(135deg, red, #ff6b4d, #fc5d21, #ff0000);";
            ?>

            background-size: 400% 400%;
            animation: block-background 20s ease-in-out infinite;
        }

        #site-container {
            height: 100vh;
            width: 100vw;
        }
    </style>
</head>
<body>

<div id="site-container" class="d-flex justify-content-center align-items-center text-center">
    <div class="result">
        <header class="mb-4">
            <a id="main-link" class="navbar-brand px-3 text-white" style="font-size: 40px!important;" href="http://localhost/pqcms/">
                PQCMS
                <img src="../../../../../images/PQCMS.svg" alt="logo" style="width: 50px">
            </a>
        </header>

        <p>
        <?php
            if($result["suc"])
                echo "Sukces!";
            else
                echo "Błąd!";
            echo " ${result["desc"]}";
        ?>
        </p>

        <noscript>
            <a href="http://localhost/pqcms/server/client/">Wykryto wyłączony JavaScript! Kliknij tutaj, aby przekierować.</a>
        </noscript>
    </div>
</div>
</body>
</html>