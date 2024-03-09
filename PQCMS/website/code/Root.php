<?php

require_once(dirname(__DIR__)."/classes/Tab.php");
require_once(dirname(__DIR__)."/classes/Text.php");

class Root extends Tab
{
    public function __construct($editable)
    {
        parent::__construct("_root_","",$editable,"../../../../",0,
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
            ],
            ["main"]
        );
    }

    public function generateHeadCode(): string
    {
        $sourcePath = $this->getSourcePath();

        return<<<HTML
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

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
HTML;
    }

    public function generateBodyCode(): string
    {
        $sourcePath = $this->getSourcePath();
        $siteTexts = $this->texts;

        return<<<HTML
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
                ${siteTexts["header1"]}
                ${siteTexts["text1"]}
            </div>
            <br><br>
            <div class="scroll800">
                ${siteTexts["header2"]}
                ${siteTexts["text2"]}
            </div>
        </div>
        <div class="big-element-img mt-5 mt-lg-0 col-lg-7 col-xl-6" id="svgs1-container">
            <img class="svgs1" id="svg1-1" src="${sourcePath}img/blob1.svg">
            <img class="svgs1" id="svg1-2" src="${sourcePath}img/blob2.svg">
            <img src="${sourcePath}img/trex.jpg" id="trex">
        </div>
    </div>
    <hr class="hr-space" id="umiejetnosci">
    <div class="col-12 row container" id="specialty">
        <span class="mt-2 mb-5 scroll600" style="margin-bottom: 100px!important;">${siteTexts["section2header"]}</span>
        
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
            <span id="projekty-header">${siteTexts["projectsheader"]}</span>
        </div>

        <div class="containter row p-lg-5">
            <div class="col-12 col-lg-6 text-center">
                <div class="pr-element m-3 p-2 my-5">
                    <div class="scroll400">${siteTexts["projects1"]}</div>
                    <div class="scroll600">
                        ${siteTexts["projects1info"]}
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 text-center">
                <div class="pr-element m-3 p-2 my-5">
                    <div class="scroll800">${siteTexts["projects2"]}</div>
                    <div class="scroll1000">
                        ${siteTexts["projects2info"]}
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-6 text-center">
                <div class="pr-element m-3 p-2 my-5">
                    <div class="scroll1200">${siteTexts["projects3"]}</div>
                    <div class="scroll1400">
                        ${siteTexts["projects3info"]}
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 text-center">
                <div class="pr-element m-3 p-2 my-5">
                    <div class="scroll1600">${siteTexts["projects4"]}</div>
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
        <i>Jeśli szukasz kontaktu do projektu <a href="projekty/pqcms/">PQCMS</a>, kliknij w ten odnośnik</i>

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
                    <td>poleq</td>
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

<script src="${sourcePath}js/theme_switcher.js"></script>
HTML;
    }
}