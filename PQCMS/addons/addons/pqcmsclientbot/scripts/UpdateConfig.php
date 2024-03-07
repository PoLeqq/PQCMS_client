<?php


$starRedirectRate = &$_POST["star-redirect-rate"];
$redirectURL = &$_POST["redirect-url"];
$redirectNewPage = &$_POST["redirect-new-page"];

if(empty($starRedirectRate))
    die("Liczba gwiazdek jest wymagana!");
else
{
    $starRedirectRate = filter_var($starRedirectRate, FILTER_VALIDATE_INT);
    if(is_null($starRedirectRate))
        die("Liczba gwiazdek musi być liczbą!");
    if($starRedirectRate < 1 || $starRedirectRate > 5)
        die("Liczba gwiazdek musi być z przedziału 1-5!");
}

if(empty($redirectURL) || !filter_var($redirectURL, FILTER_VALIDATE_URL))
    die("Link jest niepoprawny!");

if(!isset($redirectNewPage))
    die("Opcja \"nowe okno\" jest wymagana!");
else
{
    $redirectNewPage = filter_var($redirectNewPage, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    if(is_null($redirectNewPage))
        die("Opcja \"nowe okno\" powinna być wartością prawda/fałsz!");
}

require(dirname(__DIR__)."/PQCMSStarSystem.inc.php");
$addon = new PQCMSStarSystem();
$addon->setConfig([
    "star-redirect-rate" => $starRedirectRate,
    "redirect-url" => $redirectURL,
    "redirect-new-page" => $redirectNewPage
    ]);

$path = $addon->getRelativePathFromAddonToPanel();
header("location: ../$path");