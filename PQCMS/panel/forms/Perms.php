<?php

require_once(dirname(__DIR__)."/TabPerms.php");

class FormsPerms extends TabPerms
{
    public function __construct(array $forms)
    {
        $formsPermsNames = [];
        foreach ($forms as $form)
            $formsPermsNames[] = "pqcms.forms.".$form->getId();

        parent::__construct($formsPermsNames);
    }
}