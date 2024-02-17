<?php

require_once(dirname(__DIR__)."/PQCMS/website/code/Contact.php");

$site = new Contact(false);
echo $site->generateSiteCode(false);