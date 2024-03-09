<?php

abstract class Tab
{
    protected string $name;
    protected string $path;
    protected bool $editable;
    protected string $longSourcePath;
    protected array $texts;
    protected array $groups;
    protected int $folderDepth;

    public function __construct(string $name, string $path, bool $editable, string $longSourcePath, int $folderDepth, array $texts = [], array $groups = [])
    {
        $this->name = $name;
        $this->path = $path;
        $this->editable = $editable;
        $this->longSourcePath = $longSourcePath;
        $this->folderDepth = $folderDepth;

        $this->texts = $texts;
        $this->texts[] = "*";

        $this->groups = $groups;
        $this->groups[] = "*";

        if($editable)
        {
            $perms = [];
            foreach ($this->texts as $text)
                $perms[] = "pqcms.site.text.set.$text";
            foreach ($this->groups as $group)
                $perms[] = "pqcms.site.group.set.$group";

            require_once(dirname(__DIR__, 2) . "/Communicator.inc.php");
            $hasPermission = (Communicator::communicate(CommunicateURL::HAS_PERMISSION, ["perms" => $perms]));
            $isPermissionSet = (Communicator::communicate(CommunicateURL::IS_PERMISSION_SET, ["perms" => $perms]));

            if($hasPermission["suc"] == 0)
            {
                require_once(dirname(__DIR__,2)."/panel/scripts/notifications/NotificationManager.inc.php");
                NotificationManager::addNewNotification("editor-permissions-communicator-error","Edytor","e",
                    "Wystąpił błąd podczas komunikacji z serwerami PQCMS!");
                die();
            }
            else if($isPermissionSet["suc"] == 0)
            {
                require_once(dirname(__DIR__,2)."/panel/scripts/notifications/NotificationManager.inc.php");
                NotificationManager::addNewNotification("editor-permissions-communicator-error","Edytor","e",
                    "Wystąpił błąd podczas komunikacji z serwerami PQCMS!");
                die();
            }

            $hasPermission = $hasPermission["perms"];
            $isPermissionSet = $isPermissionSet["perms"];

            foreach ($this->texts as $text) {
                $textObj = Text::getTextByName($text);
                if(!is_null($textObj)) {
//                    pqcms.site.text.set.<id> - if isset
//                    pqcms.site.group.set.<id> - if isset
//                    pqcms.site.group.set.* - if isset
//                    pqcms.site.text.set.<id> - value
                    if($isPermissionSet["pqcms.site.text.set.$text"])
                        $this->texts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.text.set.$text"]);
                    else
                    {
                        $group = $textObj->getGroup();
                        if(!is_null($group) && $group->doesExists())
                        {
                            if($isPermissionSet["pqcms.site.group.set.".$group->getName()])
                                $this->texts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.group.set.".$group->getName()]);
                            else if($isPermissionSet["pqcms.site.group.set.*"])
                                $this->texts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.group.set.*"]);
                            else
                                $this->texts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.text.set.*"]);
                        }
                        else
                            $this->texts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.text.set.*"]);
                    }
                }
                else
                    $this->texts[$text] = "{PQCMS:[-]}";
            }
        }
        else
        {
            foreach($this->texts as $text) {
                $textObj = Text::getTextByName($text);
                if(!is_null($textObj))
                    $this->texts[$text] = Text::getTextByName($text)->generateHtml($text, false);
                else
                    $this->texts[$text] = "";
            }
        }
    }

    protected function getSourcePath(): string
    {
        if($this->editable)
            return $this->longSourcePath;
        return "";
    }

//    todo może do pliku i po prostu odczyt z pliku??
    protected function generateJS(array $jsonTexts): string
    {
        $jsonTexts = json_encode($jsonTexts,JSON_UNESCAPED_UNICODE);

        return<<<JS
<script>
    
    {
        const texts = $jsonTexts;
        let changes = [];
        
        for(let key in texts)
            changes[key] = false;
        
        const saveButton = createUpdateButton();
        document.querySelector("#pqcms-editor-form").appendChild(saveButton);
        
        function createUpdateButton() 
        {
            const update = document.createElement("input");
            update.type = "submit";
            update.id = "pqcms-saveButton";
            update.innerText = "Zapisz";

            update.addEventListener("click",(event) => 
            {
                event.preventDefault();
                
                let postChanges = [];
                for(let key in changes)
                    if(changes[key])
                    {
                        const inputField = document.querySelector("#pqcms-editable-textarea-"+key);
                        inputField.name = key;
                    }
                
                document.querySelector("#pqcms-editor-form").submit();
            });
            
            return update;
        }
        
        function hideUpdateButton() 
        {
            saveButton.style.opacity = "0";
            setTimeout(() => {
                saveButton.style.visilibity = "hidden";
            },1000);
        }
        
        function showUpdateButton() 
        {
            saveButton.style.visibility = "visible";
            saveButton.style.opacity = "1";
        }
        
        function isChanged() 
        {
            for(let key in changes)
                if(changes[key])
                    return true;
            return false;
        }
        
        document.querySelectorAll("textarea.pqcms-editable-textarea").forEach((e) => 
        {
            e.addEventListener("input",(event) => 
            {
                const key = event.target.id.substring(24,event.target.id.length);
                if(texts[key] === event.target.value) changes[key] = false;
                else changes[key] = true;
                    
                if(isChanged()) showUpdateButton();
                else hideUpdateButton();
            });
        });
    }
</script>
JS;
    }

    public final function generateSiteCode(string $lang = "pl"): string
    {
        if($this->editable)
        {
            @session_start();
            if(empty($_SESSION["pqcms"]["panel"]["auth_key"]))
                return "Najpierw musisz się zalogować!";
        }

        $head = $this->generateHeadCode($this->folderDepth);
        $body = $this->generateBodyCode($this->folderDepth);

        $sourcePath = $this->getSourcePath();

        $head .= <<<HTML
<link rel="stylesheet" href="${sourcePath}pqcms/pqcms.css">
<script src="${sourcePath}pqcms/pqcms.js" defer></script>
HTML;

        if($this->editable)
        {
            $head .= <<<HTML
<link rel="stylesheet" href="overlay.css">
HTML;

            $body = <<<HTML
<form method='post' action='ChangeTabText.php' id='pqcms-editor-form'>
$body
</form>
HTML;
            $body .= $this->generateJS($this->texts);
        }

        $addonsHead = "";
        $addonsBody = "";
        require_once(dirname(__DIR__,2)."/addons/AddonManager.inc.php");
        /** @var $addon Addon */
        foreach((new AddonManager())->getEnabledAddons() as $addon)
        {
            $websiteAddon = $addon->getWebsiteAddon();
            if(is_null($websiteAddon))
                continue;

            $id = $addon->getId();
            $addonHead = $websiteAddon->onWebsiteHeadLoaded($this->folderDepth);
            $addonBody = $websiteAddon->onWebsiteBodyLoaded($this->folderDepth);

            if(!empty($addonHead))
                $addonsHead .= <<<HTML
<!-- PQCMS "$id" addon -->
$addonHead
HTML;
            if(!empty($addonBody))
                $addonsBody .= <<<HTML
<!-- PQCMS "$id" addon -->
$addonBody
HTML;
        }

        return<<<HTML
<!DOCTYPE html>
<html lang="$lang">
<head>
    $head
    $addonsHead
</head>
<body>
    $body
    $addonsBody
</body>
</html>
HTML;
    }

    public abstract function generateHeadCode(): string;
    public abstract function generateBodyCode(): string;

    public function saveVisit(string $remoteAddr): void
    {
        require_once(dirname(__DIR__)."/TabsCounter.inc.php");
        $tabsCounter = new TabsCounter();
        $tabsCounter->saveVisit($this->name,$remoteAddr);
        $tabsCounter->close();
    }
}