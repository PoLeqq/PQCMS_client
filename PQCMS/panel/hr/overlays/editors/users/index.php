<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edytowanie użytkownika | PQCMS</title>

    <link rel="icon" href="../../../../../images/PQCMS.svg">

    <link rel="stylesheet" href="../../../../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../overlay.css">
    <link rel="stylesheet" href="style.css">

    <script src="../../permissionTableEditor.js" defer></script>
</head>
<body class="d-flex align-items-center flex-column">
<?php
if(empty($_GET["username"]) || empty($_GET["nickname"]) || !isset($_GET["disabled"]) || !isset($_GET["perms"]))
    die("Nie podano wszystkich danych!");
$_GET["perms"] = json_decode(urldecode($_GET["perms"]),true);
if(is_null($_GET["perms"]))
    die("Podano niepoprawne uprawnienia!");
?>
    <div>
        <form method="POST" action="EditUser.php">
            <fieldset class="d-flex flex-column justify-content-center align-items-start">
                <legend class="h1">Użytkownik</legend>

                <label class="mt-1" for="username">
                    <span class="h4">
                        Nazwa
                    </span>
                    <br>
                    <i>do logowania</i>
                    <br/>
                    <i>Tego pola nie moższ tutaj zmieniać!</i>
                </label>
                <input type="text" id="username" name="username" class="my-2 rounded-0" placeholder="j@n_prac0wn1k" value="<?php echo $_GET["username"] ?>" readonly/>

                <label for="nickname" class="mt-1">
                    <span class="h4">
                        Nick
                    </span>
                    <br>
                    <i>widoczna nazwa</i>
                </label>
                <input type="text" id="nickname" name="nickname" class="my-2 rounded-0" placeholder="Janek" value="<?php echo $_GET["nickname"] ?>"/>

                <label for="email" class="mt-1">
                    <span class="h4">
                        E-Mail
                    </span>
                </label>
                <input type="text" id="email" name="email" class="my-2 rounded-0" placeholder="jan@poleq.pl" value="<?php echo $_GET["email"] ?>"/>

                <label for="password" class="mt-1">
                    <span class="h4">
                        Hasło
                    </span>
                    <br/>
                    <i>w polu niżej będzie widoczne!</i><br/>
                    <i>Hasło można tylko zresetować, nie można zobaczyć poprzedniego</i>
                </label>
                <input type="text" id="password" name="password" class="my-2 rounded-0" autocomplete="off"/>

<!--                <label>-->
<!--                    <span class="h4">-->
<!--                        Rangi-->
<!--                    </span>-->
<!--                    <br/>-->
<!--                    <span class="my-3">-->
<!--                        --><?php
//                        require_once(dirname(__DIR__, 5) . "/Communicator.inc.php");
//                        $ranks = Communicator::communicate(CommunicateURL::GET_RANK);
//
//                        if($ranks["suc"] == 1)
//                        {
//                            foreach($ranks["resp"] as $rank)
//                            {
//                                $checked = (isset($_GET["perms"]["pqcms.rank.".$rank["name"]]) && $_GET["perms"]["pqcms.rank.".$rank["name"]]) ? "checked" : "";
//                                echo "<input type='checkbox' name='perms[true][pqcms.rank.${rank["name"]}]' $checked/> ${rank["name"]}<br>";
//                            }
//                        }
//                        else
//                            echo "<p style='color: red'>Wystąpił błąd podczas pobierania rang! Opis: ${ranks["desc"]}</p>";
//
//                        ?>
<!--                    </span>-->
<!--                </label>-->

                <label>
                    Status konta
                    <?php
                    $selectedE = $_GET["disabled"] == 0 ? "selected" : "";
                    $selectedD = $_GET["disabled"] == 1 ? "selected" : "";

                    echo<<<HTML
    <select name="disabled">
        <option value="0" $selectedE>Włączone</option>
        <option value="1" $selectedD>Wyłączone</option>
    </select>
    HTML;

                    ?>

                </label>

                <label class="my-3">
                    <span class="h4">
                        Uprawnienia
                    </span>
                    <br/>
                    <i>Kolumny tak/nie (prawda/fałsz) oznaczają, jaką wartość ma przyjąć uprawnienie</i>
                </label>
                <table class="data-table" id="permission-table-editable">
                    <thead>
                    <tr>
                        <th>
                            Permisja
                        </th>
                        <th>
                            Opis
                        </th>
                        <th>
                            Tak
                        </th>
                        <th>
                            Nie
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    require_once(dirname(__DIR__, 5) . "/Communicator.inc.php");
                    $allPerms = Communicator::communicate(CommunicateURL::GET_PERMS);
                    if($allPerms["suc"] == 1)
                        foreach($allPerms["resp"] as $perm)
                        {
                            $desc = $perm["description"];
                            $perm = $perm["perm"];

                            $enabledTrue = (isset($_GET["perms"][$perm]) && $_GET["perms"][$perm]) ? "checked" : "";
                            $enabledFalse = (isset($_GET["perms"][$perm]) && !$_GET["perms"][$perm]) ? "checked" : "";

                            echo <<<HTML
    <tr>
        <td style="text-align: left">
            <pre style="margin: 0">${perm}</pre>
        </td>
        <td style="text-align: left">
            ${desc}
        </td>
        <td>
            <input type="checkbox" name="perms[true][${perm}]" class="checkbox-perm-true" data-perm="${perm}" $enabledTrue/>
        </td>
        <td>
            <input type="checkbox" name="perms[false][${perm}]" class="checkbox-perm-false" data-perm="${perm}" $enabledFalse/>
        </td>
    </tr>
    HTML;
                        }
                    else
                        echo "<p style='color: red'>Wystąpił błąd podczas listy permisji! Opis: ${ranks["desc"]}</p>";

                    ?>
                    </tbody>
                </table>

                <input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Edytuj">
            </fieldset>
        </form>
        <form method="post" action="ResetPassword.php" class="d-flex justify-content-start align-items-center">
            <input type="hidden" name="username" value="<?php echo $_GET["username"] ?>"/>
            <input type="submit" value="Zresetuj hasło" class="btn btn-danger"/>
        </form>
    </div>
</body>
</html>