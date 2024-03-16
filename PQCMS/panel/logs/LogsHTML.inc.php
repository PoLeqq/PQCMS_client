<?php

class LogsHTML
{
    private array $links;

    public function __construct()
    {
        $this->links = [
            "site" => [
                "header" => "Dziennik zdarzeń",
                "description" => "Logi serwerowe. Logowania (udane/nieudane), zmiana: tekstu, ustawień,
                dodawanie/edycja rang/użytkowników itp."
            ],
            "stats" => [
                "header" => "Statystyki",
                "description" => "Wejścia na stronę (podział na sumę oraz unikalne wejścia dnia). Wgląd 
                na lokalizację gości, którzy znaleźli się na Twojej stronie."
            ]
        ];
    }

    public function getLogsButtons(): string
    {
        $html = "";
        foreach($this->links as $link => $text)
        {
            $html .= <<<HTML
<div class="col-10 p-5 col-lg-3 offset-glg-0 log-type-button d-flex flex-column justify-content-center align-items-center" id="pqcms-log-type-button-$link">
    <h3 class="mb-3">${text["header"]}</h3>
    <span>
        ${text["description"]}    
    </span>
</div>
HTML;
        }
        return $html;
    }

    public function getLogsScripts(): string
    {
        $js = "<script defer>";
        foreach($this->links as $link => $text)
        {
            $js .= <<<JS
document.querySelector("#pqcms-log-type-button-$link").addEventListener("click",() => {
    window.location.href = "$link/";
});
JS;
        }
        $js .= "
</script>";
        return $js;
    }
}