<?php

require_once("../scripts/server/TabUtils.inc.php");
require_once("Perms.php");
require_once(dirname(__DIR__,2)."/config/data/JSONForms.php");

$forms = new JSONForms();
$verify = TabUtils::verifyUser("forms",(new FormsPerms($forms->getForms()))->getPerms());

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../default.css">
    <link rel="stylesheet" href="forms.css">

    <title>PQCMS - Formularze</title>
</head>
<body>
<div class="p-4">
<?php

if(!empty($forms->getForms()))
{
    foreach($forms->getForms() as $form)
    {
        if(!$verify["perms"]["pqcms.forms.".$form->getId()])
            continue;

        $tbody = "";
        $thead = "";

        foreach($form->getCols() as $col)
            $thead .= <<<HTML
<th>
    $col
</th>
HTML;
            $thead .= <<<HTML
<th>
    Data
</th>
HTML;

        foreach($form->getRows() as $row)
        {
            $rowData = json_decode($row["data"],true);

            $tbody .= "<tr>";
            foreach($form->getCols() as $colId => $colName)
            {
                if(in_array($colId,array_keys($rowData)))
                   $tbody .= <<<HTML
<td>
    ${rowData[$colId]}
</td>
HTML;
                else
                    $tbody .= "<td></td>";

//                else
            }
//            foreach(json_decode($row["data"]) as $rowCol => $rowVal)
//                $tbody .= <<<HTML
//<td>
//    $rowVal
//</td>
//HTML;
            $tbody .= <<<HTML
<td>
    ${row["date"]}
</td>
HTML;
            $tbody .= "</tr>";
        }

        $formName = $form->getName();
        $formId = $form->getId();
        echo<<<HTML
<h1 style="margin-bottom: 0;">$formName</h1>
<i>$formId</i>
<table style="margin-top: 15px">
    <thead>
        $thead
    </thead>
    <tbody>
        $tbody
    </tbody>
</table>
HTML;
    }
}
else
    echo<<<HTML
Nie ma żadnych formularzy do wyświetlenia.
HTML;





?>
</div>
</body>
</html>