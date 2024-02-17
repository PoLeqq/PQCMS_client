<?php

class LocalPermissions
{
    public static function getLocalWebsitePermissions(): array
    {
        $perms = [];

        require_once(dirname(__DIR__,2)."/config/data/JSONForms.php");
        $forms = new JSONForms();
        foreach($forms->getForms() as $form)
        {
            $formId = $form->getId();
            $perms[] = [
                "perm" => "pqcms.forms.$formId",
                "description" => "Dostęp do danych formularza ".$form->getName()." ($formId)"
                ];
        }

        require_once(dirname(__DIR__)."/database/Database.inc.php");
        $conn = Database::getConnection();
        if(is_null($conn))
            return [];
        $result = $conn->query("SELECT name FROM pqcms_site_text_group");
        while($row = $result->fetch_row())
        {
            $perms[] = [
                "perm" => "pqcms.site.group.set.".$row[0],
                "description" => "Dostęp do edycji grupy ${row[0]}"
            ];;
        }

        $result->free_result();

        $result = $conn->query("SELECT name FROM pqcms_site_text");
        while($row = $result->fetch_row())
            $perms[] = [
                "perm" => "pqcms.site.text.set.".$row[0],
                "description" => "Dostęp do edycji tekstu ${row[0]}"
            ];

        $result->close();
        $conn->close();

        return $perms;
    }
}