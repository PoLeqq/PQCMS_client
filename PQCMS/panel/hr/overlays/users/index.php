<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranks - ElectroCMS Overlay</title>

    <link rel="stylesheet" href="../../../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../overlay.css">
</head>
<body>
    <form method="POST" action="AddUser.php" class="col-12 p-4">
        <fieldset class="d-flex flex-column justify-content-center align-items-start">
            <legend class="">Użytkownik</legend>

            <label class="mt-1" for="username">
                Nazwa <br>
                <i>do logowania</i>
            </label>
            <input type="text" id="username" name="username" class="my-2 rounded-0" placeholder="j@n_prac0wn1k"/>

            <label for="nickname" class="mt-1">
                Nick <br>
                <i>przyjazna nazwa</i>
            </label>
            <input type="text" id="nickname" name="nickname" class="my-2 rounded-0" placeholder="Janek"/>

            <label for="password" class="mt-1">
                Hasło <br>
                <i>w polu niżej będzie widoczne!</i>
            </label>
            <input type="text" id="password" name="password" class="my-2 rounded-0" autocomplete="off"/>

            <label>
                Ranga
                <select name="rank" class="my-2">
                    <option value="">-</option>

                    <?php
                    require_once "../../../../hr/Rank.php";
    //                foreach(getAllRanksOrder("priority",false) as $rank)
    //                    echo "<option value=\"{$rank->getId()}\">$rank->name</option>";
                    ?>
                </select>
            </label>

            <label>
                Po utworzeniu konto
                <select name="disabled">
                    <option value="0">Włączone</option>
                    <option value="1">Wyłączone</option>
                </select>
            </label>

            <!-- Miejsce na permisje -->

            <input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Dodaj">
        </fieldset>
    </form>
</body>
</html>