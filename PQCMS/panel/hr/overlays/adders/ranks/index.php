<?php

require_once(dirname(__DIR__,4)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUserChildrenTab("hr");

require_once(dirname(__DIR__,5)."/utils/PQCMSToken.inc.php");
$token = PQCMSToken::generateToken();
$_SESSION["pqcms"]["panel"]["hr"]["ranks"]["add"]["token"] = $token;

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dodawanie rangi - PQCMS</title>

    <link rel="icon" href="../../../../../images/PQCMS.svg">
    
    <link rel="stylesheet" href="../../../../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../overlay.css">
    <link rel="stylesheet" href="style.css">

    <script src="../../permissionTableEditor.js" defer></script>
</head>
<body>

    <form method="POST" action="AddRank.php" class="col-12 p-4">
        <input type="hidden" name="token" value="<?php echo $token["value"] ?>"/>

        <fieldset class="d-flex flex-column justify-content-center align-items-start">
            <legend class="">Ranga</legend>
    
            <label class="mt-1">
                Nazwa rangi (identyfikator)<br/>
                <input type="text" name="name" class="my-2 rounded-0" placeholder="admin"/>
            </label>

            <label class="mt-1">
                Nazwa wyświetlana<br/>
                <input type="text" name="display_name" class="my-2 rounded-0" placeholder="Administrator"/>
            </label>
<!--            <label class="mt-1">Rodzic</label>-->
<!---->
<!--            --><?php
//            require_once(dirname(__DIR__, 5) . "/Communicator.inc.php");
//            $ranksResponse = Communicator::communicate(CommunicateURL::GET_RANK);
//
//            if($ranksResponse["suc"] == 1)
//            {
//                echo<<<HTML
//<select name="parent" class="my-2">
//    <option value="">-</option>
//HTML;
//
//                foreach($ranksResponse["resp"] as $rank)
//                    echo "<option value=\"${$rank["name"]}\">${rank["display_name"]} (${rank["name"]})</option>";
//
//                echo<<<HTML
//</select>
//HTML;
//            }
//            else
//                echo<<<HTML
//<span style="color: red">
//Wystąpił błąd podczas pobierania rang! Opis: ${ranksResponse["desc"]}
//</span>
//HTML;
//            ?>

            <label class="mt-1">
                Priorytet<br/>
                <i class="formAside">(im wyższy, tym ranga jest "ważniejsza"; min. 0, max. 65535)</i><br/>
                <input type="number" name="priority" placeholder="priorytet">
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

                require_once(dirname(__DIR__,5)."/Communicator.inc.php");
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