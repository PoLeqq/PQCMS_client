<?php
    require_once("Checker.php");
    if(!isFirstTime())
    {
        header("location: ../");
        die("Niepoprawne przekierowanie. Nie musisz być na tej stronie, wszystko zostało już zainicjowane!");
    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>PQCMS - Inicjator</title>

    <link rel="icon" type="image/x-icon" href="../images/PQCMS.svg">

    <link rel="stylesheet" href="../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../login/index.css">

    <script src="main.js" defer></script>
</head>
<body>
    <div id="site-container" class="d-flex justify-content-center align-items-center text-center">

        <form method="POST" class="p-4" action="https://poleq.pl/server/initializer/index.php">
            <input type="hidden" name="ip" value="<?php echo $_SERVER["SERVER_ADDR"] ?>"/>

            <header class="mb-4">
                <a id="main-link" class="navbar-brand fs-2 px-3 link-nav text-white" style="font-size: 40px!important;" href="../../">
                    PQCMS
                    <img src="../images/PQCMS.svg" alt="logo">
                </a>
            </header>

            <fieldset class="form-group border border-white d-flex flex-column justify-content-center align-items-center">
                <legend class="w-75 h2 pb-2 border border-white">Inicjalizator</legend>

                <label class="mt-1" for="pqcms-domain">Domena</label>
                <input type="text" id="pqcms-domain" name="pqcms-domain" class="w-75 form-control-lg m-2 rounded-0" placeholder="twojadomena.pl" />

                <label class="mt-1" for="pqcms-domain">Nazwa użytkownika</label>
                <input type="text" id="pqcms-domain" name="pqcms-username" class="w-75 form-control-lg m-2 rounded-0" placeholder="na2w@_uzytkown1k@3!" />

                <label class="mt-3" for="password">Klucz Licencyjny</label>
                <input type="text" id="password" name="pqcms-license-key" class="w-75 form-control-lg m-2 rounded-0" placeholder="XXXXX-XXXXX-XXXXX-XXXXX" />

                <input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Rozpocznij!">

            </fieldset>
        </form>

    </div>
</body>
</html>