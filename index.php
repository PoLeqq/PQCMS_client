<!-- można dodać plik robots.txt, aby usuwał "podsuwanie" plików pqcms'a pod wyszukiwanie -->
<?php

require_once(__DIR__."/PQCMS/website/code/Root.php");

$root = new Root();
echo $root->generateHtml(false);

?>