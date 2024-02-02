<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dodawanie użytkownika - PQCMS</title>

    <link rel="icon" href="../../../../../images/PQCMS.svg">

    <link rel="stylesheet" href="../../../../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../overlay.css">
    <link rel="stylesheet" href="style.css">

    <script src="../../permissionTableEditor.js" defer></script>
</head>
<body class="d-flex justify-content-center align-items-center">
    <form method="POST" action="AddUser.php">
        <fieldset class="d-flex flex-column justify-content-center align-items-start">
            <legend class="h1">Użytkownik</legend>

            <label class="mt-1" for="username">
                <span class="h4">
                    Login
                </span>
            </label>
            <input type="text" id="username" name="username" class="my-2 rounded-0" placeholder="j@n_prac0wn1k"/>

            <label for="nickname" class="mt-1">
                <span class="h4">
                    Nick
                </span>
                <br>
                <i>widoczna nazwa</i>
            </label>
            <input type="text" id="nickname" name="nickname" class="my-2 rounded-0" placeholder="Janek"/>

            <label for="email" class="mt-1">
                <span class="h4">
                    E-Mail
                </span>
            </label>
            <input type="text" id="email" name="email" class="my-2 rounded-0" placeholder="jan@poleq.pl"/>

            <label for="password" class="mt-1">
                <span class="h4">
                    Hasło
                </span>
                <br/>
                <i>w polu niżej będzie widoczne!</i>
            </label>
            <input type="text" id="password" name="password" class="my-2 rounded-0" autocomplete="off"/>

<!--            <label>-->
<!--                <span class="h4">-->
<!--                    Rangi-->
<!--                </span>-->
<!--                <br/>-->
<!--                <span class="my-3">-->
<!--                    --><?php
//                    require_once(dirname(__DIR__, 5) . "/Communicator.inc.php");
//                    $ranks = Communicator::communicate(CommunicateURL::GET_RANK);
//                    if($ranks["suc"] == 1)
//                    {
//                        foreach($ranks["resp"] as $rank)
//                            echo<<<HTML
//<label class="d-block">
//    <input type="checkbox" name="perms[true][pqcms.rank.${rank["name"]}]"/> ${rank["name"]}
//</label>
//HTML;
//                    }
//                    else
//                        echo "<p style='color: red'>Wystąpił błąd podczas pobierania rang! Opis: ${ranks["desc"]}</p>";
//
//                    ?>
<!--                </span>-->
<!--            </label>-->

            <label>
                Po utworzeniu konto
                <select name="disabled">
                    <option value="0">Włączone</option>
                    <option value="1">Wyłączone</option>
                </select>
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

                $permsToCheck = [];
                foreach($allPerms["resp"] as $perm)
                    $permsToCheck[] = $perm["perm"];

                $userPerms = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["perms" => $permsToCheck]);
                if($allPerms["suc"] == 1)
                    foreach($allPerms["resp"] as $perm)
                    {
                        $disabledCheckbox = $userPerms["perms"][$perm["perm"]] ? "" : "disabled";
                        $disabledClass = $userPerms["perms"][$perm["perm"]] ? "" : "class=\"disabled\"";
                        echo<<<HTML
<tr>
    <td style="text-align: left">
        <pre style="margin: 0">${perm["perm"]}</pre>
    </td>
    <td style="text-align: left">
        ${perm["description"]}
    </td>
    <td ${disabledClass}>
        <input type="checkbox" name="perms[true][${perm["perm"]}]" class="checkbox-perm-true" data-perm="${perm["perm"]}" ${disabledCheckbox}/>
    </td>
    <td ${disabledClass}>
        <input type="checkbox" name="perms[false][${perm["perm"]}]" class="checkbox-perm-false" data-perm="${perm["perm"]}" ${disabledCheckbox}/>
    </td>
</tr>
HTML;
                    }
                else
                    echo "<p style='color: red'>Wystąpił błąd podczas listy permisji! Opis: ${userPerms["desc"]}</p>";

                ?>
                </tbody>
            </table>

            <input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Dodaj">
        </fieldset>
    </form>
</body>
</html>