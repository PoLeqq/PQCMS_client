<?php

// Sprawdzenie, czy user posiada permisje do strony
require_once(dirname(__DIR__)."/scripts/server/TabUtils.inc.php");
TabUtils::verifyUser("hr");

require_once(dirname(__DIR__,2)."/Communicator.inc.php");
$user = Communicator::communicate(CommunicateURL::GET_USER,["username" => $_SESSION["pqcms"]["panel"]["username"]])["resp"][0];
$perms = Communicator::communicate(CommunicateURL::GET_PERMS);
$hasPerms = Communicator::communicate(CommunicateURL::HAS_PERMISSION,["perms" => [
        "pqcms.hr.user.edit.nickname.${user["username"]}",
        "pqcms.hr.user.edit.email.${user["username"]}"
]])["perms"];

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - Panel, Użytkownik</title>

    <!--    <link rel="stylesheet" href="panel.css">-->
    <link rel="stylesheet" href="../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dataTable.css">
    <link rel="stylesheet" href="../default.css">
    <link rel="stylesheet" href="user.css">
    <link rel="icon" href="../../images/PQCMS.svg">

    <style>
        form {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>

<div class="p-4">
    <h1>Twoje dane</h1>

    <form method="post" action="ChangeUserData.php" class="d-flex flex-column gap-3 mb-4">
        <?php

        $nicknameInput = ($hasPerms["pqcms.hr.user.edit.nickname.${user["username"]}"])  ? "" : "disabled";
        $emailInput = ($hasPerms["pqcms.hr.user.edit.email.${user["username"]}"])  ? "" : "disabled";

        echo<<<HTML
<label>
    <b>Login</b>

    <span>${user["username"]}</span>
    <input type="hidden" name="username" value="${user["username"]}">
</label>

<label>
    <b>Nazwa użytkownika</b>
    <input name="username" value="${user["nickname"]}" ${nicknameInput}>
</label>

<label>
    <b>E-mail</b>
    <input name="email" value="${user["email"]}" $emailInput>
</label>

<input type="submit" value="Zmień dane"/>
HTML;

        ?>
    </form>

    <h1>Twoje uprawnienia</h1>
    <?php
    if(isset($user["perms"]))
    {
        echo<<<HTML
<table class="px-4 my-3 col-12 data-table">
    <thead>
    <tr>
        <th>Uprawnienie</th>
        <th>Opis</th>
        <th>Wartość</th>
    </tr>
    </thead>
    <tbody>
HTML;
        foreach($user["perms"] as $perm => $value)
        {
            $permDesc = getPermissionByName($perm);
            if(is_null($permDesc))
                $desc = "<span style='color: red'>Nieznane uprawnienie!</span>";
            else
                $desc = $permDesc["description"];

            $value = $value ? "<td class='perm-enabled'>Wł</td>" : "<td class='perm-disabled'>Wył</td>";
            echo<<<HTML
        <tr>
            <td><pre>$perm</pre></td>
            <td>$desc</td>
            $value
        </tr>
HTML;
        }

        echo<<<HTML
    </tbody>
</table>
HTML;
    }
    else
        echo "Jest to konto administratora, dlatego masz dostęp do wszystkiego.";

    ?>
</div>

<script>
    const tabs = document.querySelectorAll("nav ul li");
    const iframeOverlay = document.querySelector("#mainIframeOverlay");
    const iframe = document.querySelector("#mainFrame iframe");

    function changeIframeSrc(element)
    {
        const newUrl = element.getAttribute("data-site");
        const currentURL = new URL(iframe.src);

        const baseUrl = currentURL.origin + currentURL.pathname;
        const pathURL = baseUrl + "?site=" + encodeURIComponent(newUrl);

        if(currentURL.href === pathURL) return;

        iframeOverlay.style.visibility = "visible";
        iframeOverlay.style.opacity = "1";

        iframe.src = pathURL;
    }

    tabs.forEach((e) => {
        e.addEventListener("click", () => changeIframeSrc(e));
    })

    iframe.addEventListener("load",() =>
    {
        iframeOverlay.style.opacity = "0";

        setTimeout(() => {
            iframeOverlay.style.visibility = "hidden";
        },500);
    })
</script>
</body>
</html>

<?php

function getPermissionByName($permName): ?array
{
    global $perms;

    foreach($perms["resp"] as $perm)
    {
        if($perm["perm"] === $permName)
            return $perm;
    }

    return null;
}

?>