<?php

require_once(dirname(__DIR__,3)."/classes/AddonWebsite.inc.php");
class PQCMSClientBotWebsite extends AddonWebsite
{
    public function onWebsiteHeadLoaded(int $folderDepth): ?string
    {
        $dir = $this->addon->getRelativePathFromWebsiteToAddon($folderDepth);

        return<<<HTML
<link rel="stylesheet" href="$dir/website/style.css"/>
<style>

.pqcms-clientbot-bot-message-bot-icon::after {
    background: url("${dir}/images/icon.svg") no-repeat;
    top: 5px;
    left: 5px;

    width: 30px;
    height: 30px;
}

.pqcms-clientbot-bot-message-user-icon::after {
    background: url("${dir}/images/person.svg") no-repeat;
    top: 8px;
    left: 8px;

    width: 24px;
    height: 24px;
}
</style>
HTML;
    }

    /**
     * Funkcja wywoływana na stronie widocznej dla klienta
     * @param int $folderDepth
     * @return ?string tekst, który będzie wyświetlony na stronie w sekcji <i>&lt;body&gt;</i> (np. HTML, JS)
     */
    public function onWebsiteBodyLoaded(int $folderDepth): ?string
    {
        $dir = $this->addon->getRelativePathFromWebsiteToAddon($folderDepth);

        try {
            $config = $this->addon->getConfig();
        } catch (Exception $e) {
            return<<<HTML
$e
HTML;
        }
        if(!isset($config["bot_name"]))
            $name = "Wirtualny asystent";
        else
            $name = $config["bot_name"];

        $startMessages = "";
        foreach($config["start_messages"] as $cfgStartMessage)
        {
            if(!is_array($cfgStartMessage))
                continue;

            $msg = $cfgStartMessage[array_rand($cfgStartMessage)];

            $startMessages .= <<<HTML
<div class="pqcms-clientbot-bot-message-bot">
    <div class="pqcms-clientbot-bot-message-bot-icon"></div>
    <div class="pqcms-clientbot-bot-message-bot-content">
        $msg
    </div>
</div>
HTML;
        }


        return<<<HTML
<div id="pqcms-clientbot" class="col-12 col-md-8 col-lg-5 col-xl-3" data-expand="false">
    <div id="pqcms-clientbot-expander">
        <img class="img-fluid" src="$dir/images/icon.svg" alt="BOT"/>
    </div>
    <div id="pqcms-clientbot-bot" class="d-flex flex-column justify-content-between">
        <header>
            $name
        </header>
        <div id="pqcms-clientbot-bot-conversation" class="p-3">
            $startMessages   
        </div>
        <div id="pqcms-clientbot-options" class="d-flex flex-wrap align-items-center justify-content-center gap-3 py-3"></div>
    </div>
</div>

<input type="hidden" id="pqcms-clientbot-data-dir" value="$dir"/>

<script src="$dir/website/script.js" type="module"></script>
HTML;

    }
}