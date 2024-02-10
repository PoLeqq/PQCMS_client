<?php

if(empty($_POST["client"]))
    die("Musisz podać imię i nazwisko!");
if(empty($_POST["email"]))
    die("Musisz podać adres e-mail!");
if(empty($_POST["title"]))
    die("Musisz podać tytuł wiadomości!");
if(empty($_POST["message"]))
    die("Musisz podać treść wiadomości!");

require_once(dirname(__DIR__)."/classes/Form.php");
$form = Form::getForm("contact");

if(is_null($form))
    die("Błąd. Nie odnaleziono formularza!");

if(!$form->isEnabled())
    die("Ten formularz jest wyłączony!");

if(!$form->addRow(["client" => $_POST["client"],"email" => $_POST["email"], "title" => $_POST["title"], "message" => $_POST["message"]]))
    die("Wystąpił błąd podczas przesyłania formularza!");

die("Przesłano formularz!");
