<?php

require_once(dirname(__DIR__)."/classes/Tab.php");
require_once(dirname(__DIR__)."/classes/Text.php");

class Root extends Tab
{
    public function __construct()
    {
        parent::__construct("_root_","",
            [
                "header1",
                "text1",
                "header2",
                "text2",
                "section2header",
                "section21",
                "section22",
                "section23",
                "section24",
                "projectsheader",
                "projects1",
                "projects1info",
                "projects2",
                "projects2info",
                "projects3",
                "projects3info",
                "projects4",
                "projects4info"
            ]
        );
    }

    protected function getSourcePath(bool $editable): string
    {
        if($editable) return "../../../../";
        return "";
    }

    public function generateHtml(bool $editable): string
    {
        $groups = [
            "*",
            "main"
        ];

        if($editable)
        {
            @session_start();
            if(empty($_SESSION["pqcms-panel-auth_key"]))
                return "Najpierw musisz się zalogować!";

            $perms = [];
            foreach ($this->texts as $text)
                $perms[] = "pqcms.site.text.set.$text";
            foreach ($groups as $group) {
                $perms[] = "pqcms.site.group.set.$group";
            }

            require_once(dirname(__DIR__, 2) . "/Communicator.inc.php");
            $hasPermission = (Communicator::communicate(CommunicateURL::HAS_PERMISSION, ["perms" => $perms]));
            $isPermissionSet = (Communicator::communicate(CommunicateURL::IS_PERMISSION_SET, ["perms" => $perms]));

            echo "<pre style='margin-top: 100px;'>";
            var_dump($hasPermission);
            var_dump($isPermissionSet);
            echo "</pre>";

            if(is_null($hasPermission) || $hasPermission["suc"] == 0)
            {
                require_once(dirname(__DIR__,2)."/panel/scripts/notifications/NotificationManager.inc.php");
                NotificationManager::addNewNotification("editor-permissions-communicator-error","Edytor","e",
                    "Wystąpił błąd podczas komunikacji z serwerami PQCMS! (uprawnienia do tekstów mogą działać nieprawidłowo)");
            }
            else if(is_null($isPermissionSet) || $isPermissionSet["suc"] == 0)
            {
                require_once(dirname(__DIR__,2)."/panel/scripts/notifications/NotificationManager.inc.php");
                NotificationManager::addNewNotification("editor-permissions-communicator-error","Edytor","e",
                    "Wystąpił błąd podczas komunikacji z serwerami PQCMS! (uprawnienia do tekstów mogą działać nieprawidłowo)");
            }

            $hasPermission = $hasPermission["perms"];
            $isPermissionSet = $isPermissionSet["perms"];

        }

        $sourcePath = $this->getSourcePath($editable);

        if ($editable)
            foreach ($this->texts as $text) {
                $textObj = Text::getTextByName($text);
//                if($textObj->getGroup() != -1)
                if(!is_null($textObj)) {
//                    pqcms.site.text.set.<id> - if isset
//                    pqcms.site.group.set.<id> - if isset
//                    pqcms.site.group.set.* - if isset
//                    pqcms.site.text.set.<id> - value

                    if($isPermissionSet["pqcms.site.text.set.$text"])
                    {
                        $siteTexts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.text.set.$text"]);
                    }
                    else
                    {
                        $group = $textObj->getGroup();
                        if(!is_null($group) && $group->doesExists())
                        {
                            if($isPermissionSet["pqcms.site.group.set.".$group->getName()])
                                $siteTexts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.group.set.".$group->getName()]);
                            else if($isPermissionSet["pqcms.site.group.set.*"])
                                $siteTexts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.group.set.*"]);
                            else
                                $siteTexts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.text.set.*"]);
                        }
                        else
                            $siteTexts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.text.set.*"]);
                    }


//                        if($isPermissionSet["pqcms.site.text.set.$text"]) {
//                            $siteTexts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.text.set.$text"]);
//                        }
//                        else {
////                        if($textObj->getGroup() !== -1)
//                            $siteTexts[$text] = $textObj->generateHtml($text, true, !$hasPermission["pqcms.site.text.set.$text"]);
//                        }
                }
                else {
                    $siteTexts[$text] = "{PQCMS:[-]}";
                }
            }
        else
            foreach($this->texts as $text) {
                $textObj = Text::getTextByName($text);
                if(!is_null($textObj))
                    $siteTexts[$text] = Text::getTextByName($text)->generateHtml($text, false);
                else
                    $siteTexts[$text] = "";
            }

        $generatedJS = "";
        $panelCSS = "";
        $editableForm = ["",""];
        if($editable)
        {
            $editableForm[0] = "<form method='post' action='ChangeTabText.php' id='pqcms-editor-form'>";
            $editableForm[1] = "</form>";
            $jsonTexts = [];
            foreach($this->texts as $text)
            {
                $textObj = Text::getTextByName($text);
                if(!is_null($textObj))
                    $jsonTexts[$text] = Text::getTextByName($text)->generateHtml($text, false);
                else
                    $jsonTexts[$text] = "";
            }
            $generatedJS = $this->generateJS(json_encode($jsonTexts,JSON_UNESCAPED_UNICODE));
            $panelCSS = "<link rel=\"stylesheet\" href=\"overlay.css\">";
        }

        return <<<HTML
<!-- można dodać plik robots.txt, aby usuwał "podsuwanie" plików pqcms'a pod wyszukiwanie -->
<!doctype html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Strona główna | poleq.pl</title>
    <meta name="description" content="Moje skromne portfolio c:">
    <meta name="keywords" content="pqcms, poleq cms, professional quality cms, poleq, wiktor soliński, wiktor solinski, poleq.pl, pqcms.pl">
    <meta name="author" content='Wiktor "PoLeq" Soliński'>
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="icon" type="image/x-icon" href="${sourcePath}/img/trex.jpg">

    <link rel="stylesheet" href="${sourcePath}bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="${sourcePath}css/default.css">
    <link rel="stylesheet" href="${sourcePath}css/nav.css">
    <link rel="stylesheet" href="${sourcePath}css/main.css">
    <link rel="stylesheet" href="${sourcePath}css/footer.css">

    ${panelCSS}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
  
  	<meta content="poleq.pl" property="og:title" />
    <meta content="Zapraszam do przejrzenia mojej skromnej wizytówki c: Odłączeni od Sieci: https://discord.gg/T6HFxVQjJV" property="og:description" />
    <meta content="https://poleq.pl/" property="og:url" />
    <meta content="https://poleq.pl/img/trex.jpg" property="og:image" />
    <meta content="#6204dd" data-react-helmet="true" name="theme-color" />    
  
  
    <script src="${sourcePath}bs5/js/bootstrap.min.js" defer></script>
    <script src="${sourcePath}js/parallax.min.js" defer></script>
	<script src="https://unpkg.com/scrollreveal@4" defer></script>
    <script src="${sourcePath}js/scroll.js" defer></script>
</head>
<body>

    ${editableForm[0]}
    <nav class="navbar navbar-expand-lg navbar-dark">

        <div class="container-fluid">

            <a class="navbar-brand fs-2 px-3 link-nav" style="font-size: 40px!important;" href="#">poleq.pl</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

                <ul class="navbar-nav mb-2 mb-lg-0 fs-4">

                    <li class="nav-item link-nav">
                        <a class="nav-link" aria-current="page" href="#" style="color: white;">Strona główna</a>
                    </li>
                    
                    <li class="nav-item link-nav">
                        <a class="nav-link" aria-current="page" href="#umiejetnosci" style="color: white;">Umiejętności</a>
                    </li>

                    <li class="nav-item link-nav">
                        <a class="nav-link" href="#projekty" style="color: white;">Projekty</a>
                    </li>

                    <li class="nav-item link-nav">
                        <a class="nav-link" href="#kontakt" style="color: white;">Kontakt</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="big-element row p-5 col-sm-12 offset-1" id="big1">
            <div class="big-element-text m-0 col-lg-5 col-xl-6">
                <div class="scroll400">
                    <h1>${siteTexts["header1"]}</h1>
                    ${siteTexts["text1"]}
                </div>
                <br><br>
                <div class="scroll800">
                    <h1>${siteTexts["header2"]}</h1>
                    ${siteTexts["text2"]}
                </div>
            </div>
            <div class="big-element-img mt-5 mt-lg-0 col-lg-7 col-xl-6" id="svgs1-container">
                <img class="svgs1" id="svg1-1" src="${sourcePath}img/blob1.svg">
                <img class="svgs1" id="svg1-2" src="${sourcePath}img/blob2.svg">
                <img src="${sourcePath}img/trex.jpg" xmlns="http://www.w3.org/2000/svg" id="trex">
            </div>
        </div>
        <hr class="hr-space" id="umiejetnosci">
        <div class="col-12 row container" id="specialty">
            <h2 class="mt-2 mb-5 scroll600" style="margin-bottom: 100px!important;">${siteTexts["section2header"]}</h2>
            
            <div class="sp-element col-5 m-2 m-md-0 col-md-3 p-2 scroll800">
                <div class="sp-img pl-5">
                    <img src="${sourcePath}img/web.png" width="40">
                </div>
                <div class="sp-desc">
                    ${siteTexts["section21"]}
                </div>
            </div>

            <div class="sp-element col-5 m-2 m-md-0 col-md-3 p-2 scroll1000">
                <div class="sp-img">
                    <img src="${sourcePath}img/minecraft.svg" width="40">
                </div>
                <div class="sp-desc">
                    ${siteTexts["section22"]}
                </div>
            </div>
            
            <div class="sp-element col-5 m-2 m-md-0 col-md-3 p-2 scroll1200">
                <div class="sp-img">
                    <img src="${sourcePath}img/discord.svg" width="40">
                </div>
                <div class="sp-desc">
                    ${siteTexts["section23"]}
                </div>
            </div>
            
            <div class="sp-element col-5 m-2 m-md-0 col-md-3 p-2 scroll1400">
                <div class="sp-img pt-1">
                    <img src="${sourcePath}img/coding.png" width="40">
                </div>
                <div class="sp-desc">
                    ${siteTexts["section24"]}
                </div>
            </div>
        </div>

        <div class="wave-container">
            <div class="wave"></div>
        </div>

        <div class="purple-site p-5" id="projekty">
            <div class="scroll200">
                <h1 id="projekty-header">${siteTexts["projectsheader"]}</h1>
            </div>

            <div class="containter row p-lg-5">
                <div class="col-12 col-lg-6 text-center">
                    <div class="pr-element m-3 p-2 my-5">
                        <div class="scroll400"><h2>${siteTexts["projects1"]}</h2></div>
                        <div class="scroll600">
                            ${siteTexts["projects1info"]}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 text-center">
                    <div class="pr-element m-3 p-2 my-5">
                        <div class="scroll800"><h2>${siteTexts["projects2"]}</h2></div>
                        <div class="scroll1000">
                            ${siteTexts["projects2info"]}
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-lg-6 text-center">
                    <div class="pr-element m-3 p-2 my-5">
                        <div class="scroll1200"><h2>${siteTexts["projects3"]}</h2></div>
                        <div class="scroll1400">
                            ${siteTexts["projects3info"]}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 text-center">
                    <div class="pr-element m-3 p-2 my-5">
                        <div class="scroll1600"><h2>${siteTexts["projects4"]}</h2></div>
                        <div class="scroll1800">
                            ${siteTexts["projects4info"]}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="purple-gradient"></div>

        <div class="container scroll600" id="kontakt">
            <h1>Chcesz się ze mną skontaktować?</h1>
            Pozostawiam Ci tutaj parę sposobów, wybierz ten, który najbardziej Ci odpowiada!

            <div class="table-responsive">
                <table class="col-10 m-5">
                    <tr>
                        <th style="min-width: 100px;">Serwer discord</th>
                        <th>Discord (nick+tag)</th>
                        <th>Instagram</th>
                        <th>E-mail</th>
                    </tr>
                    <tr>
                        <td><a href="https://discord.gg/T6HFxVQjJV">Dołącz na serwer!</a></td>
                        <td>!PoLeq#7737 <a href="javascript:void(0);" onclick="copynick()">(kopiuj)</a></td>
                        <td><a href="https://www.instagram.com/poleq__/">poleq__</a></td>
                        <td><a href="mailto:wiktorsolinski123@gmail.com">wiktorsolinski123@gmail.com</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </main>


    <div id="footer-wave-container">
        <div id="footer-wave"></div>
    </div>
    <footer>
        <div class="scroll800">
            poleq.pl &copy Wszelkie prawa zastrzeżone
        </div>
    </footer>
    ${editableForm[1]}

    <!--<div class="website-info" onclick="closeinfo()">
        <h3>W budowie...</h3>
        Strona ta <b>nie jest jeszcze dokończona!</b> Jeżeli masz jakieś uwagi, może i błędy, napisz do mnie śmiało!
        Pomoże mi to w rozwoju tej jakże niesamowitej strony (xD) i siebie.<br>
        <i>Kliknij, aby zamknąć okienko</i>
    </div>-->
    <!-- <a href="https://www.flaticon.com/free-icons/development" title="development icons">Development icons created by Freepik - Flaticon</a> -->

    <!-- <div class="switch_nd" onclick="switch_theme()">
        <img src="img/moon.svg" width="40"></svg>
    </div> -->

    <script src="${sourcePath}js/theme_switcher.js"></script>
    

    <script>
        function copynick() {
            navigator.clipboard.writeText("!PoLeq#7737");
            alert("Nick skopiowany!");
        }
        function closeinfo(){
            document.querySelector(".website-info").style.display="none";
        }
    </script>
    
    ${generatedJS}
</body>
</html>
HTML;

    }
}