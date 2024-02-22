<!-- można dodać plik robots.txt, aby usuwał "podsuwanie" plików pqcms'a pod wyszukiwanie -->
<?php

require_once(__DIR__."/pqcms/website/code/Root.php");

$root = new Root(false);
echo $root->generateSiteCode(false);
$root->saveVisit($_SERVER["REMOTE_ADDR"]);