<?php

require_once("../scripts/server/TabUtils.inc.php");
require_once("Perms.php");

require_once(dirname(__DIR__,2)."/config/data/JSONAddons.php");
$activaAddons = new JSONAddons();

require_once(dirname(__DIR__,2)."/addons/AddonManager.inc.php");
$addonManager = new AddonManager();

//$verify = TabUtils::verifyUser("addons");
$verify = TabUtils::verifyUser("addons",(new AddonsPerms($addonManager->getAddonList()))->getPerms());

require_once(dirname(__DIR__,2)."/utils/PQCMSToken.inc.php");
$token = PQCMSToken::generateToken();
$_SESSION["pqcms"]["panel"]["addons"]["change-activation"]["token"] = $token;

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../default.css">
    <link rel="stylesheet" href="addons.css">

    <script src="../../bs5/js/bootstrap.min.js"></script>
    <script src="addonToggler.js" defer></script>

    <style>
        .addon-disabled
        {
            cursor: not-allowed;
        }
    </style>

    <title>PQCMS - Formularze</title>
</head>
<body>
<div class="p-3 d-flex flex-wrap">
<?php

    /** @var Addon $addon */
    foreach($addonManager->getAddonList() as $addon)
    {
        $disabled = ($verify["perms"]["pqcms.addons.".$addon->getId()]) ? "addon-disabled" : "";
        echo $addon->getViewHTML($addonManager->isAddonEnabled($addon->getId()),$disabled,$token["value"]);
    }

?>
</div>
</body>
</html>