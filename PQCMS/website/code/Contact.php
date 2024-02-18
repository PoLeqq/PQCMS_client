<?php

require_once(dirname(__DIR__)."/classes/Tab.php");
require_once(dirname(__DIR__)."/classes/Text.php");

class Contact extends Tab
{
    public function __construct(bool $editable)
    {
        parent::__construct("kontakt","kontakt","../../../../",$editable);
    }

    public function generateHeadCode(string $lang = "pl"): string
    {
        $sourcePath = $this->getSourcePath();

        return<<<HTML
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>Kontakt | poleq.pl</title>
<meta name="description" content="Moje skromne portfolio - kontakt c:">
<meta name="author" content='Wiktor "PoLeq" Soliński'>
<meta http-equiv="X-Ua-Compatible" content="IE=edge">

<link rel="icon" type="image/x-icon" href="$sourcePath/img/trex.jpg">

<link rel="stylesheet" href="${sourcePath}bs5/css/bootstrap.min.css">
<link rel="stylesheet" href="${sourcePath}css/default.css">
<link rel="stylesheet" href="${sourcePath}css/nav.css">
<link rel="stylesheet" href="${sourcePath}css/main.css">
<link rel="stylesheet" href="${sourcePath}css/footer.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">

<meta content="poleq.pl" property="og:title" />
<meta content="Zapraszam do przejrzenia mojej skromnej wizytówki c: Odłączeni od Sieci: https://discord.gg/T6HFxVQjJV" property="og:description" />
<meta content="https://poleq.pl/" property="og:url" />
<meta content="https://poleq.pl/img/trex.jpg" property="og:image" />
<meta content="#6204dd" data-react-helmet="true" name="theme-color" />
HTML;

    }

    public function generateBodyCode(string $lang = "pl"): string
    {
        //        $absolutePath = "https://poleq.pl/";
        $absolutePath = "https://localhost/pqcmsclient/pqcms/";

        return<<<HTML
<h1>Kontakt</h1>
    
<form class="col-6 d-flex flex-column gap-3" method="post" action="${absolutePath}website/forms/Contact.php">
    <input name="client" placeholder="Imię i Nazwisko"/>
    <input type="email" name="email" placeholder="E-mail"/>
    <input name="title" placeholder="Tytuł"/>
    <textarea name="message" placeholder="Wiadomość"></textarea>
    <input type="submit" value="Prześlij!"/>
</form>
HTML;

    }
}