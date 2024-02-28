<?php

class Website
{
    private array $tabs;

    public function __construct()
    {
        require_once(dirname(__DIR__,2)."/config/data/JSONTabs.php");
        $tabs = new JSONTabs();
        foreach($tabs->getTabs() as $class => $name)
        {
            require_once(dirname(__DIR__)."/code/$class.php");
            $this->tabs["$class"] = new $class(true);
        }
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