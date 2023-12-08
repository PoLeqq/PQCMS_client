<?php

require_once(dirname(__DIR__)."/PQCMS/website/code/Contact.php");

$site = new Contact();
echo $site->generateHtml(false);