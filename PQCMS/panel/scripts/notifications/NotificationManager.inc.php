<?php

@session_start();

class NotificationManager
{
    private string $id;
    private string $title;

    public function __construct(string $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public static function removeNotification(string $id): void
    {
        unset($_SESSION["pqcms"]["panel"]["notifications"][$id]);
    }

    public function addNotification(string $type, string $text): void
    {
        $_SESSION["pqcms"]["panel"]["notifications"][$this->id]["title"] = $this->title;
        $_SESSION["pqcms"]["panel"]["notifications"][$this->id]["type"] = $type;
        $_SESSION["pqcms"]["panel"]["notifications"][$this->id]["text"] = $text;
    }

    public static function addNewNotification(string $id, string $title, string $type, string $text): void
    {
        $id = uniqid($id."-");
        $_SESSION["pqcms"]["panel"]["notifications"][$id]["title"] = $title;
        $_SESSION["pqcms"]["panel"]["notifications"][$id]["type"] = $type;
        $_SESSION["pqcms"]["panel"]["notifications"][$id]["text"] = $text;
    }
}