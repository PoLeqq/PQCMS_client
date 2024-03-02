<?php

require_once(dirname(__DIR__,2)."/classes/AddonPanel.inc.php");

class PQCMSStarSystemPanel extends AddonPanel
{

    protected function getWebsiteHTML()
    {
        return<<<HTML
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>test</title>
</head>
<body>
    <h1>PQ Star System Panel</h1>
</body>
</html>
HTML;

    }
}