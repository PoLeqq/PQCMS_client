<?php

class Website
{
    private array $tabs;

    public function __construct()
    {
        require_once(dirname(__DIR__)."/code/Root.php");
//        require_once(dirname(__DIR__)."/code/Contact.php");
        $this->tabs["_root_"] = new Root(true);
//        $this->tabs["kontakt"] = new Contact();
    }

    public function getTab(string $name): ?Tab
    {
        if(!isset($this->tabs[$name])) return null;
        return $this->tabs[$name];
    }

    public function getTabs(): array
    {
        return $this->tabs;
    }
}