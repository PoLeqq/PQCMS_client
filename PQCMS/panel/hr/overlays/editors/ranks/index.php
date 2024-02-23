<?php

require_once(dirname(__DIR__,4)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUserChildrenTab("hr");

require_once(dirname(__DIR__,5)."/utils/PQCMSToken.inc.php");
$token = PQCMSToken::generateToken();
$_SESSION["pqcms"]["panel"]["hr"]["ranks"]["edit"]["token"] = $token;

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - Edytor rang</title>

    <title>Edytowanie rangi | PQCMS</title>

    <link rel="icon" href="../../../../../images/PQCMS.svg">

    <link rel="stylesheet" href="../../../../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../overlay.css">
    <link rel="stylesheet" href="../users/style.css">
    <link rel="stylesheet" href="index.css">

    <script src="../../permissionTableEditor.js" defer></script>
</head>
<body>
    <?php
//     || !isset($_GET["parent"])
    if(empty($_GET["name"]) || empty($_GET["display_name"]) || !isset($_GET["priority"]) || !isset($_GET["perms"]))
        die("Nie podano wszystkich danych!");
    $_GET["perms"] = json_decode(urldecode($_GET["perms"]),true);
    if(is_null($_GET["perms"]))
        die("Podano niepoprawne uprawnienia!");
    ?>

    <div>
        <form method="POST" action="EditRank.php">
            <input type="hidden" name="token" value="<?php echo $token["value"] ?>"/>

            <fieldset class="d-flex flex-column justify-content-center align-items-start">
                <legend class="h1">Ranga</legend>

                <label class="mt-1" for="name">
                    <span class="h4">
                        ID
                    </span>
                </label>
                <input type="text" id="name" name="name" class="my-2 rounded-0" value="<?php echo $_GET["name"] ?>" readonly/>

                <label for="display_name" class="mt-1">
                    <span class="h4">
                        Nazwa
                    </span>
                </label>
                <input type="text" id="display_name" name="display_name" class="my-2 rounded-0" placeholder="Janek" value="<?php echo $_GET["display_name"] ?>"/>

                <label for="priority" class="mt-1">
                    <span class="h4">
                        Priorytet
                    </span>
                </label>
                <i>(im wyższy, tym ranga jest "ważniejsza"; min. 0, max. 65535)</i>
                <input type="number" id="priority" name="priority" class="my-2 rounded-0" value="<?php echo $_GET["priority"] ?>"/>

<!--                <label class="mt-1">-->
<!--                    <span class="h4">-->
<!--                        Rodzic-->
<!--                    </span>-->
<!--                    <br/>-->
<!--                    <span class="my-3">-->
<!--                        --><?php
//                        require_once(dirname(__DIR__, 5) . "/Communicator.inc.php");
//                        $ranks = Communicator::communicate(CommunicateURL::GET_RANK);
//
//                        if($ranks["suc"] == 1)
//                        {
//                            echo<<<HTML
//                        <select name="parent">
//                            <option value="">-</option>
//HTML;
//                            foreach($ranks["resp"] as $rank)
//                            {
//                                if($rank["name"] === $_GET["name"])
//                                    continue;
//                                echo<<<HTML
//                            <option value="${rank["name"]}">${rank["name"]}</option>
//HTML;
//                            }
//                            echo<<<HTML
//                        </select>
//HTML;
//                        }
//                        else
//                            echo "<p style='color: red'>Wystąpił błąd podczas pobierania rang! Opis: ${ranks["desc"]}</p>";
//                        ?>
<!--                    </span>-->
<!--                </label>-->

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
                            echo "<p style='color: red'>Wystąpił błąd podczas listy permisji! Opis: ${allPerms["desc"]}</p>";
                        ?>
                    </tbody>
                </table>

                <input type="submit" name="submit" class="btn btn-primary my-4 rounded-0" value="Edytuj">
            </fieldset>
        </form>
    </div>
</body>
</html>