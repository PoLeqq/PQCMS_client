<?php

require_once(dirname(__DIR__,3)."/classes/AddonWebsite.inc.php");
class PQCMSClientBotWebsite extends AddonWebsite
{
    public function onWebsiteHeadLoaded(int $folderDepth): ?string
    {
        $css = file_get_contents(__DIR__."/style.css");
        $dir = $this->addon->getRelativePathFromWebsiteToAddon($folderDepth);

        return<<<HTML
<style>
$css

.pqcms-clientbot-bot-message-bot-icon::after {
    background: url("${dir}/images/icon.svg") no-repeat;
    top: 5px;
    left: 5px;

    width: 40px;
    height: 40px;
}

.pqcms-clientbot-bot-message-user-icon::after {
    background: url("${dir}/images/person.svg") no-repeat;
    top: 8px;
    left: 8px;

    width: 34px;
    height: 34px;
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
        $js = file_get_contents(__DIR__."/script.js");
        $dir = $this->addon->getRelativePathFromWebsiteToAddon($folderDepth);

        $config = $this->addon->getConfig();
        $name = $config["bot_name"];

//        $startMessages =

        return<<<HTML
<div id="pqcms-clientbot" class="col-12 col-md-8 col-lg-5 col-xl-3" data-expand="false">
    <div id="pqcms-clientbot-expander">
        <img class="img-fluid" src="$dir/images/icon.svg" alt="BOT"/>
    </div>
    <div id="pqcms-clientbot-bot" class="d-flex flex-column justify-content-between">
        <header>
            $name
        </header>
        <div id="pqcms-clientbot-bot-conversation" class="p-3"></div>
        <div id="pqcms-clientbot-options" class="d-flex flex-wrap align-items-center justify-content-center gap-3 py-3"></div>
    </div>
</div>

<script>

function getResponse(value)
{
    disableAllResponses();
    
    fetch("pqcms/addons/addons/pqcmsclientbot/system/GetResponse.php", {
        method: "POST",
        headers: {
            "Content-Type" : "application/json",
        },
        body : JSON.stringify(value)
    }).then((resp) => {
        resp.clone().text().then(console.log);
        
        resp.json().then((json) => {
            setTimeout(() => {
                addMessageBox("bot",json["suc"],json["msg"]);
            
                if(json["suc"] === 1)
                {
                    const options = document.querySelector("#pqcms-clientbot-options");
                    options.innerHTML = "";
                     
                    json["resp"].forEach(e => {
                        const option = getAnswerResponseBox(e.text);
                        option.addEventListener("click", () => {
                            if(option.hasAttribute("disabled"))
                                return;
                            onAnswerResponseBotClick(e);
                        });
                    
                        options.appendChild(option);
                    })           
                }
                else
                    enableAllResponses();
            },1000);
        }).catch((err) => {
            console.error(err);
            enableAllResponses();
        });
    }).catch((err) => {
        console.error(err);
        enableAllResponses();
    });
}

// setTimeout(() => {getResponse("default")},1000);
getResponse("default");

$js
</script>
HTML;

    }
}