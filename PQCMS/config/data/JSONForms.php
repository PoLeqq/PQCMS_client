<?php

require_once(dirname(__DIR__)."/JSONObject.php");
include_once(dirname(__DIR__,2)."/website/classes/Form.php");

/**
 * Przedstawia dział "database" w pqcms/config/data/data.json - dane o bazie danych
 */
class JSONForms extends JSONObject
{
    function __construct()
    {
        parent::__construct("forms","/files/data.json");
    }

    /**
     * Zwraca listę formularzy
     */
    public function getForms(): array
    {
        $forms = [];
        foreach ($this->data as $form)
            $forms[] = new Form($form["id"],$form["name"],$form["enabled"],$form["cols"]);
        return $forms;
    }

    /**
     * Zwraca formularz o konkretnej nazwie
     */
    public function getForm(string $id): ?Form
    {
        foreach($this->data as $form)
            if($form["id"] === $id)
                return new Form($form["id"], $form["name"], $form["enabled"], $form["cols"]);

        return null;
    }

    public function doesFormExists(string $name): bool
    {
        foreach($this->getForms() as $form)
            if($form["name"] === $name)
                return true;

        return false;
    }
}